<?php

declare(strict_types=1);

namespace App\Actions\Player;

use App\Models\Player;

final readonly class UpdatePlayer
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(Player $player, array $attributes): void
    {
        $player->update($attributes);
    }
}
