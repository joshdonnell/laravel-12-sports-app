<?php

declare(strict_types=1);

namespace App\Actions\Venue;

use App\Models\Venue;

final readonly class CreateVenue
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes): Venue
    {
        return Venue::query()->create($attributes);
    }
}
