<?php

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\SessionController;

use App\Http\Controllers\Artist\Settings\ProfileController as ArtistProfile;
use App\Http\Controllers\Artist\Settings\PasswordController as ArtistPassword;
use App\Http\Controllers\Artist\Settings\SessionController as ArtistSession;

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::redirect('settings', '/settings/profile');

    // admin routes

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('settings/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('settings/appearance', function () {
        return Inertia::render('settings/Appearance');
    })->name('appearance');

    Route::get('settings/session',[SessionController::class, 'index'])->name('session.index');
    Route::delete('settings/session/{id}', [SessionController::class, 'destroy'])->name('session.destroy');
    Route::post('settings/session/logout-others', [SessionController::class, 'logoutOthers'])->name('session.logout.others');


    // artist routes
    Route::get('artist/settings/profile', [ArtistProfile::class, 'edit'])->name('artist.profile.edit');

    Route::patch('artist/settings/profile', [ArtistProfile::class, 'update'])->name('artist.profile.update');
    Route::delete('artist/settings/profile', [ArtistProfile::class, 'destroy'])->name('artist.profile.destroy');


    Route::get('artist/settings/password', [ArtistPassword::class, 'edit'])->name('artist.password.edit');

    Route::get('artist/settings/appearance', function () {
        return Inertia::render('artist/settings/Appearance');
    })->name('artist.appearance');

    Route::get('artist/settings/session', [ArtistSession::class, 'index'])->name('artist.session.index');
    Route::delete('artist/settings/session/{id}', [ArtistSession::class, 'destroy'])->name('artist.session.destroy');
    Route::post('artist/settings/session/logout-others', [ArtistSession::class, 'logoutOthers'])->name('artist.session.logout.others');

});
