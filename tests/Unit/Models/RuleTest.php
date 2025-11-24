<?php

declare(strict_types=1);

use App\Models\Rule;
use App\Models\Ruleset;
use App\Models\RuleType;

test('the rule model structure matches', function (): void {
    $rule = Rule::factory()->create()->refresh();

    expect(array_keys($rule->toArray()))
        ->toBe([
            'id',
            'ruleset_id',
            'rule_type_id',
            'value',
            'description',
            'created_at',
            'updated_at',
        ]);
});

test('a rule belongs to a ruleset and rule type', function (): void {
    $rule = Rule::factory()->create();

    expect($rule->ruleset)->toBeInstanceOf(Ruleset::class)
        ->and($rule->ruleType)->toBeInstanceOf(RuleType::class);
});
