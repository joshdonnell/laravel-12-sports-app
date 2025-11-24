<?php

declare(strict_types=1);

use App\Enums\Genders;

it('has all expected cases', function (): void {
    expect(Genders::cases())->toHaveCount(3);
    expect(Genders::Male)->toBeInstanceOf(Genders::class);
    expect(Genders::Female)->toBeInstanceOf(Genders::class);
    expect(Genders::Other)->toBeInstanceOf(Genders::class);
});

it('has correct string values', function (): void {
    expect(Genders::Male->value)->toBe('male');
    expect(Genders::Female->value)->toBe('female');
    expect(Genders::Other->value)->toBe('other');
});

it('can be created from string value', function (): void {
    expect(Genders::from('male'))->toBe(Genders::Male);
    expect(Genders::from('female'))->toBe(Genders::Female);
    expect(Genders::from('other'))->toBe(Genders::Other);
});

it('throws exception when creating from invalid string value', function (): void {
    expect(fn () => Genders::from('Invalid'))->toThrow(ValueError::class);
});
