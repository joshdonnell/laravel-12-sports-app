<?php

declare(strict_types=1);

use App\Models\Position;
use App\Models\Sport;

test('the position model structure matches', function (): void {
    $position = Position::factory()->create()->refresh();

    expect(array_keys($position->toArray()))
        ->toBe([
            'id',
            'uuid',
            'name',
            'known_as',
            'sport_id',
            'created_at',
            'updated_at',
        ]);
});

test('a position belongs to a sport', function (): void {
    $team = Position::factory()->create();

    expect($team->sport)->toBeInstanceOf(Sport::class);
});
