<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['prohibited'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'editeur',
            'is_active' => true,
            'must_change_password' => true,
        ]);

        try {
            event(new Registered($user));
        } catch (Throwable $exception) {
            Log::error('Editor account verification email failed.', [
                'user_id' => $user->id,
                'actor_id' => $request->user()->id,
                'mailer' => config('mail.default'),
                'smtp_host' => config('mail.mailers.smtp.host'),
                'smtp_auto_tls' => config('mail.mailers.smtp.auto_tls'),
                'smtp_require_tls' => config('mail.mailers.smtp.require_tls'),
                'exception' => $exception,
            ]);

            throw $exception;
        }

        Log::info('Editor account created.', [
            'user_id' => $user->id,
            'actor_id' => $request->user()->id,
            'verification_notification_dispatched' => true,
            'mailer' => config('mail.default'),
            'smtp_host' => config('mail.mailers.smtp.host'),
            'smtp_auto_tls' => config('mail.mailers.smtp.auto_tls'),
            'smtp_require_tls' => config('mail.mailers.smtp.require_tls'),
        ]);

        return redirect(route('dashboard', absolute: false));
    }
}
