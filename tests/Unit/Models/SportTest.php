<?php

declare(strict_types=1);

use App\Models\Sport;

test('the sport model structure matches', function (): void {
    $sport = Sport::factory()->create()->refresh();

    expect(array_keys($sport->toArray()))
        ->toBe([
            'id',
            'uuid',
            'name',
            'created_at',
            'updated_at',
        ]);
});
