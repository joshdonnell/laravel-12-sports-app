<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\Sport;
use App\Models\User;

beforeEach(function (): void {
    $this->seed(Database\Seeders\RoleAndPermissionSeeder::class);
});

test('index', function (): void {
    $user = User::factory()->create();

    expect($user->can('index', $user))->toBeFalse();

    $user->assignRole(Role::SuperAdmin);

    expect($user->can('index', $user))->toBeTrue();
});

test('create', function (): void {
    $user = User::factory()->create();

    expect($user->can('create', $user))->toBeFalse();

    $user->assignRole(Role::SuperAdmin);

    expect($user->can('create', $user))->toBeTrue();
});

test('store', function (): void {
    $user = User::factory()->create();

    expect($user->can('store', $user))->toBeFalse();

    $user->assignRole(Role::SuperAdmin);

    expect($user->can('store', $user))->toBeTrue();
});

test('edit', function (): void {
    $sport = Sport::factory()->create();
    $user = User::factory()->create([
        'sport_id' => $sport->id,
    ]);
    $userToEdit = User::factory()->create();

    expect($user->can('edit', $userToEdit))->toBeFalse();

    $user->assignRole(Role::SuperAdmin);

    expect($user->can('edit', $userToEdit))->toBeTrue();

    $user->syncRoles([Role::Admin]);

    expect($user->can('edit', $userToEdit))->toBeFalse();

    $userToEdit->update([
        'sport_id' => $sport->id,
    ]);

    expect($user->can('edit', $userToEdit))->toBeTrue();
});

test('update', function (): void {
    $sport = Sport::factory()->create();
    $user = User::factory()->create([
        'sport_id' => $sport->id,
    ]);
    $userToEdit = User::factory()->create();

    expect($user->can('update', $userToEdit))->toBeFalse();

    $user->assignRole(Role::SuperAdmin);

    expect($user->can('update', $userToEdit))->toBeTrue();

    $user->syncRoles([Role::Admin]);

    expect($user->can('update', $userToEdit))->toBeFalse();

    $userToEdit->update([
        'sport_id' => $sport->id,
    ]);

    expect($user->can('update', $userToEdit))->toBeTrue();
});

test('destroy', function (): void {
    $sport = Sport::factory()->create();
    $user = User::factory()->create([
        'sport_id' => $sport->id,
    ]);
    $userToEdit = User::factory()->create();

    expect($user->can('destroy', $userToEdit))->toBeFalse();

    $user->assignRole(Role::SuperAdmin);

    expect($user->can('destroy', $userToEdit))->toBeTrue();

    $user->syncRoles([Role::Admin]);

    expect($user->can('destroy', $userToEdit))->toBeFalse();
});
