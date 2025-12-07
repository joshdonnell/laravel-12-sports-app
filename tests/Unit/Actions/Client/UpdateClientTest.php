<?php

declare(strict_types=1);

use App\Actions\Client\UpdateClient;
use App\Models\Client;
use App\Models\Sport;

it('updates a client', function (): void {
    $client = Client::factory()->create();
    $sport = Sport::factory()->create();
    $action = resolve(UpdateClient::class);

    $action->handle($client, [
        'name' => 'Updated Client',
        'sport_id' => $sport->id,
    ]);

    expect($client->name)->toBe('Updated Client')
        ->and($client->sport_id)->toBe($sport->id);
});
