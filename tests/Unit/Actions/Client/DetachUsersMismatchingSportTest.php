<?php

declare(strict_types=1);

use App\Actions\Client\DetachUsersMismatchingSport;
use App\Models\Client;
use App\Models\Sport;
use App\Models\User;

it('will find users with different sports to the clients', function (): void {
    $sport = Sport::factory()->create();
    $client = Client::factory()->create([
        'sport_id' => $sport->id,
    ]);
    $user = User::factory()->create([
        'sport_id' => $sport->id,
    ]);
    $users = User::factory(3)->create();
    $client->users()->attach([...$users, $user]);

    expect($client->users)->toHaveCount(4);

    $action = resolve(DetachUsersMismatchingSport::class);

    $action->handle($client);

    $client->load('users');
    expect($client->users)->toHaveCount(1);
});
