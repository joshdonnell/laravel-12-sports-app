<?php

declare(strict_types=1);

namespace App\Actions\Club;

use App\Models\Club;

final readonly class CreateClub
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes): Club
    {
        return Club::query()->create($attributes);
    }
}
