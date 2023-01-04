<?php

namespace App\Http\Controllers;

use App\Models\TwitterProfile;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render(
            'Dashboard',
            [
                'profiles' => TwitterProfile::getUserProfiles(),
                'selectedProfileId' => Auth::user()->twitter_profile_id,
            ]
        );
    }
}
