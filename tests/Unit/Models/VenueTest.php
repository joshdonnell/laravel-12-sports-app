<?php

declare(strict_types=1);

use App\Models\Sport;
use App\Models\Venue;

test('the venue model structure matches', function (): void {
    $venue = Venue::factory()->create()->refresh();

    expect(array_keys($venue->toArray()))
        ->toBe([
            'id',
            'uuid',
            'name',
            'address',
            'website',
            'sport_id',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
});

test('a venue belongs to a sport', function (): void {
    $venue = Venue::factory()->create();

    expect($venue->sport)->toBeInstanceOf(Sport::class);
});
