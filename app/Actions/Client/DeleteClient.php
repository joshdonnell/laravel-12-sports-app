<?php

declare(strict_types=1);

namespace App\Actions\Client;

use App\Models\Client;

final readonly class DeleteClient
{
    public function handle(Client $client): void
    {
        $client->delete();
    }
}
