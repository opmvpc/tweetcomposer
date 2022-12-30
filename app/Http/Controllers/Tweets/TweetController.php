<?php

namespace App\Http\Controllers\Tweets;

use App\Http\Controllers\Controller;
use App\Models\Tweet;
use Inertia\Inertia;

class TweetController extends Controller
{
    public function index()
    {
        return Inertia::render('Tweets/Tweets', [
            'drafts' => Tweet::getDrafts(),
            'scheduled' => Tweet::getScheduled(),
            'posted' => Tweet::getPosted(),
        ]);
    }
}
