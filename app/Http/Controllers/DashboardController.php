<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with real-time statistics.
     */
    public function index(Request $request)
    {
        // Get total user count
        $userCount = User::count();

        // Get active sessions count
        $activeSessions = DB::table('sessions')->whereNotNull('user_id')->count();

        // For revenue, you would typically connect to your payment provider
        // This is a placeholder - replace with actual revenue calculation
        $revenue = 0; // Placeholder value

        return Inertia::render('Dashboard', [
            'userCount' => $userCount,
            'revenue' => $revenue,
            'activeSessions' => $activeSessions,
        ]);
    }
}
