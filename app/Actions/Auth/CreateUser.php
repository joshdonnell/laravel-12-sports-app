<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use SensitiveParameter;

final readonly class CreateUser
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes, #[SensitiveParameter] string $password, int $sport): User
    {
        $user = User::query()->create([
            ...$attributes,
            'password' => $password,
            'sport_id' => $sport,
        ]);

        event(new Registered($user));

        return $user;
    }
}
