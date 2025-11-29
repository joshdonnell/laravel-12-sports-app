<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\User;

final readonly class UserPolicy
{
    public function index(User $user): bool
    {
        return $user->can(Permission::LIST_USERS);
    }

    public function create(User $user): bool
    {
        return $user->can(Permission::CREATE_USER);
    }

    public function store(User $user): bool
    {
        return $user->can(Permission::CREATE_USER);
    }

    public function edit(User $user, User $model): bool
    {
        if ($user->hasRole(Role::SuperAdmin) && $user->can(Permission::UPDATE_USER)) {
            return true;
        }

        return $model->sport()->is($user->sport) && $user->can(Permission::UPDATE_USER);
    }

    public function update(User $user, User $model): bool
    {
        if ($user->hasRole(Role::SuperAdmin) && $user->can(Permission::UPDATE_USER)) {
            return true;
        }

        return $model->sport()->is($user->sport) && $user->can(Permission::UPDATE_USER);
    }

    public function destroy(User $user, User $model): bool
    {
        if ($user->hasRole(Role::SuperAdmin) && $user->can(Permission::DELETE_USER)) {
            return true;
        }

        return $model->sport()->is($user->sport) && $user->can(Permission::DELETE_USER);
    }
}
