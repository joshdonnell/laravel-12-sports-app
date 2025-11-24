<?php

declare(strict_types=1);

namespace App\Actions\Season;

use App\Models\Season;

final readonly class UpdateSeason
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(Season $season, array $attributes): void
    {
        $season->update($attributes);
    }
}
