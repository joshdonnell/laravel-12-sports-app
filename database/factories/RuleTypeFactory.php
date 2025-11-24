<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\RuleType;
use App\Models\Sport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RuleType>
 */
final class RuleTypeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'key' => fake()->slug(),
            'sport_id' => Sport::factory()->create(),
        ];
    }
}
