<?php

declare(strict_types=1);

namespace App\Actions\Club;

use App\Models\Club;

final readonly class UpdateClub
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(Club $club, array $attributes): void
    {
        $club->update($attributes);
    }
}
