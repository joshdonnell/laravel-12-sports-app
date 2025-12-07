<?php

declare(strict_types=1);

use App\Models\Round;
use App\Queries\Round\AlphabeticalRoundQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

it('returns a query builder', function (): void {
    $query = new AlphabeticalRoundQuery();

    $builder = $query->builder();

    expect($builder)->toBeInstanceOf(Builder::class);
});

it('returns rounds in alphabetical order when they exist', function (): void {
    Round::factory(3)->sequence(
        ['name' => 'A'],
        ['name' => 'C'],
        ['name' => 'B']
    )->create();

    $query = new AlphabeticalRoundQuery();
    $rounds = $query->builder()->get();

    expect($rounds)->toHaveCount(3)
        ->and($rounds->first())->toBeInstanceOf(Round::class)
        ->and($rounds->pluck('name')->all())->toEqual(['A', 'B', 'C']);
});

it('returns an empty collection when no rounds are found', function (): void {
    $query = new AlphabeticalRoundQuery();
    $clients = $query->builder()->get();

    expect($clients)->toBeInstanceOf(Collection::class)
        ->and($clients)->toHaveCount(0);
});
