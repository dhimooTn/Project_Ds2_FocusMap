<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

// Routes pour invités (non connectés)
Route::middleware('guest')->group(function () {
    // Formulaire de connexion
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login.form');
    
    // Traitement du login
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    
    // Formulaire d'inscription
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    
    // Traitement de l'inscription
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
    
    // Logout pour les invités (si nécessaire)
    Route::post('/logout', [LoginController::class, 'logout'])->name('login.logout');
});

// Routes pour utilisateurs authentifiés
Route::middleware('auth')->group(function () {
    // Déconnexion
    Route::post('/logout', [LoginController::class, 'logout'])->name('login.logout');

    // Tableau de bord
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/goals', function () {
        return view('goals');
    })->name('goals');
    Route::get('/mindMap', function () {
        return view('mindMap');
    })->name('mindMap');
    Route::get('/cartGeo', function () {
        return view('carteGeo');
    })->name('carteGeo');
    Route::get('/calendar', function () {
        return view('calandar');
    })->name('calendar');
    Route::get('/blog', function () {
        return view('blog');
    })->name('blog');
});
