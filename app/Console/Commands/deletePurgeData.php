<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
ini_set('memory_limit', '-1');
use App\RawData;
use App\DataPurge;
use Carbon\Carbon;
use App\Audit;
use DB;
use Illuminate\Database\Eloquent\Builder;

class deletePurgeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:purge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'for delete purge data of last 7 days';

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
        /* echo Carbon::now()->subDays(25);
        die; */
        // $date = Carbon::now()->subDays(7);
        // echo "$date";
        // $records = count(RawData::where('dump_date', '>', Carbon::now()->subDays(7))->get());
        $audits = Audit::where('audit_date', '<', Carbon::now()->subDays(60))->get();
        
        foreach($audits as $value){
            $raw_data1 = RawData::where('id',$value->raw_data_id)->first();
            
            // if(isset($raw_data1['status'])){
                if($raw_data1['status'] != 1){
                    DB::table('raw_data')
                    ->where('id', $value->raw_data_id)
                    ->where('status', 0)
                    ->update(['status' => 1]);
                    
                }
            // }
             else {


                // $update_delete = Audit::where('id',$value->id)->update(['deleted' => 1]);
            }
            
            
        }
        
         // set status 1 for find audits in audit table and stats is 0 in rawdata ends
        
        $purge = RawData::where('dump_date', '<', Carbon::now()->subDays(60))->where('status',0)->delete();
        $no_records = new DataPurge;
        $no_records->no_of_record = $purge; 
        $no_records->save();

    }
}
