<?php

declare(strict_types=1);

use App\Data\Venue\VenueData;
use App\Models\Sport;
use App\Models\Venue;
use Spatie\LaravelData\Exceptions\CannotCreateData;

it('can be created with all required fields', function (): void {
    $sport = Sport::factory()->create(['name' => 'Netball']);

    $clientData = VenueData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Client Name',
        'sport_id' => $sport->id,
        'sport' => $sport,
    ]);

    expect($clientData)->toBeInstanceOf(VenueData::class)
        ->and($clientData->uuid)->toBe('123e4567-e89b-12d3-a456-426614174000')
        ->and($clientData->name)->toBe('Client Name')
        ->and($clientData->sport_id)->toBe($sport->id)
        ->and($clientData->sport_name)->toBe('Netball');
});

it('errors when uuid is not provided', function (): void {
    $sport = Sport::factory()->create();

    expect(fn (): VenueData => VenueData::from([
        'name' => 'Client Name',
        'sport_id' => $sport->id,
        'sport' => $sport,
    ]))->toThrow(CannotCreateData::class);
});

it('errors when name is not provided', function (): void {
    $sport = Sport::factory()->create();

    expect(fn (): VenueData => VenueData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'sport_id' => $sport->id,
        'sport' => $sport,
    ]))->toThrow(CannotCreateData::class);
});

it('errors when sport_id is not provided', function (): void {
    $sport = Sport::factory()->create();

    expect(fn (): VenueData => VenueData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Client Name',
        'sport' => $sport,
    ]))->toThrow(CannotCreateData::class);
});

it('errors when sport is not provided', function (): void {
    $sport = Sport::factory()->create();

    expect(fn (): VenueData => VenueData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Client Name',
        'sport_id' => $sport->id,
    ]))->toThrow(CannotCreateData::class);
});

it('can be created from a Venue model', function (): void {
    $sport = Sport::factory()->create(['name' => 'Netball']);
    $venue = Venue::factory()->create([
        'name' => 'Test Venue',
        'sport_id' => $sport->id,
    ]);
    $venue->load('sport');

    $venueData = VenueData::from($venue);

    expect($venueData)->toBeInstanceOf(VenueData::class)
        ->and($venueData->uuid)->toBe($venue->uuid)
        ->and($venueData->name)->toBe('Test Venue')
        ->and($venueData->sport_id)->toBe($sport->id)
        ->and($venueData->sport_name)->toBe('Netball');
});
