<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // <-- AJOUTER CETTE LIGNE pour utiliser Str::slug()

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

    // Enregistre un nouveau document dans la base de données
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // MODIFICATION : On ajoute le 'slug' généré automatiquement à partir du titre
        Document::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']), // Ex: "Structure de table" -> "structure-de-table"
            'content' => $validated['content'],
            'user_name' => Auth::user()->name,
        ]);

        return redirect()->route('pages.document')->with('success', 'Document créé avec succès !');
    }

    // MODIFICATION : Laravel cherchera le document via le slug grâce à votre route
    public function show(Document $document)
    {
        return view('docs.show', compact('document'));
    }

    public function edit(Document $document)
    {
        return view('docs.edit', compact('document'));
    }

    // Met à jour le document
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // MODIFICATION : On met à jour les champs ET on recalcule le slug si le titre a changé
        $document->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
        ]);

        return redirect()->route('pages.document')->with('success', 'Document mis à jour avec succès !');
    }

    public function destroy(Document $document)
    {
        $document->delete();
        return redirect()->route('pages.document')->with('success', 'Document supprimé avec succès !');
    }
}
