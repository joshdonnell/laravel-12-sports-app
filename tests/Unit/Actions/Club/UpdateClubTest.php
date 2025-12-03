<?php

declare(strict_types=1);

use App\Actions\Club\UpdateClub;
use App\Models\Club;

it('updates a club', function (): void {
    $club = Club::factory()->create();

    $action = resolve(UpdateClub::class);

    $action->handle($club, [
        'name' => 'Updated Club',
        'known_as' => 'Updated Club',
        'official_name' => 'Updated Club',
        'code' => 'UC',
        'logo' => 'https://example.com/logo.png',
        'bio' => 'Updated Club bio',
    ]);

    expect($club->refresh()
        ->name)->toBe('Updated Club')
        ->and($club->refresh()->known_as)->toBe('Updated Club')
        ->and($club->refresh()->official_name)->toBe('Updated Club')
        ->and($club->refresh()->code)->toBe('UC')
        ->and($club->refresh()->logo)->toBe('https://example.com/logo.png')
        ->and($club->refresh()->bio)->toBe('Updated Club bio');
});
