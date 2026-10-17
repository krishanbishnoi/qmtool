<?php
//
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Audit;
use App\Auditcycle;
use App\Rebuttal;
use App\ClientsQtl;
use App\AuditResult;
use DB;
use Illuminate\Database\Eloquent\Builder;
use App\AuditParameterResult;
use App\QmSheet;
use App\Process;
use App\QmSheetSubParameter;
use Carbon\Carbon;
use App\PartnersProcess;
use Auth;

class QaController extends Controller
{
    
    public function index(){
        return view('dashboards.qa_dashboard_new');
    }
    public function my_score($start_date, $end_date){
        
        $response = array();
        $qa = Auth::user()->id;                 
        //$qa_list = get_qtl_qa_list($qtl);
        
        $audit_count = Audit::whereDate('audit_date',">=",$start_date)
        ->whereDate('audit_date',"<=",$end_date)
        ->where('audited_by_id',$qa)
        ->count('id');

        $rebuttal_count = Rebuttal::join('audits', 'rebuttals.audit_id','=', 'audits.id')
        ->whereDate('audits.audit_date',">=",$start_date)
        ->whereDate('audits.audit_date',"<=",$end_date)
        ->where('audits.audited_by_id',$qa)
        ->count('rebuttals.id');
        
        $rebuttal_accepted = Rebuttal::join('audits', 'rebuttals.audit_id','=', 'audits.id')
        ->whereDate('audits.audit_date',">=",$start_date)
        ->whereDate('audits.audit_date',"<=",$end_date)
        ->where('rebuttals.status',1)
        ->where('audits.audited_by_id',$qa)
        ->count('rebuttals.id');

        $rebuttal_rejected = Rebuttal::join('audits', 'rebuttals.audit_id','=', 'audits.id')
        ->whereDate('audits.audit_date',">=",$start_date)
        ->whereDate('audits.audit_date',"<=",$end_date)
        ->where('rebuttals.status',2)
        ->where('audits.audited_by_id',$qa)
        ->count('rebuttals.id');

        $fatal_count = AuditResult::join('audits', 'audit_results.audit_id','=', 'audits.id')
        ->whereDate('audits.audit_date',">=",$start_date)
        ->whereDate('audits.audit_date',"<=",$end_date)
        ->where('audit_results.selected_option',3)
        ->where('audits.audited_by_id',$qa)
        ->count('audit_results.id');

        $fatal_score_sum = AuditParameterResult::join('audits', 'audit_parameter_results.audit_id','=', 'audits.id')
        ->whereDate('audits.audit_date',">=",$start_date)
        ->whereDate('audits.audit_date',"<=",$end_date)
        ->where('audits.is_critical',0)
        ->where('audits.audited_by_id',$qa)
        ->sum('audit_parameter_results.with_fatal_score');

        $temp_wait_sum = AuditParameterResult::join('audits', 'audit_parameter_results.audit_id','=', 'audits.id')
        ->whereDate('audits.audit_date',">=",$start_date)
        ->whereDate('audits.audit_date',"<=",$end_date)
        ->where('audits.audited_by_id',$qa)
        ->sum('audit_parameter_results.temp_weight');

        $overall_score = Audit::whereDate('audit_date',">=",$start_date)
        ->whereDate('audit_date',"<=",$end_date)
        ->where('audited_by_id',$qa)
        ->sum('overall_score');

        if($audit_count){

            $with_fatal_score =   $temp_wait_sum > 0 ? round(($fatal_score_sum/$temp_wait_sum)*100) : 0;    
            $without_fatal_score = $audit_count > 0 ?  round(($overall_score/$audit_count)) : 0; 
        } else {
            $with_fatal_score = 0;    
            $without_fatal_score = 0; 
        }

        $response['audit_count'] = $audit_count;
        $response['rebuttal_count'] = $rebuttal_count;
        $response['rebuttal_accepted'] = $rebuttal_accepted;
        $response['rebuttal_rejected'] = $rebuttal_rejected;
        $response['fatal_count'] = $fatal_count;
        $response['with_fatal_score'] = $with_fatal_score;
        $response['without_fatal_score'] = $without_fatal_score;

        return response()->json(['status'=>200,'message'=>"Success",'data'=>$response],200);
    
    } 
    public function overall_score($start_date, $end_date){
        
        $response = array();
        //$client_id = ClientsQtl::where('qtl_user_id',Auth::user()->id)->pluck('client_id');
        //$client_id = Audit::where('audited_by_id',Auth::user()->id)->pluck('client_id');

      
        $sheet_id =  User::where('id',Auth::user()->id)->pluck('assigned_sheet_id');
        $client_id = QmSheet::where('id', $sheet_id)->pluck('client_id');
        // echo $client_id;
        // die; 
        $audit_count = Audit::whereDate('audit_date',">=",$start_date)
        ->whereDate('audit_date',"<=",$end_date)
        ->whereIn('client_id',$client_id)
        ->count('id');

        $rebuttal_count = Rebuttal::join('audits', 'rebuttals.audit_id','=', 'audits.id')
        ->whereDate('audits.audit_date',">=",$start_date)
        ->whereDate('audits.audit_date',"<=",$end_date)
        ->whereIn('audits.client_id',$client_id)
        ->count('rebuttals.id');
        
        $rebuttal_accepted = Rebuttal::join('audits', 'rebuttals.audit_id','=', 'audits.id')
        ->whereDate('audits.audit_date',">=",$start_date)
        ->whereDate('audits.audit_date',"<=",$end_date)
        ->where('rebuttals.status',1)
        ->whereIn('audits.client_id',$client_id)
        ->count('rebuttals.id');

        $rebuttal_rejected = Rebuttal::join('audits', 'rebuttals.audit_id','=', 'audits.id')
        ->whereDate('audits.audit_date',">=",$start_date)
        ->whereDate('audits.audit_date',"<=",$end_date)
        ->where('rebuttals.status',2)
        ->whereIn('audits.client_id',$client_id)
        ->count('rebuttals.id');

        $fatal_count = AuditResult::join('audits', 'audit_results.audit_id','=', 'audits.id')
        ->whereDate('audits.audit_date',">=",$start_date)
        ->whereDate('audits.audit_date',"<=",$end_date)
        ->where('audit_results.selected_option',3)
        ->whereIn('audits.client_id',$client_id)
        ->count('audit_results.id');

        $fatal_score_sum = AuditParameterResult::join('audits', 'audit_parameter_results.audit_id','=', 'audits.id')
        ->whereDate('audits.audit_date',">=",$start_date)
        ->whereDate('audits.audit_date',"<=",$end_date)
        ->where('audits.is_critical',0)
        ->whereIn('audits.client_id',$client_id)
        ->sum('audit_parameter_results.with_fatal_score');

        $temp_wait_sum = AuditParameterResult::join('audits', 'audit_parameter_results.audit_id','=', 'audits.id')
        ->whereDate('audits.audit_date',">=",$start_date)
        ->whereDate('audits.audit_date',"<=",$end_date)
        ->whereIn('audits.client_id',$client_id)
        ->sum('audit_parameter_results.temp_weight');

        $overall_score = Audit::whereDate('audit_date',">=",$start_date)
        ->whereDate('audit_date',"<=",$end_date)
        ->whereIn('client_id',$client_id)
        ->sum('overall_score');

        if($audit_count){

            $with_fatal_score = $temp_wait_sum > 0 ? round(($fatal_score_sum/$temp_wait_sum)*100) : 0;    
            $without_fatal_score = $audit_count > 0 ? round(($overall_score/$audit_count)) : 0; 
        } else {
            $with_fatal_score = 0;    
            $without_fatal_score = 0; 
        }

        $response['audit_count'] = $audit_count;
        $response['rebuttal_count'] = $rebuttal_count;
        $response['rebuttal_accepted'] = $rebuttal_accepted;
        $response['rebuttal_rejected'] = $rebuttal_rejected;
        $response['fatal_count'] = $fatal_count;
        $response['with_fatal_score'] = $with_fatal_score;
        $response['without_fatal_score'] = $without_fatal_score;

        //$response['rebuttal_accepted'] = $rebuttal_accepted;

        return response()->json(['status'=>200,'message'=>"Success",'data'=>$response],200);
    
    }


    // public function qa_dashboard_targets(){
    //     $response = array();
    //     $qa = Auth::user()->id;
    //     $todays_date = date('y-m-d');

    //     $todays_score = Audit::where('audit_date', $todays_date )->where('audited_by_id', $qa)->count();
    //     $audit_cycle = Auditcycle::where('client_id',Auth::user()->parent_client)->orderBy('id', 'desc')->take(1)
    //     ->get();

    //     $firstStartDate = $audit_cycle->start_date;
    //     $firstEndDate = $audit_cycle->end_date;
    //     $current_cycle_audits =  Audit::whereDate('audit_date', '>=', $firstStartDate) ->whereDate('audit_date', '<=', $firstEndDate)->where('audited_by_id', $qa)->count();

    //     $achievedPerToday =   ($todays_score/40) *100;
    //     $achievedPerCurrentCycle =   ($current_cycle_audits/936) *100;

    //     $not_achived_today = 40 -  $todays_score;
    //     $not_achived_today_per = ($not_achived_today/40)*100;

    //     $not_achived_cycle = 936 -  $current_cycle_audits;
    //     $not_achived_cycle_per = ($not_achived_cycle/936)*100;

    //     $response['todays_score'] = $todays_score;
    //     $response['current_cycle_audits'] = $current_cycle_audits;
    //     $response['not_achived_today_per'] = $not_achived_today_per;
    //     $response['not_achived_cycle_per'] = $not_achived_cycle_per;
    //     $response['achievedPerToday'] = $achievedPerToday;
    //     $response['achievedPerCurrentCycle'] = $achievedPerCurrentCycle;

    //     return response()->json(['status'=>200,'message'=>"Success",'data'=>$response],200);      


    // }

    public function qa_dashboard_targets() {
        $qa = Auth::user()->id;
        $todays_date = now()->format('Y-m-d');
    
      
        $todays_score = Audit::whereDate('audit_date', $todays_date)
                            ->where('audited_by_id', $qa)
                            ->count();
    
        
        $audit_cycle = Auditcycle::where('client_id', Auth::user()->parent_client)
                                ->latest('id')
                                ->first();
    
       
        $firstStartDate = $audit_cycle ? $audit_cycle->start_date : null;
        $firstEndDate = $audit_cycle ? $audit_cycle->end_date : null;
    
      
        $current_cycle_audits = $firstStartDate && $firstEndDate ? 
                                Audit::whereDate('audit_date', '>=', $firstStartDate) ->whereDate('audit_date', '<=', $firstEndDate)
                                    ->where('audited_by_id', $qa)
                                    ->count() : 0;
    
      
        $target_per_day = 40;
        $target_per_cycle = 936;
        $achievedPerToday = number_format(($todays_score / $target_per_day) * 100 , 2);
        $achievedPerCurrentCycle = number_format(($current_cycle_audits / $target_per_cycle) * 100 , 2);
    
        // Calculation of not achieved percentages
        $not_achieved_today_per = 100 - $achievedPerToday;
        $not_achieved_cycle_per = 100 - $achievedPerCurrentCycle;
    
       
        $response = [
            'todays_score' => $todays_score,
            'current_cycle_audits' => $current_cycle_audits,
            'not_achieved_today_per' => $not_achieved_today_per,
            'not_achieved_cycle_per' => $not_achieved_cycle_per,
            'achievedPerToday' => $achievedPerToday,
            'achievedPerCurrentCycle' => $achievedPerCurrentCycle
        ];
    
        return response()->json(['status' => 200, 'message' => "Success", 'data' => $response], 200);
    }



    public function qtl_dashboard_qa_performance_piller_chart_data($start_date, $end_date){
        $response = array();
        $qtl = Auth::user()->id;
        $qa_list = get_qtl_qa_list($qtl);

        $all_auditor_list = Audit::distinct('audited_by_id')->whereIn('audited_by_id',$qa_list)
        ->whereDate('audit_date',">=",$start_date)
        ->whereDate('audit_date',"<=",$end_date)->pluck('audited_by_id');

        $auditor_name_list = User::whereIn('id',$all_auditor_list)->pluck('name');
        $audit_done = [];
        $rebuttal_raised = [];
        $rebuttal_accepted = [];
        $fatal = [];
        foreach($all_auditor_list as $value){
            
            $audit_count = Audit::where('audited_by_id',$value)->whereDate('audit_date',">=",$start_date)
            ->whereDate('audit_date',"<=",$end_date)->count('id');

            $fatal_count = Audit::where('audited_by_id',$value)->where('is_critical',1)
            ->whereDate('audit_date',">=",$start_date)
            ->whereDate('audit_date',"<=",$end_date)->count('id');

            $rebuttal_count = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id') 
            ->where('audits.audited_by_id',$value)->whereDate('audits.audit_date',">=",$start_date)
            ->whereDate('audits.audit_date',"<=",$end_date)->count('rebuttals.id');
            

            array_push($audit_done,$audit_count);
            array_push($fatal,$fatal_count);
            array_push($rebuttal_raised,$rebuttal_count);
            
        }
        $response['auditor_name_list'] = $auditor_name_list;
        $response['audit_done'] = $audit_done;
        $response['fatal'] = $fatal;
        $response['rebuttal_raised'] = $rebuttal_raised;
        
        return response()->json(['status'=>200,'message'=>"Success",'data'=>$response],200);
    }

    
    public function qtl_dashboard_qc_deviation_piller_chart_data($start_date, $end_date){
    
        $response = array();
        $qtl = Auth::user()->id;
        $name = [];
        $count = [];
        $qc_count_data = DB::select("select u.name as users, count(audited_by_id) as number  
        from audits a inner join users u on a.audited_by_id= u.id where a.qc_status = 1  and 
        a.audit_date >= '".$start_date."' and a.audit_date <= '".$end_date."'  and 
        u.reporting_user_id = '".$qtl."' group by audited_by_id ");
         
        foreach($qc_count_data as $value){
             array_push($name,$value->users);
             array_push($count,$value->number);


        }

        
        
        $response['users'] = $name;
        $response['number'] = $count;
        
        

        return response()->json(['status'=>200,'message'=>"Success",'data'=>  $response],200);
    }
    public function qa_dashboard_process_wise_performance_data($start_date, $end_date){
        $response = array();
        $qa = Auth::user()->id;
       // $qa_list = get_qtl_qa_list($qtl);

        

        // $process_list = PartnersProcess::join('partners','partners_processes.partner_id', '=', 'partners.id')
        //     ->join('clients', 'clients.id','=','partners.client_id')
        //     ->join('clients_qtls','partners.client_id','=','clients_qtls.client_id')
        //     ->join('processes','partners_processes.process_id','=','processes.id')
        //     ->where('clients_qtls.qtl_user_id',$qtl)->distinct()->pluck('processes.name');
        
             $process_ids = User::select('assigned_process_ids')->where('id',Auth::user()->id)->first();
             $process_id = json_decode($process_ids->assigned_process_ids);
            
          
              

        $audit_done = [];
        $rebuttal_raised = [];
        $rebuttal_accepted = [];
        $fatal = [];
        $process_wise_data = [];
        
        foreach($process_id as $value){
            $process_data = array();
            
            $audit_count = Audit::where('audited_by_id',$qa)->where('process_id',$value)->whereDate('audit_date',">=",$start_date)
            ->whereDate('audit_date',"<=",$end_date)->count('id');

            $fatal_count = Audit::where('audited_by_id',$qa)->where('is_critical',1)
            ->where('process_id',$value)
            ->whereDate('audit_date',">=",$start_date)
            ->whereDate('audit_date',"<=",$end_date)->count('id');

            $rebuttal_count = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id') 
            ->where('audits.audited_by_id',$qa)->whereDate('audits.audit_date',">=",$start_date)
            ->whereDate('audits.audit_date',"<=",$end_date)
            ->where('audits.process_id',$value)
            ->count('rebuttals.id');
            
            $process_list = Process::where('id',$value)->first();
            // array_push($audit_done,$audit_count);
            // array_push($fatal,$fatal_count);
            // array_push($rebuttal_raised,$rebuttal_count);
            $process_data['category'] = $process_list->name;
            $process_data['audit_done'] = $audit_count;
             $process_data['fatal'] = $fatal_count;
            $process_data['rebuttal_raised'] = $rebuttal_count;
            $process_wise_data[] = $process_data;
        }
       
      
        // $response['audit_done'] = $audit_done;
        // $response['fatal'] = $fatal;
        // $response['rebuttal_raised'] = $rebuttal_raised;
        // $response['process_list'] = $process_list;
        // $response['process_list_id'] = $process_ids;
        
        return response()->json(['status'=>200,'message'=>"Success",'data'=>$process_wise_data],200);
    }

    public function qa_dashboard_pareto_agent_wise($start_date, $end_date,$agent_name){
        $qa = Auth::user()->id;
       
        
        if($agent_name == "all"){ 
            $data = DB::select("SELECT count(ar.reason_id) as visits, r.name as country 
            FROM audit_results ar inner join audits a on ar.audit_id = a.id inner join reasons r 
            on ar.reason_id = r.id inner join raw_data rd on a.raw_data_id = rd.id where a.audited_by_id =".$qa." 
            and a.audit_date>='".$start_date."' and audit_date <= DATE_ADD('".$end_date."', INTERVAL 1 DAY)  
            group by reason_id");
        }
        else{
            $data = DB::select("SELECT count(ar.reason_id) as visits, r.name as country 
            FROM audit_results ar inner join audits a on ar.audit_id = a.id inner join reasons r 
            on ar.reason_id = r.id inner join raw_data rd on a.raw_data_id = rd.id where a.audited_by_id =".$qa." 
            and a.audit_date>='".$start_date."' and audit_date <= DATE_ADD('".$end_date."', INTERVAL 1 DAY)
            and rd.agent_name = '".$agent_name."' group by reason_id");
        }

        $data =json_decode(json_encode($data), true);;
        
        uasort($data, $this->make_comparer(array('visits', SORT_DESC)));
        $response = [];
        foreach($data as $value){
            $d=array();
            $d['country']=$value['country'];
            $d['visits']=$value['visits'];
            $response[] = $d;
        }
        return response()->json(['status'=>200,'message'=>"Success",'data'=>$response],200);
    }
    function make_comparer() {
        $criteriaNames = func_get_args();
        $comparer = function($first, $second) use ($criteriaNames) {
            // Do we have anything to compare?
            while(!empty($criteriaNames)) {
                // What will we compare now?
                $criterion = array_shift($criteriaNames);
    
                // Used to reverse the sort order by multiplying
                // 1 = ascending, -1 = descending
                $sortOrder = 1; 
                if (is_array($criterion)) {
                    $sortOrder = $criterion[1] == SORT_DESC ? -1 : 1;
                    $criterion = $criterion[0];
                }
    
                // Do the actual comparison
                if ($first[$criterion] < $second[$criterion]) {
                    return -1 * $sortOrder;
                }
                else if ($first[$criterion] > $second[$criterion]) {
                    return 1 * $sortOrder;
                }
    
            }
    
            // Nothing more to compare with, so $first == $second
            return 0;
        };
    
        return $comparer;
    }

    public function qa_dashboard_trend_chart_data(){
        $date = "2022-04-03";
        // $period = \Carbon\CarbonPeriod::create('2022-01-03', '1 month', $date);

        // foreach ($period as $dt) {
        //  echo $dt->format("Y-m") . "<br>\n";
        
        // }die;
        $month_array = [];
        $month_name = [];
        for($i = 1; $i<=6; $i++){
            $date = "2021-09-12 12:12:12";
            $today = Carbon::now()->subMonths($i);
            array_push($month_array,$today->format("Y-m"));
            array_push($month_name,$today->format("Y-M"));
        }
        // print_r($month_array);
        // die;

        $position = 0;
        $audit_data = [];


        foreach($month_array as $value){
            $objects = array();
            $value .= "%";
            $name = $month_name[$position];

            $audit_count = Audit::where('audited_by_id',Auth::user()->id)->where('audit_date','like',$value)
            ->count('id');
            $objects['date'] = $name;
            $objects['value'] = $audit_count;
            
            $audit_data[] = $objects;
            $position++;
            
        }
        
        return response()->json(['status'=>200,'message'=>"Success",'data'=>$audit_data],200);
    }

    function qa_dashboard_performance_for_auditors($start_date, $end_date){
        $response = array();
        $qa = Auth::user()->id;
        $data = [];
        
        $audit = Audit::where('audited_by_id',$qa)->first();
        $client_id = $audit->client_id;
     
        $auditors_max_count = DB::select("select count(a.id) as counts,max(audited_by_id)as user_id,
         u.name as names from audits a inner join users u on a.audited_by_id= u.id 
         where audit_date>='".$start_date."' and audit_date <= DATE_ADD('".$end_date."', INTERVAL 1 DAY) and a.audited_by_id = u.id and u.status = 1 and a.client_id = ".$client_id."
         group by audited_by_id order by count(a.id) desc");

      

         foreach($auditors_max_count as $value){
            $response['names'] = $value->names;
            if(Auth::user()->id == $value->user_id){
                $response['current'] = 1;
            }else {
                $response['current'] = 0;
            }
            $response['counts'] =  $value->counts;
            
            $data[] = $response;
        }
        return response()->json(['status'=>200,'message'=>"Success",'data'=> $data],200);
   
    }

    function qa_dashboard_performance_for_agents($start_date, $end_date){
        $response = array();
        $qa = Auth::user()->id;
        $data = [];
        
        $audit = Audit::where('audited_by_id',$qa)->first();
        $client_id = $audit->client_id;

        $agents_audit_count = DB::select("select count(a.id) as counts,a.audited_by_id,
        r.agent_name as names FROM audits a inner join raw_data r on a.raw_data_id = r.id  
         where audit_date>='".$start_date."' and audit_date <= DATE_ADD('".$end_date."', INTERVAL 1 DAY) 
         and a.audited_by_id = ".$qa." and a.client_id = ".$client_id."
         group by r.agent_name order by count(a.id) desc");

        foreach($agents_audit_count as $value){
            $response['names'] = $value->names;
            $response['counts'] =  $value->counts;
            
            $data[] = $response;
        }
        return response()->json(['status'=>200,'message'=>"Success",'data'=> $data],200);
   
    }
}
