<?php

namespace App\Http\Controllers\Venue\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredVenueController extends Controller
{
    /**
     * Display the venue registration view.
     */
    public function create(): Response
    {
        return Inertia::render('venue/auth/Register');
    }

    /**
     * Handle an incoming venue registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign the user role first
        $user->assignRole('user');
        // Then assign the venue role
        $user->assignRole('venue');

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('venue.dashboard'));
    }
}
