<?php

declare(strict_types=1);

namespace App\Actions\Round;

use App\Models\Round;

final readonly class CreateRound
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes): Round
    {
        return Round::query()->create($attributes);
    }
}
