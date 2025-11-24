<?php

declare(strict_types=1);

namespace App\Http\Controllers\Season;

use App\Actions\Season\CreateSeason;
use App\Actions\Season\UpdateSeason;
use App\Data\Season\SeasonData;
use App\Http\Requests\Season\CreateSeasonRequest;
use App\Http\Requests\Season\UpdateSeasonRequest;
use App\Models\Season;
use App\Queries\Season\LatestSeasonQuery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final readonly class SeasonController
{
    public function index(LatestSeasonQuery $query): Response
    {
        Gate::authorize('index');

        $seasons = $query->builder()->paginate(config('app.defaults.pagination.limit'));

        return Inertia::render('season/Index',
            [
                'seasons' => SeasonData::collect($seasons),
            ]
        );
    }

    public function create(): Response
    {
        Gate::authorize('create');

        return Inertia::render('season/Create');
    }

    public function store(CreateSeasonRequest $request, CreateSeason $action): RedirectResponse
    {
        Gate::authorize('store');

        $action->handle($request->validated());

        return to_route('seasons.index');
    }

    public function edit(Season $season): Response
    {
        Gate::authorize('edit');

        return Inertia::render('season/Edit', [
            'season' => SeasonData::from($season),
        ]);
    }

    public function update(UpdateSeasonRequest $request, Season $season, UpdateSeason $action): RedirectResponse
    {
        Gate::authorize('update');

        $action->handle($season, $request->validated());

        return to_route('seasons.index');
    }
}
