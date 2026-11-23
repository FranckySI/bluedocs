<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class DocumentationStyleController extends Controller
{
    public function edit(): View
    {
        Gate::authorize('create-users');

        return view('admin.documentation-style', [
            'style' => Setting::documentationStyle(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        Gate::authorize('create-users');

        $validated = $request->validate([
            'documentation_font_family' => ['required', 'in:sans,serif,mono'],
            'documentation_font_size' => ['required', 'integer', 'min:12', 'max:24'],
            'documentation_text_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'documentation_heading_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'documentation_background_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'documentation_line_height' => ['required', 'numeric', 'min:1', 'max:2'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => (string) $value],
            );
        }

        Log::info('Documentation style updated.', [
            'actor_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('admin.documentation-style.edit')
            ->with('status', 'documentation-style-updated');
    }
}
