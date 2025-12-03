<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\Player;
use App\Models\Sport;
use App\Models\User;

beforeEach(function (): void {
    $this->seed(Database\Seeders\RoleAndPermissionSeeder::class);
});

it('returns sports of the given type when scoped to a model', function (): void {
    $sportA = Sport::factory()->create();
    $sportB = Sport::factory()->create();
    $user = User::factory()->create(['sport_id' => $sportA->id]);
    Player::factory()->create(['sport_id' => $sportA->id]);
    Player::factory()->create(['sport_id' => $sportB->id]);

    $this->actingAs($user);

    $players = Player::all();

    expect($players->count())->toBe(1)
        ->and($players->first()->sport_id)->toBe($sportA->id);
});

it('returns all sports of the given type when scoped to a model and a super admin', function (): void {
    $user = User::factory()->create();
    $user->syncRoles([Role::SuperAdmin]);
    $this->actingAs($user);

    Player::factory(5)->create();
    $players = Player::all();

    expect($players->count())->toBe(5);
});
