<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

it('renders forgot password page', function (): void {
    $response = $this->fromRoute('home')
        ->get(route('password.request'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('auth/ForgotPassword')
            ->has('status'));
});

it('may send password reset notification', function (): void {
    Notification::fake();

    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $response = $this->fromRoute('password.request')
        ->post(route('password.email'), [
            'email' => 'test@example.com',
        ]);

    $response->assertRedirectToRoute('password.request')
        ->assertSessionHas('status', 'We have emailed your password reset link.');

    Notification::assertSentTo($user, ResetPassword::class);
});

it('returns generic message for non-existent email', function (): void {
    Notification::fake();

    $response = $this->fromRoute('password.request')
        ->post(route('password.email'), [
            'email' => 'nonexistent@example.com',
        ]);

    $response->assertSessionHasErrors([
        'email' => "We can't find a user with that email address.",
    ]);

    Notification::assertNothingSent();
});

it('requires email', function (): void {
    $response = $this->fromRoute('password.request')
        ->post(route('password.email'), []);

    $response->assertRedirectToRoute('password.request')
        ->assertSessionHasErrors('email');
});

it('requires valid email format', function (): void {
    $response = $this->fromRoute('password.request')
        ->post(route('password.email'), [
            'email' => 'not-an-email',
        ]);

    $response->assertRedirectToRoute('password.request')
        ->assertSessionHasErrors('email');
});

it('redirects authenticated users away from forgot password', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->fromRoute('dashboard')
        ->get(route('password.request'));

    $response->assertRedirectToRoute('dashboard');
});
