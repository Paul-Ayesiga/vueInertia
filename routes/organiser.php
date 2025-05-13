<?php

use App\Http\Controllers\Organiser\Auth\RegisteredOrganiserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->group(function () {
    Route::get('organiser/register', [RegisteredOrganiserController::class, 'create'])
        ->name('organiser.register');

    Route::post('organiser/register', [RegisteredOrganiserController::class, 'store']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/organiser/dashboard', function () {
        return Inertia::render('organiser/dashboard');
    })->name('organiser.dashboard');

    // Other authenticated routes...
});
