<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Position;
use App\Models\Sport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Position>
 */
final class PositionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'known_as' => fake()->word(),
            'sport_id' => Sport::factory()->create(),
        ];
    }
}
