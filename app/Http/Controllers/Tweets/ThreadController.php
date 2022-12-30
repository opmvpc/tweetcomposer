<?php

namespace App\Http\Controllers\Tweets;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Models\Tweet;
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
}
