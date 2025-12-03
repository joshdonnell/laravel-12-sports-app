<?php

declare(strict_types=1);

use App\Actions\Team\CreateTeam;
use App\Models\Club;
use App\Models\Sport;
use App\Models\Team;

it('creates a team', function (): void {
    $action = resolve(CreateTeam::class);
    $club = Club::factory()->create();
    $sport = Sport::factory()->create();
    $team = $action->handle([
        'name' => 'Test Team',
        'club_id' => $club->id,
        'sport_id' => $sport->id,
    ]);
    expect($team)->toBeInstanceOf(Team::class);
});
