<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Round;
use App\Models\Sport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Round>
 */
final class RoundFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'round_number' => fake()->numberBetween(0, 100),
            'sport_id' => Sport::factory()->create(),
        ];
    }
}
