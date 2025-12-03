<?php

declare(strict_types=1);

use App\Data\User\UserData;
use App\Enums\Role;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Spatie\LaravelData\Exceptions\CannotCreateData;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

it('can be created with all required fields', function (): void {
    $role = Spatie\Permission\Models\Role::query()->where('name', Role::User->value)->first();

    $userData = UserData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'sport_id' => null,
        'roles' => collect([$role]),
    ]);

    expect($userData)
        ->toBeInstanceOf(UserData::class);
});

it('errors when uuid is not provided', function (): void {
    $role = Spatie\Permission\Models\Role::query()->where('name', Role::User->value)->first();

    expect(fn (): UserData => UserData::from([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'sport_id' => null,
        'roles' => collect([$role]),
    ]))->toThrow(CannotCreateData::class);
});

it('errors when name is not provided', function (): void {
    $role = Spatie\Permission\Models\Role::query()->where('name', Role::User->value)->first();

    expect(fn (): UserData => UserData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'email' => 'john@example.com',
        'sport_id' => null,
        'roles' => collect([$role]),
    ]))->toThrow(CannotCreateData::class);
});

it('errors when email is not provided', function (): void {
    $role = Spatie\Permission\Models\Role::query()->where('name', Role::User->value)->first();

    expect(fn (): UserData => UserData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'John Doe',
        'sport_id' => null,
        'roles' => collect([$role]),
    ]))->toThrow(CannotCreateData::class);
});

it('errors when roles is not provided', function (): void {
    expect(fn (): UserData => UserData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'sport_id' => null,
    ]))->toThrow(CannotCreateData::class);
});

it('can be created from a User model', function (): void {
    $user = User::factory()->create();
    $user->assignRole(Role::User);
    $user->load('roles');

    $userData = UserData::from($user);

    expect($userData)
        ->toBeInstanceOf(UserData::class);
});

it('computes role_names correctly with single role', function (): void {
    $role = Spatie\Permission\Models\Role::query()->where('name', Role::Admin->value)->first();

    $userData = UserData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'sport_id' => null,
        'roles' => collect([$role]),
    ]);

    expect($userData->role_names)->toBe('Admin')
        ->and($userData->role)->toBe(Role::Admin);
});

it('computes role_names correctly with multiple roles', function (): void {
    $adminRole = Spatie\Permission\Models\Role::query()->where('name', Role::Admin->value)->first();
    $editorRole = Spatie\Permission\Models\Role::query()->where('name', Role::Editor->value)->first();

    $userData = UserData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Multi Role User',
        'email' => 'multi@example.com',
        'sport_id' => null,
        'roles' => collect([$adminRole, $editorRole]),
    ]);

    expect($userData->role_names)->toBe('Admin, Editor')
        ->and($userData->role)->toBe(Role::Admin);
});

it('sets role enum to first role when multiple roles', function (): void {
    $editorRole = Spatie\Permission\Models\Role::query()->where('name', Role::Editor->value)->first();
    $userRole = Spatie\Permission\Models\Role::query()->where('name', Role::User->value)->first();

    $userData = UserData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Test User',
        'email' => 'test@example.com',
        'sport_id' => null,
        'roles' => collect([$editorRole, $userRole]),
    ]);

    expect($userData->role)->toBe(Role::Editor);
});
