<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\DB;
use Illuminate\Mail\Mailable;
use App\Mail\ClientWiseProductivityReport;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Console\Command;

class WelcomeDahboardDateWise extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'welcome_dahshboard:daywise';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store daily data in dashboard report';

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


    public function handle() {



        $audit_cyle_data = Auditcycle::where('client_id',$client_id)->orderby('start_date','desc')->first();

        $users = User::with('roles')->where('parent_client','>',0)->get();
        
        if(Auth::user()->parent_client) {
            $client_id = Auth::user()->parent_client;
            $all_cluster_processes = get_helper_cluster_processes(Auth::user()->id);
            $all_cluster_partners = get_helper_cluster_partners(Auth::user()->id);
            $all_cluster_locations = get_helper_cluster_locations(Auth::user()->id);
           // $all_cluster_lobs = get_helper_cluster_lobs(Auth::user()->id);
        }

        $current_date_time = date('Y-m-d H:i:s');
        $rebuttal_data = [];

        if(Auth::user()->parent_client) { 
            if(Auth::user()->hasRole('qtl')){
                
                $audit_data = Audit::with('raw_data')->where('client_id',$client_id)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->whereIn('partner_id',$all_cluster_partners)
                ->whereIn('process_id',$all_cluster_processes)
                ->get();

                $feedback_shared = Audit::where('client_id',$client_id)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->whereIn('partner_id',$all_cluster_partners)
                ->whereIn('process_id',$all_cluster_processes)
                ->where('feedback_shared_status',1)
                ->count();

            }
            else {
                $audit_data = Audit::with('raw_data')->where('client_id',$client_id)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->whereIn('partner_id',$all_cluster_partners)
                ->where('qc_tat','<',$current_date_time)
                ->whereIn('process_id',$all_cluster_processes)
                ->get();

                $feedback_shared = Audit::where('client_id',$client_id)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->whereIn('partner_id',$all_cluster_partners)
                ->where('qc_tat','<',$current_date_time)
                ->where('feedback_shared_status',1)
                ->whereIn('process_id',$all_cluster_processes)
                ->count();

            }

            $callType=RawData::select(DB::raw("distinct(call_type)"))
            ->where('client_id',$client_id)
            ->whereIn('partner_id',$all_cluster_partners)
            ->whereIn('process_id',$all_cluster_processes)
            ->orderby('id','asc')->get();

        } 

        else {

            $audit_data =Audit::with('raw_data')->where('client_id',$client_id)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)->get();
            $feedback_shared =Audit::where('client_id',$client_id)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->where('feedback_shared_status',1)
            ->count();

            $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->orderby('id','asc')->get();

        } 

        
        if(isset($feedback_shared)){
            $coverage['feedback_shared'] = $feedback_shared;

        }
        $temp_total_rebuttal = 0;
        $temp_accepted_rebuttal = 0;
        $temp_rejected_rebuttal = 0;
        $unique_rebuttal_counts = 0;

        if(Auth::user()->parent_client) { 


            $temp_total_rebuttal = Rebuttal::whereHas('audit_data', function (Builder $query) 
            use ($client_id,$all_cluster_partners, $all_cluster_processes, $month_first_data, $today) {
                $query->where('client_id',$client_id)
                ->whereIn('partner_id',$all_cluster_partners)
                ->whereIn('process_id',$all_cluster_processes)
                ->where('rebuttal_status','>',0)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today);
            })->count();

            $temp_accepted_rebuttal = Rebuttal::whereHas('audit_data', function (Builder $query) 
            use ($client_id,$all_cluster_partners, $all_cluster_processes, $month_first_data, $today) {
                $query->where('client_id',$client_id)
                ->whereIn('partner_id',$all_cluster_partners)
                ->whereIn('process_id',$all_cluster_processes)
                ->where('rebuttal_status','>',0)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today);
            })->where('status','=', 1)->count();

            $temp_rejected_rebuttal = Rebuttal::whereHas('audit_data', function (Builder $query) 
            use ($client_id,$all_cluster_partners, $all_cluster_processes, $month_first_data, $today) {
                $query->where('client_id',$client_id)
                ->whereIn('partner_id',$all_cluster_partners)
                ->whereIn('process_id',$all_cluster_processes)
                ->where('rebuttal_status','>',0)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today);
            })->where('status','=', 2)->count();

            $unique_rebuttal_counts = Rebuttal::whereHas('audit_data', function (Builder $query) 
            use ($client_id,$all_cluster_partners, $all_cluster_processes, $month_first_data, $today) {
                $query->where('client_id',$client_id)
                ->whereIn('partner_id',$all_cluster_partners)
                ->whereIn('process_id',$all_cluster_processes)
                ->where('rebuttal_status','>',0)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today);
            })->distinct('audit_id')->count('audit_id');

            
            
            
            /*      $temp_rebuttal_data = Audit::where('client_id',$client_id)

                    ->whereIn('partner_id',$all_cluster_partners)

                    ->whereIn('process_id',$all_cluster_processes)

                    ->where('rebuttal_status','>',0)

                    ->whereDate('audit_date','>=',$month_first_data)

                    ->whereDate('audit_date','<=',$today)

                    ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])

                    ->get(); */

            

        } 
        else {

            /* $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('rebuttal_status','>',0)

            ->whereDate('audit_date','>=',$month_first_data)

            ->whereDate('audit_date','<=',$today)

            ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
            */

            $temp_total_rebuttal = Rebuttal::whereHas('audit_data', function (Builder $query) 
            use ($client_id,$month_first_data, $today) {
                $query->where('client_id',$client_id)
                ->where('rebuttal_status','>',0)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today);
            })->count();

            $temp_accepted_rebuttal = Rebuttal::whereHas('audit_data', function (Builder $query) 
            use ($client_id, $month_first_data, $today) {
                $query->where('client_id',$client_id)
               
                ->where('rebuttal_status','>',0)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today);
            })->where('status','=', 1)->count();

            $temp_rejected_rebuttal = Rebuttal::whereHas('audit_data', function (Builder $query) 
            use ($client_id, $month_first_data, $today) {
                $query->where('client_id',$client_id)
               
                ->where('rebuttal_status','>',0)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today);
            })->where('status','=', 2)->count();

            $unique_rebuttal_counts = Rebuttal::whereHas('audit_data', function (Builder $query) 
            use ($client_id, $month_first_data, $today) {
                $query->where('client_id',$client_id)
                
                ->where('rebuttal_status','>',0)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today);
            })->distinct('audit_id')->count('audit_id');
        }                   
 

        if($client_id==1)

        {

            $coverage['target'] = 500;

            $coverage['achived_per'] = round(($audit_data->count()/500)*100);


        }
        else if($client_id==9 || $client_id==13 || $client_id==14 || $client_id==15 || $client_id==17 || $client_id==18|| $client_id==23){

            //echo "jsa"; die;

            if(Auth::user()->parent_client) { 

                $target=MonthTarget::

                join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            

                ->where('month_targets.client_id',$client_id)

                //->where('auditcycles.name','like',"$month%")
                ->where('auditcycles.start_date',$month_first_data)
                ->where('auditcycles.end_date',$today)
                

                ->whereIn('month_targets.process_id',$all_cluster_processes)

                ->sum('month_targets.eq_audit_target_mtd');

                /* $month_first_data = $audit_cyle_data->start_date;
                $today = $audit_cyle_data->end_date; */

                /* echo "<pre>"; 
                echo $month; */
                // print_r($all_cluster_processes);
                /* die; */

            } 

            else {

                $target=MonthTarget::

                join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            

                ->where('month_targets.client_id',$client_id)

                ->where('auditcycles.name','like',"$month%")

                ->sum('month_targets.eq_audit_target_mtd');

            }

            

            if(gettype($target) == "NULL"){

                $coverage['target'] = 0;

            }else {

                $coverage['target'] = $target;

            }



            //echo $audit_data->count(); die;

            if($target == 0){

                $coverage['achived_per'] = 0;

            }else {

                $coverage['achived_per'] = round(($audit_data->count()/$target)*100);

            }

        }     

        else

        {

            $coverage['target'] = 22000;

            $coverage['achived_per'] = round(($audit_data->count()/22000)*100);

        }

        

        $coverage['achived'] = $audit_data->count();

        



        $final_data['coverage'] = $coverage;


        
        
            /*     foreach ($temp_rebuttal_data as $key => $value) {

                if($value->audit_rebuttal->count() > 0){
                    $unique_rebuttal_counts++;
                }
                

                $temp_total_rebuttal += $value->audit_rebuttal->count();

                $temp_accepted_rebuttal += $value->audit_rebuttal_accepted->count();

                $temp_rejected_rebuttal += $value->audit_rebuttal_rejected->count();

            } */



        $rebuttal_data['unque_rebuttal'] = $unique_rebuttal_counts;

        $rebuttal_data['raised'] = $temp_total_rebuttal;

        $rebuttal_data['accepted'] = $temp_accepted_rebuttal;

        $rebuttal_data['rejected'] = $temp_rejected_rebuttal;

        $rebuttal_data['wip'] = ($temp_total_rebuttal-($temp_accepted_rebuttal+$temp_rejected_rebuttal));



        if($audit_data->count())

        $rebuttal_data['rebuttal_per'] = round(($rebuttal_data['raised']/$audit_data->count())*100);

        else

        $rebuttal_data['rebuttal_per'] =0; 



        if($rebuttal_data['accepted'])

            $rebuttal_data['accepted_per'] = round((($rebuttal_data['accepted']/$audit_data->count())*100));

        else

            $rebuttal_data['accepted_per'] = 0;



        if($rebuttal_data['rejected'])

            $rebuttal_data['rejected_per'] = round((($rebuttal_data['rejected']/$rebuttal_data['raised'])*100));

        else

            $rebuttal_data['rejected_per'] = 0;


        if($rebuttal_data['wip'])

            $rebuttal_data['wip_per'] = round((($rebuttal_data['wip']/$rebuttal_data['raised'])*100));

        else

            $rebuttal_data['wip_per'] = 0;


        $final_data['rebuttal'] = $rebuttal_data;



        $query_w="";

        $request_w="";

        $complain_w="";

        $s=1;

        foreach ($callType as $key => $value) 

        {

            if($s == 1) {

                $query_w=$value->call_type;

            }

            if($s == 2) {

                $request_w=$value->call_type;

            }

            if($s == 3) {

                $complain_w=$value->call_type;

            }

            

            $s++;

        }



        $temp_qrc['query_count']=0;

        $temp_qrc['query_fatal_score_sum']=0;

        $temp_qrc['query_fatal_count']=0;

        $temp_qrc['request_count']=0;

        $temp_qrc['request_fatal_count']=0;

        $temp_qrc['request_fatal_score_sum'] =0;

        $temp_qrc['complaint_count']=0;

        $temp_qrc['complaint_fatal_count']=0;

        $temp_qrc['complaint_fatal_score_sum'] =0;



        $temp_qrc['query_rebuttal_per']=0;

        $temp_qrc['query_accepted_per']=0;

        $temp_qrc['query_rejected_per']=0;

        $temp_qrc['query_raised_process'] = 0;

        $temp_qrc['query_accepted_process'] = 0;

        $temp_qrc['query_rejected_process'] = 0;



        $temp_qrc['request_rebuttal_per']=0;

        $temp_qrc['request_accepted_per']=0;

        $temp_qrc['request_rejected_per'] =0;

        $temp_qrc['request_raised_process'] = 0;

        $temp_qrc['request_accepted_process'] = 0;

        $temp_qrc['request_rejected_process'] = 0;





        $temp_qrc['complain_rebuttal_per']=0;

        $temp_qrc['complain_accepted_per']=0;

        $temp_qrc['complain_rejected_per']=0;

        $temp_qrc['complain_raised_process'] = 0;

        $temp_qrc['complain_accepted_process'] = 0;

        $temp_qrc['complain_rejected_process'] = 0;



        foreach($audit_data as $d) {           

            if($d->raw_data->call_type == $query_w) {

               $temp_qrc['query_count']+=1; 

               $temp_qrc['query_fatal_score_sum']+=$d->with_fatal_score_per;

            }

            if($d->raw_data->call_type == $query_w && $d->is_critical == 1) {

               $temp_qrc['query_fatal_count']+=1; 

            }

            //request

            if($d->raw_data->call_type == $request_w) {

               $temp_qrc['request_count']+=1; 

               $temp_qrc['request_fatal_score_sum']+=$d->with_fatal_score_per;

            }

            if($d->raw_data->call_type == $request_w && $d->is_critical == 1) {

               $temp_qrc['request_fatal_count']+=1;

            }

            // Complaint

            if($d->raw_data->call_type == $complain_w) {

               $temp_qrc['complaint_count']+=1; 

               $temp_qrc['complaint_fatal_score_sum']+=$d->with_fatal_score_per;

            }

            if($d->raw_data->call_type == $complain_w && $d->is_critical == 1) {

               $temp_qrc['complaint_fatal_count']+=1; 

            }

        }  

            

        $final_data['call_type']=[$query_w,$request_w,$complain_w];

        $final_data['qrc'] = $temp_qrc;

        // ends QRC



        // starts Process Wise Score

        if(Auth::user()->parent_client) {

            if(Auth::user()->hasRole('qtl')) {

                $partner_list = Partner::where('client_id',$client_id)->with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->get();

            } else {

                $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])

                ->whereIn('id',$all_cluster_partners)

                ->get();

            }       

            

        }    

        else  {

            $partner_list = Partner::where('client_id',$client_id)->with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->get();

        }

        $partner_process_list=[];

        foreach ($partner_list as $akey => $avalue) {

            foreach ($avalue->partner_process as $bkey => $bvalue) {

                if(Auth::user()->parent_client) {

                    if(gettype(array_search($bvalue->process_id,$all_cluster_processes)) == "integer") {

                        $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;

                    }

                }

                else {

                    $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;

                }

            }

        }



        $loop=1;

        $final_score=0;

        $final_scorable=0;

        foreach ($partner_process_list as $key => $value) {



            if(Auth::user()->parent_client) {

                

                // this change done by shailendra ticket no 2773

                $parter_list = implode(',', $all_cluster_partners);

                // this change done by shailendra ticket no 2773

                

                $process_audit_data['score_sum'] = Audit::where('client_id',$client_id)

                                            ->where('process_id',$key)

                                            ->whereIn('partner_id',$all_cluster_partners)

                                            ->whereDate('audit_date','>=',$month_first_data)

                                            ->whereDate('audit_date','<=',$today)

                                            ->sum('overall_score');



                $fatal_score_sum = DB::select("

                                            select sum(p.with_fatal_score) as fatal_sum from audit_parameter_results p inner join audits a

                                            on p.audit_id = a.id where a.process_id = ".$key." and a.client_id = ".$client_id." and a.partner_id IN (".$parter_list.")

                                            and a.audit_date >= '".$month_first_data."' and a.audit_date <= DATE_ADD('".$today."', INTERVAL 1 DAY)  and a.is_critical = 0 and a.deleted = 0");

                                

                $temp_wait_sum = DB::select("

                                            select sum(p.temp_weight) as temp_sum from audit_parameter_results p inner join audits a

                                            on p.audit_id = a.id where a.process_id = ".$key." and a.client_id = ".$client_id." and a.partner_id IN (".$parter_list.")

                                            and a.audit_date >= '".$month_first_data."' and a.audit_date <= DATE_ADD('".$today."', INTERVAL 1 DAY) and a.deleted = 0");

                                            



                if(Auth::user()->hasRole('qtl')){
                $process_audit_data['audit_count'] = Audit::where('client_id',$client_id)

                ->where('process_id',$key)

                ->whereIn('partner_id',$all_cluster_partners)

                ->whereDate('audit_date','>=',$month_first_data)

                ->whereDate('audit_date','<=',$today)

                ->count();
                }else{
                $process_audit_data['audit_count'] = Audit::where('client_id',$client_id)

                ->where('process_id',$key)

                ->whereIn('partner_id',$all_cluster_partners)

                ->whereDate('audit_date','>=',$month_first_data)

                ->whereDate('audit_date','<=',$today)

                ->where('qc_tat','<',$current_date_time)

                ->count();
               
                }
               // print_r($process_audit_data['audit_count']); die;
                


                $process_audit_data['with_fatal'] = Audit::where('client_id',$client_id)

                ->where('process_id',$key)

                ->whereIn('partner_id',$all_cluster_partners)

                //->where('is_critical',1)

                ->whereDate('audit_date','>=',$month_first_data)

                ->whereDate('audit_date','<=',$today)

                ->sum('with_fatal_score_per');

            } else {



            

                $process_audit_data['score_sum'] = Audit::where('client_id',$client_id)

                                            ->where('process_id',$key)

                                            ->whereDate('audit_date','>=',$month_first_data)

                                            ->whereDate('audit_date','<=',$today)

                                            ->sum('overall_score');



                $fatal_score_sum = DB::select("

                                            select sum(p.with_fatal_score) as fatal_sum from audit_parameter_results p inner join audits a

                                            on p.audit_id = a.id where a.process_id = ".$key." and a.client_id = ".$client_id."

                                            and a.audit_date >= '".$month_first_data."' and a.audit_date <= DATE_ADD('".$today."', INTERVAL 1 DAY)  and a.is_critical = 0");

                                

                $temp_wait_sum = DB::select("

                                            select sum(p.temp_weight) as temp_sum from audit_parameter_results p inner join audits a

                                            on p.audit_id = a.id where a.process_id = ".$key." and a.client_id = ".$client_id."

                                            and a.audit_date >= '".$month_first_data."' and a.audit_date <= DATE_ADD('".$today."', INTERVAL 1 DAY)");

                                            


                if(Auth::user()->hasRole('qtl')){

                $process_audit_data['audit_count'] = Audit::where('client_id',$client_id)

                ->where('process_id',$key)

                ->whereDate('audit_date','>=',$month_first_data)

                ->whereDate('audit_date','<=',$today)

                ->count();

                }else{

                $process_audit_data['audit_count'] = Audit::where('client_id',$client_id)

                ->where('process_id',$key)

                ->whereDate('audit_date','>=',$month_first_data)

                ->whereDate('audit_date','<=',$today)

                ->where('qc_tat','<',$current_date_time)

                ->count();

                }
                $process_audit_data['with_fatal'] = Audit::where('client_id',$client_id)

                ->where('process_id',$key)

                //->where('is_critical',1)

                ->whereDate('audit_date','>=',$month_first_data)

                ->whereDate('audit_date','<=',$today)

                ->sum('with_fatal_score_per');



            }

        /* New added by shailendra pms ticket - 945  */
        
        $rebuttal_data_process = $this->Calculate_process_rebuttal_welcome_dashboard($client_id,$key,$month_first_data,$today,$process_audit_data['audit_count']);
        
        $process_audit_data['rebuttal_per'] = $rebuttal_data_process['rebuttal_per'];

        $process_audit_data['accepted_per'] = $rebuttal_data_process['accepted_per'];

        $process_audit_data['rejected_per'] = $rebuttal_data_process['rejected_per'];

        $process_audit_data['raised_process'] = $rebuttal_data_process['raised'];

        $process_audit_data['accepted_process'] = $rebuttal_data_process['accepted'];

        $process_audit_data['rejected_process'] = $rebuttal_data_process['rejected'];

     
       
        /* New added by shailendra pms ticket - 945  */



            if($process_audit_data['audit_count']) {



                

          //  $process_audit_data['scored_with_fatal'] = round(   ($process_audit_data['with_fatal']/$process_audit_data['audit_count']));

                /* echo $process_audit_data['audit_count'];

                dd(); */

                if($temp_wait_sum[0]->temp_sum == 0){

                    $process_audit_data['scored_with_fatal'] = 0;

                }else {

                    $process_audit_data['scored_with_fatal'] = round(   ($fatal_score_sum[0]->fatal_sum/$temp_wait_sum[0]->temp_sum) *100);

                }

            

           

            $process_audit_data['score'] = round(($process_audit_data['score_sum']/$process_audit_data['audit_count']));

             }

            else {

            $process_audit_data['score'] = 0;

            $process_audit_data['scored_with_fatal'] = 0;

            }



            $partner_process_list[$key]['data'] = $process_audit_data;

            $final_score+=$process_audit_data['score_sum'];

            $final_scorable+=$process_audit_data['audit_count'];





            if($loop==1)

                $partner_process_list[$key]['class'] = true;

            else

                $partner_process_list[$key]['class']=false;



            $loop++;



        }

        $ov_scored=0;

        if($final_scorable != 0) {

            $ov_scored=round(($final_score/$final_scorable)*100);

        } 



        $final_data['pws'] = $partner_process_list;
        //print_r($final_data['pws']); die;

        // ends Process Wise Score


        // Partner & Location Wise Report

        $pl_report = [];

        $loop=1;

        $partner_name="";

        $loc_audit_count=0;

        foreach ($partner_list as $key => $value) {

        //echo "<pre>"; print_r($value->partner_process); die; 

            $d=array();

            $d['partner_id']=$value->id;

            $d['partner_name']=$value->name;

            if(Auth::user()->hasRole('qtl')){

            $plr_audits = Audit::where('client_id',$client_id)->where('partner_id',$value->id)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)->get();

            }else{
                $plr_audits = Audit::where('client_id',$client_id)->where('partner_id',$value->id)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)->where('qc_tat','<',$current_date_time)->get();
            }
            $d['audit_count']=$plr_audits->count();



            $fatal_score_sum = DB::select("

                        select sum(p.with_fatal_score) as fatal_sum from audit_parameter_results p inner join audits a

                        on p.audit_id = a.id 

                        inner join raw_data r on a.raw_data_id = r.id

                        where a.partner_id = ".$d['partner_id']." and a.client_id = ".$client_id."

                        and a.audit_date >= '".$month_first_data."' and a.audit_date <= '".$today."'  and a.is_critical = 0");

            

                    $temp_wait_sum = DB::select("

                        select sum(p.temp_weight) as temp_sum from audit_parameter_results p inner join audits a

                        on p.audit_id = a.id 

                        inner join raw_data r on a.raw_data_id = r.id

                        where a.partner_id = ".$d['partner_id']." and a.client_id = ".$client_id."

                        and a.audit_date >= '".$month_first_data."' and a.audit_date <= '".$today."'");





            $fatal_audit_score_sum =0;

            $without_fatal_audit_score_sum = 0;

            foreach ($plr_audits as $key => $value1) {

                if($value1->is_critical==1)

                $fatal_audit_score_sum += 0;

                else

                $fatal_audit_score_sum += $value1->overall_score;



                $without_fatal_audit_score_sum +=$value1->overall_score; 

            }

            if($plr_audits->count())

            {

               // $d['with_fatal'] = round(($fatal_audit_score_sum/$plr_audits->count()));



                if($temp_wait_sum[0]->temp_sum == 0){

                    $d['with_fatal'] = 0;

                }else {

                    $d['with_fatal'] = round(($fatal_score_sum[0]->fatal_sum/$temp_wait_sum[0]->temp_sum)*100);

                }

                



                $d['without_fatal'] = round(($without_fatal_audit_score_sum/$plr_audits->count()));

            }else

            {

                $d['with_fatal']=0;

                $d['without_fatal']=0;

            }

            $d['process_data']=array();

            foreach ($value->partner_process as $bkey => $bvalue) { 

                foreach ($value->partner_location as $lkey => $lvalue) { 

                    $a=array();
                    if(isset($bvalue->process->id)){
                    $a['process_id']=$bvalue->process->id;

                    $a['process_name']=$bvalue->process->name;

                    $a['location_id']=$lvalue->location_id;

                    $a['location']=$lvalue->location_detail->name;
                    }
                    else{
                        print_r($bvalue); die;
                    }
                    

                    
                    if(Auth::user()->hasRole('qtl')){
                    $audits = Audit::where('client_id',$client_id)->where('process_id',$a['process_id'])->where('partner_id',$value->id)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)->whereHas('raw_data', function (Builder $query) use ($a) {

                            $query->where('partner_location_id', 'like', $a['location_id']);

                        })->get();
                    }else{
                        $audits = Audit::where('client_id',$client_id)->where('qc_tat','<',$current_date_time)->where('process_id',$a['process_id'])->where('partner_id',$value->id)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)->whereHas('raw_data', function (Builder $query) use ($a) {

                            $query->where('partner_location_id', 'like', $a['location_id']);

                        })->get();
                    }
                    

                    

                    $fatal_score_sum = DB::select("

                        select sum(p.with_fatal_score) as fatal_sum from audit_parameter_results p inner join audits a

                        on p.audit_id = a.id 

                        inner join raw_data r on a.raw_data_id = r.id

                        where a.process_id = ".$a['process_id']." and a.client_id = ".$client_id." and r.partner_location_id like '".$a['location_id']."%'

                        and a.audit_date >= '".$month_first_data."' and a.audit_date <= '".$today."'  and a.is_critical = 0");

            

                    $temp_wait_sum = DB::select("

                        select sum(p.temp_weight) as temp_sum from audit_parameter_results p inner join audits a

                        on p.audit_id = a.id 

                        inner join raw_data r on a.raw_data_id = r.id

                        where a.process_id = ".$a['process_id']." and a.client_id = ".$client_id." and r.partner_location_id like '".$a['location_id']."%'

                        and a.audit_date >= '".$month_first_data."' and a.audit_date <= '".$today."'");

                    

                    

                    

                    $a['audit_count']=$audits->count();









                    $fatal_audit_score =0;

                    $without_fatal_audit_score = 0;

                    foreach ($audits as $k => $v) {



                        if($v->is_critical==1)

                        $fatal_audit_score += 0;

                        else

                        $fatal_audit_score += $v->overall_score;



                        $without_fatal_audit_score +=$v->overall_score;

                    }



                    if($audits->count())

                    {

                       // $a['with_fatal'] = round(($fatal_audit_score/$audits->count()));

                        if($temp_wait_sum[0]->temp_sum == 0){

                            $a['with_fatal'] = 0;

                        }else {

                            $a['with_fatal'] = round(($fatal_score_sum[0]->fatal_sum/$temp_wait_sum[0]->temp_sum)*100);

                        }

                        



                        $a['without_fatal'] = round(($without_fatal_audit_score/$audits->count()));

                    }else

                    {

                        $a['with_fatal']=0;

                        $a['without_fatal']=0;

                    }

                    $d['process_data'][]=$a;

                }

            }

            $pl_report[]=$d; 

        }

        $final_data['plr'] = $pl_report;

        //echo "<pre>"; print_r($pl_report); die;

        // Partner & Location Wise Report



        $audit_cyle_data = Auditcycle::where('client_id',$client_id)->orderby('start_date','desc')->groupBy('name')->get();



        $final_data['audit_cycle'] = $audit_cyle_data;



        

        $final_data['ov_scored']=$ov_scored;



        

               

        return view('dashboards.client_welcome_dashboard_new',compact('final_data','month_first_data','today','client_id'));

        //return response()->json(['status'=>200,'message'=>"Success",'data'=>$final_data], 200);

    }
    
}
