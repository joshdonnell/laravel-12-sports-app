<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Models\Team;

final readonly class DeleteTeam
{
    public function handle(Team $team): void
    {
        $team->delete();
    }
}
