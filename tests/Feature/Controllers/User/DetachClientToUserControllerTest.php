<?php

declare(strict_types=1);

use App\Enums\Permission;
use App\Models\Client;
use App\Models\Sport;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

it('can detach a client from a user', function (): void {
    $sport = Sport::factory()->create();
    $user = User::factory()->create(
        ['sport_id' => $sport->id]
    );
    $client = Client::factory()->create(['sport_id' => $sport->id]);
    $user->clients()->attach($client);

    expect($user->clients)->toHaveCount(1)
        ->and($client->users->first()->id)->toEqual($client->id);

    $user->givePermissionTo(Permission::UPDATE_USER);

    $this->actingAs($user)->delete(route('users.detach-client', [$user, $client]));

    $user->load('clients');
    expect($user->clients)->toHaveCount(0);
});
