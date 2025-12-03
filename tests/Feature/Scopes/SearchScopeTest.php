<?php

declare(strict_types=1);

use App\Models\Player;
use App\Models\Scopes\SearchScope;
use App\Models\Season;

it('returns a resource by the search term by the default name column', function (): void {
    $term = '2024/2025';
    Season::factory(3)->create();
    Season::factory()->create([
        'name' => $term,
    ]);

    $search = str($term);
    $seasons = Season::query()->withGlobalScope('search', new SearchScope($search))->get();

    expect($seasons->count())->toBe(1)
        ->and($seasons->first()->name)->toBe($term);
});

it('returns a resource by the search term by a defined column', function (): void {
    $term = 'My Test Name';
    Player::factory(3)->create();
    Player::factory()->create([
        'known_as' => $term,
    ]);

    $search = str($term);
    $players = Player::query()->withGlobalScope('search', new SearchScope($search, 'known_as'))->get();

    expect($players->count())->toBe(1)
        ->and($players->first()->known_as)->toBe($term);
});
