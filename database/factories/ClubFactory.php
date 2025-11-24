<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Club;
use App\Models\Sport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Club>
 */
final class ClubFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'known_as' => fake()->name(),
            'official_name' => fake()->name(),
            'code' => fake()->unique()->regexify('[A-Z0-9]{2}'),
            'logo' => fake()->imageUrl(),
            'bio' => fake()->paragraph(),
            'sport_id' => Sport::factory()->create(),
        ];
    }
}
