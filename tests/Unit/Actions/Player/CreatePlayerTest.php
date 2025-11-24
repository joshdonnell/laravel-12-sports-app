<?php

declare(strict_types=1);

use App\Actions\Player\CreatePlayer;
use App\Models\Country;
use App\Models\Player;
use App\Models\Sport;

it('creates a player', function (): void {
    $country = Country::factory()->create();
    $sport = Sport::factory()->create();
    $action = app(CreatePlayer::class);
    $player = $action->handle([
        'first_name' => 'Test Player',
        'country_id' => $country->id,
        'sport_id' => $sport->id,
    ]);
    expect($player)->toBeInstanceOf(Player::class);
});
