<?php

declare(strict_types=1);

namespace App\Http\Controllers\Club;

use App\Actions\Club\CreateClub;
use App\Actions\Club\DeleteClub;
use App\Actions\Club\UpdateClub;
use App\Data\Club\ClubCardData;
use App\Data\Club\ClubData;
use App\Http\Requests\Club\CreateClubRequest;
use App\Http\Requests\Club\UpdateClubRequest;
use App\Models\Club;
use App\Models\Scopes\FilterSportScope;
use App\Models\Scopes\SearchScope;
use App\Models\User;
use App\Queries\Club\AlphabeticalClubsQuery;
use App\Services\Inertia\SharedInertiaData;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final readonly class ClubController
{
    public function index(#[CurrentUser()] User $user, AlphabeticalClubsQuery $query, Request $request, SharedInertiaData $sharedInertiaData): Response
    {
        Gate::authorize('index', Club::class);

        $search = $request->string('search')->trim();
        $sport = $request->string('sport')->trim();

        $clubs = $query->builder()
            ->withGlobalScope('search', new SearchScope($search))
            ->withGlobalScope('sportFilter', new FilterSportScope($sport, $user))
            ->with('sport')
            ->paginate(config('app.defaults.pagination.limit'))
            ->onEachSide(1)
            ->withQueryString();

        return Inertia::render('club/Index',
            [
                'clubs' => ClubCardData::collect($clubs),
                'sports' => Inertia::defer(fn (): ?array => $sharedInertiaData->getSports()),
                'sport' => $sport,
                'search' => $search,
            ]
        );
    }

    public function create(SharedInertiaData $sharedInertiaData): Response
    {
        Gate::authorize('create', Club::class);

        return Inertia::render('club/Create', [
            'sports' => Inertia::defer(fn (): ?array => $sharedInertiaData->getSports()),
        ]);
    }

    public function store(CreateClubRequest $request, CreateClub $action, #[CurrentUser()] User $user): RedirectResponse
    {
        Gate::authorize('store', Club::class);

        $attributes = $request->safe()->except('sport_id', 'logo');
        $attributes['sport_id'] = $request->integer('sport_id', $user->sport_id);
        $logo = $request->file('logo');

        $action->handle($attributes, $logo);

        return to_route('clubs.index');
    }

    public function edit(Club $club): Response
    {
        Gate::authorize('edit', $club);

        return Inertia::render('club/Edit', [
            'club' => ClubData::from($club),
        ]);
    }

    public function update(Club $club, UpdateClubRequest $request, UpdateClub $action): RedirectResponse
    {
        Gate::authorize('update', $club);

        $attributes = $request->safe()->except('logo', 'remove_logo');
        $logo = $request->file('logo');
        $removeLogo = $request->boolean('remove_logo');

        $action->handle($club, $attributes, $logo, $removeLogo);

        return to_route('clubs.index');
    }

    public function destroy(Club $club, DeleteClub $action): RedirectResponse
    {
        Gate::authorize('destroy', $club);

        $action->handle($club);

        return to_route('clubs.index');
    }
}
