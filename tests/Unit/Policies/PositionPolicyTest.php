<?php

declare(strict_types=1);

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Position;
use App\Models\Sport;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

test('index', function (): void {
    $user = User::factory()->create();

    expect($user->can('index', Position::class))->toBeFalse();

    $user->givePermissionTo(Permission::LIST_POSITIONS);

    expect($user->can('index', Position::class))->toBeTrue();
});

test('create', function (): void {
    $user = User::factory()->create();

    expect($user->can('create', Position::class))->toBeFalse();

    $user->givePermissionTo(Permission::CREATE_POSITION);

    expect($user->can('create', Position::class))->toBeTrue();
});

test('store', function (): void {
    $user = User::factory()->create();

    expect($user->can('store', Position::class))->toBeFalse();

    $user->givePermissionTo(Permission::CREATE_POSITION);

    expect($user->can('store', Position::class))->toBeTrue();
});

test('edit', function (): void {
    $sport = Sport::factory()->create();
    $user = User::factory()->create([
        'sport_id' => $sport->id,
    ]);
    $position = Position::factory()->create();

    expect($user->can('edit', $position))->toBeFalse();

    $user->givePermissionTo(Permission::UPDATE_POSITION);

    expect($user->can('edit', $position))->toBeFalse();

    $user->syncRoles([Role::SuperAdmin]);
    expect($user->can('edit', $position))->toBeTrue();

    $user->removeRole(Role::SuperAdmin);

    $position->update([
        'sport_id' => $sport->id,
    ]);

    expect($user->can('edit', $position))->toBeTrue();
});

test('update', function (): void {
    $sport = Sport::factory()->create();
    $user = User::factory()->create([
        'sport_id' => $sport->id,
    ]);
    $position = Position::factory()->create();

    expect($user->can('update', $position))->toBeFalse();

    $user->givePermissionTo(Permission::UPDATE_POSITION);
    expect($user->can('update', $position))->toBeFalse();

    $user->syncRoles([Role::SuperAdmin]);
    expect($user->can('update', $position))->toBeTrue();

    $user->removeRole(Role::SuperAdmin);

    $position->update([
        'sport_id' => $sport->id,
    ]);

    expect($user->can('update', $position))->toBeTrue();
});

test('destroy', function (): void {
    $sport = Sport::factory()->create();
    $user = User::factory()->create([
        'sport_id' => $sport->id,
    ]);
    $position = Position::factory()->create();

    expect($user->can('destroy', $position))->toBeFalse();

    $user->givePermissionTo(Permission::DELETE_POSITION);
    expect($user->can('destroy', $position))->toBeFalse();

    $user->syncRoles([Role::SuperAdmin]);
    expect($user->can('destroy', $position))->toBeTrue();

    $user->removeRole(Role::SuperAdmin);
    expect($user->can('destroy', $position))->toBeFalse();

    $position->update([
        'sport_id' => $sport->id,
    ]);

    expect($user->can('destroy', $position))->toBeTrue();
});
