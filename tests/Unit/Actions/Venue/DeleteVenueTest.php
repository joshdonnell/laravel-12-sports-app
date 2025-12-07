<?php

declare(strict_types=1);

use App\Actions\Venue\DeleteVenue;
use App\Models\Venue;

it('soft deletes a venue', function (): void {
    $venue = Venue::factory()->create();

    $action = resolve(DeleteVenue::class);

    $action->handle($venue);

    expect($venue->deleted_at)->not->toBeNull();
});
