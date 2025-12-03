<?php

declare(strict_types=1);

use App\Data\Shared\SelectData;
use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Sport;
use App\Models\User;
use App\Services\Inertia\SharedInertiaData;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

describe('getSports', function (): void {
    it('returns sports when the user can create sports', function (): void {
        $sport = Sport::factory()->create(['name' => 'Basketball']);
        $user = User::factory()->create(['sport_id' => $sport->id]);
        $user->givePermissionTo(Permission::CREATE_SPORT);
        $this->actingAs($user);

        $sharedInertiaData = resolve(SharedInertiaData::class);
        $sports = $sharedInertiaData->getSports();

        expect($sports)->toBeArray()
            ->and($sports)->not->toBeEmpty()
            ->and($sports[0])->toBeInstanceOf(SelectData::class);

        $basketballSport = collect($sports)->firstWhere('label', 'Basketball');
        expect($basketballSport)->not->toBeNull()
            ->and($basketballSport->value)->toBe((string) $sport->id)
            ->and($basketballSport->label)->toBe('Basketball');
    });

    it('returns null when the user cannot create sports', function (): void {
        $sport = Sport::factory()->create();
        $user = User::factory()->create(['sport_id' => $sport->id]);
        $this->actingAs($user);

        $sharedInertiaData = resolve(SharedInertiaData::class);
        $sports = $sharedInertiaData->getSports();

        expect($sports)->toBeNull();
    });

    it('returns multiple sports in alphabetical order', function (): void {
        $netball = Sport::factory()->create(['name' => 'Netball']);
        $basketball = Sport::factory()->create(['name' => 'Basketball']);
        $soccer = Sport::factory()->create(['name' => 'Soccer']);
        $user = User::factory()->create(['sport_id' => $basketball->id]);
        $user->givePermissionTo(Permission::CREATE_SPORT);
        $this->actingAs($user);

        $sharedInertiaData = resolve(SharedInertiaData::class);
        $sports = $sharedInertiaData->getSports();

        expect($sports)->toBeArray()
            ->and($sports)->not->toBeEmpty();

        $labels = collect($sports)->pluck('label')->toArray();
        $ourSports = array_intersect($labels, ['Basketball', 'Netball', 'Soccer']);
        $ourSportsOrdered = array_values($ourSports);

        expect($ourSportsOrdered)->toBe(['Basketball', 'Netball', 'Soccer']);
    });
});

describe('getAssignableRoles', function (): void {
    it('returns all roles for super admin', function (): void {
        $user = User::factory()->create();
        $user->syncRoles([Role::SuperAdmin]);
        $this->actingAs($user);

        $sharedInertiaData = resolve(SharedInertiaData::class);
        $roles = $sharedInertiaData->getAssignableRoles();

        expect($roles)->toBeArray()
            ->and($roles)->toHaveCount(4)
            ->and($roles[0])->toBeInstanceOf(SelectData::class)
            ->and($roles[0]->value)->toBe('super-admin')
            ->and($roles[0]->label)->toBe('Super Admin')
            ->and($roles[1]->value)->toBe('admin')
            ->and($roles[1]->label)->toBe('Admin')
            ->and($roles[2]->value)->toBe('editor')
            ->and($roles[2]->label)->toBe('Editor')
            ->and($roles[3]->value)->toBe('user')
            ->and($roles[3]->label)->toBe('User');
    });

    it('returns all roles except super admin for admin', function (): void {
        $user = User::factory()->create();
        $user->syncRoles([Role::Admin]);
        $this->actingAs($user);

        $sharedInertiaData = resolve(SharedInertiaData::class);
        $roles = $sharedInertiaData->getAssignableRoles();

        expect($roles)->toBeArray()
            ->and($roles)->toHaveCount(3)
            ->and($roles[0]->value)->toBe('admin')
            ->and($roles[0]->label)->toBe('Admin')
            ->and($roles[1]->value)->toBe('editor')
            ->and($roles[1]->label)->toBe('Editor')
            ->and($roles[2]->value)->toBe('user')
            ->and($roles[2]->label)->toBe('User');
    });

    it('returns null for editor', function (): void {
        $user = User::factory()->create();
        $user->syncRoles([Role::Editor]);
        $this->actingAs($user);

        $sharedInertiaData = resolve(SharedInertiaData::class);
        $roles = $sharedInertiaData->getAssignableRoles();

        expect($roles)->toBeNull();
    });

    it('returns null for user', function (): void {
        $user = User::factory()->create();
        $user->syncRoles([Role::User]);
        $this->actingAs($user);

        $sharedInertiaData = resolve(SharedInertiaData::class);
        $roles = $sharedInertiaData->getAssignableRoles();

        expect($roles)->toBeNull();
    });

    it('returns null for user with no role', function (): void {
        $user = User::factory()->create();
        $this->actingAs($user);

        $sharedInertiaData = resolve(SharedInertiaData::class);
        $roles = $sharedInertiaData->getAssignableRoles();

        expect($roles)->toBeNull();
    });
});
