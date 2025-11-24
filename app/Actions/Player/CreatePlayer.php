<?php

declare(strict_types=1);

namespace App\Actions\Player;

use App\Models\Player;

final readonly class CreatePlayer
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes): Player
    {
        return Player::query()->create($attributes);
    }
}
