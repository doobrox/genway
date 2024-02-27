<?php
 
namespace App\Console\Commands;
 
use Carbon\Carbon;
use Illuminate\Console\Command;
 
class LogTestCommand extends Command
{
    // The name and signature of the console command.
    protected $signature = 'log:text';
 
    protected $description = 'Log text to check schedule cron';
 
    // Execute the console command.
    public function handle()
    {
        \Log::info('test 1');
    }
}