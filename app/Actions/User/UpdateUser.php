<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use SensitiveParameter;

final readonly class UpdateUser
{
    public function __construct(private DetachClientsMismatchingSport $detachClientsMismatchingSport) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(User $user, array $attributes, #[SensitiveParameter] ?string $password, ?Role $role): void
    {
        if ($password !== null) {
            $attributes['password'] = $password;
        }

        $sportChanged = array_key_exists('sport_id', $attributes) && $user->sport_id !== $attributes['sport_id'];

        DB::transaction(function () use ($user, $attributes, $role, $sportChanged): void {
            $user->update($attributes);

            if ($role instanceof Role) {
                $user->syncRoles($role);
            }

            if ($sportChanged) {
                $this->detachClientsMismatchingSport->handle($user);
            }
        });
    }
}
