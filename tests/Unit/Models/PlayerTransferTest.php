<?php

declare(strict_types=1);

use App\Models\Player;
use App\Models\PlayerTransfer;
use App\Models\Team;

test('the player transfer model structure matches', function (): void {
    $playerTransfer = PlayerTransfer::factory()->create()->refresh();

    expect(array_keys($playerTransfer->toArray()))
        ->toBe([
            'id',
            'player_id',
            'from_team_id',
            'to_team_id',
            'transfer_date',
            'created_at',
            'updated_at',
        ]);
});

test('a player transfer belongs to a player and team', function (): void {
    $playerTransfer = PlayerTransfer::factory()->create();

    expect($playerTransfer->player)->toBeInstanceOf(Player::class)
        ->and($playerTransfer->fromTeam)->toBeInstanceOf(Team::class)
        ->and($playerTransfer->toTeam)->toBeInstanceOf(Team::class);
});
