<?php

declare(strict_types=1);

use App\Models\Position;
use App\Queries\Position\AlphabeticalPositionQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

it('returns a query builder', function (): void {
    $query = new AlphabeticalPositionQuery();

    $builder = $query->builder();

    expect($builder)->toBeInstanceOf(Builder::class);
});

it('returns positions in alphabetical order when they exist', function (): void {
    Position::factory(3)->sequence(
        ['name' => 'A'],
        ['name' => 'C'],
        ['name' => 'B']
    )->create();

    $query = new AlphabeticalPositionQuery();
    $positions = $query->builder()->get();

    expect($positions)->toHaveCount(3)
        ->and($positions->first())->toBeInstanceOf(Position::class)
        ->and($positions->pluck('name')->all())->toEqual(['A', 'B', 'C']);
});

it('returns an empty collection when no positions are found', function (): void {
    $query = new AlphabeticalPositionQuery();
    $positions = $query->builder()->get();

    expect($positions)->toBeInstanceOf(Collection::class)
        ->and($positions)->toHaveCount(0);
});
