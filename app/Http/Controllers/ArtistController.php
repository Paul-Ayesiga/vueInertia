<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Booking;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ArtistController extends Controller
{
    public function show(Artist $artist)
    {
        $artist->load(['user', 'bookings' => function ($query) {
            $query->where('status', 'confirmed')
                  ->where('event_date', '>=', now())
                  ->orderBy('event_date', 'asc')
                  ->take(5);
        }]);

        $upcomingEvents = $artist->bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'title' => $booking->event_name,
                'date' => $booking->event_date,
                'venue' => $booking->venue_name,
                'location' => $booking->venue_location,
                'status' => $booking->status,
            ];
        });

        $isFollowing = auth()->check() ? $artist->followers()->where('user_id', auth()->id())->exists() : false;

        return Inertia::render('artist/Show', [
            'artist' => [
                'id' => $artist->id,
                'name' => $artist->stage_name,
                'profilePhoto' => $artist->profile_photo ? asset('storage/' . $artist->profile_photo) : null,
                'coverPhoto' => $artist->cover_photo ? asset('storage/' . $artist->cover_photo) : null,
                'bio' => $artist->bio,
                'location' => $artist->country . ', ' . $artist->region,
                'genres' => $artist->genres,
                'socialLinks' => [
                    'spotify' => $artist->spotify_link,
                    'soundcloud' => $artist->soundcloud_link,
                    'youtube' => $artist->youtube_link,
                    'instagram' => $artist->instagram_link,
                    'twitter' => $artist->twitter_link,
                ],
                'stats' => [
                    'followers' => $artist->followers()->count(),
                    'likes' => $artist->likes_count,
                ],
                'isFollowing' => $isFollowing,
            ],
            'upcomingEvents' => $upcomingEvents,
        ]);
    }

    public function toggleFollow(Artist $artist)
    {
        if (!auth()->check()) {
            return response()->json([
                'message' => 'Please login to follow artists'
            ], 401);
        }

        $user = auth()->user();
        $isFollowing = $artist->followers()->where('user_id', $user->id)->exists();

        if ($isFollowing) {
            $artist->followers()->detach($user->id);
        } else {
            $artist->followers()->attach($user->id);
        }

        // Refresh the artist model to get updated counts
        $artist->refresh();
        $newFollowerCount = $artist->followers()->count();

        return back()->with([
            'artist' => [
                'id' => $artist->id,
                'name' => $artist->stage_name,
                'profilePhoto' => $artist->profile_photo ? asset('storage/' . $artist->profile_photo) : null,
                'coverPhoto' => $artist->cover_photo ? asset('storage/' . $artist->cover_photo) : null,
                'bio' => $artist->bio,
                'location' => $artist->country . ', ' . $artist->region,
                'genres' => $artist->genres,
                'socialLinks' => [
                    'spotify' => $artist->spotify_link,
                    'soundcloud' => $artist->soundcloud_link,
                    'youtube' => $artist->youtube_link,
                    'instagram' => $artist->instagram_link,
                    'twitter' => $artist->twitter_link,
                ],
                'stats' => [
                    'followers' => $newFollowerCount,
                    'likes' => $artist->likes_count,
                ],
                'isFollowing' => !$isFollowing,
            ],
        ]);
    }
} 