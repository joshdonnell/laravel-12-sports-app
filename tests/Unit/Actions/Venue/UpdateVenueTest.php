<?php

declare(strict_types=1);

use App\Actions\Venue\UpdateVenue;
use App\Models\Sport;
use App\Models\Venue;

it('creates a venue', function (): void {
    $sport = Sport::factory()->create();
    $venue = Venue::factory()->create();

    $action = resolve(UpdateVenue::class);

    $action->handle($venue, [
        'name' => 'Updated Venue',
        'address' => 'Updated Address',
        'website' => 'https://example.com',
        'sport_id' => $sport->id,
    ]);

    expect($venue->name)->toBe('Updated Venue')
        ->and($venue->address)->toBe('Updated Address')
        ->and($venue->website)->toBe('https://example.com')
        ->and($venue->sport_id)->toBe($sport->id);
});
