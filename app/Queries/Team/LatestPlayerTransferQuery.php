<?php

declare(strict_types=1);

namespace App\Queries\Team;

use App\Models\Player;
use App\Models\PlayerTransfer;
use Illuminate\Database\Eloquent\Builder;

final readonly class LatestPlayerTransferQuery
{
    /**
     * @return Builder<PlayerTransfer>
     */
    public function builder(Player $player): Builder
    {
        return PlayerTransfer::query()
            ->where('player_id', $player->id)
            ->latest('transfer_date');
    }
}
