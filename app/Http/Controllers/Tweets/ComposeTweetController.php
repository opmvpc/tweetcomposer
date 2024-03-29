<?php

namespace App\Http\Controllers\Tweets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tweets\TweetsRequest;
use App\Http\Requests\Tweets\UploadMediaRequest;
use App\Models\Medium;
use App\Models\Thread;
use App\Models\Tweet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ComposeTweetController extends Controller
{
    public function index(?int $threadId = null)
    {
        if (null !== $threadId) {
            $thread = Thread::findOrFail($threadId);
            $this->authorize('update', $thread);
        } else {
            $this->authorize('create', Thread::class);

            $thread = Auth::user()->threads()->create(['title' => 'Untitled']);

            $thread->twitter_profile_id = Auth::user()->twitter_profile_id;
            $thread->save();
            $thread->tweets()->create();

            return redirect()->route('compose.index', ['threadId' => $thread->id]);
        }

        return Inertia::render('Tweets/ComposeTweet', [
            'thread' => $thread->only(
                'id',
                'status',
                'post_as_thread',
                'is_posted',
                'scheduled_at',
            ),
            'tweets' => $thread->getTweetAndReplies(),
            'profiles' => Auth::user()->twitterProfiles,
            'selectedProfileId' => $thread->twitter_profile_id,
        ]);
    }

    public function update(TweetsRequest $request, int $threadId)
    {
        $thread = Thread::findOrFail($threadId);
        $this->authorize('update', $thread);

        $thread->title = Str::limit($request->validated()['tweets'][0]['content'], 30) ?? 'Untitled';
        $thread->save();

        foreach ($request->validated()['tweets'] as $tweet) {
            $current = Tweet::findOrFail($tweet['id']);
            $current->update($tweet);
        }

        return redirect()->route('compose.index', ['threadId' => $threadId]);
    }

    public function addReply(int $threadId)
    {
        $thread = Thread::findOrFail($threadId);
        $this->authorize('update', $thread);

        $tweet = $thread->tweets()->create();

        return response()->json([
            'id' => $tweet->id,
        ]);
    }

    public function uploadMedia(UploadMediaRequest $request, int $tweetId)
    {
        $tweet = Tweet::findOrFail($tweetId);
        $this->authorize('update', $tweet->thread);

        foreach ($request->validated()['files'] as $file) {
            $dir = sprintf('public/users/%d/tweets/%d', Auth::id(), $tweet->id);
            $path = $file->store($dir);
            $tweet->media()->create([
                'path' => $path,
                'url' => Storage::url($path),
            ]);
        }
    }

    public function destroyTweet(int $tweetId)
    {
        $tweet = Tweet::findOrFail($tweetId);
        $this->authorize('delete', $tweet->thread);

        $tweet->delete();
    }

    public function destroyMedia(int $mediaId)
    {
        $media = Medium::findOrFail($mediaId);
        $this->authorize('delete', $media->tweet->thread);

        $media->delete();
    }
}
