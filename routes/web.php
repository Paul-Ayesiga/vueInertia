<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArtistController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/artists/{artist}', [ArtistController::class, 'show'])->name('artist.show');
Route::post('/artists/{artist}/follow', [ArtistController::class, 'toggleFollow'])->name('artist.follow');

Route::middleware(['auth', 'verified' ,'role:user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Other authenticated routes...
});

// Add this temporary debug route
Route::get('/debug-artist/{artist}', function (App\Models\Artist $artist) {
    return [
        'artist' => [
            'id' => $artist->id,
            'name' => $artist->stage_name,
            'profilePhoto' => $artist->profile_photo ? asset('storage/' . $artist->profile_photo) : null,
            'coverPhoto' => $artist->cover_photo ? asset('storage/' . $artist->cover_photo) : null,
        ],
        'raw_data' => [
            'profile_photo' => $artist->profile_photo,
            'cover_photo' => $artist->cover_photo,
        ],
        'storage_check' => [
            'profile_photo_exists' => $artist->profile_photo ? \Illuminate\Support\Facades\Storage::disk('public')->exists($artist->profile_photo) : null,
            'cover_photo_exists' => $artist->cover_photo ? \Illuminate\Support\Facades\Storage::disk('public')->exists($artist->cover_photo) : null,
        ],
        'asset_urls' => [
            'profile_photo_url' => $artist->profile_photo ? asset('storage/' . $artist->profile_photo) : null,
            'cover_photo_url' => $artist->cover_photo ? asset('storage/' . $artist->cover_photo) : null,
        ]
    ];
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/artist.php';
require __DIR__ . '/organiser.php';
require __DIR__ . '/venue.php';
