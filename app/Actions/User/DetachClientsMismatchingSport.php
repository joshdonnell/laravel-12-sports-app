<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\Client;
use App\Models\User;

final readonly class DetachClientsMismatchingSport
{
    public function __construct(private DetachClientFromUser $detachClientFromUser) {}

    public function handle(User $user): void
    {
        $sport = $user->sport_id;
        $clients = $user->clients;

        if (empty($sport) || $clients->isEmpty()) {
            return;
        }

        $clientsToRemove = $clients->where('sport_id', '!=', $sport);
        $clientsToRemove->map(fn (Client $client) => $this->detachClientFromUser->handle($user, $client));
    }
}
