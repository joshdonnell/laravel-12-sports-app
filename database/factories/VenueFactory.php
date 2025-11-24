<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Sport;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Venue>
 */
final class VenueFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'address' => fake()->address(),
            'website' => fake()->url(),
            'sport_id' => Sport::factory()->create(),
        ];
    }
}
