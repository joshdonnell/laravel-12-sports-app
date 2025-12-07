<?php

declare(strict_types=1);

use App\Models\Club;
use App\Queries\Club\AlphabeticalClubsQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

it('returns a query builder', function (): void {
    $query = new AlphabeticalClubsQuery();

    $builder = $query->builder();

    expect($builder)->toBeInstanceOf(Builder::class);
});

it('returns clubs in alphabetical order when they exist', function (): void {
    Club::factory(3)->sequence(
        ['name' => 'A'],
        ['name' => 'C'],
        ['name' => 'B']
    )->create();

    $query = new AlphabeticalClubsQuery();
    $clubs = $query->builder()->get();

    expect($clubs)->toHaveCount(3)
        ->and($clubs->first())->toBeInstanceOf(Club::class)
        ->and($clubs->pluck('name')->all())->toEqual(['A', 'B', 'C']);
});

it('returns an empty collection when no clubs are found', function (): void {
    $query = new AlphabeticalClubsQuery();
    $clients = $query->builder()->get();

    expect($clients)->toBeInstanceOf(Collection::class)
        ->and($clients)->toHaveCount(0);
});
