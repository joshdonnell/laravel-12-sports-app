<?php

declare(strict_types=1);

use App\Enums\Permission;
use App\Models\Sport;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

describe('index', function (): void {
    it('renders sports index page', function (): void {
        $user = createUserWithPermission(Permission::LIST_SPORTS);

        Sport::factory()->count(3)->create();

        $response = $this->actingAs($user)
            ->fromRoute('dashboard')
            ->get(route('sports.index'));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('sport/Index')
                ->has('sports'));
    });

    it('denies access without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('sports.index'));

        $response->assertForbidden();
    });

    it('filters sports by search term', function (): void {
        $user = createUserWithPermission(Permission::LIST_SPORTS);

        Sport::factory()->create(['name' => 'Basketball']);
        Sport::factory()->create(['name' => 'Football']);
        Sport::factory()->create(['name' => 'Netball']);

        $response = $this->actingAs($user)
            ->get(route('sports.index', ['search' => 'ball']));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('sport/Index')
                ->where('search', 'ball')
                ->has('sports.data', 3));
    });
});

describe('create', function (): void {
    it('renders sport create page', function (): void {
        $user = createUserWithPermission(Permission::CREATE_SPORT);

        $response = $this->actingAs($user)
            ->fromRoute('sports.index')
            ->get(route('sports.create'));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('sport/Create'));
    });

    it('denies access without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('sports.create'));

        $response->assertForbidden();
    });
});

describe('store', function (): void {
    it('may create a sport', function (): void {
        $user = createUserWithPermission(Permission::CREATE_SPORT);

        $response = $this->actingAs($user)
            ->fromRoute('sports.create')
            ->post(route('sports.store'), [
                'name' => 'Basketball',
            ]);

        $response->assertRedirectToRoute('sports.index');
        expect(Sport::query()->where('name', 'Basketball')->exists())->toBeTrue();
    });

    it('denies sport creation without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('sports.store'), [
                'name' => 'Basketball',
            ]);

        $response->assertForbidden();
    });

    it('validates required fields', function (): void {
        $user = createUserWithPermission(Permission::CREATE_SPORT);

        $response = $this->actingAs($user)
            ->fromRoute('sports.create')
            ->post(route('sports.store'), []);

        $response->assertRedirectToRoute('sports.create')
            ->assertSessionHasErrors(['name']);

        $response = $this->actingAs($user)
            ->fromRoute('sports.create')
            ->post(route('sports.store'), [
                'name' => 'Basketball',
            ]);

        $response->assertSessionHasNoErrors();
    });

    it('enforces max length for name', function (): void {
        $user = createUserWithPermission(Permission::CREATE_SPORT);

        $response = $this->actingAs($user)
            ->fromRoute('sports.create')
            ->post(route('sports.store'), [
                'name' => str_repeat('a', 256),
            ]);

        $response->assertRedirectToRoute('sports.create')
            ->assertSessionHasErrors('name');
    });

    it('enforces unique name', function (): void {
        $user = createUserWithPermission(Permission::CREATE_SPORT);
        Sport::factory()->create(['name' => 'Basketball']);

        $response = $this->actingAs($user)
            ->fromRoute('sports.create')
            ->post(route('sports.store'), [
                'name' => 'Basketball',
            ]);

        $response->assertRedirectToRoute('sports.create')
            ->assertSessionHasErrors('name');
    });
});

describe('edit', function (): void {
    it('renders sport edit page', function (): void {
        $user = createUserWithPermission(Permission::UPDATE_SPORT);
        $sport = Sport::factory()->create();

        $response = $this->actingAs($user)
            ->fromRoute('sports.index')
            ->get(route('sports.edit', $sport));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('sport/Edit')
                ->has('sport'));
    });

    it('denies access without permission', function (): void {
        $user = User::factory()->create();
        $sport = Sport::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('sports.edit', $sport));

        $response->assertForbidden();
    });
});

describe('update', function (): void {
    it('may update a sport', function (): void {
        $user = createUserWithPermission(Permission::UPDATE_SPORT);
        $sport = Sport::factory()->create(['name' => 'Old Sport Name']);

        $response = $this->actingAs($user)
            ->fromRoute('sports.edit', $sport)
            ->patch(route('sports.update', $sport), [
                'name' => 'New Sport Name',
            ]);

        $response->assertRedirectToRoute('sports.index');
        expect($sport->refresh()->name)->toBe('New Sport Name');
    });

    it('denies sport update without permission', function (): void {
        $user = User::factory()->create();
        $sport = Sport::factory()->create(['name' => 'Old Sport Name']);

        $response = $this->actingAs($user)
            ->patch(route('sports.update', $sport), [
                'name' => 'New Sport Name',
            ]);

        $response->assertForbidden();
    });

    it('validates required fields', function (): void {
        $user = createUserWithPermission(Permission::UPDATE_SPORT);
        $sport = Sport::factory()->create();

        $response = $this->actingAs($user)
            ->fromRoute('sports.edit', $sport)
            ->patch(route('sports.update', $sport), []);

        $response->assertRedirectToRoute('sports.edit', $sport)
            ->assertSessionHasErrors(['name']);

        $response = $this->actingAs($user)
            ->fromRoute('sports.edit', $sport)
            ->patch(route('sports.update', $sport), [
                'name' => 'New Sport Name',
            ]);

        $response->assertSessionHasNoErrors();
    });

    it('enforces max length for name', function (): void {
        $user = createUserWithPermission(Permission::UPDATE_SPORT);
        $sport = Sport::factory()->create();

        $response = $this->actingAs($user)
            ->fromRoute('sports.edit', $sport)
            ->patch(route('sports.update', $sport), [
                'name' => str_repeat('a', 256),
            ]);

        $response->assertRedirectToRoute('sports.edit', $sport)
            ->assertSessionHasErrors('name');
    });

    it('enforces unique name', function (): void {
        $user = createUserWithPermission(Permission::UPDATE_SPORT);
        Sport::factory()->create(['name' => 'Basketball']);
        $sport = Sport::factory()->create(['name' => 'Football']);

        $response = $this->actingAs($user)
            ->fromRoute('sports.edit', $sport)
            ->patch(route('sports.update', $sport), [
                'name' => 'Basketball',
            ]);

        $response->assertRedirectToRoute('sports.edit', $sport)
            ->assertSessionHasErrors('name');
    });

    it('allows updating sport to keep the same name', function (): void {
        $user = createUserWithPermission(Permission::UPDATE_SPORT);
        $sport = Sport::factory()->create(['name' => 'Basketball']);

        $response = $this->actingAs($user)
            ->fromRoute('sports.edit', $sport)
            ->patch(route('sports.update', $sport), [
                'name' => 'Basketball',
            ]);

        $response->assertRedirectToRoute('sports.index');
        expect($sport->refresh()->name)->toBe('Basketball');
    });
});
