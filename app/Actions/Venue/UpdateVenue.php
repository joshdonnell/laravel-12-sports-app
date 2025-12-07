<?php

declare(strict_types=1);

namespace App\Actions\Venue;

use App\Models\Venue;

final readonly class UpdateVenue
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(Venue $venue, array $attributes): void
    {
        $venue->update($attributes);
    }
}
