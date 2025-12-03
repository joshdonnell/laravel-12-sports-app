<?php

declare(strict_types=1);

namespace App\Http\Controllers\Sport;

use App\Actions\Sport\CreateSport;
use App\Actions\Sport\UpdateSport;
use App\Data\Sport\SportData;
use App\Http\Requests\Sport\CreateSportRequest;
use App\Http\Requests\Sport\UpdateSportRequest;
use App\Models\Scopes\SearchScope;
use App\Models\Sport;
use App\Queries\Sport\AlphabeticalSportsQuery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final readonly class SportController
{
    public function index(AlphabeticalSportsQuery $query, Request $request): Response
    {
        Gate::authorize('index', Sport::class);

        $search = $request->string('search')->trim();

        $sports = $query->builder()
            ->withGlobalScope('search', new SearchScope($search))
            ->paginate(config('app.defaults.pagination.limit'))
            ->onEachSide(1)
            ->withQueryString();

        return Inertia::render('sport/Index',
            [
                'sports' => SportData::collect($sports),
                'search' => $search,
            ]
        );
    }

    public function create(): Response
    {
        Gate::authorize('create', Sport::class);

        return Inertia::render('sport/Create');
    }

    public function store(CreateSportRequest $request, CreateSport $action): RedirectResponse
    {
        Gate::authorize('store', Sport::class);

        $action->handle($request->validated());

        return to_route('sports.index');
    }

    public function edit(Sport $sport): Response
    {
        Gate::authorize('edit', $sport);

        return Inertia::render('sport/Edit', [
            'sport' => SportData::from($sport),
        ]);
    }

    public function update(UpdateSportRequest $request, Sport $sport, UpdateSport $action): RedirectResponse
    {
        Gate::authorize('update', $sport);

        $action->handle($sport, $request->validated());

        return to_route('sports.index');
    }
}
