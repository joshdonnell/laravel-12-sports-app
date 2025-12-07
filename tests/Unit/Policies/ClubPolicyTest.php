<?php

declare(strict_types=1);

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Club;
use App\Models\Sport;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

test('index', function (): void {
    $user = User::factory()->create();

    expect($user->can('index', Club::class))->toBeFalse();

    $user->givePermissionTo(Permission::LIST_CLUBS);

    expect($user->can('index', Club::class))->toBeTrue();
});

test('create', function (): void {
    $user = User::factory()->create();

    expect($user->can('create', Club::class))->toBeFalse();

    $user->givePermissionTo(Permission::CREATE_CLUB);

    expect($user->can('create', Club::class))->toBeTrue();
});

test('store', function (): void {
    $user = User::factory()->create();

    expect($user->can('store', Club::class))->toBeFalse();

    $user->givePermissionTo(Permission::CREATE_CLUB);

    expect($user->can('store', Club::class))->toBeTrue();
});

test('edit', function (): void {
    $sport = Sport::factory()->create();
    $user = User::factory()->create([
        'sport_id' => $sport->id,
    ]);
    $club = Club::factory()->create();

    expect($user->can('edit', $club))->toBeFalse();

    $user->givePermissionTo(Permission::UPDATE_CLUB);

    expect($user->can('edit', $club))->toBeFalse();

    $user->syncRoles([Role::SuperAdmin]);
    expect($user->can('edit', $club))->toBeTrue();

    $user->removeRole(Role::SuperAdmin);

    $club->update([
        'sport_id' => $sport->id,
    ]);

    expect($user->can('edit', $club))->toBeTrue();
});

test('update', function (): void {
    $sport = Sport::factory()->create();
    $user = User::factory()->create([
        'sport_id' => $sport->id,
    ]);
    $club = Club::factory()->create();

    expect($user->can('update', $club))->toBeFalse();

    $user->givePermissionTo(Permission::UPDATE_CLUB);
    expect($user->can('update', $club))->toBeFalse();

    $user->syncRoles([Role::SuperAdmin]);
    expect($user->can('update', $club))->toBeTrue();

    $user->removeRole(Role::SuperAdmin);

    $club->update([
        'sport_id' => $sport->id,
    ]);

    expect($user->can('update', $club))->toBeTrue();
});

test('destroy', function (): void {
    $sport = Sport::factory()->create();
    $user = User::factory()->create([
        'sport_id' => $sport->id,
    ]);
    $club = Club::factory()->create();

    expect($user->can('destroy', $club))->toBeFalse();

    $user->givePermissionTo(Permission::DELETE_CLUB);
    expect($user->can('destroy', $club))->toBeFalse();

    $user->syncRoles([Role::SuperAdmin]);
    expect($user->can('destroy', $club))->toBeTrue();

    $user->removeRole(Role::SuperAdmin);
    expect($user->can('destroy', $club))->toBeFalse();

    $club->update([
        'sport_id' => $sport->id,
    ]);

    expect($user->can('destroy', $club))->toBeTrue();
});
