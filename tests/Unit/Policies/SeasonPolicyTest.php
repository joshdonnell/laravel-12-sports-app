<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\Season;
use App\Models\User;

beforeEach(function (): void {
    $this->seed(Database\Seeders\RoleAndPermissionSeeder::class);
});

test('index', function (): void {
    $user = User::factory()->create();
    $season = Season::factory()->create();

    expect($user->can('index', $season))->toBeFalse();

    $user->assignRole(Role::SuperAdmin);

    expect($user->can('index', $season))->toBeTrue();
});

test('create', function (): void {
    $user = User::factory()->create();
    $season = Season::factory()->create();

    expect($user->can('create', $season))->toBeFalse();

    $user->assignRole(Role::SuperAdmin);

    expect($user->can('create', $season))->toBeTrue();
});

test('store', function (): void {
    $user = User::factory()->create();
    $season = Season::factory()->create();

    expect($user->can('store', $season))->toBeFalse();

    $user->assignRole(Role::SuperAdmin);

    expect($user->can('store', $season))->toBeTrue();
});

test('edit', function (): void {
    $user = User::factory()->create();
    $season = Season::factory()->create();

    expect($user->can('edit', $season))->toBeFalse();

    $user->assignRole(Role::SuperAdmin);

    expect($user->can('create', $season))->toBeTrue();
});

test('update', function (): void {
    $user = User::factory()->create();
    $season = Season::factory()->create();

    expect($user->can('update', $season))->toBeFalse();

    $user->assignRole(Role::SuperAdmin);

    expect($user->can('update', $season))->toBeTrue();
});
