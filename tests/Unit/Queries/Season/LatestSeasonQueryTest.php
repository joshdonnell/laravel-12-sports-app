<?php

declare(strict_types=1);

use App\Models\Season;
use App\Queries\Season\LatestSeasonQuery;
use Illuminate\Database\Eloquent\Builder;

it('returns a query builder', function (): void {
    $query = new LatestSeasonQuery();

    $builder = $query->builder();

    expect($builder)->toBeInstanceOf(Builder::class);
});

it('returns seasons when they exist', function (): void {
    Season::factory(3)->create();
    $query = new LatestSeasonQuery();
    $seasons = $query->builder()->get();

    expect($seasons)->toHaveCount(3)
        ->and($seasons->first())->toBeInstanceOf(Season::class);
});

it('returns an empty collection when no season is found', function (): void {
    $query = new LatestSeasonQuery();
    $seasons = $query->builder()->get();

    expect($seasons)->toHaveCount(0);
});
