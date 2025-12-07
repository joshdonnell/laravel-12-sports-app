<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;

final readonly class SeasonPolicy
{
    public function index(User $user): bool
    {
        return $user->can(Permission::LIST_SEASONS);
    }

    public function create(User $user): bool
    {
        return $user->can(Permission::CREATE_SEASON);
    }

    public function store(User $user): bool
    {
        return $user->can(Permission::CREATE_SEASON);
    }

    public function edit(User $user): bool
    {
        return $user->can(Permission::UPDATE_SEASON);
    }

    public function update(User $user): bool
    {
        return $user->can(Permission::UPDATE_SEASON);
    }
}
