<?php

use App\Http\Controllers\Artist\Auth\RegisteredArtistController;
use App\Http\Controllers\Artist\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use App\Http\Controllers\Artist\Settings\ProfileController;

Route::middleware('guest')->group(function () {
    Route::get('artist/register', [RegisteredArtistController::class, 'create'])
        ->name('artist.register');

    Route::post('artist/register', [RegisteredArtistController::class, 'store']);
});

Route::middleware(['auth', 'verified', 'role:artist'])->group(function () {
    Route::get('/artist/dashboard', [DashboardController::class, 'index'])->name('artist.dashboard');

    // Artist profile routes
    Route::prefix('artist/settings')->name('artist.')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Other authenticated routes...
});
