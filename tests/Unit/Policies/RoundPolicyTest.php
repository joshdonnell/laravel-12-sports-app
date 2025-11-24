<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\Round;
use App\Models\User;

beforeEach(function (): void {
    $this->seed(Database\Seeders\RoleAndPermissionSeeder::class);
});

test('index', function (): void {
    $user = User::factory()->create();
    $round = Round::factory()->create();

    expect($user->can('index', $round))->toBeFalse();

    $user->assignRole(Role::SuperAdmin);

    expect($user->can('index', $round))->toBeTrue();
});

test('create', function (): void {
    $user = User::factory()->create();
    $round = Round::factory()->create();

    expect($user->can('create', $round))->toBeFalse();

    $user->assignRole(Role::SuperAdmin);

    expect($user->can('create', $round))->toBeTrue();
});

test('store', function (): void {
    $user = User::factory()->create();
    $round = Round::factory()->create();

    expect($user->can('store', $round))->toBeFalse();

    $user->assignRole(Role::SuperAdmin);

    expect($user->can('store', $round))->toBeTrue();
});

test('edit', function (): void {
    $user = User::factory()->create();
    $round = Round::factory()->create();

    expect($user->can('edit', $round))->toBeFalse();

    $user->assignRole(Role::SuperAdmin);

    expect($user->can('create', $round))->toBeTrue();
});

test('update', function (): void {
    $user = User::factory()->create();
    $round = Round::factory()->create();

    expect($user->can('update', $round))->toBeFalse();

    $user->assignRole(Role::SuperAdmin);

    expect($user->can('update', $round))->toBeTrue();
});
