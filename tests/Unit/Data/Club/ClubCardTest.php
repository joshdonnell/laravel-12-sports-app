<?php

declare(strict_types=1);

use App\Data\Club\ClubCardData;
use App\Models\Club;
use Spatie\LaravelData\Exceptions\CannotCreateData;

it('can be created with all required fields', function (): void {
    $clubCardData = ClubCardData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Club Name',
        'knownAs' => 'Club Known As',
    ]);

    expect($clubCardData)->toBeInstanceOf(ClubCardData::class);
});

it('errors when uuid is not provided', function (): void {
    expect(fn (): ClubCardData => ClubCardData::from([
        'name' => 'Club Name',
        'knownAs' => 'Club Known As',
    ]))->toThrow(CannotCreateData::class);
});

it('errors when name is not provided', function (): void {
    expect(fn (): ClubCardData => ClubCardData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
    ]))->toThrow(CannotCreateData::class);
});

it('can be created from a Club model', function (): void {
    $club = Club::factory()->create();
    $clubCardData = ClubCardData::from($club);

    expect($clubCardData)->toBeInstanceOf(ClubCardData::class);
});
