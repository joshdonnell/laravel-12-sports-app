<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\CreateUser;
use App\Actions\Auth\DeleteUser;
use App\Http\Requests\Auth\CreateUserRequest;
use App\Http\Requests\Auth\DeleteUserRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

final readonly class UserController
{
    public function store(CreateUserRequest $request, CreateUser $action, #[CurrentUser] User $user): RedirectResponse
    {
        /** @var array<string, mixed> $attributes */
        $attributes = $request->safe()->except('password', 'sport_id');
        $sport = $request->integer('sport_id') ?: $user->sport_id;

        $user = $action->handle(
            $attributes,
            $request->string('password')->value(),
            $sport
        );

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(DeleteUserRequest $request, #[CurrentUser] User $user, DeleteUser $action): RedirectResponse
    {
        Auth::logout();

        $action->handle($user);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('home');
    }
}
