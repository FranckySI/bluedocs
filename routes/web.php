<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;



// ROUTES DE NAVIGATION 
Route::get('/', function () {
    return view('pages.accueil');
})->name('accueil');

Route::get('/plan', function () {
    return view('pages.plan');
})->name('pages.plan');


// Dashboard principal
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Route du Sommaire
Route::get('/sommaire', [DocumentController::class, 'showSommaire'])->name('docs.sommaire');



//  CRUD DES DOCUMENTS (Protégées par Auth)
Route::middleware('auth')->group(function(){
    // Liste des archives / Tous les documents
    Route::get('/documents', [DocumentController::class, 'index'])->name('pages.document');
    
    // Création d'un document
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    
    // Gestion du document par son modèle (Laravel utilise le slug grâce à votre modèle)
    Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
});



//  SÉCURITÉ, UTILISATEURS ET PROFILS
Route::middleware('can:create-users')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// ROUTE  WIKI On ajoute une contrainte (where) pour interdire à cette route de capturer 'login', 'logout', 'register', etc.
Route::get('/{document}', [DocumentController::class, 'show'])
    ->name('documents.show')
    ->where('document', '^(?!login|logout|register|password|verify-email).*$');

    // Tout en bas de votre fichier routes/web.php
Route::get('/{slug}', [DocumentController::class, 'show'])->name('documents.show');






