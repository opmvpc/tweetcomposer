<?php

namespace App\Http\Controllers\Tweets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tweets\UpdateThreadRequest;
use App\Models\Thread;
use Inertia\Inertia;

class ThreadController extends Controller
{
    public function index()
    {
        return Inertia::render('Tweets/Tweets', [
            'drafts' => Thread::getDrafts(),
            'scheduled' => Thread::getScheduled(),
            'posted' => Thread::getPosted(),
        ]);
    }

    public function update(int $threadId, UpdateThreadRequest $request)
    {
        $thread = Thread::findOrFail($threadId);
        $thread->twitter_profile_id = $request->validated()['selectedProfileId'];
        $thread->save();
    }
}
