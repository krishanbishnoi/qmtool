<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Audit;
use App\RawData;

class CheckIncompletedAudit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'incomplete:audit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        
        $incomplete_audit =  DB::select("
        select a.* FROM audits a where a.id not in (select r.audit_id from audit_results r
         where r.audit_id = a.id) and a.audit_date > '2024-03-06'");
       
        $incomplete_raw_data =  DB::select("SELECT * FROM audits where raw_data_id not in (select id from raw_data) and audit_date > '2024-03-06';");
       
        foreach($incomplete_audit as $data){
           
            $change_status = RawData::find($data->raw_data_id);
            if($change_status){
            $change_status->status = 0;
            $change_status->save();
            }
            // $data = Audit::where('id',$data->id)->delete();
            

        }

        foreach($incomplete_raw_data as $value){
           
            $change = Audit::find($value->id);
            if($change){
            $change->deleted = 1;
            $change->save();
            }
        }
        
        
    }
}
