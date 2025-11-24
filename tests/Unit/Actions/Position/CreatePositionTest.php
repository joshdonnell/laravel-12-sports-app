<?php

declare(strict_types=1);

use App\Actions\Position\CreatePosition;
use App\Models\Position;
use App\Models\Sport;

it('creates a position', function (): void {
    $action = app(CreatePosition::class);
    $position = $action->handle([
        'name' => 'Test Position',
        'sport_id' => Sport::factory()->create()->id,
    ]);
    expect($position)->toBeInstanceOf(Position::class);
});
