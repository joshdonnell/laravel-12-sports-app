<?php

declare(strict_types=1);

namespace App\Queries\Club;

use App\Models\Club;
use Illuminate\Database\Eloquent\Builder;

final readonly class AlphabeticalClubsQuery
{
    /**
     * @return Builder<Club>
     */
    public function builder(): Builder
    {
        return Club::query()
            ->orderBy('name');
    }
}
