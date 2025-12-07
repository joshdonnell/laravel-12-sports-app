<?php

declare(strict_types=1);

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Club;
use App\Models\Sport;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

function createClub(?Sport $sport = null, array $attributes = []): Club
{
    $defaults = $sport instanceof Sport ? ['sport_id' => $sport->id] : [];

    return Club::factory()->create(array_merge($defaults, $attributes));
}

describe('index', function (): void {
    it('renders clubs index page', function (): void {
        $user = createUserWithPermission(Permission::LIST_CLUBS);

        Club::factory()->count(3)->create();

        $response = $this->actingAs($user)
            ->fromRoute('dashboard')
            ->get(route('clubs.index'));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('club/Index')
                ->has('clubs'));
    });

    it('denies access without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('clubs.index'));

        $response->assertForbidden();
    });

    it('filters clubs by search term', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::LIST_CLUBS, $sport);

        createClub($sport, ['name' => 'Manchester United']);
        createClub($sport, ['name' => 'Manchester City']);
        createClub($sport, ['name' => 'Liverpool']);

        $response = $this->actingAs($user)
            ->get(route('clubs.index', ['search' => 'Manchester']));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('club/Index')
                ->where('search', 'Manchester')
                ->has('clubs.data'));
    });

    it('filters clubs by sport', function (): void {
        $sport1 = Sport::factory()->create(['name' => 'Basketball']);
        $sport2 = Sport::factory()->create(['name' => 'Netball']);
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        $user->givePermissionTo(Permission::LIST_CLUBS->value);

        createClub($sport1);
        createClub($sport2);

        $response = $this->actingAs($user)
            ->get(route('clubs.index', ['sport' => (string) $sport1->id]));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('club/Index')
                ->where('sport', (string) $sport1->id));
    });
});

describe('create', function (): void {
    it('renders club create page', function (): void {
        $user = createUserWithPermission(Permission::CREATE_CLUB);

        $response = $this->actingAs($user)
            ->fromRoute('clubs.index')
            ->get(route('clubs.create'));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('club/Create'));
    });

    it('denies access without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('clubs.create'));

        $response->assertForbidden();
    });
});

describe('store', function (): void {
    it('may create a club as SuperAdmin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport);

        $response = $this->actingAs($user)
            ->fromRoute('clubs.create')
            ->post(route('clubs.store'), [
                'name' => 'New Club',
                'known_as' => 'NC',
                'official_name' => 'New Club FC',
                'code' => 'NCF',
                'bio' => 'A great club',
                'sport_id' => $sport->id,
            ]);

        $response->assertRedirectToRoute('clubs.index');
        expect(Club::query()->where('name', 'New Club')->exists())->toBeTrue();
    });

    it('may create a club as Admin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::Admin, $sport);

        $response = $this->actingAs($user)
            ->fromRoute('clubs.create')
            ->post(route('clubs.store'), [
                'name' => 'New Club',
                'known_as' => 'NC',
            ]);

        $response->assertRedirectToRoute('clubs.index');

        $club = Club::query()->where('name', 'New Club')->first();
        expect($club)->not->toBeNull()
            ->and($club->sport_id)->toBe($sport->id);
    });

    it('denies club creation without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('clubs.store'), [
                'name' => 'New Club',
            ]);

        $response->assertForbidden();
    });

    it('validates required fields', function (): void {
        $user = createUserWithPermission(Permission::CREATE_CLUB);

        $response = $this->actingAs($user)
            ->fromRoute('clubs.create')
            ->post(route('clubs.store'), []);

        $response->assertRedirectToRoute('clubs.create')
            ->assertSessionHasErrors(['name']);

        $response = $this->actingAs($user)
            ->fromRoute('clubs.create')
            ->post(route('clubs.store'), [
                'name' => 'Valid Club',
            ]);

        $response->assertSessionHasNoErrors();
    });

    it('requires sport_id when creating club as SuperAdmin', function (): void {
        $user = createUserWithRole(Role::SuperAdmin);

        $response = $this->actingAs($user)
            ->fromRoute('clubs.create')
            ->post(route('clubs.store'), [
                'name' => 'New Club',
            ]);

        $response->assertRedirectToRoute('clubs.create')
            ->assertSessionHasErrors('sport_id');
    });
});

describe('edit', function (): void {
    it('renders club edit page', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_CLUB, $sport);
        $club = createClub($sport);

        $response = $this->actingAs($user)
            ->fromRoute('clubs.index')
            ->get(route('clubs.edit', $club));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('club/Edit')
                ->has('club'));
    });

    it('denies access without permission', function (): void {
        $sport = Sport::factory()->create();
        $user = User::factory()->create(['sport_id' => $sport->id]);
        $club = createClub($sport);

        $response = $this->actingAs($user)
            ->get(route('clubs.edit', $club));

        $response->assertForbidden();
    });

    it('returns not found for club from different sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_CLUB, $sport1);
        $club = createClub($sport2);

        $response = $this->actingAs($user)
            ->get(route('clubs.edit', $club));

        $response->assertNotFound();
    });

    it('allows SuperAdmin to edit club from any sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        $club = createClub($sport2);

        $response = $this->actingAs($user)
            ->get(route('clubs.edit', $club));

        $response->assertOk();
    });
});

describe('update', function (): void {
    it('may update a club', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_CLUB, $sport);
        $club = createClub($sport, ['name' => 'Old Name', 'known_as' => 'ON']);

        $response = $this->actingAs($user)
            ->fromRoute('clubs.edit', $club)
            ->patch(route('clubs.update', $club), [
                'name' => 'New Name',
                'known_as' => 'NN',
                'remove_logo' => false,
            ]);

        $response->assertRedirectToRoute('clubs.index');
        expect($club->refresh()->name)->toBe('New Name')
            ->and($club->known_as)->toBe('NN');
    });

    it('denies club update without permission', function (): void {
        $sport = Sport::factory()->create();
        $user = User::factory()->create(['sport_id' => $sport->id]);
        $club = createClub($sport, ['name' => 'Old Name']);

        $response = $this->actingAs($user)
            ->patch(route('clubs.update', $club), [
                'name' => 'New Name',
                'remove_logo' => false,
            ]);

        $response->assertForbidden();
    });

    it('returns not found for update of club from different sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_CLUB, $sport1);
        $club = createClub($sport2);

        $response = $this->actingAs($user)
            ->patch(route('clubs.update', $club), [
                'name' => 'New Name',
                'remove_logo' => false,
            ]);

        $response->assertNotFound();
    });

    it('allows SuperAdmin to update club from any sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        $club = createClub($sport2, ['name' => 'Old Name']);

        $response = $this->actingAs($user)
            ->fromRoute('clubs.edit', $club)
            ->patch(route('clubs.update', $club), [
                'name' => 'New Name',
                'remove_logo' => false,
            ]);

        $response->assertRedirectToRoute('clubs.index');
        expect($club->refresh()->name)->toBe('New Name');
    });

    it('validates required fields', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_CLUB, $sport);
        $club = createClub($sport);

        $response = $this->actingAs($user)
            ->fromRoute('clubs.edit', $club)
            ->patch(route('clubs.update', $club), []);

        $response->assertRedirectToRoute('clubs.edit', $club)
            ->assertSessionHasErrors(['name', 'remove_logo']);

        $response = $this->actingAs($user)
            ->fromRoute('clubs.edit', $club)
            ->patch(route('clubs.update', $club), [
                'name' => 'Valid Club',
                'remove_logo' => false,
            ]);

        $response->assertSessionHasNoErrors();
    });
});

describe('destroy', function (): void {
    it('may delete a club', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::DELETE_CLUB, $sport);
        $club = createClub($sport);

        $response = $this->actingAs($user)
            ->fromRoute('clubs.index')
            ->delete(route('clubs.destroy', $club));

        $response->assertRedirectToRoute('clubs.index');
        expect(Club::query()->where('id', $club->id)->exists())->toBeFalse();
    });

    it('denies club deletion without permission', function (): void {
        $sport = Sport::factory()->create();
        $user = User::factory()->create(['sport_id' => $sport->id]);
        $club = createClub($sport);

        $response = $this->actingAs($user)
            ->delete(route('clubs.destroy', $club));

        $response->assertForbidden();
    });

    it('returns not found for deletion of club from different sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithPermission(Permission::DELETE_CLUB, $sport1);
        $club = createClub($sport2);

        $response = $this->actingAs($user)
            ->delete(route('clubs.destroy', $club));

        $response->assertNotFound();
    });

    it('allows SuperAdmin to delete club from any sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        $club = createClub($sport2);

        $response = $this->actingAs($user)
            ->delete(route('clubs.destroy', $club));

        $response->assertRedirectToRoute('clubs.index');
        expect(Club::query()->where('id', $club->id)->exists())->toBeFalse();
    });
});
