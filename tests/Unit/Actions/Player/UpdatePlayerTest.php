<?php

declare(strict_types=1);

use App\Actions\Player\UpdatePlayer;
use App\Models\Player;

it('updates a player', function (): void {
    $player = Player::factory()->create();
    $action = resolve(UpdatePlayer::class);
    $action->handle($player, ['first_name' => 'New Name']);
    expect($player->refresh()->first_name)->toBe('New Name');
});
