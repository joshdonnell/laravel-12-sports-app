<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Rule;
use App\Models\Ruleset;
use App\Models\RuleType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rule>
 */
final class RuleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ruleset_id' => Ruleset::factory()->create(),
            'rule_type_id' => RuleType::factory()->create(),
            'value' => fake()->name(),
            'description' => fake()->sentence(),
        ];
    }
}
