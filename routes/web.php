<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;



Route::get('/', function () {
    return view('pages.accueil');
})->name('accueil');

Route::get('/edit', function () {
    return view('pages.edit');
})->name('pages.edit');

Route::get('/documents', function(){
    return view('pages.document');
})->name('pages.document');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');



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
