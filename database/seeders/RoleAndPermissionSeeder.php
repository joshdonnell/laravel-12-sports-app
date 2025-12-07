<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Permission;
use App\Enums\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Contracts\Role as ContractsRole;
use Spatie\Permission\Models\Role as ModelsRole;
use Spatie\Permission\PermissionRegistrar;

final class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        resolve(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (Permission::cases() as $permission) {
            \Spatie\Permission\Models\Permission::query()->updateOrCreate(['name' => $permission->value]);
        }

        foreach (Role::cases() as $role) {
            $role = ModelsRole::query()->updateOrCreate(['name' => $role->value]);

            $this->syncPermissionsToRole($role);
        }

        resolve(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function syncPermissionsToRole(ContractsRole $role): void
    {
        $permissions = [];

        switch ($role->name) {
            case Role::SuperAdmin->value:
                $permissions = Permission::cases();
                break;
            case Role::Admin->value:
                $permissions = [
                    Permission::LIST_CLIENTS,
                    Permission::CREATE_CLIENT,
                    Permission::UPDATE_CLIENT,

                    Permission::LIST_CLUBS,
                    Permission::CREATE_CLUB,
                    Permission::UPDATE_CLUB,
                    Permission::DELETE_CLUB,

                    Permission::LIST_FIXTURES,
                    Permission::CREATE_FIXTURE,
                    Permission::UPDATE_FIXTURE,
                    Permission::DELETE_FIXTURE,

                    Permission::LIST_PLAYERS,
                    Permission::CREATE_PLAYER,
                    Permission::UPDATE_PLAYER,
                    Permission::DELETE_PLAYER,

                    Permission::LIST_POSITIONS,
                    Permission::CREATE_POSITION,
                    Permission::UPDATE_POSITION,
                    Permission::DELETE_POSITION,

                    Permission::LIST_ROUNDS,
                    Permission::CREATE_ROUND,
                    Permission::UPDATE_ROUND,

                    Permission::LIST_RULESETS,
                    Permission::CREATE_RULESET,
                    Permission::UPDATE_RULESET,
                    Permission::DELETE_RULESET,

                    Permission::LIST_SCORING,

                    Permission::LIST_SEASONS,
                    Permission::CREATE_SEASON,
                    Permission::UPDATE_SEASON,

                    Permission::LIST_STANDINGS,

                    Permission::LIST_STATS,

                    Permission::LIST_TEAMS,
                    Permission::CREATE_TEAM,
                    Permission::UPDATE_TEAM,
                    Permission::DELETE_TEAM,

                    Permission::LIST_TOURNAMENTS,
                    Permission::CREATE_TOURNAMENT,
                    Permission::UPDATE_TOURNAMENT,
                    Permission::DELETE_TOURNAMENT,

                    Permission::LIST_USERS,
                    Permission::CREATE_USER,
                    Permission::UPDATE_USER,

                    Permission::LIST_VENUES,
                    Permission::CREATE_VENUE,
                    Permission::UPDATE_VENUE,
                    Permission::DELETE_VENUE,
                ];
                break;
            case Role::Editor->value:
                $permissions = [
                    Permission::LIST_CLUBS,
                    Permission::CREATE_CLUB,
                    Permission::UPDATE_CLUB,

                    Permission::LIST_FIXTURES,
                    Permission::CREATE_FIXTURE,
                    Permission::UPDATE_FIXTURE,

                    Permission::LIST_PLAYERS,
                    Permission::CREATE_PLAYER,
                    Permission::UPDATE_PLAYER,

                    Permission::LIST_SCORING,

                    Permission::LIST_STANDINGS,

                    Permission::LIST_STATS,

                    Permission::LIST_TEAMS,
                    Permission::CREATE_TEAM,
                    Permission::UPDATE_TEAM,

                    Permission::LIST_VENUES,
                    Permission::CREATE_VENUE,
                    Permission::UPDATE_VENUE,
                ];
                break;
        }

        $role->syncPermissions($permissions);
    }
}
