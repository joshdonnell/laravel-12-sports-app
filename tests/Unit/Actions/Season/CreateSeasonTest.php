<?php

declare(strict_types=1);

use App\Actions\Season\CreateSeason;
use App\Models\Season;

it('creates a season', function (): void {
    $action = app(CreateSeason::class);
    $season = $action->handle([
        'name' => 'Test Season',
    ]);
    expect($season)->toBeInstanceOf(Season::class);
});
