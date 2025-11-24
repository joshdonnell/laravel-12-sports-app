<?php

declare(strict_types=1);

namespace App\Actions\Player;

use App\Models\Player;

final readonly class DeletePlayer
{
    public function handle(Player $player): void
    {
        $player->delete();
    }
}
