<?php

declare(strict_types=1);

use App\Actions\Client\DeleteClient;
use App\Models\Client;

it('soft deletes a client', function (): void {
    $client = Client::factory()->create();

    $action = resolve(DeleteClient::class);

    $action->handle($client);

    expect($client->deleted_at)->not->toBeNull();
});
