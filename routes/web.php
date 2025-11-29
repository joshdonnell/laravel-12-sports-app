<?php

declare(strict_types=1);

use App\Http\Controllers\Season\SeasonController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\TwoFactorAuthenticationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->group(function (): void {
    Route::redirect('/', 'login')->name('home');
});

Route::middleware('auth')->group(function (): void {
    // User Profile...
    Route::redirect('settings', '/settings/profile');
    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('user-profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('user-profile.update');

    // User Password...
    Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('settings/password', [PasswordController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('password.update');

    // User Two-Factor Authentication...
    Route::get('settings/two-factor', [TwoFactorAuthenticationController::class, 'show'])
        ->name('two-factor.show');
});

// TODO: we need to add routes and use the spatie permission package to protect them
// Rounds
// Clubs
// Teams
// Players
// Positions

Route::middleware(['auth', 'verified'])->prefix('dashboard')->group(function (): void {
    // Dashboard
    Route::get('/', fn () => Inertia::render('Dashboard'))->name('dashboard');

    // Seasons
    Route::resource('seasons', SeasonController::class)->only(['index', 'create', 'store', 'edit', 'update']);
});

require __DIR__.'/auth.php';
