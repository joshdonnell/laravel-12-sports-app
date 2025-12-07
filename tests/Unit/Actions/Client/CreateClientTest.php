<?php

declare(strict_types=1);

use App\Actions\Client\CreateClient;
use App\Models\Client;
use App\Models\Sport;

it('creates a client', function (): void {
    $sport = Sport::factory()->create();

    $action = resolve(CreateClient::class);

    $client = $action->handle([
        'name' => 'New Client',
        'sport_id' => $sport->id,
    ]);

    expect($client)->toBeInstanceOf(Client::class)
        ->and($client->name)->toBe('New Client')
        ->and($client->sport_id)->toBe($sport->id);
});
