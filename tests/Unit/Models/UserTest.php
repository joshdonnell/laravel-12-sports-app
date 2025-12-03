<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\Client;
use App\Models\Scopes\SportScope;
use App\Models\Sport;
use App\Models\User;

beforeEach(function (): void {
    $this->seed(Database\Seeders\RoleAndPermissionSeeder::class);
});

test('the user model structure matches', function (): void {
    $user = User::factory()->create()->refresh();

    expect(array_keys($user->toArray()))
        ->toBe([
            'id',
            'uuid',
            'name',
            'email',
            'email_verified_at',
            'two_factor_confirmed_at',
            'sport_id',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
});

test('a user belongs to many clients', function (): void {
    $user = User::factory()->hasClients(3)->create()->refresh();

    expect($user->clients)->toHaveCount(3)
        ->and($user->clients->first())->toBeInstanceOf(Client::class);
});

test('a user belongs to a sport', function (): void {
    $user = User::factory()->create()->refresh();

    expect($user->sport)->toBeInstanceOf(Sport::class);
});

test('a super admin user can see all users', function (): void {
    $user = User::factory()->create();
    $user->syncRoles([Role::SuperAdmin]);
    User::factory(3)->create();

    $this->actingAs($user);

    $users = User::query()->withoutGlobalScope(SportScope::class)->get();

    expect($users)->toHaveCount(4);
});

test('a admin user can only see users with a lower role than themself', function (): void {
    $user = User::factory()->create();
    $user->syncRoles([Role::Admin]);
    $superAdmins = User::factory(3)->create();

    foreach ($superAdmins as $superAdmin) {
        $superAdmin->syncRoles([Role::SuperAdmin]);
    }

    $this->actingAs($user);

    $users = User::query()->usersWithLowerRole()->withoutGlobalScope(SportScope::class)->get();

    expect($users)->toHaveCount(1);
});

test('a editor and default users can not see any user', function (): void {
    User::factory(3)->create();
    $user = User::factory()->create();
    $user->syncRoles([Role::Editor]);

    $this->actingAs($user);

    $users = User::query()->usersWithLowerRole()->withoutGlobalScope(SportScope::class)->get();

    expect($users)->toHaveCount(0);
});

test('a users own model is not returned when using the excludeCurrentUser scope', function (): void {
    User::factory(3)->create();
    $user = User::factory()->create();

    $this->actingAs($user);

    $users = User::query()->excludeCurrentUser()->withoutGlobalScope(SportScope::class)->get();

    expect($users)->toHaveCount(3)
        ->and($users->pluck('id'))->not->toContain($user->id);
});
