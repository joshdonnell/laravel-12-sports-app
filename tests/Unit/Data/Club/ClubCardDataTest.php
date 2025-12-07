<?php

declare(strict_types=1);

use App\Data\Club\ClubCardData;
use App\Models\Club;
use Spatie\LaravelData\Exceptions\CannotCreateData;

it('can be created with all required fields', function (): void {
    $sport = createSport();

    $clubCardData = ClubCardData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Club Name',
        'known_as' => 'Club Known As',
        'sport_id' => $sport->id,
        'sport' => $sport,
    ]);

    expect($clubCardData)->toBeInstanceOf(ClubCardData::class);
});

it('errors when uuid is not provided', function (): void {
    $sport = createSport();

    expect(fn (): ClubCardData => ClubCardData::from([
        'name' => 'Club Name',
        'known_as' => 'Club Known As',
        'sport_id' => $sport->id,
        'sport' => $sport,
    ]))->toThrow(CannotCreateData::class);
});

it('errors when name is not provided', function (): void {
    $sport = createSport();

    expect(fn (): ClubCardData => ClubCardData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'known_as' => 'Club Known As',
        'sport_id' => $sport->id,
        'sport' => $sport,
    ]))->toThrow(CannotCreateData::class);
});

it('errors when a sport is not provided', function (): void {
    expect(fn (): ClubCardData => ClubCardData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Club Name',
        'known_as' => 'Club Known As',
    ]))->toThrow(CannotCreateData::class);
});

it('can be created from a Club model', function (): void {
    $club = Club::factory()->create();
    $club->load('sport');
    $clubCardData = ClubCardData::from($club);

    expect($clubCardData)->toBeInstanceOf(ClubCardData::class);
});
