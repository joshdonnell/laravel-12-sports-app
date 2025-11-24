<?php

declare(strict_types=1);

use App\Models\Season;
use App\Models\Standing;
use App\Models\Team;
use App\Models\Tournament;

test('the standing model structure matches', function (): void {
    $standing = Standing::factory()->create()->refresh();

    expect(array_keys($standing->toArray()))
        ->toBe([
            'id',
            'position',
            'points',
            'bonus_points',
            'played',
            'won',
            'drawn',
            'lost',
            'score_for',
            'score_against',
            'score_difference',
            'season_id',
            'tournament_id',
            'team_id',
            'created_at',
            'updated_at',
        ]);
});

test('a standing belongs to a tournament, season and team', function (): void {
    $standing = Standing::factory()->create();

    expect($standing->tournament)->toBeInstanceOf(Tournament::class)
        ->and($standing->season)->toBeInstanceOf(Season::class)
        ->and($standing->team)->toBeInstanceOf(Team::class);
});
