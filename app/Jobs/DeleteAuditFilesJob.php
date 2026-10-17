<?php

namespace App\Jobs;

use App\Audit;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeleteAuditFilesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $startDate;
    public $endDate;

    /**
     * Create a new job instance.
     */
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $baseUrl = "https://qmtool.s3.ap-south-1.amazonaws.com/";
        Log::info("🟢 Starting DeleteAuditFilesJob", [
            'start_date' => $this->startDate,
            'end_date'   => $this->endDate
        ]);
        $totalDeleted = 0;

        Audit::whereNotNull('good_bad_call_file')
        ->whereBetween('audit_date', [$this->startDate, $this->endDate])
        ->chunk(500, function ($audits) use ($baseUrl, &$totalDeleted) {
            $filesToDelete = [];

            foreach ($audits as $audit) {
                $filePath = Str::after($audit->good_bad_call_file, $baseUrl);
                if ($filePath) {
                    $filesToDelete[] = $filePath;
                }
            }

            // Delete in smaller chunks of 100 to avoid S3 overload
            foreach (array_chunk($filesToDelete, 100) as $batch) {
                Storage::delete($batch);
                $count = count($batch);
                $totalDeleted += $count;

                Log::info("Deleted batch of files", [
                    'count' => $count,
                ]);
            }
        });

    Log::info("✅ DeleteAuditFilesJob finished", [
        'total_deleted' => $totalDeleted,
        'start_date'    => $this->startDate,
        'end_date'      => $this->endDate
    ]);
    }
}
