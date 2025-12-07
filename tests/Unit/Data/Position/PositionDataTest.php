<?php

declare(strict_types=1);

use App\Data\Position\PositionData;
use App\Models\Position;
use Spatie\LaravelData\Exceptions\CannotCreateData;

it('can be created with all required fields', function (): void {
    $sport = createSport();

    $positonData = PositionData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Position Name',
        'known_as' => 'Known As',
        'sport_id' => $sport->id,
        'sport' => $sport,
    ]);

    expect($positonData)->toBeInstanceOf(PositionData::class)
        ->and($positonData->uuid)->toBe('123e4567-e89b-12d3-a456-426614174000')
        ->and($positonData->name)->toBe('Position Name')
        ->and($positonData->known_as)->toBe('Known As')
        ->and($positonData->sport_id)->toBe($sport->id)
        ->and($positonData->sport_name)->toBe('Netball');
});

it('errors when uuid is not provided', function (): void {
    $sport = createSport();

    expect(fn (): PositionData => PositionData::from([
        'name' => 'Position Name',
        'known_as' => 'Known As',
        'sport_id' => $sport->id,
        'sport' => $sport,
    ]))->toThrow(CannotCreateData::class);
});

it('errors when name is not provided', function (): void {
    $sport = createSport();

    expect(fn (): PositionData => PositionData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'known_as' => 'Known As',
        'sport_id' => $sport->id,
        'sport' => $sport,
    ]))->toThrow(CannotCreateData::class);
});

it('errors when sport_id is not provided', function (): void {
    $sport = createSport();

    expect(fn (): PositionData => PositionData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Position Name',
        'known_as' => 'Known As',
        'sport' => $sport,
    ]))->toThrow(CannotCreateData::class);
});

it('errors when sport is not provided', function (): void {
    $sport = createSport();

    expect(fn (): PositionData => PositionData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Position Name',
        'known_as' => 'Known As',
        'sport_id' => $sport->id,
    ]))->toThrow(CannotCreateData::class);
});

it('can be created from a Position model', function (): void {
    $sport = createSport();
    $position = Position::factory()->create([
        'name' => 'Position Name',
        'known_as' => 'Known As',
        'sport_id' => $sport->id,
    ]);
    $position->load('sport');

    $positionData = PositionData::from($position);

    expect($positionData)->toBeInstanceOf(PositionData::class)
        ->and($positionData->uuid)->toBe($position->uuid)
        ->and($positionData->name)->toBe('Position Name')
        ->and($positionData->known_as)->toBe('Known As')
        ->and($positionData->sport_id)->toBe($sport->id)
        ->and($positionData->sport_name)->toBe('Netball');
});

it('computes sport_name from sport relationship', function (): void {
    $sport = createSport();

    $positionData = PositionData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Position Name',
        'sport_id' => $sport->id,
        'sport' => $sport,
    ]);

    expect($positionData->sport_name)->toBe('Netball');
});
