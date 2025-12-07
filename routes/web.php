<?php

declare(strict_types=1);

use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Club\ClubController;
use App\Http\Controllers\Position\PositionController;
use App\Http\Controllers\Round\RoundController;
use App\Http\Controllers\Season\SeasonController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\TwoFactorAuthenticationController;
use App\Http\Controllers\Sport\SportController;
use App\Http\Controllers\User\AttachClientToUserController;
use App\Http\Controllers\User\DetachClientToUserController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Venue\VenueController;
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

Route::middleware(['auth', 'verified'])->prefix('dashboard')->group(function (): void {
    // Dashboard
    Route::get('/', fn () => Inertia::render('Dashboard'))->name('dashboard');

    // Sports
    Route::resource('sports', SportController::class)->only(['index', 'create', 'store', 'edit', 'update']);

    // Seasons
    Route::resource('seasons', SeasonController::class)->only(['index', 'create', 'store', 'edit', 'update']);

    // Rounds
    Route::resource('rounds', RoundController::class)->only(['index', 'create', 'store', 'edit', 'update']);

    // Users
    Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::post('users/{user}/client/{client}', AttachClientToUserController::class)->name('users.attach-client');
    Route::delete('users/{user}/client/{client}', DetachClientToUserController::class)->name('users.detach-client');

    // Clients
    Route::resource('clients', ClientController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    // Venues
    Route::resource('venues', VenueController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    // Positions
    Route::resource('positions', PositionController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    // Clubs
    Route::resource('clubs', ClubController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
});

require __DIR__.'/auth.php';
