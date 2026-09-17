<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Document;

class DashboardController extends Controller
{
    // ROUTE: /dashboard -> Affiche UNIQUEMENT le Dashboard
    public function index()
    {
        $connexions = User::whereNotNull('last_login_at')
            ->orderByDesc('last_login_at')
            ->take(10)
            ->get();

        // Récupère les 5 derniers pour le tableau du Dashboard
        $recentDocuments = Document::orderBy('updated_at', 'desc')->take(5)->get();
        return view('pages.dashboard', compact('connexions', 'recentDocuments'));
    }

    // ROUTE: /documents -> Affiche UNIQUEMENT la page Liste des documents
    public function recentDocuments()
    {
        $documents = Document::latest()->get();
        $recentDocuments = Document::orderBy('updated_at', 'desc')->take(5)->get();
        
        // RETOURNE LA VUE DOCUMENT
        return view('pages.document', compact('documents', 'recentDocuments'));
    }
}
