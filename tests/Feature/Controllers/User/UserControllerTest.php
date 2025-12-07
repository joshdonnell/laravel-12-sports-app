<?php

declare(strict_types=1);

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Sport;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

function createTestUser(?Sport $sport = null, array $attributes = []): User
{
    $defaults = $sport instanceof Sport ? ['sport_id' => $sport->id] : [];
    $user = User::factory()->create(array_merge($defaults, $attributes));
    $user->assignRole(Role::User);

    return $user;
}

describe('index', function (): void {
    it('renders users index page', function (): void {
        $user = createUserWithPermission(Permission::LIST_USERS);

        $testUsers = User::factory()->count(3)->create();
        $testUsers->each(fn ($u) => $u->assignRole(Role::User));

        $response = $this->actingAs($user)
            ->fromRoute('dashboard')
            ->get(route('users.index'));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('user/Index')
                ->has('users'));
    });

    it('denies access without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('users.index'));

        $response->assertForbidden();
    });

    it('filters users by search term', function (): void {
        $user = createUserWithPermission(Permission::LIST_USERS);

        $john = createTestUser(null, ['name' => 'John Smith']);
        $jane = createTestUser(null, ['name' => 'Jane Doe']);
        $johnEmail = createTestUser(null, ['email' => 'john@example.com']);

        $response = $this->actingAs($user)
            ->get(route('users.index', ['search' => 'john']));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('user/Index')
                ->where('search', 'john')
                ->has('users.data'));
    });
});

describe('create', function (): void {
    it('renders user create page', function (): void {
        $user = createUserWithPermission(Permission::CREATE_USER);

        $response = $this->actingAs($user)
            ->fromRoute('users.index')
            ->get(route('users.create'));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('user/Create')
                ->has('roles'));
    });

    it('denies access without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('users.create'));

        $response->assertForbidden();
    });
});

describe('store', function (): void {
    it('may create a user as SuperAdmin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport);

        $response = $this->actingAs($user)
            ->fromRoute('users.create')
            ->post(route('users.store'), [
                'name' => 'New User',
                'email' => 'newuser@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'sport_id' => $sport->id,
                'role' => Role::User->value,
            ]);

        $newUser = User::query()->where('email', 'newuser@example.com')->first();
        expect($newUser)->not->toBeNull();

        $response->assertRedirect(route('users.edit', $newUser));
    });

    it('may create a user as Admin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::Admin, $sport);

        $response = $this->actingAs($user)
            ->fromRoute('users.create')
            ->post(route('users.store'), [
                'name' => 'New User',
                'email' => 'newuser@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'role' => Role::User->value,
            ]);

        $newUser = User::query()->where('email', 'newuser@example.com')->first();
        expect($newUser)->not->toBeNull()
            ->and($newUser->sport_id)->toBe($sport->id);

        $response->assertRedirect(route('users.edit', $newUser));
    });

    it('denies user creation without permission', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('users.store'), [
                'name' => 'New User',
                'email' => 'newuser@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ]);

        $response->assertForbidden();
    });

    it('validates required fields', function (): void {
        $user = createUserWithPermission(Permission::CREATE_USER);

        $response = $this->actingAs($user)
            ->fromRoute('users.create')
            ->post(route('users.store'), []);

        $response->assertRedirectToRoute('users.create')
            ->assertSessionHasErrors(['name', 'email', 'password']);

        $response = $this->actingAs($user)
            ->fromRoute('users.create')
            ->post(route('users.store'), [
                'name' => 'New User',
                'email' => 'newuser@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ]);

        $response->assertSessionHasNoErrors();
    });

    it('enforces max length for name', function (): void {
        $user = createUserWithPermission(Permission::CREATE_USER);

        $response = $this->actingAs($user)
            ->fromRoute('users.create')
            ->post(route('users.store'), [
                'name' => str_repeat('a', 256),
                'email' => 'newuser@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ]);

        $response->assertRedirectToRoute('users.create')
            ->assertSessionHasErrors('name');
    });

    it('enforces unique email', function (): void {
        $user = createUserWithPermission(Permission::CREATE_USER);
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->actingAs($user)
            ->fromRoute('users.create')
            ->post(route('users.store'), [
                'name' => 'New User',
                'email' => 'existing@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ]);

        $response->assertRedirectToRoute('users.create')
            ->assertSessionHasErrors('email');
    });

    it('requires sport_id when creating user as SuperAdmin', function (): void {
        $user = createUserWithRole(Role::SuperAdmin);

        $response = $this->actingAs($user)
            ->fromRoute('users.create')
            ->post(route('users.store'), [
                'name' => 'New User',
                'email' => 'newuser@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'role' => Role::User->value,
            ]);

        $response->assertRedirectToRoute('users.create')
            ->assertSessionHasErrors('sport_id');
    });

    it('requires role when creating user as SuperAdmin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport);

        $response = $this->actingAs($user)
            ->fromRoute('users.create')
            ->post(route('users.store'), [
                'name' => 'New User',
                'email' => 'newuser@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'sport_id' => $sport->id,
            ]);

        $response->assertRedirectToRoute('users.create')
            ->assertSessionHasErrors('role');
    });

    it('requires role when creating user as Admin', function (): void {
        $user = createUserWithRole(Role::Admin);

        $response = $this->actingAs($user)
            ->fromRoute('users.create')
            ->post(route('users.store'), [
                'name' => 'New User',
                'email' => 'newuser@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ]);

        $response->assertRedirectToRoute('users.create')
            ->assertSessionHasErrors('role');
    });

    it('prevents Admin from creating SuperAdmin', function (): void {
        $user = createUserWithRole(Role::Admin);

        $response = $this->actingAs($user)
            ->fromRoute('users.create')
            ->post(route('users.store'), [
                'name' => 'New User',
                'email' => 'newuser@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'role' => Role::SuperAdmin->value,
            ]);

        $response->assertRedirectToRoute('users.create')
            ->assertSessionHasErrors('role');
    });
});

describe('edit', function (): void {
    it('renders user edit page', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_USER, $sport);
        $editUser = createTestUser($sport);

        $response = $this->actingAs($user)
            ->fromRoute('users.index')
            ->get(route('users.edit', $editUser));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('user/Edit')
                ->has('user')
                ->has('roles')
                ->has('clients'));
    });

    it('denies access without permission', function (): void {
        $user = User::factory()->create();
        $editUser = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('users.edit', $editUser));

        $response->assertForbidden();
    });

    it('denies access to edit user from different sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_USER, $sport1);
        $editUser = User::factory()->create(['sport_id' => $sport2->id]);

        $response = $this->actingAs($user)
            ->get(route('users.edit', $editUser));

        $response->assertForbidden();
    });

    it('allows SuperAdmin to edit user from any sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        $editUser = createTestUser($sport2);

        $response = $this->actingAs($user)
            ->get(route('users.edit', $editUser));

        $response->assertOk();
    });
});

describe('update', function (): void {
    it('may update a user', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_USER, $sport);
        $editUser = createTestUser($sport, ['name' => 'Old Name']);

        $response = $this->actingAs($user)
            ->fromRoute('users.edit', $editUser)
            ->patch(route('users.update', $editUser), [
                'name' => 'New Name',
                'email' => $editUser->email,
            ]);

        $response->assertRedirectToRoute('users.index');
        expect($editUser->refresh()->name)->toBe('New Name');
    });

    it('denies user update without permission', function (): void {
        $user = User::factory()->create();
        $editUser = User::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user)
            ->patch(route('users.update', $editUser), [
                'name' => 'New Name',
                'email' => $editUser->email,
            ]);

        $response->assertForbidden();
    });

    it('denies update of user from different sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_USER, $sport1);
        $editUser = User::factory()->create(['sport_id' => $sport2->id]);

        $response = $this->actingAs($user)
            ->patch(route('users.update', $editUser), [
                'name' => 'New Name',
                'email' => $editUser->email,
            ]);

        $response->assertForbidden();
    });

    it('allows SuperAdmin to update user from any sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        $editUser = createTestUser($sport2, ['name' => 'Old Name']);

        $response = $this->actingAs($user)
            ->fromRoute('users.edit', $editUser)
            ->patch(route('users.update', $editUser), [
                'name' => 'New Name',
                'email' => $editUser->email,
                'sport_id' => $sport2->id,
                'role' => Role::User->value,
            ]);

        $response->assertRedirectToRoute('users.index');
        expect($editUser->refresh()->name)->toBe('New Name');
    });

    it('validates required fields', function (): void {
        $user = createUserWithPermission(Permission::UPDATE_USER);
        $editUser = User::factory()->create();

        $response = $this->actingAs($user)
            ->fromRoute('users.edit', $editUser)
            ->patch(route('users.update', $editUser), []);

        $response->assertRedirectToRoute('users.edit', $editUser)
            ->assertSessionHasErrors(['name', 'email']);

        $response = $this->actingAs($user)
            ->fromRoute('users.edit', $editUser)
            ->patch(route('users.update', $editUser), [
                'name' => 'New Name',
                'email' => $editUser->email,
            ]);

        $response->assertSessionHasNoErrors();
    });

    it('allows updating user to keep the same email', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_USER, $sport);
        $editUser = createTestUser($sport, ['email' => 'same@example.com']);

        $response = $this->actingAs($user)
            ->fromRoute('users.edit', $editUser)
            ->patch(route('users.update', $editUser), [
                'name' => 'New Name',
                'email' => 'same@example.com',
            ]);

        $response->assertRedirectToRoute('users.index');
        expect($editUser->refresh()->email)->toBe('same@example.com');
    });

    it('enforces max length for name', function (): void {
        $user = createUserWithPermission(Permission::UPDATE_USER);
        $editUser = User::factory()->create();

        $response = $this->actingAs($user)
            ->fromRoute('users.edit', $editUser)
            ->patch(route('users.update', $editUser), [
                'name' => str_repeat('a', 256),
                'email' => $editUser->email,
            ]);

        $response->assertRedirectToRoute('users.edit', $editUser)
            ->assertSessionHasErrors('name');
    });

    it('enforces unique email', function (): void {
        $user = createUserWithPermission(Permission::UPDATE_USER);
        User::factory()->create(['email' => 'existing@example.com']);
        $editUser = User::factory()->create(['email' => 'other@example.com']);

        $response = $this->actingAs($user)
            ->fromRoute('users.edit', $editUser)
            ->patch(route('users.update', $editUser), [
                'name' => 'New Name',
                'email' => 'existing@example.com',
            ]);

        $response->assertRedirectToRoute('users.edit', $editUser)
            ->assertSessionHasErrors('email');
    });

    it('can update password', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_USER, $sport);
        $editUser = createTestUser($sport);
        $oldPassword = $editUser->password;

        $response = $this->actingAs($user)
            ->fromRoute('users.edit', $editUser)
            ->patch(route('users.update', $editUser), [
                'name' => $editUser->name,
                'email' => $editUser->email,
                'password' => 'NewPassword123!',
                'password_confirmation' => 'NewPassword123!',
            ]);

        $response->assertRedirectToRoute('users.index');
        expect($editUser->refresh()->password)->not->toBe($oldPassword);
    });

    it('can update without changing password', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::UPDATE_USER, $sport);
        $editUser = createTestUser($sport);
        $oldPassword = $editUser->password;

        $response = $this->actingAs($user)
            ->fromRoute('users.edit', $editUser)
            ->patch(route('users.update', $editUser), [
                'name' => 'New Name',
                'email' => $editUser->email,
            ]);

        $response->assertRedirectToRoute('users.index');
        expect($editUser->refresh()->password)->toBe($oldPassword);
    });

    it('requires sport_id when updating user as SuperAdmin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport);
        $editUser = createTestUser($sport);

        $response = $this->actingAs($user)
            ->fromRoute('users.edit', $editUser)
            ->patch(route('users.update', $editUser), [
                'name' => 'New Name',
                'email' => $editUser->email,
                'role' => Role::User->value,
            ]);

        $response->assertRedirectToRoute('users.edit', $editUser)
            ->assertSessionHasErrors('sport_id');
    });

    it('requires role when updating user as SuperAdmin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport);
        $editUser = createTestUser($sport);

        $response = $this->actingAs($user)
            ->fromRoute('users.edit', $editUser)
            ->patch(route('users.update', $editUser), [
                'name' => 'New Name',
                'email' => $editUser->email,
                'sport_id' => $sport->id,
            ]);

        $response->assertRedirectToRoute('users.edit', $editUser)
            ->assertSessionHasErrors('role');
    });

    it('requires role when updating user as Admin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::Admin, $sport);
        $editUser = createTestUser($sport);

        $response = $this->actingAs($user)
            ->fromRoute('users.edit', $editUser)
            ->patch(route('users.update', $editUser), [
                'name' => 'New Name',
                'email' => $editUser->email,
            ]);

        $response->assertRedirectToRoute('users.edit', $editUser)
            ->assertSessionHasErrors('role');
    });

    it('prevents Admin from updating user to SuperAdmin', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithRole(Role::Admin, $sport);
        $editUser = createTestUser($sport);

        $response = $this->actingAs($user)
            ->fromRoute('users.edit', $editUser)
            ->patch(route('users.update', $editUser), [
                'name' => 'New Name',
                'email' => $editUser->email,
                'role' => Role::SuperAdmin->value,
            ]);

        $response->assertRedirectToRoute('users.edit', $editUser)
            ->assertSessionHasErrors('role');
    });
});

describe('destroy', function (): void {
    it('may delete a user', function (): void {
        $sport = Sport::factory()->create();
        $user = createUserWithPermission(Permission::DELETE_USER, $sport);
        $deleteUser = User::factory()->create(['sport_id' => $sport->id]);

        $response = $this->actingAs($user)
            ->fromRoute('users.index')
            ->delete(route('users.destroy', $deleteUser));

        $response->assertRedirectToRoute('users.index');
        expect(User::query()->where('id', $deleteUser->id)->exists())->toBeFalse();
    });

    it('denies user deletion without permission', function (): void {
        $user = User::factory()->create();
        $deleteUser = User::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('users.destroy', $deleteUser));

        $response->assertForbidden();
    });

    it('denies deletion of user from different sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithPermission(Permission::DELETE_USER, $sport1);
        $deleteUser = User::factory()->create(['sport_id' => $sport2->id]);

        $response = $this->actingAs($user)
            ->delete(route('users.destroy', $deleteUser));

        $response->assertForbidden();
    });

    it('allows SuperAdmin to delete user from any sport', function (): void {
        $sport1 = Sport::factory()->create();
        $sport2 = Sport::factory()->create();
        $user = createUserWithRole(Role::SuperAdmin, $sport1);
        $deleteUser = User::factory()->create(['sport_id' => $sport2->id]);

        $response = $this->actingAs($user)
            ->delete(route('users.destroy', $deleteUser));

        $response->assertRedirectToRoute('users.index');
        expect(User::query()->where('id', $deleteUser->id)->exists())->toBeFalse();
    });
});
