<?php

declare(strict_types=1);

namespace App\Queries\Position;

use App\Models\Position;
use Illuminate\Database\Eloquent\Builder;

final readonly class AlphabeticalPositionQuery
{
    /**
     * @return Builder<Position>
     */
    public function builder(): Builder
    {
        return Position::query()
            ->orderBy('name');
    }
}
