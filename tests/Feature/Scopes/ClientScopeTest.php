<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\Client;
use App\Models\Scopes\SportScope;
use App\Models\Tournament;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

it('returns client of the given type when scoped to a model', function (): void {
    $client = Client::factory()->create();
    $user = User::factory()->hasAttached($client)->create();
    $user->load('clients');

    $tournamentWithoutClient = Tournament::factory()->create();
    $tournamentWithClient = Tournament::factory()->hasAttached($client)->create();
    $tournamentWithClient->load('clients');

    $this->actingAs($user);

    $tournaments = Tournament::query()->withoutGlobalScope(SportScope::class)->get();

    expect($tournaments)->toHaveCount(1);
});

it('returns all client of the given type when scoped to a model and a super admin', function (): void {
    $user = User::factory()->create();
    $user->syncRoles([Role::SuperAdmin]);
    $this->actingAs($user);

    Tournament::factory(5)->hasClients()->create();
    $tournaments = Tournament::all();

    expect($tournaments->count())->toBe(5);
});
