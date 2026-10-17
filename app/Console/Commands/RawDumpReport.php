<?php

namespace App\Console\Commands;

ini_set('memory_limit', '-1');

ini_set('allow_url_fopen','1');
use Aws\S3\S3Client;
use Illuminate\Console\Command;
use App\Audit;
use App\AuditParameterResult;
use App\AuditResult;
use App\Client;
use App\Partner;
use App\PartnerLocation;
use App\PartnersProcess;
use App\QmSheet;
use App\RawDumpCroneFilter;
use App\Mode;
use Crypt;
use App\QmSheetParameter;
use App\QmSheetSubParameter;
use App\RawData;
use App\ReLabel;
use App\Process;
use App\User;
use App\Circle;
use App\ReasonType;
use App\BodReason;
use Auth;
use DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Role;
use App\RoleUser;

class RawDumpReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rawdump:report';

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
        $yesterday_date = Carbon::now()->subDays(1)->format('Y-m-d');
        //whereDate('created_at',$yesterday_date)->
        $todays_filters = RawDumpCroneFilter::where('file_save_status',0)->whereDate('created_at',$yesterday_date)->get();
       
        //$todays_filters = RawDumpCroneFilter::where('id',16046)->get();
        foreach($todays_filters as $value_dump){

            $data=[];
            
            
            if($value_dump->qm_sheet_id){
                $sheet_details = QmSheet::find($value_dump->filter_sheet_id);
                $mode_status = 0;
            } else {
                $mode_status =0;
            }
            $all_reason_type = ReasonType::with('reasons')->get();
            //$all_modes = Mode::get();

            $params_data = QmSheetParameter::where('qm_sheet_id',$value_dump->filter_sheet_id)->with('qm_sheet_sub_parameter')->get();
            $repeater_param_data=[];
            foreach ($params_data as $key => $values) {
                foreach ($values->qm_sheet_sub_parameter as $keyb => $valueb) {
                    $repeater_param_data[$values->id][$valueb->id]=['name'=>$valueb->sub_parameter,'observation'=>null,'scored'=>0,'scorable'=>0,'remark'=>null,'reason_type'=>null,'reason'=>null];
                }
            }
        
            $partner_id = $value_dump->filter_partner_id;
            $partner_location_id = $value_dump->filter_location_id;
            $client_id = $value_dump->filter_client_id;
            $lob = $value_dump->filter_lob_id;
            $brand_name = $value_dump->filter_brand_id;
            $circle = $value_dump->filter_circle_id;
            $parent_client = User::where('parent_client',$value_dump->filter_client_id)->first();

            // Cluster Code
            $parent_client_id = $parent_client->parent_client;
            $client = $value_dump->user_id;
            $current_date_time = date('Y-m-d H:i:s');
            $all_cluster_clients = [$client];
            $all_cluster_processes = get_helper_cluster_processes($value_dump->user_id);
            $all_cluster_partners = get_helper_cluster_partners($value_dump->user_id);
            $all_cluster_locations = get_helper_cluster_locations($value_dump->user_id);
           // $all_cluster_lobs = get_helper_cluster_lobs($value_dump->user_id);

         

          $has_role = Role::select('roles.name')->join('role_user','roles.id','=','role_user.role_id')->join('users','users.id','=','role_user.user_id')
          ->where('users.id',$value_dump->user_id)->first();
            $assigned_role = $has_role->name;
           //echo $assigned_role; die;
           if($parent_client_id){
               if($partner_id != "%"){
                   $all_cluster_partners = [$partner_id];
               }
               if($partner_location_id != "%"){
                   $all_cluster_locations = [$partner_location_id];
               }
               
               if(($assigned_role == 'client') || ($assigned_role == 'partner-admin') ||
   
               ($assigned_role =='partner-training-head') || ($assigned_role =='partner-operation-head')
        
               || ($assigned_role =='partner-quality-head')) {
                  
                  
                    $temp_audit_data = Audit::whereHas('raw_data', function (Builder $query) use ($all_cluster_partners,$all_cluster_locations,$parent_client_id) {
        
                    $query->where('client_id',$parent_client_id)
                           
                        ->whereIn('partner_id',$all_cluster_partners)
        
                        ->whereIn('partner_location_id',$all_cluster_locations);
        
                    })->where('qm_sheet_id',$value_dump->filter_sheet_id)
        
                    ->where('process_id',$value_dump->filter_process_id)
        
                    ->whereDate('audit_date','>=',$value_dump->filter_start_date)
                    ->where('qc_tat','<',$current_date_time)
        
                    ->whereDate('audit_date','<=',$value_dump->filter_end_date)->with(['raw_data','raw_data.location_data','raw_data.partner_detail','audit_results','qa_qtl_detail','audit_results.reason_type','audit_results.reason'])->get();
        
                   //  echo $request->start_date;
                   //  echo "//".$request->end_date;
                   //  echo "//".count($all_cluster_partners);
                   //  echo "//".count($all_cluster_locations);
                   //  echo "//".count($all_cluster_processes); die;
                    //echo count($temp_audit_data); die;
               } else {
              
                    $temp_audit_data = Audit::whereHas('raw_data', function (Builder $query) use ($all_cluster_partners,$all_cluster_locations,$parent_client_id) {
        
                    $query->where('client_id',$parent_client_id)
        
                    ->whereIn('partner_id',$all_cluster_partners)
        
                    ->whereIn('partner_location_id',$all_cluster_locations);
        
                    })->where('qm_sheet_id',$value_dump->filter_sheet_id)
        
                    ->where('process_id',$value_dump->filter_process_id)
        
                    ->whereDate('audit_date','>=',$value_dump->filter_start_date)
        
                    ->whereDate('audit_date','<=',$value_dump->filter_end_date)->with(['raw_data','raw_data.location_data','raw_data.partner_detail','audit_results','qa_qtl_detail','audit_results.reason_type','audit_results.reason'])->get();
        
        
        
               }
           }
        else{

          if(($assigned_role =='client') || ($assigned_role =='partner-admin') ||
   
          ($assigned_role =='partner-training-head') || ($assigned_role =='partner-operation-head')
   
          || ($assigned_role =='partner-quality-head')) {
     
               $temp_audit_data = Audit::whereHas('raw_data', function (Builder $query) use ($partner_id,$partner_location_id,$parent_client_id) {
   
               $query->where('client_id',$parent_client_id)
   
                   ->where('partner_id','LIKE',$partner_id)
   
                   ->where('partner_location_id','LIKE',$partner_location_id);
   
               })->where('qm_sheet_id',$value_dump->filter_sheet_id)
   
               ->where('process_id',$value_dump->filter_process_id)
   
               ->whereDate('audit_date','>=',$value_dump->filter_start_date)
               ->where('qc_tat','<',$current_date_time)
   
               ->whereDate('audit_date','<=',$value_dump->filter_end_date)->with(['raw_data','raw_data.location_data','raw_data.partner_detail','audit_results','qa_qtl_detail','audit_results.reason_type','audit_results.reason'])->get();
   
            
   
          } else {
            
               $temp_audit_data = Audit::whereHas('raw_data', function (Builder $query) use ($partner_id,$partner_location_id,$parent_client_id) {
   
               $query->where('client_id',$parent_client_id)
   
                   ->where('partner_id','LIKE',$partner_id)
   
                   ->where('partner_location_id','LIKE',$partner_location_id);
   
               })->where('qm_sheet_id',$value_dump->filter_sheet_id)
   
               ->where('process_id',$value_dump->filter_process_id)
   
               ->whereDate('audit_date','>=',$value_dump->filter_start_date)
   
               ->whereDate('audit_date','<=',$value_dump->filter_end_date)->with(['raw_data','raw_data.location_data','raw_data.partner_detail','audit_results','qa_qtl_detail','audit_results.reason_type','audit_results.reason'])->get();
   
   
   
          }
        }
       
            $labels = ReLabel::where('qm_sheet_id',$value_dump->qm_sheet_id)->first();
          
            //$audit_date_head = isset($labels->qm_sheet_id) ? $labels->info_4 : "Audit Date";
            
            // Parameter Header
            $para_headers = "";
            foreach($repeater_param_data as $kk=>$vv){
                foreach($vv as $kkb=>$vvb){
                    $ismode = "";
                    if($mode_status == 1){
                        $ismode = "<th style='border:1px solid black;text-align: center;'>Mode</th>";
                    }
                    $para_headers .= "<th style='border:1px solid black;text-align: center;'>".$vvb['name']."</th>";
                    $para_headers .= "<th style='border:1px solid black;text-align: center;'>Scored</th>
                    <th style='border:1px solid black;text-align: center;'>Scorable</th>".$ismode."
                    <th style='border:1px solid black;text-align: center;'>Reason Type</th>
                    <th style='border:1px solid black;text-align: center;'>Reason</th>
                    <th style='border:1px solid black;text-align: center;'>Remark</th>
                    ";
                    
                }
            }
            // Parameter Header

          
            $test ="<table style='border:1px solid black;'>
                    <tr> 
                        <th style='border:1px solid black;text-align: center;'>Auditor Name</th>
                        <th style='border:1px solid black;text-align: center;'>". (isset($labels->qm_sheet_id) ? $labels->info_4 : "Audit Date")."</th>
                        <th style='border:1px solid black;text-align: center;'>". (isset($labels->qm_sheet_id) ? $labels->info_3 : "Partner")."</th>
                        <th style='border:1px solid black;text-align: center;'>location</th>
                        <th style='border:1px solid black;text-align: center;'>Call ID</th>
                        <th style='border:1px solid black;text-align: center;'>". (isset($labels->qm_sheet_id) ? $labels->info_5 : "Agent Name")."</th>
                        <th style='border:1px solid black;text-align: center;'>Emp. ID</th>
                        <th style='border:1px solid black;text-align: center;'>Doj</th>
                        <th style='border:1px solid black;text-align: center;'>Lob</th>
                        <th style='border:1px solid black;text-align: center;'>Language</th>
                        <th style='border:1px solid black;text-align: center;'>Case Id</th>
                        <th style='border:1px solid black;text-align: center;'>". (isset($labels->qm_sheet_id) ? $labels->info_19 : "Call Time")."</th>
                        <th style='border:1px solid black;text-align: center;'>". (isset($labels->qm_sheet_id) ? $labels->info_20 : "Call Duration")."</th>
                        <th style='border:1px solid black;text-align: center;'>Call Type</th>
                        <th style='border:1px solid black;text-align: center;'>". (isset($labels->qm_sheet_id) ? $labels->info_9 : "Call Sub Type")."</th>
                        <th style='border:1px solid black;text-align: center;'>". (isset($labels->qm_sheet_id) ? $labels->info_10 : "Disposition")."</th>
                        <th style='border:1px solid black;text-align: center;'>". (isset($labels->qm_sheet_id) ? $labels->info_8 : "Campaign Name")."</th>
                        <th style='border:1px solid black;text-align: center;'>". (isset($labels->qm_sheet_id) ? $labels->info_11 : "Customer Name")."</th>
                        <th style='border:1px solid black;text-align: center;'>". (isset($labels->qm_sheet_id) ? $labels->info_12 : "Customer Phone")."</th>
                        <th style='border:1px solid black;text-align: center;'>Refrence Number</th>
                        <th style='border:1px solid black;text-align: center;'>QRC</th>
                        <th style='border:1px solid black;text-align: center;'>Language 2</th>
                        <th style='border:1px solid black;text-align: center;'>Overall Summary</th>
                        <th style='border:1px solid black;text-align: center;'>With Fatal Score</th>
                        <th style='border:1px solid black;text-align: center;'>Without Fatal Score</th>
                        <th style='border:1px solid black;text-align: center;'>Brand Name</th>
                        <th style='border:1px solid black;text-align: center;'>Circle</th>
                        <th style='border:1px solid black;text-align: center;'>RCA Type</th>
                        <th style='border:1px solid black;text-align: center;'>A - RCA Mode</th>
                        <th style='border:1px solid black;text-align: center;'>A - RCA1</th>
                        <th style='border:1px solid black;text-align: center;'>A - RCA2</th>
                        <th style='border:1px solid black;text-align: center;'>A - RCA3</th>
                        <th style='border:1px solid black;text-align: center;'>A - RCA Other</th>
                        <th style='border:1px solid black;text-align: center;'>B - RCA Mode</th>
                        <th style='border:1px solid black;text-align: center;'>B - RCA1</th>
                        <th style='border:1px solid black;text-align: center;'>B - RCA2</th>
                        <th style='border:1px solid black;text-align: center;'>B - RCA3</th>
                        <th style='border:1px solid black;text-align: center;'>B - RCA Other</th>
                        <th style='border:1px solid black;text-align: center;'>Info 1</th>
                        <th style='border:1px solid black;text-align: center;'>Info 2</th>
                        <th style='border:1px solid black;text-align: center;'>Info 3</th>
                        <th style='border:1px solid black;text-align: center;'>Info 4</th>
                        <th style='border:1px solid black;text-align: center;'>Info 5</th>
                        ".$para_headers."
                        <th style='border:1px solid black;text-align: center;'>Error Code</th>
                        <th style='border:1px solid black;text-align: center;'>Total Scored</th>
                        <th style='border:1px solid black;text-align: center;'>Total Scorable</th>
                    </tr> 
                <tbody>";

            
        
            
            foreach ($temp_audit_data as $key => $value) {
                    
                    $basic['temp_raw_data'] = $value->raw_data;
                    $basic['auditor'] = $value->auditor->name;
                    $basic['audit_date'] = $value->audit_date;
                    $basic['partner'] = $value->raw_data->partner_detail->name;
                    //dd($value->raw_data->location_data->name);
                    $basic['location'] = $value->raw_data->location_data->name;
                    $basic['case_id'] = $value->case_id;

                    if($value->good_bad_call_file){
                        $path_name = str_replace("https://qmtool.s3.ap-south-1.amazonaws.com/", "", $value->good_bad_call_file);
                        $url = Storage::disk('s3')->temporaryUrl(
                            $path_name,
                            now()->addMinutes(8640) //Minutes for which the signature will stay valid
                        );
                        $basic['good_bad_call_file'] = $url;
                    } else {
                        $basic['good_bad_call_file'] = $value->good_bad_call_file;
                    }
                    $basic['audited_by'] = $value->qa_qtl_detail->name;
                    $basic['qrc_2'] = $value->qrc_2;
                    $basic['language_2'] = $value->language_2;
                    $basic['refrence_number'] = $value->refrence_number;
                    $basic['error_code'] = $value->error_code;
                    $basic['overall_summary'] = $value->overall_summary;
                    $basic['without_fatal_score'] = $value->overall_score;
                    $basic['with_fatal_score_per'] = $value->with_fatal_score_per;

                    $basic['rca_type'] = ($value->type_id)?$value->rca_type->name:'-';

                    $basic['a_rca_mode'] = ($value->mode_id)?$value->a_rca_mode->name:'-';
                    $basic['a_rca_type_1'] = ($value->rca1_id)?$value->a_rca_type_1->name:'-';
                    $basic['a_rca_type_2'] = ($value->rca2_id)?$value->a_rca_type_2->name:'-';
                    $basic['a_rca_type_3'] = ($value->rca3_id)?$value->a_rca_type_3->name:'-';
                    $basic['a_rca_type_other'] = $value->rca_other_detail;

                    $basic['b_rca_mode'] = ($value->type_2_rca_mode_id)?$value->b_rca_mode->name:'-';
                    $basic['b_rca_type_1'] = ($value->type_2_rca1_id)?$value->b_rca_type_1->name:'-';
                    $basic['b_rca_type_2'] = ($value->type_2_rca2_id)?$value->b_rca_type_2->name:'-';
                    $basic['b_rca_type_3'] = ($value->type_2_rca3_id)?$value->b_rca_type_3->name:'-';
                    $basic['b_rca_type_other'] = $value->type_2_rca_other_detail;

                    $temp_result = $value->audit_results;
                
                    
                    foreach ($repeater_param_data as $keyb => $valueb) {
                    
                        foreach ($valueb as $keyc => $valuec) {
                            $to_filter_row_id = $temp_result->where('parameter_id',$keyb)->where('sub_parameter_id',$keyc)->sum('id');

                            $to_filter_row = $temp_result->find($to_filter_row_id);
                            
                            
                            if(!is_null($to_filter_row))
                            {
                            if($to_filter_row->is_non_scoring == 1 ) {
                                $repeater_param_data[$keyb][$keyc]['observation'] = return_non_scoring_observation($to_filter_row->selected_option);
                                $repeater_param_data[$keyb][$keyc]['scorable'] = $to_filter_row->after_audit_weight;
                            } else if($to_filter_row->is_non_scoring == 2){
                                $repeater_param_data[$keyb][$keyc]['scorable'] = 0;
                                $repeater_param_data[$keyb][$keyc]['observation'] = return_general_observation($to_filter_row->selected_option);
                            }
                                
                            else {
                                $repeater_param_data[$keyb][$keyc]['scorable'] = $to_filter_row->after_audit_weight;
                                $repeater_param_data[$keyb][$keyc]['observation'] = return_general_observation($to_filter_row->selected_option);
                            }
                   
                            if($to_filter_row->reason_type_id)
                                {
                                    $temp_reason_type = $all_reason_type->find($to_filter_row->reason_type_id);
                                    if(!isset($temp_reason_type->name)){
                                    
                                        $reasons = '-';
                                        $temp_reason = '-';
                                        $reason = '-';
                                        $reason_type = '-';
                                        
                                    }
                                    else{
                                        $reason_type = $temp_reason_type->name;
                                
                                        $reasons = $temp_reason_type->reasons;
                                        $temp_reason = $reasons->find($to_filter_row->reason_id);
        
                                        if($temp_reason)
                                            $reason = $temp_reason->name;
                                        else
                                            $reason = $to_filter_row->reason_id;
                                    }
                                
                                }
                            else
                                {
                                    $reason_type = '-';
                                    $reason = '-';
                                }

                                
                            /* print_r($to_filter_row);
                            die; */
                            //echo $to_filter_row->mode_id;
                            // $mode_details = AuditResult::where('audit_id',$value->id)->where('parameter_id',$keyb)->where('sub_parameter_id',$keyc)->first();
                            // /* print_r($mode_details);
                            // die; */
                            // //echo $keyc . ", ". $mode_details->mode_id. "<br>";
                            // if(!is_null($mode_details->mode_id)){

                            
                            //     $mode_details = Mode::find($mode_details->mode_id);
                                
                            //     $selected_mode = $mode_details->name;
                            // }else {
                            //     $selected_mode = "";
                            // }
                            $repeater_param_data[$keyb][$keyc]['reason_type'] = $reason_type;
                            $repeater_param_data[$keyb][$keyc]['reason'] = $reason;
                            // $repeater_param_data[$keyb][$keyc]['mode'] = $selected_mode;
                            $repeater_param_data[$keyb][$keyc]['remark'] = $to_filter_row->remark;
                            $repeater_param_data[$keyb][$keyc]['scored']=$temp_result->where('parameter_id',$keyb)->where('sub_parameter_id',$keyc)->sum('score');
                            }
                        }
                    }

                $para_columns = "";
                $total_scored = 0;
                $total_scorable = 0;
                $is_critical = 0;
                $is_mode_value = "";
                
              
                
                foreach($repeater_param_data as $kk=>$vv){
                    foreach($vv as $kkb=>$vvb){
                        if($mode_status == 1){
                            $is_mode_value = "<td style='border:1px solid black;text-align: center;'>".$vvb['mode']."</td>"; 
                        }
                        if($vvb['observation'] == 'Critical') { $is_critical=1; }

                        $para_columns .= "<td style='border:1px solid black;text-align: center;'>".$vvb['observation']."</td>
                                            <td style='border:1px solid black;text-align: center;'>".$vvb['scored']."</td>
                                            <td style='border:1px solid black;text-align: center;'>".$vvb['scorable']."</td>".$is_mode_value."
                                            <td style='border:1px solid black;text-align: center;'>".$vvb['reason_type']."</td>
                                            <td style='border:1px solid black;text-align: center;'>".$vvb['reason']."</td>
                                            <td style='border:1px solid black;text-align: center;'>".$vvb['remark']."</td>
                        ";

                        $total_scored += $vvb['scored'];
                        $total_scorable += $vvb['scorable'];
                    }
                }

                if($is_critical == 1) { $scored_value = 0; } else { 
                    if($total_scored >= $total_scorable){
                        $scored_value = $total_scorable;
                    } else {
                        $scored_value = $total_scored;
                    }
                } 

                if($total_scorable > 100) { $scorable_value = 100; } else {
                    $scorable_value = $total_scorable;
                }

                $test .="<tr>
                                
                <td style='border:1px solid black;text-align: center;'>".$value->auditor->name."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->audit_date."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->partner_detail->name."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->location_data->name."</td>
                
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->call_id."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->agent_name."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->emp_id."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->doj."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->lob."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->language_2."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->case_id."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->call_time ."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->call_duration."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->call_type."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->call_sub_type."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->disposition."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->campaign_name."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->customer_name."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->phone_number."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->refrence_number."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->qrc_2."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->language_2."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->overall_summary."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->with_fatal_score_per."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->overall_score."</td>

                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->brand_name."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->circle."</td>

                <td style='border:1px solid black;text-align: center;'>".$basic['rca_type']."</td>
                <td style='border:1px solid black;text-align: center;'>".$basic['a_rca_mode']."</td>
                <td style='border:1px solid black;text-align: center;'>".$basic['a_rca_type_1']."</td>
                <td style='border:1px solid black;text-align: center;'>".$basic['a_rca_type_2']."</td>
                <td style='border:1px solid black;text-align: center;'>".$basic['a_rca_type_3']."</td>
                <td style='border:1px solid black;text-align: center;'>".$basic['a_rca_type_other']."</td>
                <td style='border:1px solid black;text-align: center;'>".$basic['b_rca_mode']."</td>
                <td style='border:1px solid black;text-align: center;'>".$basic['b_rca_type_1']."</td>
                <td style='border:1px solid black;text-align: center;'>".$basic['b_rca_type_2']."</td>
                <td style='border:1px solid black;text-align: center;'>".$basic['b_rca_type_3']."</td>
                <td style='border:1px solid black;text-align: center;'>".$basic['b_rca_type_other']."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->info_1."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->info_2."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->info_3."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->info_4."</td>
                <td style='border:1px solid black;text-align: center;'>".$value->raw_data->info_5."</td>
                ".$para_columns."
                <td style='border:1px solid black;text-align: center;'>".$value->error_code."</td>
                <td style='border:1px solid black;text-align: center;'>".$scored_value."</td>
                <td style='border:1px solid black;text-align: center;'>".$scorable_value."</td>
                </tr>";

                    //$basic['audit'] = $repeater_param_data;
    
                    $data[] = $basic;

            }




            $file = uniqid(rand(), true).".xls";
           
                
            require_once('HtmlConvert2021.php');
            $xls = new \HtmlExcel();
            $xls->addSheet($file, $test); 
            //$xls->headers();
            $ddddd = $xls->buildFile();
            //echo gettype($ddddd); die;
            $client = S3Client::factory(array(
                'key'    => 'AKIAUYUMYMBTWQOO3PWC',
                'secret' => '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',
                'region' => 'ap-south-1',
                'version'     => '2006-03-01',
            ));
            // Register the stream wrapper from an S3Client object
            $client->registerStreamWrapper();
            file_put_contents('s3://qmtool/raw_dump_reports/'.$file, $ddddd);
          
         
            $location = Storage::url('raw_dump_reports/').$file;
          
            
            /* $subject = "Raw Dump Report";
                $corelPath = 'client_wise_report.xls';
        
                $client = 'Mama Money';
                $process = 'QM TOOL';
                $qtl = 'Sumeet';
                $to_emails = ["shailendra.kumar@qdegrees.com"]; */
                 //$cc_emails = ["shailendra.kumar@qdegrees.com","sb@qdegrees.com","cbrajesh@qdegrees.com","rahul.gupta@qdegrees.com"];
               
                 // Mail::to('$to_emails)->send(new \App\Mail\ProductivityReportMail);
                /* Mail::to($to_emails)->send(new ClientWiseRawDumpReport(
                    [
                    // 'partner_name'=>'All',
                    'file'=>$corelPath,
                    'subject'=>$subject,
                    'client'=>$client,
                    'process'=>$process,
                    'qtl'=>$qtl,
                    'url'=>'https://simpliq.qdegrees.com/'
                    ]
                )); */

            $save = RawDumpCroneFilter::find($value_dump->id);
            $save->file_location = $location;
            $save->file_save_status = 1;
            $save->save();
             
           
            
        }
        
    }
}
