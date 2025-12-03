<?php

declare(strict_types=1);

use App\Models\Sport;
use App\Queries\Sport\AlphabeticalSportsQuery;
use Illuminate\Database\Eloquent\Builder;

it('returns a query builder', function (): void {
    $query = new AlphabeticalSportsQuery();

    $builder = $query->builder();

    expect($builder)->toBeInstanceOf(Builder::class);
});

it('returns sports in alphabetical order when they exist', function (): void {
    Sport::factory(3)->sequence(
        ['name' => 'Football'],
        ['name' => 'Basketball'],
        ['name' => 'Netball']
    )->create();

    $query = new AlphabeticalSportsQuery();
    $sports = $query->builder()->get();

    expect($sports)->toHaveCount(3)
        ->and($sports->first())->toBeInstanceOf(Sport::class)
        ->and($sports->pluck('name')->all())->toEqual(['Basketball', 'Football', 'Netball']);
});

it('returns an empty collection when no sports are found', function (): void {
    $query = new AlphabeticalSportsQuery();
    $sports = $query->builder()->get();

    expect($sports)->toHaveCount(0);
});
