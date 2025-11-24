<?php

declare(strict_types=1);

use App\Models\RuleType;
use App\Models\Sport;

test('the rule type model structure matches', function (): void {
    $ruleType = RuleType::factory()->create()->refresh();

    expect(array_keys($ruleType->toArray()))
        ->toBe([
            'id',
            'name',
            'key',
            'sport_id',
            'created_at',
            'updated_at',
        ]);
});

test('a rule type belongs to a sport', function (): void {
    $ruleType = RuleType::factory()->create()->refresh();

    expect($ruleType->sport)->toBeInstanceOf(Sport::class);
});
