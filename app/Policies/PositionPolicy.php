<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Position;
use App\Models\User;

final class PositionPolicy
{
    public function index(User $user): bool
    {
        return $user->can(Permission::LIST_POSITIONS);
    }

    public function create(User $user): bool
    {
        return $user->can(Permission::CREATE_POSITION);
    }

    public function store(User $user): bool
    {
        return $user->can(Permission::CREATE_POSITION);
    }

    public function edit(User $user, Position $position): bool
    {
        if ($user->hasRole(Role::SuperAdmin) && $user->can(Permission::UPDATE_POSITION)) {
            return true;
        }

        return $position->sport()->is($user->sport) && $user->can(Permission::UPDATE_POSITION);
    }

    public function update(User $user, Position $position): bool
    {
        if ($user->hasRole(Role::SuperAdmin) && $user->can(Permission::UPDATE_POSITION)) {
            return true;
        }

        return $position->sport()->is($user->sport) && $user->can(Permission::UPDATE_POSITION);
    }

    public function destroy(User $user, Position $position): bool
    {
        if ($user->hasRole(Role::SuperAdmin) && $user->can(Permission::DELETE_POSITION)) {
            return true;
        }

        return $position->sport()->is($user->sport) && $user->can(Permission::DELETE_POSITION);
    }
}
