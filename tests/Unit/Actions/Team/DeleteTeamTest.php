<?php

declare(strict_types=1);

use App\Actions\Team\DeleteTeam;
use App\Models\Team;

it('deletes a team', function (): void {
    $team = Team::factory()->create();
    $action = app(DeleteTeam::class);
    $action->handle($team);
    expect($team->deleted_at)->not->toBeNull();
});
