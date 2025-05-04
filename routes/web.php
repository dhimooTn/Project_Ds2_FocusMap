<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\TaskController;

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
});

// Routes pour utilisateurs authentifiés
Route::middleware('auth')->group(function () {
    // Déconnexion
    Route::post('/logout', [LoginController::class, 'logout'])->name('login.logout');

    // Tableau de bord
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Objectifs - Routes CRUD
    Route::prefix('goals')->group(function () {
        Route::get('/', [GoalController::class, 'index'])->name('goals.index');
        Route::post('/', [GoalController::class, 'store'])->name('goals.store');
        Route::get('/create', [GoalController::class, 'create'])->name('goals.create');
        Route::get('/{goal}', [GoalController::class, 'show'])->name('goals.show');
        Route::get('/{goal}/edit', [GoalController::class, 'edit'])->name('goals.edit');
        Route::put('/{goal}', [GoalController::class, 'update'])->name('goals.update');
        Route::delete('/{goal}', [GoalController::class, 'destroy'])->name('goals.destroy');
        
        // Tâches associées aux objectifs
        Route::prefix('{goal}/tasks')->group(function () {
            Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
            Route::put('/{task}', [TaskController::class, 'update'])->name('tasks.update');
            Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
        });
    });

    // Tâches - Routes supplémentaires
    Route::prefix('tasks')->group(function () {
        Route::put('/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update.status');
    });

    // Autres pages
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