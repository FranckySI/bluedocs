<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class SectionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        try {
            $section = Section::create($validated);

            Log::info('Sommaire section created.', [
                'section_id' => $section->id,
                'actor_id' => $request->user()?->getAuthIdentifier(),
            ]);
        } catch (Throwable $exception) {
            Log::error('Failed to create a sommaire section.', [
                'title' => $validated['title'],
                'actor_id' => $request->user()?->getAuthIdentifier(),
                'exception' => $exception,
            ]);

            throw $exception;
        }

        return redirect()->route('docs.sommaire')->with('success', 'Section créée avec succès.');
    }

    public function destroy(Request $request, Section $section): RedirectResponse
    {
        try {
            $section->delete();

            Log::info('Sommaire section deleted.', [
                'section_id' => $section->id,
                'actor_id' => $request->user()?->getAuthIdentifier(),
            ]);
        } catch (Throwable $exception) {
            Log::error('Failed to delete a sommaire section.', [
                'section_id' => $section->id,
                'actor_id' => $request->user()?->getAuthIdentifier(),
                'exception' => $exception,
            ]);

            throw $exception;
        }

        return redirect()->route('docs.sommaire')->with('success', 'Section supprimée avec succès.');
    }
}
