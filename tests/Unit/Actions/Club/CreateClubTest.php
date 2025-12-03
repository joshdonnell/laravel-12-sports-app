<?php

declare(strict_types=1);

use App\Actions\Club\CreateClub;
use App\Models\Club;
use App\Models\Sport;

it('creates a club', function (): void {
    $action = resolve(CreateClub::class);

    $club = $action->handle([
        'name' => 'Test Club',
        'known_as' => 'Test Club',
        'official_name' => 'Test Club',
        'code' => 'TC',
        'sport_id' => Sport::factory()->create()->id,
    ]);

    expect($club)->toBeInstanceOf(Club::class);
});
