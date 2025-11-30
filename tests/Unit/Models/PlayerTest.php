<?php

declare(strict_types=1);

use App\Models\Country;
use App\Models\Player;
use App\Models\Position;
use App\Models\Sport;
use App\Models\Stat;
use App\Models\Team;

test('the player model structure matches', function (): void {
    $player = Player::factory()->create()->refresh();

    expect(array_keys($player->toArray()))
        ->toBe([
            'id',
            'uuid',
            'first_name',
            'last_name',
            'known_as',
            'match_name',
            'gender',
            'date_of_birth',
            'country_id',
            'height',
            'weight',
            'profile_picture',
            'sport_id',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
});

test('a player belongs to a country', function (): void {
    $player = Player::factory()->create();
    $country = Country::factory()->create();
    $player->country()->associate($country);
    $player->save();

    expect($player->country)->toBeInstanceOf(Country::class);
});

test('a player belongs to many positions', function (): void {
    $player = Player::factory()->hasPositions(3)->create();

    expect($player->positions)->toHaveCount(3)
        ->and($player->positions->first())->toBeInstanceOf(Position::class);
});

test('a player belongs to many teams', function (): void {
    $player = Player::factory()->hasTeams(3)->create();

    expect($player->teams)->toHaveCount(3)
        ->and($player->teams->first())->toBeInstanceOf(Team::class);
});

test('a player belongs to a sport', function (): void {
    $player = Player::factory()->create();

    expect($player->sport)->toBeInstanceOf(Sport::class);
});

test('a player can morph to many stats', function (): void {
    $player = Player::factory()->create()->refresh();
    Stat::factory(3)->create([
        'statable_id' => $player->id,
    ]);

    expect($player->stats)->toHaveCount(3)
        ->and($player->stats->first())->toBeInstanceOf(Stat::class);
});
