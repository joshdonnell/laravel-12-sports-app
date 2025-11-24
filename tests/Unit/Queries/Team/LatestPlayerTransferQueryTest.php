<?php

declare(strict_types=1);

use App\Models\Player;
use App\Models\PlayerTransfer;
use App\Queries\Team\LatestPlayerTransferQuery;
use Illuminate\Database\Eloquent\Builder;

it('returns a query builder', function (): void {
    $query = new LatestPlayerTransferQuery();

    $builder = $query->builder(Player::factory()->create());

    expect($builder)->toBeInstanceOf(Builder::class);
});

it('returns transfers in latest transfer_date order when they exist', function (): void {
    $player = Player::factory()->create();
    $transfers = PlayerTransfer::factory()->sequence(
        ['player_id' => $player->id, 'transfer_date' => now()->subDays(1)],
        ['player_id' => $player->id, 'transfer_date' => now()->subDays(2)],
        ['player_id' => $player->id, 'transfer_date' => now()->subDays(3)],
    )->count(3)->create();

    $query = new LatestPlayerTransferQuery();
    $playerTransfers = $query->builder($player)->get();
    $sortedTransfers = $transfers->sortByDesc('transfer_date')->values();

    expect($playerTransfers)->toHaveCount(3)
        ->and($playerTransfers->first())->toBeInstanceOf(PlayerTransfer::class)
        ->and($playerTransfers->pluck('id')->all())->toEqual($sortedTransfers->pluck('id')->all());
});

it('returns an empty collection when no player transfer is found', function (): void {
    $query = new LatestPlayerTransferQuery();
    $playerTransfer = $query->
    builder(Player::factory()->create())->get();

    expect($playerTransfer)->toHaveCount(0);
});
