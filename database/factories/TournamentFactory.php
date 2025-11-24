<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Ruleset;
use App\Models\Sport;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tournament>
 */
final class TournamentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'code' => fake()->unique()->regexify('[A-Z0-9]{6}'),
            'exclude_stats' => fake()->boolean(),
            'sport_id' => Sport::factory()->create(),
            'ruleset_id' => Ruleset::factory()->create(),
        ];
    }
}
