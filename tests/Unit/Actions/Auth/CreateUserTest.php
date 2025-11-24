<?php

declare(strict_types=1);

use App\Actions\Auth\CreateUser;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;

it('creates a user', function (): void {
    Event::fake([Registered::class]);

    $sport = Sport::factory()->create();
    $action = app(CreateUser::class);

    $user = $action->handle([
        'name' => 'Test User',
        'email' => 'example@email.com',
    ], 'password', $sport->id);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->name)->toBe('Test User')
        ->and($user->email)->toBe('example@email.com')
        ->and($user->password)->not->toBe('password');

    Event::assertDispatched(Registered::class);
});
