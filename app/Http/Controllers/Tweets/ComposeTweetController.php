<?php

namespace App\Http\Controllers\Tweets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tweets\TweetsRequest;
use App\Models\Tweet;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ComposeTweetController extends Controller
{
    public function index(?int $id = null)
    {
        if (null !== $id) {
            $tweet = Tweet::findOrFail($id);
        } else {
            $tweet = Auth::user()->tweets()->create();
        }

        return Inertia::render('Tweets/ComposeTweet', [
            'tweets' => $tweet->getTweetAndReplies(),
        ]);
    }

    public function update(TweetsRequest $request)
    {
        foreach ($request->validated()['tweets'] as $tweet) {
            $current = Tweet::findOrFail($tweet['id']);
            $current->update($tweet);
        }

        $firstTweet = $request->validated()['tweets'][0];

        return redirect()->route('compose.index', ['id' => $firstTweet['id']]);
    }

    public function addReply(int $id)
    {
        $tweet = Tweet::findOrFail($id);

        $child = $tweet->child()->create();
        $child->user_id = Auth::id();
        $child->save();

        return response()->json([
            'id' => $child->id,
        ]);
    }
}
