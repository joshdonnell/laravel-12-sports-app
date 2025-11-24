<?php

declare(strict_types=1);

use App\Enums\FixtureStatus;

it('has all expected cases', function (): void {
    expect(FixtureStatus::cases())->toHaveCount(6);
    expect(FixtureStatus::Scheduled)->toBeInstanceOf(FixtureStatus::class);
    expect(FixtureStatus::InProgress)->toBeInstanceOf(FixtureStatus::class);
    expect(FixtureStatus::Pending)->toBeInstanceOf(FixtureStatus::class);
    expect(FixtureStatus::Completed)->toBeInstanceOf(FixtureStatus::class);
    expect(FixtureStatus::Cancelled)->toBeInstanceOf(FixtureStatus::class);
    expect(FixtureStatus::Postponed)->toBeInstanceOf(FixtureStatus::class);
});

it('has correct string values', function (): void {
    expect(FixtureStatus::Scheduled->value)->toBe('scheduled');
    expect(FixtureStatus::InProgress->value)->toBe('in-progress');
    expect(FixtureStatus::Pending->value)->toBe('pending');
    expect(FixtureStatus::Completed->value)->toBe('completed');
    expect(FixtureStatus::Cancelled->value)->toBe('cancelled');
    expect(FixtureStatus::Postponed->value)->toBe('postponed');
});

it('can be created from string value', function (): void {
    expect(FixtureStatus::from('scheduled'))->toBe(FixtureStatus::Scheduled);
    expect(FixtureStatus::from('in-progress'))->toBe(FixtureStatus::InProgress);
    expect(FixtureStatus::from('pending'))->toBe(FixtureStatus::Pending);
    expect(FixtureStatus::from('completed'))->toBe(FixtureStatus::Completed);
    expect(FixtureStatus::from('cancelled'))->toBe(FixtureStatus::Cancelled);
    expect(FixtureStatus::from('postponed'))->toBe(FixtureStatus::Postponed);
});

it('throws exception when creating from invalid string value', function (): void {
    expect(fn () => FixtureStatus::from('Invalid'))->toThrow(ValueError::class);
});
