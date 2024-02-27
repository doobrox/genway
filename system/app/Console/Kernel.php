<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('log:text')->everyMinute();

        /*
         *  --once - To force worker to exit after running one job
         *  --tries - The maximum number of times a job may be attempted
         *  --timeout - The maximum number of seconds that jobs can run may be specified
         *  --queue - Name of the queue, must be specified otherwise the job in the named queue will not be executed
         *  - withoutOverlapping() - By default, scheduled tasks will be run even if the previous instance of the task is still running. To prevent this, you may use the withoutOverlapping method
         *  - sendOutputTo() - Send the output to a file for later inspection
         */
        $schedule->command('queue:work --timeout=60 --tries=3 --once')
            ->everyMinute()
            // default for withoutOverlapping is 1440 min (24h)
            ->withoutOverlapping(1)
            ->sendOutputTo(storage_path('logs/queue-jobs.log'));

        $schedule->command('sitemap:generate')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
