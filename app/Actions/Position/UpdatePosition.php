<?php

declare(strict_types=1);

namespace App\Actions\Position;

use App\Models\Position;

final readonly class UpdatePosition
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(Position $position, array $attributes): void
    {
        $position->update($attributes);

        // TODO: When we build out player we need to add a side effect check if the sport_id
        // is updated we need to detach this from the existing players as they will
        // be part of a different sport
    }
}
