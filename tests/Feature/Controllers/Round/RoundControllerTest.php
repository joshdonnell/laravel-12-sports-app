<?php

declare(strict_types=1);

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Round;
use App\Models\Sport;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

function createRound(?Sport $sport = null, array $attributes = []): Round
{
    $defaults = $sport instanceof Sport ? ['sport_id' => $sport->id] : [];

    return Round::factory()->create(array_merge($defaults, $attributes));
}

describe('index', function (): void {
    it('renders rounds index page', function (): void {
        $user = createUserWithPermission(Permission::LIST_ROUNDS);

        Round::factory()->count(3)->create();

        $response = $this->actingAs($user)
            ->fromRoute('dashboard')
            ->get(route('rounds.index'));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('round/Index')
                ->has('rounds'));
    });

    it('denies access without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('rounds.index'));

        $response->assertForbidden();
    });

    it('filters rounds by search term', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::LIST_ROUNDS, $sport);

        createRound($sport, ['name' => 'Round One']);
        createRound($sport, ['name' => 'Round Two']);
        createRound($sport, ['name' => 'Finals']);

        $response = $this->actingAs($user)
            ->get(route('rounds.index', ['search' => 'Round']));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('round/Index')
                ->where('search', 'Round')
                ->has('rounds.data'));
    });

    it('filters rounds by sport', function (): void {
        $sport1 = Sport::factory()->create(['name' => 'Basketball']);
        $sport2 = Sport::factory()->create(['name' => 'Netball']);
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        $user->givePermissionTo(Permission::LIST_ROUNDS->value);

        createRound($sport1);
        createRound($sport2);

        $response = $this->actingAs($user)
            ->get(route('rounds.index', ['sport' => (string) $sport1->id]));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('round/Index')
                ->where('sport', (string) $sport1->id));
    });
});

describe('create', function (): void {
    it('renders round create page', function (): void {
        $user = createUserWithPermission(Permission::CREATE_ROUND);

        $response = $this->actingAs($user)
            ->fromRoute('rounds.index')
            ->get(route('rounds.create'));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('round/Create'));
    });

    it('denies access without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('rounds.create'));

        $response->assertForbidden();
    });
});

describe('store', function (): void {
    it('may create a round as SuperAdmin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport);

        $response = $this->actingAs($user)
            ->fromRoute('rounds.create')
            ->post(route('rounds.store'), [
                'name' => 'Round One',
                'round_number' => 1,
                'sport_id' => $sport->id,
            ]);

        $response->assertRedirectToRoute('rounds.index');
        expect(Round::query()->where('name', 'Round One')->exists())->toBeTrue();
    });

    it('may create a round as Admin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::Admin, $sport);

        $response = $this->actingAs($user)
            ->fromRoute('rounds.create')
            ->post(route('rounds.store'), [
                'name' => 'Round One',
                'round_number' => 1,
            ]);

        $response->assertRedirectToRoute('rounds.index');

        $round = Round::query()->where('name', 'Round One')->first();
        expect($round)->not->toBeNull()
            ->and($round->sport_id)->toBe($sport->id);
    });

    it('denies round creation without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('rounds.store'), [
                'name' => 'Round One',
                'round_number' => 1,
            ]);

        $response->assertForbidden();
    });

    it('validates required fields', function (): void {
        $user = createUserWithPermission(Permission::CREATE_ROUND);

        $response = $this->actingAs($user)
            ->fromRoute('rounds.create')
            ->post(route('rounds.store'), []);

        $response->assertRedirectToRoute('rounds.create')
            ->assertSessionHasErrors(['name', 'round_number']);

        $response = $this->actingAs($user)
            ->fromRoute('rounds.create')
            ->post(route('rounds.store'), [
                'name' => 'Valid Round',
                'round_number' => 1,
            ]);

        $response->assertSessionHasNoErrors();
    });

    it('requires sport_id when creating round as SuperAdmin', function (): void {
        $user = createUserWithRole(Role::SuperAdmin);

        $response = $this->actingAs($user)
            ->fromRoute('rounds.create')
            ->post(route('rounds.store'), [
                'name' => 'Round One',
                'round_number' => 1,
            ]);

        $response->assertRedirectToRoute('rounds.create')
            ->assertSessionHasErrors('sport_id');
    });
});

describe('edit', function (): void {
    it('renders round edit page', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_ROUND, $sport);
        $round = createRound($sport);

        $response = $this->actingAs($user)
            ->fromRoute('rounds.index')
            ->get(route('rounds.edit', $round));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('round/Edit')
                ->has('round'));
    });

    it('denies access without permission', function (): void {
        $user = User::factory()->create();
        $round = Round::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('rounds.edit', $round));

        $response->assertForbidden();
    });
});

describe('update', function (): void {
    it('may update a round', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_ROUND, $sport);
        $round = createRound($sport, ['name' => 'Old Name', 'round_number' => 1]);

        $response = $this->actingAs($user)
            ->fromRoute('rounds.edit', $round)
            ->patch(route('rounds.update', $round), [
                'name' => 'New Name',
                'round_number' => 2,
            ]);

        $response->assertRedirectToRoute('rounds.index');
        expect($round->refresh()->name)->toBe('New Name')
            ->and($round->round_number)->toBe(2);
    });

    it('denies round update without permission', function (): void {
        $user = User::factory()->create();
        $round = createRound(null, ['name' => 'Old Name']);

        $response = $this->actingAs($user)
            ->patch(route('rounds.update', $round), [
                'name' => 'New Name',
                'round_number' => 1,
            ]);

        $response->assertForbidden();
    });

    it('validates required fields', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_ROUND, $sport);
        $round = createRound($sport);

        $response = $this->actingAs($user)
            ->fromRoute('rounds.edit', $round)
            ->patch(route('rounds.update', $round), []);

        $response->assertRedirectToRoute('rounds.edit', $round)
            ->assertSessionHasErrors(['name', 'round_number']);

        $response = $this->actingAs($user)
            ->fromRoute('rounds.edit', $round)
            ->patch(route('rounds.update', $round), [
                'name' => 'Valid Round',
                'round_number' => 1,
            ]);

        $response->assertSessionHasNoErrors();
    });

    it('requires sport_id when updating round as SuperAdmin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport);
        $round = createRound($sport);

        $response = $this->actingAs($user)
            ->fromRoute('rounds.edit', $round)
            ->patch(route('rounds.update', $round), [
                'name' => 'New Name',
                'round_number' => 1,
            ]);

        $response->assertRedirectToRoute('rounds.edit', $round)
            ->assertSessionHasErrors('sport_id');
    });

    it('may update round as SuperAdmin with sport_id', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport);
        $round = createRound($sport, ['name' => 'Old Name', 'round_number' => 1]);

        $response = $this->actingAs($user)
            ->fromRoute('rounds.edit', $round)
            ->patch(route('rounds.update', $round), [
                'name' => 'New Name',
                'round_number' => 2,
                'sport_id' => $sport->id,
            ]);

        $response->assertRedirectToRoute('rounds.index');
        expect($round->refresh()->name)->toBe('New Name')
            ->and($round->round_number)->toBe(2);
    });
});
