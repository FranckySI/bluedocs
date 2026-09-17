<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document; // Assurez-vous d'avoir créé le modèle Document
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{

    public function index()
    {
        $documents = Document::latest()->get();
        return view('pages.document', compact('documents'));
    }



    // affiche le formulaire de création de document
    public function create()
    {
        return view('docs.create');
    }

    // Enregistre un nouveau document dans la base de données
    public function store(Request $request)
    {
        // Validation des données du formulaire
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Sauvegarde du document dans la base de données
        Document::create([
            'title' => $validated['title'],
            'content' => $validated['content'], // <-- Plus aucune erreur dans l'éditeur !
            'user_name' => Auth::user()->name,
        ]);

        // Redirection vers la page de liste des documents avec un message de succès
        return redirect()->route('pages.document')->with('success', 'Document créé avec succès !');
    }

    // Affiche le détail d'un document spécifique
    public function show(Document $document)
    {
        return view('docs.show', compact('document'));
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
