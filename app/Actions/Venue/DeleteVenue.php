<?php

declare(strict_types=1);

namespace App\Actions\Venue;

use App\Models\Venue;

final readonly class DeleteVenue
{
    public function handle(Venue $venue): void
    {
        $venue->delete();
    }
}
