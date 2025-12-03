<?php

declare(strict_types=1);

namespace App\Actions\Sport;

use App\Models\Sport;

final readonly class UpdateSport
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(Sport $sport, array $attributes): void
    {
        $sport->update($attributes);
    }
}
