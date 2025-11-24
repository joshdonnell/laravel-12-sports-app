<?php

declare(strict_types=1);

namespace App\Actions\Season;

use App\Models\Season;

final readonly class CreateSeason
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes): Season
    {
        return Season::query()->create($attributes);
    }
}
