<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use SensitiveParameter;

final readonly class CreateUser
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes, #[SensitiveParameter] string $password, Role $role): User
    {
        return DB::transaction(function () use ($attributes, $password, $role) {
            $user = User::query()->create([
                ...$attributes,
                'password' => $password,
            ]);

            $user->assignRole($role);

            event(new Registered($user));

            return $user;
        });
    }
}
