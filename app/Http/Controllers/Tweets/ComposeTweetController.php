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
        $tweet = $id ? Tweet::findOrFail($id) : Tweet::create();

        return Inertia::render('Tweets/ComposeTweet', [
            'tweets' => $tweet->getTweetAndReplies(),
        ]);
    }

    public function store(TweetsRequest $request)
    {
        foreach ($request->validated()['tweets'] as $tweet) {
            Auth::user()->tweets()->create($tweet);
        }

        return redirect()->route('compose.index');
    }
}
