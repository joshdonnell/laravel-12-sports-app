<?php

declare(strict_types=1);

use App\Actions\Team\UpdateTeam;
use App\Models\Team;

it('updates a team', function (): void {
    $team = Team::factory()->create();
    $action = resolve(UpdateTeam::class);
    $action->handle($team, ['name' => 'New Name']);
    expect($team->name)->toBe('New Name');
});
