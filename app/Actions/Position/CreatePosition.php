<?php

declare(strict_types=1);

namespace App\Actions\Position;

use App\Models\Position;

final readonly class CreatePosition
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes): Position
    {
        return Position::query()->create($attributes);
    }
}
