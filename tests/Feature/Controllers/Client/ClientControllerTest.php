<?php

declare(strict_types=1);

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Client;
use App\Models\Sport;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

function createClient(?Sport $sport = null, array $attributes = []): Client
{
    $defaults = $sport instanceof Sport ? ['sport_id' => $sport->id] : [];

    return Client::factory()->create(array_merge($defaults, $attributes));
}

describe('index', function (): void {
    it('renders clients index page', function (): void {
        $user = createUserWithPermission(Permission::LIST_CLIENTS);

        Client::factory()->count(3)->create();

        $response = $this->actingAs($user)
            ->fromRoute('dashboard')
            ->get(route('clients.index'));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('client/Index')
                ->has('clients'));
    });

    it('denies access without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('clients.index'));

        $response->assertForbidden();
    });

    it('filters clients by search term', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::LIST_CLIENTS, $sport);

        createClient($sport, ['name' => 'Alpha Corp']);
        createClient($sport, ['name' => 'Beta LLC']);
        createClient($sport, ['name' => 'Gamma Inc']);

        $response = $this->actingAs($user)
            ->get(route('clients.index', ['search' => 'Alpha']));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('client/Index')
                ->where('search', 'Alpha')
                ->has('clients.data'));
    });

    it('filters clients by sport', function (): void {
        $sport1 = Sport::factory()->create(['name' => 'Basketball']);
        $sport2 = Sport::factory()->create(['name' => 'Netball']);
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        $user->givePermissionTo(Permission::LIST_CLIENTS->value);

        createClient($sport1);
        createClient($sport2);

        $response = $this->actingAs($user)
            ->get(route('clients.index', ['sport' => (string) $sport1->id]));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('client/Index')
                ->where('sport', (string) $sport1->id));
    });
});

describe('create', function (): void {
    it('renders client create page', function (): void {
        $user = createUserWithPermission(Permission::CREATE_CLIENT);

        $response = $this->actingAs($user)
            ->fromRoute('clients.index')
            ->get(route('clients.create'));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('client/Create'));
    });

    it('denies access without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('clients.create'));

        $response->assertForbidden();
    });
});

describe('store', function (): void {
    it('may create a client as SuperAdmin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport);

        $response = $this->actingAs($user)
            ->fromRoute('clients.create')
            ->post(route('clients.store'), [
                'name' => 'New Client',
                'sport_id' => $sport->id,
            ]);

        $response->assertRedirectToRoute('clients.index');
        expect(Client::query()->where('name', 'New Client')->exists())->toBeTrue();
    });

    it('may create a client as Admin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::Admin, $sport);

        $response = $this->actingAs($user)
            ->fromRoute('clients.create')
            ->post(route('clients.store'), [
                'name' => 'New Client',
            ]);

        $response->assertRedirectToRoute('clients.index');

        $client = Client::query()->where('name', 'New Client')->first();
        expect($client)->not->toBeNull()
            ->and($client->sport_id)->toBe($sport->id);
    });

    it('denies client creation without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('clients.store'), [
                'name' => 'New Client',
            ]);

        $response->assertForbidden();
    });

    it('validates required fields', function (): void {
        $user = createUserWithPermission(Permission::CREATE_CLIENT);

        $response = $this->actingAs($user)
            ->fromRoute('clients.create')
            ->post(route('clients.store'), []);

        $response->assertRedirectToRoute('clients.create')
            ->assertSessionHasErrors(['name']);

        $response = $this->actingAs($user)
            ->fromRoute('clients.create')
            ->post(route('clients.store'), [
                'name' => 'Valid Client',
            ]);

        $response->assertSessionHasNoErrors();
    });

    it('enforces unique name', function (): void {
        $user = createUserWithPermission(Permission::CREATE_CLIENT);
        createClient(null, ['name' => 'Existing Client']);

        $response = $this->actingAs($user)
            ->fromRoute('clients.create')
            ->post(route('clients.store'), [
                'name' => 'Existing Client',
            ]);

        $response->assertRedirectToRoute('clients.create')
            ->assertSessionHasErrors('name');
    });

    it('requires sport_id when creating client as SuperAdmin', function (): void {
        $user = createUserWithRole(Role::SuperAdmin);

        $response = $this->actingAs($user)
            ->fromRoute('clients.create')
            ->post(route('clients.store'), [
                'name' => 'New Client',
            ]);

        $response->assertRedirectToRoute('clients.create')
            ->assertSessionHasErrors('sport_id');
    });
});

describe('edit', function (): void {
    it('renders client edit page', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_CLIENT, $sport);
        $client = createClient($sport);

        $response = $this->actingAs($user)
            ->fromRoute('clients.index')
            ->get(route('clients.edit', $client));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('client/Edit')
                ->has('client'));
    });

    it('denies access without permission', function (): void {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('clients.edit', $client));

        $response->assertForbidden();
    });

    it('denies access to edit client from different sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_CLIENT, $sport1);
        $client = createClient($sport2);

        $response = $this->actingAs($user)
            ->get(route('clients.edit', $client));

        $response->assertForbidden();
    });

    it('allows SuperAdmin to edit client from any sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        $client = createClient($sport2);

        $response = $this->actingAs($user)
            ->get(route('clients.edit', $client));

        $response->assertOk();
    });
});

describe('update', function (): void {
    it('may update a client', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_CLIENT, $sport);
        $client = createClient($sport, ['name' => 'Old Name']);

        $response = $this->actingAs($user)
            ->fromRoute('clients.edit', $client)
            ->patch(route('clients.update', $client), [
                'name' => 'New Name',
            ]);

        $response->assertRedirectToRoute('clients.index');
        expect($client->refresh()->name)->toBe('New Name');
    });

    it('denies client update without permission', function (): void {
        $user = User::factory()->create();
        $client = createClient(null, ['name' => 'Old Name']);

        $response = $this->actingAs($user)
            ->patch(route('clients.update', $client), [
                'name' => 'New Name',
            ]);

        $response->assertForbidden();
    });

    it('denies update of client from different sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_CLIENT, $sport1);
        $client = createClient($sport2);

        $response = $this->actingAs($user)
            ->patch(route('clients.update', $client), [
                'name' => 'New Name',
            ]);

        $response->assertForbidden();
    });

    it('allows SuperAdmin to update client from any sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        $client = createClient($sport2, ['name' => 'Old Name']);

        $response = $this->actingAs($user)
            ->fromRoute('clients.edit', $client)
            ->patch(route('clients.update', $client), [
                'name' => 'New Name',
                'sport_id' => $sport2->id,
            ]);

        $response->assertRedirectToRoute('clients.index');
        expect($client->refresh()->name)->toBe('New Name');
    });

    it('validates required fields', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_CLIENT, $sport);
        $client = createClient($sport);

        $response = $this->actingAs($user)
            ->fromRoute('clients.edit', $client)
            ->patch(route('clients.update', $client), []);

        $response->assertRedirectToRoute('clients.edit', $client)
            ->assertSessionHasErrors(['name']);

        $response = $this->actingAs($user)
            ->fromRoute('clients.edit', $client)
            ->patch(route('clients.update', $client), [
                'name' => 'Valid Client',
            ]);

        $response->assertSessionHasNoErrors();
    });

    it('enforces unique name', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_CLIENT, $sport);
        createClient($sport, ['name' => 'Existing Client']);
        $client = createClient($sport, ['name' => 'Current Client']);

        $response = $this->actingAs($user)
            ->fromRoute('clients.edit', $client)
            ->patch(route('clients.update', $client), [
                'name' => 'Existing Client',
            ]);

        $response->assertRedirectToRoute('clients.edit', $client)
            ->assertSessionHasErrors('name');
    });

    it('allows updating client to keep the same name', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_CLIENT, $sport);
        $client = createClient($sport, ['name' => 'Same Name']);

        $response = $this->actingAs($user)
            ->fromRoute('clients.edit', $client)
            ->patch(route('clients.update', $client), [
                'name' => 'Same Name',
            ]);

        $response->assertRedirectToRoute('clients.index');
        expect($client->refresh()->name)->toBe('Same Name');
    });

    it('requires sport_id when updating client as SuperAdmin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport);
        $client = createClient($sport);

        $response = $this->actingAs($user)
            ->fromRoute('clients.edit', $client)
            ->patch(route('clients.update', $client), [
                'name' => 'New Name',
            ]);

        $response->assertRedirectToRoute('clients.edit', $client)
            ->assertSessionHasErrors('sport_id');
    });
});

describe('destroy', function (): void {
    it('may delete a client', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::DELETE_CLIENT, $sport);
        $client = createClient($sport);

        $response = $this->actingAs($user)
            ->fromRoute('clients.index')
            ->delete(route('clients.destroy', $client));

        $response->assertRedirectToRoute('clients.index');
        expect(Client::query()->where('id', $client->id)->exists())->toBeFalse();
    });

    it('denies client deletion without permission', function (): void {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('clients.destroy', $client));

        $response->assertForbidden();
    });

    it('denies deletion of client from different sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithPermission(Permission::DELETE_CLIENT, $sport1);
        $client = createClient($sport2);

        $response = $this->actingAs($user)
            ->delete(route('clients.destroy', $client));

        $response->assertForbidden();
    });

    it('allows SuperAdmin to delete client from any sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        $client = createClient($sport2);

        $response = $this->actingAs($user)
            ->delete(route('clients.destroy', $client));

        $response->assertRedirectToRoute('clients.index');
        expect(Client::query()->where('id', $client->id)->exists())->toBeFalse();
    });
});
