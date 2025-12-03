<?php

declare(strict_types=1);

use App\Actions\User\CreateUser;
use App\Enums\Role;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;

beforeEach(function (): void {
    $this->seed(Database\Seeders\RoleAndPermissionSeeder::class);
});

it('creates a user', function (): void {
    Event::fake([Registered::class]);

    $sport = Sport::factory()->create();
    $action = resolve(CreateUser::class);

    $user = $action->handle([
        'name' => 'Test User',
        'email' => 'example@email.com',
        'sport_id' => $sport->id,
    ], 'password', Role::Admin);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->name)->toBe('Test User')
        ->and($user->email)->toBe('example@email.com')
        ->and($user->password)->not->toBe('password')
        ->and($user->sport_id)->toBe($sport->id)
        ->and($user->hasRole(Role::Admin))->toBeTrue();

    Event::assertDispatched(Registered::class);
});
