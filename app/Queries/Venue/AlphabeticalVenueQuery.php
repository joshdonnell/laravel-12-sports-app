<?php

declare(strict_types=1);

namespace App\Queries\Venue;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Builder;

final readonly class AlphabeticalVenueQuery
{
    /**
     * @return Builder<Venue>
     */
    public function builder(): Builder
    {
        return Venue::query()
            ->orderBy('name');
    }
}
