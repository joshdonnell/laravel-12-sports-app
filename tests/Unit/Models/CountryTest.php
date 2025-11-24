<?php

declare(strict_types=1);

use App\Models\Country;
use App\Models\Player;

test('the country model structure matches', function (): void {
    $country = Country::factory()->create()->refresh();

    expect(array_keys($country->toArray()))
        ->toBe([
            'id',
            'name',
            'code',
            'flag',
            'created_at',
            'updated_at',
        ]);
});

test('a country had many players', function (): void {
    $country = Country::factory()->create();
    Player::factory(3)->create([
        'country_id' => $country->id,
    ]);

    expect($country->players)->toHaveCount(3)
        ->and($country->players->first())->toBeInstanceOf(Player::class);
});
