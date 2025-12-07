<?php

declare(strict_types=1);

use App\Models\Venue;
use App\Queries\Venue\AlphabeticalVenueQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

it('returns a query builder', function (): void {
    $query = new AlphabeticalVenueQuery();

    $builder = $query->builder();

    expect($builder)->toBeInstanceOf(Builder::class);
});

it('returns clients in alphabetical order when they exist', function (): void {
    Venue::factory(3)->sequence(
        ['name' => 'A'],
        ['name' => 'C'],
        ['name' => 'B']
    )->create();

    $query = new AlphabeticalVenueQuery();
    $venues = $query->builder()->get();

    expect($venues)->toHaveCount(3)
        ->and($venues->first())->toBeInstanceOf(Venue::class)
        ->and($venues->pluck('name')->all())->toEqual(['A', 'B', 'C']);
});

it('returns an empty collection when no venues are found', function (): void {
    $query = new AlphabeticalVenueQuery();
    $venues = $query->builder()->get();

    expect($venues)->toBeInstanceOf(Collection::class)
        ->and($venues)->toHaveCount(0);
});
