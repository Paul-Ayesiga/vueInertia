<?php

namespace App\Http\Controllers\Artist\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Artist;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredArtistController extends Controller
{
    /**
     * Display the artist registration view.
     */
    public function create(): Response
    {
        return Inertia::render('artist/auth/Register');
    }

    /**
     * Handle an incoming artist registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // User table fields
            'fullName' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            // Artist table fields
            'phoneNumber' => 'required|string|min:10',
            'stageName' => 'required|string|min:2',
            'gender' => 'required|in:male,female,other',
            'nationality' => 'required|string|min:2',
            'address' => 'required|string|min:5',
            'country' => 'required|string|min:2',
            'region' => 'required|string|min:2',
            'nin' => 'required|string|min:14|unique:artists',
            'nationalIdPhotos.*' => 'required|image|max:2048', // Max 2MB per image
            'bio' => 'required|string|min:50',
            'profilePhoto' => 'required|image|max:2048',
            'socialMediaLinks' => 'required|array|min:1',
            'socialMediaLinks.*.platform' => 'required|string',
            'socialMediaLinks.*.url' => 'required|url',
            'musicLinks' => 'required|array|min:1',
            'musicLinks.*.platform' => 'required|string',
            'musicLinks.*.url' => 'required|url',
            'termsAccepted' => 'required|accepted',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->fullName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Handle file uploads
        $nationalIdPhotos = [];
        foreach ($request->file('nationalIdPhotos') as $photo) {
            $path = $photo->store('national-ids', 'public');
            $nationalIdPhotos[] = $path;
        }

        $profilePhotoPath = $request->file('profilePhoto')->store('profile-photos', 'public');

        // Create artist profile
        $artist = Artist::create([
            'user_id' => $user->id,
            'phone_number' => $request->phoneNumber,
            'stage_name' => $request->stageName,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'address' => $request->address,
            'country' => $request->country,
            'region' => $request->region,
            'nin' => $request->nin,
            'national_id_photos' => $nationalIdPhotos,
            'bio' => $request->bio,
            'profile_photo' => $profilePhotoPath,
            'social_media_links' => $request->socialMediaLinks,
            'music_links' => $request->musicLinks,
        ]);

        // Assign roles
        $user->assignRole('user');
        $user->assignRole('artist');

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('artist.dashboard'));
    }
}
