<?php

declare(strict_types=1);

namespace App\Actions\Club;

use App\Models\Club;

final readonly class DeleteClub
{
    public function handle(Club $club): void
    {
        $club->delete();
    }
}
