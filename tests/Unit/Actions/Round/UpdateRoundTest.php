<?php

declare(strict_types=1);

use App\Actions\Round\UpdateRound;
use App\Models\Round;

it('updates a round', function (): void {
    $round = Round::factory()->create();
    $action = app(UpdateRound::class);
    $action->handle($round, ['name' => 'New Name']);
    expect($round->name)->toBe('New Name');
});
