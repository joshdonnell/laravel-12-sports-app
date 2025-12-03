<?php

declare(strict_types=1);

use App\Models\User;
use App\Queries\User\AlphabeticalUsersQuery;
use Illuminate\Database\Eloquent\Builder;

it('returns a query builder', function (): void {
    $query = new AlphabeticalUsersQuery();

    $builder = $query->builder();

    expect($builder)->toBeInstanceOf(Builder::class);
});

it('returns users in alphabetical order when they exist', function (): void {
    User::factory(3)->sequence(
        ['name' => 'A'],
        ['name' => 'C'],
        ['name' => 'B']
    )->create();

    $query = new AlphabeticalUsersQuery();
    $sports = $query->builder()->get();

    expect($sports)->toHaveCount(3)
        ->and($sports->first())->toBeInstanceOf(User::class)
        ->and($sports->pluck('name')->all())->toEqual(['A', 'B', 'C']);
});

it('returns an empty collection when no users are found', function (): void {
    $query = new AlphabeticalUsersQuery();
    $sports = $query->builder()->get();

    expect($sports)->toHaveCount(0);
});
