<?php

declare(strict_types=1);

use App\Actions\User\UpdateUser;
use App\Enums\Role;
use App\Models\Sport;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

it('updates a user', function (): void {
    $user = User::factory()->create();
    $sport = Sport::factory()->create();
    $action = resolve(UpdateUser::class);

    $action->handle($user, [
        'name' => 'Updated User',
        'email' => 'updated@email.com',
        'sport_id' => $sport->id,
    ], null, Role::Admin);

    expect($user->name)->toBe('Updated User')
        ->and($user->email)->toBe('updated@email.com')
        ->and($user->sport_id)->toBe($sport->id)
        ->and($user->hasRole(Role::Admin))->toBeTrue();
});
