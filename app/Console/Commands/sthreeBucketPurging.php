<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\RawData;
use App\DataPurge;
use Carbon\Carbon;
use App\Audit;
use DB;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Facades\Storage;

class sthreeBucketPurging extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'callfile:purging';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'for delete purge data of last 10 days';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $date = Carbon::now()->subDays(7);
        // echo "$date";
        // $records = count(RawData::where('dump_date', '>', Carbon::now()->subDays(7))->get());
        // https://qmtool.s3.ap-south-1.amazonaws.com/qa_views_call_files/RiqS1CJlCzn29TsLlJw8zpGZ1UuuBmaQ8DZZ7eUz.wav


        Storage::disk('s3')->delete('qa_views_call_files/'. "RiqS1CJlCzn29TsLlJw8zpGZ1UuuBmaQ8DZZ7eUz.wav");

    }
}
