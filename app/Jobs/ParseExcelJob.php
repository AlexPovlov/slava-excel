<?php

namespace App\Jobs;

use App\Services\ExcelParseService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParseExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file_name;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file_name)
    {
        $this->file_name = $file_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ExcelParseService $excel_service)
    {
        $excel_service->saveRows($this->file_name);
    }
}
