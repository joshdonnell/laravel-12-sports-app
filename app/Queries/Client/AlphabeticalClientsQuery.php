<?php

declare(strict_types=1);

namespace App\Queries\Client;

use App\Models\Client;
use App\Models\Scopes\SportScope;
use Illuminate\Database\Eloquent\Builder;

final readonly class AlphabeticalClientsQuery
{
    /**
     * @return Builder<Client>
     */
    public function builder(): Builder
    {
        return Client::query()
            ->withGlobalScope('sport', new SportScope())
            ->orderBy('name');
    }
}
