<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\NetballRuleTypes;
use App\Enums\TournamentTypes;
use App\Models\Rule;
use App\Models\Ruleset;
use App\Models\RuleType;
use App\Models\Sport;
use Illuminate\Database\Seeder;

final class NetballRuleSeeder extends Seeder
{
    public function run(Sport $sport): void
    {
        $ruleset = Ruleset::query()->create([
            'sport_id' => $sport->id,
            'name' => 'NSL',
            'type' => TournamentTypes::League->value,
        ]);

        foreach (NetballRuleTypes::cases() as $ruleTypeEnum) {
            $ruleType = RuleType::query()->create([
                'name' => $ruleTypeEnum->name,
                'key' => $ruleTypeEnum->value,
                'sport_id' => $sport->id,
            ]);

            $ruleValue = NetballRuleTypes::from($ruleTypeEnum->value)->exampleRulesByType();

            if (! empty($ruleValue)) {
                Rule::query()->create([
                    'ruleset_id' => $ruleset->id,
                    'rule_type_id' => $ruleType->id,
                    'value' => $ruleValue,
                ]);
            }
        }
    }
}
