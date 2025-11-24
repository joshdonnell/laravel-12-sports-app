<?php

declare(strict_types=1);

use App\Models\Season;

test('the season model structure matches', function (): void {
    $season = Season::factory()->create()->refresh();

    expect(array_keys($season->toArray()))
        ->toBe([
            'id',
            'uuid',
            'name',
            'created_at',
            'updated_at',
        ]);
});
