<?php

use App\Http\Controllers\Venue\Auth\RegisteredVenueController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->group(function () {
    Route::get('venue/register', [RegisteredVenueController::class, 'create'])
        ->name('venue.register');

    Route::post('venue/register', [RegisteredVenueController::class, 'store']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/venue/dashboard', function () {
        return Inertia::render('venue/dashboard');
    })->name('venue.dashboard');

    // Other authenticated routes...
});
