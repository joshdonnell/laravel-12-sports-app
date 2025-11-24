<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\EmailResetNotificationController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Auth\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    // Login...
    Route::get('login', [SessionController::class, 'create'])
        ->name('login');
    Route::post('login', [SessionController::class, 'store'])
        ->name('login.store');

    // Forgot Password...
    Route::get('forgot-password', [EmailResetNotificationController::class, 'create'])
        ->name('password.request');
    Route::post('forgot-password', [EmailResetNotificationController::class, 'store'])
        ->name('password.email');

    // Reset Password...
    Route::get('reset-password/{token}', [PasswordResetController::class, 'create'])
        ->name('password.reset');
    Route::post('reset-password', [PasswordResetController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function (): void {
    // Delete User...
    Route::delete('user', [UserController::class, 'destroy'])->name('user.destroy');

    // User Email Verification...
    Route::get('verify-email', [EmailVerificationNotificationController::class, 'create'])
        ->name('verification.notice');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Verify Email...
    Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'update'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Logout...
    Route::post('logout', [SessionController::class, 'destroy'])
        ->name('logout');
});
