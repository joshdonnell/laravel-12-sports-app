<?php

declare(strict_types=1);

namespace App\Actions\Sport;

use App\Models\Sport;

final readonly class CreateSport
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes): Sport
    {
        return Sport::query()->create($attributes);
    }
}
