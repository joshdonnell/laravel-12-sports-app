<?php

declare(strict_types=1);

use App\Models\Client;
use App\Queries\Client\AlphabeticalClientsQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

it('returns a query builder', function (): void {
    $query = new AlphabeticalClientsQuery();

    $builder = $query->builder();

    expect($builder)->toBeInstanceOf(Builder::class);
});

it('returns clients in alphabetical order when they exist', function (): void {
    Client::factory(3)->sequence(
        ['name' => 'A'],
        ['name' => 'C'],
        ['name' => 'B']
    )->create();

    $query = new AlphabeticalClientsQuery();
    $clients = $query->builder()->get();

    expect($clients)->toHaveCount(3)
        ->and($clients->first())->toBeInstanceOf(Client::class)
        ->and($clients->pluck('name')->all())->toEqual(['A', 'B', 'C']);
});

it('returns an empty collection when no clients are found', function (): void {
    $query = new AlphabeticalClientsQuery();
    $clients = $query->builder()->get();

    expect($clients)->toBeInstanceOf(Collection::class)
        ->and($clients)->toHaveCount(0);
});
