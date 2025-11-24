<?php

declare(strict_types=1);

use App\Data\Club\ClubData;
use App\Models\Club;
use Spatie\LaravelData\Exceptions\CannotCreateData;

it('can be created with all required fields', function (): void {
    $clubData = ClubData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Club Name',
    ]);

    expect($clubData)->toBeInstanceOf(ClubData::class);
});

it('errors when uuid is not provided', function (): void {
    expect(fn (): ClubData => ClubData::from([
        'name' => 'Club Name',
    ]))->toThrow(CannotCreateData::class);
});

it('errors when name is not provided', function (): void {
    expect(fn (): ClubData => ClubData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
    ]))->toThrow(CannotCreateData::class);
});

it('can be created from a Club model', function (): void {
    $club = Club::factory()->create();
    $clubData = ClubData::from($club);

    expect($clubData)->toBeInstanceOf(ClubData::class);
});
