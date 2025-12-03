<?php

declare(strict_types=1);

namespace App\Queries\Sport;

use App\Models\Sport;
use Illuminate\Database\Eloquent\Builder;

final readonly class AlphabeticalSportsQuery
{
    /**
     * @return Builder<Sport>
     */
    public function builder(): Builder
    {
        return Sport::query()
            ->orderBy('name');
    }
}
