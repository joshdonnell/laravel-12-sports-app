<?php

declare(strict_types=1);

namespace App\Http\Controllers\Round;

use App\Actions\Round\CreateRound;
use App\Actions\Round\UpdateRound;
use App\Data\Round\RoundData;
use App\Http\Requests\Round\CreateRoundRequest;
use App\Http\Requests\Round\UpdateRoundRequest;
use App\Models\Round;
use App\Models\Scopes\FilterSportScope;
use App\Models\Scopes\SearchScope;
use App\Models\User;
use App\Queries\Round\AlphabeticalRoundQuery;
use App\Services\Inertia\SharedInertiaData;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final readonly class RoundController
{
    public function index(AlphabeticalRoundQuery $query, Request $request, SharedInertiaData $sharedInertiaData, #[CurrentUser()] User $user): Response
    {
        Gate::authorize('index', Round::class);

        $search = $request->string('search')->trim();
        $sport = $request->string('sport')->trim();

        $rounds = $query->builder()
            ->withGlobalScope('search', new SearchScope($search))
            ->withGlobalScope('sportFilter', new FilterSportScope($sport, $user))
            ->with('sport')
            ->paginate(config('app.defaults.pagination.limit'))
            ->onEachSide(1)
            ->withQueryString();

        return Inertia::render('round/Index',
            [
                'rounds' => RoundData::collect($rounds),
                'sports' => Inertia::defer(fn (): ?array => $sharedInertiaData->getSports()),
                'search' => $search,
                'sport' => $sport,
            ]
        );
    }

    public function create(SharedInertiaData $sharedInertiaData): Response
    {
        Gate::authorize('create', Round::class);

        return Inertia::render('round/Create', [
            'sports' => Inertia::defer(fn (): ?array => $sharedInertiaData->getSports()),
        ]);
    }

    public function store(CreateRoundRequest $request, CreateRound $action, #[CurrentUser()] User $user): RedirectResponse
    {
        Gate::authorize('store', Round::class);

        $attributes = $request->safe()->except('sport_id');
        $attributes['sport_id'] = $request->integer('sport_id', $user->sport_id);

        $action->handle($attributes);

        return to_route('rounds.index');
    }

    public function edit(Round $round, SharedInertiaData $sharedInertiaData): Response
    {
        Gate::authorize('edit', $round);

        $round->load('sport');

        return Inertia::render('round/Edit', [
            'round' => RoundData::from($round),
            'sports' => Inertia::defer(fn (): ?array => $sharedInertiaData->getSports()),
        ]);
    }

    public function update(UpdateRoundRequest $request, Round $round, UpdateRound $action): RedirectResponse
    {
        Gate::authorize('update', $round);

        $action->handle($round, $request->validated());

        return to_route('rounds.index');
    }
}
