<?php

declare(strict_types=1);

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Position;
use App\Models\Sport;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

function createPosition(?Sport $sport = null, array $attributes = []): Position
{
    $defaults = $sport instanceof Sport ? ['sport_id' => $sport->id] : [];

    return Position::factory()->create(array_merge($defaults, $attributes));
}

describe('index', function (): void {
    it('renders positions index page', function (): void {
        $user = createUserWithPermission(Permission::LIST_POSITIONS);

        Position::factory()->count(3)->create();

        $response = $this->actingAs($user)
            ->fromRoute('dashboard')
            ->get(route('positions.index'));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('position/Index')
                ->has('positions'));
    });

    it('denies access without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('positions.index'));

        $response->assertForbidden();
    });

    it('filters positions by search term', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::LIST_POSITIONS, $sport);

        createPosition($sport, ['name' => 'Point Guard']);
        createPosition($sport, ['name' => 'Shooting Guard']);
        createPosition($sport, ['name' => 'Center']);

        $response = $this->actingAs($user)
            ->get(route('positions.index', ['search' => 'Guard']));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('position/Index')
                ->where('search', 'Guard')
                ->has('positions.data'));
    });

    it('filters positions by sport', function (): void {
        $sport1 = Sport::factory()->create(['name' => 'Basketball']);
        $sport2 = Sport::factory()->create(['name' => 'Netball']);
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        $user->givePermissionTo(Permission::LIST_POSITIONS->value);

        createPosition($sport1);
        createPosition($sport2);

        $response = $this->actingAs($user)
            ->get(route('positions.index', ['sport' => (string) $sport1->id]));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('position/Index')
                ->where('sport', (string) $sport1->id));
    });
});

describe('create', function (): void {
    it('renders position create page', function (): void {
        $user = createUserWithPermission(Permission::CREATE_POSITION);

        $response = $this->actingAs($user)
            ->fromRoute('positions.index')
            ->get(route('positions.create'));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('position/Create'));
    });

    it('denies access without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('positions.create'));

        $response->assertForbidden();
    });
});

describe('store', function (): void {
    it('may create a position as SuperAdmin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport);

        $response = $this->actingAs($user)
            ->fromRoute('positions.create')
            ->post(route('positions.store'), [
                'name' => 'Point Guard',
                'known_as' => 'PG',
                'sport_id' => $sport->id,
            ]);

        $response->assertRedirectToRoute('positions.index');
        expect(Position::query()->where('name', 'Point Guard')->exists())->toBeTrue();
    });

    it('may create a position as Admin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::Admin, $sport);

        $response = $this->actingAs($user)
            ->fromRoute('positions.create')
            ->post(route('positions.store'), [
                'name' => 'Point Guard',
                'known_as' => 'PG',
            ]);

        $response->assertRedirectToRoute('positions.index');

        $position = Position::query()->where('name', 'Point Guard')->first();
        expect($position)->not->toBeNull()
            ->and($position->sport_id)->toBe($sport->id);
    });

    it('denies position creation without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('positions.store'), [
                'name' => 'Point Guard',
            ]);

        $response->assertForbidden();
    });

    it('validates required fields', function (): void {
        $user = createUserWithPermission(Permission::CREATE_POSITION);

        $response = $this->actingAs($user)
            ->fromRoute('positions.create')
            ->post(route('positions.store'), []);

        $response->assertRedirectToRoute('positions.create')
            ->assertSessionHasErrors(['name']);

        $response = $this->actingAs($user)
            ->fromRoute('positions.create')
            ->post(route('positions.store'), [
                'name' => 'Valid Position',
            ]);

        $response->assertSessionHasNoErrors();
    });

    it('enforces unique name per sport', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::CREATE_POSITION, $sport);
        createPosition($sport, ['name' => 'Point Guard']);

        $response = $this->actingAs($user)
            ->fromRoute('positions.create')
            ->post(route('positions.store'), [
                'name' => 'Point Guard',
            ]);

        $response->assertRedirectToRoute('positions.create')
            ->assertSessionHasErrors('name');
    });

    it('allows same name in different sports', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        createPosition($sport1, ['name' => 'Center']);

        $response = $this->actingAs($user)
            ->fromRoute('positions.create')
            ->post(route('positions.store'), [
                'name' => 'Center',
                'sport_id' => $sport2->id,
            ]);

        $response->assertRedirectToRoute('positions.index');
        expect(Position::query()->where('name', 'Center')->where('sport_id', $sport2->id)->exists())->toBeTrue();
    });

    it('requires sport_id when creating position as SuperAdmin', function (): void {
        $user = createUserWithRole(Role::SuperAdmin);

        $response = $this->actingAs($user)
            ->fromRoute('positions.create')
            ->post(route('positions.store'), [
                'name' => 'Point Guard',
            ]);

        $response->assertRedirectToRoute('positions.create')
            ->assertSessionHasErrors('sport_id');
    });
});

describe('edit', function (): void {
    it('renders position edit page', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_POSITION, $sport);
        $position = createPosition($sport);

        $response = $this->actingAs($user)
            ->fromRoute('positions.index')
            ->get(route('positions.edit', $position));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('position/Edit')
                ->has('position'));
    });

    it('denies access without permission', function (): void {
        $sport = Sport::factory()->create();
        $user = User::factory()->create(['sport_id' => $sport->id]);
        $position = createPosition($sport);

        $response = $this->actingAs($user)
            ->get(route('positions.edit', $position));

        $response->assertForbidden();
    });

    it('returns not found for position from different sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_POSITION, $sport1);
        $position = createPosition($sport2);

        $response = $this->actingAs($user)
            ->get(route('positions.edit', $position));

        $response->assertNotFound();
    });

    it('allows SuperAdmin to edit position from any sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        $position = createPosition($sport2);

        $response = $this->actingAs($user)
            ->get(route('positions.edit', $position));

        $response->assertOk();
    });
});

describe('update', function (): void {
    it('may update a position', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_POSITION, $sport);
        $position = createPosition($sport, ['name' => 'Old Name', 'known_as' => 'ON']);

        $response = $this->actingAs($user)
            ->fromRoute('positions.edit', $position)
            ->patch(route('positions.update', $position), [
                'name' => 'New Name',
                'known_as' => 'NN',
            ]);

        $response->assertRedirectToRoute('positions.index');
        expect($position->refresh()->name)->toBe('New Name')
            ->and($position->known_as)->toBe('NN');
    });

    it('denies position update without permission', function (): void {
        $sport = Sport::factory()->create();
        $user = User::factory()->create(['sport_id' => $sport->id]);
        $position = createPosition($sport, ['name' => 'Old Name']);

        $response = $this->actingAs($user)
            ->patch(route('positions.update', $position), [
                'name' => 'New Name',
            ]);

        $response->assertForbidden();
    });

    it('returns not found for update of position from different sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_POSITION, $sport1);
        $position = createPosition($sport2);

        $response = $this->actingAs($user)
            ->patch(route('positions.update', $position), [
                'name' => 'New Name',
            ]);

        $response->assertNotFound();
    });

    it('allows SuperAdmin to update position from any sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        $position = createPosition($sport2, ['name' => 'Old Name']);

        $response = $this->actingAs($user)
            ->fromRoute('positions.edit', $position)
            ->patch(route('positions.update', $position), [
                'name' => 'New Name',
                'sport_id' => $sport2->id,
            ]);

        $response->assertRedirectToRoute('positions.index');
        expect($position->refresh()->name)->toBe('New Name');
    });

    it('validates required fields', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_POSITION, $sport);
        $position = createPosition($sport);

        $response = $this->actingAs($user)
            ->fromRoute('positions.edit', $position)
            ->patch(route('positions.update', $position), []);

        $response->assertRedirectToRoute('positions.edit', $position)
            ->assertSessionHasErrors(['name']);

        $response = $this->actingAs($user)
            ->fromRoute('positions.edit', $position)
            ->patch(route('positions.update', $position), [
                'name' => 'Valid Position',
            ]);

        $response->assertSessionHasNoErrors();
    });

    it('enforces unique name per sport', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_POSITION, $sport);
        createPosition($sport, ['name' => 'Existing Position']);
        $position = createPosition($sport, ['name' => 'Current Position']);

        $response = $this->actingAs($user)
            ->fromRoute('positions.edit', $position)
            ->patch(route('positions.update', $position), [
                'name' => 'Existing Position',
            ]);

        $response->assertRedirectToRoute('positions.edit', $position)
            ->assertSessionHasErrors('name');
    });

    it('allows updating position to keep the same name', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_POSITION, $sport);
        $position = createPosition($sport, ['name' => 'Same Name']);

        $response = $this->actingAs($user)
            ->fromRoute('positions.edit', $position)
            ->patch(route('positions.update', $position), [
                'name' => 'Same Name',
            ]);

        $response->assertRedirectToRoute('positions.index');
        expect($position->refresh()->name)->toBe('Same Name');
    });

    it('requires sport_id when updating position as SuperAdmin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport);
        $position = createPosition($sport);

        $response = $this->actingAs($user)
            ->fromRoute('positions.edit', $position)
            ->patch(route('positions.update', $position), [
                'name' => 'New Name',
            ]);

        $response->assertRedirectToRoute('positions.edit', $position)
            ->assertSessionHasErrors('sport_id');
    });
});

describe('destroy', function (): void {
    it('may delete a position', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::DELETE_POSITION, $sport);
        $position = createPosition($sport);

        $response = $this->actingAs($user)
            ->fromRoute('positions.index')
            ->delete(route('positions.destroy', $position));

        $response->assertRedirectToRoute('positions.index');
        expect(Position::query()->where('id', $position->id)->exists())->toBeFalse();
    });

    it('denies position deletion without permission', function (): void {
        $sport = Sport::factory()->create();
        $user = User::factory()->create(['sport_id' => $sport->id]);
        $position = createPosition($sport);

        $response = $this->actingAs($user)
            ->delete(route('positions.destroy', $position));

        $response->assertForbidden();
    });

    it('returns not found for deletion of position from different sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithPermission(Permission::DELETE_POSITION, $sport1);
        $position = createPosition($sport2);

        $response = $this->actingAs($user)
            ->delete(route('positions.destroy', $position));

        $response->assertNotFound();
    });

    it('allows SuperAdmin to delete position from any sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        $position = createPosition($sport2);

        $response = $this->actingAs($user)
            ->delete(route('positions.destroy', $position));

        $response->assertRedirectToRoute('positions.index');
        expect(Position::query()->where('id', $position->id)->exists())->toBeFalse();
    });
});
