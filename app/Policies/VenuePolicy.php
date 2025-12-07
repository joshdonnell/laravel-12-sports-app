<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\User;
use App\Models\Venue;

final class VenuePolicy
{
    public function index(User $user): bool
    {
        return $user->can(Permission::LIST_VENUES);
    }

    public function create(User $user): bool
    {
        return $user->can(Permission::CREATE_VENUE);
    }

    public function store(User $user): bool
    {
        return $user->can(Permission::CREATE_VENUE);
    }

    public function edit(User $user, Venue $venue): bool
    {
        if ($user->hasRole(Role::SuperAdmin) && $user->can(Permission::UPDATE_VENUE)) {
            return true;
        }

        return $venue->sport()->is($user->sport) && $user->can(Permission::UPDATE_VENUE);
    }

    public function update(User $user, Venue $venue): bool
    {
        if ($user->hasRole(Role::SuperAdmin) && $user->can(Permission::UPDATE_VENUE)) {
            return true;
        }

        return $venue->sport()->is($user->sport) && $user->can(Permission::UPDATE_VENUE);
    }

    public function destroy(User $user, Venue $venue): bool
    {
        if ($user->hasRole(Role::SuperAdmin) && $user->can(Permission::DELETE_VENUE)) {
            return true;
        }

        return $venue->sport()->is($user->sport) && $user->can(Permission::DELETE_VENUE);
    }
}
