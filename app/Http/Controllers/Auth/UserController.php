<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\User\DeleteUser;
use App\Http\Requests\Auth\DeleteUserRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

final readonly class UserController
{
    // TODO: make this an invokeable function to delete a your user
    public function destroy(DeleteUserRequest $request, #[CurrentUser] User $user, DeleteUser $action): RedirectResponse
    {
        Auth::logout();

        $action->handle($user);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('home');
    }
}
