<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;

final class SportPolicy
{
    public function index(User $user): bool
    {
        return $user->can(Permission::LIST_SPORTS);
    }

    public function create(User $user): bool
    {
        return $user->can(Permission::CREATE_SPORT);
    }

    public function store(User $user): bool
    {
        return $user->can(Permission::CREATE_SPORT);
    }

    public function edit(User $user): bool
    {

        return $user->can(Permission::UPDATE_SPORT);
    }

    public function update(User $user): bool
    {
        return $user->can(Permission::UPDATE_SPORT);
    }
}
