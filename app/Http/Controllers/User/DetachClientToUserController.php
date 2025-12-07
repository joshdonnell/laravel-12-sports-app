<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Actions\User\DetachClientFromUser;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

final readonly class DetachClientToUserController
{
    public function __invoke(User $user, Client $client, DetachClientFromUser $action): RedirectResponse
    {
        Gate::authorize('clients', [$user, $client]);

        $action->handle($user, $client);

        return to_route('users.edit', ['user' => $user->uuid]);
    }
}
