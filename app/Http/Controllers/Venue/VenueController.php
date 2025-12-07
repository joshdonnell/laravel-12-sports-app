<?php

declare(strict_types=1);

namespace App\Http\Controllers\Venue;

use App\Actions\Venue\CreateVenue;
use App\Actions\Venue\DeleteVenue;
use App\Actions\Venue\UpdateVenue;
use App\Data\Venue\VenueData;
use App\Http\Requests\Venue\CreateVenueRequest;
use App\Http\Requests\Venue\UpdateVenueRequest;
use App\Models\Scopes\FilterSportScope;
use App\Models\Scopes\SearchScope;
use App\Models\User;
use App\Models\Venue;
use App\Queries\Venue\AlphabeticalVenueQuery;
use App\Services\Inertia\SharedInertiaData;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final readonly class VenueController
{
    public function index(#[CurrentUser()] User $user, AlphabeticalVenueQuery $query, Request $request, SharedInertiaData $sharedInertiaData): Response
    {
        Gate::authorize('index', Venue::class);

        $search = $request->string('search')->trim();
        $sport = $request->string('sport')->trim();

        $venues = $query->builder()
            ->withGlobalScope('search', new SearchScope($search))
            ->withGlobalScope('sportFilter', new FilterSportScope($sport, $user))
            ->with('sport')
            ->paginate(config('app.defaults.pagination.limit'))
            ->onEachSide(1)
            ->withQueryString();

        return Inertia::render('venue/Index',
            [
                'venues' => VenueData::collect($venues),
                'sports' => Inertia::defer(fn (): ?array => $sharedInertiaData->getSports()),
                'sport' => $sport,
                'search' => $search,
            ]
        );
    }

    public function create(SharedInertiaData $sharedInertiaData): Response
    {
        Gate::authorize('create', Venue::class);

        return Inertia::render('venue/Create', [
            'sports' => Inertia::defer(fn (): ?array => $sharedInertiaData->getSports()),
        ]);
    }

    public function store(#[CurrentUser()] User $user, CreateVenueRequest $request, CreateVenue $action): RedirectResponse
    {
        Gate::authorize('store', Venue::class);

        $attributes = $request->safe()->except('sport_id');
        $attributes['sport_id'] = $request->integer('sport_id', $user->sport_id);

        $action->handle($attributes);

        return to_route('venues.index');
    }

    public function edit(Venue $venue, SharedInertiaData $sharedInertiaData): Response
    {
        Gate::authorize('edit', $venue);

        $venue->load('sport');

        return Inertia::render('venue/Edit', [
            'venue' => VenueData::from($venue),
            'sports' => Inertia::defer(fn (): ?array => $sharedInertiaData->getSports()),
        ]);
    }

    public function update(Venue $venue, #[CurrentUser()] User $user, UpdateVenueRequest $request, UpdateVenue $action): RedirectResponse
    {
        Gate::authorize('update', $venue);

        $attributes = $request->safe()->except('sport_id');
        $attributes['sport_id'] = $request->integer('sport_id', $user->sport_id);

        $action->handle($venue, $attributes);

        return to_route('venues.index');
    }

    public function destroy(Venue $venue, DeleteVenue $action): RedirectResponse
    {
        Gate::authorize('destroy', $venue);

        $action->handle($venue);

        return to_route('venues.index');
    }
}
