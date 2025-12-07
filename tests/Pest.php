<?php

declare(strict_types=1);

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Sleep;
use Illuminate\Support\Str;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->beforeEach(function (): void {
        Str::createRandomStringsNormally();
        Str::createUuidsNormally();
        Http::preventStrayRequests();
        Process::preventStrayProcesses();
        Sleep::fake();

        $this->freezeTime();
    })
    ->in('Browser', 'Feature', 'Unit');

expect()->extend('toBeOne', fn () => $this->toBe(1));

function createUserWithPermission(Permission $permission, ?Sport $sport = null): User
{
    $user = User::factory()->create($sport instanceof Sport ? ['sport_id' => $sport->id] : []);
    $user->givePermissionTo($permission->value);

    return $user;
}

function createUserWithRole(Role $role, ?Sport $sport = null): User
{
    $user = User::factory()->create($sport instanceof Sport ? ['sport_id' => $sport->id] : []);
    $user->assignRole($role);

    return $user;
}

function createSport(): Sport
{
    return Sport::factory()->create(['name' => 'Netball']);
}
