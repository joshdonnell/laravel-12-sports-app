<?php

declare(strict_types=1);

namespace App\Actions\Round;

use App\Models\Round;

final readonly class UpdateRound
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(Round $round, array $attributes): void
    {
        $round->update($attributes);
    }
}
