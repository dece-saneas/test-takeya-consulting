<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::resource('post', PostController::class)
        ->except(['index', 'show']);
});

Route::resource('post', PostController::class)
    ->only(['index', 'show']);

Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
