<?php

namespace App\Http\Controllers\Artist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Artist;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with artist statistics.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $artist = $user->artist;

        // Helper function to ensure we have an array
        $ensureArray = function ($value) {
            if (is_string($value)) {
                return json_decode($value, true) ?? [];
            }
            return is_array($value) ? $value : [];
        };

        // Get artist data using actual database fields
        $artistData = [
            'name' => $user->name,
            'stageName' => $artist->stage_name,
            'profilePhoto' => $artist->profile_photo ? asset('storage/' . $artist->profile_photo) : null,
            'bio' => $artist->bio,
            'country' => $artist->country,
            'region' => $artist->region,
            'address' => $artist->address,
            'nationality' => $artist->nationality,
            'gender' => $artist->gender,
            'phoneNumber' => $artist->phone_number,
            'socialMediaLinks' => $ensureArray($artist->social_media_links),
            'musicLinks' => $ensureArray($artist->music_links),
            // These will be implemented when we add the relationships
            'totalTracks' => 0,
            'totalFollowers' => 0,
            'totalLikes' => 0,
        ];

        // Get performance stats
        // For now using placeholder data, will be implemented with actual tracking
        $stats = [
            'monthlyListeners' => 0,
            'totalStreams' => 0,
            'engagement' => 0,
        ];

        // Format social media links for display
        $socialMediaActivity = collect($artistData['socialMediaLinks'])->map(function ($link) {
            return [
                'id' => uniqid(),
                'type' => 'share',
                'message' => "Profile available on " . ucfirst($link['platform']),
                'timestamp' => 'Active',
                'url' => $link['url']
            ];
        })->values()->all();

        // Format music links for display
        $musicLinksActivity = collect($artistData['musicLinks'])->map(function ($link) {
            return [
                'id' => uniqid(),
                'type' => 'music',
                'message' => "Music available on " . ucfirst($link['platform']),
                'timestamp' => 'Active',
                'url' => $link['url']
            ];
        })->values()->all();

        // Combine both types of activities
        $recentActivity = array_merge($socialMediaActivity, $musicLinksActivity);

        // Format music links as tracks
        $topTracks = collect($artistData['musicLinks'])->map(function ($link, $index) {
            return [
                'id' => $index + 1,
                'title' => "Track on " . ucfirst($link['platform']),
                'streams' => 0, // Will be implemented with actual tracking
                'duration' => '--:--', // Will be implemented with actual track data
                'thumbnail' => '/placeholder-track.jpg', // Will be implemented with actual track data
                'platform' => $link['platform'],
                'url' => $link['url']
            ];
        })->values()->all();

        // For now, using placeholder data for events
        // This will be implemented when we add the events feature
        $upcomingEvents = [
            [
                'id' => 1,
                'title' => 'No Events Scheduled',
                'date' => now()->addDays(30)->format('Y-m-d'),
                'venue' => 'TBD',
                'ticketsSold' => 0,
                'totalCapacity' => 0,
            ]
        ];

        return Inertia::render('artist/dashboard', [
            'artist' => $artistData,
            'stats' => $stats,
            'recentActivity' => $recentActivity,
            'upcomingEvents' => $upcomingEvents,
            'topTracks' => $topTracks,
        ]);
    }
}
