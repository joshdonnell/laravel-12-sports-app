<?php

declare(strict_types=1);

namespace App\Actions\Client;

use App\Actions\User\DetachClientFromUser;
use App\Models\Client;
use App\Models\User;

final readonly class DetachUsersMismatchingSport
{
    public function __construct(private DetachClientFromUser $detachClientFromUser) {}

    public function handle(Client $client): void
    {
        $sport = $client->sport_id;
        $users = $client->users;

        if (empty($sport) || $users->isEmpty()) {
            return;
        }

        $clientsToRemove = $users->where('sport_id', '!=', $sport);
        $clientsToRemove->map(fn (User $user) => $this->detachClientFromUser->handle($user, $client));
    }
}
