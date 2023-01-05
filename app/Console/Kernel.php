<?php

namespace App\Console;

use App\Jobs\SendThread;
use App\Models\Thread;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            Thread::posted()->where('is_posted', false)->get()->each(function ($thread) {
                SendThread::dispatch($thread);
                $thread->is_posted = true;
                $thread->save();
            });
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
