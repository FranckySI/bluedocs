<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();
        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'must_change_password' => false,
        ]);
        $user->save();

        Log::info('Account password changed.', [
            'user_id' => $user->id,
            'actor_id' => $user->id,
            'forced_change' => false,
        ]);

        return back()->with('status', 'password-updated');
    }

    public function updateFirstLogin(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();
        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'must_change_password' => false,
        ]);
        $user->save();

        Log::info('Account password changed.', [
            'user_id' => $user->id,
            'actor_id' => $user->id,
            'forced_change' => true,
        ]);

        return redirect(route('dashboard', absolute: false))->with('status', 'password-updated');
    }
}
