<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Fixture;
use App\Models\FixtureEvent;
use App\Models\FixtureEventType;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FixtureEvent>
 */
final class FixtureEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_time' => fake()->numberBetween(0, 100),
            'event_period' => fake()->numberBetween(0, 100),
            'event_metadata' => [],
            'fixture_id' => Fixture::factory()->create(),
            'team_id' => Team::factory()->create(),
            'player_id' => Player::factory()->create(),
            'fixture_event_type_id' => FixtureEventType::factory()->create(),
        ];
    }
}
