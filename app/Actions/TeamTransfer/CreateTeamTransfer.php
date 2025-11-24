<?php

declare(strict_types=1);

namespace App\Actions\TeamTransfer;

use App\Models\Player;
use App\Models\PlayerTransfer;
use App\Models\Team;

final readonly class CreateTeamTransfer
{
    public function handle(Player $player, ?Team $fromTeam = null, ?Team $toTeam = null): PlayerTransfer
    {
        return PlayerTransfer::query()->create([
            'player_id' => $player->id,
            'from_team_id' => $fromTeam?->id,
            'to_team_id' => $toTeam?->id,
            'transfer_date' => \Illuminate\Support\Facades\Date::now(),
        ]);
    }
}
