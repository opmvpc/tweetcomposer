<?php

namespace App\Http\Controllers\Tweets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tweets\TweetsRequest;
use App\Models\Thread;
use App\Models\Tweet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ComposeTweetController extends Controller
{
    public function index(?int $threadId = null)
    {
        if (null !== $threadId) {
            $thread = Thread::findOrFail($threadId);
        } else {
            $thread = Auth::user()->threads()->create(['title' => 'Untitled']);
            $thread->tweets()->create();
        }

        return Inertia::render('Tweets/ComposeTweet', [
            'thread' => $thread,
            'tweets' => $thread->getTweetAndReplies(),
        ]);
    }

    public function update(TweetsRequest $request, int $threadId)
    {
        foreach ($request->validated()['tweets'] as $tweet) {
            $current = Tweet::findOrFail($tweet['id']);
            $current->update($tweet);
        }

        $thread = Thread::findOrFail($threadId);
        $thread->title = Str::limit($request->validated()['tweets'][0]['content'], 30) ?? 'Untitled';
        $thread->save();

        return redirect()->route('compose.index', ['threadId' => $threadId]);
    }

    public function addReply(int $threadId)
    {
        $thread = Thread::findOrFail($threadId);

        $tweet = $thread->tweets()->create();

        return response()->json([
            'id' => $tweet->id,
        ]);
    }
}
