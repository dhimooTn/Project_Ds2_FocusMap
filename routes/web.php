<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\MindMapController;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('login.logout');
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

    // Goals
    Route::prefix('goals')->group(function () {
        Route::get('/', [GoalController::class, 'index'])->name('goals.index');
        Route::post('/', [GoalController::class, 'store'])->name('goals.store');
        Route::get('/create', [GoalController::class, 'create'])->name('goals.create');
        Route::get('/{goal}', [GoalController::class, 'show'])->name('goals.show');
        Route::get('/{goal}/edit', [GoalController::class, 'edit'])->name('goals.edit');
        Route::put('/{goal}', [GoalController::class, 'update'])->name('goals.update');
        Route::delete('/{goal}', [GoalController::class, 'destroy'])->name('goals.destroy');
        
        // Tasks
        Route::prefix('{goal}/tasks')->group(function () {
            Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
            Route::put('/{task}', [TaskController::class, 'update'])->name('tasks.update');
            Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
        });
    });

    // Tasks
    Route::prefix('tasks')->group(function () {
        Route::post('/store', [TaskController::class, 'storeStandalone'])->name('tasks.store.standalone');
        Route::put('/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update.status');
    });

    // Other pages
    Route::get('/mindMap', [MindMapController::class, 'index'])->name('mindMap');

    Route::get('/cartGeo', function () {
        return view('carteGeo');
    })->name('carteGeo');

    Route::get('/calendar', function () {
        return view('calandar');
    })->name('calendar');

    // Blog routes
    Route::prefix('blog')->group(function () {
        Route::get('/', [BlogController::class, 'index'])->name('blog.index');
        Route::get('/create', [BlogController::class, 'create'])->name('blog.create');
        Route::post('/', [BlogController::class, 'store'])->name('blog.store');
        Route::get('/{journal}', [BlogController::class, 'show'])->name('blog.show');
        Route::get('/{journal}/edit', [BlogController::class, 'edit'])->name('blog.edit');
        Route::put('/{journal}', [BlogController::class, 'update'])->name('blog.update');
        Route::delete('/{journal}', [BlogController::class, 'destroy'])->name('blog.destroy');
    });
});