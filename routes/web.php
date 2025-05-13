<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArtistController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Other authenticated routes...
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/artist/dashboard', [ArtistController::class, 'index'])->name('artist.dashboard');

    // Other authenticated routes...
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
