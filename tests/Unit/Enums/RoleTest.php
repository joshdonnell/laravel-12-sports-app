<?php

declare(strict_types=1);

use App\Enums\Role;

it('has all expected cases', function (): void {
    expect(Role::cases())->toHaveCount(4);
    expect(Role::SuperAdmin)->toBeInstanceOf(Role::class);
    expect(Role::Admin)->toBeInstanceOf(Role::class);
    expect(Role::Editor)->toBeInstanceOf(Role::class);
    expect(Role::User)->toBeInstanceOf(Role::class);
});

it('has correct string values', function (): void {
    expect(Role::SuperAdmin->value)->toBe('super-admin');
    expect(Role::Admin->value)->toBe('admin');
    expect(Role::Editor->value)->toBe('editor');
    expect(Role::User->value)->toBe('user');
});

it('can be created from string value', function (): void {
    expect(Role::from('super-admin'))->toBe(Role::SuperAdmin);
    expect(Role::from('admin'))->toBe(Role::Admin);
    expect(Role::from('editor'))->toBe(Role::Editor);
    expect(Role::from('user'))->toBe(Role::User);
});

it('throws exception when creating from invalid string value', function (): void {
    expect(fn () => Role::from('Invalid'))->toThrow(ValueError::class);
});
