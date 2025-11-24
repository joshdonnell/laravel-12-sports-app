<?php

declare(strict_types=1);

use App\Enums\TournamentTypes;

it('has all expected cases', function (): void {
    expect(TournamentTypes::cases())->toHaveCount(4);
    expect(TournamentTypes::RoundRobin)->toBeInstanceOf(TournamentTypes::class);
    expect(TournamentTypes::SingleElimination)->toBeInstanceOf(TournamentTypes::class);
    expect(TournamentTypes::DoubleElimination)->toBeInstanceOf(TournamentTypes::class);
    expect(TournamentTypes::League)->toBeInstanceOf(TournamentTypes::class);
});

it('has correct string values', function (): void {
    expect(TournamentTypes::RoundRobin->value)->toBe('round_robin');
    expect(TournamentTypes::SingleElimination->value)->toBe('single_elimination');
    expect(TournamentTypes::DoubleElimination->value)->toBe('double_elimination');
    expect(TournamentTypes::League->value)->toBe('league');
});

it('can be created from string value', function (): void {
    expect(TournamentTypes::from('round_robin'))->toBe(TournamentTypes::RoundRobin);
    expect(TournamentTypes::from('single_elimination'))->toBe(TournamentTypes::SingleElimination);
    expect(TournamentTypes::from('double_elimination'))->toBe(TournamentTypes::DoubleElimination);
    expect(TournamentTypes::from('league'))->toBe(TournamentTypes::League);
});

it('throws exception when creating from invalid string value', function (): void {
    expect(fn () => TournamentTypes::from('Invalid'))->toThrow(ValueError::class);
});
