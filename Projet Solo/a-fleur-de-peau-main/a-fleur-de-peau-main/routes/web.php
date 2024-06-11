<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ArticleController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/gestion', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('/articles', [ArticleController::class,'index'])->name('articles.index');
    Route::get('/articles/create', [ArticleController::class,'create'])->name('articles.create');
    Route::get('/articles/show/{article}', [ArticleController::class,'show'])->name('articles.show');
    Route::get('/articles/edit/{article}', [ArticleController::class,'edit'])->name('articles.edit');
});
