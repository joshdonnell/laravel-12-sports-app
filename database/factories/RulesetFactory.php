<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TournamentTypes;
use App\Models\Ruleset;
use App\Models\Sport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ruleset>
 */
final class RulesetFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->name(),
            'description' => fake()->sentence(),
            'type' => fake()->randomElement(array_column(TournamentTypes::cases(), 'value')),
            'sport_id' => Sport::factory()->create(),
        ];
    }
}
