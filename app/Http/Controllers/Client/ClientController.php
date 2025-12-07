<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Actions\Client\CreateClient;
use App\Actions\Client\DeleteClient;
use App\Actions\Client\UpdateClient;
use App\Data\Client\ClientData;
use App\Http\Requests\Client\CreateClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Models\Client;
use App\Models\Scopes\FilterSportScope;
use App\Models\Scopes\SearchScope;
use App\Models\User;
use App\Queries\Client\AlphabeticalClientsQuery;
use App\Services\Inertia\SharedInertiaData;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final readonly class ClientController
{
    public function index(#[CurrentUser()] User $user, AlphabeticalClientsQuery $query, Request $request, SharedInertiaData $sharedInertiaData): Response
    {
        Gate::authorize('index', Client::class);

        $search = $request->string('search')->trim();
        $sport = $request->string('sport')->trim();

        $clients = $query->builder()
            ->withGlobalScope('search', new SearchScope($search))
            ->withGlobalScope('sportFilter', new FilterSportScope($sport, $user))
            ->with('sport')
            ->paginate(config('app.defaults.pagination.limit'))
            ->onEachSide(1)
            ->withQueryString();

        return Inertia::render('client/Index',
            [
                'clients' => ClientData::collect($clients),
                'sports' => Inertia::defer(fn (): ?array => $sharedInertiaData->getSports()),
                'sport' => $sport,
                'search' => $search,
            ]
        );
    }

    public function create(SharedInertiaData $sharedInertiaData): Response
    {
        Gate::authorize('create', Client::class);

        return Inertia::render('client/Create', [
            'sports' => Inertia::defer(fn (): ?array => $sharedInertiaData->getSports()),
        ]);
    }

    public function store(#[CurrentUser()] User $user, CreateClientRequest $request, CreateClient $action): RedirectResponse
    {
        Gate::authorize('store', Client::class);

        $attributes = $request->safe()->except('sport_id');
        $attributes['sport_id'] = $request->integer('sport_id', $user->sport_id);

        $action->handle($attributes);

        return to_route('clients.index');
    }

    public function edit(Client $client, SharedInertiaData $sharedInertiaData): Response
    {
        Gate::authorize('edit', $client);

        $client->load('sport');

        return Inertia::render('client/Edit', [
            'client' => ClientData::from($client),
            'sports' => Inertia::defer(fn (): ?array => $sharedInertiaData->getSports()),
        ]);
    }

    public function update(Client $client, #[CurrentUser()] User $user, UpdateClientRequest $request, UpdateClient $action): RedirectResponse
    {
        Gate::authorize('update', $client);

        $attributes = $request->safe()->except('sport_id');
        $attributes['sport_id'] = $request->integer('sport_id', $user->sport_id);

        $action->handle($client, $attributes);

        return to_route('clients.index');
    }

    public function destroy(Client $client, DeleteClient $action): RedirectResponse
    {
        Gate::authorize('destroy', $client);

        $action->handle($client);

        return to_route('clients.index');
    }
}
