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

it('renders users index page', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::LIST_USERS->value);

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

it('denies access to users index without permission', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('users.index'));

    $response->assertForbidden();
});

it('filters users by search term', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::LIST_USERS->value);

    $john = User::factory()->create(['name' => 'John Smith']);
    $john->assignRole(Role::User);

    $jane = User::factory()->create(['name' => 'Jane Doe']);
    $jane->assignRole(Role::User);

    $johnEmail = User::factory()->create(['email' => 'john@example.com']);
    $johnEmail->assignRole(Role::User);

    $response = $this->actingAs($user)
        ->get(route('users.index', ['search' => 'john']));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('user/Index')
            ->where('search', 'john')
            ->has('users.data'));
});

it('renders user create page', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::CREATE_USER->value);

    $response = $this->actingAs($user)
        ->fromRoute('users.index')
        ->get(route('users.create'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('user/Create')
            ->has('roles'));
});

it('denies access to user create without permission', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('users.create'));

    $response->assertForbidden();
});

it('may create a user as SuperAdmin', function (): void {
    $user = User::factory()->create();
    $user->assignRole(Role::SuperAdmin);

    $sport = Sport::factory()->create();

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

    $response->assertRedirectToRoute('users.index');

    expect(User::query()->where('email', 'newuser@example.com')->exists())->toBeTrue();
});

it('may create a user as Admin', function (): void {
    $user = User::factory()->create();
    $user->assignRole(Role::Admin);

    $response = $this->actingAs($user)
        ->fromRoute('users.create')
        ->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => Role::User->value,
        ]);

    $response->assertRedirectToRoute('users.index');

    expect(User::query()->where('email', 'newuser@example.com')->exists())->toBeTrue();
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

it('requires name when creating user', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::CREATE_USER->value);

    $response = $this->actingAs($user)
        ->fromRoute('users.create')
        ->post(route('users.store'), [
            'email' => 'newuser@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

    $response->assertRedirectToRoute('users.create')
        ->assertSessionHasErrors('name');
});

it('requires email when creating user', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::CREATE_USER->value);

    $response = $this->actingAs($user)
        ->fromRoute('users.create')
        ->post(route('users.store'), [
            'name' => 'New User',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

    $response->assertRedirectToRoute('users.create')
        ->assertSessionHasErrors('email');
});

it('requires password when creating user', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::CREATE_USER->value);

    $response = $this->actingAs($user)
        ->fromRoute('users.create')
        ->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'newuser@example.com',
        ]);

    $response->assertRedirectToRoute('users.create')
        ->assertSessionHasErrors('password');
});

it('requires password confirmation when creating user', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::CREATE_USER->value);

    $response = $this->actingAs($user)
        ->fromRoute('users.create')
        ->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'Password123!',
        ]);

    $response->assertRedirectToRoute('users.create')
        ->assertSessionHasErrors('password');
});

it('enforces max length for name when creating user', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::CREATE_USER->value);

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

it('enforces unique email when creating user', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::CREATE_USER->value);

    $existingUser = User::factory()->create(['email' => 'existing@example.com']);

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
    $user = User::factory()->create();
    $user->assignRole(Role::SuperAdmin);

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
    $user = User::factory()->create();
    $user->assignRole(Role::SuperAdmin);

    $sport = Sport::factory()->create();

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
    $user = User::factory()->create();
    $user->assignRole(Role::Admin);

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
    $user = User::factory()->create();
    $user->assignRole(Role::Admin);

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

it('renders user edit page', function (): void {
    $sport = Sport::factory()->create();

    $user = User::factory()->create(['sport_id' => $sport->id]);
    $user->givePermissionTo(Permission::UPDATE_USER->value);

    $editUser = User::factory()->create(['sport_id' => $sport->id]);
    $editUser->assignRole(Role::User);

    $response = $this->actingAs($user)
        ->fromRoute('users.index')
        ->get(route('users.edit', $editUser));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('user/Edit')
            ->has('user')
            ->has('roles'));
});

it('denies access to user edit without permission', function (): void {
    $user = User::factory()->create();
    $editUser = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('users.edit', $editUser));

    $response->assertForbidden();
});

it('may update a user', function (): void {
    $sport = Sport::factory()->create();

    $user = User::factory()->create(['sport_id' => $sport->id]);
    $user->givePermissionTo(Permission::UPDATE_USER->value);

    $editUser = User::factory()->create(['name' => 'Old Name', 'sport_id' => $sport->id]);
    $editUser->assignRole(Role::User);

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

it('requires name when updating user', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::UPDATE_USER->value);

    $editUser = User::factory()->create();

    $response = $this->actingAs($user)
        ->fromRoute('users.edit', $editUser)
        ->patch(route('users.update', $editUser), [
            'email' => $editUser->email,
        ]);

    $response->assertRedirectToRoute('users.edit', $editUser)
        ->assertSessionHasErrors('name');
});

it('requires email when updating user', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::UPDATE_USER->value);

    $editUser = User::factory()->create();

    $response = $this->actingAs($user)
        ->fromRoute('users.edit', $editUser)
        ->patch(route('users.update', $editUser), [
            'name' => 'New Name',
        ]);

    $response->assertRedirectToRoute('users.edit', $editUser)
        ->assertSessionHasErrors('email');
});

it('allows updating user to keep the same email', function (): void {
    $sport = Sport::factory()->create();

    $user = User::factory()->create(['sport_id' => $sport->id]);
    $user->givePermissionTo(Permission::UPDATE_USER->value);

    $editUser = User::factory()->create(['email' => 'same@example.com', 'sport_id' => $sport->id]);
    $editUser->assignRole(Role::User);

    $response = $this->actingAs($user)
        ->fromRoute('users.edit', $editUser)
        ->patch(route('users.update', $editUser), [
            'name' => 'New Name',
            'email' => 'same@example.com',
        ]);

    $response->assertRedirectToRoute('users.index');

    expect($editUser->refresh()->email)->toBe('same@example.com');
});

it('enforces max length for name when updating user', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::UPDATE_USER->value);

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

it('enforces unique email when updating user', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::UPDATE_USER->value);

    $existingUser = User::factory()->create(['email' => 'existing@example.com']);
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

it('can update password when updating user', function (): void {
    $sport = Sport::factory()->create();

    $user = User::factory()->create(['sport_id' => $sport->id]);
    $user->givePermissionTo(Permission::UPDATE_USER->value);

    $editUser = User::factory()->create(['sport_id' => $sport->id]);
    $editUser->assignRole(Role::User);
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

    $user = User::factory()->create(['sport_id' => $sport->id]);
    $user->givePermissionTo(Permission::UPDATE_USER->value);

    $editUser = User::factory()->create(['sport_id' => $sport->id]);
    $editUser->assignRole(Role::User);
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

it('may delete a user', function (): void {
    $sport = Sport::factory()->create();

    $user = User::factory()->create(['sport_id' => $sport->id]);
    $user->givePermissionTo(Permission::DELETE_USER->value);

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
