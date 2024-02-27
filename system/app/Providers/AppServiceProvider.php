<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobProcessed;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('path.public', function() {
            return realpath(base_path().'/../public');
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // \Log::info(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
        Paginator::useBootstrapFive();

        // runs the queue/scheduler more than once a minute if the job is done
        Queue::after(function (JobProcessed $event) {
            Artisan::call('schedule:run');
        });
    }
}
