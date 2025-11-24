<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\FixtureEventType;
use App\Models\Stat;
use App\Models\StatsRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StatsRecord>
 */
final class StatsRecordFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'stat_id' => Stat::factory(),
            'fixture_event_type_id' => FixtureEventType::factory()->create(),
            'key' => fake()->name(),
            'value' => fake()->numberBetween(1, 100),
        ];
    }
}
