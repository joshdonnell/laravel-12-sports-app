<?php

declare(strict_types=1);

use App\Models\Fixture;
use App\Models\Player;
use App\Models\Season;
use App\Models\Stat;
use App\Models\StatsRecord;
use App\Models\Team;
use App\Models\Tournament;

test('the client model structure matches', function (): void {
    $stat = Stat::factory()->create()->refresh();

    expect(array_keys($stat->toArray()))->toBe([
        'id',
        'statable_type',
        'statable_id',
        'season_id',
        'fixture_id',
        'tournament_id',
        'created_at',
        'updated_at',
    ]);
});

test('a stat has many stat records', function (): void {
    $stat = Stat::factory()->create();
    $statRecords = StatsRecord::factory()->count(3)->create([
        'stat_id' => $stat,
    ]);

    expect($stat->stats)->toHaveCount(3)
        ->and($stat->stats->first())->toBeInstanceOf(StatsRecord::class);
});

test('a stat belongs to a fixture, tournament, player and season', function (): void {
    $stat = Stat::factory()->create();

    expect($stat->statable)->toBeInstanceOf(Player::class)
        ->and($stat->season)->toBeInstanceOf(Season::class)
        ->and($stat->tournament)->toBeInstanceOf(Tournament::class)
        ->and($stat->fixture)->toBeInstanceOf(Fixture::class);
});

test('a stat belongs to a fixture, tournament, team and season', function (): void {
    $stat = Stat::factory()->teamStats()->create();

    expect($stat->statable)->toBeInstanceOf(Team::class)
        ->and($stat->season)->toBeInstanceOf(Season::class)
        ->and($stat->tournament)->toBeInstanceOf(Tournament::class)
        ->and($stat->fixture)->toBeInstanceOf(Fixture::class);
});
