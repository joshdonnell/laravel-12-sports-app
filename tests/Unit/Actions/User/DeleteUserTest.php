<?php

declare(strict_types=1);

use App\Actions\User\DeleteUser;
use App\Models\User;

it('soft deletes a user', function (): void {
    $user = User::factory()->create();

    $action = resolve(DeleteUser::class);

    $action->handle($user);

    expect($user->deleted_at)->not->toBeNull();
});
