<?php

declare(strict_types=1);

namespace App\Queries\Round;

use App\Models\Round;
use Illuminate\Database\Eloquent\Builder;

final readonly class AlphabeticalRoundQuery
{
    /**
     * @return Builder<Round>
     */
    public function builder(): Builder
    {
        return Round::query()
            ->orderBy('name');
    }
}
