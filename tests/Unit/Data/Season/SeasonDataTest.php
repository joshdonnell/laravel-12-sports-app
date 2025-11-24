<?php

declare(strict_types=1);

use App\Data\Season\SeasonData;
use App\Models\Season;
use Spatie\LaravelData\Exceptions\CannotCreateData;

it('can be created with all required fields', function (): void {
    $seasonData = SeasonData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Season Name',
    ]);

    expect($seasonData)->toBeInstanceOf(SeasonData::class);
});

it('errors when uuid is not provided', function (): void {
    expect(fn (): SeasonData => SeasonData::from([
        'name' => 'Season Name',
    ]))->toThrow(CannotCreateData::class);
});

it('errors when name is not provided', function (): void {
    expect(fn (): SeasonData => SeasonData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
    ]))->toThrow(CannotCreateData::class);
});

it('can be created from a season model', function (): void {
    $season = Season::factory()->create();
    $seasonData = SeasonData::from($season);

    expect($seasonData)->toBeInstanceOf(SeasonData::class);
});
