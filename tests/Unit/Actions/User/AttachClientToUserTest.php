<?php

declare(strict_types=1);

use App\Actions\User\AttachClientToUser;
use App\Models\Client;
use App\Models\User;

it('can attach a client from user', function (): void {
    $user = User::factory()->create();
    $client = Client::factory()->create();

    expect($user->clients)->toHaveCount(0);

    resolve(AttachClientToUser::class)->handle($user, $client);

    $user->load('clients');
    expect($user->clients)->toHaveCount(1);
});
