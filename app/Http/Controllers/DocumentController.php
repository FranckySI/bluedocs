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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Si le slug est envoyé en cachette par le formulaire, on le garde. 
        // Sinon, on le génère à partir du titre.
        $document = Document::create([
            'title' => $validated['title'],
            'slug' => $request->input('slug') ?? Str::slug($validated['title']), 
            'content' => $validated['content'],
            'user_name' => Auth::user()->name,
        ]);

        // Au lieu de retourner aux archives, on redirige l'utilisateur directement 
        // sur le document qu'il vient d'enregistrer
        return redirect()->route('documents.show', $document->slug)->with('success', 'Document créé avec succès !');
    }

    // Plus besoin de manipuler de chaînes de caractères, Laravel injecte l'objet via son slug
    public function show($slug)
    {
        // On cherche si le document existe en BDD
        $document = Document::where('slug', $slug)->first();

        // S'il n'existe pas, on prépare un document "virtuel" temporaire
        if (!$document) {
            $document = new Document();
            $document->title = ucfirst(str_replace('-', ' ', $slug)); // Ex: "manager-soap" -> "Manager soap"
            $document->slug = $slug;
            $document->content = '
                <div class="p-6 bg-amber-50 border border-amber-200 rounded-md text-amber-800 mb-4" style="background-color: #fef3c7; border-color: #fde68a; color: #92400e; padding: 1.5rem; border-radius: 0.375rem; margin-bottom: 1rem;">
                    <strong>Ce document n\'est pas encore disponible.</strong><br>
                </div>';
                
            $document->user_name = Auth::user() ? Auth::user()->name : 'Système';
            
            // Marqueur temporaire pour que la vue Blade sache qu'il faut afficher l'option "Créer"
            $document->is_new = true; 
        }

        // On utilise TOUJOURS votre vue unique centralisée dans 'pages'
        return view('docs.sommaire', compact('document'));
    }

    public function edit(Document $document)
    {
        return view('docs.edit', compact('document'));
    }

    public function showSommaire()
    {
        // On récupère le document "sommaire" de façon standard
        $document = Document::where('slug', 'sommaire')->firstOrFail();

        return view('docs.sommaire', compact('document'));
    }
    
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $document->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
        ]);

        return redirect()->route('docs.sommaire')->with('success', 'Document mis à jour avec succès !');
    }

    public function destroy(Document $document)
    {
        $document->delete();
        return redirect()->route('pages.document')->with('success', 'Document supprimé avec succès !');
    }
}
