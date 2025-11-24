<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;

final readonly class RoundPolicy
{
    public function index(User $user): bool
    {
        return $user->can(Permission::LIST_ROUNDS);
    }

    public function create(User $user): bool
    {
        return $user->can(Permission::CREATE_ROUND);
    }

    public function store(User $user): bool
    {
        return $user->can(Permission::CREATE_ROUND);
    }

    public function edit(User $user): bool
    {
        return $user->can(Permission::UPDATE_ROUND);
    }

    public function update(User $user): bool
    {
        return $user->can(Permission::UPDATE_ROUND);
    }
}
