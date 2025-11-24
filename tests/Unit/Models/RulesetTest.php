<?php

declare(strict_types=1);

use App\Models\Rule;
use App\Models\Ruleset;
use App\Models\Sport;

test('the ruleset model structure matches', function (): void {
    $ruleset = Ruleset::factory()->create()->refresh();

    expect(array_keys($ruleset->toArray()))
        ->toBe([
            'id',
            'uuid',
            'name',
            'description',
            'type',
            'sport_id',
            'created_at',
            'updated_at',
        ]);
});

test('a ruleset has many rules', function (): void {
    $ruleset = Ruleset::factory()->create();
    Rule::factory(3)->create([
        'ruleset_id' => $ruleset->id,
    ]);

    expect($ruleset->rules)->toHaveCount(3)
        ->and($ruleset->rules->first())->toBeInstanceOf(Rule::class);
});

test('a ruleset belongs to a sport', function (): void {
    $ruleset = Ruleset::factory()->create();

    expect($ruleset->sport)->toBeInstanceOf(Sport::class);
});
