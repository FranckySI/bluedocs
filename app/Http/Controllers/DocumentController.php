<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document; 
use Illuminate\Support\Facades\Auth;
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

    public function store(Request $request)
    {
        // Validation 
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Sauvegarde base de données
        Document::create([
        'title' => $validated['title'],
        'slug' => $request->input('slug') ?? Str::slug($validated['title']), 
        'content' => $validated['content'],
        'user_name' => Auth::user()->name,
    ]);

        return redirect()->route('pages.document')->with('success', 'Document créé avec succès !');
    }

    // Affiche le détail d'un doc
    public function show($slug)
    {
        //  cherche le document par slug
        $document = Document::where('slug', $slug)->first();

        //  S'il n'existe pas dans MySQL, on prépare un document "virtuel"
        if (!$document) {
            $document = new Document();
            $document->title = ucfirst(str_replace('-', ' ', $slug));
            $document->slug = $slug;
            // Ce contenu sera visible uniquement en mode lecture
            $document->content = '
            <div class="p-6 bg-amber-50 border border-amber-200 rounded-md text-amber-800 mb-4">
                <strong>Ce document n\'existe pas encore.</strong><br>
                Si vous avez les droits, vous pouvez le créer dès maintenant en cliquant sur le bouton "Créer ce document" ci-dessus.
            </div>';
            $document->user_name = Auth::user() ? Auth::user()->name : 'Système';

            $document->is_new = true;
        }

        return view('docs.sommaire', compact('document'));
    }

    public function showSommaire()
    {
        $document = Document::where('slug', 'sommaire')->firstOrFail();
        return view('docs.sommaire', compact('document'));
    }



    public function edit(Document $document)
    {
        return view('docs.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        // Validation des données du formulaire
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Mise à jour du document dans la base de données
        $document->update($validated);

        // Redirection vers la page de liste des documents avec un message de succès
        return redirect()->route('pages.document')->with('success', 'Document mis à jour avec succès !');
    }

    public function destroy(Document $document)
    {
        $document->delete();
        return redirect()->route('pages.document')->with('success', 'Document supprimé avec succès !');
    }
}
