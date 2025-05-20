<?php

namespace App\Http\Controllers\Artist\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();
        $user->load('artist'); // Eager load the artist relationship

        $artist = $user->artist;
        if ($artist) {
            $artist = $artist->toArray();
            if (isset($artist['profile_photo'])) {
                $artist['profile_photo'] = asset('storage/' . $artist['profile_photo']);
            }
            if (isset($artist['cover_photo'])) {
                $artist['cover_photo'] = asset('storage/' . $artist['cover_photo']);
            }
        }

        return Inertia::render('artist/settings/Profile', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
            'artist' => $artist,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        Log::info('Validated data:', $validated);

        // Update user data - only if the fields are present
        $user = $request->user();
        $userData = array_intersect_key([
            'name' => $validated['name'] ?? null,
            'email' => $validated['email'] ?? null,
        ], array_filter($validated, fn($key) => in_array($key, ['name', 'email']), ARRAY_FILTER_USE_KEY));

        Log::info('User data to update:', $userData);

        if (!empty($userData)) {
            $user->fill($userData);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();
            Log::info('User updated successfully');
        }

        // Update or create artist data
        $artist = $user->artist;
        Log::info('Current artist data:', ['artist' => $artist]);

        // Handle profile photo upload
        if ($request->hasFile('profilePhoto')) {
            // Delete old profile photo if it exists
            if ($artist && $artist->profile_photo) {
                Storage::disk('public')->delete($artist->profile_photo);
            }
            $path = $request->file('profilePhoto')->store('profile-photos', 'public');
            $validated['profile_photo'] = $path;
            Log::info('Profile photo uploaded:', ['path' => $path]);
        }

        // Handle cover photo upload
        if ($request->hasFile('coverPhoto')) {
            Log::info('Cover photo file details:', [
                'name' => $request->file('coverPhoto')->getClientOriginalName(),
                'size' => $request->file('coverPhoto')->getSize(),
                'mime' => $request->file('coverPhoto')->getMimeType()
            ]);
            
            // Delete old cover photo if it exists
            if ($artist && $artist->cover_photo) {
                Storage::disk('public')->delete($artist->cover_photo);
                Log::info('Deleted old cover photo:', ['path' => $artist->cover_photo]);
            }
            
            $path = $request->file('coverPhoto')->store('cover-photos', 'public');
            $validated['cover_photo'] = $path;
            Log::info('Cover photo uploaded:', ['path' => $path]);
        } else {
            Log::info('No cover photo file found in request');
            // Dump all files in the request for debugging
            if ($request->allFiles()) {
                Log::info('All files in request:', ['files' => array_keys($request->allFiles())]);
            }
        }

        // Convert arrays to JSON strings if they exist
        if (isset($validated['socialMediaLinks'])) {
            $validated['social_media_links'] = json_encode($validated['socialMediaLinks']);
            Log::info('Social media links encoded:', ['links' => $validated['social_media_links']]);
        }
        if (isset($validated['musicLinks'])) {
            $validated['music_links'] = json_encode($validated['musicLinks']);
            Log::info('Music links encoded:', ['links' => $validated['music_links']]);
        }

        // Map form fields to database fields - only include fields that are present
        $artistData = array_filter([
            'stage_name' => $validated['stageName'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'phone_number' => $validated['phoneNumber'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'nationality' => $validated['nationality'] ?? null,
            'country' => $validated['country'] ?? null,
            'region' => $validated['region'] ?? null,
            'address' => $validated['address'] ?? null,
            'social_media_links' => $validated['social_media_links'] ?? null,
            'music_links' => $validated['music_links'] ?? null,
            'profile_photo' => $validated['profile_photo'] ?? null,
            'cover_photo' => $validated['cover_photo'] ?? null,
        ], fn($value) => !is_null($value));

        Log::info('Artist data to update:', $artistData);

        try {
            if ($artist && !empty($artistData)) {
                $artist->update($artistData);
                Log::info('Artist updated successfully');
            } elseif (!empty($artistData)) {
                $user->artist()->create($artistData);
                Log::info('New artist created successfully');
            }
        } catch (\Exception $e) {
            Log::error('Error updating artist:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to update profile. Please try again.']);
        }

        return to_route('artist.profile.edit');
    }

    /**
     * Delete the user's profile.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();
        $user->artist()->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
