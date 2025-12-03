<?php

declare(strict_types=1);

use App\Actions\Position\UpdatePosition;
use App\Models\Position;

it('updates a position', function (): void {
    $position = Position::factory()->create();
    $action = resolve(UpdatePosition::class);
    $action->handle($position, ['name' => 'New Name']);
    expect($position->name)->toBe('New Name');
});
