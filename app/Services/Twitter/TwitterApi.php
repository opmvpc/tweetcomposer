<?php

namespace App\Services\Twitter;

use Coderjerk\BirdElephant\BirdElephant;
use Illuminate\Support\Facades\Auth;

class TwitterApi
{
    public function __construct()
    {
        $this->setupAuth();
    }

    public function setupAuth()
    {
        $user = Auth::user();
        $token = $user->twitter_token;

        // your credentials, should be passed in via $_ENV or similar, don't hardcode.
        $credentials = [
            'bearer_token' => config('services.twitter.bearer_token'),
            'consumer_key' => config('services.twitter.client_id'),
            'consumer_secret' => config('services.twitter.client_secret'),
            // if using oAuth 2.0 with PKCE
            'auth_token' => $token, // OAuth 2.0 auth token
            // if using oAuth 1.0a
            'token_identifier' => $user->twitter_id,
            'token_secret' => $token,
        ];

        // instantiate the object
        $twitter = new BirdElephant($credentials);

        $image = $twitter->tweets()->upload(storage_path('app/public/users/1/tweets/1228/NMPwa0IPVQGWoZ4KHln91LFFxvvVTg0OTamoNEya.jpg'));

        // pass the returned media id to a media object as an array
        $media = (new \Coderjerk\BirdElephant\Compose\Media())->mediaIds(
            [
                $image->media_id_string,
            ]
        );

        // //compose the tweet and pass along the media object
        $tweet = (new \Coderjerk\BirdElephant\Compose\Tweet())->text('Hello')
            ->media($media)
        ;

        $twitter->tweets()->tweet($tweet);

        // get a user's followers using the handy helper methods
        // $followers = $twitter->user('A_imaginarium')->followers();
        // dd($followers);

        // pass your query params to the methods directly
        // $following = $twitter->user('coderjerk')->following([
        //     'max_results' => 20,
        //     'user.fields' => 'profile_image_url',
        // ]);

        // tweet something
        // $tweet = (new \Coderjerk\BirdElephant\Compose\Tweet())->text('Hello');

        // $twitter->tweets()->tweet($tweet);
    }
}
