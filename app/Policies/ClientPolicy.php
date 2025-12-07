<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Client;
use App\Models\User;

final class ClientPolicy
{
    public function index(User $user): bool
    {
        return $user->can(Permission::LIST_CLIENTS);
    }

    public function create(User $user): bool
    {
        return $user->can(Permission::CREATE_CLIENT);
    }

    public function store(User $user): bool
    {
        return $user->can(Permission::CREATE_CLIENT);
    }

    public function edit(User $user, Client $client): bool
    {
        if ($user->hasRole(Role::SuperAdmin) && $user->can(Permission::UPDATE_CLIENT)) {
            return true;
        }

        return $client->sport()->is($user->sport) && $user->can(Permission::UPDATE_CLIENT);
    }

    public function update(User $user, Client $client): bool
    {
        if ($user->hasRole(Role::SuperAdmin) && $user->can(Permission::UPDATE_CLIENT)) {
            return true;
        }

        return $client->sport()->is($user->sport) && $user->can(Permission::UPDATE_CLIENT);
    }

    public function destroy(User $user, Client $client): bool
    {
        if ($user->hasRole(Role::SuperAdmin) && $user->can(Permission::DELETE_CLIENT)) {
            return true;
        }

        return $client->sport()->is($user->sport) && $user->can(Permission::DELETE_CLIENT);
    }
}
