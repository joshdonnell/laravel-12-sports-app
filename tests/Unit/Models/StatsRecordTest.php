<?php

declare(strict_types=1);

use App\Models\StatsRecord;

test('the stat record model structure matches', function (): void {
    $statRecord = StatsRecord::factory()->create()->refresh();

    expect(array_keys($statRecord->toArray()))
        ->toBe([
            'id',
            'stat_id',
            'fixture_event_type_id',
            'key',
            'value',
            'created_at',
            'updated_at',
        ]);
});
