<?php

declare(strict_types=1);

namespace App\Http\Controllers\Season;

use App\Actions\Season\CreateSeason;
use App\Actions\Season\UpdateSeason;
use App\Data\Season\SeasonData;
use App\Http\Requests\Season\CreateSeasonRequest;
use App\Http\Requests\Season\UpdateSeasonRequest;
use App\Models\Scopes\SearchScope;
use App\Models\Season;
use App\Queries\Season\LatestSeasonQuery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final readonly class SeasonController
{
    public function index(LatestSeasonQuery $query, Request $request): Response
    {
        Gate::authorize('index', Season::class);

        $search = $request->string('search')->trim();

        $seasons = $query->builder()
            ->withGlobalScope('search', new SearchScope($search))
            ->paginate(config('app.defaults.pagination.limit'))
            ->onEachSide(1)
            ->withQueryString();

        return Inertia::render('season/Index',
            [
                'seasons' => SeasonData::collect($seasons),
                'search' => $search,
            ]
        );
    }

    public function create(): Response
    {
        Gate::authorize('create', Season::class);

        return Inertia::render('season/Create');
    }

    public function store(CreateSeasonRequest $request, CreateSeason $action): RedirectResponse
    {
        Gate::authorize('store', Season::class);

        $action->handle($request->validated());

        return to_route('seasons.index');
    }

    public function edit(Season $season): Response
    {
        Gate::authorize('edit', $season);

        return Inertia::render('season/Edit', [
            'season' => SeasonData::from($season),
        ]);
    }

    public function update(UpdateSeasonRequest $request, Season $season, UpdateSeason $action): RedirectResponse
    {
        Gate::authorize('update', $season);

        $action->handle($season, $request->validated());

        return to_route('seasons.index');
    }
}
