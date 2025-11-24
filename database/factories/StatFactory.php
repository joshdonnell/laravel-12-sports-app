<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Fixture;
use App\Models\Player;
use App\Models\Season;
use App\Models\Stat;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Stat>
 */
final class StatFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'statable_type' => Player::class,
            'statable_id' => Player::factory()->create(),
            'season_id' => Season::factory()->create(),
            'fixture_id' => Fixture::factory()->create(),
            'tournament_id' => Tournament::factory()->create(),
        ];
    }

    public function teamStats(): self
    {
        return $this->state(fn (): array => [
            'statable_type' => Team::class,
            'statable_id' => Team::factory()->create(),
        ]);
    }

    public function playerStats(): self
    {
        return $this->state(fn (): array => [
            'fixture_id' => null,
            'tournament_id' => null,
        ]);
    }
}
