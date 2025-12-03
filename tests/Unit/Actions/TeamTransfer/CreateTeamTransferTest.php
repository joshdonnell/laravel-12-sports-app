<?php

declare(strict_types=1);

use App\Actions\TeamTransfer\CreateTeamTransfer;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

it('creates a team transfer', function (): void {
    $action = resolve(CreateTeamTransfer::class);
    $player = Player::factory()->create();
    $fromTeam = Team::factory()->create();
    $toTeam = Team::factory()->create();
    $action->handle($player, $fromTeam, $toTeam);
    $playerTransfers = DB::table('player_transfers')->where('player_id', $player->id)->get();

    expect($playerTransfers)->toHaveCount(1);
});

it('creates a team transfer without from team', function (): void {
    $action = resolve(CreateTeamTransfer::class);
    $player = Player::factory()->create();
    $toTeam = Team::factory()->create();
    $action->handle($player, null, $toTeam);
    $playerTransfers = DB::table('player_transfers')->where('player_id', $player->id)->get();

    expect($playerTransfers)->toHaveCount(1);
    expect($playerTransfers->first()->from_team_id)->toBeNull();
});

it('creates a team transfer without to team', function (): void {
    $action = resolve(CreateTeamTransfer::class);
    $player = Player::factory()->create();
    $fromTeam = Team::factory()->create();
    $action->handle($player, $fromTeam);
    $playerTransfers = DB::table('player_transfers')->where('player_id', $player->id)->get();

    expect($playerTransfers)->toHaveCount(1);
    expect($playerTransfers->first()->to_team_id)->toBeNull();
});
