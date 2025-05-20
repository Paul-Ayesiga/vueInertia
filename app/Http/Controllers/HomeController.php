<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        // Get popular artists with their user data
        $popularArtists = Artist::with('user')
            ->select([
                'id',
                'user_id',
                'stage_name',
                'profile_photo',
                'bio',
                'country',
                'region'
            ])
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($artist) {
                return [
                    'id' => $artist->id,
                    'name' => $artist->stage_name,
                    'profilePhoto' => $artist->profile_photo ? asset('storage/' . $artist->profile_photo) : null,
                    'location' => $artist->region . ', ' . $artist->country,
                    'bio' => $artist->bio,
                ];
            });

        return Inertia::render('Welcome', [
            'popularArtists' => $popularArtists
        ]);
    }
} 