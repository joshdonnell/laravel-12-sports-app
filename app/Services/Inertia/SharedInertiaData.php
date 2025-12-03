<?php

declare(strict_types=1);

namespace App\Services\Inertia;

use App\Data\Shared\SelectData;
use App\Enums\Permission;
use App\Enums\Role;
use App\Models\User;
use App\Queries\Sport\AlphabeticalSportsQuery;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Str;

final readonly class SharedInertiaData
{
    public function __construct(
        #[CurrentUser()] private User $user,
        private AlphabeticalSportsQuery $sportsQuery
    ) {}

    /**
     * @return array<SelectData>|null
     */
    public function getSports(): ?array
    {
        if (! $this->user->can(Permission::CREATE_SPORT)) {
            return null;
        }

        return SelectData::pick(
            'id',
            'name',
            null,
            $this->sportsQuery->builder()->get()->toArray()
        );
    }

    /**
     * @return array<SelectData>|null
     */
    public function getAssignableRoles(): ?array
    {
        $availableRoles = match (true) {
            $this->user->hasRole(Role::SuperAdmin) => Role::cases(),
            $this->user->hasRole(Role::Admin) => $this->getRolesExcept(Role::SuperAdmin),
            default => [],
        };

        if ($availableRoles === []) {
            return null;
        }

        return array_map(
            fn (Role $role): SelectData => new SelectData(
                $role->value,
                $this->formatRoleName($role->name),
                null
            ),
            $availableRoles
        );
    }

    /**
     * @return array<Role>
     */
    private function getRolesExcept(Role $excludedRole): array
    {
        return array_values(
            array_filter(
                Role::cases(),
                fn (Role $role): bool => $role !== $excludedRole
            )
        );
    }

    private function formatRoleName(string $name): string
    {
        return implode(' ', Str::ucsplit($name));
    }
}
