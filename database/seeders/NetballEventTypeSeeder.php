<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\NetballFixtureEventTypes;
use App\Models\FixtureEventType;
use App\Models\Sport;
use Illuminate\Database\Seeder;

final class NetballEventTypeSeeder extends Seeder
{
    public function run(Sport $sport): void
    {

        foreach (NetballFixtureEventTypes::cases() as $fixtureEventType) {
            FixtureEventType::query()->create([
                'name' => $fixtureEventType->name,
                'key' => $fixtureEventType->value,
                'sport_id' => $sport->id,
            ]);
        }
    }
}
