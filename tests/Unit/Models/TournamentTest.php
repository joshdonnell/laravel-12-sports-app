<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\Ruleset;
use App\Models\Sport;
use App\Models\Tournament;

test('the tournament model structure matches', function (): void {
    $tournament = Tournament::factory()->create()->refresh();

    expect(array_keys($tournament->toArray()))
        ->toBe([
            'id',
            'uuid',
            'name',
            'code',
            'exclude_stats',
            'sport_id',
            'ruleset_id',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
});

test('a tournament belongs to a ruleset and sport', function (): void {
    $tournament = Tournament::factory()->create();

    expect($tournament->ruleset)->toBeInstanceOf(Ruleset::class)
        ->and($tournament->sport)->toBeInstanceOf(Sport::class);
});

test('a tournament belongs to many clients', function (): void {
    $tournament = Tournament::factory()->hasClients(3)->create();

    expect($tournament->clients)->toHaveCount(3)
        ->and($tournament->clients->first())->toBeInstanceOf(Client::class);
});
