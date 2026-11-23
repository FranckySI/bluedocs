<?php

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentationStyleController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentSearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

// ROUTES DE NAVIGATION
Route::get('/', function () {
    return view('pages.accueil');
})->name('accueil');

// Route du Sommaire
Route::get('/sommaire', [DocumentController::class, 'showSommaire'])->name('docs.sommaire');

// Recherche dans les documents
Route::get('/recherche', DocumentSearchController::class)->name('documents.search');

Route::middleware(['auth', 'account.active', 'verified', 'password.changed'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/documents', [DocumentController::class, 'index'])->name('pages.document');
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    Route::get('/password/first-login', fn () => view('auth.force-password-change'))
        ->name('password.first-login');
    Route::put('/password/first-login', [PasswordController::class, 'updateFirstLogin'])
        ->name('password.first-login.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::middleware('can:create-users')->group(function () {
        Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('/register', [RegisteredUserController::class, 'store']);
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/status', [UserManagementController::class, 'updateStatus'])
            ->name('users.update-status');
        Route::get('/admin/documentation-style', [DocumentationStyleController::class, 'edit'])
            ->name('admin.documentation-style.edit');
        Route::put('/admin/documentation-style', [DocumentationStyleController::class, 'update'])
            ->name('admin.documentation-style.update');
    });
});

require __DIR__.'/auth.php';

// ROUTE  WIKI On ajoute une contrainte (where) pour interdire à cette route de capturer 'login', 'logout', 'register', etc.
Route::get('/{document}', [DocumentController::class, 'show'])
    ->name('documents.show')
    ->where('document', '^(?!login|logout|register|password|verify-email).*$');
