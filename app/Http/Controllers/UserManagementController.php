<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    public function index(): View
    {
        return view('users.index', [
            'editors' => User::query()
                ->where('role', 'editeur')
                ->orderBy('name')
                ->orderBy('id')
                ->paginate(20),
        ]);
    }

    public function updateStatus(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === 'editeur', 404);

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $user->is_active = $validated['is_active'];
        $user->save();

        Log::info('Editor account status changed.', [
            'user_id' => $user->id,
            'actor_id' => $request->user()->id,
            'is_active' => $user->is_active,
        ]);

        return redirect()->route('users.index')->with('status', 'user-status-updated');
    }
}
