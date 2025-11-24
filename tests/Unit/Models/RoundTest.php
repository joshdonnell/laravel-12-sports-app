<?php

declare(strict_types=1);

use App\Models\Fixture;
use App\Models\Round;

test('the round model structure matches', function (): void {
    $round = Round::factory()->create()->refresh();

    expect(array_keys($round->toArray()))
        ->toBe([
            'id',
            'uuid',
            'name',
            'round_number',
            'sport_id',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
});

test('a round has many fixtures', function (): void {
    $round = Round::factory()->create()->refresh();
    Fixture::factory(3)->create([
        'round_id' => $round->id,
    ]);

    expect($round->fixtures)->toHaveCount(3)
        ->and($round->fixtures->first())->toBeInstanceOf(Fixture::class);
});
