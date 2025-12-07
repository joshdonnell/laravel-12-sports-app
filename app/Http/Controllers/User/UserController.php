<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Actions\User\CreateUser;
use App\Actions\User\DeleteUser;
use App\Actions\User\UpdateUser;
use App\Data\Client\ClientData;
use App\Data\User\UserData;
use App\Enums\Role;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Scopes\SearchScope;
use App\Models\User;
use App\Queries\User\AlphabeticalUsersQuery;
use App\Services\Inertia\SharedInertiaData;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final readonly class UserController
{
    public function index(AlphabeticalUsersQuery $query, Request $request): Response
    {
        Gate::authorize('index', User::class);

        $search = $request->string('search')->trim();

        $users = $query->builder()
            ->withGlobalScope('search', new SearchScope($search))
            ->with('roles:name')
            ->paginate(config('app.defaults.pagination.limit'))
            ->onEachSide(1)
            ->withQueryString();

        return Inertia::render('user/Index',
            [
                'users' => UserData::collect($users),
                'search' => $search,
            ]
        );
    }

    public function create(SharedInertiaData $sharedInertiaData): Response
    {
        Gate::authorize('create', User::class);

        return Inertia::render('user/Create', [
            'roles' => $sharedInertiaData->getAssignableRoles(),
            'sports' => Inertia::defer(fn (): ?array => $sharedInertiaData->getSports()),
        ]);
    }

    public function store(#[CurrentUser()] User $user, CreateUserRequest $request, CreateUser $action): RedirectResponse
    {
        Gate::authorize('store', User::class);

        $attributes = $request->safe()->except('password', 'sport_id', 'role');
        $attributes['sport_id'] = $request->integer('sport_id', $user->sport_id);
        $role = $request->filled('role') ? Role::from($request->string('role')->value()) : Role::User;

        $newUser = $action->handle($attributes, $request->string('password')->value(), $role);

        return to_route('users.edit', ['user' => $newUser->uuid]);
    }

    public function edit(User $user, SharedInertiaData $sharedInertiaData): Response
    {
        Gate::authorize('edit', $user);

        $user->load('roles:name');
        $clients = $user
            ->clients()
            ->with('sport')
            ->paginate(config('app.defaults.pagination.limit'))
            ->onEachSide(1);

        return Inertia::render('user/Edit', [
            'user' => UserData::from($user),
            'roles' => $sharedInertiaData->getAssignableRoles(),
            'sports' => Inertia::defer(fn (): ?array => $sharedInertiaData->getSports()),
            'clients' => ClientData::collect($clients),
            'availableClients' => Inertia::optional(fn (): array => $user->availableClients($user)),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user, UpdateUser $action): RedirectResponse
    {
        Gate::authorize('update', $user);

        $attributes = $request->safe()->except('password', 'sport_id', 'role');
        $attributes['sport_id'] = $request->integer('sport_id', $user->sport_id);

        $role = $request->filled('role') ? Role::from($request->string('role')->value()) : null;
        $password = $request->filled('password') ? $request->string('password')->value() : null;

        $action->handle($user, $attributes, $password, $role);

        return to_route('users.index');
    }

    public function destroy(User $user, DeleteUser $action): RedirectResponse
    {
        Gate::authorize('destroy', $user);

        $action->handle($user);

        return to_route('users.index');
    }
}
