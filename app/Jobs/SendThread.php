<?php

namespace App\Jobs;

use App\Models\Thread;
use App\Services\Twitter\TwitterApi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendThread implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $thread;

    /**
     * Create a new job instance.
     */
    public function __construct(Thread $thread)
    {
        $this->thread = $thread;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        TwitterApi::thread($this->thread);
    }
}
