<?php

declare(strict_types=1);

use App\Models\FixtureEventType;

test('the fixture event type model structure matches', function (): void {
    $fixtureEventType = FixtureEventType::factory()->create()->refresh();

    expect(array_keys($fixtureEventType->toArray()))
        ->toBe([
            'id',
            'name',
            'key',
            'sport_id',
            'created_at',
            'updated_at',
        ]);
});
