<?php

use App\Models\User;
use Illuminate\Support\Facades\Log;

it('renders the login screen', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

it('authenticates active users using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

it('does not authenticate users with an invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

it('logs users out', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});

it('does not authenticate a disabled account and logs the failed attempt', function () {
    $user = User::factory()->create([
        'email' => 'disabled@example.com',
        'is_active' => false,
    ]);
    Log::spy();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
    Log::shouldHaveReceived('warning')
        ->once()
        ->with('Authentication attempt failed.', Mockery::on(function (array $context): bool {
            return $context['email_fingerprint'] === hash_hmac('sha256', 'disabled@example.com', (string) config('app.key'));
        }));
});

it('logs out an already authenticated user after their account is disabled', function () {
    $user = User::factory()->create(['is_active' => false]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});
