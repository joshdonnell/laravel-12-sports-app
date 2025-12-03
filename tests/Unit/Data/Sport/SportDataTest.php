<?php

declare(strict_types=1);

use App\Data\Sport\SportData;
use App\Models\Sport;
use Spatie\LaravelData\Exceptions\CannotCreateData;

it('can be created with all required fields', function (): void {
    $sportData = SportData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Sport Name',
    ]);

    expect($sportData)->toBeInstanceOf(SportData::class);
});

it('errors when uuid is not provided', function (): void {
    expect(fn (): SportData => SportData::from([
        'name' => 'Sport Name',
    ]))->toThrow(CannotCreateData::class);
});

it('errors when name is not provided', function (): void {
    expect(fn (): SportData => SportData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
    ]))->toThrow(CannotCreateData::class);
});

it('can be created from a Sport model', function (): void {
    $sport = Sport::factory()->create();
    $sportData = SportData::from($sport);

    expect($sportData)->toBeInstanceOf(SportData::class);
});
