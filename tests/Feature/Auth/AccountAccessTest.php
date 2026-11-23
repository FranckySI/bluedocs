<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

it('requires email verification and a password change before granting normal access', function () {
    $user = User::factory()->unverified()->create(['must_change_password' => true]);
    Log::spy();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('verification.notice'));

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)],
    );

    $this->get($verificationUrl)
        ->assertRedirect(route('dashboard', absolute: false));

    $this->get(route('dashboard'))
        ->assertRedirect(route('password.first-login'));

    $this->get(route('password.first-login'))
        ->assertOk()
        ->assertSee('Changer le mot de passe provisoire');

    $passwordResponse = $this->put(route('password.first-login.update'), [
        'current_password' => 'password',
        'password' => 'new-secure-password',
        'password_confirmation' => 'new-secure-password',
    ]);

    $passwordResponse->assertRedirect(route('dashboard', absolute: false));

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'must_change_password' => false,
    ]);
    expect(Hash::check('new-secure-password', $user->fresh()->password))->toBeTrue();

    $this->get(route('dashboard'))->assertOk();

    Log::shouldHaveReceived('info')
        ->once()
        ->with('Account password changed.', [
            'user_id' => $user->id,
            'actor_id' => $user->id,
            'forced_change' => true,
        ]);
});

it('does not allow a user to bypass the forced password change through profile pages', function () {
    $user = User::factory()->create(['must_change_password' => true]);

    $this->actingAs($user)
        ->get(route('profile.edit'))
        ->assertRedirect(route('password.first-login'));
});

it('keeps the forced password change pending when the temporary password is incorrect', function () {
    $user = User::factory()->create(['must_change_password' => true]);

    $this->actingAs($user)
        ->put(route('password.first-login.update'), [
            'current_password' => 'wrong-password',
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ])
        ->assertSessionHasErrorsIn('updatePassword', 'current_password');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'must_change_password' => true,
    ]);
});
