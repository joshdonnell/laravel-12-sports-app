<?php

declare(strict_types=1);

use App\Actions\User\DetachClientsMismatchingSport;
use App\Models\Client;
use App\Models\Sport;
use App\Models\User;

it('will find clients with different sports to the users', function (): void {
    $sport = Sport::factory()->create();
    $user = User::factory()->create([
        'sport_id' => $sport->id,
    ]);
    $client = Client::factory()->create([
        'sport_id' => $sport->id,
    ]);
    $clients = Client::factory(3)->create();
    $user->clients()->attach([...$clients, $client]);

    expect($user->clients)->toHaveCount(4);

    $action = resolve(DetachClientsMismatchingSport::class);

    $action->handle($user);

    $user->load('clients');
    expect($user->clients)->toHaveCount(1);
});
