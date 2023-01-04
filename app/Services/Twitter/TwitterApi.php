<?php

namespace App\Services\Twitter;

use App\Models\Thread;
use Coderjerk\BirdElephant\BirdElephant;
use Coderjerk\BirdElephant\Compose\Media;
use Coderjerk\BirdElephant\Compose\Tweet;

class TwitterApi
{
    public static function thread(Thread $thread)
    {
        $credentials = static::setupAuth($thread);
        $api = new BirdElephant($credentials);
        static::sendThread($api, $thread);
    }

    protected static function setupAuth(Thread $thread): array
    {
        $token_identifier = $thread->twitterProfile->token;
        $token_secret = $thread->twitterProfile->token_secret;

        return [
            'bearer_token' => config('services.twitter.bearer_token'),
            'consumer_key' => config('services.twitter.client_id'),
            'consumer_secret' => config('services.twitter.client_secret'),
            'token_identifier' => $token_identifier,
            'token_secret' => $token_secret,
        ];
    }

    protected static function sendThread(BirdElephant $api, Thread $thread)
    {
        $thread->tweets()->get()->each(function ($tweet) use ($api) {
            $media = static::uploadMedia($api, $tweet);

            $tweet = (new Tweet())->text($tweet->content)
                ->media($media)
            ;

            $api->tweets()->tweet($tweet);
        });
    }

    protected static function uploadMedia(BirdElephant $api, $tweet): Media
    {
        $mediaIds = [];
        $tweet->media()->get()->each(function ($media) use ($api, &$mediaIds) {
            $imgPath = storage_path('app/'.$media->path);
            $medium = $api->tweets()->upload($imgPath);
            $mediaIds[] = $medium->media_id_string;
        });

        return (new Media())->mediaIds(
            $mediaIds
        );
    }
}
