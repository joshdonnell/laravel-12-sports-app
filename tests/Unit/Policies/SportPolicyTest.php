<?php

declare(strict_types=1);

use App\Enums\Permission;
use App\Models\Sport;
use App\Models\User;

beforeEach(function (): void {
    $this->seed(Database\Seeders\RoleAndPermissionSeeder::class);
});

test('index', function (): void {
    $user = User::factory()->create();
    $sport = Sport::factory()->create();

    expect($user->can('index', $sport))->toBeFalse();

    $user->givePermissionTo(Permission::LIST_SPORTS);

    expect($user->can('index', $sport))->toBeTrue();
});

test('create', function (): void {
    $user = User::factory()->create();
    $sport = Sport::factory()->create();

    expect($user->can('create', $sport))->toBeFalse();

    $user->givePermissionTo(Permission::CREATE_SPORT);

    expect($user->can('create', $sport))->toBeTrue();
});

test('store', function (): void {
    $user = User::factory()->create();
    $sport = Sport::factory()->create();

    expect($user->can('store', $sport))->toBeFalse();

    $user->givePermissionTo(Permission::CREATE_SPORT);

    expect($user->can('store', $sport))->toBeTrue();
});

test('edit', function (): void {
    $user = User::factory()->create();
    $sport = Sport::factory()->create();

    expect($user->can('edit', $sport))->toBeFalse();

    $user->givePermissionTo(Permission::UPDATE_SPORT);

    expect($user->can('edit', $sport))->toBeTrue();
});

test('update', function (): void {
    $user = User::factory()->create();
    $sport = Sport::factory()->create();

    expect($user->can('update', $sport))->toBeFalse();

    $user->givePermissionTo(Permission::UPDATE_SPORT);

    expect($user->can('update', $sport))->toBeTrue();
});
