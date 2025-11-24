<?php

declare(strict_types=1);

use App\Actions\Round\DeleteRound;
use App\Models\Round;

it('deletes a round', function (): void {
    $round = Round::factory()->create();
    $action = app(DeleteRound::class);
    $action->handle($round);
    expect($round->deleted_at)->not->toBeNull();
});
