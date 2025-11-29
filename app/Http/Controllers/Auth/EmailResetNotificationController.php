<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\CreateUserEmailResetNotification;
use App\Http\Requests\Auth\CreateUserEmailResetNotificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final readonly class EmailResetNotificationController
{
    public function create(Request $request): Response
    {
        return Inertia::render('auth/ForgotPassword', [
            'status' => $request->session()->get('status'),
        ]);
    }

    public function store(
        CreateUserEmailResetNotificationRequest $request,
        CreateUserEmailResetNotification $action
    ): RedirectResponse {
        $action->handle(['email' => $request->string('email')->value()]);

        return back()->with('status', __('We have emailed your password reset link.'));
    }
}
