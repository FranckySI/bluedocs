<?php

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

it('allows administrators to open the editor account screen', function () {
    $editor = User::factory()->create(['role' => 'editeur']);

    $this->actingAs($editor)
        ->get('/register')
        ->assertForbidden();

    $this->post('/register', [
        'name' => 'Unauthorized Editor',
        'email' => 'unauthorized@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertForbidden();

    $this->assertDatabaseMissing('users', ['email' => 'unauthorized@example.com']);

    $administrator = User::factory()->create(['role' => 'admin']);

    $this->actingAs($administrator)
        ->get('/register')
        ->assertOk()
        ->assertSee('Mot de passe provisoire')
        ->assertDontSee('name="role"', false);
});

it('creates an unverified editor account and sends a verification email', function () {
    $administrator = User::factory()->create(['role' => 'admin']);
    Notification::fake();
    Log::spy();

    $response = $this->actingAs($administrator)->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Temporary!123',
        'password_confirmation' => 'Temporary!123',
    ]);

    $response->assertRedirect(route('dashboard', absolute: false));
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'role' => 'editeur',
        'email_verified_at' => null,
        'is_active' => true,
        'must_change_password' => true,
    ]);

    $user = User::where('email', 'test@example.com')->firstOrFail();
    expect(Hash::check('Temporary!123', $user->password))->toBeTrue();
    Notification::assertSentTo($user, VerifyEmail::class, function (VerifyEmail $notification) use ($user): bool {
        $message = $notification->toMail($user);

        return $message->subject === 'Vérifiez votre adresse e-mail - BlueDocs'
            && str_contains(implode(' ', $message->introLines), route('login'))
            && ! str_contains(implode(' ', $message->introLines), 'Temporary!123');
    });

    Log::shouldHaveReceived('info')
        ->once()
        ->with('Editor account created.', [
            'user_id' => $user->id,
            'actor_id' => $administrator->id,
            'verification_notification_dispatched' => true,
            'mailer' => config('mail.default'),
            'smtp_host' => 'smtp-load.malagasy.com',
            'smtp_auto_tls' => false,
            'smtp_require_tls' => false,
        ]);
});

it('uses the corporate SMTP relay with TLS and the BlueDocs sender identity', function () {
    expect(config('mail.mailers.smtp.host'))->toBe('smtp-load.malagasy.com');
    expect(config('mail.mailers.smtp.port'))->toBe('587');
    expect(config('mail.mailers.smtp.auto_tls'))->toBeFalse();
    expect(config('mail.mailers.smtp.require_tls'))->toBeFalse();
    expect(config('mail.mailers.smtp.username'))->toBeNull();
    expect(config('mail.mailers.smtp.password'))->toBeNull();
    expect(config('mail.from'))->toBe([
        'address' => 'admin@bluedocs.mg',
        'name' => 'BlueDocs',
    ]);
});

it('prevents administrators from assigning a privileged role during editor creation', function () {
    $administrator = User::factory()->create(['role' => 'admin']);

    $this->actingAs($administrator)
        ->post('/register', [
            'name' => 'Legacy User',
            'email' => 'legacy@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
        ])
        ->assertSessionHasErrors('role');

    $this->assertDatabaseMissing('users', ['email' => 'legacy@example.com']);
});

it('logs and rethrows verification email delivery failures', function () {
    $administrator = User::factory()->create(['role' => 'admin']);
    $dispatcher = Mockery::mock(Dispatcher::class);
    $dispatcher->shouldReceive('send')
        ->once()
        ->andThrow(new RuntimeException('Mail transport unavailable.'));
    $this->app->instance(Dispatcher::class, $dispatcher);
    Log::spy();

    $this->actingAs($administrator)
        ->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
        ->assertInternalServerError();

    $user = User::where('email', 'test@example.com')->firstOrFail();
    Log::shouldHaveReceived('error')
        ->with('Editor account verification email failed.', Mockery::on(
            fn (array $context): bool => $context['user_id'] === $user->id
                && $context['actor_id'] === $administrator->id
                && $context['mailer'] === config('mail.default')
                && $context['smtp_host'] === 'smtp-load.malagasy.com'
                && $context['smtp_auto_tls'] === false
                && $context['smtp_require_tls'] === false
                && $context['exception'] instanceof RuntimeException,
        ));
});
