<?php

declare(strict_types=1);

use App\Actions\Club\UpdateClub;
use App\Models\Club;
use Illuminate\Http\UploadedFile;

it('updates a club', function (): void {
    $club = Club::factory()->create([
        'logo' => null,
    ]);

    $action = resolve(UpdateClub::class);
    $file = UploadedFile::fake()->create('document.png', 100);

    $action->handle($club, [
        'name' => 'Updated Club',
        'known_as' => 'Updated Club',
        'official_name' => 'Updated Club',
        'code' => 'UC',
        'bio' => 'Updated Club bio',
    ], $file, false);

    expect($club->refresh()
        ->name)->toBe('Updated Club')
        ->and($club->refresh()->known_as)->toBe('Updated Club')
        ->and($club->refresh()->official_name)->toBe('Updated Club')
        ->and($club->refresh()->code)->toBe('UC')
        ->and($club->refresh()->logo)->not()->toBeNull()
        ->and($club->refresh()->bio)->toBe('Updated Club bio');
});
