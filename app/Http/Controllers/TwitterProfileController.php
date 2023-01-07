<?php

namespace App\Http\Controllers;

use App\Http\Requests\TwitterProfile\SelectProfileRequest;
use App\Models\TwitterProfile;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class TwitterProfileController extends Controller
{
    public function index()
    {
        return Inertia::render('TwitterProfile/Index', [
            'profiles' => TwitterProfile::getUserProfiles(),
        ]);
    }

    public function addTwitterAccount()
    {
        return Socialite::driver('twitter')->redirect();
    }

    public function store()
    {
        $twitterUser = Socialite::driver('twitter')->user();

        $user = Auth::user();
        $profile = $user->twitterProfiles()->updateOrCreate(
            [
                'twitter_id' => $twitterUser->id,
            ],
            [
                'nickname' => $twitterUser->nickname,
                'avatar' => $twitterUser->avatar,
                'token' => $twitterUser->token,
                'token_secret' => $twitterUser->tokenSecret,
            ]
        );

        if (null === $user->twitter_profile_id) {
            $user->twitter_profile_id = $profile->id;
            $user->save();
        }

        return redirect()->route('twitter-profile.index')->banner('Twitter profile added successfully!');
    }

    public function updateSelectedProfile(SelectProfileRequest $request)
    {
        $user = Auth::user();
        $user->twitter_profile_id = $request->validated()['selectedProfileId'];
        $user->save();

        $request->session()->flash('flash.banner', 'Your default Twitter profile has been successfully changed!');
        $request->session()->flash('flash.bannerStyle', 'success');
    }

    public function destroy(int $twitterProfileId)
    {
        $twitterProfile = TwitterProfile::findOrFail($twitterProfileId);
        $twitterProfile->delete();

        $user = Auth::user();
        if (null === $user->selectedTwitterProfile) {
            // change the default profile to the first one
            $firstProfile = $user->twitterProfiles()->first();
            if (null !== $firstProfile) {
                $user->twitter_profile_id = $firstProfile->id;
                $user->save();
            }
        }

        return redirect()->back()->banner('Twitter profile deleted successfully!');
    }
}
