<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Player;
use App\Models\PlayerTransfer;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PlayerTransfer>
 */
final class PlayerTransferFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'player_id' => Player::factory()->create(),
            'from_team_id' => Team::factory()->create(),
            'to_team_id' => Team::factory()->create(),
            'transfer_date' => fake()->date(),
        ];
    }

    public function newPlayerWithoutFromTeam(): self
    {
        return $this->state(fn (): array => [
            'from_team_id' => null,
        ]);
    }

    public function existingPlayerToNoTeam(): self
    {
        return $this->state(fn (): array => [
            'to_team_id' => null,
        ]);
    }
}
