<?php

declare(strict_types=1);

use App\Models\Fixture;
use App\Models\FixtureEvent;
use App\Models\FixtureEventType;
use App\Models\Player;
use App\Models\Team;

test('the fixture event model structure matches', function (): void {
    $fixtureEvent = FixtureEvent::factory()->create()->refresh();

    expect(array_keys($fixtureEvent->toArray()))->toBe([
        'id',
        'event_time',
        'event_period',
        'event_metadata',
        'fixture_id',
        'team_id',
        'player_id',
        'fixture_event_type_id',
        'created_at',
        'updated_at',
    ]);
});

test('a fixture belongs to a team. player, team and fixture event type', function (): void {
    $fixtureEvent = FixtureEvent::factory()->create();

    expect($fixtureEvent->fixture)->toBeInstanceOf(Fixture::class)
        ->and($fixtureEvent->team)->toBeInstanceOf(Team::class)
        ->and($fixtureEvent->player)->toBeInstanceOf(Player::class)
        ->and($fixtureEvent->eventType)->toBeInstanceOf(FixtureEventType::class);
});
