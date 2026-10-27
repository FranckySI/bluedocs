<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::latest()->get();

        return view('pages.document', compact('documents'));
    }

    public function create()
    {
        return view('docs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'slug' => 'nullable|string|max:255|unique:documents,slug',
        ]);

        $slug = $validated['slug'] ?? Str::slug($validated['title']);

        if (! isset($validated['slug'])) {
            $baseSlug = $slug !== '' ? $slug : 'document';
            $slug = $baseSlug;
            $suffix = 2;

            while (Document::query()->where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$suffix;
                $suffix++;
            }
        }

        $document = Document::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'user_name' => Auth::user()->name,
        ]);

        Log::info('Document created.', [
            'document_id' => $document->id,
            'slug' => $document->slug,
            'actor_id' => Auth::id(),
        ]);

        return redirect()->route('docs.sommaire')->with('success', 'Document créé avec succès !');
    }

    public function show(string $slug): View
    {
        $document = Document::where('slug', $slug)->first();

        if (! $document) {
            $document = new Document;
            $document->title = ucfirst(str_replace('-', ' ', $slug));
            $document->slug = $slug;
            $document->content = '
            <div class="p-6 bg-amber-50 border border-amber-200 rounded-md text-amber-800 mb-4">
                <strong>Ce document n\'existe pas encore.</strong><br>
                Si vous avez les droits, vous pouvez le créer dès maintenant en cliquant sur le bouton "Créer ce document" ci-dessus.
            </div>';
            $document->user_name = Auth::user() ? Auth::user()->name : 'Système';

            $document->is_new = true;
        }

        return view('docs.sommaire', [
            'document' => $document,
        ]);
    }

    public function showSommaire(): View
    {
        $document = Document::query()
            ->where('slug', 'sommaire')
            ->first();

        if (! $document) {
            $document = new Document([
                'title' => 'Sommaire',
                'slug' => 'sommaire',
                'content' => '<p>Le sommaire n’a pas encore été rédigé.</p>',
                'user_name' => 'Système',
            ]);
            $document->is_new = true;
        }

        return view('docs.sommaire', [
            'document' => $document,
        ]);
    }

    public function edit(Document $document)
    {
        return view('docs.edit', compact('document'));
    }

    public function update(Request $request, Document $document): RedirectResponse
    {
        // Validation des données du formulaire
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // On prépare les données à sauvegarder
        $data = $validated;
        $data['updated_by'] = Auth::id();   // Enregistre l'ID (Utile pour la relation Eloquent)
        $data['user_name'] = Auth::user()->name; // Écrase l'ancien nom par celui du modificateur

        // Mise à jour du document dans la base de données
        $document->update($data);

        Log::info('Document updated.', [
            'document_id' => $document->id,
            'slug' => $document->slug,
            'actor_id' => Auth::id(),
        ]);

        return redirect()->route('docs.sommaire')->with('success', 'Document mis à jour avec succès !');
    }

    public function destroy(Document $document): RedirectResponse
    {
        $documentId = $document->id;
        $documentSlug = $document->slug;

        $document->delete();

        Log::info('Document deleted.', [
            'document_id' => $documentId,
            'slug' => $documentSlug,
            'actor_id' => Auth::id(),
        ]);

        return redirect()->route('pages.document')->with('success', 'Document supprimé avec succès !');
    }
}
