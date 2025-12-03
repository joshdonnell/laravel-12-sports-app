<?php

declare(strict_types=1);

use App\Actions\Player\DeletePlayer;
use App\Models\Player;

it('deletes a player', function (): void {
    $player = Player::factory()->create();
    $action = resolve(DeletePlayer::class);
    $action->handle($player);
    expect($player->deleted_at)->not->toBeNull();
});
