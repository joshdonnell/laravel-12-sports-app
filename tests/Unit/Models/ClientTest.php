<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\Sport;
use App\Models\Tournament;
use App\Models\User;

test('the client model structure matches', function (): void {
    $client = Client::factory()->create()->refresh();

    expect(array_keys($client->toArray()))
        ->toBe([
            'id',
            'uuid',
            'name',
            'sport_id',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
});

test('a client belongs to many users', function (): void {
    $client = Client::factory()->hasUsers(3)->create();

    expect($client->users)->toHaveCount(3)
        ->and($client->users->first())->toBeInstanceOf(User::class);
});

test('a client belongs to many tournaments', function (): void {
    $client = Client::factory()->hasTournaments(3)->create();

    expect($client->tournaments)->toHaveCount(3)
        ->and($client->tournaments->first())->toBeInstanceOf(Tournament::class);
});

test('a client belongs to a sport', function (): void {
    $client = Client::factory()->create();

    expect($client->sport)->toBeInstanceOf(Sport::class);
});
