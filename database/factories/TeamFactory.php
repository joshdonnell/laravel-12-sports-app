<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Club;
use App\Models\Sport;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Team>
 */
final class TeamFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'short_name' => fake()->name(),
            'logo' => fake()->imageUrl(),
            'sport_id' => Sport::factory()->create(),
            'club_id' => Club::factory()->create(),
        ];
    }
}
