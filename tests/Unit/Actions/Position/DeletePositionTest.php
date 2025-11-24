<?php

declare(strict_types=1);

use App\Actions\Position\DeletePosition;
use App\Models\Position;

it('deletes a position', function (): void {
    $position = Position::factory()->create();
    $action = app(DeletePosition::class);
    $action->handle($position);
    expect($position->exists)->toBeFalse();
});
