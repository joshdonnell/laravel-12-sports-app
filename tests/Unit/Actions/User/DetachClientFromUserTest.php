<?php

declare(strict_types=1);

use App\Actions\User\DetachClientFromUser;
use App\Models\Client;
use App\Models\User;

it('can detach a client from user', function (): void {
    $user = User::factory()->create();
    $client = Client::factory()->create();
    $user->clients()->attach($client);

    expect($user->clients)->toHaveCount(1);

    resolve(DetachClientFromUser::class)->handle($user, $client);

    $user->load('clients');
    expect($user->clients)->toHaveCount(0);
});
