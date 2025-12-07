<?php

declare(strict_types=1);

use App\Enums\Permission;
use App\Models\Club;
use App\Models\Scopes\FilterSportScope;
use App\Models\Sport;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

it('returns a resource with the selected sport', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::CREATE_SPORT);
    $sportToFilter = Sport::factory()->create();
    $club = Club::factory()->create(['sport_id' => $sportToFilter->id]);
    Club::factory(3)->create();

    $sportToFilterId = Str($sportToFilter->id);

    $clubs = Club::query()->withGlobalScope('filterSport', new FilterSportScope($sportToFilterId, $user))->get();

    expect($clubs->count())->toBe(1)
        ->and($clubs->first()->name)->toBe($club->name);
});
