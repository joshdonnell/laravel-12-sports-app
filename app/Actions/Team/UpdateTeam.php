<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Models\Team;

final readonly class UpdateTeam
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(Team $team, array $attributes): void
    {
        $team->update($attributes);
    }
}
