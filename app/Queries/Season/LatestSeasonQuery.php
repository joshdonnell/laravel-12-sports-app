<?php

declare(strict_types=1);

namespace App\Queries\Season;

use App\Models\Season;
use Illuminate\Database\Eloquent\Builder;

final readonly class LatestSeasonQuery
{
    /**
     * @return Builder<Season>
     */
    public function builder(): Builder
    {
        return Season::query()
            ->latest('updated_at');
    }
}
