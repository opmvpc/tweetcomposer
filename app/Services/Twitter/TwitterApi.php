<?php

namespace App\Services\Twitter;

use App\Models\Thread;
use Coderjerk\BirdElephant\BirdElephant;
use Coderjerk\BirdElephant\Compose\Media;
use Coderjerk\BirdElephant\Compose\Reply;
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
        $tweets = $thread->tweets()->get()->filter(function ($tweet) {
            return (null !== $tweet->content && '' !== $tweet->content) || !$tweet->media->isEmpty();
        })->values();

        foreach ($tweets as $index => $tweet) {
            $tweetObj = (new Tweet())->text($tweet->content);

            if (!$tweet->media->isEmpty()) {
                $media = static::uploadMedia($api, $tweet);
                $tweetObj->media($media);
            }

            if ($index > 0) {
                $reply = (new Reply())->inReplyToTweetId($tweets[$index - 1]->twitter_api_id);
                $tweetObj->reply($reply);
            }

            $response = $api->tweets()->tweet($tweetObj);
            $tweet->twitter_api_id = $response->data->id;
            $tweet->save();
        }
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
