<?php

declare(strict_types=1);

use App\Actions\Club\DeleteClub;
use App\Models\Club;

it('deletes a club', function (): void {
    $club = Club::factory()->create();

    $action = resolve(DeleteClub::class);

    $action->handle($club);

    expect($club->deleted_at)->not->toBeNull();
});
