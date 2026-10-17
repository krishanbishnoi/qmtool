<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

ini_set('memory_limit', '-1');
use App\AuditLogPurge;
use App\AuditLog;
use App\Audit;
use Carbon\Carbon;
use App\LoggedUser;
use App\AuditorPerformenceReport;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class deleteAuditLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:audit_log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'for delete audit log data of last 7 days';

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
        
       /*  $report_data = AuditLog::where('updated_at', '<', Carbon::now()->subDays(7))->get();

        $auditors = AuditLog::where('updated_at', '<', Carbon::now()->subDays(7))->distinct()->pluck('auditor_id');

        
        foreach($auditors as $key => $value){

            $audits = Audit::where('created_at', '<=', Carbon::now()->subDays(7))->where('created_at', '>=', Carbon::now()->subDays(14))->where('audited_by_id',$value)->count('id');
            

            
            $data = AuditLog::where('updated_at', '<', Carbon::now()->subDays(7))->where('auditor_id',$value)->sum('total_minuts');
            
            $performance_report = new AuditorPerformenceReport;
            $performance_report->auditor_id = $value;
            $performance_report->total_spend_time = $data;
            $performance_report->audit_done = $audits;
            $performance_report->start_date = Carbon::now()->subDays(14);
            $performance_report->end_date = Carbon::now()->subDays(7);
            $performance_report->save();
            
        }
        $purge = AuditLog::where('updated_at', '<', Carbon::now()->subDays(7))->delete();
        $no_records = new AuditLogPurge;
        $no_records->no_of_record = $purge; 
        $no_records->save(); */


        $yesterday = Carbon::now()->subDays(7);
        $today = date('Y-m-d');
        
        $auditors = AuditLog::where('updated_at', '>=', $yesterday)->where('updated_at', '<', $today)->distinct()->pluck('auditor_id');

        foreach($auditors as $key => $value){
            $audits = Audit::where('created_at', '>=', $yesterday)->where('created_at', '<', $today)->where('audited_by_id',$value)->count('id');
            $data = AuditLog::where('updated_at', '>=', $yesterday)->where('updated_at', '<', $today)->where('auditor_id',$value)->sum('total_minuts');
            $performance_report = new AuditorPerformenceReport;
            $performance_report->auditor_id = $value;
            $performance_report->total_spend_time = $data;
            if($audits >= 1){
                $performance_report->present_days = 1;
            }
            
            $performance_report->audit_done = $audits;
            $performance_report->start_date = $yesterday;
            $performance_report->end_date = $yesterday;
            $performance_report->save();
            
        }
        $purge = AuditLog::where('updated_at', '>=', $yesterday)->where('updated_at', '<', $today)->delete();
        $no_records = new AuditLogPurge;
        $no_records->no_of_record = $purge; 
        $no_records->save();
    }
}
