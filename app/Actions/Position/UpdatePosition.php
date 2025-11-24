<?php

declare(strict_types=1);

namespace App\Actions\Position;

use App\Models\Position;

final readonly class UpdatePosition
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(Position $position, array $attributes): void
    {
        $position->update($attributes);
    }
}
