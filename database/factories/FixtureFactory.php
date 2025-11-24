<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\FixtureStatus;
use App\Models\Fixture;
use App\Models\Round;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fixture>
 */
final class FixtureFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->sentence(),
            'week' => fake()->numberBetween(0, 100),
            'date' => fake()->date(),
            'status' => fake()->randomElement(array_column(FixtureStatus::cases(), 'value')),
            'home_team' => Team::factory()->create(),
            'away_team' => Team::factory()->create(),
            'home_team_score' => fake()->numberBetween(0, 100),
            'away_team_score' => fake()->numberBetween(0, 100),
            'home_team_bonus_score' => fake()->numberBetween(0, 100),
            'away_team_bonus_score' => fake()->numberBetween(0, 100),
            'venue_id' => Venue::factory()->create(),
            'tournament_id' => Tournament::factory()->create(),
            'round_id' => Round::factory()->create(),
        ];
    }
}
