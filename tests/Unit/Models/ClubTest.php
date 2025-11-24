<?php

declare(strict_types=1);

use App\Models\Club;
use App\Models\Sport;
use App\Models\Team;

test('the club model structure matches', function (): void {
    $club = Club::factory()->create()->refresh();

    expect(array_keys($club->toArray()))
        ->toBe([
            'id',
            'uuid',
            'name',
            'known_as',
            'official_name',
            'code',
            'logo',
            'bio',
            'sport_id',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
});

test('a club has many teams', function (): void {
    $club = Club::factory()->create();
    Team::factory(3)->create([
        'club_id' => $club->id,
    ]);

    expect($club->teams)->toHaveCount(3)
        ->and($club->teams->first())->toBeInstanceOf(Team::class);
});

test('a client belongs to a sport', function (): void {
    $club = Club::factory()->create();

    expect($club->sport)->toBeInstanceOf(Sport::class);
});
