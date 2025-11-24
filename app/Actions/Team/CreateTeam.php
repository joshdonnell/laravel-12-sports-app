<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Models\Team;

final readonly class CreateTeam
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes): Team
    {
        return Team::query()->create($attributes);
    }
}
