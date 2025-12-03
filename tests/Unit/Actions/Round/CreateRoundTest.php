<?php

declare(strict_types=1);

use App\Actions\Round\CreateRound;
use App\Models\Round;
use App\Models\Sport;

it('creates a round', function (): void {
    $action = resolve(CreateRound::class);
    $sport = Sport::factory()->create();
    $round = $action->handle([
        'name' => 'Test Round',
        'round_number' => 1,
        'sport_id' => $sport->id,
    ]);
    expect($round)->toBeInstanceOf(Round::class);
});
