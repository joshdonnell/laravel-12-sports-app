<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\Fixture;
use App\Models\Round;
use App\Models\Sport;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\Venue;

test('the fixture model structure matches', function (): void {
    $fixture = Fixture::factory()->create()->refresh();

    expect(array_keys($fixture->toArray()))
        ->toBe([
            'id',
            'uuid',
            'name',
            'description',
            'week',
            'date',
            'status',
            'home_team',
            'away_team',
            'home_team_score',
            'away_team_score',
            'home_team_bonus_score',
            'away_team_bonus_score',
            'venue_id',
            'tournament_id',
            'round_id',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
});

test('a fixture belongs to a team, venue, tournament, round and sport', function (): void {
    $fixture = Fixture::factory()->create();

    expect($fixture->homeTeam)->toBeInstanceOf(Team::class)
        ->and($fixture->awayTeam)->toBeInstanceOf(Team::class)
        ->and($fixture->tournament)->toBeInstanceOf(Tournament::class)
        ->and($fixture->round)->toBeInstanceOf(Round::class)
        ->and($fixture->venue)->toBeInstanceOf(Venue::class);
});

test('a fixture has a sport through a tournament', function (): void {
    $fixture = Fixture::factory()->create();

    expect($fixture->sport)->toBeInstanceOf(Sport::class);
});

test('a fixture has many clients through a tournament', function (): void {
    $tournament = Tournament::factory()->hasClients(3)->create();
    $fixture = Fixture::factory()->create([
        'tournament_id' => $tournament->id,
    ]);

    expect($fixture->clients)->toHaveCount(3)
        ->and($fixture->clients->first())->toBeInstanceOf(Client::class);
});
