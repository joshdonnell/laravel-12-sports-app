<?php

declare(strict_types=1);

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Sport;
use App\Models\User;
use App\Models\Venue;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

test('index', function (): void {
    $user = User::factory()->create();

    expect($user->can('index', Venue::class))->toBeFalse();

    $user->givePermissionTo(Permission::LIST_VENUES);

    expect($user->can('index', Venue::class))->toBeTrue();
});

test('create', function (): void {
    $user = User::factory()->create();

    expect($user->can('create', Venue::class))->toBeFalse();

    $user->givePermissionTo(Permission::CREATE_VENUE);

    expect($user->can('create', Venue::class))->toBeTrue();
});

test('store', function (): void {
    $user = User::factory()->create();

    expect($user->can('store', Venue::class))->toBeFalse();

    $user->givePermissionTo(Permission::CREATE_VENUE);

    expect($user->can('store', Venue::class))->toBeTrue();
});

test('edit', function (): void {
    $sport = Sport::factory()->create();
    $user = User::factory()->create([
        'sport_id' => $sport->id,
    ]);
    $venue = Venue::factory()->create();

    expect($user->can('edit', $venue))->toBeFalse();

    $user->givePermissionTo(Permission::UPDATE_VENUE);

    expect($user->can('edit', $venue))->toBeFalse();

    $user->syncRoles([Role::SuperAdmin]);
    expect($user->can('edit', $venue))->toBeTrue();

    $user->removeRole(Role::SuperAdmin);

    $venue->update([
        'sport_id' => $sport->id,
    ]);

    expect($user->can('edit', $venue))->toBeTrue();
});

test('update', function (): void {
    $sport = Sport::factory()->create();
    $user = User::factory()->create([
        'sport_id' => $sport->id,
    ]);
    $venue = Venue::factory()->create();

    expect($user->can('update', $venue))->toBeFalse();

    $user->givePermissionTo(Permission::UPDATE_VENUE);
    expect($user->can('update', $venue))->toBeFalse();

    $user->syncRoles([Role::SuperAdmin]);
    expect($user->can('update', $venue))->toBeTrue();

    $user->removeRole(Role::SuperAdmin);

    $venue->update([
        'sport_id' => $sport->id,
    ]);

    expect($user->can('update', $venue))->toBeTrue();
});

test('destroy', function (): void {
    $sport = Sport::factory()->create();
    $user = User::factory()->create([
        'sport_id' => $sport->id,
    ]);
    $venue = Venue::factory()->create();

    expect($user->can('destroy', $venue))->toBeFalse();

    $user->givePermissionTo(Permission::DELETE_VENUE);
    expect($user->can('destroy', $venue))->toBeFalse();

    $user->syncRoles([Role::SuperAdmin]);
    expect($user->can('destroy', $venue))->toBeTrue();

    $user->removeRole(Role::SuperAdmin);
    expect($user->can('destroy', $venue))->toBeFalse();

    $venue->update([
        'sport_id' => $sport->id,
    ]);

    expect($user->can('destroy', $venue))->toBeTrue();
});
