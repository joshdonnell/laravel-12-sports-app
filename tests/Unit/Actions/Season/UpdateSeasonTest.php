<?php

declare(strict_types=1);

use App\Actions\Season\UpdateSeason;
use App\Models\Season;

it('updates a season', function (): void {
    $season = Season::factory()->create();
    $action = resolve(UpdateSeason::class);
    $action->handle($season, ['name' => 'New Name']);
    expect($season->refresh()->name)->toBe('New Name');
});
