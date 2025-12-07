<?php

declare(strict_types=1);

use App\Data\Round\RoundData;
use App\Models\Round;
use App\Models\Sport;
use Spatie\LaravelData\Exceptions\CannotCreateData;

it('can be created with all required fields', function (): void {
    $sport = createSport();

    $roundData = RoundData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Round Name',
        'round_number' => 1,
        'sport_id' => $sport->id,
        'sport' => $sport,
    ]);

    expect($roundData)->toBeInstanceOf(RoundData::class)
        ->and($roundData->uuid)->toBe('123e4567-e89b-12d3-a456-426614174000')
        ->and($roundData->name)->toBe('Round Name')
        ->and($roundData->round_number)->toBe(1)
        ->and($roundData->sport_id)->toBe($sport->id)
        ->and($roundData->sport_name)->toBe('Netball');
});

it('errors when uuid is not provided', function (): void {
    $sport = createSport();

    expect(fn (): RoundData => RoundData::from([
        'name' => 'Round Name',
        'round_number' => 1,
        'sport_id' => $sport->id,
        'sport' => $sport,
    ]))->toThrow(CannotCreateData::class);
});

it('errors when name is not provided', function (): void {
    $sport = createSport();

    expect(fn (): RoundData => RoundData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'round_number' => 1,
        'sport_id' => $sport->id,
        'sport' => $sport,
    ]))->toThrow(CannotCreateData::class);
});

it('errors when a round number is not provided', function (): void {
    $sport = createSport();

    expect(fn (): RoundData => RoundData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Round name',
        'sport_id' => $sport->id,
        'sport' => $sport,
    ]))->toThrow(CannotCreateData::class);
});

it('errors when sport_id is not provided', function (): void {
    $sport = createSport();

    expect(fn (): RoundData => RoundData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Round Name',
        'round_number' => 1,
        'sport' => $sport,
    ]))->toThrow(CannotCreateData::class);
});

it('errors when the sport model is not provided', function (): void {
    $sport = createSport();

    expect(fn (): RoundData => RoundData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Round Name',
        'round_number' => 1,
        'sport_id' => $sport->id,
    ]))->toThrow(CannotCreateData::class);
});

it('can be created from a Round model', function (): void {
    $sport = createSport();
    $round = Round::factory()->create([
        'name' => 'Test Round',
        'round_number' => 1,
        'sport_id' => $sport->id,
    ]);
    $round->load('sport');

    $roundData = RoundData::from($round);

    expect($roundData)->toBeInstanceOf(RoundData::class)
        ->and($roundData->uuid)->toBe($round->uuid)
        ->and($roundData->name)->toBe('Test Round')
        ->and($roundData->round_number)->toBe(1)
        ->and($roundData->sport_id)->toBe($sport->id)
        ->and($roundData->sport_name)->toBe('Netball');
});

it('computes sport_name from sport relationship', function (): void {
    $sport = Sport::factory()->create(['name' => 'Netball']);

    $roundData = RoundData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Round Name',
        'round_number' => 1,
        'sport_id' => $sport->id,
        'sport' => $sport,
    ]);

    expect($roundData->sport_name)->toBe('Netball');
});
