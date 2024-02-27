<?php

namespace App\Jobs;

use App\Models\QueuedExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class UpdateExportStatusJob implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    public $export;
    public $status = 2;
    
    public function __construct(QueuedExport $export, $status = 2)
    {
        $this->export = $export;
        $this->status = $status;
    }

    public function handle()
    {
        $this->export->update(['status' => $this->status]);
    }
}