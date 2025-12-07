<?php

declare(strict_types=1);

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Sport;
use App\Models\User;
use App\Models\Venue;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

function createVenue(?Sport $sport = null, array $attributes = []): Venue
{
    $defaults = $sport instanceof Sport ? ['sport_id' => $sport->id] : [];

    return Venue::factory()->create(array_merge($defaults, $attributes));
}

describe('index', function (): void {
    it('renders venues index page', function (): void {
        $user = createUserWithPermission(Permission::LIST_VENUES);

        Venue::factory()->count(3)->create();

        $response = $this->actingAs($user)
            ->fromRoute('dashboard')
            ->get(route('venues.index'));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('venue/Index')
                ->has('venues'));
    });

    it('denies access without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('venues.index'));

        $response->assertForbidden();
    });

    it('filters venues by search term', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::LIST_VENUES, $sport);

        createVenue($sport, ['name' => 'Copper Box Arena']);
        createVenue($sport, ['name' => 'Wembley Stadium']);
        createVenue($sport, ['name' => 'Olympic Park']);

        $response = $this->actingAs($user)
            ->get(route('venues.index', ['search' => 'Copper']));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('venue/Index')
                ->where('search', 'Copper')
                ->has('venues.data'));
    });

    it('filters venues by sport', function (): void {
        $sport1 = Sport::factory()->create(['name' => 'Basketball']);
        $sport2 = Sport::factory()->create(['name' => 'Netball']);
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        $user->givePermissionTo(Permission::LIST_VENUES->value);

        createVenue($sport1);
        createVenue($sport2);

        $response = $this->actingAs($user)
            ->get(route('venues.index', ['sport' => (string) $sport1->id]));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('venue/Index')
                ->where('sport', (string) $sport1->id));
    });
});

describe('create', function (): void {
    it('renders venue create page', function (): void {
        $user = createUserWithPermission(Permission::CREATE_VENUE);

        $response = $this->actingAs($user)
            ->fromRoute('venues.index')
            ->get(route('venues.create'));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('venue/Create'));
    });

    it('denies access without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('venues.create'));

        $response->assertForbidden();
    });
});

describe('store', function (): void {
    it('may create a venue as SuperAdmin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport);

        $response = $this->actingAs($user)
            ->fromRoute('venues.create')
            ->post(route('venues.store'), [
                'name' => 'New Venue',
                'address' => '123 Main St',
                'website' => 'https://example.com',
                'sport_id' => $sport->id,
            ]);

        $response->assertRedirectToRoute('venues.index');
        expect(Venue::query()->where('name', 'New Venue')->exists())->toBeTrue();
    });

    it('may create a venue as Admin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::Admin, $sport);

        $response = $this->actingAs($user)
            ->fromRoute('venues.create')
            ->post(route('venues.store'), [
                'name' => 'New Venue',
            ]);

        $response->assertRedirectToRoute('venues.index');

        $venue = Venue::query()->where('name', 'New Venue')->first();
        expect($venue)->not->toBeNull()
            ->and($venue->sport_id)->toBe($sport->id);
    });

    it('denies venue creation without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('venues.store'), [
                'name' => 'New Venue',
            ]);

        $response->assertForbidden();
    });

    it('validates required fields', function (): void {
        $user = createUserWithPermission(Permission::CREATE_VENUE);

        $response = $this->actingAs($user)
            ->fromRoute('venues.create')
            ->post(route('venues.store'), []);

        $response->assertRedirectToRoute('venues.create')
            ->assertSessionHasErrors(['name']);

        $response = $this->actingAs($user)
            ->fromRoute('venues.create')
            ->post(route('venues.store'), [
                'name' => 'Valid Venue',
            ]);

        $response->assertSessionHasNoErrors();
    });

    it('enforces unique name', function (): void {
        $user = createUserWithPermission(Permission::CREATE_VENUE);
        createVenue(null, ['name' => 'Existing Venue']);

        $response = $this->actingAs($user)
            ->fromRoute('venues.create')
            ->post(route('venues.store'), [
                'name' => 'Existing Venue',
            ]);

        $response->assertRedirectToRoute('venues.create')
            ->assertSessionHasErrors('name');
    });

    it('requires sport_id when creating venue as SuperAdmin', function (): void {
        $user = createUserWithRole(Role::SuperAdmin);

        $response = $this->actingAs($user)
            ->fromRoute('venues.create')
            ->post(route('venues.store'), [
                'name' => 'New Venue',
            ]);

        $response->assertRedirectToRoute('venues.create')
            ->assertSessionHasErrors('sport_id');
    });
});

describe('edit', function (): void {
    it('renders venue edit page', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_VENUE, $sport);
        $venue = createVenue($sport);

        $response = $this->actingAs($user)
            ->fromRoute('venues.index')
            ->get(route('venues.edit', $venue));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('venue/Edit')
                ->has('venue'));
    });

    it('denies access without permission', function (): void {
        $sport = Sport::factory()->create();
        $user = User::factory()->create(['sport_id' => $sport->id]);
        $venue = createVenue($sport);

        $response = $this->actingAs($user)
            ->get(route('venues.edit', $venue));

        $response->assertForbidden();
    });

    it('returns 404 when editing venue from different sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_VENUE, $sport1);
        $venue = createVenue($sport2);

        $response = $this->actingAs($user)
            ->get(route('venues.edit', $venue));

        $response->assertNotFound();
    });

    it('allows SuperAdmin to edit venue from any sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        $venue = createVenue($sport2);

        $response = $this->actingAs($user)
            ->get(route('venues.edit', $venue));

        $response->assertOk();
    });
});

describe('update', function (): void {
    it('may update a venue', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_VENUE, $sport);
        $venue = createVenue($sport, ['name' => 'Old Name']);

        $response = $this->actingAs($user)
            ->fromRoute('venues.edit', $venue)
            ->patch(route('venues.update', $venue), [
                'name' => 'New Name',
            ]);

        $response->assertRedirectToRoute('venues.index');
        expect($venue->refresh()->name)->toBe('New Name');
    });

    it('denies venue update without permission', function (): void {
        $sport = Sport::factory()->create();
        $user = User::factory()->create(['sport_id' => $sport->id]);
        $venue = createVenue($sport, ['name' => 'Old Name']);

        $response = $this->actingAs($user)
            ->patch(route('venues.update', $venue), [
                'name' => 'New Name',
            ]);

        $response->assertForbidden();
    });

    it('returns 404 when updating venue from different sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_VENUE, $sport1);
        $venue = createVenue($sport2);

        $response = $this->actingAs($user)
            ->patch(route('venues.update', $venue), [
                'name' => 'New Name',
            ]);

        $response->assertNotFound();
    });

    it('allows SuperAdmin to update venue from any sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        $venue = createVenue($sport2, ['name' => 'Old Name']);

        $response = $this->actingAs($user)
            ->fromRoute('venues.edit', $venue)
            ->patch(route('venues.update', $venue), [
                'name' => 'New Name',
                'sport_id' => $sport2->id,
            ]);

        $response->assertRedirectToRoute('venues.index');
        expect($venue->refresh()->name)->toBe('New Name');
    });

    it('validates required fields', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_VENUE, $sport);
        $venue = createVenue($sport);

        $response = $this->actingAs($user)
            ->fromRoute('venues.edit', $venue)
            ->patch(route('venues.update', $venue), []);

        $response->assertRedirectToRoute('venues.edit', $venue)
            ->assertSessionHasErrors(['name']);

        $response = $this->actingAs($user)
            ->fromRoute('venues.edit', $venue)
            ->patch(route('venues.update', $venue), [
                'name' => 'Valid Venue',
            ]);

        $response->assertSessionHasNoErrors();
    });

    it('enforces unique name', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_VENUE, $sport);
        createVenue($sport, ['name' => 'Existing Venue']);
        $venue = createVenue($sport, ['name' => 'Current Venue']);

        $response = $this->actingAs($user)
            ->fromRoute('venues.edit', $venue)
            ->patch(route('venues.update', $venue), [
                'name' => 'Existing Venue',
            ]);

        $response->assertRedirectToRoute('venues.edit', $venue)
            ->assertSessionHasErrors('name');
    });

    it('allows updating venue to keep the same name', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_VENUE, $sport);
        $venue = createVenue($sport, ['name' => 'Same Name']);

        $response = $this->actingAs($user)
            ->fromRoute('venues.edit', $venue)
            ->patch(route('venues.update', $venue), [
                'name' => 'Same Name',
            ]);

        $response->assertRedirectToRoute('venues.index');
        expect($venue->refresh()->name)->toBe('Same Name');
    });

    it('requires sport_id when updating venue as SuperAdmin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport);
        $venue = createVenue($sport);

        $response = $this->actingAs($user)
            ->fromRoute('venues.edit', $venue)
            ->patch(route('venues.update', $venue), [
                'name' => 'New Name',
            ]);

        $response->assertRedirectToRoute('venues.edit', $venue)
            ->assertSessionHasErrors('sport_id');
    });
});

describe('destroy', function (): void {
    it('may delete a venue', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::DELETE_VENUE, $sport);
        $venue = createVenue($sport);

        $response = $this->actingAs($user)
            ->fromRoute('venues.index')
            ->delete(route('venues.destroy', $venue));

        $response->assertRedirectToRoute('venues.index');
        expect(Venue::query()->where('id', $venue->id)->exists())->toBeFalse();
    });

    it('denies venue deletion without permission', function (): void {
        $sport = Sport::factory()->create();
        $user = User::factory()->create(['sport_id' => $sport->id]);
        $venue = createVenue($sport);

        $response = $this->actingAs($user)
            ->delete(route('venues.destroy', $venue));

        $response->assertForbidden();
    });

    it('returns 404 when deleting venue from different sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithPermission(Permission::DELETE_VENUE, $sport1);
        $venue = createVenue($sport2);

        $response = $this->actingAs($user)
            ->delete(route('venues.destroy', $venue));

        $response->assertNotFound();
    });

    it('allows SuperAdmin to delete venue from any sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        $venue = createVenue($sport2);

        $response = $this->actingAs($user)
            ->delete(route('venues.destroy', $venue));

        $response->assertRedirectToRoute('venues.index');
        expect(Venue::query()->where('id', $venue->id)->exists())->toBeFalse();
    });
});
