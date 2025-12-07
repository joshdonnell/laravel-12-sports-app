<?php

declare(strict_types=1);

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Client;
use App\Models\Sport;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

test('index', function (): void {
    $user = User::factory()->create();

    expect($user->can('index', Client::class))->toBeFalse();

    $user->givePermissionTo(Permission::LIST_CLIENTS);

    expect($user->can('index', Client::class))->toBeTrue();
});

test('create', function (): void {
    $user = User::factory()->create();

    expect($user->can('create', Client::class))->toBeFalse();

    $user->givePermissionTo(Permission::CREATE_CLIENT);

    expect($user->can('create', Client::class))->toBeTrue();
});

test('store', function (): void {
    $user = User::factory()->create();

    expect($user->can('store', Client::class))->toBeFalse();

    $user->givePermissionTo(Permission::CREATE_CLIENT);

    expect($user->can('store', Client::class))->toBeTrue();
});

test('edit', function (): void {
    $sport = Sport::factory()->create();
    $user = User::factory()->create([
        'sport_id' => $sport->id,
    ]);
    $client = Client::factory()->create();

    expect($user->can('edit', $client))->toBeFalse();

    $user->givePermissionTo(Permission::UPDATE_CLIENT);

    expect($user->can('edit', $client))->toBeFalse();

    $user->syncRoles([Role::SuperAdmin]);
    expect($user->can('edit', $client))->toBeTrue();

    $user->removeRole(Role::SuperAdmin);

    $client->update([
        'sport_id' => $sport->id,
    ]);

    expect($user->can('edit', $client))->toBeTrue();
});

test('update', function (): void {
    $sport = Sport::factory()->create();
    $user = User::factory()->create([
        'sport_id' => $sport->id,
    ]);
    $client = Client::factory()->create();

    expect($user->can('update', $client))->toBeFalse();

    $user->givePermissionTo(Permission::UPDATE_CLIENT);
    expect($user->can('update', $client))->toBeFalse();

    $user->syncRoles([Role::SuperAdmin]);
    expect($user->can('update', $client))->toBeTrue();

    $user->removeRole(Role::SuperAdmin);

    $client->update([
        'sport_id' => $sport->id,
    ]);

    expect($user->can('update', $client))->toBeTrue();
});

test('destroy', function (): void {
    $sport = Sport::factory()->create();
    $user = User::factory()->create([
        'sport_id' => $sport->id,
    ]);
    $client = Client::factory()->create();

    expect($user->can('destroy', $client))->toBeFalse();

    $user->givePermissionTo(Permission::DELETE_CLIENT);
    expect($user->can('destroy', $client))->toBeFalse();

    $user->syncRoles([Role::SuperAdmin]);
    expect($user->can('destroy', $client))->toBeTrue();

    $user->removeRole(Role::SuperAdmin);
    expect($user->can('destroy', $client))->toBeFalse();

    $client->update([
        'sport_id' => $sport->id,
    ]);

    expect($user->can('destroy', $client))->toBeTrue();
});
