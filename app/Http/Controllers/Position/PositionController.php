<?php

declare(strict_types=1);

namespace App\Http\Controllers\Position;

use App\Actions\Position\CreatePosition;
use App\Actions\Position\DeletePosition;
use App\Actions\Position\UpdatePosition;
use App\Data\Position\PositionData;
use App\Http\Requests\Position\CreatePositionRequest;
use App\Http\Requests\Position\UpdatePositionRequest;
use App\Models\Position;
use App\Models\Scopes\FilterSportScope;
use App\Models\Scopes\SearchScope;
use App\Models\User;
use App\Queries\Position\AlphabeticalPositionQuery;
use App\Services\Inertia\SharedInertiaData;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final readonly class PositionController
{
    public function index(#[CurrentUser()] User $user, AlphabeticalPositionQuery $query, Request $request, SharedInertiaData $sharedInertiaData): Response
    {
        Gate::authorize('index', Position::class);

        $search = $request->string('search')->trim();
        $sport = $request->string('sport')->trim();

        $positions = $query->builder()
            ->withGlobalScope('search', new SearchScope($search))
            ->withGlobalScope('sportFilter', new FilterSportScope($sport, $user))
            ->with('sport')
            ->paginate(config('app.defaults.pagination.limit'))
            ->onEachSide(1)
            ->withQueryString();

        return Inertia::render('position/Index',
            [
                'positions' => PositionData::collect($positions),
                'sports' => Inertia::defer(fn (): ?array => $sharedInertiaData->getSports()),
                'sport' => $sport,
                'search' => $search,
            ]
        );
    }

    public function create(SharedInertiaData $sharedInertiaData): Response
    {
        Gate::authorize('create', Position::class);

        return Inertia::render('position/Create', [
            'sports' => Inertia::defer(fn (): ?array => $sharedInertiaData->getSports()),
        ]);
    }

    public function store(#[CurrentUser()] User $user, CreatePositionRequest $request, CreatePosition $action): RedirectResponse
    {
        Gate::authorize('store', Position::class);

        $attributes = $request->safe()->except('sport_id');
        $attributes['sport_id'] = $request->integer('sport_id', $user->sport_id);

        $action->handle($attributes);

        return to_route('positions.index');
    }

    public function edit(Position $position, SharedInertiaData $sharedInertiaData): Response
    {
        Gate::authorize('edit', $position);

        $position->load('sport');

        return Inertia::render('position/Edit', [
            'position' => PositionData::from($position),
            'sports' => Inertia::defer(fn (): ?array => $sharedInertiaData->getSports()),
        ]);
    }

    public function update(Position $position, #[CurrentUser()] User $user, UpdatePositionRequest $request, UpdatePosition $action): RedirectResponse
    {
        Gate::authorize('update', $position);

        $attributes = $request->safe()->except('sport_id');
        $attributes['sport_id'] = $request->integer('sport_id', $user->sport_id);

        $action->handle($position, $attributes);

        return to_route('positions.index');
    }

    public function destroy(Position $position, DeletePosition $action): RedirectResponse
    {
        Gate::authorize('destroy', $position);

        $action->handle($position);

        return to_route('positions.index');
    }
}
