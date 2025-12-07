<?php

declare(strict_types=1);

use App\Actions\Venue\CreateVenue;
use App\Models\Sport;
use App\Models\Venue;

it('creates a venue', function (): void {
    $sport = Sport::factory()->create();

    $action = resolve(CreateVenue::class);

    $client = $action->handle([
        'name' => 'New Venue',
        'sport_id' => $sport->id,
    ]);

    expect($client)->toBeInstanceOf(Venue::class)
        ->and($client->name)->toBe('New Venue')
        ->and($client->sport_id)->toBe($sport->id);
});
