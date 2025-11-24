<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Season;
use App\Models\Standing;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Standing>
 */
final class StandingFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'position' => fake()->numberBetween(1, 10),
            'points' => fake()->numberBetween(0, 100),
            'bonus_points' => fake()->numberBetween(0, 100),
            'played' => fake()->numberBetween(0, 100),
            'won' => fake()->numberBetween(0, 100),
            'drawn' => fake()->numberBetween(0, 100),
            'lost' => fake()->numberBetween(0, 100),
            'score_for' => fake()->numberBetween(0, 100),
            'score_against' => fake()->numberBetween(0, 100),
            'score_difference' => fake()->numberBetween(0, 100),
            'season_id' => Season::factory()->create(),
            'tournament_id' => Tournament::factory()->create(),
            'team_id' => Team::factory()->create(),
        ];
    }
}
