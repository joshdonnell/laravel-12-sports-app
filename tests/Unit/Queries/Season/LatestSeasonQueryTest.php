<?php

declare(strict_types=1);

use App\Models\Season;
use App\Queries\Season\LatestSeasonQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

it('returns a query builder', function (): void {
    $query = new LatestSeasonQuery();

    $builder = $query->builder();

    expect($builder)->toBeInstanceOf(Builder::class);
});

it('returns seasons in updated_at order when they exist', function (): void {
    Season::factory(3)->sequence(
        ['name' => 'season A', 'updated_at' => now()->subYear()],
        ['name' => 'season B', 'updated_at' => now()],
        ['name' => 'season C', 'updated_at' => now()->subDay()],
    )->create();

    $query = new LatestSeasonQuery();
    $seasons = $query->builder()->get();

    expect($seasons)->toHaveCount(3)
        ->and($seasons->first())->toBeInstanceOf(Season::class)
        ->and($seasons->pluck('name')->all())->toEqual(['season B', 'season C', 'season A']);
});

it('returns an empty collection when no season is found', function (): void {
    $query = new LatestSeasonQuery();
    $seasons = $query->builder()->get();

    expect($seasons)->toBeInstanceOf(Collection::class)
        ->and($seasons)->toHaveCount(0);
});
