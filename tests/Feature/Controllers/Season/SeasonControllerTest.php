<?php

declare(strict_types=1);

use App\Enums\Permission;
use App\Models\Season;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

it('renders seasons index page', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::LIST_SEASONS->value);

    Season::factory()->count(3)->create();

    $response = $this->actingAs($user)
        ->fromRoute('dashboard')
        ->get(route('seasons.index'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('season/Index')
            ->has('seasons'));
});

it('denies access to seasons index without permission', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('seasons.index'));

    $response->assertForbidden();
});

it('renders season create page', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::CREATE_SEASON->value);

    $response = $this->actingAs($user)
        ->fromRoute('seasons.index')
        ->get(route('seasons.create'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('season/Create'));
});

it('denies access to season create without permission', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('seasons.create'));

    $response->assertForbidden();
});

it('may create a season', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::CREATE_SEASON->value);

    $response = $this->actingAs($user)
        ->fromRoute('seasons.create')
        ->post(route('seasons.store'), [
            'name' => '2024 Season',
        ]);

    $response->assertRedirectToRoute('seasons.index');

    expect(Season::query()->where('name', '2024 Season')->exists())->toBeTrue();
});

it('denies season creation without permission', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('seasons.store'), [
            'name' => '2024 Season',
        ]);

    $response->assertForbidden();
});

it('requires name when creating season', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::CREATE_SEASON->value);

    $response = $this->actingAs($user)
        ->fromRoute('seasons.create')
        ->post(route('seasons.store'), []);

    $response->assertRedirectToRoute('seasons.create')
        ->assertSessionHasErrors('name');
});

it('enforces max length for name when creating season', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::CREATE_SEASON->value);

    $response = $this->actingAs($user)
        ->fromRoute('seasons.create')
        ->post(route('seasons.store'), [
            'name' => str_repeat('a', 256),
        ]);

    $response->assertRedirectToRoute('seasons.create')
        ->assertSessionHasErrors('name');
});

it('renders season edit page', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::UPDATE_SEASON->value);

    $season = Season::factory()->create();

    $response = $this->actingAs($user)
        ->fromRoute('seasons.index')
        ->get(route('seasons.edit', $season));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('season/Edit')
            ->has('season'));
});

it('denies access to season edit without permission', function (): void {
    $user = User::factory()->create();
    $season = Season::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('seasons.edit', $season));

    $response->assertForbidden();
});

it('may update a season', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::UPDATE_SEASON->value);

    $season = Season::factory()->create(['name' => 'Old Season Name']);

    $response = $this->actingAs($user)
        ->fromRoute('seasons.edit', $season)
        ->patch(route('seasons.update', $season), [
            'name' => 'New Season Name',
        ]);

    $response->assertRedirectToRoute('seasons.index');

    expect($season->refresh()->name)->toBe('New Season Name');
});

it('denies season update without permission', function (): void {
    $user = User::factory()->create();
    $season = Season::factory()->create(['name' => 'Old Season Name']);

    $response = $this->actingAs($user)
        ->patch(route('seasons.update', $season), [
            'name' => 'New Season Name',
        ]);

    $response->assertForbidden();
});

it('requires name when updating season', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::UPDATE_SEASON->value);

    $season = Season::factory()->create();

    $response = $this->actingAs($user)
        ->fromRoute('seasons.edit', $season)
        ->patch(route('seasons.update', $season), []);

    $response->assertRedirectToRoute('seasons.edit', $season)
        ->assertSessionHasErrors('name');
});

it('enforces max length for name when updating season', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::UPDATE_SEASON->value);

    $season = Season::factory()->create();

    $response = $this->actingAs($user)
        ->fromRoute('seasons.edit', $season)
        ->patch(route('seasons.update', $season), [
            'name' => str_repeat('a', 256),
        ]);

    $response->assertRedirectToRoute('seasons.edit', $season)
        ->assertSessionHasErrors('name');
});
