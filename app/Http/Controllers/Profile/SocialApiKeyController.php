<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SocialApiKeyController extends Controller
{
    public function index()
    {
        return Inertia::render('Profile/SocialApiKey', [
            'user' => Auth::user(),
        ]);
    }

    public function update()
    {
        $user = Auth::user();

        $user->update([
            'twitter_api_key' => request('twitter_api_key'),
            'instagram_api_key' => request('instagram_api_key'),
            'gpt_api_key' => request('gpt_api_key'),
        ]);

        return redirect()->route('social-api-key.index');
    }
}
