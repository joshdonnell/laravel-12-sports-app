<?php

declare(strict_types=1);

namespace App\Actions\Client;

use App\Models\Client;

final readonly class CreateClient
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes): Client
    {
        return Client::query()->create($attributes);
    }
}
