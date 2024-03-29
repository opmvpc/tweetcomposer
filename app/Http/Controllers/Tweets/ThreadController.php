<?php

namespace App\Http\Controllers\Tweets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tweets\UpdateThreadRequest;
use App\Http\Requests\Tweets\UpdateThreadStatusRequest;
use App\Jobs\SendThread;
use App\Models\Thread;
use Carbon\Carbon;
use Inertia\Inertia;

class ThreadController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Thread::class);

        return Inertia::render('Tweets/Tweets', [
            'drafts' => Thread::getUserDrafts(),
            'scheduled' => Thread::getUserScheduled(),
            'posted' => Thread::getUserPosted(),
        ]);
    }

    public function update(int $threadId, UpdateThreadRequest $request)
    {
        $thread = Thread::findOrFail($threadId);
        $this->authorize('update', $thread);

        $thread->twitter_profile_id = $request->validated()['selectedProfileId'];
        $thread->post_as_thread = $request->validated()['postAsThread'];
        if (null !== $request->validated()['scheduledAtDate'] && null !== $request->validated()['scheduledAtTime']) {
            $thread->scheduled_at = new Carbon(
                sprintf(
                    '%s %s',
                    $request->validated()['scheduledAtDate'],
                    $request->validated()['scheduledAtTime']
                )
            );
        } else {
            $thread->scheduled_at = null;
        }

        $thread->save();
    }

    public function updateStatus(int $threadId, UpdateThreadStatusRequest $request)
    {
        $thread = Thread::findOrFail($threadId);
        $this->authorize('update', $thread);

        $status = $request->validated()['status'];

        if ('draft' === $status) {
            $thread->scheduled_at = null;
        } elseif ('scheduled' === $status) {
            $thread->scheduled_at = now()->addDay();
        } elseif ('posted' === $status) {
            $thread->scheduled_at = now();
            $thread->is_posted = true;
            SendThread::dispatch($thread);
        }

        $thread->save();
    }

    public function destroy(int $threadId)
    {
        $thread = Thread::findOrFail($threadId);
        $this->authorize('delete', $thread);

        $thread->delete();

        return redirect()->route('threads.index')->banner('Thread deleted successfully!');
    }
}
