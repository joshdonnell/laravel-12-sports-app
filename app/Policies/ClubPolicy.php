<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Club;
use App\Models\User;

final class ClubPolicy
{
    public function index(User $user): bool
    {
        return $user->can(Permission::LIST_CLUBS);
    }

    public function create(User $user): bool
    {
        return $user->can(Permission::CREATE_CLUB);
    }

    public function store(User $user): bool
    {
        return $user->can(Permission::CREATE_CLUB);
    }

    public function edit(User $user, Club $club): bool
    {
        if ($user->hasRole(Role::SuperAdmin) && $user->can(Permission::UPDATE_CLUB)) {
            return true;
        }

        return $club->sport()->is($user->sport) && $user->can(Permission::UPDATE_CLUB);
    }

    public function update(User $user, Club $club): bool
    {
        if ($user->hasRole(Role::SuperAdmin) && $user->can(Permission::UPDATE_CLUB)) {
            return true;
        }

        return $club->sport()->is($user->sport) && $user->can(Permission::UPDATE_CLUB);
    }

    public function destroy(User $user, Club $club): bool
    {
        if ($user->hasRole(Role::SuperAdmin) && $user->can(Permission::DELETE_CLUB)) {
            return true;
        }

        return $club->sport()->is($user->sport) && $user->can(Permission::DELETE_CLUB);
    }
}
