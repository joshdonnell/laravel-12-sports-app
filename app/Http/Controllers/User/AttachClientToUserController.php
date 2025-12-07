<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Actions\User\AttachClientToUser;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

final readonly class AttachClientToUserController
{
    public function __invoke(User $user, Client $client, AttachClientToUser $action): RedirectResponse
    {
        Gate::authorize('clients', [$user, $client]);

        $action->handle($user, $client);

        return to_route('users.edit', ['user' => $user->uuid]);
    }
}
