<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Client;
use App\Models\Sport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
final class ClientFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            'sport_id' => Sport::factory()->create(),
        ];
    }
}
