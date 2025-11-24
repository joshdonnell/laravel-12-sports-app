<?php

declare(strict_types=1);

use App\Enums\NetballFixtureEventTypes;

it('has all expected cases', function (): void {
    expect(NetballFixtureEventTypes::cases())->toHaveCount(14);
    expect(NetballFixtureEventTypes::GameManagement)->toBeInstanceOf(NetballFixtureEventTypes::class);
    expect(NetballFixtureEventTypes::Goal)->toBeInstanceOf(NetballFixtureEventTypes::class);
    expect(NetballFixtureEventTypes::Penalty)->toBeInstanceOf(NetballFixtureEventTypes::class);
    expect(NetballFixtureEventTypes::CentrePassReceived)->toBeInstanceOf(NetballFixtureEventTypes::class);
    expect(NetballFixtureEventTypes::Feed)->toBeInstanceOf(NetballFixtureEventTypes::class);
    expect(NetballFixtureEventTypes::UnforcedError)->toBeInstanceOf(NetballFixtureEventTypes::class);
    expect(NetballFixtureEventTypes::SuperShot)->toBeInstanceOf(NetballFixtureEventTypes::class);
    expect(NetballFixtureEventTypes::Substitution)->toBeInstanceOf(NetballFixtureEventTypes::class);
    expect(NetballFixtureEventTypes::Deflection)->toBeInstanceOf(NetballFixtureEventTypes::class);
    expect(NetballFixtureEventTypes::Pickup)->toBeInstanceOf(NetballFixtureEventTypes::class);
    expect(NetballFixtureEventTypes::Rebound)->toBeInstanceOf(NetballFixtureEventTypes::class);
    expect(NetballFixtureEventTypes::Interception)->toBeInstanceOf(NetballFixtureEventTypes::class);
    expect(NetballFixtureEventTypes::Gain)->toBeInstanceOf(NetballFixtureEventTypes::class);
    expect(NetballFixtureEventTypes::Miss)->toBeInstanceOf(NetballFixtureEventTypes::class);
});

it('has correct string values', function (): void {
    expect(NetballFixtureEventTypes::GameManagement->value)->toBe('game-management');
    expect(NetballFixtureEventTypes::Goal->value)->toBe('goal');
    expect(NetballFixtureEventTypes::Penalty->value)->toBe('penalty');
    expect(NetballFixtureEventTypes::CentrePassReceived->value)->toBe('centre-pass-received');
    expect(NetballFixtureEventTypes::Feed->value)->toBe('feed');
    expect(NetballFixtureEventTypes::UnforcedError->value)->toBe('unforced-error');
    expect(NetballFixtureEventTypes::SuperShot->value)->toBe('super-shot');
    expect(NetballFixtureEventTypes::Substitution->value)->toBe('substitution');
    expect(NetballFixtureEventTypes::Deflection->value)->toBe('deflection');
    expect(NetballFixtureEventTypes::Pickup->value)->toBe('pickup');
    expect(NetballFixtureEventTypes::Rebound->value)->toBe('rebound');
    expect(NetballFixtureEventTypes::Interception->value)->toBe('interception');
    expect(NetballFixtureEventTypes::Gain->value)->toBe('gain');
    expect(NetballFixtureEventTypes::Miss->value)->toBe('miss');
});

it('can be created from string value', function (): void {
    expect(NetballFixtureEventTypes::from('game-management'))->toBe(NetballFixtureEventTypes::GameManagement);
    expect(NetballFixtureEventTypes::from('goal'))->toBe(NetballFixtureEventTypes::Goal);
    expect(NetballFixtureEventTypes::from('penalty'))->toBe(NetballFixtureEventTypes::Penalty);
    expect(NetballFixtureEventTypes::from('centre-pass-received'))->toBe(NetballFixtureEventTypes::CentrePassReceived);
    expect(NetballFixtureEventTypes::from('feed'))->toBe(NetballFixtureEventTypes::Feed);
    expect(NetballFixtureEventTypes::from('unforced-error'))->toBe(NetballFixtureEventTypes::UnforcedError);
    expect(NetballFixtureEventTypes::from('super-shot'))->toBe(NetballFixtureEventTypes::SuperShot);
    expect(NetballFixtureEventTypes::from('substitution'))->toBe(NetballFixtureEventTypes::Substitution);
    expect(NetballFixtureEventTypes::from('deflection'))->toBe(NetballFixtureEventTypes::Deflection);
    expect(NetballFixtureEventTypes::from('pickup'))->toBe(NetballFixtureEventTypes::Pickup);
    expect(NetballFixtureEventTypes::from('rebound'))->toBe(NetballFixtureEventTypes::Rebound);
    expect(NetballFixtureEventTypes::from('interception'))->toBe(NetballFixtureEventTypes::Interception);
    expect(NetballFixtureEventTypes::from('gain'))->toBe(NetballFixtureEventTypes::Gain);
    expect(NetballFixtureEventTypes::from('miss'))->toBe(NetballFixtureEventTypes::Miss);
});

it('throws exception when creating from invalid string value', function (): void {
    expect(fn () => NetballFixtureEventTypes::from('Invalid'))->toThrow(ValueError::class);
});
