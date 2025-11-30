<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\Sport;
use App\Models\User;

test('the user model structure matches', function (): void {
    $user = User::factory()->create()->refresh();

    expect(array_keys($user->toArray()))
        ->toBe([
            'id',
            'uuid',
            'name',
            'email',
            'email_verified_at',
            'two_factor_confirmed_at',
            'sport_id',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
});

test('a user belongs to many clients', function (): void {
    $user = User::factory()->hasClients(3)->create()->refresh();

    expect($user->clients)->toHaveCount(3)
        ->and($user->clients->first())->toBeInstanceOf(Client::class);
});

test('a user belongs to a sport', function (): void {
    $user = User::factory()->create()->refresh();

    expect($user->sport)->toBeInstanceOf(Sport::class);
});
