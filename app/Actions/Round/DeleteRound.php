<?php

declare(strict_types=1);

namespace App\Actions\Round;

use App\Models\Round;

final readonly class DeleteRound
{
    public function handle(Round $round): void
    {
        $round->delete();
    }
}
