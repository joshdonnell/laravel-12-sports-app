<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\FixtureEventType;
use App\Models\Sport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FixtureEventType>
 */
final class FixtureEventTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'key' => fake()->slug(),
            'sport_id' => Sport::factory()->create(),
        ];
    }
}
