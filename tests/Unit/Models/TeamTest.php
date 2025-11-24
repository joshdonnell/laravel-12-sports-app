<?php

declare(strict_types=1);

use App\Models\Club;
use App\Models\Player;
use App\Models\Sport;
use App\Models\Stat;
use App\Models\Team;
use App\Models\Venue;

test('the team model structure matches', function (): void {
    $team = Team::factory()->create()->refresh();

    expect(array_keys($team->toArray()))
        ->toBe([
            'id',
            'uuid',
            'name',
            'short_name',
            'logo',
            'sport_id',
            'club_id',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
});

test('a team belongs to a club and sport', function (): void {
    $team = Team::factory()->create();

    expect($team->club)->toBeInstanceOf(Club::class)
        ->and($team->sport)->toBeInstanceOf(Sport::class);
});

test('a team belongs to many players', function (): void {
    $team = Team::factory()->create();
    $players = Player::factory(3)->create();
    $team->players()->attach($players);

    expect($team->players)->toHaveCount(3)
        ->and($team->players->first())->toBeInstanceOf(Player::class);
});

test('a team belongs to many venues', function (): void {
    $team = Team::factory()->create();
    $venues = Venue::factory(3)->create();
    $team->venues()->attach($venues);

    expect($team->venues)->toHaveCount(3)
        ->and($team->venues->first())->toBeInstanceOf(Venue::class);
});

test('a team morphs to many stats', function (): void {
    $team = Team::factory()->create()->refresh();
    Stat::factory(3)->teamStats()->create([
        'statable_id' => $team->id,
    ]);

    expect($team->stats)->toHaveCount(3)
        ->and($team->stats->first())->toBeInstanceOf(Stat::class);
});
