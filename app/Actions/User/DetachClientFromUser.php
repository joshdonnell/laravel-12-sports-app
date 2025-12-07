<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\Client;
use App\Models\User;

final readonly class DetachClientFromUser
{
    public function handle(User $user, Client $client): void
    {
        $user->clients()->detach($client);
    }
}
