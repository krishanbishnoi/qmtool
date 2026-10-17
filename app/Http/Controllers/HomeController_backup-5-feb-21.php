<?php
namespace App\Http\Controllers;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '0');
use App\Audit;
use App\AuditParameterResult;
use App\AuditResult;
use App\Client;
use App\Exports\AgentQuartileExport;
use App\Partner;
use App\PartnerLocation;
use App\PartnersProcess;
use App\QmSheet;
use App\QmSheetParameter;
use App\QmSheetSubParameter;
use App\RawData;
use App\ReLabel;
use App\Reason;
use App\ReasonType;
use App\Auditcycle;
use App\User;
use Auth;
use Crypt;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\PartnersProcessSpoc;
use PDF;
use App\MonthTarget;
use App\Process;

class HomeController extends Controller
{   
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function developerTest(Request $request) {
        $d=RawData::where(['client_id'=>9,'process_id'=>32,'status'=>1])->get()->count();
        echo $d."<br>";  //die;
        //174 0,60,120,
        
        $data=RawData::where(['client_id'=>9,'process_id'=>32,'status'=>1])->offset(120)->limit(60)->get();
        //echo $data."<br>"; die;

        foreach ($data as $key => $value) {
            $raw=RawData::find($value->id);
            $raw->call_type="DNA";
            $raw->save();
            $getAudit=Audit::where('raw_data_id',$value->id)->first();
            $getAudit->qrc_2="DNA";
            $getAudit->save();
        }
        echo "data updated"; die;
    }

    public function save_png(Request $request){
        date_default_timezone_set ( 'Asia/Calcutta' );
        $today_date = date ( "Ymd", time () );
        //just a random name for the image file
        $random = rand(100, 1000);
        $filename=$today_date.$_POST['filename'];
        //$_POST[data][1] has the base64 encrypted binary codes. 
        //convert the binary to image using file_put_contents
        // $savefile = @file_put_contents("output/$random.png", base64_decode(explode(",", $_POST['data'])[1]));
        $savefile = @file_put_contents("pdf/image/".$filename, base64_decode(explode(",", $_POST['data'])[1]));
        chmod("pdf/image/".$filename,0777);
        //if the file saved properly, print the file name

       
        if($request->filename == 'paramerer_wise_compilance.png') {
               
                $request->session()->put('paramerer_wise_compilance', $filename);
            
        }

        if($request->filename == 'nonScoring.png') {

            $request->session()->put('nonScoring', $filename);

        }
        if($request->filename == 'agent_performance.png') {

            $request->session()->put('agent_performance', $filename);

        }
        if($request->filename == 'with_fatel.png') {

            $request->session()->put('with_fatel', $filename);

        }
        if($request->filename == 'without_fatel.png') {

            $request->session()->put('without_fatel', $filename);

        }
        if($request->filename == 'call_type_container.png') {

            $request->session()->put('call_type_container', $filename);

        }
        if($request->filename == 'disposition_wise_compliance.png') {

            $request->session()->put('disposition_wise_compliance', $filename);

        }
        if($request->filename == 'pareto_data.png') {

            $request->session()->put('pareto_data', $filename);

        }


        /* print_r($request->session()->all());
        die(); */

        if($savefile){
            echo $random; 
        }
    }

    public function test_html_new_get(Request $request) {

        if($request->isMethod('post')){

            
            
       // clearstatcache();
        //echo "<pre>"; print_r($request->all()); die;

        $dates = explode(",", $request->date);


        $start_date = date_to_db($dates[0]);
        $end_date = date_to_db($dates[1]);

       /*  echo $end_date;
        dd(); */

        if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198 || Auth::user()->id == 267 ) {
            $client_id=9;

        }

        if(Auth::user()->hasRole('partner-admin'))
        {
            if(Auth::user()->hasRole('partner-quality-head')){
                if(Auth::user()->id == 267) {
                    $client_id=9;
                } 
                if(Auth::user()->id == 307 || Auth::user()->id == 308 || Auth::user()->id == 291) {
                    $client_id=13;
                } 
                else {
                    $client_id = Auth::user()->spoc_detail->partner->client_id;
                }
                
            } else {
                if(Auth::user()->id == 195) {
                    $client_id=9;
                } else {
                    $client_id = Auth::user()->partner_admin_detail->client_id;
                }
                
            }
            

        }elseif(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head')||
                Auth::user()->hasRole('partner-quality-head'))
        {
            if(Auth::user()->id == 267) {
                $client_id =9;
            } else {
                $client_id = Auth::user()->spoc_detail->partner->client_id;
            }
            
        }elseif(Auth::user()->hasRole('client')){
        	if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198 ) {
        		$client_id=9;
            }
            else if(Auth::user()->id == 41) {
        		$client_id=1;
        	} else {
                if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
        		
        	}
            
        }     
        if(Auth::user()->id == 44) {
            $partner_id = 1;
            $location_id = 2;
        } else {
            if($request->partner_id == 'all') {
                if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {
                    $partner_id=32;
                } 

                else if(Auth::user()->id == 280 ) {
                    $partner_id=38;
                }

                else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                    $partner_id=40;
                }
                elseif(Auth::user()->id == 249 || Auth::user()->id == 250){
                    $partner_id=41;
                }

                elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                    $partner_id=44;
                }

                elseif(Auth::user()->id == 139) {
                    $partner_id=38;
                }

                elseif (Auth::user()->id == 195) {
                   $partner_id=39;
                }

                elseif (Auth::user()->id == 254) {
                   $partner_id=43;
                }

                elseif (Auth::user()->id == 267) {
                   $partner_id=45;
                }
                
                else {
                    $partner_id='%';
                }
                
            } else {
                $partner_id = $request->partner_id;
            }
            
            $location_id = $request->location_id;
        }

       
        /* echo $partner_id;
        dd(); */
        $process_id = $request->process_id;
        $lob = $request->lob; 
        // starts rebuttal 
        $rebuttal_data = [];

        /*$audit_data = Audit::where('client_id',$client_id)
       ->where('partner_id','like',$partner_id)
       ->where('process_id',$process_id)
       ->whereDate('audit_date',">=",$start_date)
       ->whereDate('audit_date',"<=",$end_date)
       ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
            $query->where('partner_location_id', 'like', $location_id);
            $query->where('lob', 'like', $lob);
        })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])
       ; */

        if(Auth::user()->id == 252 || Auth::user()->id == 269 || Auth::user()->id == 279 || Auth::user()->id == 283) {
            $client_id = 9;
            
            $audit_data = Audit::where('client_id',$client_id)
       			   ->whereIn('partner_id',array(38,39,43,45))
			       ->where('process_id',$process_id)
			       ->whereDate('audit_date',">=",$start_date)
			       ->whereDate('audit_date',"<=",$end_date)
			       ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
			            $query->where('partner_location_id', 'like', $location_id);
			            $query->where('lob', 'like', $lob);
			        })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])->get(); 

        }
        
         elseif (Auth::user()->id == 270 || Auth::user()->id == 271) {
        	 $client_id = 9;
        	 $audit_data = Audit::where('client_id',$client_id)
       			   ->whereIn('partner_id',array(38,45))
			       ->where('process_id',$process_id)
			       ->whereDate('audit_date',">=",$start_date)
			       ->whereDate('audit_date',"<=",$end_date)
			       ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
			            $query->where('partner_location_id', 'like', $location_id);
			            $query->where('lob', 'like', $lob);
			        })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])->get();           
        } else {
            if(Auth::user()->id == 41) {
        		$client_id=1;
        	}
             $audit_data = Audit::where('client_id',$client_id)
		       ->where('partner_id','like',$partner_id)
		       ->where('process_id',$process_id)
		       ->whereDate('audit_date',">=",$start_date)
		       ->whereDate('audit_date',"<=",$end_date)
		       ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
		            $query->where('partner_location_id', 'like', $location_id);
		            $query->where('lob', 'like', $lob);
		        })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])->get();
        }
        
        if($client_id == 9 || $client_id == 13) {
            if($request->date == "2020-04-01,2021-03-31"){
                if($request->partner_id == "all"){
               
                    $target = MonthTarget::
                    join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')
                    ->where('month_targets.client_id',$client_id)
                    
                    ->where('month_targets.process_id',$process_id)
                    ->where('month_targets.lob', 'like', $lob)
                    ->whereDate('auditcycles.start_date','>=', $start_date)
                    ->whereDate('auditcycles.end_date','<=', $end_date)
                    ->sum('month_targets.eq_audit_target_mtd'); 
               }else {
                    $target = MonthTarget::
                    join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')
                    ->where('month_targets.client_id',$client_id)
                    ->where('month_targets.partner_id',$partner_id)
                    ->where('month_targets.process_id',$process_id)
                    ->where('month_targets.lob', 'like', $lob)
                    ->whereDate('auditcycles.start_date','>=', $start_date)
                    ->whereDate('auditcycles.end_date','<=', $end_date)
                    ->sum('month_targets.eq_audit_target_mtd'); 
               }
            } else {
                if($request->partner_id == "all"){
               
                    $target = MonthTarget::
                    join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')
                    ->where('month_targets.client_id',$client_id)
                    
                    ->where('month_targets.process_id',$process_id)
                    ->where('month_targets.lob', 'like', $lob)
                    ->whereDate('auditcycles.start_date', $start_date)
                    ->whereDate('auditcycles.end_date', $end_date)
                    ->sum('month_targets.eq_audit_target_mtd'); 
               }else {
                    $target = MonthTarget::
                    join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')
                    ->where('month_targets.client_id',$client_id)
                    ->where('month_targets.partner_id',$partner_id)
                    ->where('month_targets.process_id',$process_id)
                    ->where('month_targets.lob', 'like', $lob)
                    ->whereDate('auditcycles.start_date', $start_date)
                    ->whereDate('auditcycles.end_date', $end_date)
                    ->sum('month_targets.eq_audit_target_mtd'); 
               }
            }
            
           
            
            
            
            if(gettype($target) == "NULL"){
                $coverage['target'] = 0;
                $coverage['achived'] = $audit_data->count();
                $coverage['achived_per'] = round(($audit_data->count()/450)*100);
            }else if($target == 0) {
               
                $coverage['target'] = $target;
                $coverage['achived'] = $audit_data->count();
                $coverage['achived_per'] = $audit_data->count();
            }
            else {
                $coverage['target'] = $target;
                $coverage['achived'] = $audit_data->count();
                $coverage['achived_per'] = round(($audit_data->count()/$target)*100);
          
            }
           
           
        }else {
            $coverage['target'] = 450;
            $coverage['achived'] = $audit_data->count();
            $coverage['achived_per'] = round(($audit_data->count()/450)*100);
        }
                                
        $final_data['partner_id']=$partner_id;
        $final_data['lob']=$request->lob; 
        $final_data['location_id']=$request->location_id;
        $final_data['process_id']=$request->process_id;
        $final_data['date']=$request->date;
        

        $final_data['user_id']=Auth::user()->id;
        $final_data['coverage'] = $coverage;

        $temp_total_rebuttal = 0;
        $temp_accepted_rebuttal = 0;
        $temp_rejected_rebuttal=0;

        foreach ($audit_data as $key => $value) {
            if($value->rebuttal_status > 0)
            {
                $temp_total_rebuttal += $value->audit_rebuttal_count;
                $temp_accepted_rebuttal += $value->audit_rebuttal_accepted->count();
                $temp_rejected_rebuttal += $value->audit_rebuttal_rejected->count();
            }
        }

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
            $rebuttal_data['rejected_per'] = round((($rebuttal_data['rejected']/$audit_data->count())*100));
        else
            $rebuttal_data['rejected_per'] = 0;

        $final_data['rebuttal'] = $rebuttal_data;
        // ends rebuttal 

        // Process Score
            $fatal_dialer_data['with_fatal_score'] = 0;
            $fatal_dialer_data['without_fatal_score'] = 0;
            
            $fatal_audit_count = $audit_data->where('is_critical',1)->count();

            // $fatal_audit_count = $audit_data->where('is_critical',0)->sum('overall_score');
            
            $without_fatal_audit_count = $audit_data->where('is_critical',0)->count();
            $fatal_audit_score_sum=0;
            $without_fatal_audit_score_sum=0;
            $scr=0;
            $sca=0;
            foreach ($audit_data as $key => $value) {
                
                foreach ($value->audit_parameter_result->where('is_non_scoring','!=',1) as $keyb => $valueb) {
                        
                        if($value->is_critical == 0) {
                            $scr += $valueb->with_fatal_score;
                        }  
                            //$sca+=$valueb->temp_weight;
                        $sca+=$valueb->temp_weight;    
                } 
                $without_fatal_audit_score_sum +=$value->overall_score;
            }
            if($audit_data->count())
            {
                $fatal_dialer_data['with_fatal_score'] = round(($scr/$sca)*100);
                
                $fatal_dialer_data['without_fatal_score'] = round(($without_fatal_audit_score_sum/$audit_data->count()));
            }else
            {
                $fatal_dialer_data['with_fatal_score']=0;
                $fatal_dialer_data['with_fatal_score']=0;
            }

            $final_data['fatal_dialer_data'] = $fatal_dialer_data;
            /* echo $fatal_dialer_data['with_fatal_score'];
            dd(); */
            //Fatal first row block
            $fatal_first_row_block['total_audits'] = $audit_data->count();
            $fatal_first_row_block['total_fatal_count_sub_parameter'] = 0;
            $fatal_first_row_block['total_fatal_audits'] = $fatal_audit_count;
            if($fatal_audit_count)
            $fatal_first_row_block['total_fatal_audit_per'] = round((($fatal_first_row_block['total_fatal_audits']/$fatal_first_row_block['total_audits'])*100));
            else
            $fatal_first_row_block['total_fatal_audit_per'] = 0;

            // added below in final_data
            //Fatal first row block

            // Parameter Wise Fatal Count & Score
            //get latest QM Sheet
            $latest_qm_sheet = QmSheet::where('client_id',$client_id)->where('process_id',$process_id)->orderBy('version','desc')->first();
            $latest_qm_sheet_id = $latest_qm_sheet->id;

            $all_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('is_non_scoring',0)->with('qm_sheet_sub_parameter')->get();

            $pwfcs = [];
            $pws;
            $params_list = [];
            $all_fatal_audits = $audit_data->where('is_critical',1);
            $count=1;
            foreach ($all_params as $key => $value) {
                $temp_params['counter'] = $count;
                $temp_params['fatal_count'] = 0;
                $temp_params['fail_count'] = 0;
                $temp_params['fatal_counted_score'] = 0;
                $temp_params['parameter'] = $value->parameter;

                $params_list[] = ucfirst($value->parameter);
                $paramSco=0;
                $paramScoble=0;
                foreach ($audit_data as $bkey => $bvalue) {
                    $temp_params['fatal_count'] += $bvalue->audit_parameter_result->where('parameter_id',$value->id)->where('is_critical',1)->count();
                    $temp_params['fail_count'] += $bvalue->audit_results->where('parameter_id',$value->id)->where('selected_option',2)->count();
                    // $temp_row = $bvalue->audit_parameter_result->where('parameter_id',$value->id)->where('is_critical',1)->first();
                    $temp_params['fatal_counted_score'] += $bvalue->audit_parameter_result->where('parameter_id',$value->id)->sum('with_fatal_score_per');
                    $paramSco+=$bvalue->audit_parameter_result->where('parameter_id',$value->id)->sum('with_fatal_score');
                    $paramScoble+=$bvalue->audit_parameter_result->where('parameter_id',$value->id)->sum('temp_weight');
                }   

                if($paramScoble != 0)
               // $temp_params['fatal_score'] = round(($temp_params['fatal_counted_score']/$audit_data->count()));
                    $temp_params['fatal_score'] = round(($paramSco/$paramScoble)*100);
                else
                $temp_params['fatal_score'] = 0;

                $pwfcs[] = $temp_params;

                // Parameter score 2
                $paramScore=0;
                $paramScorable=0;
                $temp_params['param_score_total'] = 0;
                foreach ($audit_data as $keyc => $valuec) {
                    $temp_params['param_score_total'] += $valuec->audit_parameter_result->where('parameter_id',$value->id)->sum('with_fatal_score_per');
                     $paramScore+=$valuec->audit_parameter_result->where('parameter_id',$value->id)->sum('with_fatal_score');
                    $paramScorable+=$valuec->audit_parameter_result->where('parameter_id',$value->id)->sum('temp_weight');
                }
                if($paramScorable != 0)
               // $temp_params['param_score'] = round(($temp_params['param_score_total']/$audit_data->count()));
                    $temp_params['param_score'] = round(($paramScore/$paramScorable)*100);
                else
                $temp_params['param_score'] = 0;
                // Parameter score 2

                $pws[] = $temp_params['param_score'];

                $count++;
            }
            $final_data['pwfcs'] = $pwfcs;
            // Parameter Wise Fatal Count & Score

            // Process Score

            // Parameter score 2
            $final_data['parameter_list'] = $params_list;
            $final_data['pws'] = $pws;
            // Parameter score 2

            // Disposition Wise Score
            $despo = [];

            $temp_all_unique_despos=[];
            foreach ($audit_data as $key => $value) {
                // get all unique despositions
                $temp_all_unique_despos[] = $value->raw_data->disposition;
            }

            $all_unique_despos = array_unique($temp_all_unique_despos);
            $all_unique_despos_counts = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_unique_despos_score_total = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_unique_despos_score = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_audit_counts=array();
            $all_scores=array();
            foreach ($audit_data as $key => $value) {
                if($temp_id = array_search($value->raw_data->disposition,$all_unique_despos,true))
                {   
                    
                    $all_unique_despos_counts[$temp_id] += 1;
                    $all_unique_despos_score_total[$temp_id] += $value->overall_score;
                }
            }

            $s=0;
            foreach ($all_unique_despos as $key => $value) {
                $all_audit_counts[$s]=Audit::where('client_id',$client_id)
                                       ->where('partner_id','like',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$value) {
                                            $query->where('partner_location_id', 'like', $location_id);  
                                            $query->where('disposition', 'like', $value);                              
                                        })->get()->count(); 
                $audit=Audit::where('client_id',$client_id)
                                       ->where('partner_id','like',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$value) {
                                            $query->where('partner_location_id', 'like', $location_id); 
                                            $query->where('disposition', 'like', $value);                                  
                                        })->withCount(['audit_results','audit_parameter_result'])
                                       ->get();
                $score=0;
                $scorable=0;
                foreach ($audit as $key => $value) {                
                    foreach ($value->audit_parameter_result->where('is_non_scoring',0) as $keyb => $valueb) {
                    	if($value->is_critical == 0) {
                    		$score += $valueb->with_fatal_score;
                    	}
                        
                        $scorable += $valueb->temp_weight;
                    }
                }
                if($scorable != 0) {
                    $all_scores[$s] = round(($score/$scorable)*100);
                }else {
                    $all_scores[$s] = 0;
                }
                $s++;   
                /*if($all_unique_despos_counts[$key])
                    $all_unique_despos_score[$key] = round(($all_unique_despos_score_total[$key]/$all_unique_despos_counts[$key]));
                else
                    $all_unique_despos_score[$key] = 0;*/
            }

            $despo['all_unique_despos'] = array_values($all_unique_despos);
            $despo['all_unique_despos_counts'] = array_values($all_audit_counts);
            $despo['all_unique_despos_score'] = array_values($all_scores);

            $final_data['disposition'] = $despo;
            // Disposition Wise Score

            //Sub Parameter Wise Score
            //set param-subparam array
            $tmp_params=[];
            foreach ($all_params as $key => $value) {
                $temp_sps = [];
                $temp_sps['parameter_id'] = $value->id;
                $temp_sps['parameter'] = ucfirst($value->parameter);
                $temp_sps_list = [];
                $temp_sps_color = [];
                //Sub parameter details
                $temp_sps_detail = [];
                foreach ($value->qm_sheet_sub_parameter as $keyb => $valueb) {
                    $temp_sps_list[] = ucfirst(strtolower($valueb->sub_parameter));
                    
                    if($valueb->critical == 1){
                        $temp_sps_color[] = "rgb(65, 105, 225)";
                    }else {
                        $temp_sps_color[] = "grey";
                    }
                    $temp_sps_detail[] = str_replace('"', '', $valueb->details);
                    $temp_sps['sub_p_id'][] = $valueb->id;
                    $temp_sps['sp_p_score'][] = 0;
                    $temp_sps['sp_p_weight'][] = 0;
                    $temp_sps['sp_p_obtained'][] = 0;

                    $temp_sps['sp_p_fatal_count'][] = 0;
                }
                $temp_sps['temp_sps_color'] = $temp_sps_color;
                $temp_sps['temp_sps_list'] = $temp_sps_list;
                $temp_sps['temp_sps_detail'] = $temp_sps_detail;
                $tmp_params[] = $temp_sps;
            }
            // get all audit results 
            foreach ($audit_data as $key => $value) {
                foreach ($tmp_params as $keyb => $valueb) {
                    foreach ($valueb['sub_p_id'] as $keyc => $valuec) {

                        $tmp_params[$keyb]['sp_p_obtained'][$keyc] += $value->audit_results->where('parameter_id',$valueb['parameter_id'])->where('sub_parameter_id',$valuec)->sum('score');

                        $tmp_params[$keyb]['sp_p_weight'][$keyc] += $value->audit_results->where('parameter_id',$valueb['parameter_id'])->where('sub_parameter_id',$valuec)->sum('after_audit_weight');

                        $tmp_params[$keyb]['sp_p_fatal_count'][$keyc] += $value->audit_results->where('parameter_id',$valueb['parameter_id'])->where('sub_parameter_id',$valuec)->where('is_critical',1)->count();
                    }
                }
            }

            //settle down the score from sum
            foreach ($tmp_params as $key => $value) {
                foreach ($value['sp_p_score'] as $keyb => $valueb) {


                    $fatal_first_row_block['total_fatal_count_sub_parameter'] += $tmp_params[$key]['sp_p_fatal_count'][$keyb];

                    if($tmp_params[$key]['sp_p_weight'][$keyb])
                    {
                        $tmp_params[$key]['sp_p_score'][$keyb] = round(($tmp_params[$key]['sp_p_obtained'][$keyb]/$tmp_params[$key]['sp_p_weight'][$keyb])*100,0);
                    }else
                    {
                        $tmp_params[$key]['sp_p_score'][$keyb] = 0;
                    }
                }
            }
            $final_data['fatal_first_row_block'] = $fatal_first_row_block;
            $final_data['spws'] = $tmp_params;
            //Sub Parameter Wise Score


            // starts QRC
            $getUniqueCallType=array();
            $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->where('process_id',$process_id)->orderby('id','asc')->get();
           // echo "<pre>"; print_r($callType); die;
            $query_w="";
            $request_w="";
            $complain_w="";
            $s=1;
            foreach ($callType as $key => $value) {
                if($s == 1) {
                    $query_w=$value->call_type;
                }
                if($s == 2) {
                    $request_w=$value->call_type;
                }
                if($s == 3) {
                    $complain_w=$value->call_type;
                }
                $getUniqueCallType[]=$value->call_type;
                $s++;
            }     

            $temp_qrc_bam['query_count']=0;
            $temp_qrc_bam['query_fatal_count']=0;
            $temp_qrc_bam['query_fatal_score_sum'] =0;
            $temp_qrc_bam['request_count']=0;
            $temp_qrc_bam['request_fatal_count']=0;
            $temp_qrc_bam['request_fatal_score_sum'] =0;
            $temp_qrc_bam['complaint_count']=0;
            $temp_qrc_bam['complaint_fatal_count']=0;
            $temp_qrc_bam['complaint_fatal_score_sum'] =0;
            foreach($audit_data as $d) {
                if($d->raw_data->call_type == $query_w) {
                   $temp_qrc_bam['query_count']+=1; 
                   $temp_qrc_bam['query_fatal_score_sum']+=$d->with_fatal_score_per;
                }
                if($d->raw_data->call_type == $query_w && $d->is_critical == 1) {
                   $temp_qrc_bam['query_fatal_count']+=1; 
                }
                //request
                if($d->raw_data->call_type == $request_w) {
                   $temp_qrc_bam['request_count']+=1; 
                   $temp_qrc_bam['request_fatal_score_sum']+=$d->with_fatal_score_per;
                }
                if($d->raw_data->call_type == $request_w && $d->is_critical == 1) {
                   $temp_qrc_bam['request_fatal_count']+=1;
                }
                // Complaint
                if($d->raw_data->call_type == $complain_w) {
                   $temp_qrc_bam['complaint_count']+=1; 
                   $temp_qrc_bam['complaint_fatal_score_sum']+=$d->with_fatal_score_per;
                }
                if($d->raw_data->call_type == $complain_w && $d->is_critical == 1) {
                   $temp_qrc_bam['complaint_fatal_count']+=1; 
                }
            }

            $temp_qrc['query_count'] = $audit_data->where('qrc_2',$query_w)->count();
            
            $temp_qrc['query_fatal_count'] = $audit_data->where('qrc_2',$query_w)->where('is_critical',1)->count();
            $temp_qrc['query_fatal_score_sum'] = $audit_data->where('qrc_2',$query_w)->sum('with_fatal_score_per');

            if($temp_qrc['query_count'])
            $temp_qrc['query_fatal_score'] = round(($temp_qrc['query_fatal_score_sum']/$temp_qrc['query_count']));
            else
            $temp_qrc['query_fatal_score'] = 0;

            $temp_qrc['request_count'] = $audit_data->where('qrc_2',$request_w)->count();
            $temp_qrc['request_fatal_count'] = $audit_data->where('qrc_2',$request_w)->where('is_critical',1)->count();
            $temp_qrc['request_fatal_score_sum'] = $audit_data->where('qrc_2',$request_w)->sum('with_fatal_score_per');

            if($temp_qrc['request_count'])
            $temp_qrc['request_fatal_score'] = round(($temp_qrc['request_fatal_score_sum']/$temp_qrc['request_count']));
            else
            $temp_qrc['request_fatal_score'] =0;

            $temp_qrc['complaint_count'] = $audit_data->where('qrc_2',$complain_w)->count();
            $temp_qrc['complaint_fatal_count'] = $audit_data->where('qrc_2',$complain_w)->where('is_critical',1)->count();
            $temp_qrc['complaint_fatal_score_sum'] = $audit_data->where('qrc_2',$complain_w)->sum('with_fatal_score_per');

            if($temp_qrc['complaint_count'])
            $temp_qrc['complaint_fatal_score'] = round(($temp_qrc['complaint_fatal_score_sum']/$temp_qrc['complaint_count']));
            else
            $temp_qrc['complaint_fatal_score'] = 0;



            $qrc_data['audit_count']=[     
                $temp_qrc['query_count'],
                $temp_qrc['request_count'],
                $temp_qrc['complaint_count']
            ];
            $qrc_data['fatal_count']=[
                $temp_qrc['query_fatal_count'],
                $temp_qrc['request_fatal_count'],
                $temp_qrc['complaint_fatal_count']
            ];

            $qrc_data['score']=[
                $temp_qrc['query_fatal_score'],
                $temp_qrc['request_fatal_score'],
                $temp_qrc['complaint_fatal_score']
            ];

            if($temp_qrc_bam['query_count'])
            $temp_qrc_bam['query_fatal_score'] = round(($temp_qrc_bam['query_fatal_score_sum']/$temp_qrc_bam['query_count']));
            else
            $temp_qrc_bam['query_fatal_score'] = 0;

            if($temp_qrc_bam['request_count'])
            $temp_qrc_bam['request_fatal_score'] = round(($temp_qrc_bam['request_fatal_score_sum']/$temp_qrc_bam['request_count']));
            else
            $temp_qrc_bam['request_fatal_score'] = 0; 

            if($temp_qrc_bam['complaint_count'])
            $temp_qrc_bam['complaint_fatal_score'] = round(($temp_qrc_bam['complaint_fatal_score_sum']/$temp_qrc_bam['complaint_count']));
            else
            $temp_qrc_bam['complaint_fatal_score'] = 0; 

            $qrc_data_bam['audit_count']=[     
                $temp_qrc_bam['query_count'],
                $temp_qrc_bam['request_count'],
                $temp_qrc_bam['complaint_count']
            ];
            $qrc_data_bam['fatal_count']=[
                $temp_qrc_bam['query_fatal_count'],
                $temp_qrc_bam['request_fatal_count'],
                $temp_qrc_bam['complaint_fatal_count']
            ];

            $qrc_data_bam['score']=[
                $temp_qrc_bam['query_fatal_score'],
                $temp_qrc_bam['request_fatal_score'],
                $temp_qrc_bam['complaint_fatal_score']
            ];
           
               
            $final_data['qrc'] = $qrc_data;
            $final_data['qrc_bam'] = $qrc_data_bam;
            $final_data['call_type']=[
                $query_w,$request_w,$complain_w
            ];
            //echo "<pre>"; print_r($final_data['qrc']); die;
            $final_data['client_id']=$client_id;
            
           
            // ends QRC

            // quartile starts QRC
            $all_agents = [];
            foreach ($audit_data as $key => $value) {
                $all_agents[] = $value->raw_data->emp_id;
            }

            $all_unique_agents = array_unique($all_agents);
            $all_audit_score=[];
            $quartile_audit_count=array();
            $quartile_audit_count[0] = 0;
            $quartile_audit_count[1] = 0;
            $quartile_audit_count[2] = 0;
            $quartile_audit_count[3] = 0;
            foreach ($all_unique_agents as $key => $value) {

                $agent_all_audit_score = Audit::where('client_id',$client_id)
                                       ->where('partner_id','like',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($value,$location_id) {
                                            $query->where('emp_id', 'like', $value);
                                            $query->where('partner_location_id', 'like', $location_id);  
                                        })->withCount(['audit_parameter_result'])->get();
                $scored=0;
                $scorable=0;
                $ag_count=0;
                foreach ($audit_data as $key => $value1) {
                if($value1->raw_data->emp_id == $value)  {
                    $ag_count+=1;
                    foreach ($value1->audit_parameter_result->where('is_non_scoring','!=',1) as $keyb => $valueb) {
                        if($value1->is_critical == 0) {
                            $scored += $valueb->with_fatal_score;
                        }                         
                        $scorable += $valueb->temp_weight;                   
                    }
                }              
                     
                    // $scored += $value1->with_fatal_score_per;
                    //$scorable += 1;               
                }
                if($scorable == 0) {
                    $score = 0;
                } else {
                    $score = round(($scored/$scorable)*100); 
                }

                if($score >= 0 && $score < 41){ 
                    $quartile_audit_count[0] +=$ag_count;
                }
                if($score >= 41  && $score < 61) {
                    $quartile_audit_count[1] +=$ag_count;
                }
                if($score >= 61  && $score < 81) {
                    $quartile_audit_count[2] +=$ag_count;
                }
                if($score > 80) {
                    $quartile_audit_count[3] +=$ag_count;
                }
                
            
            $all_audit_score[] = ["name"=>$value,
                                      "audit_count"=>$ag_count,
                                      "with_fatal_score_per_sum"=>$agent_all_audit_score->sum('with_fatal_score_per'),
                                      "score"=>$score];
            }

            // echo "<pre>";
            // print_r($all_audit_score); die;

            // check the fall in of all agents
            $quartile_data[0] = 0;
            $quartile_data[1] = 0;
            $quartile_data[2] = 0;
            $quartile_data[3] = 0;

            foreach ($all_audit_score as $key => $value) {
                if($value['score'] >= 0 && $value['score'] < 41)
                    $quartile_data[0] += 1;
                else if($value['score'] >= 41  && $value['score'] < 61)
                    $quartile_data[1] += 1;
                else if($value['score'] >= 61  && $value['score'] < 81)
                    $quartile_data[2] +=1;
                else if($value['score'] > 80)
                    $quartile_data[3] += 1;
            }


            $final_data['quartile'] = $quartile_data;
            $final_data['quartile_au_count']=$quartile_audit_count;
            $totalCount=$audit_data->count();
            $quar_audit_contribution[0] = 0;
            $quar_audit_contribution[1] = 0;
            $quar_audit_contribution[2] = 0;
            $quar_audit_contribution[3] = 0; 
            if($totalCount != 0) {
               $quar_audit_contribution[0]=round(($quartile_audit_count[0]/$totalCount)*100);
               $quar_audit_contribution[1]=round(($quartile_audit_count[1]/$totalCount)*100); 
               $quar_audit_contribution[2]=round(($quartile_audit_count[2]/$totalCount)*100);
               $quar_audit_contribution[3]=round(($quartile_audit_count[3]/$totalCount)*100);
            }

            $final_data['quartile_au_contri']=$quar_audit_contribution;
            
            
            //print_r($final_data['quartile_au_count']); die;
            $final_data['user_id']=Auth::user()->id;
            // quartile ends QRC

            //non scoring sub parameter
            //get para - sub para
            $non_scoring_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('is_non_scoring',1)->with('qm_sheet_sub_parameter')->get();
            $non_scoring_params_name='';
            if($non_scoring_params->isNotEmpty())
            {
                        $non_scoring_params_name = $non_scoring_params[0]->parameter;
                        $non_scoring_params = $non_scoring_params[0]->qm_sheet_sub_parameter;
            }

            $temp_non_scoring_params=[];
            foreach ($non_scoring_params as $key => $value) {

                $temp_non_scoring_params[] = ['non_scoring_option_group'=>$value->non_scoring_option_group,'name'=>$value->sub_parameter,'id'=>$value->id,'count'=>[0,0,0]];
            }

            foreach ($audit_data as $key => $value) {

                foreach ($temp_non_scoring_params as $keyb => $valueb) {

                $a_result = $value->audit_results->where('sub_parameter_id',$valueb['id']);
                $temp_a_result=[];
                foreach ($a_result as $keyx => $valuex) {
                    $temp_a_result = $valuex;
                }

                if($temp_a_result)
                switch ($valueb['non_scoring_option_group']) {
                    case 1:
                    {  
                        if($temp_a_result->selected_option==1)
                            $temp_non_scoring_params[$keyb]['count'][0] +=1;
                        else 
                            $temp_non_scoring_params[$keyb]['count'][1] +=1;

                        break;
                    }
                    case 2:
                    {  
                        if($temp_a_result->selected_option==3)
                            $temp_non_scoring_params[$keyb]['count'][0] +=1;
                        else 
                            $temp_non_scoring_params[$keyb]['count'][1] +=1;


                        break;
                    }
                    case 3:
                    {  
                       if($temp_a_result->selected_option==6 || $temp_a_result->selected_option==7)
                            $temp_non_scoring_params[$keyb]['count'][0] +=1;
                        else 
                            $temp_non_scoring_params[$keyb]['count'][1] +=1;


                        break;
                    }
                    case 4:
                    {  

                        if($temp_a_result->selected_option==10 || $temp_a_result->selected_option==11)
                            $temp_non_scoring_params[$keyb]['count'][0] +=1;
                        else 
                            $temp_non_scoring_params[$keyb]['count'][1] +=1;
                        
                        break;
                    }
                    
                    default:
                        # code...
                        break;
                }
                    # code...
                }
            }
            
            $temp_all_non_scoring_sub_parameter_list=[];
            $temp_all_non_scoring_sub_parameter_data=[];
            foreach ($temp_non_scoring_params as $key => $value) {

                if(($temp_non_scoring_params[$key]['count'][0]+$temp_non_scoring_params[$key]['count'][1])!=0)
                {
                    $temp_non_scoring_params[$key]['count'][2] = round(($temp_non_scoring_params[$key]['count'][0]/($temp_non_scoring_params[$key]['count'][0]+$temp_non_scoring_params[$key]['count'][1]))*100);    
                }else
                {
                    $temp_non_scoring_params[$key]['count'][2]=0;
                }
                

                $temp_all_non_scoring_sub_parameter_list[] = $value['name'];
                $temp_all_non_scoring_sub_parameter_data[] = $temp_non_scoring_params[$key]['count'][2];


            }   

            $final_data['non_scoring_params'] = ["list"=>$temp_all_non_scoring_sub_parameter_list,"score"=>$temp_all_non_scoring_sub_parameter_data,"names"=>$non_scoring_params_name];
            //non scoring sub parameter

            // starts process stats
            $fail_count=0;
            $critical_count=0;
            $na_count=0;
            foreach ($audit_data as $key => $value) {
                $fail_count += $value->audit_results->where('selected_option',2)->count();
                $critical_count += $value->audit_results->where('selected_option',3)->count();
                $na_count += $value->audit_results->where('selected_option',4)->count();
            }

            $qm_sheet_sp_count = QmSheetSubParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('non_scoring_option_group',0)->count();
            $audit_sp_count = $audit_data->count()*$qm_sheet_sp_count;

            if($audit_data->count())
            {
                $process_stats['dpu'] = round((($fail_count+$critical_count) / $audit_data->count()),2);
                $process_stats['dpo'] = round((($fail_count+$critical_count) / ($audit_sp_count-$na_count)),2);
                $process_stats['dpmo'] = $process_stats['dpo']*1000000;

                $process_stats['ppm'] = round(($fatal_audit_count/$audit_data->count())*1000000,2);

                $process_stats['fty'] = ($audit_data->where('is_critical',0)->count()/$audit_data->count());
            }
            else
            {
                $process_stats['dpu'] =0;
                $process_stats['dpo'] = 0;
                $process_stats['dpmo'] = 0;
                $process_stats['ppm'] = 0;
                $process_stats['fty']=0;
            }




            $final_data['process_stats'] = $process_stats;
            // ends process stats


            // pareto data
            $pareto_audit_result = AuditResult::whereHas('audit', function (Builder $query) use ($partner_id,$process_id,$start_date,$end_date,$location_id) {
                        $query->where('partner_id','like',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                        });
                    })->with('reason')->get();

        $all_uni_reaons=[];
        $all_uni_reaons_a=[];
        $all_uni_reaons_b=[];
        $all_uni_reaons_c=[];
        $all_uni_reaons_d=[];
        $all_uni_reaons_e=[];
        foreach ($pareto_audit_result as $key => $value) {

            if($value->reason)
            $all_uni_reaons_a[$value->reason_id]=['count'=>0,'name'=>$value->reason->name];
        }

        //get count
        foreach ($all_uni_reaons_a as $key => $value) {
            $count = $pareto_audit_result->where('reason_id',$key)->count();
            $all_uni_reaons_a[$key]['count'] = $count;
        }

        usort($all_uni_reaons_a, function($a, $b) {
            return $b['count'] - $a['count'];
        });

        $runningSum = 0;
        foreach ($all_uni_reaons_a as $kk=>$vv) {
            $runningSum += $vv['count'];
            $all_uni_reaons_b[$kk] = $vv['count'];
            $all_uni_reaons_c[$kk] = $runningSum;
            $all_uni_reaons_d[$kk] = $vv['name'];
        }

        
        foreach ($all_uni_reaons_c as $kk=>$vv) {
            $all_uni_reaons_e[$kk] = (round(($vv/$runningSum)*100,2));
        }

        $final_data['pareto_data']['count'] = $all_uni_reaons_b;
        $final_data['pareto_data']['per'] = $all_uni_reaons_e;
        $final_data['pareto_data']['reasons'] = $all_uni_reaons_d;
            // pareto data
        /* echo "<pre>";
        print_r($final_data['pareto_data']['per']);
        dd(); */

       


            if(Auth::user()->hasRole('partner-admin'))
            {
                if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {
                    $all_partners = Partner::select('name','id')->where('id',32)->get();
                }  
                else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                    $all_partners = Partner::select('name','id')->where('id',40)->get();
                }
                elseif(Auth::user()->id == 139) {
                    $all_partners = Partner::select('name','id')->where('id',38)->get();
                }
                elseif(Auth::user()->id == 270 || Auth::user()->id == 271 ||  Auth::user()->id == 280) {
                   $all_partners = Partner::select('name','id')->where('id',38)->orwhere('id',45)->get(); 
                }
                elseif(Auth::user()->id == 282 ) {
                    $all_partners = Partner::select('name','id')->where('id',39)->orwhere('id',43)->get(); 
                 }
                elseif(Auth::user()->id == 195) {
                    $all_partners = Partner::select('name','id')->where('id',39)->get();
                }
                elseif(Auth::user()->id == 254) {
                    $all_partners = Partner::select('name','id')->where('id',43)->get();
                }
                elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                    $all_partners = Partner::select('name','id')->where('id',41)->get();
                }
                elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {
                    $a=array(38,45,43,39);
                    $all_partners = Partner::select('name','id')->whereIn('id',$a)->get();
                }
                elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                    $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
                }
                elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                    $all_partners = Partner::select('name','id')->where('id',44)->get();
                }
                elseif (Auth::user()->id == 267) {
                   
                   $all_partners = Partner::select('name','id')->where('id',45)->get();
                } 
                elseif (Auth::user()->id == 307 || Auth::user()->id == 308) {
                   
                   $all_partners = Partner::select('name','id')->where('id',48)->get();
                } 
                else {
                $all_partners = Partner::select('name','id')->where('id',Auth::user()->partner_admin_detail->id)->get(); 
                } 
            }elseif(Auth::user()->hasRole('partner-training-head')||
                    Auth::user()->hasRole('partner-operation-head'))
                    
            {   
                if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {
                    $all_partners = Partner::select('name','id')->where('id',32)->get();
                }  
                else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                    $all_partners = Partner::select('name','id')->where('id',40)->get();
                }



                elseif(Auth::user()->id == 139) {
                    $all_partners = Partner::select('name','id')->where('id',38)->get();
                }
                elseif(Auth::user()->id == 195) {
                    $all_partners = Partner::select('name','id')->where('id',39)->get();
                }
                elseif(Auth::user()->id == 254) {
                    $all_partners = Partner::select('name','id')->where('id',43)->get();
                }
                elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                    $all_partners = Partner::select('name','id')->where('id',41)->get();
                }
                elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {
                    $a=array(38,45,43,39);
                    $all_partners = Partner::select('name','id')->whereIn('id',$a)->get();
                }
                elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                    $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
                }
                elseif(Auth::user()->id == 270 || Auth::user()->id == 271 ||  Auth::user()->id == 280) {
                    $all_partners = Partner::select('name','id')->where('id',38)->orwhere('id',45)->get(); 
                 }
                 elseif(Auth::user()->id == 282 ) {
                     $all_partners = Partner::select('name','id')->where('id',39)->orwhere('id',43)->get(); 
                  }
                elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                    $all_partners = Partner::select('name','id')->where('id',44)->get();
                } 
                elseif (Auth::user()->id == 267) {
                   
                   $all_partners = Partner::select('name','id')->where('id',45)->get();
                } 
                else {
                $all_partners = Partner::where('id',Auth::user()->spoc_detail->partner_id)->pluck('name','id'); 
                } 
            }elseif( Auth::user()->hasRole('partner-quality-head') ){



                $partner = PartnersProcessSpoc::where('user_id',Auth::user()->id)->get();  

                $partnerid =  $partner[0]['partner_id']; 
                if (Auth::user()->id == 267) {
                   
                   $all_partners = Partner::where('id',45)->get();
                } else {
                    $all_partners = Partner::where('id',$partnerid)->get(); 
                }            

            }elseif(Auth::user()->hasRole('client')){        
                if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {
                	$all_partners = Partner::select('name','id')->where('id',32)->get();
                }  elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                    $all_partners = Partner::select('name','id')->where('id',40)->get();
                }
                elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                    $all_partners = Partner::select('name','id')->where('id',41)->get();
                }
                elseif(Auth::user()->id == 279  || Auth::user()->id == 283){
                    $a=array(38,45,43,39);
                    $all_partners = Partner::select('name','id')->whereIn('id',$a)->get();
                }
                elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                    $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
                }
                elseif(Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 280) {
                    $all_partners = Partner::select('name','id')->where('id',38)->orwhere('id',45)->get(); 
                 }
                 elseif(Auth::user()->id == 282 ) {
                     $all_partners = Partner::select('name','id')->where('id',39)->orwhere('id',43)->get(); 
                  }
                elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                    $all_partners = Partner::select('name','id')->where('id',44)->get();
                }
                elseif(Auth::user()->id == 139) {
                    $all_partners = Partner::select('name','id')->where('id',38)->get();
                }
                elseif (Auth::user()->id == 195) {
                     $all_partners = Partner::select('name','id')->where('id',39)->get();
                }
                elseif (Auth::user()->id == 254) {
                     $all_partners = Partner::select('name','id')->where('id',43)->get();
                }
                elseif(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
                	$all_partners = Partner::select('name','id')->where('client_id',9)->get();
                }
                else {
                    if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
                	$all_partners = Partner::select('name','id')->where('client_id',$client_id)->get();
                }   
            }

            if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
                $all_partners = Partner::select('name','id')->where('client_id',9)->get();
            }
            if (Auth::user()->id == 41) {
                   
                $all_partners = Partner::select('name','id')->where('id',1)->get();
            } 
              /*  echo $final_data['fatal_dialer_data']['with_fatal_score'];
               dd(); */
           /* echo $final_data['fatal_first_row_block']['total_fatal_count_sub_parameter'];
            dd(); */

            return view('dashboards.demo_dash',compact('all_partners','final_data'));
        }else {

                if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
                    $client_list=Client::where('id',1)->orwhere('id',9)->get();
                } else {
                    $client_list=array();
                }
           /*  echo "hii";
            dd(); */
            if(Auth::user()->hasRole('partner-admin'))
            {   
               
                if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {
                    $all_partners = Partner::select('name','id')->where('id',32)->get();
                }  
                else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                    $all_partners = Partner::select('name','id')->where('id',40)->get();
                }
                elseif(Auth::user()->id == 139) {
                    $all_partners = Partner::select('name','id')->where('id',38)->get();
                }
                elseif(Auth::user()->id == 195) {
                    $all_partners = Partner::select('name','id')->where('id',39)->get();
                }
                elseif(Auth::user()->id == 254) {
                    $all_partners = Partner::select('name','id')->where('id',43)->get();
                }
                elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                    $all_partners = Partner::select('name','id')->where('id',41)->get();
                }
                elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {
                    $a=array(38,45,43,39);
                    $all_partners = Partner::select('name','id')->whereIn('id',$a)->get();
                }
                elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                    $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
                }
                elseif(Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 280) {
                    $all_partners = Partner::select('name','id')->where('id',38)->orwhere('id',45)->get(); 
                 }
                 elseif(Auth::user()->id == 282 ) {
                     $all_partners = Partner::select('name','id')->where('id',39)->orwhere('id',43)->get(); 
                  }
                elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                    $all_partners = Partner::select('name','id')->where('id',44)->get();
                } else {
                    if(Auth::user()->id == 244){
                        $all_partners = Partner::select('name','id')->where('id',Auth::user()->partner_admin_detail->id)->get();
                    }
                    if(Auth::user()->id == 307 || Auth::user()->id == 308){
                        $all_partners = Partner::select('name','id')->where('id',48)->get();
                    }
                    else {
                        
                        $all_partners = Partner::select('name','id')->where('id',Auth::user()->partner_admin_detail->id)->get();
                    }
                    
                }  
                
            }elseif(Auth::user()->hasRole('partner-training-head')||
                    Auth::user()->hasRole('partner-operation-head'))
                    
            {
                if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {
                    $all_partners = Partner::select('name','id')->where('id',32)->get();
                }  
                else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                    $all_partners = Partner::select('name','id')->where('id',40)->get();
                }
                elseif(Auth::user()->id == 139) {
                    $all_partners = Partner::select('name','id')->where('id',38)->get();
                }
                elseif(Auth::user()->id == 195) {
                    $all_partners = Partner::select('name','id')->where('id',39)->get();
                }
                elseif(Auth::user()->id == 254) {
                    $all_partners = Partner::select('name','id')->where('id',43)->get();
                }
                elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                    $all_partners = Partner::select('name','id')->where('id',41)->get();
                }
                elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {
                    $a=array(38,45,43,39);
                    $all_partners = Partner::select('name','id')->whereIn('id',$a)->get();
                }
                elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                    $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
                }
                elseif(Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 280) {
                    $all_partners = Partner::select('name','id')->where('id',38)->orwhere('id',45)->get(); 
                 }
                 elseif(Auth::user()->id == 282 ) {
                     $all_partners = Partner::select('name','id')->where('id',39)->orwhere('id',43)->get(); 
                  }
                elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                    $all_partners = Partner::select('name','id')->where('id',44)->get();
                } else {
                $all_partners = Partner::where('id',Auth::user()->spoc_detail->partner_id)->pluck('name','id'); 
                } 
                /* print_r($all_partners);
                dd(); */
            }elseif( Auth::user()->hasRole('partner-quality-head') ){



                $partner = PartnersProcessSpoc::where('user_id',Auth::user()->id)->get();  

                $partnerid =  $partner[0]['partner_id'];    
                if (Auth::user()->id == 267) {
                   
                   $all_partners = Partner::select('name','id')->where('id',45)->get();
                } else {

                    $all_partners = Partner::where('id',$partnerid)->get(); 
                }

            

            }elseif(Auth::user()->hasRole('client')){
                if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {
                	$all_partners = Partner::select('name','id')->where('id',32)->get();
                }  
                else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                    $all_partners = Partner::select('name','id')->where('id',40)->get();
                }
                elseif(Auth::user()->id == 139) {
                    $all_partners = Partner::select('name','id')->where('id',38)->get();
                }
                elseif(Auth::user()->id == 195) {
                    $all_partners = Partner::select('name','id')->where('id',39)->get();
                }
                elseif(Auth::user()->id == 254) {
                    $all_partners = Partner::select('name','id')->where('id',43)->get();
                }
                elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                    $all_partners = Partner::select('name','id')->where('id',41)->get();
                }
                elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {
                    $a=array(38,45,43,39);
                    $all_partners = Partner::select('name','id')->whereIn('id',$a)->get();
                }
                elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                    $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
                }
                elseif(Auth::user()->id == 270 || Auth::user()->id == 271 ||  Auth::user()->id == 280) {
                    $all_partners = Partner::select('name','id')->where('id',38)->orwhere('id',45)->get(); 
                 }
                 elseif(Auth::user()->id == 282 ) {
                     $all_partners = Partner::select('name','id')->where('id',39)->orwhere('id',43)->get(); 
                  }
                elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                    $all_partners = Partner::select('name','id')->where('id',44)->get();
                }
                elseif(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
	                $client_id=9;
	                $all_partners = Partner::select('name','id')->where('client_id',9)->get();
            	}
                else {
                    if(Auth::user()->client_detail) {
                        $client_id = Auth::user()->client_detail->client_id;
                    } else {
                        $client_id = Auth::user()->parent_client;
                    }
                	$all_partners = Partner::select('name','id')->where('client_id',$client_id)->get();
                }       
            }

            if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198 || Auth::user()->id == 267 ) {
                $client_id=9;
                $all_partners = Partner::select('name','id')->where('client_id',9)->get();
            }
            if (Auth::user()->id == 267) {
                   
                $all_partners = Partner::select('name','id')->where('id',45)->get();
            } 

            if (Auth::user()->id == 41) {
                   
                $all_partners = Partner::select('name','id')->where('id',1)->get();
            } 
               
           

            $final_data = 0;
            //$final_data['user_id']=Auth::user()->id;
            /* print_r($all_partners);
            dd(); */
            return view('dashboards.demo_dash',compact('all_partners','final_data','client_list','client_id'));
        }
        

    }

    public function test_html_new(Request $request) {
        $dates = explode("-", $request->date);        
        $start_date = date_to_db($dates[0]);
        $end_date = date_to_db($dates[1]);
 
        if(Auth::user()->hasRole('partner-admin'))
        {
            $client_id = Auth::user()->partner_admin_detail->client_id;            
        }elseif(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head')||
                Auth::user()->hasRole('partner-quality-head'))
        {
            $client_id = Auth::user()->spoc_detail->partner->client_id;
        }elseif(Auth::user()->hasRole('client')){
            if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
            
        }     
        if(Auth::user()->id == 44) {
            $partner_id = 1;
            $location_id = 2;
        } else {
            $partner_id = $request->partner_id;
            $location_id = $request->location_id;
        }
        
        $process_id = $request->process_id;
        $lob = $request->lob;
        // starts rebuttal 
        $rebuttal_data = [];
        $audit_data = Audit::where('client_id',$client_id)
                                       ->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                            $query->where('lob', 'like', $lob);
                                        })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])
                                       ->get();

        $coverage['target'] = 450;
        $coverage['achived'] = $audit_data->count();
        $coverage['achived_per'] = round(($audit_data->count()/450)*100);

        $final_data['coverage'] = $coverage;

        $temp_total_rebuttal = 0;
        $temp_accepted_rebuttal = 0;
        $temp_rejected_rebuttal=0;

        foreach ($audit_data as $key => $value) {
            if($value->rebuttal_status > 0)
            {
                $temp_total_rebuttal += $value->audit_rebuttal_count;
                $temp_accepted_rebuttal += $value->audit_rebuttal_accepted->count();
                $temp_rejected_rebuttal += $value->audit_rebuttal_rejected->count();
            }
        }

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
            $rebuttal_data['rejected_per'] = round((($rebuttal_data['rejected']/$audit_data->count())*100));
        else
            $rebuttal_data['rejected_per'] = 0;

        $final_data['rebuttal'] = $rebuttal_data;
        // ends rebuttal 

        // Process Score
            $fatal_dialer_data['with_fatal_score'] = 0;
            $fatal_dialer_data['without_fatal_score'] = 0;
            
            $fatal_audit_count = $audit_data->where('is_critical',1)->count();
            $without_fatal_audit_count = $audit_data->where('is_critical',0)->count();
            $fatal_audit_score_sum=0;
            $without_fatal_audit_score_sum=0;
            foreach ($audit_data as $key => $value) {
                
                $fatal_audit_score_sum += $value->with_fatal_score_per;
                $without_fatal_audit_score_sum +=$value->overall_score;
            }
            if($audit_data->count())
            {
                $fatal_dialer_data['with_fatal_score'] = round(($fatal_audit_score_sum/$audit_data->count()));
                $fatal_dialer_data['without_fatal_score'] = round(($without_fatal_audit_score_sum/$audit_data->count()));
            }else
            {
                $fatal_dialer_data['with_fatal_score']=0;
                $fatal_dialer_data['with_fatal_score']=0;
            }

            $final_data['fatal_dialer_data'] = $fatal_dialer_data;

            //Fatal first row block
            $fatal_first_row_block['total_audits'] = $audit_data->count();
            $fatal_first_row_block['total_fatal_count_sub_parameter'] = 0;
            $fatal_first_row_block['total_fatal_audits'] = $fatal_audit_count;
            if($fatal_audit_count)
            $fatal_first_row_block['total_fatal_audit_per'] = round((($fatal_first_row_block['total_fatal_audits']/$fatal_first_row_block['total_audits'])*100));
            else
            $fatal_first_row_block['total_fatal_audit_per'] = 0;

            // added below in final_data
            //Fatal first row block

            // Parameter Wise Fatal Count & Score
            //get latest QM Sheet
            $latest_qm_sheet = QmSheet::where('client_id',$client_id)->where('process_id',$process_id)->orderBy('version','desc')->first();
            $latest_qm_sheet_id = $latest_qm_sheet->id;

            $all_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('is_non_scoring',0)->with('qm_sheet_sub_parameter')->get();

            $pwfcs = [];
            $pws;
            $params_list = [];
            $all_fatal_audits = $audit_data->where('is_critical',1);
            $count=1;
            foreach ($all_params as $key => $value) {
                $temp_params['counter'] = $count;
                $temp_params['fatal_count'] = 0;
                $temp_params['fail_count'] = 0;
                $temp_params['fatal_counted_score'] = 0;
                $temp_params['parameter'] = $value->parameter;

                $params_list[] = ucfirst($value->parameter);

                foreach ($audit_data as $bkey => $bvalue) {
                    $temp_params['fatal_count'] += $bvalue->audit_parameter_result->where('parameter_id',$value->id)->where('is_critical',1)->count();
                    $temp_params['fail_count'] += $bvalue->audit_results->where('parameter_id',$value->id)->where('selected_option',2)->count();
                    // $temp_row = $bvalue->audit_parameter_result->where('parameter_id',$value->id)->where('is_critical',1)->first();
                    $temp_params['fatal_counted_score'] += $bvalue->audit_parameter_result->where('parameter_id',$value->id)->sum('with_fatal_score_per');
                }   

                if($audit_data->count())
                $temp_params['fatal_score'] = round(($temp_params['fatal_counted_score']/$audit_data->count()));
                else
                $temp_params['fatal_score'] = 0;

                $pwfcs[] = $temp_params;

                // Parameter score 2
                $temp_params['param_score_total'] = 0;
                foreach ($audit_data as $keyc => $valuec) {
                    $temp_params['param_score_total'] += $valuec->audit_parameter_result->where('parameter_id',$value->id)->sum('with_fatal_score_per');
                }
                if($audit_data->count())
                $temp_params['param_score'] = round(($temp_params['param_score_total']/$audit_data->count()));
                else
                $temp_params['param_score'] = 0;
                // Parameter score 2

                $pws[] = $temp_params['param_score'];

                $count++;
            }
            $final_data['pwfcs'] = $pwfcs;
            // Parameter Wise Fatal Count & Score

            // Process Score

            // Parameter score 2
            $final_data['parameter_list'] = $params_list;
            $final_data['pws'] = $pws;
            // Parameter score 2

            // Disposition Wise Score
            $despo = [];

            $temp_all_unique_despos=[];
            foreach ($audit_data as $key => $value) {
                // get all unique despositions
                $temp_all_unique_despos[] = $value->raw_data->disposition;
            }

            $all_unique_despos = array_unique($temp_all_unique_despos);
            $all_unique_despos_counts = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_unique_despos_score_total = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_unique_despos_score = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_audit_counts=array();
            $all_scores=array();
            foreach ($audit_data as $key => $value) {
                if($temp_id = array_search($value->raw_data->disposition,$all_unique_despos,true))
                {   
                    $all_unique_despos_counts[$temp_id] += 1;
                    $all_unique_despos_score_total[$temp_id] += $value->overall_score;
                }
            }

            $s=0;
            foreach ($all_unique_despos as $key => $value) {
                $all_audit_counts[$s]=Audit::where('client_id',$client_id)
                                       ->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$value) {
                                            $query->where('partner_location_id', 'like', $location_id);  
                                            $query->where('disposition', 'like', $value);                              
                                        })->get()->count(); 
                $audit=Audit::where('client_id',$client_id)
                                       ->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$value) {
                                            $query->where('partner_location_id', 'like', $location_id); 
                                            $query->where('disposition', 'like', $value);                                  
                                        })->withCount(['audit_results'])
                                       ->get();
                $score=0;
                $scorable=0;
                foreach ($audit as $key => $value) {                
                    foreach ($value->audit_results->where('is_non_scoring',0) as $keyb => $valueb) {
                        $score += $valueb->score;
                        $scorable += $valueb->after_audit_weight;
                    }
                }
                if($scorable != 0) {
                    $all_scores[$s] = round(($score/$scorable)*100);
                }else {
                    $all_scores[$s] = 0;
                }
                $s++;   
                /*if($all_unique_despos_counts[$key])
                    $all_unique_despos_score[$key] = round(($all_unique_despos_score_total[$key]/$all_unique_despos_counts[$key]));
                else
                    $all_unique_despos_score[$key] = 0;*/
            }

            $despo['all_unique_despos'] = array_values($all_unique_despos);
            $despo['all_unique_despos_counts'] = array_values($all_audit_counts);
            $despo['all_unique_despos_score'] = array_values($all_scores);

            $final_data['disposition'] = $despo;
            // Disposition Wise Score

            //Sub Parameter Wise Score
            //set param-subparam array
            $tmp_params=[];
            foreach ($all_params as $key => $value) {
                $temp_sps = [];
                $temp_sps['parameter_id'] = $value->id;
                $temp_sps['parameter'] = ucfirst($value->parameter);
                $temp_sps_list = [];
                //Sub parameter details
                $temp_sps_detail = [];
                foreach ($value->qm_sheet_sub_parameter as $keyb => $valueb) {
                    $temp_sps_list[] = ucfirst(strtolower($valueb->sub_parameter));
                    $temp_sps_detail[] = str_replace('"', '', $valueb->details);
                    $temp_sps['sub_p_id'][] = $valueb->id;
                    $temp_sps['sp_p_score'][] = 0;
                    $temp_sps['sp_p_weight'][] = 0;
                    $temp_sps['sp_p_obtained'][] = 0;

                    $temp_sps['sp_p_fatal_count'][] = 0;
                }
                $temp_sps['temp_sps_list'] = $temp_sps_list;
                $temp_sps['temp_sps_detail'] = $temp_sps_detail;
                $tmp_params[] = $temp_sps;
            }
            // get all audit results 
            foreach ($audit_data as $key => $value) {
                foreach ($tmp_params as $keyb => $valueb) {
                    foreach ($valueb['sub_p_id'] as $keyc => $valuec) {

                        $tmp_params[$keyb]['sp_p_obtained'][$keyc] += $value->audit_results->where('parameter_id',$valueb['parameter_id'])->where('sub_parameter_id',$valuec)->sum('score');

                        $tmp_params[$keyb]['sp_p_weight'][$keyc] += $value->audit_results->where('parameter_id',$valueb['parameter_id'])->where('sub_parameter_id',$valuec)->sum('after_audit_weight');

                        $tmp_params[$keyb]['sp_p_fatal_count'][$keyc] += $value->audit_results->where('parameter_id',$valueb['parameter_id'])->where('sub_parameter_id',$valuec)->where('is_critical',1)->count();
                    }
                }
            }

            //settle down the score from sum
            foreach ($tmp_params as $key => $value) {
                foreach ($value['sp_p_score'] as $keyb => $valueb) {


                    $fatal_first_row_block['total_fatal_count_sub_parameter'] += $tmp_params[$key]['sp_p_fatal_count'][$keyb];

                    if($tmp_params[$key]['sp_p_weight'][$keyb])
                    {
                        $tmp_params[$key]['sp_p_score'][$keyb] = round(($tmp_params[$key]['sp_p_obtained'][$keyb]/$tmp_params[$key]['sp_p_weight'][$keyb])*100);
                    }else
                    {
                        $tmp_params[$key]['sp_p_score'][$keyb] = 0;
                    }
                }
            }
            $final_data['fatal_first_row_block'] = $fatal_first_row_block;
            $final_data['spws'] = $tmp_params;
            //Sub Parameter Wise Score


            // starts QRC
            $temp_qrc['query_count'] = $audit_data->where('qrc_2','Query')->count();
            $temp_qrc['query_fatal_count'] = $audit_data->where('qrc_2','Query')->where('is_critical',1)->count();
            $temp_qrc['query_fatal_score_sum'] = $audit_data->where('qrc_2','Query')->sum('with_fatal_score_per');

            if($temp_qrc['query_count'])
            $temp_qrc['query_fatal_score'] = round(($temp_qrc['query_fatal_score_sum']/$temp_qrc['query_count']));
            else
            $temp_qrc['query_fatal_score'] = 0;

            $temp_qrc['request_count'] = $audit_data->where('qrc_2','Request')->count();
            $temp_qrc['request_fatal_count'] = $audit_data->where('qrc_2','Request')->where('is_critical',1)->count();
            $temp_qrc['request_fatal_score_sum'] = $audit_data->where('qrc_2','Request')->sum('with_fatal_score_per');

            if($temp_qrc['request_count'])
            $temp_qrc['request_fatal_score'] = round(($temp_qrc['request_fatal_score_sum']/$temp_qrc['request_count']));
            else
            $temp_qrc['request_fatal_score'] =0;

            $temp_qrc['complaint_count'] = $audit_data->where('qrc_2','Complaint')->count();
            $temp_qrc['complaint_fatal_count'] = $audit_data->where('qrc_2','Complaint')->where('is_critical',1)->count();
            $temp_qrc['complaint_fatal_score_sum'] = $audit_data->where('qrc_2','Complaint')->sum('with_fatal_score_per');

            if($temp_qrc['complaint_count'])
            $temp_qrc['complaint_fatal_score'] = round(($temp_qrc['complaint_fatal_score_sum']/$temp_qrc['complaint_count']));
            else
            $temp_qrc['complaint_fatal_score'] = 0;

            $qrc_data['audit_count']=[$temp_qrc['query_count'],$temp_qrc['request_count'],$temp_qrc['complaint_count']];
            $qrc_data['fatal_count']=[$temp_qrc['query_fatal_count'],$temp_qrc['request_fatal_count'],$temp_qrc['complaint_fatal_count']];
            $qrc_data['score']=[$temp_qrc['query_fatal_score'],$temp_qrc['request_fatal_score'],$temp_qrc['complaint_fatal_score']];

            $final_data['qrc'] = $qrc_data;
            // ends QRC

            // quartile starts QRC
            $all_agents = [];
            foreach ($audit_data as $key => $value) {
                $all_agents[] = $value->raw_data->emp_id;
            }

            $all_unique_agents = array_unique($all_agents);
            $all_audit_score=[];
            foreach ($all_unique_agents as $key => $value) {

                $agent_all_audit_score = Audit::where('client_id',$client_id)
                                       ->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($value,$location_id) {
                                            $query->where('emp_id', 'like', $value);
                                            $query->where('partner_location_id', 'like', $location_id);  
                                        })->get();
                $scored=0;
                $scorable=0;
                foreach ($agent_all_audit_score as $key => $value1) {                
                    foreach ($value1->audit_results->where('is_non_scoring',0) as $keyb => $valueb) {
                        $scored += $valueb->score;
                        $scorable += $valueb->after_audit_weight;                                   
                    }               
                }
                if($scorable == 0) {
                    $score = 0;
                } else {
                    $score = round(($scored/$scorable)*100); 
                }
                $all_audit_score[] = ["name"=>$value,
                                      "audit_count"=>$agent_all_audit_score->count(),
                                      "with_fatal_score_per_sum"=>$agent_all_audit_score->sum('with_fatal_score_per'),
                                      "score"=>$score];
            }

            //echo "<pre>";
            //print_r($all_audit_score); die;

            // check the fall in of all agents
            $quartile_data[0] = 0;
            $quartile_data[1] = 0;
            $quartile_data[2] = 0;
            $quartile_data[3] = 0;

            foreach ($all_audit_score as $key => $value) {
                if($value['score'] >= 0 && $value['score'] < 41)
                    $quartile_data[0] += 1;
                else if($value['score'] >= 41  && $value['score'] < 61)
                    $quartile_data[1] += 1;
                else if($value['score'] >= 61  && $value['score'] < 81)
                    $quartile_data[2] +=1;
                else if($value['score'] > 80)
                    $quartile_data[3] += 1;
            }


            $final_data['quartile'] = $quartile_data;
            $final_data['user_id']=Auth::user()->id;
            // quartile ends QRC

            //non scoring sub parameter
            //get para - sub para
            $non_scoring_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('is_non_scoring',1)->with('qm_sheet_sub_parameter')->get();
            $non_scoring_params_name='';
            if($non_scoring_params->isNotEmpty())
            {
                        $non_scoring_params_name = $non_scoring_params[0]->parameter;
                        $non_scoring_params = $non_scoring_params[0]->qm_sheet_sub_parameter;
            }

            $temp_non_scoring_params=[];
            foreach ($non_scoring_params as $key => $value) {

                $temp_non_scoring_params[] = ['non_scoring_option_group'=>$value->non_scoring_option_group,'name'=>$value->sub_parameter,'id'=>$value->id,'count'=>[0,0,0]];
            }

            foreach ($audit_data as $key => $value) {

                foreach ($temp_non_scoring_params as $keyb => $valueb) {

                $a_result = $value->audit_results->where('sub_parameter_id',$valueb['id']);
                $temp_a_result=[];
                foreach ($a_result as $keyx => $valuex) {
                    $temp_a_result = $valuex;
                }

                if($temp_a_result)
                switch ($valueb['non_scoring_option_group']) {
                    case 1:
                    {  
                        if($temp_a_result->selected_option==1)
                            $temp_non_scoring_params[$keyb]['count'][0] +=1;
                        else 
                            $temp_non_scoring_params[$keyb]['count'][1] +=1;

                        break;
                    }
                    case 2:
                    {  
                        if($temp_a_result->selected_option==3)
                            $temp_non_scoring_params[$keyb]['count'][0] +=1;
                        else 
                            $temp_non_scoring_params[$keyb]['count'][1] +=1;


                        break;
                    }
                    case 3:
                    {  
                       if($temp_a_result->selected_option==6)
                            $temp_non_scoring_params[$keyb]['count'][0] +=1;
                        else 
                            $temp_non_scoring_params[$keyb]['count'][1] +=1;


                        break;
                    }
                    case 4:
                    {  

                        if($temp_a_result->selected_option==10)
                            $temp_non_scoring_params[$keyb]['count'][0] +=1;
                        else 
                            $temp_non_scoring_params[$keyb]['count'][1] +=1;
                        
                        break;
                    }
                    
                    default:
                        # code...
                        break;
                }
                    # code...
                }
            }
            
            $temp_all_non_scoring_sub_parameter_list=[];
            $temp_all_non_scoring_sub_parameter_data=[];
            foreach ($temp_non_scoring_params as $key => $value) {

                if(($temp_non_scoring_params[$key]['count'][0]+$temp_non_scoring_params[$key]['count'][1])!=0)
                {
                    $temp_non_scoring_params[$key]['count'][2] = round(($temp_non_scoring_params[$key]['count'][0]/($temp_non_scoring_params[$key]['count'][0]+$temp_non_scoring_params[$key]['count'][1]))*100);    
                }else
                {
                    $temp_non_scoring_params[$key]['count'][2]=0;
                }
                

                $temp_all_non_scoring_sub_parameter_list[] = $value['name'];
                $temp_all_non_scoring_sub_parameter_data[] = $temp_non_scoring_params[$key]['count'][2];


            }   

            $final_data['non_scoring_params'] = ["list"=>$temp_all_non_scoring_sub_parameter_list,"score"=>$temp_all_non_scoring_sub_parameter_data,"names"=>$non_scoring_params_name];
            //non scoring sub parameter

            // starts process stats
            $fail_count=0;
            $critical_count=0;
            $na_count=0;
            foreach ($audit_data as $key => $value) {
                $fail_count += $value->audit_results->where('selected_option',2)->count();
                $critical_count += $value->audit_results->where('selected_option',3)->count();
                $na_count += $value->audit_results->where('selected_option',4)->count();
            }

            $qm_sheet_sp_count = QmSheetSubParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('non_scoring_option_group',0)->count();
            $audit_sp_count = $audit_data->count()*$qm_sheet_sp_count;

            if($audit_data->count())
            {
                $process_stats['dpu'] = round((($fail_count+$critical_count) / $audit_data->count()),2);
                $process_stats['dpo'] = round((($fail_count+$critical_count) / ($audit_sp_count-$na_count)),2);
                $process_stats['dpmo'] = $process_stats['dpo']*1000000;

                $process_stats['ppm'] = round(($fatal_audit_count/$audit_data->count())*1000000,2);

                $process_stats['fty'] = ($audit_data->where('is_critical',0)->count()/$audit_data->count());
            }
            else
            {
                $process_stats['dpu'] =0;
                $process_stats['dpo'] = 0;
                $process_stats['dpmo'] = 0;
                $process_stats['ppm'] = 0;
                $process_stats['fty']=0;
            }




            $final_data['process_stats'] = $process_stats;
            // ends process stats


            // pareto data
            $pareto_audit_result = AuditResult::whereHas('audit', function (Builder $query) use ($partner_id,$process_id,$start_date,$end_date,$location_id) {
                        $query->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                        });
                    })->with('reason')->get();

        $all_uni_reaons=[];
        $all_uni_reaons_a=[];
        $all_uni_reaons_b=[];
        $all_uni_reaons_c=[];
        $all_uni_reaons_d=[];
        $all_uni_reaons_e=[];
        foreach ($pareto_audit_result as $key => $value) {

            if($value->reason)
            $all_uni_reaons_a[$value->reason_id]=['count'=>0,'name'=>$value->reason->name];
        }

        //get count
        foreach ($all_uni_reaons_a as $key => $value) {
            $count = $pareto_audit_result->where('reason_id',$key)->count();
            $all_uni_reaons_a[$key]['count'] = $count;
        }

        usort($all_uni_reaons_a, function($a, $b) {
            return $b['count'] - $a['count'];
        });

        $runningSum = 0;
        foreach ($all_uni_reaons_a as $kk=>$vv) {
            $runningSum += $vv['count'];
            $all_uni_reaons_b[$kk] = $vv['count'];
            $all_uni_reaons_c[$kk] = $runningSum;
            $all_uni_reaons_d[$kk] = $vv['name'];
        }

        
        foreach ($all_uni_reaons_c as $kk=>$vv) {
            $all_uni_reaons_e[$kk] = (round(($vv/$runningSum)*100,2));
        }

        $final_data['pareto_data']['count'] = $all_uni_reaons_b;
        $final_data['pareto_data']['per'] = $all_uni_reaons_e;
        $final_data['pareto_data']['reasons'] = $all_uni_reaons_d;
            // pareto data
        // echo "<pre>";
        // print_r($final_data); die;
        return view('charts',compact('final_data'));
    }

    public function test_html(Request $request){
        
        
        // echo "<pre>";
        // print_r($request->all()); die;
        
        $dates = explode("-", $request->date);
        
        $start_date = date_to_db($dates[0]);
        $end_date = date_to_db($dates[1]);
 
        if(Auth::user()->hasRole('partner-admin'))
        {
            $client_id = Auth::user()->partner_admin_detail->client_id;            
        }elseif(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head')||
                Auth::user()->hasRole('partner-quality-head'))
        {
            $client_id = Auth::user()->spoc_detail->partner->client_id;
        }elseif(Auth::user()->hasRole('client')){
            if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
        }     
        if(Auth::user()->id == 44) {
            $partner_id = 1;
            $location_id = 2;
        } else {
            $partner_id = $request->partner_id;
            $location_id = $request->location_id;
        }
        
        $process_id = $request->process_id;
        $lob = $request->lob;
        // starts rebuttal 
        $rebuttal_data = [];
        
        $audit_data = Audit::where('client_id',$client_id)
                                       ->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                            $query->where('lob', 'like', $lob);
                                        })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])
                                       ->get();

        $coverage['target'] = 450;
        $coverage['achived'] = $audit_data->count();
        $coverage['achived_per'] = round(($audit_data->count()/450)*100);

        $final_data['coverage'] = $coverage;

        $temp_total_rebuttal = 0;
        $temp_accepted_rebuttal = 0;
        $temp_rejected_rebuttal=0;

        foreach ($audit_data as $key => $value) {
            if($value->rebuttal_status > 0)
            {
                $temp_total_rebuttal += $value->audit_rebuttal_count;
                $temp_accepted_rebuttal += $value->audit_rebuttal_accepted->count();
                $temp_rejected_rebuttal += $value->audit_rebuttal_rejected->count();
            }
        }

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
            $rebuttal_data['rejected_per'] = round((($rebuttal_data['rejected']/$audit_data->count())*100));
        else
            $rebuttal_data['rejected_per'] = 0;

        $final_data['rebuttal'] = $rebuttal_data;
        // ends rebuttal 

        // Process Score
            $fatal_dialer_data['with_fatal_score'] = 0;
            $fatal_dialer_data['without_fatal_score'] = 0;
            
            $fatal_audit_count = $audit_data->where('is_critical',1)->count();
            $without_fatal_audit_count = $audit_data->where('is_critical',0)->count();
            $fatal_audit_score_sum=0;
            $without_fatal_audit_score_sum=0;
            foreach ($audit_data as $key => $value) {
                
                $fatal_audit_score_sum += $value->with_fatal_score_per;
                $without_fatal_audit_score_sum +=$value->overall_score;
            }
            if($audit_data->count())
            {
                $fatal_dialer_data['with_fatal_score'] = round(($fatal_audit_score_sum/$audit_data->count()));
                $fatal_dialer_data['without_fatal_score'] = round(($without_fatal_audit_score_sum/$audit_data->count()));
            }else
            {
                $fatal_dialer_data['with_fatal_score']=0;
                $fatal_dialer_data['with_fatal_score']=0;
            }

            $final_data['fatal_dialer_data'] = $fatal_dialer_data;

            //Fatal first row block
            $fatal_first_row_block['total_audits'] = $audit_data->count();
            $fatal_first_row_block['total_fatal_count_sub_parameter'] = 0;
            $fatal_first_row_block['total_fatal_audits'] = $fatal_audit_count;
            if($fatal_audit_count)
            $fatal_first_row_block['total_fatal_audit_per'] = round((($fatal_first_row_block['total_fatal_audits']/$fatal_first_row_block['total_audits'])*100));
            else
            $fatal_first_row_block['total_fatal_audit_per'] = 0;

            // added below in final_data
            //Fatal first row block

            // Parameter Wise Fatal Count & Score
            //get latest QM Sheet
            $latest_qm_sheet = QmSheet::where('client_id',$client_id)->where('process_id',$process_id)->orderBy('version','desc')->first();
            $latest_qm_sheet_id = $latest_qm_sheet->id;

            $all_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('is_non_scoring',0)->with('qm_sheet_sub_parameter')->get();

            $pwfcs = [];
            $pws;
            $params_list = [];
            $all_fatal_audits = $audit_data->where('is_critical',1);
            $count=1;
            foreach ($all_params as $key => $value) {
                $temp_params['counter'] = $count;
                $temp_params['fatal_count'] = 0;
                $temp_params['fail_count'] = 0;
                $temp_params['fatal_counted_score'] = 0;
                $temp_params['parameter'] = $value->parameter;

                $params_list[] = ucfirst($value->parameter);

                foreach ($audit_data as $bkey => $bvalue) {
                    $temp_params['fatal_count'] += $bvalue->audit_parameter_result->where('parameter_id',$value->id)->where('is_critical',1)->count();
                    $temp_params['fail_count'] += $bvalue->audit_results->where('parameter_id',$value->id)->where('selected_option',2)->count();
                    // $temp_row = $bvalue->audit_parameter_result->where('parameter_id',$value->id)->where('is_critical',1)->first();
                    $temp_params['fatal_counted_score'] += $bvalue->audit_parameter_result->where('parameter_id',$value->id)->sum('with_fatal_score_per');
                }   

                if($audit_data->count())
                $temp_params['fatal_score'] = round(($temp_params['fatal_counted_score']/$audit_data->count()));
                else
                $temp_params['fatal_score'] = 0;

                $pwfcs[] = $temp_params;

                // Parameter score 2
                $temp_params['param_score_total'] = 0;
                foreach ($audit_data as $keyc => $valuec) {
                    $temp_params['param_score_total'] += $valuec->audit_parameter_result->where('parameter_id',$value->id)->sum('with_fatal_score_per');
                }
                if($audit_data->count())
                $temp_params['param_score'] = round(($temp_params['param_score_total']/$audit_data->count()));
                else
                $temp_params['param_score'] = 0;
                // Parameter score 2

                $pws[] = $temp_params['param_score'];

                $count++;
            }
            $final_data['pwfcs'] = $pwfcs;
            // Parameter Wise Fatal Count & Score

            // Process Score

            // Parameter score 2
            $final_data['parameter_list'] = $params_list;
            $final_data['pws'] = $pws;
            // Parameter score 2

            // Disposition Wise Score
            $despo = [];

            $temp_all_unique_despos=[];
            foreach ($audit_data as $key => $value) {
                // get all unique despositions
                $temp_all_unique_despos[] = $value->raw_data->disposition;
            }

            $all_unique_despos = array_unique($temp_all_unique_despos);
            $all_unique_despos_counts = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_unique_despos_score_total = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_unique_despos_score = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_audit_counts=array();
            $all_scores=array();
            foreach ($audit_data as $key => $value) {
                if($temp_id = array_search($value->raw_data->disposition,$all_unique_despos,true))
                {   
                    $all_unique_despos_counts[$temp_id] += 1;
                    $all_unique_despos_score_total[$temp_id] += $value->overall_score;
                }
            }

            $s=0;
            foreach ($all_unique_despos as $key => $value) {
                $all_audit_counts[$s]=Audit::where('client_id',$client_id)
                                       ->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$value) {
                                            $query->where('partner_location_id', 'like', $location_id);  
                                            $query->where('disposition', 'like', $value);                              
                                        })->get()->count(); 
                $audit=Audit::where('client_id',$client_id)
                                       ->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$value) {
                                            $query->where('partner_location_id', 'like', $location_id); 
                                            $query->where('disposition', 'like', $value);                                  
                                        })->withCount(['audit_results'])
                                       ->get();
                $score=0;
                $scorable=0;
                foreach ($audit as $key => $value) {                
                    foreach ($value->audit_results->where('is_non_scoring',0) as $keyb => $valueb) {
                        $score += $valueb->score;
                        $scorable += $valueb->after_audit_weight;
                    }
                }
                if($scorable != 0) {
                    $all_scores[$s] = round(($score/$scorable)*100);
                }else {
                    $all_scores[$s] = 0;
                }
                $s++;   
                /*if($all_unique_despos_counts[$key])
                    $all_unique_despos_score[$key] = round(($all_unique_despos_score_total[$key]/$all_unique_despos_counts[$key]));
                else
                    $all_unique_despos_score[$key] = 0;*/
            }

            $despo['all_unique_despos'] = array_values($all_unique_despos);
            $despo['all_unique_despos_counts'] = array_values($all_audit_counts);
            $despo['all_unique_despos_score'] = array_values($all_scores);

            $final_data['disposition'] = $despo;
            // Disposition Wise Score

            //Sub Parameter Wise Score
            //set param-subparam array
            $tmp_params=[];
            foreach ($all_params as $key => $value) {
                $temp_sps = [];
                $temp_sps['parameter_id'] = $value->id;
                $temp_sps['parameter'] = ucfirst($value->parameter);
                $temp_sps_list = [];
                //Sub parameter details
                $temp_sps_detail = [];
                foreach ($value->qm_sheet_sub_parameter as $keyb => $valueb) {
                    $temp_sps_list[] = ucfirst(strtolower($valueb->sub_parameter));
                    $temp_sps_detail[] = str_replace('"', '', $valueb->details);
                    $temp_sps['sub_p_id'][] = $valueb->id;
                    $temp_sps['sp_p_score'][] = 0;
                    $temp_sps['sp_p_weight'][] = 0;
                    $temp_sps['sp_p_obtained'][] = 0;

                    $temp_sps['sp_p_fatal_count'][] = 0;
                }
                $temp_sps['temp_sps_list'] = $temp_sps_list;
                $temp_sps['temp_sps_detail'] = $temp_sps_detail;
                $tmp_params[] = $temp_sps;
            }
            // get all audit results 
            foreach ($audit_data as $key => $value) {
                foreach ($tmp_params as $keyb => $valueb) {
                    foreach ($valueb['sub_p_id'] as $keyc => $valuec) {

                        $tmp_params[$keyb]['sp_p_obtained'][$keyc] += $value->audit_results->where('parameter_id',$valueb['parameter_id'])->where('sub_parameter_id',$valuec)->sum('score');

                        $tmp_params[$keyb]['sp_p_weight'][$keyc] += $value->audit_results->where('parameter_id',$valueb['parameter_id'])->where('sub_parameter_id',$valuec)->sum('after_audit_weight');

                        $tmp_params[$keyb]['sp_p_fatal_count'][$keyc] += $value->audit_results->where('parameter_id',$valueb['parameter_id'])->where('sub_parameter_id',$valuec)->where('is_critical',1)->count();
                    }
                }
            }

            //settle down the score from sum
            foreach ($tmp_params as $key => $value) {
                foreach ($value['sp_p_score'] as $keyb => $valueb) {


                    $fatal_first_row_block['total_fatal_count_sub_parameter'] += $tmp_params[$key]['sp_p_fatal_count'][$keyb];

                    if($tmp_params[$key]['sp_p_weight'][$keyb])
                    {
                        $tmp_params[$key]['sp_p_score'][$keyb] = round(($tmp_params[$key]['sp_p_obtained'][$keyb]/$tmp_params[$key]['sp_p_weight'][$keyb])*100);
                    }else
                    {
                        $tmp_params[$key]['sp_p_score'][$keyb] = 0;
                    }
                }
            }
            $final_data['fatal_first_row_block'] = $fatal_first_row_block;

            // starts process stats
            $fail_count=0;
            $critical_count=0;
            $na_count=0;
            foreach ($audit_data as $key => $value) {
                $fail_count += $value->audit_results->where('selected_option',2)->count();
                $critical_count += $value->audit_results->where('selected_option',3)->count();
                $na_count += $value->audit_results->where('selected_option',4)->count();
            }

            $qm_sheet_sp_count = QmSheetSubParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('non_scoring_option_group',0)->count();
            $audit_sp_count = $audit_data->count()*$qm_sheet_sp_count;

            if($audit_data->count())
            {
                $process_stats['dpu'] = round((($fail_count+$critical_count) / $audit_data->count()),2);
                $process_stats['dpo'] = round((($fail_count+$critical_count) / ($audit_sp_count-$na_count)),2);
                $process_stats['dpmo'] = $process_stats['dpo']*1000000;

                $process_stats['ppm'] = round(($fatal_audit_count/$audit_data->count())*1000000,2);

                $process_stats['fty'] = ($audit_data->where('is_critical',0)->count()/$audit_data->count());
            }
            else
            {
                $process_stats['dpu'] =0;
                $process_stats['dpo'] = 0;
                $process_stats['dpmo'] = 0;
                $process_stats['ppm'] = 0;
                $process_stats['fty']=0;
            }
            $final_data['process_stats'] = $process_stats;
            // ends process stats
            

        //return view('checkdashboard',compact('final_data','request'));
        $html = view('checkdashboard',compact('final_data','request'))->render();
       //echo $html; die;

        //$pdf = PDF::loadView('checkdashboard',compact('final_data','request'));
        $mpdf=new \Mpdf\Mpdf();
        $stylesheet1 = file_get_contents('assets/vendors/custom/fullcalendar/fullcalendar.bundle.css');
        $stylesheet2 = file_get_contents('assets/vendors/base/vendors.bundle.css');
        $stylesheet3 = file_get_contents('assets/demo/default/base/style.bundle.css');
        $stylesheet4 = file_get_contents('assets/demo/default/skins/header/base/light.css');
        $stylesheet5 = file_get_contents('assets/demo/default/skins/brand/dark.css');
        $mpdf->WriteHTML($stylesheet1,1);
        $mpdf->WriteHTML($stylesheet2,1);
        $mpdf->WriteHTML($stylesheet3,1);
        $mpdf->WriteHTML($stylesheet4,1);
        $mpdf->WriteHTML($stylesheet5,1);
        $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);
        $mpdf->Output();
         


      //  return response()->json(['status'=>200,'message'=>"Success",'data'=>$final_data], 200);

        
    }

    public function generate_pdf() 
    {
        $pdf=new PDF('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0); 
        
        $data = array(1);
        
        $pdf = PDF::loadView('checkdashboard', $data);
        $pdf->stream('document.pdf');
        
        dd();
    }
 

    public function test_function()
    {
        // $data = QmSheet::all();
        // foreach ($data as $key => $value) {
        //         $re_label = new ReLabel;
        //         $re_label->info_1 = 'Communication Instance ID(Call ID)';
        //         $re_label->info_2 = 'Client';
        //         $re_label->info_3 = 'Partner';
        //         $re_label->info_4 = 'Audit Date';
        //         $re_label->info_5 = 'Agent Name';
        //         $re_label->info_6 = 'TL Name';
        //         $re_label->info_7 = 'QA / QTL Name';
        //         $re_label->info_8 = 'Campaign Name';
        //         $re_label->info_9 = 'Call Sub Type';
        //         $re_label->info_10 = 'Disposition';
        //         $re_label->info_11 = 'Customer Name';
        //         $re_label->info_12 = 'Cusotmer contact number';
        //         $re_label->info_13 = 'QM-Sheet Version';
        //         $re_label->info_14 = 'QRC 1';
        //         $re_label->info_15 = 'QRC for QA';
        //         $re_label->info_16 = 'Language 1';
        //         $re_label->info_17 = 'Language for QA';
        //         $re_label->info_18 = 'Case ID';
        //         $re_label->info_19 = 'Call Time';
        //         $re_label->info_20 = 'Call Duration';
        //         $re_label->info_21 = 'Refrence No.';

        //         $re_label->qm_sheet_id = $value->id;
        //         $re_label->save();  
        // }

        $audits = Audit::where('client_id',1)->limit(10000)->get();
        
        foreach ($audits as $key => $value) {
            DB::table('audit_results')->where('audit_id', $value->id)->delete();
            DB::table('audit_parameter_results')->where('audit_id', $value->id)->delete();
            $value->delete();
        }
    }

    public function testExcel(Request $request) 
    {   //echo "<pre>"; print_r($request->all()); die; 
        $dates = explode(",", $request->date);
        $start_date = date_to_db($dates[0]);
        $end_date = date_to_db($dates[1]);
        $range=$request->range;
        $location_id =$request->location_id;
        $lob=$request->lob;
        if(Auth::user()->hasRole('partner-admin'))        {
            if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
                $client_id=9;
            } else {
           $client_id = Auth::user()->partner_admin_detail->client_id;
           }            
        }elseif(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head')||
                Auth::user()->hasRole('partner-quality-head'))
        {   
            if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
                $client_id=9;
            } else {
            $client_id = Auth::user()->spoc_detail->partner->client_id;
            }
        }elseif(Auth::user()->hasRole('client')){
            if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
                $client_id=9;
            } else {
                if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
            }
            
        }    

        $audit_data = Audit::where('client_id',$client_id)
                                       ->where('partner_id',$request->partner_id)
                                       ->where('process_id',$request->process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                            $query->where('lob', 'like', $lob);                                      
                                        })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])
                                       ->get(); 
        $all_agents = [];        
        foreach ($audit_data as $key => $value) {
            $all_agents[] = $value->raw_data->emp_id;
        }

        $all_unique_agents = array_unique($all_agents);
        $all_audit_score=array();  
        $new_ar=array();    
        foreach ($all_unique_agents as $key => $value) {

            $agent_all_audit_score = Audit::where('client_id',$client_id)
                                       ->where('partner_id',$request->partner_id)
                                       ->where('process_id',$request->process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($value) {
                                            $query->where('emp_id', 'like', $value);  
                                        })->withCount(['audit_parameter_result'])->get();
            $score=0;
            $scorable=0;
            $ag_count=0;
           // $scorable=count($agent_all_audit_score);
            //$agent_name="";
            foreach ($audit_data as $key => $value1) { 
            if($value1->raw_data->emp_id == $value) {
                $ag_count+=1;
                foreach ($value1->audit_parameter_result->where('is_non_scoring','!=',1) as $keyb => $valueb) {
                    if($value1->is_critical == 0) {
                        $score += $valueb->with_fatal_score;
                    }                         
                    $scorable += $valueb->temp_weight;                                     
                }
            }                
            }   
            if($scorable != 0) {
                
                $final_score=round(($score/$scorable)*100);
            } else  {
                $final_score=0;
            }
                        
                $data=array();                 
                if($final_score >= 0 && $final_score < 41){
                    $data = [   "emp_id" => $value, 
                        "name"=>$value,
                        "audit_count"=>$ag_count,
                        "bucket"=>"0 to 40 %",
                        "score"=>$final_score." %"];
                    $all_audit_score[1][] = $data;
                    $all_audit_score[0][] = $data;
                }
                else if($final_score >= 41  && $final_score < 61){
                     $data = [   "emp_id" => $value, 
                        "name"=>$value,
                        "audit_count"=>$ag_count,
                        "bucket"=>"41 to 60 %",
                        "score"=>$final_score." %"];
                    $all_audit_score[2][] = $data;
                    $all_audit_score[0][] = $data;
                }
                else if($final_score >= 61  && $final_score < 81){
                     $data = [   "emp_id" => $value, 
                        "name"=>$value,
                        "audit_count"=>$ag_count,
                        "bucket"=>"61 to 80 %",
                        "score"=>$final_score." %"];
                    $all_audit_score[3][] = $data;
                    $all_audit_score[0][] = $data;
                }
                else if($final_score > 80){
                     $data = [   "emp_id" => $value, 
                        "name"=>$value,
                        "audit_count"=>$ag_count,
                        "bucket"=>"Greater than 80 %",
                        "score"=>$final_score." %"];
                    $all_audit_score[4][] = $data;
                    $all_audit_score[0][] = $data;
                }                
                    
        }       
        ksort($all_audit_score); 
        //echo "<pre>"; print_r($all_audit_score); die;         
        if(array_key_exists(0, $all_audit_score) && $range == 0) 
            $new_ar[]=$this->array_sort_by_column($all_audit_score[0], 'score');
        else if(array_key_exists(1, $all_audit_score) && $range == 1)
            $new_ar[]=$this->array_sort_by_column($all_audit_score[1], 'score');
        else if(array_key_exists(2, $all_audit_score) && $range == 2)
            $new_ar[]=$this->array_sort_by_column($all_audit_score[2], 'score');
        else if(array_key_exists(3, $all_audit_score) && $range == 3)
            $new_ar[]=$this->array_sort_by_column($all_audit_score[3], 'score');   
        else if(array_key_exists(4, $all_audit_score) && $range == 4)
            $new_ar[]=$this->array_sort_by_column($all_audit_score[4], 'score');  

        
        return Excel::download(new AgentQuartileExport(['data'=>$new_ar]), 'agent_performance.xlsx');           
                
        //return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function delete_audit($call_id)
    {
        $raw_data = RawData::where('call_id',$call_id)->with('audit')->first();
        $audit_id = $raw_data->audit->id;
        Audit::find($audit_id)->delete();
        AuditParameterResult::where('audit_id',$audit_id)->delete();
        AuditResult::where('audit_id',$audit_id)->delete();
        $raw_data->status=0;
        $raw_data->save();
    }
    public function temp_audits()
    {
        $data = Audit::whereDate('audit_date',null)->with(['raw_data','client','partner'])->get();
        return view('temp_audit',compact('data'));
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   

        if(Auth::user()->is_first_time_user == 1) {
            return redirect('profile');
        } 
        else if(Auth::user()->hasRole('client')){
            return redirect()->route('welcome_dashboard_new');
        }
        else {
            if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198 || Auth::user()->id == 41) {
            //return redirect('test_html_new_get');
                return redirect()->route('welcome_dashboard_new');
            } 
            if(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head')||
                Auth::user()->hasRole('partner-quality-head')||
                Auth::user()->hasRole('partner-admin')) 
            {
                
                return redirect()->route('welcome_dashboard_new');
               // return redirect('test_html_new_get');
            } 
            else {
                /* echo bcrypt("Voda@123");
                dd(); */
                return view('home');
            }
        }
        
        
    }
    public function logged_in_user_total_notifications()
    {
        $final_data=array();
        $temp=[];

        foreach (Auth::user()->unreadNotifications as $notification)
        {

            switch (class_basename($notification->type)) {
                    case 'RebuttalRaised':
                    {
                        $temp['upper_text'] = $notification->data['upper_text'];
                        $temp['lower_text'] = "at ".$notification->created_at;
                        $temp['icon'] = "flaticon2-line-chart kt-font-success";
                        $temp['url'] = $notification->data['url'];
                        $temp['noti_id'] = $notification->id;
                        break;
                    }
                    case 'RebuttalReply':
                    {
                        $temp['upper_text'] = $notification->data['upper_text'];
                        $temp['lower_text'] = "at ".$notification->created_at;
                        $temp['icon'] = "flaticon2-line-chart kt-font-success";
                        $temp['url'] = $notification->data['url'];
                        $temp['noti_id'] = $notification->id;
                        break;
                    }
                    case 'CalibrationRequestNoti':
                    {
                        $temp['upper_text'] = $notification->data['upper_text'];
                        $temp['lower_text'] = $notification->data['lower_text'];
                        $temp['icon'] = "flaticon2-line-chart kt-font-success";
                        $temp['url'] = $notification->data['url'];
                        $temp['noti_id'] = $notification->id;
                        break;
                    }
                
                default:
                    # code...
                    break;
            }

            $final_data[] = $temp;
        }
        $count=count($final_data);
        $html="";
        foreach ($final_data as $key => $value) 
        {
            $html.='<div>';
            $html.='<a  class="kt-notification__item" href="javascript:redirect_me_noti('.$value["noti_id"].','.$value["url"].');"><div class="kt-notification__item-icon"><i class="'.$value["icon"].'"></i></div><div class="kt-notification__item-details">
            <div class="kt-notification__item-title">'.$value["upper_text"].'</div><div class="kt-notification__item-time">'.$value["lower_text"].'</div></div></a>';
            $html.='</div>';
        }
        //echo "<pre>"; print_r($final_data); die;
        return response()->json(['status'=>200,'message'=>"Success",'data'=>$final_data,'count'=>$count,'html'=>$html], 200);
    }
    public function mark_read_notification(Request $request)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id',$request->noti_id)->first();
        if ($notification)
            $notification->markAsRead();

        return response()->json(['status'=>200,'message'=>"Success"], 200);
    }
    // public function get_client_welcome_dashboard($client_id)
    // {
    //     $partner_list = Partner::where('client_id',Crypt::decrypt($client_id))->get();
    //     return response()->json(['status'=>200,'message'=>"Success",'data'=>$partner_list], 200);
    // }

    public function client_detail_dashboard()
    {
        return view('dashboards.client_partner_wise_dashboard');
    }
    public function report_disposition_wise_report()
    {
        return view('reports.client_disposition_wise_report');
    }
    public function report_agent_wise_report()
    {
        return view('reports.client_agent_wise_report');
    }

    public function get_loged_in_client_partners()
    {   
        $all_partners=array();

        if(Auth::user()->hasRole('partner-admin'))
        {   
            if(Auth::user()->id == 139) {
                $all_partners = Partner::where('id',38)->pluck('name','id');
            }
            elseif(Auth::user()->id == 195) {
                $all_partners = Partner::where('id',39)->pluck('name','id');
            }
             else {
                $all_partners = Partner::where('id',Auth::user()->partner_admin_detail->id)->pluck('name','id');
            }
             
        }elseif(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head')||
                Auth::user()->hasRole('partner-quality-head'))
        {   
            $all_partners = Partner::where('id',Auth::user()->spoc_detail->partner_id)->pluck('name','id');  
        }elseif(Auth::user()->hasRole('client')){        	
        	if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) { 
        		$all_partners = Partner::where('id',32)->pluck('name','id');
        	} elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                $all_partners = Partner::where('id',40)->pluck('name','id');
            }
            elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $all_partners = Partner::where('id',41)->pluck('name','id');
            } 
            elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
            }
            elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {
                    $a=array(38,45,43,39);
                    $all_partners = Partner::select('name','id')->whereIn('id',$a)->get();
            }
            elseif(Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 280) {
                $all_partners = Partner::where('id',38)->orwhere('id',45)->pluck('name','id'); 
             }
             elseif(Auth::user()->id == 282 ) {
                 $all_partners = Partner::where('id',39)->orwhere('id',43)->pluck('name','id'); 
              }
            elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                    $all_partners = Partner::where('id',44)->pluck('name','id');
            }
            elseif(Auth::user()->id == 139) { 
                $all_partners = Partner::where('id',38)->pluck('name','id');
            }
            elseif(Auth::user()->id == 195) { 
                $all_partners = Partner::where('id',39)->pluck('name','id');
            }
            elseif(Auth::user()->id == 254) { 
                $all_partners = Partner::where('id',43)->pluck('name','id');
            }
            elseif(Auth::user()->id == 42) {
                $all_partners = Partner::where('client_id',9)->pluck('name','id');
            }

            else {
                if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
        		$all_partners = Partner::where('client_id',$client_id)->pluck('name','id');
        	}     
        }

        $all_partners['all']='All';
        
        if(Auth::user()->id == 44) {            
            $selected_name="MK";
            $selected_location="Delhi"; 
            $temp_all_process = PartnersProcess::where('partner_id',1)->with('process')->get();
            $all_process = [];
            foreach ($temp_all_process as $key => $value) {
                $all_process[$value->process_id] = $value->process->name;
            }
            $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',1)->get()->toArray();
            $all_lobs = [];
            foreach ($temp_all_lob as $key => $value) {
                $all_lobs[$value['lob']] = $value['lob'];
            }
        } else {            
            $selected_name = '';
            $selected_location = '';
            $all_process = '';
            $all_lobs = '';
        }         
        return response()->json(['status'=>200,'message'=>"Success",'data'=>$all_partners,'client'=>$selected_name,'selected_location'=>$selected_location,'all_process'=>$all_process,'all_lobs'=>$all_lobs], 200);
    }
    public function get_partner_locations($partner_id)
    {   
        $all_location = [];
        if($partner_id == "all") {

            if(Auth::user()->hasRole('partner-admin'))
            {
                $all_partners = Partner::where('id',Auth::user()->partner_admin_detail->id)->get();  
            }elseif(Auth::user()->hasRole('partner-training-head')||
                    Auth::user()->hasRole('partner-operation-head')||
                    Auth::user()->hasRole('partner-quality-head'))
            {   
                $all_partners = Partner::where('id',Auth::user()->spoc_detail->partner_id)->get();  
            }elseif(Auth::user()->hasRole('client')){
                if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
                $all_partners = Partner::where('client_id',$client_id)->get();  
            }
            
            foreach ($all_partners as $p) {
                $temp_all_locations = PartnerLocation::where('partner_id',$p->id)->with('location_detail')->get();                
                foreach ($temp_all_locations as $key => $value) {
                	if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {
                		if($value->location_id == 36) {
                			$all_location[$value->location_id] = $value->location_detail->name;
                		}                 		
                	} elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                        if($value->location_id == 44) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }   
                    } 
                    elseif(Auth::user()->id == 139) {
                        if($value->location_id == 14) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }    
                    }
                    elseif(Auth::user()->id == 195) {
                        if($value->location_id == 2) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }    
                    }
                    elseif(Auth::user()->id == 254) {
                        if($value->location_id == 2) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }    
                    }
                    elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                        if($value->location_id == 44) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }   
                    }
                    elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                        if($value->location_id == 2 || $value->location_id == 14 || $value->location_id == 20) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }   
                    }
                    elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                         if($value->location_id == 14) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }   
                    }
                    else {
                		$all_location[$value->location_id] = $value->location_detail->name;
                	}
                    
                }
            }
            
        } else {
            $temp_all_locations = PartnerLocation::where('partner_id',$partner_id)->with('location_detail')->get();
            
            foreach ($temp_all_locations as $key => $value) {
            	if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {
                	if($value->location_id == 36) {
                		$all_location[$value->location_id] = $value->location_detail->name;
            		}
            	} elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                    if($value->location_id == 44) {
                        $all_location[$value->location_id] = $value->location_detail->name;
                    }
                }
                elseif(Auth::user()->id == 139) {
                    if($value->location_id == 14) {
                        $all_location[$value->location_id] = $value->location_detail->name;
                    }
                }
                elseif (Auth::user()->id == 195) {
                    if($value->location_id == 2) {
                        $all_location[$value->location_id] = $value->location_detail->name;
                    }
                }
                elseif (Auth::user()->id == 254) {
                    if($value->location_id == 2) {
                        $all_location[$value->location_id] = $value->location_detail->name;
                    }
                }
                elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                    if($value->location_id == 44) {
                        $all_location[$value->location_id] = $value->location_detail->name;
                    }
                }
                elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                    if($value->location_id == 2 || $value->location_id == 14 || $value->location_id == 20) {
                        $all_location[$value->location_id] = $value->location_detail->name;
                    } 
                }
                elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                         if($value->location_id == 14) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }   
                    }
                else {
            		$all_location[$value->location_id] = $value->location_detail->name;
            	}
            }
        }

        $all_location['%']='All';
                
        return response()->json(['status'=>200,'message'=>"Success",'data'=>$all_location], 200);
    }
    public function get_partner_locations1($partner_id)
    {         
        if($partner_id == 'all'){
            if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
                $all_partners = Partner::where('client_id',9)->get();
            } else if(Auth::user()->id == 85){
                $client_id = 1;
                $all_partners = Partner::where('client_id',$client_id)->get();
            }else {
                if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
                $all_partners = Partner::where('client_id',$client_id)->get();
            }
            
            
            foreach ($all_partners as $p) {
                $temp_all_locations = PartnerLocation::where('partner_id',$p->id)->with('location_detail')->get();                
                foreach ($temp_all_locations as $key => $value) {
                	if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {
                    	if($value->location_id == 36) {
                        	$all_location[$value->location_id] = $value->location_detail->name;
                    	}	
                    } 
                    elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                        if($value->location_id == 44) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }   
                    }
                    elseif(Auth::user()->id == 139) {
                        if($value->location_id == 14) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }   
                    }
                    elseif(Auth::user()->id == 195) {
                        if($value->location_id == 2) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }   
                    }
                    elseif(Auth::user()->id == 254) {
                        if($value->location_id == 2) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }   
                    }
                    elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                        if($value->location_id == 44) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }  
                    }
                    elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                        if($value->location_id == 2 || $value->location_id == 14 || $value->location_id == 20) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        } 
                    }
                    elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                         if($value->location_id == 14) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }   
                    }
                    else {
                		$all_location[$value->location_id] = $value->location_detail->name;
                	}
                }
            }

            /* $client_id = Auth::user()->client_detail->client_id;
            $temp_all_locations = PartnerLocation::whereHas('partners', function (Builder $query) use ($client_id) {
                $query->where('client_id', '=', $client_id);
            })
           ->with('location_detail')->get();
            $all_location = [];
            foreach ($temp_all_locations as $key => $value) {
                $all_location[$value->location_id] = $value->location_detail->name;
            } */
            $a=0;
            $html='<option value="%">ALL</option>';
            foreach($all_location as $key =>$val) {
                $html.='<option value='.$key.'>'.$val.'</option>';
            }
            /* $html.="<option value='%'>ALL</option>"; */
        }else {
            $temp_all_locations = PartnerLocation::where('partner_id',$partner_id)->with('location_detail')->get();
            $all_location = [];
            
            foreach ($temp_all_locations as $key => $value) {
            	if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {
                	if($value->location_id == 36) {
                $all_location[$value->location_id] = $value->location_detail->name;
            	}} 
                elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                    if($value->location_id == 44) {
                        $all_location[$value->location_id] = $value->location_detail->name;
                    }
                } 
                 elseif(Auth::user()->id == 139) {
                    if($value->location_id == 14) {
                        $all_location[$value->location_id] = $value->location_detail->name;
                    }
                 }
                 elseif(Auth::user()->id == 195) {
                    if($value->location_id == 2) {
                        $all_location[$value->location_id] = $value->location_detail->name;
                    }
                 }
                 elseif(Auth::user()->id == 254) {
                    if($value->location_id == 2) {
                        $all_location[$value->location_id] = $value->location_detail->name;
                    }
                 }
                elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                    if($value->location_id == 44) {
                        $all_location[$value->location_id] = $value->location_detail->name;
                    }
                }
                elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                    if($value->location_id == 2 || $value->location_id == 14 || $value->location_id == 20) {
                        $all_location[$value->location_id] = $value->location_detail->name;
                    } 
                }
                elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                         if($value->location_id == 14) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }   
                    }
                else {
            		$all_location[$value->location_id] = $value->location_detail->name;
            	}
            }
            /* print_r($all_location);
            dd(); */
            $a=0;
            $html='<option value="%">ALL</option>';
            foreach($all_location as $key =>$val) {
                $html.='<option value='.$key.'>'.$val.'</option>';
            }
            /* $html.="<option value='%'>ALL</option>"; */
        }
        
        return $html;               
    }
    public function get_qtl_process()
    {
        $temp_all_process = Process::get();
        $all_process = [];
        foreach ($temp_all_process as $key => $value) {
            if(Auth::user()->id == 307 || Auth::user()->id == 308) {
                if($value->id == 35) {
                    $all_process[$value->id] = $value->name;
                }
            } else {
                $all_process[$value->id] = $value->name;
            }
            
        }

        return response()->json(['status'=>200,'message'=>"Success",'data'=>$all_process], 200);
    }
    public function get_partner_process($partner_id)
    {
        $all_process = [];
        if($partner_id == "all") {
            if(Auth::user()->hasRole('partner-admin'))
            {
                $all_partners = Partner::where('id',Auth::user()->partner_admin_detail->id)->get();  
            }elseif(Auth::user()->hasRole('partner-training-head')||
                    Auth::user()->hasRole('partner-operation-head')||
                    Auth::user()->hasRole('partner-quality-head'))
            {   
                $all_partners = Partner::where('id',Auth::user()->spoc_detail->partner_id)->get();  
            }elseif(Auth::user()->hasRole('client')){
                if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
                $all_partners = Partner::where('client_id',$client_id)->get();  
            }
            foreach($all_partners as $p) {
                $temp_all_process = PartnersProcess::where('partner_id',$p->id)->with('process')->get();
                foreach ($temp_all_process as $key => $value) {
                	if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) {
            		if($value->process_id == 25 || $value->process_id == 26 || $value->process_id == 30) { 
            			$all_process[$value->process_id] = $value->process->name;
            		}}
            		elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
	        			if($value->process_id == 23) {
	        				$all_process[$value->process_id] = $value->process->name;
	        			}
	            	}
                    elseif(Auth::user()->id == 139) {
                        if($value->process_id == 21 || $value->process_id == 22) {
                            $all_process[$value->process_id] = $value->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 195) {
                        if($value->process_id == 22) {
                            $all_process[$value->process_id] = $value->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 254) {
                        if($value->process_id == 22) {
                            $all_process[$value->process_id] = $value->process->name;
                        }
                    }
	            	elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
	        			if($value->process_id == 31) {
	        				$all_process[$value->process_id] = $value->process->name;
	        			}
	            	}
                    
	            	elseif(Auth::user()->id == 252 || Auth::user()->id == 269 || Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 279 || Auth::user()->id == 283) {
	        			if($value->process_id == 21 || $value->process_id == 22) {
	        				$all_process[$value->process_id] = $value->process->name;
	        			}
	            	}
	            	elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
	        			if($value->process_id == 32) {
	        				$all_process[$value->process_id] = $value->process->name;
	        			}
	            	}
                    elseif(Auth::user()->id == 307 || Auth::user()->id == 308) {
                        if($value->process_id == 35) {
                            $all_process[$value->process_id] = $value->process->name;
                        }
                    }
            		else {
            			$all_process[$value->process_id] = $value->process->name;
            		}
                    
                }
            }
        } else {
            $temp_all_process = PartnersProcess::where('partner_id',$partner_id)->with('process')->get();
            foreach ($temp_all_process as $key => $value) {
            	if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) {
            		if($value->process_id == 25 || $value->process_id == 26 || $value->process_id == 30) {
            			$all_process[$value->process_id] = $value->process->name;
            		}
            	} elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
        			if($value->process_id == 23) {
        				$all_process[$value->process_id] = $value->process->name;
        			}
            	}
                elseif(Auth::user()->id == 139) {
                    if($value->process_id == 21 || $value->process_id == 22) {
                        $all_process[$value->process_id] = $value->process->name;
                    }
                }
                 elseif(Auth::user()->id == 195) {
                    if($value->process_id == 22) {
                        $all_process[$value->process_id] = $value->process->name;
                    }
                }

                elseif(Auth::user()->id == 254) {
                    if($value->process_id == 22) {
                        $all_process[$value->process_id] = $value->process->name;
                    }
                }

            	elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
        			if($value->process_id == 31) {
        				$all_process[$value->process_id] = $value->process->name;
        			}
            	} 

            	elseif(Auth::user()->id == 252 || Auth::user()->id == 269 || Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 279 || Auth::user()->id == 283) {
	        			if($value->process_id == 21 || $value->process_id == 22) {
	        				$all_process[$value->process_id] = $value->process->name;
	        			}
	            	}
	            elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
	        			if($value->process_id == 32) {
	        				$all_process[$value->process_id] = $value->process->name;
	        			}
	            	}
                    elseif(Auth::user()->id == 307 || Auth::user()->id == 308) {
                        if($value->process_id == 35) {
                            $all_process[$value->process_id] = $value->process->name;
                        }
                    }
            	else {
            		$all_process[$value->process_id] = $value->process->name;
            	}
                
            }
        }
        return response()->json(['status'=>200,'message'=>"Success",'data'=>$all_process], 200);
    }

    public function get_partner_process1($partner_id)
    {
        if($partner_id == 'all'){
            if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198){
                $all_partners = Partner::where('client_id',9)->get();  
            } else if(Auth::user()->id == 85){
                $client_id = 1;
                $all_partners = Partner::where('client_id',$client_id)->get();  
            } else {
                if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
                $all_partners = Partner::where('client_id',$client_id)->get();  
            }
            
            foreach($all_partners as $p) {

                $all_process = PartnersProcess::where('partner_id',$p->id)->with('process')->get();
                foreach ($all_process as $key => $value) {
                	if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) {
            		if($value->process_id == 25 || $value->process_id == 26 || $value->process_id == 30) { 
            			$temp_all_process[$value->process_id] = $value->process->name;
            		}}
            		elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
	        			if($value->process_id == 23) {
	        				$temp_all_process[$value->process_id] = $value->process->name;
	        			}
	            	}
	            	elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
	        			if($value->process_id == 31) {
	        				$temp_all_process[$value->process_id] = $value->process->name;
	        			}
	            	}
	            	elseif(Auth::user()->id == 252 || Auth::user()->id == 139 || Auth::user()->id == 269 || Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 279 || Auth::user()->id == 283) {
	        			if($value->process_id == 21 || $value->process_id == 22) {
	        				$temp_all_process[$value->process_id] = $value->process->name;
	        			}
	            	}
                    elseif(Auth::user()->id == 195) {
                        if($value->process_id == 22) {
                            $temp_all_process[$value->process_id] = $value->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 254) {
                        if($value->process_id == 22) {
                            $temp_all_process[$value->process_id] = $value->process->name;
                        }
                    }
	            	elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
	        			if($value->process_id == 32) {
	        				$temp_all_process[$value->process_id] = $value->process->name;
	        			}
	            	}
                    elseif(Auth::user()->id == 307 || Auth::user()->id == 308) {
                        if($value->process_id == 35) {
                            $temp_all_process[$value->process_id] = $value->process->name;
                        }
                    }
            		 else {
            			$temp_all_process[$value->process_id] = $value->process->name;
            		}
                    
                }
            }

            $a=0;
            $html='<option value='.$a.'>Select a process</option>';
            foreach($temp_all_process as $key => $value) {
                $html.='<option value='.$key.'>'.$value.'</option>';
            }
        } else {
            $temp_all_process = PartnersProcess::where('partner_id',$partner_id)->with('process')->get();        
            $a=0;
            $html='<option value='.$a.'>Select a process</option>';
            foreach($temp_all_process as $key => $value) {
            	if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) {
            		if($value->process_id == 25 || $value->process_id == 26 || $value->process_id == 30) { 
            			$html.='<option value='.$value->process_id.'>'.$value->process->name.'</option>';
            		} 
            	}
            	elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
        			if($value->process_id == 23) {
        				$html.='<option value='.$value->process_id.'>'.$value->process->name.'</option>';
        			}
            	}
            	elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
        			if($value->process_id == 31) {
        				$html.='<option value='.$value->process_id.'>'.$value->process->name.'</option>';
        			}
            	}
            	elseif(Auth::user()->id == 252 || Auth::user()->id == 139 || Auth::user()->id == 269 || Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 279 || Auth::user()->id == 283) {
        			if($value->process_id == 21 || $value->process_id == 22) {
        				$html.='<option value='.$value->process_id.'>'.$value->process->name.'</option>';
        			}
            	}
                elseif(Auth::user()->id == 195) {
                    if($value->process_id == 22) {
                        $html.='<option value='.$value->process_id.'>'.$value->process->name.'</option>';
                    }
                }
                elseif(Auth::user()->id == 254) {
                    if($value->process_id == 22) {
                        $html.='<option value='.$value->process_id.'>'.$value->process->name.'</option>';
                    }
                }
            	elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
        			if($value->process_id == 32) {
        				$html.='<option value='.$value->process_id.'>'.$value->process->name.'</option>';
        			}
            	}
                elseif(Auth::user()->id == 307 || Auth::user()->id == 308) {
                    if($value->process_id == 35) {
                        $html.='<option value='.$value->process_id.'>'.$value->process->name.'</option>';
                    }
                }
            	else {
            		$html.='<option value='.$value->process_id.'>'.$value->process->name.'</option>';
            	} 
                
            }
        
        }

       
        
        
        return $html;       
    }

    public function get_client_welcome_data_cycle(Request $request)
    {
        $dates = explode(" ", $request->selected_elements[0]['audit_cycle']);

        $month_first_data = $dates[1];
        $today = $dates[0];
        
        $month=date('M',strtotime($dates[1]));
        
        if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
            $client_id=9;
        } else {
            if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
        }

        // starts rebuttal 
          $rebuttal_data = [];
          if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) { 
                $audit_data = Audit::where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->where('partner_id',32)->whereIn('process_id',array(25,26,30))->get();
           } 
           else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                 $audit_data = Audit::where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->where('partner_id',40)->where('process_id',23)->get();
           }
           elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $audit_data = Audit::where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->where('partner_id',41)->where('process_id',31)->get();
           }
           elseif(Auth::user()->id == 256 || Auth::user()->id == 255)  {
            $audit_data = Audit::where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->where('partner_id',44)->where('process_id',32)->get();
           }
           else {
                $audit_data =Audit::where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->get();
           }
          if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) { 
            $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('partner_id',32)->whereIn('process_id',array(25,26,30))->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
 ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
           } 
           else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('partner_id',40)->where('process_id',23)->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
 ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
           }
           elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('partner_id',41)->where('process_id',31)->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
 ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
           }
           elseif(Auth::user()->id == 256 || Auth::user()->id == 255)  {
              $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('partner_id',44)->where('process_id',32)->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
               ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
           }
           else {
                $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('rebuttal_status','>',0)
                                       ->whereDate('audit_date','>=',$month_first_data)
                                       ->whereDate('audit_date','<=',$today)
                                       ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
           }                   
          
          /*$temp_rebuttal_data = Audit::where('client_id',$client_id)->where('rebuttal_status','>',0)
                                       ->whereDate('audit_date','>=',$month_first_data)
                                       ->whereDate('audit_date','<=',$today)
                                       ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get(); */
        //dd($audit_data); 
        if($client_id==1)
        {
            $coverage['target'] = 500;
            $coverage['achived_per'] = round(($audit_data->count()/500)*100);
        }else if($client_id==9){
            //echo "jsa"; die;
            

            
            if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) { 
                //echo "here"; die;
                    $t1=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',25)->sum('month_targets.eq_audit_target_mtd');
                    
                    $t2=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',26)->sum('month_targets.eq_audit_target_mtd');
                    
                    $t3=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',30)->sum('month_targets.eq_audit_target_mtd'); $target=$t1+$t2+$t3;

            } 
            elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',23)->sum('month_targets.eq_audit_target_mtd');
            }
            elseif(Auth::user()->id == 139) {
                $t1=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',21)->sum('month_targets.eq_audit_target_mtd');
                $t2=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',22)->sum('month_targets.eq_audit_target_mtd');
                $target=$t1+$t2;
            }
            elseif(Auth::user()->id == 195) {
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',22)->sum('month_targets.eq_audit_target_mtd');
            }
            elseif(Auth::user()->id == 254) {
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',22)->sum('month_targets.eq_audit_target_mtd');
            }
            elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',31)->sum('month_targets.eq_audit_target_mtd');
            }
            elseif(Auth::user()->id == 252 || Auth::user()->id == 269 || Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 279 || Auth::user()->id == 283) {
                $t1=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',21)->sum('month_targets.eq_audit_target_mtd');
            $t2=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',22)->sum('month_targets.eq_audit_target_mtd');
            $target=$t1+$t2;
            }
            elseif(Auth::user()->id == 256 || Auth::user()->id == 255) { 
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',32)->sum('month_targets.eq_audit_target_mtd');
            }
             else {

                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->sum('month_targets.eq_audit_target_mtd');

            }
            
             


           /*  $target = MonthTarget::where('client_id',$client_id)
            ->join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')
            
            
            ->where('month_of_target','like',"Jun%")
            ->sum('eq_audit_target_mtd');   */
           /*  echo $target;

            dd(); */

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

            //$coverage['target'] = 1950;
            
        }     
        else
        {
            $coverage['target'] = 22000;
            $coverage['achived_per'] = round(($audit_data->count()/22000)*100);
        }
        
        $coverage['achived'] = $audit_data->count();
        

        $final_data['coverage'] = $coverage;

        $temp_total_rebuttal = 0;
        $temp_accepted_rebuttal = 0;
        $temp_rejected_rebuttal = 0;

        foreach ($temp_rebuttal_data as $key => $value) {
                $temp_total_rebuttal += $value->audit_rebuttal->count();
                $temp_accepted_rebuttal += $value->audit_rebuttal_accepted->count();
                $temp_rejected_rebuttal += $value->audit_rebuttal_rejected->count();
        }

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
            $rebuttal_data['rejected_per'] = round((($rebuttal_data['rejected']/$audit_data->count())*100));
        else
            $rebuttal_data['rejected_per'] = 0;


        $final_data['rebuttal'] = $rebuttal_data;
        // ends rebuttal 

        // starts QRC
        /*$temp_qrc['query_count'] = Audit::where('client_id',$client_id)
                                          ->where('qrc_2','Query')
                                          ->whereDate('audit_date','>=',$month_first_data)
                                          ->whereDate('audit_date','<=',$today)
                                          ->count(); */
        if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) { 
  $temp_qrc['query_count'] = Audit::where('client_id',$client_id)->where('partner_id',32)->whereIn('process_id',array(25,26,30))->where('qrc_2','Query')
  ->whereDate('audit_date','>=',$month_first_data)
  ->whereDate('audit_date','<=',$today)
  ->count();

  $temp_qrc['query_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',32)->whereIn('process_id',array(25,26,30))
     ->where('qrc_2','Query')
    ->where('is_critical',1)
    ->whereDate('audit_date','>=',$month_first_data)
    ->whereDate('audit_date','<=',$today)
    ->count();

    $temp_qrc['request_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',32)->whereIn('process_id',array(25,26,30))
        ->where('qrc_2','Request')
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();

    $temp_qrc['request_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',32)->whereIn('process_id',array(25,26,30))
      ->where('qrc_2','Request')
      ->where('is_critical',1)
      ->whereDate('audit_date','>=',$month_first_data)
      ->whereDate('audit_date','<=',$today)
      ->count();

    $temp_qrc['complaint_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',32)->whereIn('process_id',array(25,26,30))
      ->where('qrc_2','Complaint')
      ->whereDate('audit_date','>=',$month_first_data)
      ->whereDate('audit_date','<=',$today)
      ->count();

    $temp_qrc['complaint_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',32)->whereIn('process_id',array(25,26,30))
    ->where('qrc_2','Complaint')
    ->where('is_critical',1)
    ->whereDate('audit_date','>=',$month_first_data)
    ->whereDate('audit_date','<=',$today)
    ->count();
} 
else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                
  $temp_qrc['query_count'] = Audit::where('client_id',$client_id)->where('partner_id',40)->where('process_id',23)->where('qrc_2','Query')
  ->whereDate('audit_date','>=',$month_first_data)
  ->whereDate('audit_date','<=',$today)
  ->count();

  $temp_qrc['query_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',40)->where('process_id',23)
    ->where('qrc_2','Query')
    ->where('is_critical',1)
    ->whereDate('audit_date','>=',$month_first_data)
    ->whereDate('audit_date','<=',$today)
    ->count();

    $temp_qrc['request_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',40)->where('process_id',23)
        ->where('qrc_2','Request')
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();

    $temp_qrc['request_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',40)->where('process_id',23)
      ->where('qrc_2','Request')
      ->where('is_critical',1)
      ->whereDate('audit_date','>=',$month_first_data)
      ->whereDate('audit_date','<=',$today)
      ->count();

    $temp_qrc['complaint_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',40)->where('process_id',23)
      ->where('qrc_2','Complaint')
      ->whereDate('audit_date','>=',$month_first_data)
      ->whereDate('audit_date','<=',$today)
      ->count();

    $temp_qrc['complaint_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',40)->where('process_id',23)
    ->where('qrc_2','Complaint')
    ->where('is_critical',1)
    ->whereDate('audit_date','>=',$month_first_data)
    ->whereDate('audit_date','<=',$today)
    ->count();

}
elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
  $temp_qrc['query_count'] = Audit::where('client_id',$client_id)->where('partner_id',41)->where('process_id',31)->where('qrc_2','Query')
  ->whereDate('audit_date','>=',$month_first_data)
  ->whereDate('audit_date','<=',$today)
  ->count();

  $temp_qrc['query_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',41)->where('process_id',31)
    ->where('qrc_2','Query')
    ->where('is_critical',1)
    ->whereDate('audit_date','>=',$month_first_data)
    ->whereDate('audit_date','<=',$today)
    ->count();

    $temp_qrc['request_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',41)->where('process_id',31)
        ->where('qrc_2','Request')
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();

    $temp_qrc['request_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',41)->where('process_id',31)
      ->where('qrc_2','Request')
      ->where('is_critical',1)
      ->whereDate('audit_date','>=',$month_first_data)
      ->whereDate('audit_date','<=',$today)
      ->count();

    $temp_qrc['complaint_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',41)->where('process_id',31)
      ->where('qrc_2','Complaint')
      ->whereDate('audit_date','>=',$month_first_data)
      ->whereDate('audit_date','<=',$today)
      ->count();

    $temp_qrc['complaint_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',41)->where('process_id',31)
    ->where('qrc_2','Complaint')
    ->where('is_critical',1)
    ->whereDate('audit_date','>=',$month_first_data)
    ->whereDate('audit_date','<=',$today)
    ->count();              

}
elseif(Auth::user()->id == 256 || Auth::user()->id == 255)  {
              
  $temp_qrc['query_count'] = Audit::where('client_id',$client_id)->where('partner_id',44)->where('process_id',32)->where('qrc_2','Query')
  ->whereDate('audit_date','>=',$month_first_data)
  ->whereDate('audit_date','<=',$today)
  ->count();

  $temp_qrc['query_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',44)->where('process_id',32)
    ->where('qrc_2','Query')
    ->where('is_critical',1)
    ->whereDate('audit_date','>=',$month_first_data)
    ->whereDate('audit_date','<=',$today)
    ->count();

    $temp_qrc['request_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',44)->where('process_id',32)
        ->where('qrc_2','Request')
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();

    $temp_qrc['request_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',44)->where('process_id',32)
      ->where('qrc_2','Request')
      ->where('is_critical',1)
      ->whereDate('audit_date','>=',$month_first_data)
      ->whereDate('audit_date','<=',$today)
      ->count();

    $temp_qrc['complaint_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',44)->where('process_id',32)
      ->where('qrc_2','Complaint')
      ->whereDate('audit_date','>=',$month_first_data)
      ->whereDate('audit_date','<=',$today)
      ->count();

    $temp_qrc['complaint_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',44)->where('process_id',32)
    ->where('qrc_2','Complaint')
    ->where('is_critical',1)
    ->whereDate('audit_date','>=',$month_first_data)
    ->whereDate('audit_date','<=',$today)
    ->count();
}
else {
    $temp_qrc['query_count'] = 
            Audit::where('client_id',$client_id)->where('qrc_2','Query')
          ->whereDate('audit_date','>=',$month_first_data)
          ->whereDate('audit_date','<=',$today)
          ->count();
    $temp_qrc['query_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('qrc_2','Query')
    ->where('is_critical',1)
    ->whereDate('audit_date','>=',$month_first_data)
    ->whereDate('audit_date','<=',$today)
    ->count();
    $temp_qrc['request_count'] = 
    Audit::where('client_id',$client_id)
        ->where('qrc_2','Request')
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();
    $temp_qrc['request_fatal_count'] = 
    Audit::where('client_id',$client_id)
      ->where('qrc_2','Request')
      ->where('is_critical',1)
      ->whereDate('audit_date','>=',$month_first_data)
      ->whereDate('audit_date','<=',$today)
      ->count();
    $temp_qrc['complaint_count'] = 
    Audit::where('client_id',$client_id)
      ->where('qrc_2','Complaint')
      ->whereDate('audit_date','>=',$month_first_data)
      ->whereDate('audit_date','<=',$today)
      ->count();
    $temp_qrc['complaint_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('qrc_2','Complaint')
    ->where('is_critical',1)
    ->whereDate('audit_date','>=',$month_first_data)
    ->whereDate('audit_date','<=',$today)
    ->count();

}
        /*$temp_qrc['query_fatal_count'] = Audit::where('client_id',$client_id)
->where('qrc_2','Query')
->where('is_critical',1)
->whereDate('audit_date','>=',$month_first_data)
->whereDate('audit_date','<=',$today)
->count();
        $temp_qrc['request_count'] = Audit::where('client_id',$client_id)
        ->where('qrc_2','Request')
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();
        $temp_qrc['request_fatal_count'] = Audit::where('client_id',$client_id)
->where('qrc_2','Request')
->where('is_critical',1)
->whereDate('audit_date','>=',$month_first_data)
->whereDate('audit_date','<=',$today)
->count();
        $temp_qrc['complaint_count'] = Audit::where('client_id',$client_id)
->where('qrc_2','Complaint')
->whereDate('audit_date','>=',$month_first_data)
->whereDate('audit_date','<=',$today)
->count();
        $temp_qrc['complaint_fatal_count'] = Audit::where('client_id',$client_id)
->where('qrc_2','Complaint')
->where('is_critical',1)
->whereDate('audit_date','>=',$month_first_data)
->whereDate('audit_date','<=',$today)
->count(); */

        $final_data['qrc'] = $temp_qrc;
        // ends QRC

        // starts Process Wise Score
        if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {
                    
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',32)->get();
                } 
                else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                    
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',40)->get();
                }
                elseif(Auth::user()->id == 249 || Auth::user()->id == 250){
                   
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',41)->get();
                }

                elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                    
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',44)->get();
                }

                elseif(Auth::user()->id == 139) {
                    
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',38)->get();
                }

                elseif (Auth::user()->id == 195) {
                   
                   $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',39)->get();
                }

                elseif (Auth::user()->id == 254) {
                   
                   $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',43)->get();
                } 
                elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {

                        $a=array(38,45,43,39);
                     $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->whereIn('id',$a)->get();
                }
                elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',38)->orwhere('id',39)->orwhere('id',43)->orWhere('id',45)->get();
                } elseif(Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 280) {
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',38)->orWhere('id',45)->get();
                }
                elseif(Auth::user()->id == 282) {
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',39)->orWhere('id',43)->get();
                }
                else  {
                    $partner_list = Partner::where('client_id',$client_id)->with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->get();
                }
        $partner_process_list=[];
        foreach ($partner_list as $akey => $avalue) {
            foreach ($avalue->partner_process as $bkey => $bvalue) {
                if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) {
                    if($bvalue->process_id == 25 || $bvalue->process_id == 26 || $bvalue->process_id == 30) {
                        $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                    }
                }
                elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                        if($bvalue->process_id == 23) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 139) {
                        if($bvalue->process_id == 21 || $bvalue->process_id == 22) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 195) {
                        if($bvalue->process_id == 22) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 254) {
                        if($bvalue->process_id == 22) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                        if($bvalue->process_id == 31) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 252 || Auth::user()->id == 269 || Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 279 || Auth::user()->id == 283) {
                        if($bvalue->process_id == 21 || $bvalue->process_id == 22) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                        if($bvalue->process_id == 32) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    } else {
                        $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                    }
            }
        }

        $loop=1;
        $final_score=0;
        $final_scorable=0;
        foreach ($partner_process_list as $key => $value) {

            $process_audit_data['score_sum'] = Audit::where('client_id',$client_id)
                                         ->where('process_id',$key)
                                         ->whereDate('audit_date','>=',$month_first_data)
                                         ->whereDate('audit_date','<=',$today)
                                         ->sum('overall_score');
$process_audit_data['audit_count'] = Audit::where('client_id',$client_id)
        ->where('process_id',$key)
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();

        $process_audit_data['with_fatal'] = Audit::where('client_id',$client_id)
        ->where('process_id',$key)
        //->where('is_critical',1)
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->sum('with_fatal_score_per');
            if($process_audit_data['audit_count']) {

                
            $process_audit_data['scored_with_fatal'] = round(   ($process_audit_data['with_fatal']/$process_audit_data['audit_count']));
                
           
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
        // ends Process Wise Score

        // Partner & Location Wise Report
        $pl_report = [];
        $loop=1;
        foreach ($partner_list as $key => $value) {
            foreach ($value->partner_location as $bkey => $bvalue) {
                $temp_plr['count'] = $loop;
                $temp_plr['partner'] = $value->name;
                $temp_plr['location'] = $bvalue->location_detail->name;

                //get audits
                $plr_audits = Audit::where('client_id',$client_id)->where('partner_id',$value->id)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)->whereHas('raw_data', function (Builder $query) use ($bvalue) {
                    $query->where('partner_location_id', 'like', $bvalue->location_id);
                })->get();
                $temp_plr['audits_count'] = $plr_audits->count();

                $fatal_audit_score_sum =0;
                $without_fatal_audit_score_sum = 0;
                foreach ($plr_audits as $key => $value) {

                if($value->is_critical==1)
                $fatal_audit_score_sum += 0;
                else
                $fatal_audit_score_sum += $value->overall_score;

                $without_fatal_audit_score_sum +=$value->overall_score;
                }

                if($plr_audits->count())
                {
                $temp_plr['with_fatal'] = round(($fatal_audit_score_sum/$plr_audits->count()));
                $temp_plr['without_fatal'] = round(($without_fatal_audit_score_sum/$plr_audits->count()));
                }else
                {
                $temp_plr['with_fatal']=0;
                $temp_plr['without_fatal']=0;
                }


                
                //get audits

                $pl_report[] = $temp_plr;
                $loop++;
            }
        }
        $final_data['plr'] = $pl_report;
        // Partner & Location Wise Report

        $audit_cyle_data = Auditcycle::where('client_id',$client_id)->orderby('start_date','desc')->get()->pluck('start_date','end_date');

        $final_data['audit_cycle'] = $audit_cyle_data;
        $final_data['ov_scored']=$ov_scored;


        return response()->json(['status'=>200,'message'=>"Success",'data'=>$final_data], 200);
    }

    /* public function get_qrc_lob_wise_welcome_dashboard($process_id,$month_first_data,$today){
        // starts QRC
        if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
            $client_id=9;
        } else {
            $client_id = Auth::user()->client_detail->client_id;
        }


        if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) { 
            $temp_qrc['query_count'] = Audit::where('client_id',$client_id)->where('partner_id',32)->whereIn('process_id',array(25,26,30))->where('qrc_2','Query')
    
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['query_fatal_count'] = 
            Audit::where('client_id',$client_id)
         
            ->where('partner_id',32)->whereIn('process_id',array(25,26,30))
            ->where('qrc_2','Query')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['request_count'] = 
            Audit::where('client_id',$client_id)
          
            ->where('partner_id',32)->whereIn('process_id',array(25,26,30))
                ->where('qrc_2','Request')
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();

            $temp_qrc['request_fatal_count'] = 
            Audit::where('client_id',$client_id)
          
            ->where('partner_id',32)->whereIn('process_id',array(25,26,30))
            ->where('qrc_2','Request')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['complaint_count'] = 
            Audit::where('client_id',$client_id)
        
            ->where('partner_id',32)->whereIn('process_id',array(25,26,30))
            ->where('qrc_2','Complaint')
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['complaint_fatal_count'] = 
            Audit::where('client_id',$client_id)
            
            ->where('partner_id',32)->whereIn('process_id',array(25,26,30))
            ->where('qrc_2','Complaint')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();
        } 
        else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                        
        $temp_qrc['query_count'] = Audit::where('client_id',$client_id)->where('partner_id',40)->where('process_id',23)->where('qrc_2','Query')
        
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();

        $temp_qrc['query_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',40)->where('process_id',23)
            ->where('qrc_2','Query')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['request_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',40)->where('process_id',23)
                ->where('qrc_2','Request')
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();

            $temp_qrc['request_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',40)->where('process_id',23)
            ->where('qrc_2','Request')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['complaint_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',40)->where('process_id',23)
            ->where('qrc_2','Complaint')
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['complaint_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',40)->where('process_id',23)
            ->where('qrc_2','Complaint')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

        }
        elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
        $temp_qrc['query_count'] = Audit::where('client_id',$client_id)->where('partner_id',41)->where('process_id',31)->where('qrc_2','Query')
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();

        $temp_qrc['query_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',41)->where('process_id',31)
            ->where('qrc_2','Query')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['request_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',41)->where('process_id',31)
                ->where('qrc_2','Request')
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();

            $temp_qrc['request_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',41)->where('process_id',31)
            ->where('qrc_2','Request')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['complaint_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',41)->where('process_id',31)
            ->where('qrc_2','Complaint')
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['complaint_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',41)->where('process_id',31)
            ->where('qrc_2','Complaint')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();              

        }
        elseif(Auth::user()->id == 256 || Auth::user()->id == 255)  {
                    
        $temp_qrc['query_count'] = Audit::where('client_id',$client_id)->where('partner_id',44)->where('process_id',32)->where('qrc_2','Query')
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();

        $temp_qrc['query_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',44)->where('process_id',32)
            ->where('qrc_2','Query')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['request_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',44)->where('process_id',32)
                ->where('qrc_2','Request')
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();

            $temp_qrc['request_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',44)->where('process_id',32)
            ->where('qrc_2','Request')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['complaint_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',44)->where('process_id',32)
            ->where('qrc_2','Complaint')
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['complaint_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',44)->where('process_id',32)
            ->where('qrc_2','Complaint')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();
        }
        else {
            $temp_qrc['query_count'] = 
                    Audit::where('client_id',$client_id)->whereIn('qrc_2',['Query','FTR'])
                    
                    ->where('process_id',$process_id)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();
            $temp_qrc['query_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('process_id',$process_id)
            ->whereIn('qrc_2',['Query','FTR'])
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();
            $temp_qrc['request_count'] = 
            Audit::where('client_id',$client_id)
                ->where('process_id',$process_id)
                ->whereIn('qrc_2',['NFTR','Request'])
               // ->where('qrc_2','Request')
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();
            $temp_qrc['request_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('process_id',$process_id)
            ->whereIn('qrc_2',['NFTR','Request'])
           // ->where('qrc_2','Request')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();
            $temp_qrc['complaint_count'] = 
            Audit::where('client_id',$client_id)
            ->where('process_id',$process_id)
            ->whereIn('qrc_2',['Complaint','DNA'])
           // ->where('qrc_2','Complaint')
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();
            $temp_qrc['complaint_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('process_id',$process_id)
            ->whereIn('qrc_2',['Complaint','DNA'])
           // ->where('qrc_2','Complaint')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

        }

        $final_data['qrc'] = $temp_qrc;


        // starts Process Wise Score
        $partner_list = Partner::where('client_id',$client_id)->with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->get();
        $partner_process_list=[];
        foreach ($partner_list as $akey => $avalue) {
            foreach ($avalue->partner_process as $bkey => $bvalue) {
                $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
            }
        }
      
        $loop=1;
        foreach ($partner_process_list as $key => $value) {

            $process_audit_data['score_sum'] = Audit::where('client_id',$client_id)                                                ->where('process_id',$key)
                                                     ->whereDate('audit_date','>=',$month_first_data)
                                                     ->whereDate('audit_date','<=',$today)
                                                     ->sum('overall_score');
            $process_audit_data['audit_count'] = Audit::where('client_id',$client_id)
                                                        ->where('process_id',$key)
                                                        ->whereDate('audit_date','>=',$month_first_data)
                                                        ->whereDate('audit_date','<=',$today)
                                                        ->count();
            $process_audit_data['score_with_fatal'] = Audit::where('client_id',$client_id)
                                                     ->where('process_id',$key)
                                                     ->whereDate('audit_date','>=',$month_first_data)
                                                     ->whereDate('audit_date','<=',$today)
                                                     ->sum('with_fatal_score_per');


            if($process_audit_data['audit_count']) {
                $process_audit_data['score'] = round(($process_audit_data['score_sum']/$process_audit_data['audit_count']));
                $process_audit_data['scored_with_fatal'] = round(($process_audit_data['score_with_fatal']/$process_audit_data['audit_count']));
            }            
            else {
                $process_audit_data['score'] = 0;
                $process_audit_data['scored_with_fatal'] = 0;
            }           

            $partner_process_list[$key]['data'] = $process_audit_data;

            if($loop==1)
                $partner_process_list[$key]['class'] = true;
            else
                $partner_process_list[$key]['class']=false;

            $loop++;

        }

        $final_data['pws'] = $partner_process_list;
        // ends Process Wise Score

        // Partner & Location Wise Report    
                
        return $final_data;
       
   } */

    public function get_qrc_lob_wise_welcome_dashboard($process_id,$month_first_data,$today){
        // starts QRC
        if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
            $client_id=9;
        } else {
            if(Auth::user()->id == 41){
                $client_id = 1;
            }else {
                if(Auth::user()->hasRole('partner-quality-head')){

                    $partner_details = PartnersProcessSpoc::where('user_id',Auth::user()->id)->first();
                    if(Auth::user()->id == 307 || Auth::user()->id == 308) {
                        $user_details = Partner::where('id',35)->first();
                        $client_id = 13;
                    } else {
                        $user_details = Partner::where('id',$partner_details->partner_id)->first();
                        $client_id = $user_details->client_id;
                    }
                    
                }else {
                    if(Auth::user()->client_detail) {
                        $client_id = Auth::user()->client_detail->client_id;
                    } else {
                        $client_id = Auth::user()->parent_client;
                    }
                }
            }
            
        }
    

        if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) { 
            $temp_qrc['query_count'] = Audit::where('client_id',$client_id)->where('partner_id',32)->whereIn('process_id',array(25,26,30))->where('qrc_2','Query')

            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['query_fatal_count'] = 
            Audit::where('client_id',$client_id)
        
            ->where('partner_id',32)->whereIn('process_id',array(25,26,30))
            ->where('qrc_2','Query')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['request_count'] = 
            Audit::where('client_id',$client_id)
        
            ->where('partner_id',32)->whereIn('process_id',array(25,26,30))
                ->where('qrc_2','Request')
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();

            $temp_qrc['request_fatal_count'] = 
            Audit::where('client_id',$client_id)
        
            ->where('partner_id',32)->whereIn('process_id',array(25,26,30))
            ->where('qrc_2','Request')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['complaint_count'] = 
            Audit::where('client_id',$client_id)
        
            ->where('partner_id',32)->whereIn('process_id',array(25,26,30))
            ->where('qrc_2','Complaint')
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['complaint_fatal_count'] = 
            Audit::where('client_id',$client_id)
            
            ->where('partner_id',32)->whereIn('process_id',array(25,26,30))
            ->where('qrc_2','Complaint')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            // Add Rebuttal on welcome dashboard on PWS 
            $rebuttal_data_process_query = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id,$process_id,$month_first_data,$today,$temp_qrc['query_count'],['Query','FTR']);
            
            $temp_qrc['query_rebuttal_per'] = $rebuttal_data_process_query['rebuttal_per'];
            $temp_qrc['query_accepted_per'] = $rebuttal_data_process_query['accepted_per'];
            $temp_qrc['query_rejected_per'] = $rebuttal_data_process_query['rejected_per'];
            $temp_qrc['query_raised_process'] = $rebuttal_data_process_query['raised'];
            $temp_qrc['query_accepted_process'] = $rebuttal_data_process_query['accepted'];
            $temp_qrc['query_rejected_process'] = $rebuttal_data_process_query['rejected'];
        
            
            $rebuttal_data_process_request = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id,$process_id,$month_first_data,$today,$temp_qrc['request_count'],['NFTR','Request']);
            
            $temp_qrc['request_rebuttal_per'] = $rebuttal_data_process_request['rebuttal_per'];
            $temp_qrc['request_accepted_per'] = $rebuttal_data_process_request['accepted_per'];
            $temp_qrc['request_rejected_per'] = $rebuttal_data_process_request['rejected_per'];
            $temp_qrc['request_raised_process'] = $rebuttal_data_process_request['raised'];
            $temp_qrc['request_accepted_process'] = $rebuttal_data_process_request['accepted'];
            $temp_qrc['request_rejected_process'] = $rebuttal_data_process_request['rejected'];
        
            $rebuttal_data_process_complain = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id,$process_id,$month_first_data,$today,$temp_qrc['complaint_count'],['Complaint','DNA']);
            
            $temp_qrc['complain_rebuttal_per'] = $rebuttal_data_process_complain['rebuttal_per'];
            $temp_qrc['complain_accepted_per'] = $rebuttal_data_process_complain['accepted_per'];
            $temp_qrc['complain_rejected_per'] = $rebuttal_data_process_complain['rejected_per'];
            $temp_qrc['complain_raised_process'] = $rebuttal_data_process_complain['raised'];
            $temp_qrc['complain_accepted_process'] = $rebuttal_data_process_complain['accepted'];
            $temp_qrc['complain_rejected_process'] = $rebuttal_data_process_complain['rejected'];    
            // End Rebuttal on welcome dashboard on PWS

        } 
        else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                        
        $temp_qrc['query_count'] = Audit::where('client_id',$client_id)->where('partner_id',40)->where('process_id',23)->where('qrc_2','Query')
        
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();

        $temp_qrc['query_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',40)->where('process_id',23)
            ->where('qrc_2','Query')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['request_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',40)->where('process_id',23)
                ->where('qrc_2','Request')
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();

            $temp_qrc['request_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',40)->where('process_id',23)
            ->where('qrc_2','Request')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['complaint_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',40)->where('process_id',23)
            ->where('qrc_2','Complaint')
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['complaint_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',40)->where('process_id',23)
            ->where('qrc_2','Complaint')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            // Add Rebuttal on welcome dashboard on PWS 
            $rebuttal_data_process_query = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id,$process_id,$month_first_data,$today,$temp_qrc['query_count'],['Query','FTR']);
            
            $temp_qrc['query_rebuttal_per'] = $rebuttal_data_process_query['rebuttal_per'];
            $temp_qrc['query_accepted_per'] = $rebuttal_data_process_query['accepted_per'];
            $temp_qrc['query_rejected_per'] = $rebuttal_data_process_query['rejected_per'];
            $temp_qrc['query_raised_process'] = $rebuttal_data_process_query['raised'];
            $temp_qrc['query_accepted_process'] = $rebuttal_data_process_query['accepted'];
            $temp_qrc['query_rejected_process'] = $rebuttal_data_process_query['rejected'];
        
            
            $rebuttal_data_process_request = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id,$process_id,$month_first_data,$today,$temp_qrc['request_count'],['NFTR','Request']);
            
            $temp_qrc['request_rebuttal_per'] = $rebuttal_data_process_request['rebuttal_per'];
            $temp_qrc['request_accepted_per'] = $rebuttal_data_process_request['accepted_per'];
            $temp_qrc['request_rejected_per'] = $rebuttal_data_process_request['rejected_per'];
            $temp_qrc['request_raised_process'] = $rebuttal_data_process_request['raised'];
            $temp_qrc['request_accepted_process'] = $rebuttal_data_process_request['accepted'];
            $temp_qrc['request_rejected_process'] = $rebuttal_data_process_request['rejected'];
        
            $rebuttal_data_process_complain = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id,$process_id,$month_first_data,$today,$temp_qrc['complaint_count'],['Complaint','DNA']);
            
            $temp_qrc['complain_rebuttal_per'] = $rebuttal_data_process_complain['rebuttal_per'];
            $temp_qrc['complain_accepted_per'] = $rebuttal_data_process_complain['accepted_per'];
            $temp_qrc['complain_rejected_per'] = $rebuttal_data_process_complain['rejected_per'];
            $temp_qrc['complain_raised_process'] = $rebuttal_data_process_complain['raised'];
            $temp_qrc['complain_accepted_process'] = $rebuttal_data_process_complain['accepted'];
            $temp_qrc['complain_rejected_process'] = $rebuttal_data_process_complain['rejected'];
            // End Rebuttal on welcome dashboard on PWS


        }
        elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
        $temp_qrc['query_count'] = Audit::where('client_id',$client_id)->where('partner_id',41)->where('process_id',31)->where('qrc_2','Query')
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();

        $temp_qrc['query_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',41)->where('process_id',31)
            ->where('qrc_2','Query')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['request_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',41)->where('process_id',31)
                ->where('qrc_2','Request')
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();

            $temp_qrc['request_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',41)->where('process_id',31)
            ->where('qrc_2','Request')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['complaint_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',41)->where('process_id',31)
            ->where('qrc_2','Complaint')
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['complaint_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',41)->where('process_id',31)
            ->where('qrc_2','Complaint')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();  
            
            // Add Rebuttal on welcome dashboard on PWS 
            $rebuttal_data_process_query = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id,$process_id,$month_first_data,$today,$temp_qrc['query_count'],['Query','FTR']);
            
            $temp_qrc['query_rebuttal_per'] = $rebuttal_data_process_query['rebuttal_per'];
            $temp_qrc['query_accepted_per'] = $rebuttal_data_process_query['accepted_per'];
            $temp_qrc['query_rejected_per'] = $rebuttal_data_process_query['rejected_per'];
            $temp_qrc['query_raised_process'] = $rebuttal_data_process_query['raised'];
            $temp_qrc['query_accepted_process'] = $rebuttal_data_process_query['accepted'];
            $temp_qrc['query_rejected_process'] = $rebuttal_data_process_query['rejected'];
        
            
            $rebuttal_data_process_request = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id,$process_id,$month_first_data,$today,$temp_qrc['request_count'],['NFTR','Request']);
            
            $temp_qrc['request_rebuttal_per'] = $rebuttal_data_process_request['rebuttal_per'];
            $temp_qrc['request_accepted_per'] = $rebuttal_data_process_request['accepted_per'];
            $temp_qrc['request_rejected_per'] = $rebuttal_data_process_request['rejected_per'];
            $temp_qrc['request_raised_process'] = $rebuttal_data_process_request['raised'];
            $temp_qrc['request_accepted_process'] = $rebuttal_data_process_request['accepted'];
            $temp_qrc['request_rejected_process'] = $rebuttal_data_process_request['rejected'];
        
            $rebuttal_data_process_complain = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id,$process_id,$month_first_data,$today,$temp_qrc['complaint_count'],['Complaint','DNA']);
            
            $temp_qrc['complain_rebuttal_per'] = $rebuttal_data_process_complain['rebuttal_per'];
            $temp_qrc['complain_accepted_per'] = $rebuttal_data_process_complain['accepted_per'];
            $temp_qrc['complain_rejected_per'] = $rebuttal_data_process_complain['rejected_per'];
            $temp_qrc['complain_raised_process'] = $rebuttal_data_process_complain['raised'];
            $temp_qrc['complain_accepted_process'] = $rebuttal_data_process_complain['accepted'];
            $temp_qrc['complain_rejected_process'] = $rebuttal_data_process_complain['rejected'];
            // End Rebuttal on welcome dashboard on PWS


        }
        elseif(Auth::user()->id == 256 || Auth::user()->id == 255)  {
                    
        $temp_qrc['query_count'] = Audit::where('client_id',$client_id)->where('partner_id',44)->where('process_id',32)->where('qrc_2','Query')
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();

        $temp_qrc['query_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',44)->where('process_id',32)
            ->where('qrc_2','Query')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['request_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',44)->where('process_id',32)
                ->where('qrc_2','Request')
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();

            $temp_qrc['request_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',44)->where('process_id',32)
            ->where('qrc_2','Request')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['complaint_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',44)->where('process_id',32)
            ->where('qrc_2','Complaint')
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            $temp_qrc['complaint_fatal_count'] = 
            Audit::where('client_id',$client_id)
            ->where('partner_id',44)->where('process_id',32)
            ->where('qrc_2','Complaint')
            ->where('is_critical',1)
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();

            // Add Rebuttal on welcome dashboard on PWS 
            $rebuttal_data_process_query = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id,$process_id,$month_first_data,$today,$temp_qrc['query_count'],['Query','FTR']);
            
            $temp_qrc['query_rebuttal_per'] = $rebuttal_data_process_query['rebuttal_per'];
            $temp_qrc['query_accepted_per'] = $rebuttal_data_process_query['accepted_per'];
            $temp_qrc['query_rejected_per'] = $rebuttal_data_process_query['rejected_per'];
            $temp_qrc['query_raised_process'] = $rebuttal_data_process_query['raised'];
            $temp_qrc['query_accepted_process'] = $rebuttal_data_process_query['accepted'];
            $temp_qrc['query_rejected_process'] = $rebuttal_data_process_query['rejected'];
        
            
            $rebuttal_data_process_request = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id,$process_id,$month_first_data,$today,$temp_qrc['request_count'],['NFTR','Request']);
            
            $temp_qrc['request_rebuttal_per'] = $rebuttal_data_process_request['rebuttal_per'];
            $temp_qrc['request_accepted_per'] = $rebuttal_data_process_request['accepted_per'];
            $temp_qrc['request_rejected_per'] = $rebuttal_data_process_request['rejected_per'];
            $temp_qrc['request_raised_process'] = $rebuttal_data_process_request['raised'];
            $temp_qrc['request_accepted_process'] = $rebuttal_data_process_request['accepted'];
            $temp_qrc['request_rejected_process'] = $rebuttal_data_process_request['rejected'];
        
            $rebuttal_data_process_complain = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id,$process_id,$month_first_data,$today,$temp_qrc['complaint_count'],['Complaint','DNA']);
            
            $temp_qrc['complain_rebuttal_per'] = $rebuttal_data_process_complain['rebuttal_per'];
            $temp_qrc['complain_accepted_per'] = $rebuttal_data_process_complain['accepted_per'];
            $temp_qrc['complain_rejected_per'] = $rebuttal_data_process_complain['rejected_per'];
            $temp_qrc['complain_raised_process'] = $rebuttal_data_process_complain['raised'];
            $temp_qrc['complain_accepted_process'] = $rebuttal_data_process_complain['accepted'];
            $temp_qrc['complain_rejected_process'] = $rebuttal_data_process_complain['rejected'];     
            // End Rebuttal on welcome dashboard on PWS

        }

        elseif(Auth::user()->id == 314 || Auth::user()->id == 315)  {
                    
            $temp_qrc['query_count'] = Audit::where('client_id',$client_id)->where('partner_id',52)->whereIn('process_id',[25,26,30])->where('qrc_2','Query')
            ->whereDate('audit_date','>=',$month_first_data)
            ->whereDate('audit_date','<=',$today)
            ->count();
    
            $temp_qrc['query_fatal_count'] = 
                Audit::where('client_id',$client_id)
                ->where('partner_id',52)->whereIn('process_id',[25,26,30])
                ->where('qrc_2','Query')
                ->where('is_critical',1)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();
    
                $temp_qrc['request_count'] = 
                Audit::where('client_id',$client_id)
                ->where('partner_id',52)->whereIn('process_id',[25,26,30])
                    ->where('qrc_2','Request')
                    ->whereDate('audit_date','>=',$month_first_data)
                    ->whereDate('audit_date','<=',$today)
                    ->count();
    
                $temp_qrc['request_fatal_count'] = 
                Audit::where('client_id',$client_id)
                ->where('partner_id',52)->whereIn('process_id',[25,26,30])
                ->where('qrc_2','Request')
                ->where('is_critical',1)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();
    
                $temp_qrc['complaint_count'] = 
                Audit::where('client_id',$client_id)
                ->where('partner_id',52)->whereIn('process_id',[25,26,30])
                ->where('qrc_2','Complaint')
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();
    
                $temp_qrc['complaint_fatal_count'] = 
                Audit::where('client_id',$client_id)
                ->where('partner_id',52)->whereIn('process_id',[25,26,30])
                ->where('qrc_2','Complaint')
                ->where('is_critical',1)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();
    
                // Add Rebuttal on welcome dashboard on PWS 
                $rebuttal_data_process_query = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id,$process_id,$month_first_data,$today,$temp_qrc['query_count'],['Query','FTR']);
                
                $temp_qrc['query_rebuttal_per'] = $rebuttal_data_process_query['rebuttal_per'];
                $temp_qrc['query_accepted_per'] = $rebuttal_data_process_query['accepted_per'];
                $temp_qrc['query_rejected_per'] = $rebuttal_data_process_query['rejected_per'];
                $temp_qrc['query_raised_process'] = $rebuttal_data_process_query['raised'];
                $temp_qrc['query_accepted_process'] = $rebuttal_data_process_query['accepted'];
                $temp_qrc['query_rejected_process'] = $rebuttal_data_process_query['rejected'];
            
                
                $rebuttal_data_process_request = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id,$process_id,$month_first_data,$today,$temp_qrc['request_count'],['NFTR','Request']);
                
                $temp_qrc['request_rebuttal_per'] = $rebuttal_data_process_request['rebuttal_per'];
                $temp_qrc['request_accepted_per'] = $rebuttal_data_process_request['accepted_per'];
                $temp_qrc['request_rejected_per'] = $rebuttal_data_process_request['rejected_per'];
                $temp_qrc['request_raised_process'] = $rebuttal_data_process_request['raised'];
                $temp_qrc['request_accepted_process'] = $rebuttal_data_process_request['accepted'];
                $temp_qrc['request_rejected_process'] = $rebuttal_data_process_request['rejected'];
            
                $rebuttal_data_process_complain = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id,$process_id,$month_first_data,$today,$temp_qrc['complaint_count'],['Complaint','DNA']);
                
                $temp_qrc['complain_rebuttal_per'] = $rebuttal_data_process_complain['rebuttal_per'];
                $temp_qrc['complain_accepted_per'] = $rebuttal_data_process_complain['accepted_per'];
                $temp_qrc['complain_rejected_per'] = $rebuttal_data_process_complain['rejected_per'];
                $temp_qrc['complain_raised_process'] = $rebuttal_data_process_complain['raised'];
                $temp_qrc['complain_accepted_process'] = $rebuttal_data_process_complain['accepted'];
                $temp_qrc['complain_rejected_process'] = $rebuttal_data_process_complain['rejected'];     
                // End Rebuttal on welcome dashboard on PWS
    
            }
        else {
            $temp_qrc['query_count'] = 
                Audit::where('client_id',$client_id)->whereIn('qrc_2',['Query','FTR','Enquiry'])
                ->where('process_id',$process_id)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();
            $temp_qrc['query_fatal_count'] = 
                Audit::where('client_id',$client_id)
                ->where('process_id',$process_id)
                ->whereIn('qrc_2',['Query','FTR','Enquiry'])
                ->where('is_critical',1)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();
            $temp_qrc['request_count'] = 
                Audit::where('client_id',$client_id)
                ->where('process_id',$process_id)
                ->whereIn('qrc_2',['NFTR','Request'])
                // ->where('qrc_2','Request')
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();
            $temp_qrc['request_fatal_count'] = 
                Audit::where('client_id',$client_id)
                ->where('process_id',$process_id)
                ->whereIn('qrc_2',['NFTR','Request'])
                // ->where('qrc_2','Request')
                ->where('is_critical',1)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();
            $temp_qrc['complaint_count'] = 
                Audit::where('client_id',$client_id)
                ->where('process_id',$process_id)
                ->whereIn('qrc_2',['Complaint','DNA'])
                // ->where('qrc_2','Complaint')
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();
            $temp_qrc['complaint_fatal_count'] = 
                Audit::where('client_id',$client_id)
                ->where('process_id',$process_id)
                ->whereIn('qrc_2',['Complaint','DNA'])
                // ->where('qrc_2','Complaint')
                ->where('is_critical',1)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();

            
            // Add Rebuttal on welcome dashboard on PWS 
            $rebuttal_data_process_query = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id,$process_id,$month_first_data,$today,$temp_qrc['query_count'],['Query','FTR','Enquiry']);
            
            $temp_qrc['query_rebuttal_per'] = $rebuttal_data_process_query['rebuttal_per'];
            $temp_qrc['query_accepted_per'] = $rebuttal_data_process_query['accepted_per'];
            $temp_qrc['query_rejected_per'] = $rebuttal_data_process_query['rejected_per'];
            $temp_qrc['query_raised_process'] = $rebuttal_data_process_query['raised'];
            $temp_qrc['query_accepted_process'] = $rebuttal_data_process_query['accepted'];
            $temp_qrc['query_rejected_process'] = $rebuttal_data_process_query['rejected'];
        
            
            $rebuttal_data_process_request = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id,$process_id,$month_first_data,$today,$temp_qrc['request_count'],['NFTR','Request']);
            
            $temp_qrc['request_rebuttal_per'] = $rebuttal_data_process_request['rebuttal_per'];
            $temp_qrc['request_accepted_per'] = $rebuttal_data_process_request['accepted_per'];
            $temp_qrc['request_rejected_per'] = $rebuttal_data_process_request['rejected_per'];
            $temp_qrc['request_raised_process'] = $rebuttal_data_process_request['raised'];
            $temp_qrc['request_accepted_process'] = $rebuttal_data_process_request['accepted'];
            $temp_qrc['request_rejected_process'] = $rebuttal_data_process_request['rejected'];
        
            $rebuttal_data_process_complain = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id,$process_id,$month_first_data,$today,$temp_qrc['complaint_count'],['Complaint','DNA']);
            
            $temp_qrc['complain_rebuttal_per'] = $rebuttal_data_process_complain['rebuttal_per'];
            $temp_qrc['complain_accepted_per'] = $rebuttal_data_process_complain['accepted_per'];
            $temp_qrc['complain_rejected_per'] = $rebuttal_data_process_complain['rejected_per'];
            $temp_qrc['complain_raised_process'] = $rebuttal_data_process_complain['raised'];
            $temp_qrc['complain_accepted_process'] = $rebuttal_data_process_complain['accepted'];
            $temp_qrc['complain_rejected_process'] = $rebuttal_data_process_complain['rejected'];    
            // End Rebuttal on welcome dashboard on PWS

        }

        $final_data['qrc'] = $temp_qrc;

        /* echo "<pre>";
        print_r($final_data['qrc']);
        dd(); */
        // starts Process Wise Score
        $partner_list = Partner::where('client_id',$client_id)->with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->get();
        $partner_process_list=[];
        foreach ($partner_list as $akey => $avalue) {
            foreach ($avalue->partner_process as $bkey => $bvalue) {
                $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
            }
        }
    
        $loop=1;
        foreach ($partner_process_list as $key => $value) {

            $process_audit_data['score_sum'] = Audit::where('client_id',$client_id)                                                ->where('process_id',$key)
                                                    ->whereDate('audit_date','>=',$month_first_data)
                                                    ->whereDate('audit_date','<=',$today)
                                                    ->sum('overall_score');
            $process_audit_data['audit_count'] = Audit::where('client_id',$client_id)
                                                        ->where('process_id',$key)
                                                        ->whereDate('audit_date','>=',$month_first_data)
                                                        ->whereDate('audit_date','<=',$today)
                                                        ->count();
            $process_audit_data['score_with_fatal'] = Audit::where('client_id',$client_id)
                                                    ->where('process_id',$key)
                                                    ->whereDate('audit_date','>=',$month_first_data)
                                                    ->whereDate('audit_date','<=',$today)
                                                    ->sum('with_fatal_score_per');


            if($process_audit_data['audit_count']) {
                $process_audit_data['score'] = round(($process_audit_data['score_sum']/$process_audit_data['audit_count']));
                $process_audit_data['scored_with_fatal'] = round(($process_audit_data['score_with_fatal']/$process_audit_data['audit_count']));
            }            
            else {
                $process_audit_data['score'] = 0;
                $process_audit_data['scored_with_fatal'] = 0;
            }           

            $partner_process_list[$key]['data'] = $process_audit_data;

            if($loop==1)
                $partner_process_list[$key]['class'] = true;
            else
                $partner_process_list[$key]['class']=false;

            $loop++;

        }

        $final_data['pws'] = $partner_process_list;
        // ends Process Wise Score

        // Partner & Location Wise Report    
                
        return $final_data;
   
    }

    /* public function welcome_dashboard_new(Request $request)
    {
        

       
        if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
            $client_id=9;
        } else {
            $client_id = Auth::user()->client_detail->client_id;
        }

        if($request->isMethod('post')){
          
                $dates = explode(" ", $request->date);
                $month_first_data = $dates[0];
                $today = $dates[1];
                $audit_cycle_data = Auditcycle::where('start_date',$month_first_data)->where('end_date', $today)->first();
                $month=$audit_cycle_data->name;
           
           
        }else {
            
            $audit_cyle_data = Auditcycle::where('client_id',$client_id)->orderby('start_date','desc')->first();
            $month_first_data = $audit_cyle_data->start_date;
            $today = $audit_cyle_data->end_date;

            //$audit_cycle_data = Auditcycle::orderBy('id','desc')->limit(1)->get();
            
            $month=$audit_cyle_data->name;
          
        }

       

        // starts rebuttal 
          $rebuttal_data = [];
          
           if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) { 
                $audit_data = Audit::with('raw_data')->where('client_id',$client_id)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)->where('partner_id',32)->whereIn('process_id',array(25,26,30))->get();
                $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->where('partner_id',32)->whereIn('process_id',array(25,26,30))->orderby('id','asc')->get();
           } 
           else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                 $audit_data = Audit::with('raw_data')->where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->where('partner_id',40)->where('process_id',23)->get();
                $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->where('partner_id',40)->where('process_id',23)->orderby('id','asc')->get();
           }
           elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $audit_data = Audit::with('raw_data')->where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->where('partner_id',41)->where('process_id',31)->get();
                $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->where('partner_id',41)->where('process_id',31)->orderby('id','asc')->get();
           }
            
           elseif(Auth::user()->id == 252 || Auth::user()->id == 269 || Auth::user()->id == 270 || Auth::user()->id == 271) {
            $audit_data = Audit::with('raw_data')->where('client_id',$client_id)
                           ->whereDate('audit_date','>=',$month_first_data)
                           ->whereDate('audit_date','<=',$today)->whereIn('process_id',[21,22])->get();
            $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->where('partner_id',41)->where('process_id',31)->orderby('id','asc')->get();
            
            }

           elseif(Auth::user()->id == 256 || Auth::user()->id == 255)  {
            $audit_data = Audit::with('raw_data')->where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->where('partner_id',44)->where('process_id',32)->get();
                $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->where('partner_id',44)->where('process_id',32)->orderby('id','asc')->get();
           }
           else {
                $audit_data =Audit::with('raw_data')->where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->get();
                $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->orderby('id','asc')->get();
           } 

           if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) { 
            $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('partner_id',32)->whereIn('process_id',array(25,26,30))->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
            ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
           } 
           else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('partner_id',40)->where('process_id',23)->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
            ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
           }
           elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('partner_id',41)->where('process_id',31)->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
            ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
           }
           elseif(Auth::user()->id == 256 || Auth::user()->id == 255)  {
              $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('partner_id',44)->where('process_id',32)->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
               ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
           }
           
           elseif(Auth::user()->id == 252 || Auth::user()->id == 269 || Auth::user()->id == 270 || Auth::user()->id == 271)  {
            $temp_rebuttal_data = Audit::where('client_id',$client_id)->whereIn('process_id',[21,22])->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
             ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
         }
           else {
                $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('rebuttal_status','>',0)
                                       ->whereDate('audit_date','>=',$month_first_data)
                                       ->whereDate('audit_date','<=',$today)
                                       ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
           }                   
          
         
        if($client_id==1)
        {
            $coverage['target'] = 500;
            $coverage['achived_per'] = round(($audit_data->count()/500)*100);
        }else if($client_id==9){
            //echo "jsa"; die;
            

            
            if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) { 
                //echo "here"; die;
                    $t1=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',25)->sum('month_targets.eq_audit_target_mtd');
                    
                    $t2=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',26)->sum('month_targets.eq_audit_target_mtd');
                    
                    $t3=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',30)->sum('month_targets.eq_audit_target_mtd'); $target=$t1+$t2+$t3;

            } 
            elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',23)->sum('month_targets.eq_audit_target_mtd');
            }
            elseif(Auth::user()->id == 139) {
                $t1=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',21)->sum('month_targets.eq_audit_target_mtd');
                $t2=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',22)->sum('month_targets.eq_audit_target_mtd');
                $target=$t1+$t2;
            }
            elseif(Auth::user()->id == 195) {
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',22)->sum('month_targets.eq_audit_target_mtd');
            }
            elseif(Auth::user()->id == 254) {
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',22)->sum('month_targets.eq_audit_target_mtd');
            }
            elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',31)->sum('month_targets.eq_audit_target_mtd');
            }
            elseif(Auth::user()->id == 252 || Auth::user()->id == 269 || Auth::user()->id == 270 || Auth::user()->id == 271) {
                $t1=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',21)->sum('month_targets.eq_audit_target_mtd');
            $t2=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',22)->sum('month_targets.eq_audit_target_mtd');
            $target=$t1+$t2;
            }
            elseif(Auth::user()->id == 256 || Auth::user()->id == 255) { 
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',32)->sum('month_targets.eq_audit_target_mtd');
            }
             else {
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->sum('month_targets.eq_audit_target_mtd');
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

            //$coverage['target'] = 1950;
            
        }     
        else
        {
            $coverage['target'] = 22000;
            $coverage['achived_per'] = round(($audit_data->count()/22000)*100);
        }
        
        $coverage['achived'] = $audit_data->count();
        

        $final_data['coverage'] = $coverage;

        $temp_total_rebuttal = 0;
        $temp_accepted_rebuttal = 0;
        $temp_rejected_rebuttal = 0;

        foreach ($temp_rebuttal_data as $key => $value) {
                $temp_total_rebuttal += $value->audit_rebuttal->count();
                $temp_accepted_rebuttal += $value->audit_rebuttal_accepted->count();
                $temp_rejected_rebuttal += $value->audit_rebuttal_rejected->count();
        }

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
            $rebuttal_data['rejected_per'] = round((($rebuttal_data['rejected']/$audit_data->count())*100));
        else
            $rebuttal_data['rejected_per'] = 0;


        $final_data['rebuttal'] = $rebuttal_data;

        // ends rebuttal 

        // starts QRC
       
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
        if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {
                    
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',32)->get();
                } 
                else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                    
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',40)->get();
                }
                elseif(Auth::user()->id == 249 || Auth::user()->id == 250){
                   
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',41)->get();
                }

                elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                    
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',44)->get();
                }

                elseif(Auth::user()->id == 139) {
                    
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',38)->get();
                }

                elseif (Auth::user()->id == 195) {
                   
                   $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',39)->get();
                }

                elseif (Auth::user()->id == 254) {
                   
                   $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',43)->get();
                } elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',38)->orwhere('id',39)->orwhere('id',43)->orWhere('id',45)->get();
                } elseif(Auth::user()->id == 270 || Auth::user()->id == 271) {
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',38)->orWhere('id',45)->get();
                }
                else  {
                    $partner_list = Partner::where('client_id',$client_id)->with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->get();
                }
        $partner_process_list=[];
        foreach ($partner_list as $akey => $avalue) {
            foreach ($avalue->partner_process as $bkey => $bvalue) {
                if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) {
                    if($bvalue->process_id == 25 || $bvalue->process_id == 26 || $bvalue->process_id == 30) {
                        $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                    }
                }
                elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                        if($bvalue->process_id == 23) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 139) {
                        if($bvalue->process_id == 21 || $bvalue->process_id == 22) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 195) {
                        if($bvalue->process_id == 22) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 254) {
                        if($bvalue->process_id == 22) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                        if($bvalue->process_id == 31) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 252 || Auth::user()->id == 269 || Auth::user()->id == 270 || Auth::user()->id == 271) {
                        if($bvalue->process_id == 21 || $bvalue->process_id == 22) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                        if($bvalue->process_id == 32) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    } else {
                        $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                    }
            }
        }

        $loop=1;
        $final_score=0;
        $final_scorable=0;
        foreach ($partner_process_list as $key => $value) {

            $process_audit_data['score_sum'] = Audit::where('client_id',$client_id)
                                         ->where('process_id',$key)
                                         ->whereDate('audit_date','>=',$month_first_data)
                                         ->whereDate('audit_date','<=',$today)
                                         ->sum('overall_score');


                                         

        $process_audit_data['audit_count'] = Audit::where('client_id',$client_id)
        ->where('process_id',$key)
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();

         $process_audit_data['with_fatal'] = Audit::where('client_id',$client_id)
        ->where('process_id',$key)
        //->where('is_critical',1)
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->sum('with_fatal_score_per');
            if($process_audit_data['audit_count']) {

                
            $process_audit_data['scored_with_fatal'] = round(   ($process_audit_data['with_fatal']/$process_audit_data['audit_count']));
                
           
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
            $plr_audits = Audit::where('client_id',$client_id)->where('partner_id',$value->id)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)->get();
            $d['audit_count']=$plr_audits->count();
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
                $d['with_fatal'] = round(($fatal_audit_score_sum/$plr_audits->count()));
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
                    $a['process_id']=$bvalue->process->id;
                    $a['process_name']=$bvalue->process->name;
                    $a['location_id']=$lvalue->location_id;
                    $a['location']=$lvalue->location_detail->name;
                    $audits = Audit::where('client_id',$client_id)->where('process_id',$a['process_id'])->where('partner_id',$value->id)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)->whereHas('raw_data', function (Builder $query) use ($a) {
                            $query->where('partner_location_id', 'like', $a['location_id']);
                        })->get();
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
                        $a['with_fatal'] = round(($fatal_audit_score/$audits->count()));
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

        
               
        return view('dashboards.client_welcome_dashboard_new',compact('final_data','month_first_data','today'));
        //return response()->json(['status'=>200,'message'=>"Success",'data'=>$final_data], 200);
    } */

    public function welcome_dashboard_new(Request $request)
    {
        

        // Old logic is given blow
        /* $month_first_data = date('Y-m-01');
        $today = date('Y-m-d');  */

        
        if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
            $client_id=9;
        }else if(Auth::user()->id == 41) {
            $client_id=1;
        } else {

                
            if(Auth::user()->hasRole('partner-quality-head') || Auth::user()->hasRole('partner-admin') || Auth::user()->hasRole('partner-operation-head') || Auth::user()->hasRole('partner-training-head')){
                
                $partner_details = PartnersProcessSpoc::where('user_id',Auth::user()->id)->first();

                if(Auth::user()->id == 307 || Auth::user()->id == 308) {
                    
                    $user_details = Partner::where('id',48)->first();
                    $client_id = $user_details->client_id;
                } else {
                    
                    if(Auth::user()->id ==291 ){
                        
                        $client_id = 13;
                    }else {
                        /* echo "hii  ";
                        die; */
                        if(isset($partner_details->partner_id)){
                            $user_details = Partner::where('id',$partner_details->partner_id)->first();
                        }
                        else {
                            $user_details = Partner::where('admin_user_id',Auth::user()->id)->first();
                        }
                        
                        $client_id = $user_details->client_id;
                        
                    }
                    
                }
                
            }else {
                if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
                
            }
            
        }

        if($request->isMethod('post')){
          
                $dates = explode(" ", $request->date);
                $month_first_data = $dates[0];
                $today = $dates[1];
                $audit_cycle_data = Auditcycle::where('start_date',$month_first_data)->where('end_date', $today)->first();
                $month=$audit_cycle_data->name;
           
           
        }else {
            
            $audit_cyle_data = Auditcycle::where('client_id',$client_id)->orderby('start_date','desc')->first();
            $month_first_data = $audit_cyle_data->start_date;
            $today = $audit_cyle_data->end_date;

            //$audit_cycle_data = Auditcycle::orderBy('id','desc')->limit(1)->get();
            
            $month=$audit_cyle_data->name;
           // $month=date('M');

           /* echo $month;
           dd(); */

        }

       

        // starts rebuttal 
          $rebuttal_data = [];
          
           if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) { 
                $audit_data = Audit::with('raw_data')->where('client_id',$client_id)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)->where('partner_id',32)->whereIn('process_id',array(25,26,30))->get();
                $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->where('partner_id',32)->whereIn('process_id',array(25,26,30))->orderby('id','asc')->get();
           } 
           else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                 $audit_data = Audit::with('raw_data')->where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->where('partner_id',40)->where('process_id',23)->get();
                $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->where('partner_id',40)->where('process_id',23)->orderby('id','asc')->get();
           }
           elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $audit_data = Audit::with('raw_data')->where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->where('partner_id',41)->where('process_id',31)->get();
                $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->where('partner_id',41)->where('process_id',31)->orderby('id','asc')->get();
           }
            
           elseif(Auth::user()->id == 252 || Auth::user()->id == 269 || Auth::user()->id == 270 || Auth::user()->id == 271) {
            $audit_data = Audit::with('raw_data')->where('client_id',$client_id)
                           ->whereDate('audit_date','>=',$month_first_data)
                           ->whereDate('audit_date','<=',$today)->whereIn('process_id',[21,22])->get();
            $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->whereIn('partner_id',[38,39,43,45])->whereIn('process_id',[21,22])->orderby('id','asc')->get();
            
            }

            elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {
            $audit_data = Audit::with('raw_data')->where('client_id',$client_id)
                           ->whereDate('audit_date','>=',$month_first_data)
                           ->whereDate('audit_date','<=',$today)->whereIn('process_id',[21,22])->get();
            $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->where('partner_id',[38,45,43,39])->whereIn('process_id',[21,22])->orderby('id','asc')->get();
            
            }

           elseif(Auth::user()->id == 256 || Auth::user()->id == 255)  {
            $audit_data = Audit::with('raw_data')->where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->where('partner_id',44)->where('process_id',32)->get();
                $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->where('partner_id',44)->where('process_id',32)->orderby('id','asc')->get();
           }
            elseif(Auth::user()->id == 307 || Auth::user()->id == 308)  {
            $audit_data = Audit::with('raw_data')->where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->where('partner_id',48)->get();
                $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->where('partner_id',48)->where('process_id',35)->orderby('id','asc')->get();
           }
           elseif(Auth::user()->id == 314 || Auth::user()->id == 315)  {
            $audit_data = Audit::with('raw_data')->where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->where('partner_id',52)->get();
                $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->where('partner_id',52)->whereIn('process_id',[25,26,30])->orderby('id','asc')->get();
           }
           else {
                $audit_data =Audit::with('raw_data')->where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->get();
                $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->orderby('id','asc')->get();
           } 

           if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) { 
            $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('partner_id',32)->whereIn('process_id',array(25,26,30))->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
            ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
           } 
           else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('partner_id',40)->where('process_id',23)->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
            ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
           }
           elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('partner_id',41)->where('process_id',31)->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
            ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
           }
           elseif(Auth::user()->id == 256 || Auth::user()->id == 255)  {
              $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('partner_id',44)->where('process_id',32)->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
               ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
           }
           
           elseif(Auth::user()->id == 252 || Auth::user()->id == 269 || Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 279 || Auth::user()->id == 283)  {
            $temp_rebuttal_data = Audit::where('client_id',$client_id)->whereIn('process_id',[21,22])->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
             ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
         }
         elseif(Auth::user()->id == 307 || Auth::user()->id == 308)  {
            $temp_rebuttal_data = Audit::where('client_id',$client_id)->whereIn('process_id',[35])->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
             ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
         }
         elseif(Auth::user()->id == 314 )  {
            $temp_rebuttal_data = Audit::where('client_id',$client_id)->whereIn('process_id',[25,26,30])->where('partner_id',52)->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
             ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
         }
         elseif(Auth::user()->id == 315 )  {
            $temp_rebuttal_data = Audit::where('client_id',$client_id)->whereIn('process_id',[26])->where('partner_id',52)->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
             ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
         }
           else {
                $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('rebuttal_status','>',0)
                                       ->whereDate('audit_date','>=',$month_first_data)
                                       ->whereDate('audit_date','<=',$today)
                                       ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
           }                   
          
          /*$temp_rebuttal_data = Audit::where('client_id',$client_id)->where('rebuttal_status','>',0)
                                       ->whereDate('audit_date','>=',$month_first_data)
                                       ->whereDate('audit_date','<=',$today)
                                       ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get(); */
        //dd($audit_data); 
        if($client_id==1)
        {
            $coverage['target'] = 500;
            $coverage['achived_per'] = round(($audit_data->count()/500)*100);
        }else if($client_id==9 || $client_id==13){
            //echo "jsa"; die;
            

            
            if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) { 
                //echo "here"; die;
                    $t1=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',25)->sum('month_targets.eq_audit_target_mtd');
                    
                    $t2=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',26)->sum('month_targets.eq_audit_target_mtd');
                    
                    $t3=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',30)->sum('month_targets.eq_audit_target_mtd'); $target=$t1+$t2+$t3;

            } 
            elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',23)->sum('month_targets.eq_audit_target_mtd');
            }
            elseif(Auth::user()->id == 139) {
                $t1=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',21)->sum('month_targets.eq_audit_target_mtd');
                $t2=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',22)->sum('month_targets.eq_audit_target_mtd');
                $target=$t1+$t2;
            }
            elseif(Auth::user()->id == 195) {
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',22)->sum('month_targets.eq_audit_target_mtd');
            }
            elseif(Auth::user()->id == 254) {
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',22)->sum('month_targets.eq_audit_target_mtd');
            }
            elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',31)->sum('month_targets.eq_audit_target_mtd');
            }
            elseif(Auth::user()->id == 252 || Auth::user()->id == 269 || Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 279  || Auth::user()->id == 283) {
                $t1=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',21)->sum('month_targets.eq_audit_target_mtd');
            $t2=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',22)->sum('month_targets.eq_audit_target_mtd');
            $target=$t1+$t2;
            }
            elseif(Auth::user()->id == 256 || Auth::user()->id == 255) { 
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',32)->sum('month_targets.eq_audit_target_mtd');
            }
            elseif(Auth::user()->id == 314) { 
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->whereIn('month_targets.process_id',[25,26,30])->where('month_targets.partner_id',52)->sum('month_targets.eq_audit_target_mtd');
            }
            elseif(Auth::user()->id == 315) { 
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->whereIn('month_targets.process_id',[26])->where('month_targets.partner_id',52)->sum('month_targets.eq_audit_target_mtd');
            }
            /* elseif(Auth::user()->id == 307 || Auth::user()->id == 308) { 
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',35)->sum('month_targets.eq_audit_target_mtd');
            } */
             else {
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->sum('month_targets.eq_audit_target_mtd');
            }
            
             


           /*  $target = MonthTarget::where('client_id',$client_id)
            ->join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')
            
            
            ->where('month_of_target','like',"Jun%")
            ->sum('eq_audit_target_mtd');   */
           /*  echo $target;

            dd(); */

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

            //$coverage['target'] = 1950;
            
        }     
        else
        {
            $coverage['target'] = 22000;
            $coverage['achived_per'] = round(($audit_data->count()/22000)*100);
        }
        
        $coverage['achived'] = $audit_data->count();
        

        $final_data['coverage'] = $coverage;

        $temp_total_rebuttal = 0;
        $temp_accepted_rebuttal = 0;
        $temp_rejected_rebuttal = 0;

        foreach ($temp_rebuttal_data as $key => $value) {
                $temp_total_rebuttal += $value->audit_rebuttal->count();
                $temp_accepted_rebuttal += $value->audit_rebuttal_accepted->count();
                $temp_rejected_rebuttal += $value->audit_rebuttal_rejected->count();
        }

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
            $rebuttal_data['rejected_per'] = round((($rebuttal_data['rejected']/$audit_data->count())*100));
        else
            $rebuttal_data['rejected_per'] = 0;


        $final_data['rebuttal'] = $rebuttal_data;

        // ends rebuttal 

        // starts QRC
        /*$temp_qrc['query_count'] = Audit::where('client_id',$client_id)
                                          ->where('qrc_2','Query')
                                          ->whereDate('audit_date','>=',$month_first_data)
                                          ->whereDate('audit_date','<=',$today)
                                          ->count(); */
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
        if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {
                    
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',32)->get();
                } 
                else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                    
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',40)->get();
                }
                elseif(Auth::user()->id == 249 || Auth::user()->id == 250){
                   
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',41)->get();
                }

                elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                    
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',44)->get();
                }

                elseif(Auth::user()->id == 139) {
                    
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',38)->get();
                }

                elseif (Auth::user()->id == 195) {
                   
                   $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',39)->get();
                }

                elseif (Auth::user()->id == 254) {
                   
                   $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',43)->get();
                } 
                elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->whereIn('id',[38,45,43,39])->get();
                }
                elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',38)->orwhere('id',39)->orwhere('id',43)->orWhere('id',45)->get();
                } elseif(Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 280) {
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',38)->orWhere('id',45)->get();
                } elseif(Auth::user()->id == 282) {
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',39)->orWhere('id',43)->get();
                }
                elseif(Auth::user()->id == 307 || Auth::user()->id == 308) {
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',48)->get();
                }
                elseif(Auth::user()->id == 314 || Auth::user()->id == 315) {
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',52)->get();
                }
                else  {
                    $partner_list = Partner::where('client_id',$client_id)->with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->get();
                }
        $partner_process_list=[];
        foreach ($partner_list as $akey => $avalue) {
            foreach ($avalue->partner_process as $bkey => $bvalue) {
                if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) {
                    if($bvalue->process_id == 25 || $bvalue->process_id == 26 || $bvalue->process_id == 30) {
                        $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                    }
                }
                elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                        if($bvalue->process_id == 23) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 315) {
                        if($bvalue->process_id == 26) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 139) {
                        if($bvalue->process_id == 21 || $bvalue->process_id == 22) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 195) {
                        if($bvalue->process_id == 22) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 254) {
                        if($bvalue->process_id == 22) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                        if($bvalue->process_id == 31) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 252 || Auth::user()->id == 269 || Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 279 || Auth::user()->id == 283) {
                        if($bvalue->process_id == 21 || $bvalue->process_id == 22) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                        if($bvalue->process_id == 32) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif( Auth::user()->id == 308) {
                        if($bvalue->process_id == 35) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        } else if($bvalue->process_id == 34){
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        } else if($bvalue->process_id == 36){
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    } elseif(Auth::user()->id == 307 ) {
                        if($bvalue->process_id == 35) {
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

            if(Auth::user()->id == 314 || Auth::user()->id == 315) {

                $process_audit_data['score_sum'] = Audit::where('client_id',$client_id)
                                            ->where('process_id',$key)
                                            ->where('partner_id',52)
                                            ->whereDate('audit_date','>=',$month_first_data)
                                            ->whereDate('audit_date','<=',$today)
                                            ->sum('overall_score');

                $fatal_score_sum = DB::select("
                                            select sum(p.with_fatal_score) as fatal_sum from audit_parameter_results p inner join audits a
                                            on p.audit_id = a.id where a.process_id = ".$key." and a.client_id = ".$client_id." and a.partner_id = 52
                                            and a.audit_date >= '".$month_first_data."' and a.audit_date <= '".$today."'  and a.is_critical = 0");
                                
                $temp_wait_sum = DB::select("
                                            select sum(p.temp_weight) as temp_sum from audit_parameter_results p inner join audits a
                                            on p.audit_id = a.id where a.process_id = ".$key." and a.client_id = ".$client_id." and a.partner_id = 52
                                            and a.audit_date >= '".$month_first_data."' and a.audit_date <= '".$today."'");
                                            

                $process_audit_data['audit_count'] = Audit::where('client_id',$client_id)
                ->where('process_id',$key)
                ->where('partner_id',52)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();

                $process_audit_data['with_fatal'] = Audit::where('client_id',$client_id)
                ->where('process_id',$key)
                ->where('partner_id',52)
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
                                            and a.audit_date >= '".$month_first_data."' and a.audit_date <= '".$today."'  and a.is_critical = 0");
                                
                $temp_wait_sum = DB::select("
                                            select sum(p.temp_weight) as temp_sum from audit_parameter_results p inner join audits a
                                            on p.audit_id = a.id where a.process_id = ".$key." and a.client_id = ".$client_id."
                                            and a.audit_date >= '".$month_first_data."' and a.audit_date <= '".$today."'");
                                            

                $process_audit_data['audit_count'] = Audit::where('client_id',$client_id)
                ->where('process_id',$key)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();

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
            $plr_audits = Audit::where('client_id',$client_id)->where('partner_id',$value->id)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)->get();
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
                    $a['process_id']=$bvalue->process->id;
                    $a['process_name']=$bvalue->process->name;
                    $a['location_id']=$lvalue->location_id;
                    $a['location']=$lvalue->location_detail->name;
                    
                    
                    $audits = Audit::where('client_id',$client_id)->where('process_id',$a['process_id'])->where('partner_id',$value->id)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)->whereHas('raw_data', function (Builder $query) use ($a) {
                            $query->where('partner_location_id', 'like', $a['location_id']);
                        })->get();
                    
                    
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

    public function get_client_welcome_data()
    {
        

// Old logic is given blow
        /* $month_first_data = date('Y-m-01');
        $today = date('Y-m-d');  */

        
        if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
            $client_id=9;
        } else {
            if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
        }

        $audit_cyle_data = Auditcycle::where('client_id',$client_id)->orderby('start_date','desc')->first();
        $month_first_data = $audit_cyle_data->start_date;
        $today = $audit_cyle_data->end_date;

        // starts rebuttal 
          $rebuttal_data = [];
          
           if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) { 
                $audit_data = Audit::where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->where('partner_id',32)->whereIn('process_id',array(25,26,30))->get();
           } 
           else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                 $audit_data = Audit::where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->where('partner_id',40)->where('process_id',23)->get();
           }
           elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $audit_data = Audit::where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->where('partner_id',41)->where('process_id',31)->get();
           }
           elseif(Auth::user()->id == 256 || Auth::user()->id == 255)  {
            $audit_data = Audit::where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->where('partner_id',44)->where('process_id',32)->get();
           }
           else {
                $audit_data =Audit::where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->get();
           } 

           if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) { 
            $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('partner_id',32)->whereIn('process_id',array(25,26,30))->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
 ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
           } 
           else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('partner_id',40)->where('process_id',23)->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
 ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
           }
           elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('partner_id',41)->where('process_id',31)->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
 ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
           }
           elseif(Auth::user()->id == 256 || Auth::user()->id == 255)  {
              $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('partner_id',44)->where('process_id',32)->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
               ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
           }
           else {
                $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('rebuttal_status','>',0)
                                       ->whereDate('audit_date','>=',$month_first_data)
                                       ->whereDate('audit_date','<=',$today)
                                       ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
           }                   
          
          /*$temp_rebuttal_data = Audit::where('client_id',$client_id)->where('rebuttal_status','>',0)
                                       ->whereDate('audit_date','>=',$month_first_data)
                                       ->whereDate('audit_date','<=',$today)
                                       ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get(); */
        //dd($audit_data); 
        if($client_id==1)
        {
            $coverage['target'] = 500;
            $coverage['achived_per'] = round(($audit_data->count()/500)*100);
        }else if($client_id==9){
            //echo "jsa"; die;
            $month=date('M');

            
            if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) { 
                //echo "here"; die;
                    $t1=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',25)->sum('month_targets.eq_audit_target_mtd');
                    
                    $t2=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',26)->sum('month_targets.eq_audit_target_mtd');
                    
                    $t3=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',30)->sum('month_targets.eq_audit_target_mtd'); $target=$t1+$t2+$t3;

            } 
            elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',23)->sum('month_targets.eq_audit_target_mtd');
            }
            elseif(Auth::user()->id == 139) {
                $t1=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',21)->sum('month_targets.eq_audit_target_mtd');
                $t2=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',22)->sum('month_targets.eq_audit_target_mtd');
                $target=$t1+$t2;
            }
            elseif(Auth::user()->id == 195) {
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',22)->sum('month_targets.eq_audit_target_mtd');
            }
            elseif(Auth::user()->id == 254) {
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',22)->sum('month_targets.eq_audit_target_mtd');
            }
            elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',31)->sum('month_targets.eq_audit_target_mtd');
            }
            elseif(Auth::user()->id == 252 || Auth::user()->id == 269 || Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 279 || Auth::user()->id == 283) {
                $t1=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',21)->sum('month_targets.eq_audit_target_mtd');
            $t2=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',22)->sum('month_targets.eq_audit_target_mtd');
            $target=$t1+$t2;
            }
            elseif(Auth::user()->id == 256 || Auth::user()->id == 255) { 
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',32)->sum('month_targets.eq_audit_target_mtd');
            }
             else {
                $target=MonthTarget::
            join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
           ->where('month_targets.client_id',$client_id)
            ->where('auditcycles.name','like',"$month%")->sum('month_targets.eq_audit_target_mtd');
            }
            
             


           /*  $target = MonthTarget::where('client_id',$client_id)
            ->join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')
            
            
            ->where('month_of_target','like',"Jun%")
            ->sum('eq_audit_target_mtd');   */
           /*  echo $target;

            dd(); */

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

            //$coverage['target'] = 1950;
            
        }     
        else
        {
            $coverage['target'] = 22000;
            $coverage['achived_per'] = round(($audit_data->count()/22000)*100);
        }
        
        $coverage['achived'] = $audit_data->count();
        

        $final_data['coverage'] = $coverage;

        $temp_total_rebuttal = 0;
        $temp_accepted_rebuttal = 0;
        $temp_rejected_rebuttal = 0;

        foreach ($temp_rebuttal_data as $key => $value) {
                $temp_total_rebuttal += $value->audit_rebuttal->count();
                $temp_accepted_rebuttal += $value->audit_rebuttal_accepted->count();
                $temp_rejected_rebuttal += $value->audit_rebuttal_rejected->count();
        }

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
            $rebuttal_data['rejected_per'] = round((($rebuttal_data['rejected']/$audit_data->count())*100));
        else
            $rebuttal_data['rejected_per'] = 0;


        $final_data['rebuttal'] = $rebuttal_data;
        // ends rebuttal 

        // starts QRC
        /*$temp_qrc['query_count'] = Audit::where('client_id',$client_id)
                                          ->where('qrc_2','Query')
                                          ->whereDate('audit_date','>=',$month_first_data)
                                          ->whereDate('audit_date','<=',$today)
                                          ->count(); */
        if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) { 
  $temp_qrc['query_count'] = Audit::where('client_id',$client_id)->where('partner_id',32)->whereIn('process_id',array(25,26,30))->where('qrc_2','Query')
  ->whereDate('audit_date','>=',$month_first_data)
  ->whereDate('audit_date','<=',$today)
  ->count();

  $temp_qrc['query_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',32)->whereIn('process_id',array(25,26,30))
     ->where('qrc_2','Query')
    ->where('is_critical',1)
    ->whereDate('audit_date','>=',$month_first_data)
    ->whereDate('audit_date','<=',$today)
    ->count();

    $temp_qrc['request_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',32)->whereIn('process_id',array(25,26,30))
        ->where('qrc_2','Request')
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();

    $temp_qrc['request_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',32)->whereIn('process_id',array(25,26,30))
      ->where('qrc_2','Request')
      ->where('is_critical',1)
      ->whereDate('audit_date','>=',$month_first_data)
      ->whereDate('audit_date','<=',$today)
      ->count();

    $temp_qrc['complaint_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',32)->whereIn('process_id',array(25,26,30))
      ->where('qrc_2','Complaint')
      ->whereDate('audit_date','>=',$month_first_data)
      ->whereDate('audit_date','<=',$today)
      ->count();

    $temp_qrc['complaint_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',32)->whereIn('process_id',array(25,26,30))
    ->where('qrc_2','Complaint')
    ->where('is_critical',1)
    ->whereDate('audit_date','>=',$month_first_data)
    ->whereDate('audit_date','<=',$today)
    ->count();
} 
else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                
  $temp_qrc['query_count'] = Audit::where('client_id',$client_id)->where('partner_id',40)->where('process_id',23)->where('qrc_2','Query')
  ->whereDate('audit_date','>=',$month_first_data)
  ->whereDate('audit_date','<=',$today)
  ->count();

  $temp_qrc['query_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',40)->where('process_id',23)
    ->where('qrc_2','Query')
    ->where('is_critical',1)
    ->whereDate('audit_date','>=',$month_first_data)
    ->whereDate('audit_date','<=',$today)
    ->count();

    $temp_qrc['request_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',40)->where('process_id',23)
        ->where('qrc_2','Request')
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();

    $temp_qrc['request_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',40)->where('process_id',23)
      ->where('qrc_2','Request')
      ->where('is_critical',1)
      ->whereDate('audit_date','>=',$month_first_data)
      ->whereDate('audit_date','<=',$today)
      ->count();

    $temp_qrc['complaint_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',40)->where('process_id',23)
      ->where('qrc_2','Complaint')
      ->whereDate('audit_date','>=',$month_first_data)
      ->whereDate('audit_date','<=',$today)
      ->count();

    $temp_qrc['complaint_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',40)->where('process_id',23)
    ->where('qrc_2','Complaint')
    ->where('is_critical',1)
    ->whereDate('audit_date','>=',$month_first_data)
    ->whereDate('audit_date','<=',$today)
    ->count();

}
elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
  $temp_qrc['query_count'] = Audit::where('client_id',$client_id)->where('partner_id',41)->where('process_id',31)->where('qrc_2','Query')
  ->whereDate('audit_date','>=',$month_first_data)
  ->whereDate('audit_date','<=',$today)
  ->count();

  $temp_qrc['query_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',41)->where('process_id',31)
    ->where('qrc_2','Query')
    ->where('is_critical',1)
    ->whereDate('audit_date','>=',$month_first_data)
    ->whereDate('audit_date','<=',$today)
    ->count();

    $temp_qrc['request_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',41)->where('process_id',31)
        ->where('qrc_2','Request')
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();

    $temp_qrc['request_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',41)->where('process_id',31)
      ->where('qrc_2','Request')
      ->where('is_critical',1)
      ->whereDate('audit_date','>=',$month_first_data)
      ->whereDate('audit_date','<=',$today)
      ->count();

    $temp_qrc['complaint_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',41)->where('process_id',31)
      ->where('qrc_2','Complaint')
      ->whereDate('audit_date','>=',$month_first_data)
      ->whereDate('audit_date','<=',$today)
      ->count();

    $temp_qrc['complaint_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',41)->where('process_id',31)
    ->where('qrc_2','Complaint')
    ->where('is_critical',1)
    ->whereDate('audit_date','>=',$month_first_data)
    ->whereDate('audit_date','<=',$today)
    ->count();              

}
elseif(Auth::user()->id == 256 || Auth::user()->id == 255)  {
              
  $temp_qrc['query_count'] = Audit::where('client_id',$client_id)->where('partner_id',44)->where('process_id',32)->where('qrc_2','Query')
  ->whereDate('audit_date','>=',$month_first_data)
  ->whereDate('audit_date','<=',$today)
  ->count();

  $temp_qrc['query_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',44)->where('process_id',32)
    ->where('qrc_2','Query')
    ->where('is_critical',1)
    ->whereDate('audit_date','>=',$month_first_data)
    ->whereDate('audit_date','<=',$today)
    ->count();

    $temp_qrc['request_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',44)->where('process_id',32)
        ->where('qrc_2','Request')
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();

    $temp_qrc['request_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',44)->where('process_id',32)
      ->where('qrc_2','Request')
      ->where('is_critical',1)
      ->whereDate('audit_date','>=',$month_first_data)
      ->whereDate('audit_date','<=',$today)
      ->count();

    $temp_qrc['complaint_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',44)->where('process_id',32)
      ->where('qrc_2','Complaint')
      ->whereDate('audit_date','>=',$month_first_data)
      ->whereDate('audit_date','<=',$today)
      ->count();

    $temp_qrc['complaint_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('partner_id',44)->where('process_id',32)
    ->where('qrc_2','Complaint')
    ->where('is_critical',1)
    ->whereDate('audit_date','>=',$month_first_data)
    ->whereDate('audit_date','<=',$today)
    ->count();
}
else {
    $temp_qrc['query_count'] = 
            Audit::where('client_id',$client_id)->where('qrc_2','Query')
          ->whereDate('audit_date','>=',$month_first_data)
          ->whereDate('audit_date','<=',$today)
          ->count();
    $temp_qrc['query_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('qrc_2','Query')
    ->where('is_critical',1)
    ->whereDate('audit_date','>=',$month_first_data)
    ->whereDate('audit_date','<=',$today)
    ->count();
    $temp_qrc['request_count'] = 
    Audit::where('client_id',$client_id)
        ->where('qrc_2','Request')
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();
    $temp_qrc['request_fatal_count'] = 
    Audit::where('client_id',$client_id)
      ->where('qrc_2','Request')
      ->where('is_critical',1)
      ->whereDate('audit_date','>=',$month_first_data)
      ->whereDate('audit_date','<=',$today)
      ->count();
    $temp_qrc['complaint_count'] = 
    Audit::where('client_id',$client_id)
      ->where('qrc_2','Complaint')
      ->whereDate('audit_date','>=',$month_first_data)
      ->whereDate('audit_date','<=',$today)
      ->count();
    $temp_qrc['complaint_fatal_count'] = 
    Audit::where('client_id',$client_id)
    ->where('qrc_2','Complaint')
    ->where('is_critical',1)
    ->whereDate('audit_date','>=',$month_first_data)
    ->whereDate('audit_date','<=',$today)
    ->count();

}
        /*$temp_qrc['query_fatal_count'] = Audit::where('client_id',$client_id)
->where('qrc_2','Query')
->where('is_critical',1)
->whereDate('audit_date','>=',$month_first_data)
->whereDate('audit_date','<=',$today)
->count();
        $temp_qrc['request_count'] = Audit::where('client_id',$client_id)
        ->where('qrc_2','Request')
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();
        $temp_qrc['request_fatal_count'] = Audit::where('client_id',$client_id)
->where('qrc_2','Request')
->where('is_critical',1)
->whereDate('audit_date','>=',$month_first_data)
->whereDate('audit_date','<=',$today)
->count();
        $temp_qrc['complaint_count'] = Audit::where('client_id',$client_id)
->where('qrc_2','Complaint')
->whereDate('audit_date','>=',$month_first_data)
->whereDate('audit_date','<=',$today)
->count();
        $temp_qrc['complaint_fatal_count'] = Audit::where('client_id',$client_id)
->where('qrc_2','Complaint')
->where('is_critical',1)
->whereDate('audit_date','>=',$month_first_data)
->whereDate('audit_date','<=',$today)
->count(); */

        $final_data['qrc'] = $temp_qrc;
        // ends QRC

        // starts Process Wise Score
        if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {
                    
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',32)->get();
                } 
                else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                    
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',40)->get();
                }
                elseif(Auth::user()->id == 249 || Auth::user()->id == 250){
                   
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',41)->get();
                }

                elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                    
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',44)->get();
                }

                elseif(Auth::user()->id == 139) {
                    
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',38)->get();
                }

                elseif (Auth::user()->id == 195) {
                   
                   $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',39)->get();
                }

                elseif (Auth::user()->id == 254) {
                   
                   $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',43)->get();
                } 
                elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->whereIn('id',[38,45,43,39])->get();
                }
                elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',38)->orwhere('id',39)->orwhere('id',43)->orWhere('id',45)->get();
                } elseif (Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 280) {
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',38)->orWhere('id',45)->get();
                } elseif (Auth::user()->id == 282) {
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',39)->orWhere('id',43)->get();
                } elseif (Auth::user()->id == 285 || Auth::user()->id == 309) {
                    $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',38)->get();
                }
                else  {
                    $partner_list = Partner::where('client_id',$client_id)->with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->get();
                }
        $partner_process_list=[];
        foreach ($partner_list as $akey => $avalue) {
            foreach ($avalue->partner_process as $bkey => $bvalue) {
                if(Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) {
                    if($bvalue->process_id == 25 || $bvalue->process_id == 26 || $bvalue->process_id == 30) {
                        $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                    }
                }
                elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                        if($bvalue->process_id == 23) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 139) {
                        if($bvalue->process_id == 21 || $bvalue->process_id == 22) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 195) {
                        if($bvalue->process_id == 22) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 254) {
                        if($bvalue->process_id == 22) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                        if($bvalue->process_id == 31) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 252 || Auth::user()->id == 269 || Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 279 || Auth::user()->id == 283) {
                        if($bvalue->process_id == 21 || $bvalue->process_id == 22) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                        if($bvalue->process_id == 32) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    } else {
                        $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                    }
            }
        }

        $loop=1;
        $final_score=0;
        $final_scorable=0;
        foreach ($partner_process_list as $key => $value) {

            $process_audit_data['score_sum'] = Audit::where('client_id',$client_id)
                                         ->where('process_id',$key)
                                         ->whereDate('audit_date','>=',$month_first_data)
                                         ->whereDate('audit_date','<=',$today)
                                         ->sum('overall_score');
$process_audit_data['audit_count'] = Audit::where('client_id',$client_id)
        ->where('process_id',$key)
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->count();

        $process_audit_data['with_fatal'] = Audit::where('client_id',$client_id)
        ->where('process_id',$key)
        //->where('is_critical',1)
        ->whereDate('audit_date','>=',$month_first_data)
        ->whereDate('audit_date','<=',$today)
        ->sum('with_fatal_score_per');
            if($process_audit_data['audit_count']) {

                
            $process_audit_data['scored_with_fatal'] = round(   ($process_audit_data['with_fatal']/$process_audit_data['audit_count']));
                
           
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
        // ends Process Wise Score

        // Partner & Location Wise Report
        $pl_report = [];
        $loop=1;
        foreach ($partner_list as $key => $value) {
            foreach ($value->partner_location as $bkey => $bvalue) {
                $temp_plr['count'] = $loop;
                $temp_plr['partner'] = $value->name;
                $temp_plr['location'] = $bvalue->location_detail->name;

                //get audits
                $plr_audits = Audit::where('client_id',$client_id)->where('partner_id',$value->id)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)->whereHas('raw_data', function (Builder $query) use ($bvalue) {
                    $query->where('partner_location_id', 'like', $bvalue->location_id);
                })->get();
                $temp_plr['audits_count'] = $plr_audits->count();

                $fatal_audit_score_sum =0;
                $without_fatal_audit_score_sum = 0;
                foreach ($plr_audits as $key => $value) {

                if($value->is_critical==1)
                $fatal_audit_score_sum += 0;
                else
                $fatal_audit_score_sum += $value->overall_score;

                $without_fatal_audit_score_sum +=$value->overall_score;
                }

                if($plr_audits->count())
                {
                $temp_plr['with_fatal'] = round(($fatal_audit_score_sum/$plr_audits->count()));
                $temp_plr['without_fatal'] = round(($without_fatal_audit_score_sum/$plr_audits->count()));
                }else
                {
                $temp_plr['with_fatal']=0;
                $temp_plr['without_fatal']=0;
                }


                
                //get audits

                $pl_report[] = $temp_plr;
                $loop++;
            }
        }
        $final_data['plr'] = $pl_report;
        // Partner & Location Wise Report

        $audit_cyle_data = Auditcycle::where('client_id',$client_id)->orderby('start_date','desc')->get()->pluck('start_date','end_date');

        $final_data['audit_cycle'] = $audit_cyle_data;
        $final_data['ov_scored']=$ov_scored;
        return response()->json(['status'=>200,'message'=>"Success",'data'=>$final_data], 200);
    }

    public function get_qtl_dashboard_data(Request $request)
    {
        
        $dates = explode("-", $request->selected_elements[0]['dates']);

        $start_date = date_to_db($dates[0]);
        $end_date = date_to_db($dates[1]);

        $process_id = $request->selected_elements[0]['process_id'];

        $sheet_detail_id = QmSheet::where('process_id', $process_id)->first();
        $client_id = $sheet_detail_id->client_id;
        /* echo $process_id;
        dd(); */


        $rebuttal_data = [];

        $client_id = 3;
        $partner_id = 6;

        $location_id = '%';
        $lob = '%';
        $qtl_id = Auth::user()->id;

        //$process_id = $request->selected_elements[0]['process_id'];

        $audit_data = Audit::
        join('users', 'audits.audited_by_id', '=', 'users.id')
        ->where('users.reporting_user_id',Auth::user()->id)
        
        ->where('audit_date',">=",$start_date)
        ->where('audit_date',"<=",$end_date)
        ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
             $query->where('partner_location_id', 'like', $location_id);
             $query->where('lob', 'like', $lob);
         })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])
        ->get();        

        $coverage['target'] = 450;
        $coverage['achived'] = $audit_data->count();
        $coverage['achived_per'] = round(($audit_data->count()/450)*100);

        $final_data['coverage'] = $coverage;
        
       
        $temp_total_rebuttal = 0;
        $temp_accepted_rebuttal = 0;
        $temp_rejected_rebuttal=0;

        foreach ($audit_data as $key => $value) {
            if($value->rebuttal_status > 0)
            {
                $temp_total_rebuttal += $value->audit_rebuttal_count;
                $temp_accepted_rebuttal += $value->audit_rebuttal_accepted->count();
                $temp_rejected_rebuttal += $value->audit_rebuttal_rejected->count();
            }
        }

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
            $rebuttal_data['rejected_per'] = round((($rebuttal_data['rejected']/$audit_data->count())*100));
        else
            $rebuttal_data['rejected_per'] = 0;

        $final_data['rebuttal'] = $rebuttal_data;

         
        
        // ends rebuttal 

        // Process Score
            $fatal_dialer_data['with_fatal_score'] = 0;
            $fatal_dialer_data['without_fatal_score'] = 0;
            
            $fatal_audit_count = $audit_data->where('is_critical',1)->count();
            $without_fatal_audit_count = $audit_data->where('is_critical',0)->count();
            $fatal_audit_score_sum=0;
            $without_fatal_audit_score_sum=0;
            foreach ($audit_data as $key => $value) {
                
                $fatal_audit_score_sum += $value->with_fatal_score_per;
                $without_fatal_audit_score_sum +=$value->overall_score;
            }
            if($audit_data->count())
            {
                $fatal_dialer_data['with_fatal_score'] = round(($fatal_audit_score_sum/$audit_data->count()));
                $fatal_dialer_data['without_fatal_score'] = round(($without_fatal_audit_score_sum/$audit_data->count()));
            }else
            {
                $fatal_dialer_data['with_fatal_score']=0;
                $fatal_dialer_data['with_fatal_score']=0;
            }

            $final_data['fatal_dialer_data'] = $fatal_dialer_data;


            //Complete Process Score

            $audit_process_data = Audit::where('process_id','LIKE',$process_id)
                ->where('audit_date',">=",$start_date)
                ->where('audit_date',"<=",$end_date)
                ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
                    $query->where('partner_location_id', 'like', $location_id);
                    $query->where('lob', 'like', $lob);
                })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])
                ->get();   
               
            $fatal_process_data['with_fatal_score'] = 0;
            $fatal_process_data['without_fatal_score'] = 0;
            
            $fatal_audit_count_process = $audit_process_data->where('is_critical',1)->count();
            $without_fatal_audit_count_process = $audit_process_data->where('is_critical',0)->count();
            $fatal_audit_score_sum_process=0;
            $without_fatal_audit_score_sum_process=0;
            foreach ($audit_process_data as $key => $value) {
                
                $fatal_audit_score_sum_process += $value->with_fatal_score_per;
                $without_fatal_audit_score_sum_process +=$value->overall_score;
            }
            if($audit_process_data->count())
            {
                
                $audit_process_data['with_fatal_score'] = round(($fatal_audit_score_sum_process/$audit_process_data->count()));
                $audit_process_data['without_fatal_score'] = round(($without_fatal_audit_score_sum_process/$audit_process_data->count()));
            
                
            }else
            {
                $audit_process_data['with_fatal_score']=0;
                $audit_process_data['with_fatal_score']=0;
            }

            $final_data['audit_process_data'] = $audit_process_data;
            

            //Fatal first row block
            $fatal_first_row_block['total_audits'] = $audit_data->count();
            $fatal_first_row_block['total_fatal_count_sub_parameter'] = 0;
            $fatal_first_row_block['total_fatal_audits'] = $fatal_audit_count;
            if($fatal_audit_count)
            $fatal_first_row_block['total_fatal_audit_per'] = round((($fatal_first_row_block['total_fatal_audits']/$fatal_first_row_block['total_audits'])*100));
            else
            $fatal_first_row_block['total_fatal_audit_per'] = 0;

            // added below in final_data
            //Fatal first row block

            // Parameter Wise Fatal Count & Score
            //get latest QM Sheet
            $latest_qm_sheet = QmSheet::where('process_id',$process_id)->orderBy('version','desc')->first();
            $latest_qm_sheet_id = $latest_qm_sheet->id;
            
            $all_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('is_non_scoring','!=',1)->with('qm_sheet_sub_parameter')->get();

           
            $pwfcs = []; 
            $pws = [];
            $params_list = [];
            $all_fatal_audits = $audit_data->where('is_critical',1);
            $count=1;
            foreach ($all_params as $key => $value) {
                $temp_params['counter'] = $count;
                $temp_params['fatal_count'] = 0;
                $temp_params['fail_count'] = 0;
                $temp_params['fatal_counted_score'] = 0;
                $temp_params['parameter'] = $value->parameter;

                $params_list[] = ucfirst($value->parameter);

                foreach ($audit_data as $bkey => $bvalue) {
                    $temp_params['fatal_count'] += $bvalue->audit_parameter_result->where('parameter_id',$value->id)->where('is_critical',1)->count();
                    $temp_params['fail_count'] += $bvalue->audit_results->where('parameter_id',$value->id)->where('selected_option',2)->count();
                    // $temp_row = $bvalue->audit_parameter_result->where('parameter_id',$value->id)->where('is_critical',1)->first();
                    $temp_params['fatal_counted_score'] += $bvalue->audit_parameter_result->where('parameter_id',$value->id)->sum('with_fatal_score_per');
                }   

                if($audit_data->count())
                $temp_params['fatal_score'] = round(($temp_params['fatal_counted_score']/$audit_data->count()));
                else
                $temp_params['fatal_score'] = 0;

                $pwfcs[] = $temp_params;

                // Parameter score 2
                $temp_params['param_score_total'] = 0;
                foreach ($audit_data as $keyc => $valuec) {
                    $temp_params['param_score_total'] += $valuec->audit_parameter_result->where('parameter_id',$value->id)->sum('with_fatal_score_per');
                }
                if($audit_data->count())
                $temp_params['param_score'] = round(($temp_params['param_score_total']/$audit_data->count()));
                else
                $temp_params['param_score'] = 0;
                // Parameter score 2

                $pws[] = $temp_params['param_score'];

                $count++;
            }
            $final_data['pwfcs'] = $pwfcs;
            
           
            $top_ten = []; 
            
            $data = DB::select("
            select u.*, (select sum(ap.total_spend_time) from auditor_performence_reports ap where ap.auditor_id = u.id and 
            start_date >= '". $start_date ."' and start_date <= '". $end_date ."'
            ) as total_time,
            (select sum(ap.audit_done) from auditor_performence_reports ap where auditor_id = u.id and 
            start_date >= '". $start_date ."' and start_date <= '". $end_date ."') as total_audits,
            (select count(distinct left(a.audit_date, locate(' ', a.audit_date, 1)-1)) from audits a where audited_by_id = u.id and 
            audit_date >= '". $start_date ."' and audit_date <= '". $end_date ."') as present_days,
            (select count(ad.id) from audits ad where ad.audited_by_id = u.id and 
            ad.audit_date >= '". $start_date ."' and ad.audit_date <= '". $end_date ."') as audits,
            
            (select sum(qt.qa_target) from qa_targets qt where qt.qa_id = u.id and qt.deleted != 1 and 
            qt.created_at >= '". $start_date ."' and qt.created_at <= '". $end_date ."') as qa_target

            from users u where u.reporting_user_id = ". Auth::user()->id. " order by audits desc LIMIT 10");
        
            $data2 = DB::select("
            select u.*, (select sum(ap.total_spend_time) from auditor_performence_reports ap where ap.auditor_id = u.id and 
            start_date >= '". $start_date ."' and start_date <= '". $end_date ."'
            ) as total_time,
            (select sum(ap.audit_done) from auditor_performence_reports ap where auditor_id = u.id and 
            start_date >= '". $start_date ."' and start_date <= '". $end_date ."') as total_audits,
            (select count(distinct left(a.audit_date, locate(' ', a.audit_date, 1)-1)) from audits a where audited_by_id = u.id and 
            audit_date >= '". $start_date ."' and audit_date <= '". $end_date ."') as present_days,
            (select count(ad.id) from audits ad where ad.audited_by_id = u.id and 
            ad.audit_date >= '". $start_date ."' and ad.audit_date <= '". $end_date ."') as audits,
            
            (select sum(qt.qa_target) from qa_targets qt where qt.qa_id = u.id and qt.deleted != 1 and 
            qt.created_at >= '". $start_date ."' and qt.created_at <= '". $end_date ."') as qa_target

            from users u where u.reporting_user_id = ". Auth::user()->id. " order by audits LIMIT 10");
            $final_data['top_ten'] = $data;
            $final_data['bottom_ten'] = $data2;

            // Parameter Wise Fatal Count & Score

            // Process Score

            // Parameter score 2
            $final_data['parameter_list'] = $params_list;
            $final_data['pws'] = $pws;
            // Parameter score 2
           
            // Disposition Wise Score
            $despo = [];

            $temp_all_unique_despos=[];
            foreach ($audit_data as $key => $value) {
                // get all unique despositions
                $temp_all_unique_despos[] = $value->raw_data->disposition;
            }

            $all_unique_despos = array_unique($temp_all_unique_despos);
            $all_unique_despos_counts = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_unique_despos_score_total = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_unique_despos_score = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_audit_counts=array();
            $all_scores=array();
            foreach ($audit_data as $key => $value) {
                if($temp_id = array_search($value->raw_data->disposition,$all_unique_despos,true))
                {   
                    $all_unique_despos_counts[$temp_id] += 1;
                    $all_unique_despos_score_total[$temp_id] += $value->overall_score;
                }
            }

            $s=0;
            foreach ($all_unique_despos as $key => $value) {
                $all_audit_counts[$s]=Audit::
                join('users', 'audits.audited_by_id', '=', 'users.id')
                ->where('users.reporting_user_id',$qtl_id)
                                       ->where('process_id',$process_id)
                                       ->where('audit_date',">=",$start_date)
                                       ->where('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$value) {
                                            $query->where('partner_location_id', 'like', $location_id);  
                                            $query->where('disposition', 'like', $value);                              
                                        })->get()->count(); 

                $audit=Audit::
                join('users', 'audits.audited_by_id', '=', 'users.id')
                ->where('users.reporting_user_id',$qtl_id)
                                       ->where('process_id',$process_id)
                                       ->where('audit_date',">=",$start_date)
                                       ->where('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$value) {
                                            $query->where('partner_location_id', 'like', $location_id); 
                                            $query->where('disposition', 'like', $value);                                  
                                        })->withCount(['audit_results'])
                                       ->get();
                $score=0;
                $scorable=0;
                foreach ($audit as $key => $value) {                
                    foreach ($value->audit_results->where('is_non_scoring','!=',1) as $keyb => $valueb) {
                        $score += $valueb->score;
                        $scorable += $valueb->after_audit_weight;
                    }
                }
                if($scorable != 0) {
                    $all_scores[$s] = round(($score/$scorable)*100);
                }else {
                    $all_scores[$s] = 0;
                }
                $s++;   
                /*if($all_unique_despos_counts[$key])
                    $all_unique_despos_score[$key] = round(($all_unique_despos_score_total[$key]/$all_unique_despos_counts[$key]));
                else
                    $all_unique_despos_score[$key] = 0;*/
            }

            $despo['all_unique_despos'] = array_values($all_unique_despos);
            $despo['all_unique_despos_counts'] = array_values($all_audit_counts);
            $despo['all_unique_despos_score'] = array_values($all_scores);

            $final_data['disposition'] = $despo;

            
            // Disposition Wise Score

            //Sub Parameter Wise Score
            //set param-subparam array
            $tmp_params=[];
            foreach ($all_params as $key => $value) {
                $temp_sps = [];
                $temp_sps['parameter_id'] = $value->id;
                $temp_sps['parameter'] = ucfirst($value->parameter);
                $temp_sps_list = [];
                //Sub parameter details
                $temp_sps_detail = [];
                foreach ($value->qm_sheet_sub_parameter as $keyb => $valueb) {
                    $temp_sps_list[] = ucfirst(strtolower(str_replace('"', '', $valueb->sub_parameter)));
                    $temp_sps_detail[] = str_replace('"', '', $valueb->details);
                    $temp_sps['sub_p_id'][] = $valueb->id;
                    $temp_sps['sp_p_score'][] = 0;
                    $temp_sps['sp_p_weight'][] = 0;
                    $temp_sps['sp_p_obtained'][] = 0;

                    $temp_sps['sp_p_fatal_count'][] = 0;
                }
                $temp_sps['temp_sps_list'] = $temp_sps_list;
                $temp_sps['temp_sps_detail'] = $temp_sps_detail;
                $tmp_params[] = $temp_sps;
            }
            // get all audit results 
            foreach ($audit_data as $key => $value) {
                foreach ($tmp_params as $keyb => $valueb) {
                    foreach ($valueb['sub_p_id'] as $keyc => $valuec) {

                        $tmp_params[$keyb]['sp_p_obtained'][$keyc] += $value->audit_results->where('parameter_id',$valueb['parameter_id'])->where('sub_parameter_id',$valuec)->sum('score');

                        $tmp_params[$keyb]['sp_p_weight'][$keyc] += $value->audit_results->where('parameter_id',$valueb['parameter_id'])->where('sub_parameter_id',$valuec)->sum('after_audit_weight');

                        $tmp_params[$keyb]['sp_p_fatal_count'][$keyc] += $value->audit_results->where('parameter_id',$valueb['parameter_id'])->where('sub_parameter_id',$valuec)->where('is_critical',1)->count();
                    }
                }
            }

            //settle down the score from sum
            foreach ($tmp_params as $key => $value) {
                foreach ($value['sp_p_score'] as $keyb => $valueb) {


                    $fatal_first_row_block['total_fatal_count_sub_parameter'] += $tmp_params[$key]['sp_p_fatal_count'][$keyb];

                    if($tmp_params[$key]['sp_p_weight'][$keyb])
                    {
                        $tmp_params[$key]['sp_p_score'][$keyb] = round(($tmp_params[$key]['sp_p_obtained'][$keyb]/$tmp_params[$key]['sp_p_weight'][$keyb])*100);
                    }else
                    {
                        $tmp_params[$key]['sp_p_score'][$keyb] = 0;
                    }
                }
            }
            $final_data['fatal_first_row_block'] = $fatal_first_row_block;
            $final_data['spws'] = $tmp_params;
            //Sub Parameter Wise Score
            

            // starts QRC
            $temp_qrc['query_count'] = $audit_data->where('qrc_2','Query')->count();
            $temp_qrc['query_fatal_count'] = $audit_data->where('qrc_2','Query')->where('is_critical',1)->count();
            $temp_qrc['query_fatal_score_sum'] = $audit_data->where('qrc_2','Query')->sum('with_fatal_score_per');

            if($temp_qrc['query_count'])
            $temp_qrc['query_fatal_score'] = round(($temp_qrc['query_fatal_score_sum']/$temp_qrc['query_count']));
            else
            $temp_qrc['query_fatal_score'] = 0;

            $temp_qrc['request_count'] = $audit_data->where('qrc_2','Request')->count();
            $temp_qrc['request_fatal_count'] = $audit_data->where('qrc_2','Request')->where('is_critical',1)->count();
            $temp_qrc['request_fatal_score_sum'] = $audit_data->where('qrc_2','Request')->sum('with_fatal_score_per');

            if($temp_qrc['request_count'])
            $temp_qrc['request_fatal_score'] = round(($temp_qrc['request_fatal_score_sum']/$temp_qrc['request_count']));
            else
            $temp_qrc['request_fatal_score'] =0;

            $temp_qrc['complaint_count'] = $audit_data->where('qrc_2','Complaint')->count();
            $temp_qrc['complaint_fatal_count'] = $audit_data->where('qrc_2','Complaint')->where('is_critical',1)->count();
            $temp_qrc['complaint_fatal_score_sum'] = $audit_data->where('qrc_2','Complaint')->sum('with_fatal_score_per');

            if($temp_qrc['complaint_count'])
            $temp_qrc['complaint_fatal_score'] = round(($temp_qrc['complaint_fatal_score_sum']/$temp_qrc['complaint_count']));
            else
            $temp_qrc['complaint_fatal_score'] = 0;

            $qrc_data['audit_count']=[$temp_qrc['query_count'],$temp_qrc['request_count'],$temp_qrc['complaint_count']];
            $qrc_data['fatal_count']=[$temp_qrc['query_fatal_count'],$temp_qrc['request_fatal_count'],$temp_qrc['complaint_fatal_count']];
            $qrc_data['score']=[$temp_qrc['query_fatal_score'],$temp_qrc['request_fatal_score'],$temp_qrc['complaint_fatal_score']];

            $final_data['qrc'] = $qrc_data;
            // ends QRC
            
            // quartile starts QRC
            $all_agents = [];
            foreach ($audit_data as $key => $value) {
                $all_agents[] = $value->raw_data->emp_id;
            }

            $all_unique_agents = array_unique($all_agents);
            $all_audit_score=[];
            foreach ($all_unique_agents as $key => $value) {

                $agent_all_audit_score = Audit::
                join('users', 'audits.audited_by_id', '=', 'users.id')
                ->where('users.reporting_user_id',$qtl_id)
                                       ->where('process_id',$process_id)
                                       ->where('audit_date',">=",$start_date)
                                       ->where('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($value,$location_id) {
                                            $query->where('emp_id', $value);
                                            $query->where('partner_location_id', 'like', $location_id);  
                                        })->get();
                $scored=0;
                $scorable=0;
                foreach ($agent_all_audit_score as $key => $value1) {                
                    foreach ($value1->audit_results->where('is_non_scoring','!=',1) as $keyb => $valueb) {
                        $scored += $valueb->score;
                        $scorable += $valueb->after_audit_weight;                                   
                    }               
                }
                if($scorable == 0) {
                    $score = 0;
                } else {
                    $score = round(($scored/$scorable)*100); 
                }
                $all_audit_score[] = ["name"=>$value,
                                      "audit_count"=>$agent_all_audit_score->count(),
                                      "with_fatal_score_per_sum"=>$agent_all_audit_score->sum('with_fatal_score_per'),
                                      "score"=>$score];
            }

            //echo "<pre>";
            //print_r($all_audit_score); die;

            // check the fall in of all agents
            $quartile_data[0] = 0;
            $quartile_data[1] = 0;
            $quartile_data[2] = 0;
            $quartile_data[3] = 0;

            foreach ($all_audit_score as $key => $value) {
                if($value['score'] >= 0 && $value['score'] < 41)
                    $quartile_data[0] += 1;
                else if($value['score'] >= 41  && $value['score'] < 61)
                    $quartile_data[1] += 1;
                else if($value['score'] >= 61  && $value['score'] < 81)
                    $quartile_data[2] +=1;
                else if($value['score'] > 80)
                    $quartile_data[3] += 1;
            }


            $final_data['quartile'] = $quartile_data;
            $final_data['user_id']=Auth::user()->id;
            // quartile ends QRC
           
            //non scoring sub parameter
            //get para - sub para
            $non_scoring_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('is_non_scoring',1)->with('qm_sheet_sub_parameter')->get();
            $non_scoring_params_name='';
            if($non_scoring_params->isNotEmpty())
            {
                        $non_scoring_params_name = $non_scoring_params[0]->parameter;
                        $non_scoring_params = $non_scoring_params[0]->qm_sheet_sub_parameter;
            }

            $temp_non_scoring_params=[];
            foreach ($non_scoring_params as $key => $value) {

                $temp_non_scoring_params[] = ['non_scoring_option_group'=>$value->non_scoring_option_group,'name'=>$value->sub_parameter,'id'=>$value->id,'count'=>[0,0,0]];
            }

            foreach ($audit_data as $key => $value) {

                foreach ($temp_non_scoring_params as $keyb => $valueb) {

                $a_result = $value->audit_results->where('sub_parameter_id',$valueb['id']);
                $temp_a_result=[];
                foreach ($a_result as $keyx => $valuex) {
                    $temp_a_result = $valuex;
                }

                if($temp_a_result)
                switch ($valueb['non_scoring_option_group']) {
                    case 1:
                    {  
                        if($temp_a_result->selected_option==1)
                            $temp_non_scoring_params[$keyb]['count'][0] +=1;
                        else 
                            $temp_non_scoring_params[$keyb]['count'][1] +=1;

                        break;
                    }
                    case 2:
                    {  
                        if($temp_a_result->selected_option==3)
                            $temp_non_scoring_params[$keyb]['count'][0] +=1;
                        else 
                            $temp_non_scoring_params[$keyb]['count'][1] +=1;


                        break;
                    }
                    case 3:
                    {  
                       if($temp_a_result->selected_option==6)
                            $temp_non_scoring_params[$keyb]['count'][0] +=1;
                        else 
                            $temp_non_scoring_params[$keyb]['count'][1] +=1;


                        break;
                    }
                    case 4:
                    {  

                        if($temp_a_result->selected_option==10)
                            $temp_non_scoring_params[$keyb]['count'][0] +=1;
                        else 
                            $temp_non_scoring_params[$keyb]['count'][1] +=1;
                        
                        break;
                    }
                    
                    default:
                        # code...
                        break;
                }
                    # code...
                }
            }
            
            $temp_all_non_scoring_sub_parameter_list=[];
            $temp_all_non_scoring_sub_parameter_data=[];
            foreach ($temp_non_scoring_params as $key => $value) {

                if(($temp_non_scoring_params[$key]['count'][0]+$temp_non_scoring_params[$key]['count'][1])!=0)
                {
                    $temp_non_scoring_params[$key]['count'][2] = round(($temp_non_scoring_params[$key]['count'][0]/($temp_non_scoring_params[$key]['count'][0]+$temp_non_scoring_params[$key]['count'][1]))*100);    
                }else
                {
                    $temp_non_scoring_params[$key]['count'][2]=0;
                }
                

                $temp_all_non_scoring_sub_parameter_list[] = $value['name'];
                $temp_all_non_scoring_sub_parameter_data[] = $temp_non_scoring_params[$key]['count'][2];


            }   

            $final_data['non_scoring_params'] = ["list"=>$temp_all_non_scoring_sub_parameter_list,"score"=>$temp_all_non_scoring_sub_parameter_data,"names"=>$non_scoring_params_name];
            //non scoring sub parameter

            // starts process stats
            $fail_count=0;
            $critical_count=0;
            $na_count=0;
            foreach ($audit_data as $key => $value) {
                $fail_count += $value->audit_results->where('selected_option',2)->count();
                $critical_count += $value->audit_results->where('selected_option',3)->count();
                $na_count += $value->audit_results->where('selected_option',4)->count();
            }

            $qm_sheet_sp_count = QmSheetSubParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('non_scoring_option_group',0)->count();
            $audit_sp_count = $audit_data->count()*$qm_sheet_sp_count;

            if($audit_data->count())
            {
                $process_stats['dpu'] = round((($fail_count+$critical_count) / $audit_data->count()),2);
                $process_stats['dpo'] = round((($fail_count+$critical_count) / ($audit_sp_count-$na_count)),2);
                $process_stats['dpmo'] = $process_stats['dpo']*1000000;

                $process_stats['ppm'] = round(($fatal_audit_count/$audit_data->count())*1000000,2);

                $process_stats['fty'] = ($audit_data->where('is_critical',0)->count()/$audit_data->count());
            }
            else
            {
                $process_stats['dpu'] =0;
                $process_stats['dpo'] = 0;
                $process_stats['dpmo'] = 0;
                $process_stats['ppm'] = 0;
                $process_stats['fty']=0;
            }




            $final_data['process_stats'] = $process_stats;
            // ends process stats


            // pareto data
            $pareto_audit_result = AuditResult::whereHas('audit', function (Builder $query) use ($partner_id,$process_id,$start_date,$end_date,$location_id) {
                        $query->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->where('audit_date',">=",$start_date)
                                       ->where('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                        });
                    })->with('reason')->get();

        $all_uni_reaons=[];
        $all_uni_reaons_a=[];
        $all_uni_reaons_b=[];
        $all_uni_reaons_c=[];
        $all_uni_reaons_d=[];
        $all_uni_reaons_e=[];
        foreach ($pareto_audit_result as $key => $value) {

            if($value->reason)
            $all_uni_reaons_a[$value->reason_id]=['count'=>0,'name'=>$value->reason->name];
        }

        //get count
        foreach ($all_uni_reaons_a as $key => $value) {
            $count = $pareto_audit_result->where('reason_id',$key)->count();
            $all_uni_reaons_a[$key]['count'] = $count;
        }

        usort($all_uni_reaons_a, function($a, $b) {
            return $b['count'] - $a['count'];
        });

        $runningSum = 0;
        foreach ($all_uni_reaons_a as $kk=>$vv) {
            $runningSum += $vv['count'];
            $all_uni_reaons_b[$kk] = $vv['count'];
            $all_uni_reaons_c[$kk] = $runningSum;
            $all_uni_reaons_d[$kk] = $vv['name'];
        }

        
        foreach ($all_uni_reaons_c as $kk=>$vv) {
            $all_uni_reaons_e[$kk] = (round(($vv/$runningSum)*100,2));
        }

        $final_data['pareto_data']['count'] = $all_uni_reaons_b;
        $final_data['pareto_data']['per'] = $all_uni_reaons_e;
        $final_data['pareto_data']['reasons'] = $all_uni_reaons_d;
            // pareto data


        return response()->json(['status'=>200,'message'=>"Success",'data'=>$final_data], 200);
    }
    
    public function get_qa_dashboard_data(Request $request)
    {
        $dates = explode("-", $request->selected_elements[0]['dates']);

        $start_date = date_to_db($dates[0]);
        $end_date = date_to_db($dates[1]);

        $user_detail = User::where('id',Auth::user()->id)->first();

        $sheet_detail_id = QmSheet::where('id', $user_detail->assigned_sheet_id)->first();
        //$process 

        //$process_id = $request->selected_elements[0]['process_id'];
        
        $rebuttal_data = [];

        $client_id = $sheet_detail_id->client_id;
        $process_id = $sheet_detail_id->process_id;
        $partner_id = 6;

        $location_id = '%';
        $lob = '%';
        $qa_id = Auth::user()->id;

       // $process_id = $request->selected_elements[0]['process_id'];

        $audit_data = Audit::
        where('audited_by_id',$qa_id)
                ->where('audit_date',">=",$start_date)
                ->where('audit_date',"<=",$end_date)
                ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
                    $query->where('partner_location_id', 'like', $location_id);
                    $query->where('lob', 'like', $lob);
                })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])
                ->get();        

        $coverage['target'] = 450;
        $coverage['achived'] = $audit_data->count();
        $coverage['achived_per'] = round(($audit_data->count()/450)*100);

        $final_data['coverage'] = $coverage;

        $temp_total_rebuttal = 0;
        $temp_accepted_rebuttal = 0;
        $temp_rejected_rebuttal=0;

        foreach ($audit_data as $key => $value) {
            if($value->rebuttal_status > 0)
            {
                $temp_total_rebuttal += $value->audit_rebuttal_count;
                $temp_accepted_rebuttal += $value->audit_rebuttal_accepted->count();
                $temp_rejected_rebuttal += $value->audit_rebuttal_rejected->count();
            }
        }

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
            $rebuttal_data['rejected_per'] = round((($rebuttal_data['rejected']/$audit_data->count())*100));
        else
            $rebuttal_data['rejected_per'] = 0;

        $final_data['rebuttal'] = $rebuttal_data;
        // ends rebuttal 

        // Process Score
            $fatal_dialer_data['with_fatal_score'] = 0;
            $fatal_dialer_data['without_fatal_score'] = 0;
            
            $fatal_audit_count = $audit_data->where('is_critical',1)->count();
            $without_fatal_audit_count = $audit_data->where('is_critical',0)->count();
            $fatal_audit_score_sum=0;
            $without_fatal_audit_score_sum=0;
            foreach ($audit_data as $key => $value) {
                
                $fatal_audit_score_sum += $value->with_fatal_score_per;
                $without_fatal_audit_score_sum +=$value->overall_score;
            }
            if($audit_data->count())
            {
                $fatal_dialer_data['with_fatal_score'] = round(($fatal_audit_score_sum/$audit_data->count()));
                $fatal_dialer_data['without_fatal_score'] = round(($without_fatal_audit_score_sum/$audit_data->count()));
            }else
            {
                $fatal_dialer_data['with_fatal_score']=0;
                $fatal_dialer_data['with_fatal_score']=0;
            }

            $final_data['fatal_dialer_data'] = $fatal_dialer_data;

            //Fatal first row block
            $fatal_first_row_block['total_audits'] = $audit_data->count();
            $fatal_first_row_block['total_fatal_count_sub_parameter'] = 0;
            $fatal_first_row_block['total_fatal_audits'] = $fatal_audit_count;
            if($fatal_audit_count)
            $fatal_first_row_block['total_fatal_audit_per'] = round((($fatal_first_row_block['total_fatal_audits']/$fatal_first_row_block['total_audits'])*100));
            else
            $fatal_first_row_block['total_fatal_audit_per'] = 0;

            // added below in final_data
            //Fatal first row block

            // Parameter Wise Fatal Count & Score
            //get latest QM Sheet
            $latest_qm_sheet = QmSheet::where('client_id',$client_id)->where('process_id',$process_id)->orderBy('version','desc')->first();
            $latest_qm_sheet_id = $latest_qm_sheet->id;

            $all_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('is_non_scoring','!=',1)->with('qm_sheet_sub_parameter')->get();

            $pwfcs = [];
            $pws;
            $params_list = [];
            $all_fatal_audits = $audit_data->where('is_critical',1);
            $count=1;
            foreach ($all_params as $key => $value) {
                $temp_params['counter'] = $count;
                $temp_params['fatal_count'] = 0;
                $temp_params['fail_count'] = 0;
                $temp_params['fatal_counted_score'] = 0;
                $temp_params['parameter'] = $value->parameter;

                $params_list[] = ucfirst($value->parameter);

                foreach ($audit_data as $bkey => $bvalue) {
                    $temp_params['fatal_count'] += $bvalue->audit_parameter_result->where('parameter_id',$value->id)->where('is_critical',1)->count();
                    $temp_params['fail_count'] += $bvalue->audit_results->where('parameter_id',$value->id)->where('selected_option',2)->count();
                    // $temp_row = $bvalue->audit_parameter_result->where('parameter_id',$value->id)->where('is_critical',1)->first();
                    $temp_params['fatal_counted_score'] += $bvalue->audit_parameter_result->where('parameter_id',$value->id)->sum('with_fatal_score_per');
                }   

                if($audit_data->count())
                $temp_params['fatal_score'] = round(($temp_params['fatal_counted_score']/$audit_data->count()));
                else
                $temp_params['fatal_score'] = 0;

                $pwfcs[] = $temp_params;

                // Parameter score 2
                $temp_params['param_score_total'] = 0;
                foreach ($audit_data as $keyc => $valuec) {
                    $temp_params['param_score_total'] += $valuec->audit_parameter_result->where('parameter_id',$value->id)->sum('with_fatal_score_per');
                }
                if($audit_data->count())
                $temp_params['param_score'] = round(($temp_params['param_score_total']/$audit_data->count()));
                else
                $temp_params['param_score'] = 0;
                // Parameter score 2

                $pws[] = $temp_params['param_score'];

                $count++;
            }
            $final_data['pwfcs'] = $pwfcs;
            // Parameter Wise Fatal Count & Score

            // Process Score

            // Parameter score 2
            $final_data['parameter_list'] = $params_list;
            $final_data['pws'] = $pws;
            // Parameter score 2

            // Disposition Wise Score
            $despo = [];

            $temp_all_unique_despos=[];
            foreach ($audit_data as $key => $value) {
                // get all unique despositions
                $temp_all_unique_despos[] = $value->raw_data->disposition;
            }

            $all_unique_despos = array_unique($temp_all_unique_despos);
            $all_unique_despos_counts = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_unique_despos_score_total = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_unique_despos_score = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_audit_counts=array();
            $all_scores=array();
            foreach ($audit_data as $key => $value) {
                if($temp_id = array_search($value->raw_data->disposition,$all_unique_despos,true))
                {   
                    $all_unique_despos_counts[$temp_id] += 1;
                    $all_unique_despos_score_total[$temp_id] += $value->overall_score;
                }
            }

            $s=0;
            foreach ($all_unique_despos as $key => $value) {
                $all_audit_counts[$s]=Audit::where('audited_by_id',$qa_id)
                                       ->where('audit_date',">=",$start_date)
                                       ->where('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$value) {
                                            $query->where('partner_location_id', 'like', $location_id);  
                                            $query->where('disposition', 'like', $value);                              
                                        })->get()->count(); 
                $audit=Audit::where('audited_by_id',$qa_id)
                                       ->where('audit_date',">=",$start_date)
                                       ->where('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$value) {
                                            $query->where('partner_location_id', 'like', $location_id); 
                                            $query->where('disposition', 'like', $value);                                  
                                        })->withCount(['audit_results'])
                                       ->get();
                $score=0;
                $scorable=0;
                foreach ($audit as $key => $value) {                
                    foreach ($value->audit_results->where('is_non_scoring','!=',1) as $keyb => $valueb) {
                        $score += $valueb->score;
                        $scorable += $valueb->after_audit_weight;
                    }
                }
                if($scorable != 0) {
                    $all_scores[$s] = round(($score/$scorable)*100);
                }else {
                    $all_scores[$s] = 0;
                }
                $s++;   
                /*if($all_unique_despos_counts[$key])
                    $all_unique_despos_score[$key] = round(($all_unique_despos_score_total[$key]/$all_unique_despos_counts[$key]));
                else
                    $all_unique_despos_score[$key] = 0;*/
            }

            $despo['all_unique_despos'] = array_values($all_unique_despos);
            $despo['all_unique_despos_counts'] = array_values($all_audit_counts);
            $despo['all_unique_despos_score'] = array_values($all_scores);

            $final_data['disposition'] = $despo;
            // Disposition Wise Score
            
            //Sub Parameter Wise Score
            //set param-subparam array
            $tmp_params=[];
            foreach ($all_params as $key => $value) {
                $temp_sps = [];
                $temp_sps['parameter_id'] = $value->id;
                $temp_sps['parameter'] = ucfirst($value->parameter);
                $temp_sps_list = [];
                //Sub parameter details
                $temp_sps_detail = [];
                foreach ($value->qm_sheet_sub_parameter as $keyb => $valueb) {
                    $temp_sps_list[] = ucfirst(strtolower(str_replace('"', '', $valueb->sub_parameter)));
                    $temp_sps_detail[] = str_replace('"', '', $valueb->details);
                    $temp_sps['sub_p_id'][] = $valueb->id;
                    $temp_sps['sp_p_score'][] = 0;
                    $temp_sps['sp_p_weight'][] = 0;
                    $temp_sps['sp_p_obtained'][] = 0;

                    $temp_sps['sp_p_fatal_count'][] = 0;
                }
                $temp_sps['temp_sps_list'] = $temp_sps_list;
                $temp_sps['temp_sps_detail'] = $temp_sps_detail;
                $tmp_params[] = $temp_sps;
            }
            // get all audit results 
            foreach ($audit_data as $key => $value) {
                foreach ($tmp_params as $keyb => $valueb) {
                    foreach ($valueb['sub_p_id'] as $keyc => $valuec) {

                        $tmp_params[$keyb]['sp_p_obtained'][$keyc] += $value->audit_results->where('parameter_id',$valueb['parameter_id'])->where('sub_parameter_id',$valuec)->sum('score');

                        $tmp_params[$keyb]['sp_p_weight'][$keyc] += $value->audit_results->where('parameter_id',$valueb['parameter_id'])->where('sub_parameter_id',$valuec)->sum('after_audit_weight');

                        $tmp_params[$keyb]['sp_p_fatal_count'][$keyc] += $value->audit_results->where('parameter_id',$valueb['parameter_id'])->where('sub_parameter_id',$valuec)->where('is_critical',1)->count();
                    }
                }
            }

            //settle down the score from sum
            foreach ($tmp_params as $key => $value) {
                foreach ($value['sp_p_score'] as $keyb => $valueb) {


                    $fatal_first_row_block['total_fatal_count_sub_parameter'] += $tmp_params[$key]['sp_p_fatal_count'][$keyb];

                    if($tmp_params[$key]['sp_p_weight'][$keyb])
                    {
                        $tmp_params[$key]['sp_p_score'][$keyb] = round(($tmp_params[$key]['sp_p_obtained'][$keyb]/$tmp_params[$key]['sp_p_weight'][$keyb])*100);
                    }else
                    {
                        $tmp_params[$key]['sp_p_score'][$keyb] = 0;
                    }
                }
            }
            $final_data['fatal_first_row_block'] = $fatal_first_row_block;
            $final_data['spws'] = $tmp_params;
            //Sub Parameter Wise Score

           
            // starts QRC
            $temp_qrc['query_count'] = $audit_data->where('qrc_2','Query')->count();
            $temp_qrc['query_fatal_count'] = $audit_data->where('qrc_2','Query')->where('is_critical',1)->count();
            $temp_qrc['query_fatal_score_sum'] = $audit_data->where('qrc_2','Query')->sum('with_fatal_score_per');

            if($temp_qrc['query_count'])
            $temp_qrc['query_fatal_score'] = round(($temp_qrc['query_fatal_score_sum']/$temp_qrc['query_count']));
            else
            $temp_qrc['query_fatal_score'] = 0;

            $temp_qrc['request_count'] = $audit_data->where('qrc_2','Request')->count();
            $temp_qrc['request_fatal_count'] = $audit_data->where('qrc_2','Request')->where('is_critical',1)->count();
            $temp_qrc['request_fatal_score_sum'] = $audit_data->where('qrc_2','Request')->sum('with_fatal_score_per');

            if($temp_qrc['request_count'])
            $temp_qrc['request_fatal_score'] = round(($temp_qrc['request_fatal_score_sum']/$temp_qrc['request_count']));
            else
            $temp_qrc['request_fatal_score'] =0;

            $temp_qrc['complaint_count'] = $audit_data->where('qrc_2','Complaint')->count();
            $temp_qrc['complaint_fatal_count'] = $audit_data->where('qrc_2','Complaint')->where('is_critical',1)->count();
            $temp_qrc['complaint_fatal_score_sum'] = $audit_data->where('qrc_2','Complaint')->sum('with_fatal_score_per');

            if($temp_qrc['complaint_count'])
            $temp_qrc['complaint_fatal_score'] = round(($temp_qrc['complaint_fatal_score_sum']/$temp_qrc['complaint_count']));
            else
            $temp_qrc['complaint_fatal_score'] = 0;

            $qrc_data['audit_count']=[$temp_qrc['query_count'],$temp_qrc['request_count'],$temp_qrc['complaint_count']];
            $qrc_data['fatal_count']=[$temp_qrc['query_fatal_count'],$temp_qrc['request_fatal_count'],$temp_qrc['complaint_fatal_count']];
            $qrc_data['score']=[$temp_qrc['query_fatal_score'],$temp_qrc['request_fatal_score'],$temp_qrc['complaint_fatal_score']];

            $final_data['qrc'] = $qrc_data;
            // ends QRC

            // quartile starts QRC
            $all_agents = [];
            foreach ($audit_data as $key => $value) {
                $all_agents[] = $value->raw_data->emp_id;
            }

            $all_unique_agents = array_unique($all_agents);
            $all_audit_score=[];
            foreach ($all_unique_agents as $key => $value) {

                $agent_all_audit_score = Audit::where('audited_by_id',$qa_id)
                                       ->where('audit_date',">=",$start_date)
                                       ->where('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($value,$location_id) {
                                            $query->where('emp_id', $value);
                                            $query->where('partner_location_id', 'like', $location_id);  
                                        })->get();
                $scored=0;
                $scorable=0;
                foreach ($agent_all_audit_score as $key => $value1) {                
                    foreach ($value1->audit_results->where('is_non_scoring','!=',1) as $keyb => $valueb) {
                        $scored += $valueb->score;
                        $scorable += $valueb->after_audit_weight;                                   
                    }               
                }
                if($scorable == 0) {
                    $score = 0;
                } else {
                    $score = round(($scored/$scorable)*100); 
                }
                $all_audit_score[] = ["name"=>$value,
                                      "audit_count"=>$agent_all_audit_score->count(),
                                      "with_fatal_score_per_sum"=>$agent_all_audit_score->sum('with_fatal_score_per'),
                                      "score"=>$score];
            }

            //echo "<pre>";
            //print_r($all_audit_score); die;

            // check the fall in of all agents
            $quartile_data[0] = 0;
            $quartile_data[1] = 0;
            $quartile_data[2] = 0;
            $quartile_data[3] = 0;

            foreach ($all_audit_score as $key => $value) {
                if($value['score'] >= 0 && $value['score'] < 41)
                    $quartile_data[0] += 1;
                else if($value['score'] >= 41  && $value['score'] < 61)
                    $quartile_data[1] += 1;
                else if($value['score'] >= 61  && $value['score'] < 81)
                    $quartile_data[2] +=1;
                else if($value['score'] > 80)
                    $quartile_data[3] += 1;
            }


            $final_data['quartile'] = $quartile_data;
            $final_data['user_id']=Auth::user()->id;
            // quartile ends QRC
            
            //non scoring sub parameter
            //get para - sub para
            $non_scoring_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('is_non_scoring',1)->with('qm_sheet_sub_parameter')->get();
            $non_scoring_params_name='';
            if($non_scoring_params->isNotEmpty())
            {
                        $non_scoring_params_name = $non_scoring_params[0]->parameter;
                        $non_scoring_params = $non_scoring_params[0]->qm_sheet_sub_parameter;
            }

            $temp_non_scoring_params=[];
            foreach ($non_scoring_params as $key => $value) {

                $temp_non_scoring_params[] = ['non_scoring_option_group'=>$value->non_scoring_option_group,'name'=>$value->sub_parameter,'id'=>$value->id,'count'=>[0,0,0]];
            }

            foreach ($audit_data as $key => $value) {

                foreach ($temp_non_scoring_params as $keyb => $valueb) {

                $a_result = $value->audit_results->where('sub_parameter_id',$valueb['id']);
                $temp_a_result=[];
                foreach ($a_result as $keyx => $valuex) {
                    $temp_a_result = $valuex;
                }

                if($temp_a_result)
                switch ($valueb['non_scoring_option_group']) {
                    case 1:
                    {  
                        if($temp_a_result->selected_option==1)
                            $temp_non_scoring_params[$keyb]['count'][0] +=1;
                        else 
                            $temp_non_scoring_params[$keyb]['count'][1] +=1;

                        break;
                    }
                    case 2:
                    {  
                        if($temp_a_result->selected_option==3)
                            $temp_non_scoring_params[$keyb]['count'][0] +=1;
                        else 
                            $temp_non_scoring_params[$keyb]['count'][1] +=1;


                        break;
                    }
                    case 3:
                    {  
                       if($temp_a_result->selected_option==6)
                            $temp_non_scoring_params[$keyb]['count'][0] +=1;
                        else 
                            $temp_non_scoring_params[$keyb]['count'][1] +=1;


                        break;
                    }
                    case 4:
                    {  

                        if($temp_a_result->selected_option==10)
                            $temp_non_scoring_params[$keyb]['count'][0] +=1;
                        else 
                            $temp_non_scoring_params[$keyb]['count'][1] +=1;
                        
                        break;
                    }
                    
                    default:
                        # code...
                        break;
                }
                    # code...
                }
            }
            
            $temp_all_non_scoring_sub_parameter_list=[];
            $temp_all_non_scoring_sub_parameter_data=[];
            foreach ($temp_non_scoring_params as $key => $value) {

                if(($temp_non_scoring_params[$key]['count'][0]+$temp_non_scoring_params[$key]['count'][1])!=0)
                {
                    $temp_non_scoring_params[$key]['count'][2] = round(($temp_non_scoring_params[$key]['count'][0]/($temp_non_scoring_params[$key]['count'][0]+$temp_non_scoring_params[$key]['count'][1]))*100);    
                }else
                {
                    $temp_non_scoring_params[$key]['count'][2]=0;
                }
                

                $temp_all_non_scoring_sub_parameter_list[] = $value['name'];
                $temp_all_non_scoring_sub_parameter_data[] = $temp_non_scoring_params[$key]['count'][2];


            }   

            $final_data['non_scoring_params'] = ["list"=>$temp_all_non_scoring_sub_parameter_list,"score"=>$temp_all_non_scoring_sub_parameter_data,"names"=>$non_scoring_params_name];
            //non scoring sub parameter

            // starts process stats
            $fail_count=0;
            $critical_count=0;
            $na_count=0;
            foreach ($audit_data as $key => $value) {
                $fail_count += $value->audit_results->where('selected_option',2)->count();
                $critical_count += $value->audit_results->where('selected_option',3)->count();
                $na_count += $value->audit_results->where('selected_option',4)->count();
            }

            $qm_sheet_sp_count = QmSheetSubParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('non_scoring_option_group',0)->count();
            $audit_sp_count = $audit_data->count()*$qm_sheet_sp_count;

            if($audit_data->count())
            {
                $process_stats['dpu'] = round((($fail_count+$critical_count) / $audit_data->count()),2);
                $process_stats['dpo'] = round((($fail_count+$critical_count) / ($audit_sp_count-$na_count)),2);
                $process_stats['dpmo'] = $process_stats['dpo']*1000000;

                $process_stats['ppm'] = round(($fatal_audit_count/$audit_data->count())*1000000,2);

                $process_stats['fty'] = ($audit_data->where('is_critical',0)->count()/$audit_data->count());
            }
            else
            {
                $process_stats['dpu'] =0;
                $process_stats['dpo'] = 0;
                $process_stats['dpmo'] = 0;
                $process_stats['ppm'] = 0;
                $process_stats['fty']=0;
            }




            $final_data['process_stats'] = $process_stats;
            // ends process stats


            // pareto data
            $pareto_audit_result = AuditResult::whereHas('audit', function (Builder $query) use ($process_id,$start_date,$end_date,$location_id) {
                        $query->where('audited_by_id',Auth::user()->id)
                                       ->where('audit_date',">=",$start_date)
                                       ->where('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                        });
                    })->with('reason')->get();

        $all_uni_reaons=[];
        $all_uni_reaons_a=[];
        $all_uni_reaons_b=[];
        $all_uni_reaons_c=[];
        $all_uni_reaons_d=[];
        $all_uni_reaons_e=[];
        foreach ($pareto_audit_result as $key => $value) {

            if($value->reason)
            $all_uni_reaons_a[$value->reason_id]=['count'=>0,'name'=>$value->reason->name];
        }

        //get count
        foreach ($all_uni_reaons_a as $key => $value) {
            $count = $pareto_audit_result->where('reason_id',$key)->count();
            $all_uni_reaons_a[$key]['count'] = $count;
        }

        usort($all_uni_reaons_a, function($a, $b) {
            return $b['count'] - $a['count'];
        });

        $runningSum = 0;
        foreach ($all_uni_reaons_a as $kk=>$vv) {
            $runningSum += $vv['count'];
            $all_uni_reaons_b[$kk] = $vv['count'];
            $all_uni_reaons_c[$kk] = $runningSum;
            $all_uni_reaons_d[$kk] = $vv['name'];
        }

        
        foreach ($all_uni_reaons_c as $kk=>$vv) {
            $all_uni_reaons_e[$kk] = (round(($vv/$runningSum)*100,2));
        }

        $final_data['pareto_data']['count'] = $all_uni_reaons_b;
        $final_data['pareto_data']['per'] = $all_uni_reaons_e;
        $final_data['pareto_data']['reasons'] = $all_uni_reaons_d;
            // pareto data
           /*  print_r($final_data['pareto_data']['count']);
            dd(); */

        return response()->json(['status'=>200,'message'=>"Success",'data'=>$final_data], 200);
    }
    
    public function get_client_partner_dashboard_data(Request $request)
    {
        clearstatcache();
        //echo "<here>"; die;
        //echo "<pre>"; print_r($request->all()); die;
        $dates = explode("-", $request->selected_elements[0]['dates']);

        $start_date = date_to_db($dates[0]);
        $end_date = date_to_db($dates[1]);

        if(Auth::user()->hasRole('partner-admin'))
        {
            $client_id = Auth::user()->partner_admin_detail->client_id;            
        }elseif(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head')||
                Auth::user()->hasRole('partner-quality-head'))
        {
            $client_id = Auth::user()->spoc_detail->partner->client_id;
        }elseif(Auth::user()->hasRole('client')){
            if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
        }     
        if(Auth::user()->id == 44) {
            $partner_id = 1;
            $location_id = 2;
        } else {
            if($request->selected_elements[0]['partner_id'] == 'all') {
                $partner_id='%';
            } else {
                $partner_id = $request->selected_elements[0]['partner_id'];
            }
            
            $location_id = $request->selected_elements[0]['location_id'];
        }
        
        $process_id = $request->selected_elements[0]['process_id'];
        $lob = $request->selected_elements[0]['lob'];
        // starts rebuttal 
        $rebuttal_data = [];

        $audit_data = Audit::where('client_id',$client_id)
                                       ->where('partner_id','like',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->where('audit_date',">=",$start_date)
                                       ->where('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                            $query->where('lob', 'like', $lob);
                                        })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])
                                       ->get();

        
        if($client_id == 9) {
            $target = MonthTarget::where('client_id',$client_id)
            ->where('partner_id',$partner_id)
            ->where('process_id',$process_id)
            ->where('lob', 'like', $lob)
            ->where('month_of_target',">=","05/2020")
            ->first();  

            if(gettype($target) == "NULL"){
                $coverage['target'] = 0;
                $coverage['achived'] = $audit_data->count();
                $coverage['achived_per'] = round(($audit_data->count()/450)*100);
            }else {
                $coverage['target'] = $target->eq_audit_target_mtd;
                $coverage['achived'] = $audit_data->count();
        $coverage['achived_per'] = round(($audit_data->count()/$target->eq_audit_target_mtd)*100);
            }
           
           
        }else {
            $coverage['target'] = 450;
            $coverage['achived'] = $audit_data->count();
            $coverage['achived_per'] = round(($audit_data->count()/450)*100);
        }
                                

        


        

        $final_data['coverage'] = $coverage;

        $temp_total_rebuttal = 0;
        $temp_accepted_rebuttal = 0;
        $temp_rejected_rebuttal=0;

        foreach ($audit_data as $key => $value) {
            if($value->rebuttal_status > 0)
            {
                $temp_total_rebuttal += $value->audit_rebuttal_count;
                $temp_accepted_rebuttal += $value->audit_rebuttal_accepted->count();
                $temp_rejected_rebuttal += $value->audit_rebuttal_rejected->count();
            }
        }

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
            $rebuttal_data['rejected_per'] = round((($rebuttal_data['rejected']/$audit_data->count())*100));
        else
            $rebuttal_data['rejected_per'] = 0;

        $final_data['rebuttal'] = $rebuttal_data;
        // ends rebuttal 

        // Process Score
            $fatal_dialer_data['with_fatal_score'] = 0;
            $fatal_dialer_data['without_fatal_score'] = 0;
            
            $fatal_audit_count = $audit_data->where('is_critical',1)->count();
            $without_fatal_audit_count = $audit_data->where('is_critical',0)->count();
            $fatal_audit_score_sum=0;
            $without_fatal_audit_score_sum=0;
            foreach ($audit_data as $key => $value) {
                
                $fatal_audit_score_sum += $value->with_fatal_score_per;
                $without_fatal_audit_score_sum +=$value->overall_score;
            }
            if($audit_data->count())
            {
                $fatal_dialer_data['with_fatal_score'] = round(($fatal_audit_score_sum/$audit_data->count()));
                $fatal_dialer_data['without_fatal_score'] = round(($without_fatal_audit_score_sum/$audit_data->count()));
            }else
            {
                $fatal_dialer_data['with_fatal_score']=0;
                $fatal_dialer_data['with_fatal_score']=0;
            }

            $final_data['fatal_dialer_data'] = $fatal_dialer_data;

            //Fatal first row block
            $fatal_first_row_block['total_audits'] = $audit_data->count();
            $fatal_first_row_block['total_fatal_count_sub_parameter'] = 0;
            $fatal_first_row_block['total_fatal_audits'] = $fatal_audit_count;
            if($fatal_audit_count)
            $fatal_first_row_block['total_fatal_audit_per'] = round((($fatal_first_row_block['total_fatal_audits']/$fatal_first_row_block['total_audits'])*100));
            else
            $fatal_first_row_block['total_fatal_audit_per'] = 0;

            // added below in final_data
            //Fatal first row block

            // Parameter Wise Fatal Count & Score
            //get latest QM Sheet
            $latest_qm_sheet = QmSheet::where('client_id',$client_id)->where('process_id',$process_id)->orderBy('version','desc')->first();
            $latest_qm_sheet_id = $latest_qm_sheet->id;

            $all_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('is_non_scoring',0)->with('qm_sheet_sub_parameter')->get();

            $pwfcs = [];
            $pws;
            $params_list = [];
            $all_fatal_audits = $audit_data->where('is_critical',1);
            $count=1;
            foreach ($all_params as $key => $value) {
                $temp_params['counter'] = $count;
                $temp_params['fatal_count'] = 0;
                $temp_params['fail_count'] = 0;
                $temp_params['fatal_counted_score'] = 0;
                $temp_params['parameter'] = $value->parameter;

                $params_list[] = ucfirst($value->parameter);

                foreach ($audit_data as $bkey => $bvalue) {
                    $temp_params['fatal_count'] += $bvalue->audit_parameter_result->where('parameter_id',$value->id)->where('is_critical',1)->count();
                    $temp_params['fail_count'] += $bvalue->audit_results->where('parameter_id',$value->id)->where('selected_option',2)->count();
                    // $temp_row = $bvalue->audit_parameter_result->where('parameter_id',$value->id)->where('is_critical',1)->first();
                    $temp_params['fatal_counted_score'] += $bvalue->audit_parameter_result->where('parameter_id',$value->id)->sum('with_fatal_score_per');
                }   

                if($audit_data->count())
                $temp_params['fatal_score'] = round(($temp_params['fatal_counted_score']/$audit_data->count()));
                else
                $temp_params['fatal_score'] = 0;

                $pwfcs[] = $temp_params;

                // Parameter score 2
                $paramScore=0;
                $paramScorable=0;
                $temp_params['param_score_total'] = 0;
                foreach ($audit_data as $keyc => $valuec) {
                    $temp_params['param_score_total'] += $valuec->audit_parameter_result->where('parameter_id',$value->id)->sum('with_fatal_score_per');
                    $paramScore+=$valuec->audit_parameter_result->where('parameter_id',$value->id)->sum('with_fatal_score');
                    $paramScorable+=$valuec->audit_parameter_result->where('parameter_id',$value->id)->sum('temp_weight');
                }
                if($paramScorable != 0)
               // $temp_params['param_score'] = round(($temp_params['param_score_total']/$audit_data->count()));
                    $temp_params['param_score'] = round(($paramScore/$paramScorable)*100);
                else
                $temp_params['param_score'] = 0;
                // Parameter score 2

                $pws[] = $temp_params['param_score'];

                $count++;
            }
            $final_data['pwfcs'] = $pwfcs;
            // Parameter Wise Fatal Count & Score

            // Process Score

            // Parameter score 2
            $final_data['parameter_list'] = $params_list;
            $final_data['pws'] = $pws;
            // Parameter score 2

            // Disposition Wise Score
            $despo = [];

            $temp_all_unique_despos=[];
            foreach ($audit_data as $key => $value) {
                // get all unique despositions
                $temp_all_unique_despos[] = $value->raw_data->disposition;
            }

            $all_unique_despos = array_unique($temp_all_unique_despos);
            $all_unique_despos_counts = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_unique_despos_score_total = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_unique_despos_score = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_audit_counts=array();
            $all_scores=array();
            foreach ($audit_data as $key => $value) {
                if($temp_id = array_search($value->raw_data->disposition,$all_unique_despos,true))
                {   
                    $all_unique_despos_counts[$temp_id] += 1;
                    $all_unique_despos_score_total[$temp_id] += $value->overall_score;
                }
            }

            $s=0;
            foreach ($all_unique_despos as $key => $value) {
                $all_audit_counts[$s]=Audit::where('client_id',$client_id)
                                       ->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->where('audit_date',">=",$start_date)
                                       ->where('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$value) {
                                            $query->where('partner_location_id', 'like', $location_id);  
                                            $query->where('disposition', 'like', $value);                              
                                        })->get()->count(); 
                $audit=Audit::where('client_id',$client_id)
                                       ->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->where('audit_date',">=",$start_date)
                                       ->where('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$value) {
                                            $query->where('partner_location_id', 'like', $location_id); 
                                            $query->where('disposition', 'like', $value);                                  
                                        })->withCount(['audit_results'])
                                       ->get();
                $score=0;
                $scorable=0;
                foreach ($audit as $key => $value) {                
                    foreach ($value->audit_results->where('is_non_scoring',0) as $keyb => $valueb) {
                        $score += $valueb->score;
                        $scorable += $valueb->after_audit_weight;
                    }
                }
                if($scorable != 0) {
                    $all_scores[$s] = round(($score/$scorable)*100);
                }else {
                    $all_scores[$s] = 0;
                }
                $s++;   
                /*if($all_unique_despos_counts[$key])
                    $all_unique_despos_score[$key] = round(($all_unique_despos_score_total[$key]/$all_unique_despos_counts[$key]));
                else
                    $all_unique_despos_score[$key] = 0;*/
            }

            $despo['all_unique_despos'] = array_values($all_unique_despos);
            $despo['all_unique_despos_counts'] = array_values($all_audit_counts);
            $despo['all_unique_despos_score'] = array_values($all_scores);

            $final_data['disposition'] = $despo;
            // Disposition Wise Score

            //Sub Parameter Wise Score
            //set param-subparam array
            $tmp_params=[];
            foreach ($all_params as $key => $value) {
                $temp_sps = [];
                $temp_sps['parameter_id'] = $value->id;
                $temp_sps['parameter'] = ucfirst($value->parameter);
                $temp_sps_list = [];
                //Sub parameter details
                $temp_sps_detail = [];
                foreach ($value->qm_sheet_sub_parameter as $keyb => $valueb) {
                    $temp_sps_list[] = ucfirst(strtolower($valueb->sub_parameter));
                    $temp_sps_detail[] = str_replace('"', '', $valueb->details);
                    $temp_sps['sub_p_id'][] = $valueb->id;
                    $temp_sps['sp_p_score'][] = 0;
                    $temp_sps['sp_p_weight'][] = 0;
                    $temp_sps['sp_p_obtained'][] = 0;

                    $temp_sps['sp_p_fatal_count'][] = 0;
                }
                $temp_sps['temp_sps_list'] = $temp_sps_list;
                $temp_sps['temp_sps_detail'] = $temp_sps_detail;
                $tmp_params[] = $temp_sps;
            }
            // get all audit results 
            foreach ($audit_data as $key => $value) {
                foreach ($tmp_params as $keyb => $valueb) {
                    foreach ($valueb['sub_p_id'] as $keyc => $valuec) {

                        $tmp_params[$keyb]['sp_p_obtained'][$keyc] += $value->audit_results->where('parameter_id',$valueb['parameter_id'])->where('sub_parameter_id',$valuec)->sum('score');

                        $tmp_params[$keyb]['sp_p_weight'][$keyc] += $value->audit_results->where('parameter_id',$valueb['parameter_id'])->where('sub_parameter_id',$valuec)->sum('after_audit_weight');

                        $tmp_params[$keyb]['sp_p_fatal_count'][$keyc] += $value->audit_results->where('parameter_id',$valueb['parameter_id'])->where('sub_parameter_id',$valuec)->where('is_critical',1)->count();
                    }
                }
            }

            //settle down the score from sum
            foreach ($tmp_params as $key => $value) {
                foreach ($value['sp_p_score'] as $keyb => $valueb) {


                    $fatal_first_row_block['total_fatal_count_sub_parameter'] += $tmp_params[$key]['sp_p_fatal_count'][$keyb];

                    if($tmp_params[$key]['sp_p_weight'][$keyb])
                    {
                        $tmp_params[$key]['sp_p_score'][$keyb] = round(($tmp_params[$key]['sp_p_obtained'][$keyb]/$tmp_params[$key]['sp_p_weight'][$keyb])*100);
                    }else
                    {
                        $tmp_params[$key]['sp_p_score'][$keyb] = 0;
                    }
                }
            }
            $final_data['fatal_first_row_block'] = $fatal_first_row_block;
            $final_data['spws'] = $tmp_params;
            //Sub Parameter Wise Score


            // starts QRC
            $temp_qrc['query_count'] = $audit_data->where('qrc_2','Query')->count();
            $temp_qrc['query_fatal_count'] = $audit_data->where('qrc_2','Query')->where('is_critical',1)->count();
            $temp_qrc['query_fatal_score_sum'] = $audit_data->where('qrc_2','Query')->sum('with_fatal_score_per');

            if($temp_qrc['query_count'])
            $temp_qrc['query_fatal_score'] = round(($temp_qrc['query_fatal_score_sum']/$temp_qrc['query_count']));
            else
            $temp_qrc['query_fatal_score'] = 0;

            $temp_qrc['request_count'] = $audit_data->where('qrc_2','Request')->count();
            $temp_qrc['request_fatal_count'] = $audit_data->where('qrc_2','Request')->where('is_critical',1)->count();
            $temp_qrc['request_fatal_score_sum'] = $audit_data->where('qrc_2','Request')->sum('with_fatal_score_per');

            if($temp_qrc['request_count'])
            $temp_qrc['request_fatal_score'] = round(($temp_qrc['request_fatal_score_sum']/$temp_qrc['request_count']));
            else
            $temp_qrc['request_fatal_score'] =0;

            $temp_qrc['complaint_count'] = $audit_data->where('qrc_2','Complaint')->count();
            $temp_qrc['complaint_fatal_count'] = $audit_data->where('qrc_2','Complaint')->where('is_critical',1)->count();
            $temp_qrc['complaint_fatal_score_sum'] = $audit_data->where('qrc_2','Complaint')->sum('with_fatal_score_per');

            if($temp_qrc['complaint_count'])
            $temp_qrc['complaint_fatal_score'] = round(($temp_qrc['complaint_fatal_score_sum']/$temp_qrc['complaint_count']));
            else
            $temp_qrc['complaint_fatal_score'] = 0;

            $qrc_data['audit_count']=[$temp_qrc['query_count'],$temp_qrc['request_count'],$temp_qrc['complaint_count']];
            $qrc_data['fatal_count']=[$temp_qrc['query_fatal_count'],$temp_qrc['request_fatal_count'],$temp_qrc['complaint_fatal_count']];
            $qrc_data['score']=[$temp_qrc['query_fatal_score'],$temp_qrc['request_fatal_score'],$temp_qrc['complaint_fatal_score']];

            $final_data['qrc'] = $qrc_data;
            // ends QRC

            // quartile starts QRC
            $all_agents = [];
            foreach ($audit_data as $key => $value) {
                $all_agents[] = $value->raw_data->emp_id;
            }

            $all_unique_agents = array_unique($all_agents);
            $all_audit_score=[];
            foreach ($all_unique_agents as $key => $value) {

                $agent_all_audit_score = Audit::where('client_id',$client_id)
                                       ->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->where('audit_date',">=",$start_date)
                                       ->where('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($value,$location_id) {
                                            $query->where('emp_id', 'like', $value);
                                            $query->where('partner_location_id', 'like', $location_id);  
                                        })->get();
                $scored=0;
                $scorable=0;
                foreach ($agent_all_audit_score as $key => $value1) {                
                    //$score += $value1->with_fatal_score_per;
                    //$scorable += 1; 
                    foreach ($value1->audit_results->where('is_non_scoring',0)->where('is_critical',1) as $keyb => $valueb) {
                        $scored += $valueb->score;
                        $scorable += $valueb->after_audit_weight;                                   
                    }              
                }
                
                if($scorable != 0) {                
                    $score=round(($scored/$scorable)*100);
                } else  {
                    $score=0;
                }
                
                $all_audit_score[] = ["name"=>$value,
                                      "audit_count"=>$agent_all_audit_score->count(),
                                      "with_fatal_score_per_sum"=>$agent_all_audit_score->sum('with_fatal_score_per'),
                                      "score"=>$score];
            }

            // echo "<pre>";
            // print_r($all_audit_score); die;

            // check the fall in of all agents
            $quartile_data[0] = 0;
            $quartile_data[1] = 0;
            $quartile_data[2] = 0;
            $quartile_data[3] = 0;

            foreach ($all_audit_score as $key => $value) {
                if($value['score'] >= 0 && $value['score'] < 41)
                    $quartile_data[0] += 1;
                else if($value['score'] >= 41  && $value['score'] < 61)
                    $quartile_data[1] += 1;
                else if($value['score'] >= 61  && $value['score'] < 81)
                    $quartile_data[2] +=1;
                else if($value['score'] > 80)
                    $quartile_data[3] += 1;
            }


            $final_data['quartile'] = $quartile_data;
            $final_data['user_id']=Auth::user()->id;
            // quartile ends QRC

            //non scoring sub parameter
            //get para - sub para
            $non_scoring_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('is_non_scoring',1)->with('qm_sheet_sub_parameter')->get();
            $non_scoring_params_name='';
            if($non_scoring_params->isNotEmpty())
            {
                        $non_scoring_params_name = $non_scoring_params[0]->parameter;
                        $non_scoring_params = $non_scoring_params[0]->qm_sheet_sub_parameter;
            }

            $temp_non_scoring_params=[];
            foreach ($non_scoring_params as $key => $value) {

                $temp_non_scoring_params[] = ['non_scoring_option_group'=>$value->non_scoring_option_group,'name'=>$value->sub_parameter,'id'=>$value->id,'count'=>[0,0,0]];
            }

            foreach ($audit_data as $key => $value) {

                foreach ($temp_non_scoring_params as $keyb => $valueb) {

                $a_result = $value->audit_results->where('sub_parameter_id',$valueb['id']);
                $temp_a_result=[];
                foreach ($a_result as $keyx => $valuex) {
                    $temp_a_result = $valuex;
                }

                if($temp_a_result)
                switch ($valueb['non_scoring_option_group']) {
                    case 1:
                    {  
                        if($temp_a_result->selected_option==1)
                            $temp_non_scoring_params[$keyb]['count'][0] +=1;
                        else 
                            $temp_non_scoring_params[$keyb]['count'][1] +=1;

                        break;
                    }
                    case 2:
                    {  
                        if($temp_a_result->selected_option==3)
                            $temp_non_scoring_params[$keyb]['count'][0] +=1;
                        else 
                            $temp_non_scoring_params[$keyb]['count'][1] +=1;


                        break;
                    }
                    case 3:
                    {  
                       if($temp_a_result->selected_option==6 || $temp_a_result->selected_option==7)
                            $temp_non_scoring_params[$keyb]['count'][0] +=1;
                        else 
                            $temp_non_scoring_params[$keyb]['count'][1] +=1;


                        break;
                    }
                    case 4:
                    {  

                        if($temp_a_result->selected_option==10 || $temp_a_result->selected_option==11)
                            $temp_non_scoring_params[$keyb]['count'][0] +=1;
                        else 
                            $temp_non_scoring_params[$keyb]['count'][1] +=1;
                        
                        break;
                    }
                                  
                    default:
                        # code...
                        break;
                }
                    # code...
                }
            }
            
            $temp_all_non_scoring_sub_parameter_list=[];
            $temp_all_non_scoring_sub_parameter_data=[];
            foreach ($temp_non_scoring_params as $key => $value) {

                if(($temp_non_scoring_params[$key]['count'][0]+$temp_non_scoring_params[$key]['count'][1])!=0)
                {
                    $temp_non_scoring_params[$key]['count'][2] = round(($temp_non_scoring_params[$key]['count'][0]/($temp_non_scoring_params[$key]['count'][0]+$temp_non_scoring_params[$key]['count'][1]))*100);    
                }else
                {
                    $temp_non_scoring_params[$key]['count'][2]=0;
                }
                

                $temp_all_non_scoring_sub_parameter_list[] = $value['name'];
                $temp_all_non_scoring_sub_parameter_data[] = $temp_non_scoring_params[$key]['count'][2];


            }   

            $final_data['non_scoring_params'] = ["list"=>$temp_all_non_scoring_sub_parameter_list,"score"=>$temp_all_non_scoring_sub_parameter_data,"names"=>$non_scoring_params_name];
            //non scoring sub parameter

            // starts process stats
            $fail_count=0;
            $critical_count=0;
            $na_count=0;
            foreach ($audit_data as $key => $value) {
                $fail_count += $value->audit_results->where('selected_option',2)->count();
                $critical_count += $value->audit_results->where('selected_option',3)->count();
                $na_count += $value->audit_results->where('selected_option',4)->count();
            }

            $qm_sheet_sp_count = QmSheetSubParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('non_scoring_option_group',0)->count();
            $audit_sp_count = $audit_data->count()*$qm_sheet_sp_count;

            if($audit_data->count())
            {
                $process_stats['dpu'] = round((($fail_count+$critical_count) / $audit_data->count()),2);
                $process_stats['dpo'] = round((($fail_count+$critical_count) / ($audit_sp_count-$na_count)),2);
                $process_stats['dpmo'] = $process_stats['dpo']*1000000;

                $process_stats['ppm'] = round(($fatal_audit_count/$audit_data->count())*1000000,2);

                $process_stats['fty'] = ($audit_data->where('is_critical',0)->count()/$audit_data->count());
            }
            else
            {
                $process_stats['dpu'] =0;
                $process_stats['dpo'] = 0;
                $process_stats['dpmo'] = 0;
                $process_stats['ppm'] = 0;
                $process_stats['fty']=0;
            }




            $final_data['process_stats'] = $process_stats;
            // ends process stats


            // pareto data
            $pareto_audit_result = AuditResult::whereHas('audit', function (Builder $query) use ($partner_id,$process_id,$start_date,$end_date,$location_id) {
                        $query->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->where('audit_date',">=",$start_date)
                                       ->where('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                        });
                    })->with('reason')->get();

        $all_uni_reaons=[];
        $all_uni_reaons_a=[];
        $all_uni_reaons_b=[];
        $all_uni_reaons_c=[];
        $all_uni_reaons_d=[];
        $all_uni_reaons_e=[];
        foreach ($pareto_audit_result as $key => $value) {

            if($value->reason)
            $all_uni_reaons_a[$value->reason_id]=['count'=>0,'name'=>$value->reason->name];
        }

        //get count
        foreach ($all_uni_reaons_a as $key => $value) {
            $count = $pareto_audit_result->where('reason_id',$key)->count();
            $all_uni_reaons_a[$key]['count'] = $count;
        }

        usort($all_uni_reaons_a, function($a, $b) {
            return $b['count'] - $a['count'];
        });

        $runningSum = 0;
        foreach ($all_uni_reaons_a as $kk=>$vv) {
            $runningSum += $vv['count'];
            $all_uni_reaons_b[$kk] = $vv['count'];
            $all_uni_reaons_c[$kk] = $runningSum;
            $all_uni_reaons_d[$kk] = $vv['name'];
        }

        
        foreach ($all_uni_reaons_c as $kk=>$vv) {
            $all_uni_reaons_e[$kk] = (round(($vv/$runningSum)*100,0));
        }

        $final_data['pareto_data']['count'] = $all_uni_reaons_b;
        $final_data['pareto_data']['per'] = $all_uni_reaons_e;
        $final_data['pareto_data']['reasons'] = $all_uni_reaons_d;
            // pareto data


        return response()->json(['status'=>200,'message'=>"Success",'data'=>$final_data], 200);
    }

     public function get_client_disposition_wise_report_data(Request $request)
    {
        $dates = explode("-", $request->selected_elements[0]['dates']);

        $start_date = date_to_db($dates[0]);
        $end_date = date_to_db($dates[1]);
        if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
        $partner_id = $request->selected_elements[0]['partner_id'];
        $location_id = $request->selected_elements[0]['location_id'];
        $process_id = $request->selected_elements[0]['process_id'];
        $lob = $request->selected_elements[0]['lob'];
        // starts rebuttal 
          $rebuttal_data = [];

          $audit_data = Audit::where('client_id',$client_id)
                                       ->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->wheredate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                            $query->where('lob', 'like', $lob);         
                                        })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])
                                       ->get();                                       

           //get latest QM Sheet
            $latest_qm_sheet = QmSheet::where('client_id',$client_id)->where('process_id',$process_id)->orderBy('version','desc')->first();
            $latest_qm_sheet_id = $latest_qm_sheet->id;

            $all_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('is_non_scoring',0)->with('qm_sheet_sub_parameter')->get();
            
            $temp_all_unique_despos=[];
            foreach ($audit_data as $key => $value) {
                // get all unique despositions
                $temp_all_unique_despos[] = $value->raw_data->disposition;
            }
           
            $all_unique_despos = array_unique($temp_all_unique_despos);
            $all_unique_despos_count = array_count_values($temp_all_unique_despos);  

            $all_unique_despos_counts = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_unique_despos_score_total = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_unique_despos_score = array_map(function($val) { return 0; }, $all_unique_despos);
            foreach ($audit_data as $key => $value) {
                if($temp_id = array_search($value->raw_data->disposition,$all_unique_despos,true))
                {   
                    $all_unique_despos_counts[$temp_id] += 1;
                    $all_unique_despos_score_total[$temp_id] += $value->overall_score;
                }
            }
            foreach ($all_unique_despos_counts as $key => $value) {
                if($all_unique_despos_counts[$key])
                    $all_unique_despos_score[$key] = round(($all_unique_despos_score_total[$key]/$all_unique_despos_counts[$key]));
                else
                    $all_unique_despos_score[$key] = 0;
            }  
           
            foreach ($all_params as $key => $value) {
                $temp_param_data[$value->id] = [];
                $temp_param_data[$value->id]['name'] = $value->parameter;
                $temp_param_data[$value->id]['data']=[];
                $temp_subp_data=[];
                $temp_sum=[];
                $temp_tot_score=[];
                $temp_tot_scorable=[];
                foreach ($value->qm_sheet_sub_parameter as $keyb => $valueb) {
                    $temp_subp_data[$valueb->id] = [];
                    $temp_subp_data[$valueb->id]['name'] = $valueb->sub_parameter;
                    $temp_data=[];
                    foreach ($all_unique_despos as $keyc => $valuec) {
                        $temp_data[$valuec]['scored'] = 0;
                        $temp_data[$valuec]['scorable'] = 0;
                        $temp_data[$valuec]['score']=0;
                        $temp_data[$valuec]['result_id']=[];
                        $temp_data[$valuec]['fatal_count']=0;                        
                        $temp_sum[$valuec] = 0;
                        $temp_tot_score[$valuec] = 0;
                        $temp_tot_scorable[$valuec] = 0;
                    }
                    $temp_data['total'] = 0;
                    $temp_sum['total'] = 0;
                    $temp_tot_score['total']=0;
                    $temp_tot_scorable['total']=0;
                    $temp_subp_data[$valueb->id]['data'] = $temp_data;
                }
                $temp_param_data[$value->id]['data'] = $temp_subp_data;
                $temp_param_data[$value->id]['sum'] = $temp_sum;
                $temp_param_data[$value->id]['temp_tot_score'] = $temp_tot_score;
                $temp_param_data[$value->id]['temp_tot_scorable'] = $temp_tot_scorable;

            }

           
            
            foreach ($audit_data as $key => $value) {
                
                foreach ($value->audit_results->where('is_non_scoring',0) as $keyb => $valueb) {
                    $temp_param_data[$valueb->parameter_id]['data'][$valueb->sub_parameter_id]['data'][$value->raw_data->disposition]['scored'] += $valueb->score;
                    $temp_param_data[$valueb->parameter_id]['data'][$valueb->sub_parameter_id]['data'][$value->raw_data->disposition]['scorable'] += $valueb->after_audit_weight;
                    $temp_param_data[$valueb->parameter_id]['data'][$valueb->sub_parameter_id]['data'][$value->raw_data->disposition]['result_id'][] = $valueb->id;
                    
                    if($valueb->is_critical)
                    {
                        $temp_param_data[$valueb->parameter_id]['data'][$valueb->sub_parameter_id]['data'][$value->raw_data->disposition]['fatal_count'] += 1; 
                    }                                   
                   
                }

            }
           
            // set scores
            $dispostion=[];            
            foreach ($temp_param_data as $key => $value) {
                
                foreach ($value['data'] as $keyb => $valueb) {
                        $score_total = 0;

                        foreach ($all_unique_despos_count as $keyc => $valuec) {
                            if($temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'])
                            $score = round(($temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scored']/$temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'])*100);
                            else
                                $score = 0;

                            $temp_param_data[$key]['data'][$keyb]['data'][$keyc]['score'] = $score;
                            $temp_param_data[$key]['data'][$keyb]['data']['total'] += $score;

                            $temp_param_data[$key]['sum'][$keyc] += $score;
                            $temp_param_data[$key]['temp_tot_score'][$keyc] += $temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scored'];
                            $temp_param_data[$key]['temp_tot_scorable'][$keyc] += $temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'];
                        }

                        $temp_param_data[$key]['data'][$keyb]['data']['total'] = round(($temp_param_data[$key]['data'][$keyb]['data']['total']/count($all_unique_despos_count)));

                        $temp_param_data[$key]['sum']['total'] += $temp_param_data[$key]['data'][$keyb]['data']['total'];
                }
                
                foreach ($all_unique_despos_count as $keyd => $valued) {
                    if($temp_param_data[$key]['temp_tot_scorable'][$keyd] != 0) {
                        $temp_param_data[$key]['sum'][$keyd] = round(($temp_param_data[$key]['temp_tot_score'][$keyd]/$temp_param_data[$key]['temp_tot_scorable'][$keyd])*100);                                                      
                    } else {
                        $temp_param_data[$key]['sum'][$keyd] =0;
                    }
                }
                $temp_param_data[$key]['sum']['total'] = round(($temp_param_data[$key]['sum']['total']/count($value['data'])));
        }
        $score=[];
        $score['scored']=[];
        $score['scorable']=[];
        foreach ($all_unique_despos_count as $keyc => $valuec) {
            $score['scored'][$keyc]=0;
            $score['scorable'][$keyc]=0;
        }
        foreach($temp_param_data as $key => $value) {
            foreach ($value['data'] as $keyb => $valueb) { 
               foreach ($all_unique_despos_count as $keyc => $valuec) {
                  $score['scored'][$keyc] +=$temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scored'];
                  $score['scorable'][$keyc] +=$temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'];
               }
            }
        }
        //echo "<pre>";
        //print_r($score); die;
        $all_scores=array();
        $all_audit_counts=array();
        $all_na_counts=array();
        foreach ($all_unique_despos_count as $keyc => $valuec) {
            if($score['scorable'][$keyc] != 0){
                $all_scores[$keyc]=round(($score['scored'][$keyc]/$score['scorable'][$keyc])*100);
            } else {
                 $all_scores[$keyc]=0;
            }
            $all_audit_counts[$keyc]=Audit::where('client_id',$client_id)
                                       ->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->where('audit_date',">=",$start_date)
                                       ->where('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$keyc) {
                                            $query->where('partner_location_id', 'like', $location_id);  
                                            $query->where('disposition', 'like', $keyc);                              
                                        })->get()->count(); 
            $all_na_counts[$keyc]=Audit::where('client_id',$client_id)
                                       ->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->where('audit_date',">=",$start_date)
                                       ->where('audit_date',"<=",$end_date)
                                       ->where('case_id','like',"NA%")
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$keyc) {
                                            $query->where('partner_location_id', 'like', $location_id);  
                                            $query->where('disposition', 'like', $keyc);                              
                                        })->get()->count();            
        }
        // $final_data['data'] = $temp_param_data;
        $final_data['audit_count'] = array_values($all_audit_counts);
        $final_data['audit_score'] = array_values($all_scores); 
        $final_data['na_counts'] = array_values($all_na_counts);
        $divideby=round(count($all_unique_despos) * 100);
        $final_data['audit_score_total']=round(array_sum($final_data['audit_score'])/$divideby*100);
        $final_data['audit_count_total']=round(array_sum($final_data['audit_count']));
        $final_data['data'] = $temp_param_data;
        $final_data['despositions'] = $all_unique_despos;
        return response()->json(['status'=>200,'message'=>"Success",'data'=>$final_data], 200);
    }
     public function get_client_agent_wise_report_data(Request $request)
    {
            
        $dates = explode("-", $request->selected_elements[0]['dates']);

        $start_date = date_to_db($dates[0]);
        $end_date = date_to_db($dates[1]);
        if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
        $partner_id = $request->selected_elements[0]['partner_id'];
        $location_id = $request->selected_elements[0]['location_id'];
        $process_id = $request->selected_elements[0]['process_id'];
        $lob = $request->selected_elements[0]['lob'];

        // starts rebuttal 
          $rebuttal_data = [];

          $audit_data = Audit::where('client_id',$client_id)
                                       ->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->where('audit_date',">=",$start_date)
                                       ->where('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                            $query->where('lob', 'like', $lob);    
                                        })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])
                                       ->get();

            //get latest QM Sheet
            $latest_qm_sheet = QmSheet::where('client_id',$client_id)->where('process_id',$process_id)->orderBy('version','desc')->first();
            $latest_qm_sheet_id = $latest_qm_sheet->id;
            $all_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('is_non_scoring',0)->with('qm_sheet_sub_parameter')->get();         

            $temp_all_unique_despos=[];
            foreach ($audit_data as $key => $value) {
                // get all unique despositions
                $temp_all_unique_despos[] = $value->raw_data->emp_id;
            }
                      
            $all_unique_despos = array_unique($temp_all_unique_despos);
            $all_unique_despos_count = array_count_values($temp_all_unique_despos);

            foreach ($all_params as $key => $value) {
                $temp_param_data[$value->id] = [];
                $temp_param_data[$value->id]['name'] = $value->parameter;
                $temp_param_data[$value->id]['data']=[];
                $temp_subp_data=[];
                $temp_sum=[];
                $temp_tot_score=[];
                $temp_tot_scorable=[];
                foreach ($value->qm_sheet_sub_parameter as $keyb => $valueb) {
                    $temp_subp_data[$valueb->id] = [];
                    $temp_subp_data[$valueb->id]['name'] = $valueb->sub_parameter;
                    $temp_data=[];
                    foreach ($all_unique_despos as $keyc => $valuec) {
                        $temp_data[$valuec]['scored'] = 0;
                        $temp_data[$valuec]['scorable'] = 0;
                        $temp_data[$valuec]['score']=0;
                        $temp_data[$valuec]['result_id']=[];
                        $temp_sum[$valuec] = 0;
                        $temp_tot_score[$valuec] = 0;
                        $temp_tot_scorable[$valuec] = 0;
                    }
                    $temp_data['total'] = 0;
                    $temp_sum['total'] = 0;
                    $temp_tot_score['total']=0;
                    $temp_tot_scorable['total']=0;
                    $temp_subp_data[$valueb->id]['data'] = $temp_data;
                }
                $temp_param_data[$value->id]['data'] = $temp_subp_data;
                $temp_param_data[$value->id]['sum'] = $temp_sum;
                $temp_param_data[$value->id]['temp_tot_score'] = $temp_tot_score;
                $temp_param_data[$value->id]['temp_tot_scorable'] = $temp_tot_scorable;
            }

            foreach ($audit_data as $key => $value) {
                foreach ($value->audit_results->where('is_non_scoring',0) as $keyb => $valueb) {
                    $temp_param_data[$valueb->parameter_id]['data'][$valueb->sub_parameter_id]['data'][$value->raw_data->emp_id]['scored'] += $valueb->score;
                    $temp_param_data[$valueb->parameter_id]['data'][$valueb->sub_parameter_id]['data'][$value->raw_data->emp_id]['scorable'] += $valueb->after_audit_weight;
                    $temp_param_data[$valueb->parameter_id]['data'][$valueb->sub_parameter_id]['data'][$value->raw_data->emp_id]['result_id'][] = $valueb->id;
                }
            }

            // set scores
            $dispostion=[];
            foreach ($temp_param_data as $key => $value) {
                
                foreach ($value['data'] as $keyb => $valueb) {
                        $score_total = 0;

                        foreach ($all_unique_despos_count as $keyc => $valuec) {
                            if($temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'])
                            $score = round(($temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scored']/$temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'])*100);
                            else
                                $score = 0;

                            $temp_param_data[$key]['data'][$keyb]['data'][$keyc]['score'] = $score;
                            $temp_param_data[$key]['data'][$keyb]['data']['total'] += $score;

                            $temp_param_data[$key]['sum'][$keyc] += $score;
                            $temp_param_data[$key]['temp_tot_score'][$keyc] += $temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scored'];
                            $temp_param_data[$key]['temp_tot_scorable'][$keyc] += $temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'];
                        }

                        $temp_param_data[$key]['data'][$keyb]['data']['total'] = round(($temp_param_data[$key]['data'][$keyb]['data']['total']/count($all_unique_despos_count)));

                        $temp_param_data[$key]['sum']['total'] += $temp_param_data[$key]['data'][$keyb]['data']['total'];
                }

                foreach ($all_unique_despos_count as $keyd => $valued) {
                    if($temp_param_data[$key]['temp_tot_scorable'][$keyd] != 0) {
                        $temp_param_data[$key]['sum'][$keyd] = round(($temp_param_data[$key]['temp_tot_score'][$keyd]/$temp_param_data[$key]['temp_tot_scorable'][$keyd])*100);                                                      
                    } else {
                        $temp_param_data[$key]['sum'][$keyd] =0;
                    }
                }

                $temp_param_data[$key]['sum']['total'] = round(($temp_param_data[$key]['sum']['total']/count($value['data'])));
            }

            $score=[];
            $score['scored']=[];
            $score['scorable']=[];
            foreach ($all_unique_despos_count as $keyc => $valuec) {
                $score['scored'][$keyc]=0;
                $score['scorable'][$keyc]=0;
            }
            foreach($temp_param_data as $key => $value) {
                foreach ($value['data'] as $keyb => $valueb) { 
                   foreach ($all_unique_despos_count as $keyc => $valuec) {
                      $score['scored'][$keyc] +=$temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scored'];
                      $score['scorable'][$keyc] +=$temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'];
                   }
                }
            }
              
            $all_scores=array();
            $all_audit_counts=array();
            $all_na_counts=array();
            foreach ($all_unique_despos_count as $keyc => $valuec) {
                if($score['scorable'][$keyc] != 0){
                    $all_scores[$keyc]=round(($score['scored'][$keyc]/$score['scorable'][$keyc])*100);
                } else {
                     $all_scores[$keyc]=0;
                }
                $all_audit_counts[$keyc]=Audit::where('client_id',$client_id)
                                           ->where('partner_id',$partner_id)
                                           ->where('process_id',$process_id)
                                           ->where('audit_date',">=",$start_date)
                                           ->where('audit_date',"<=",$end_date)
                                           ->whereHas('raw_data', function (Builder $query) use ($location_id,$keyc) {
                                                $query->where('partner_location_id', 'like', $location_id);  
                                                $query->where('emp_id', 'like', $keyc);                              
                                            })->get()->count(); 
                $all_na_counts[$keyc]=Audit::where('client_id',$client_id)
                                       ->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->where('audit_date',">=",$start_date)
                                       ->where('audit_date',"<=",$end_date)
                                       ->where('case_id','like',"NA%")
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$keyc) {
                                            $query->where('partner_location_id', 'like', $location_id);  
                                            $query->where('emp_id', 'like', $keyc);                              
                                        })->get()->count();             
            }

            //echo "<pre>";
            //print_r($all_scores);
            //die; 

        $final_data['data'] = $temp_param_data;
        $final_data['audit_score'] =  $all_scores;
        $final_data['audit_count']=$all_audit_counts;
        $final_data['na_count']=$all_na_counts;

        $total_audit_by_agent=array_sum($all_audit_counts);
        $overall_score_count=count($all_unique_despos)*100; 
        $total_audit_score=round((array_sum($all_scores)/$overall_score_count)*100); 

        $final_data['total_audit_count'] = $total_audit_by_agent;
        $final_data['total_audit_score'] = $total_audit_score;       
        $final_data['despositions'] = $all_unique_despos;
        return response()->json(['status'=>200,'message'=>"Success",'data'=>$final_data], 200);

    }
    public function parameter_trending_report()
    {
        return view('reports.parameter_trending_report');
    }

    public function get_parameter_trending_report_data(Request $request)
    {
        $last_six_month_list = [];
        $last_six_month_list[] = date('F');
            for ($i = 1; $i <6; $i++) {
              $last_six_month_list[] = date('F', strtotime("-$i month"));
            }
        $last_six_month_list = array_reverse($last_six_month_list,false);
        $temp_date = '01 '.$last_six_month_list[0].' '.date('Y');
        $start_date = date('Y-m-d', strtotime($temp_date));
        $end_date = date('Y-m-d');
        if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
        $partner_id = $request->selected_elements[0]['partner_id'];
        $location_id = $request->selected_elements[0]['location_id'];
        $process_id = $request->selected_elements[0]['process_id'];

        $audit_data = Audit::where('client_id',$client_id)
                                       ->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                        })->withCount(['audit_parameter_result','raw_data','audit_results'])
                                       ->get();

        //get latest QM Sheet
        $latest_qm_sheet = QmSheet::where('client_id',$client_id)->where('process_id',$process_id)->orderBy('version','desc')->first();
        $latest_qm_sheet_id = $latest_qm_sheet->id;
        $all_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('is_non_scoring',0)->get();


        // month array
        $month_array=[];
        foreach ($last_six_month_list as $key => $value) {
            $month_array[$value]=['scored'=>0,'scorable'=>0,'score_per'=>0,'count'=>0];
        }

        $param_data=[];
        foreach ($all_params as $key => $value) {
            $param_data[$value->id] = ['data'=>$month_array,'name'=>$value->parameter];
        }

        $param_data['Overall With Fatal'] = ['data'=>$month_array,'name'=>'Overall With Fatal'];
        $param_data['Overall Without Fatal'] = ['data'=>$month_array,'name'=>'Overall Without Fatal'];

        foreach ($last_six_month_list as $key => $value) {
            $temp_date = '01 '.$value.' '.date('Y');
            $temp_start_date = date('Y-m-d', strtotime($temp_date));
            $temp_date = '31 '.$value.' '.date('Y');
            $temp_end_date = date('Y-m-d', strtotime($temp_date));

            $sorted_audits = $audit_data->where('date(audit_date)','>=',$temp_start_date)->where('date(audit_date)','<=',$temp_end_date);

            //with fatal
            $param_data['Overall With Fatal']['data'][$value]['scored'] = $sorted_audits->sum('with_fatal_score_per');
            $param_data['Overall With Fatal']['data'][$value]['count'] = $sorted_audits->count();

            if($sorted_audits->count())
            $param_data['Overall With Fatal']['data'][$value]['score_per'] = round(($param_data['Overall With Fatal']['data'][$value]['scored']/$sorted_audits->count()));
            else
                $param_data['Overall With Fatal']['data'][$value]['score_per'] = 0;

            //with fatal

            //without fatal
            $param_data['Overall Without Fatal']['data'][$value]['scored'] = $sorted_audits->sum('overall_score');
            $param_data['Overall Without Fatal']['data'][$value]['count'] = $sorted_audits->count();

            if($sorted_audits->count())
                $param_data['Overall Without Fatal']['data'][$value]['score_per'] = round(($param_data['Overall Without Fatal']['data'][$value]['scored']/$sorted_audits->count()));
            else
                $param_data['Overall Without Fatal']['data'][$value]['score_per'] = 0;

            //without fatal

            //parameter wise
            foreach ($sorted_audits as $keyb => $valueb) {
                foreach ($valueb->audit_parameter_result as $keyc => $valuec) {
                    if(array_key_exists($valuec->parameter_id,$param_data))
                    {
                        $param_data[$valuec->parameter_id]['data'][$value]['scored'] += $valuec->with_fatal_score_per;
                        $param_data[$valuec->parameter_id]['data'][$value]['count'] += 1;
                    }
                }
            }
            //parameter wise
        }

        //settle parameter wise
        foreach ($param_data as $key => $value) {
            foreach ($value['data'] as $keyb => $valueb) {
                if($param_data[$key]['data'][$keyb]['count'])
                $param_data[$key]['data'][$keyb]['score_per'] = round(($param_data[$key]['data'][$keyb]['scored']/$param_data[$key]['data'][$keyb]['count']));
                else
                $param_data[$key]['data'][$keyb]['score_per'] = 0;
            }
        }

        $final_data = ['month_list'=>$last_six_month_list,'param_data'=>$param_data];
        return response()->json(['status'=>200,'message'=>"Success",'data'=>$final_data], 200);

    }

    public function raw_dump_with_audit_report(Request $request)
    {
        //dd(date_to_db($request->start_date).'||'.date_to_db($request->end_date));

       $data=[];

       $all_reason_type = ReasonType::with('reasons')->get();

       $params_data = QmSheetParameter::where('qm_sheet_id',$request->qm_sheet_id)->with('qm_sheet_sub_parameter')->get();
       $repeater_param_data=[];
       foreach ($params_data as $key => $value) {
           foreach ($value->qm_sheet_sub_parameter as $keyb => $valueb) {
               $repeater_param_data[$value->id][$valueb->id]=['name'=>$valueb->sub_parameter,'observation'=>null,'scored'=>0,'scorable'=>0,'remark'=>null,'reason_type'=>null,'reason'=>null];
           }
       }
       
       $partner_id = $request->partner_id;
       $partner_location_id = $request->location_id;
       $client_id = $request->client_id;
       
       $current_date_time = date('Y-m-d H:i:s');
       //dd(date_to_db($request->end_date));
       if(Auth::user()->hasRole('client') || Auth::user()->hasRole('partner-admin') ||
       Auth::user()->hasRole('partner-training-head') || Auth::user()->hasRole('partner-operation-head')
       || Auth::user()->hasRole('partner-quality-head')) {
            $temp_audit_data = Audit::whereHas('raw_data', function (Builder $query) use ($partner_id,$partner_location_id,$client_id) {
            $query->where('client_id',$client_id)
                ->where('partner_id','LIKE',$partner_id)
                ->where('partner_location_id','LIKE',$partner_location_id);
            })->where('qm_sheet_id',$request->qm_sheet_id)
            ->whereDate('audit_date','>=',date_to_db($request->start_date))
            
            ->where('qc_tat','<',$current_date_time)
            ->whereDate('audit_date','<=',date_to_db($request->end_date))->with(['raw_data','raw_data.location_data','raw_data.partner_detail','audit_results','qa_qtl_detail','audit_results.reason_type','audit_results.reason'])->get();

       } else {
            $temp_audit_data = Audit::whereHas('raw_data', function (Builder $query) use ($partner_id,$partner_location_id,$client_id) {
            $query->where('client_id',$client_id)
                ->where('partner_id','LIKE',$partner_id)
                ->where('partner_location_id','LIKE',$partner_location_id);
            })->where('qm_sheet_id',$request->qm_sheet_id)
            ->whereDate('audit_date','>=',date_to_db($request->start_date))
            ->whereDate('audit_date','<=',date_to_db($request->end_date))->with(['raw_data','raw_data.location_data','raw_data.partner_detail','audit_results','qa_qtl_detail','audit_results.reason_type','audit_results.reason'])->get();

       }
       
            
       foreach ($temp_audit_data as $key => $value) {
                $basic['temp_raw_data'] = $value->raw_data;
                $basic['auditor'] = $value->auditor->name;
                $basic['audit_date'] = $value->audit_date;
                $basic['partner'] = $value->raw_data->partner_detail->name;
                //dd($value->raw_data->location_data->name);
               /*  if($client_id = 9){
                    $basic['location'] = $value->raw_data->location_data->l;
                } */
               /*  else { */
                    $basic['location'] = $value->raw_data->location_data->name;
               /*  } */
                
                $basic['case_id'] = $value->case_id;
                $basic['audited_by'] = $value->qa_qtl_detail->name;
                $basic['qrc_2'] = $value->qrc_2;
                $basic['language_2'] = $value->language_2;
                $basic['refrence_number'] = $value->refrence_number;

                $basic['overall_summary'] = $value->overall_summary;
                $basic['without_fatal_score'] = $value->overall_score;
                $basic['with_fatal_score_per'] = $value->with_fatal_score_per;

                $temp_result = $value->audit_results;
                foreach ($repeater_param_data as $keyb => $valueb) {
                    foreach ($valueb as $keyc => $valuec) {
                        $to_filter_row_id = $temp_result->where('parameter_id',$keyb)->where('sub_parameter_id',$keyc)->sum('id');

                        $to_filter_row = $temp_result->find($to_filter_row_id);
                        if(!is_null($to_filter_row))
                        {
                        if($to_filter_row->is_non_scoring)
                            $repeater_param_data[$keyb][$keyc]['observation'] = return_non_scoring_observation($to_filter_row->selected_option);
                        else
                            $repeater_param_data[$keyb][$keyc]['observation'] = return_general_observation($to_filter_row->selected_option);

                        $repeater_param_data[$keyb][$keyc]['scorable'] = $to_filter_row->after_audit_weight;

                        
                        if($to_filter_row->reason_type_id)
                            {
                                $temp_reason_type = $all_reason_type->find($to_filter_row->reason_type_id);
                                $reason_type = $temp_reason_type->name;
                                $reasons = $temp_reason_type->reasons;
                                $temp_reason = $reasons->find($to_filter_row->reason_id);

                                if($temp_reason)
                                    $reason = $temp_reason->name;
                                else
                                    $reason = $to_filter_row->reason_id;
                            }
                        else
                            {
                                $reason_type = '-';
                                $reason = '-';
                            }
                        $repeater_param_data[$keyb][$keyc]['reason_type'] = $reason_type;
                        $repeater_param_data[$keyb][$keyc]['reason'] = $reason;
                        $repeater_param_data[$keyb][$keyc]['remark'] = $to_filter_row->remark;
                        $repeater_param_data[$keyb][$keyc]['scored']=$temp_result->where('parameter_id',$keyb)->where('sub_parameter_id',$keyc)->sum('score');
                        }
                    }
                }
                $basic['audit'] = $repeater_param_data;

                $data[] = $basic;

       }
       // dd($data);
       
       return view('reports.raw_duml_with_audit_report',compact(['data','repeater_param_data']));
    }

    // raw dump report filters starts

    public function get_rdr_client_list()
    {
        if(Auth::user()->hasRole('client'))
        {
            if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198 || Auth::user()->id == 279) {
            	$list=[9=>'TATA AIA'];
            } else {
                if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                    $list=[$client_id=>'DMI'];
                } else {
                    $client_id = Auth::user()->parent_client;
                    if($client_id == 0) {
                        $list=[$client_id=>'No Client Selected'];
                    } else {
                        $list=[$client_id=>'DMI'];
                    }
                    
                }            	
            }
            
        }
        elseif(Auth::user()->hasRole('partner-admin'))
        {   
            if(Auth::user()->id == 139 || Auth::user()->id == 195) {
                $list = [9=>'TATA AIA'];
            }
            else if(Auth::user()->id == 307 || Auth::user()->id == 308) {
                $list = [13=>'Hot Star'];
            }
             else {
                $list = [Auth::user()->partner_admin_detail->client_id=>Auth::user()->partner_admin_detail->client->name];
            }
            
        }elseif(Auth::user()->hasRole('partner-training-head')||Auth::user()->hasRole('partner-operation-head')||Auth::user()->hasRole('partner-quality-head'))
        {
            $list = [Auth::user()->spoc_detail->partner->client_id=>Auth::user()->spoc_detail->partner->client->name];
        }else
        {
            $list = Client::where('company_id',Auth::user()->company_id)->pluck('name','id');    
        }
        
        return response()->json(['status'=>200,'message'=>"Success",'data'=>$list], 200);
    }
    public function get_rdr_client_partner_list($client_id)
    {
        if(Auth::user()->hasRole('partner-admin'))
        {   
            if(Auth::user()->id == 139) {
                $list = [38=>'PACE'];
            } 
            elseif(Auth::user()->id == 195) {
                $list = [39=>'Arcis'];
            } 
            elseif(Auth::user()->id == 307 || Auth::user()->id == 308) {
                $list = [48=>'Sutherland'];
            } 
            else {
            $list = [Auth::user()->partner_admin_detail->id=>Auth::user()->partner_admin_detail->name];
            }

        }elseif(Auth::user()->hasRole('partner-training-head')||Auth::user()->hasRole('partner-operation-head')||Auth::user()->hasRole('partner-quality-head'))
        {
            $list = [Auth::user()->spoc_detail->partner->id=>Auth::user()->spoc_detail->partner->name];
        }else
        {
        	if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {
        		$list = Partner::where('id',32)->pluck('name','id');
        	} 
            elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                $list = Partner::where('id',40)->pluck('name','id');
            }
            elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $list = Partner::where('id',41)->pluck('name','id');
            }
            elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {
                $list = Partner::whereIn('id',[38,45,43,39])->pluck('name','id');
            }
            elseif(Auth::user()->id == 252 || Auth::user()->id == 139 || Auth::user()->id == 269) {
                $list=Partner::where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->pluck('name','id');
            } elseif(Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 280) {
              //  $list=Partner::select('name','id')->where('id',38)->orWhere('id',45)->get();

                
                $list = Partner::where('id',38)->orWhere('id',45)->pluck('name','id');
            } elseif(Auth::user()->id == 282) {
                //  $list=Partner::select('name','id')->where('id',38)->orWhere('id',45)->get();
  
                  
                  $list = Partner::where('id',39)->orWhere('id',43)->pluck('name','id');
            } 
            elseif(Auth::user()->id == 195) {
               // $list=Partner::select('name','id')->where('id',39)->get();

                $list = Partner::where('id',39)->pluck('name','id');
            }
            elseif(Auth::user()->id == 254) {
              //  $list=Partner::select('name','id')->where('id',43)->get();

                $list = Partner::where('id',43)->pluck('name','id');
            }
            elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                // $list=Partner::select('name','id')->where('id',44)->get();

                $list = Partner::where('id',44)->pluck('name','id');
            }
            else {
        		$list = Partner::where('client_id',$client_id)->pluck('name','id');
        	}
            

            $list['%'] = "All";
        }

        return response()->json(['status'=>200,'message'=>"Success",'data'=>$list], 200);
    }
    public function get_rdr_client_partner_location_list($partner_id,$client_id)
    {
        $list=[];
        $partner_location_list=[];
        $partner_process_list=[];

        if(Auth::user()->hasRole('partner-admin'))
        {
                $temp_list = PartnerLocation::where('partner_id',$partner_id)->with('location_detail')->get();
                foreach ($temp_list as $key => $value) {
                    $partner_location_list[$value->location_id] = $value->location_detail->name;
                }
                $partner_location_list["%"] = "All";

                if($partner_id=="All")
                {
                    $all_partners = Partner::where('client_id',$client_id)->with('partner_process')->get();
                    foreach ($all_partners as $key => $value) {
                        foreach ($value->partner_process as $key_b => $value_b) {
                            $partner_process_list[$value_b->process_id] = $value_b->process->name;
                        }
                    }
                }else
                {
                    $temp_list = PartnersProcess::where('partner_id',$partner_id)->with('process')->get();
                    foreach ($temp_list as $key => $value) {
                        $partner_process_list[$value->process_id] = $value->process->name;
                    }    
                }
                
        }elseif(Auth::user()->hasRole('partner-training-head')||Auth::user()->hasRole('partner-operation-head')||Auth::user()->hasRole('partner-quality-head'))
        {
                $temp_list = PartnerLocation::where('partner_id',$partner_id)->where('location_id',Auth::user()->spoc_detail->partner_location_id)->with('location_detail')->get();
                foreach ($temp_list as $key => $value) {
                    $partner_location_list[$value->location_id] = $value->location_detail->name;
                }

                $temp_list = PartnersProcess::where('partner_id',$partner_id)->with('process')->get();
                foreach ($temp_list as $key => $value) {
                    $partner_process_list[$value->process_id] = $value->process->name;
                }

        }else
        {		
        	
        		$temp_list = PartnerLocation::where('partner_id',$partner_id)->with('location_detail')->get();
        	
                
                foreach ($temp_list as $key => $value) {
                	if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246) && $value->location_id == 36) {
                		$partner_location_list[$value->location_id] = $value->location_detail->name;
                	} 
                    elseif((Auth::user()->id == 248 || Auth::user()->id == 253) && $value->location_id == 44) {
                        $partner_location_list[$value->location_id] = $value->location_detail->name;
                    }
                    elseif((Auth::user()->id == 249 || Auth::user()->id == 250) && $value->location_id == 44) {
                        $partner_location_list[$value->location_id] = $value->location_detail->name;
                    } 
                    elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                        if($value->location_id == 2 || $value->location_id == 14 || $value->location_id == 20) {
                            $partner_location_list[$value->location_id] = $value->location_detail->name;
                        }   
                    }
                    elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                        if($value->location_id == 14) {
                            $partner_location_list[$value->location_id] = $value->location_detail->name;
                        } 
                    }
                    elseif(Auth::user()->id == 139) {
                        if($value->location_id == 14) {
                            $partner_location_list[$value->location_id] = $value->location_detail->name;
                        } 
                    }
                    elseif(Auth::user()->id == 195) {
                        if($value->location_id == 2) {
                            $partner_location_list[$value->location_id] = $value->location_detail->name;
                        } 
                    }
                    elseif(Auth::user()->id == 254) {
                        if($value->location_id == 2) {
                            $partner_location_list[$value->location_id] = $value->location_detail->name;
                        } 
                    }
                    else {
                		$partner_location_list[$value->location_id] = $value->location_detail->name;
                	}
                    
                }
                $partner_location_list["%"] = "All";

                if($partner_id=="All")
                {
                   if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {  
                   		$all_partners = Partner::where('id',32)->with('partner_process')->get();
                   } 
                   elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                        $all_partners = Partner::where('id',40)->with('partner_process')->get();
                   }
                   elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                        $all_partners = Partner::where('id',41)->with('partner_process')->get();
                   }
                   elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {
                    $all_partners = Partner::whereIn('id',[38,45,43,39])->with('partner_process')->get();
                   }
                   elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                        $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
                   } elseif(Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 280) {
                         $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',45)->get();
                   } 
                    elseif(Auth::user()->id == 282) {
                        $all_partners = Partner::select('name','id')->where('id',39)->orWhere('id',43)->get();
                    }
                   elseif(Auth::user()->id == 139) {
                    $all_partners = Partner::select('name','id')->where('id',38)->get();
                   }
                   elseif(Auth::user()->id == 195) {
                    $all_partners = Partner::select('name','id')->where('id',39)->get();
                   }
                   elseif(Auth::user()->id == 254) {
                    $all_partners = Partner::select('name','id')->where('id',43)->get();
                   }
                   elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                        $all_partners = Partner::where('id',44)->with('partner_process')->get();
                   }
                   else {
                   		$all_partners = Partner::where('client_id',$client_id)->with('partner_process')->get();
                   }
                    
                    foreach ($all_partners as $key => $value) {
                        foreach ($value->partner_process as $key_b => $value_b) {
                            $partner_process_list[$value_b->process_id] = $value_b->process->name;
                        }
                    }
                }else
                {
                    $temp_list = PartnersProcess::where('partner_id',$partner_id)->with('process')->get();
                    foreach ($temp_list as $key => $value) {
                        $partner_process_list[$value->process_id] = $value->process->name;
                    }    
                }
                
        }

        $list['partner_location_list'] = $partner_location_list;
        $list['partner_process_list'] = $partner_process_list;


        return response()->json(['status'=>200,'message'=>"Success",'data'=>$list], 200);
    }
    public function get_rdr_client_process_qmsheeet_list($client_id,$process_id)
    {
        $temp_list = QmSheet::where('client_id',$client_id)->where('process_id',$process_id)->orderBy('version','desc')->get();
        $list=[];
        foreach ($temp_list as $key => $value) {
            $list[$value->id] = $value->name."-v ".$value->version;
        }
        return response()->json(['status'=>200,'message'=>"Success",'data'=>$list], 200);
    }
    // raw dump report filters ends

    public function rebuttal_status_report(Request $request)
    {

        $partner_id=null;
        $process_id=null;
        $partner_location_id=null;
        $client_list=[];
        $client_id=null;
        if(Auth::user()->hasRole('partner-admin'))
        {   
            if(Auth::user()->id == 253 || Auth::user()->id == 250 || Auth::user()->id == 252 || Auth::user()->id == 269 || Auth::user()->id == 255 || Auth::user()->id == 246 || Auth::user()->id == 139 || Auth::user()->id == 195 || Auth::user()->id == 254 ||
                Auth::user()->id == 270 ||
                Auth::user()->id == 271 || Auth::user()->id == 279 || Auth::user()->id == 283) {
                $client_id=9; 
        } else {
            if(Auth::user()->id == 307 || Auth::user()->id == 308) {
                $client_id=13;
            } else {
               $client_id = Auth::user()->partner_admin_detail->client_id; 
            }
            
        }
            if(Auth::user()->id == 139) {
                $partner_id = 38;
            } 
            elseif(Auth::user()->id == 195) {
                $partner_id = 39;
            }
            elseif(Auth::user()->id == 307 || Auth::user()->id == 308) {
                $partner_id = 48;
            }
            else {
                $partner_id = Auth::user()->partner_admin_detail->id;
            }
            
            $process_id = '%';
            $partner_location_id = '%';
        }elseif(Auth::user()->hasRole('client'))
        {
            if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
            	$client_id = 9;
            } else {
            	if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
            }
            
            $partner_id="%";
            $process_id="%";
            $partner_location_id="%";
        }elseif(Auth::user()->hasRole('process-owner'))
        {   
            if(isset($request->client_id))
            {
                $client_id = $request->client_id;
            }else
            {
                $client_id = null;
            }
            $partner_id = "%";
            $process_id = "%";
            $partner_location_id = "%";

            $client_list = Client::where('process_owner_id',Auth::Id())->pluck('name','id');
        }
        else
        {
            if(Auth::user()->id == 172 || Auth::user()->id == 198) {
                $client_id = 9;
                $partner_id='%';
                $partner_location_id='%';
            } else if(Auth::user()->id == 41) {
                $client_id = 1;
                $partner_id='%';
                $partner_location_id='%';
            } else {
                $spoc_data = Auth::user()->spoc_detail;
                $client_id = Auth::user()->spoc_detail->partner->client_id;
                $partner_id=$spoc_data->partner_id;
                $partner_location_id=$spoc_data->partner_location_id;
            }
            
            
            
            $process_id="%";
            
        }
        $data=[];

        if(Auth::user()->id == 253 || Auth::user()->id == 250 || Auth::user()->id == 252 || Auth::user()->id == 269 || Auth::user()->id == 255 || Auth::user()->id == 246 || Auth::user()->id == 139 || Auth::user()->id == 195 || Auth::user()->id == 254 || Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 279 || Auth::user()->id == 283) {
                $client_id=9; 
        }

        $start_date  = date_to_db($request->start_date);
        $end_date  = date_to_db($request->end_date);

        if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) { 
            $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',32)->get()->toArray();
        } 
        elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
            $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',40)->get()->toArray();
        }
        elseif (Auth::user()->id == 139) {
            $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',38)->get()->toArray();
        }
        elseif (Auth::user()->id == 195) {
            $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',39)->get()->toArray();
        }
        elseif (Auth::user()->id == 254) {
            $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',43)->get()->toArray();
        }
        elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
            $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',41)->get()->toArray();
        } 
        elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {
           $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->whereIn('partner_id',[38,45,43,39])->get()->toArray(); 
        }
        elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
             $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',38)->orWhere('partner_id',39)->orWhere('partner_id',43)->orWhere('partner_id',45)->get()->toArray();
        }
        elseif(Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 280) {
             $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',38)->orWhere('partner_id',45)->get()->toArray();
        }
        elseif(Auth::user()->id == 282) {
            $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',39)->orWhere('partner_id',43)->get()->toArray();
       }
        elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
             $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',44)->get()->toArray();
        }
        else {
            $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',$partner_id)->get()->toArray();
        }
/*         print_r($temp_all_lob);
        dd(); */
        if(Auth::user()->hasRole('qtl') || Auth::user()->hasRole('process-owner')) {
            $data_1 = RawData::where('status',1)
            ->where('client_id','LIKE',$client_id)
            
            ->whereHas('audit', function ($query) use($start_date,$end_date) {
              $query->where('rebuttal_status',2)
                    ->whereDate('audit_date','>=',$start_date)
                    ->whereDate('audit_date','<=',$end_date);
            })->with(['audit','audit.audit_rebuttal'])
            ->orderBy('updated_at','desc')
            ->get();
        } else {
            $data_1 = RawData::where('status',1)
            ->where('client_id','LIKE',$client_id)
            ->where('partner_id','LIKE',$partner_id)
            ->where('process_id','LIKE',$process_id)
            ->where('partner_location_id','LIKE',$partner_location_id)
            ->whereIn('lob',$temp_all_lob)
            ->whereHas('audit', function ($query) use($start_date,$end_date) {
              $query->where('rebuttal_status',2)
                    ->whereDate('audit_date','>=',$start_date)
                    ->whereDate('audit_date','<=',$end_date);
            })->with(['audit','audit.audit_rebuttal'])
            ->orderBy('updated_at','desc')
            ->get();
        }

        /* $data_1 = RawData::where('status',1)
                      ->where('client_id','LIKE',$client_id)
                      ->where('partner_id','LIKE',$partner_id)
                      ->where('process_id','LIKE',$process_id)
                      ->where('partner_location_id','LIKE',$partner_location_id)
                      ->whereIn('lob',$temp_all_lob)
                      ->whereHas('audit', function ($query) use($start_date,$end_date) {
                        $query->where('rebuttal_status',2)
                              ->whereDate('audit_date','>=',$start_date)
                              ->whereDate('audit_date','<=',$end_date);
                      })->with(['audit','audit.audit_rebuttal'])
                      ->orderBy('updated_at','desc')
                      ->get(); */
                          
        $row_data=[];
        foreach ($data_1 as $key => $value) {
            foreach ($value->audit_rebuttal->where('re_rebuttal_id',null) as $key_b => $value_b) {

                $row_data['audit_date'] = $value->audit->audit_date;
                $row_data['partner'] = $value->partner->name;
                $row_data['location'] = $value->location_data->name;
                $row_data['call_id'] = $value->call_id;
                $row_data['agent_name'] = $value->agent_name;
                $row_data['emp_id'] = $value->emp_id;                
                $row_data['doj'] = date('d-m-Y', strtotime($value->doj));
                $row_data['lob'] = $value->lob;
                $row_data['language'] = $value->audit->language_2;
                $row_data['case_id'] = $value->audit->case_id;
                $row_data['call_time'] = $value->call_time;
                $row_data['call_duration'] = $value->call_duration;
                $row_data['call_type'] = $value->call_type;
                $row_data['call_sub_type'] = $value->call_sub_type;
                $row_data['disposition'] = $value->disposition;
                $row_data['campaign_name'] = $value->campaign_name;
                $row_data['customer_name'] = $value->customer_name;
                $row_data['phone_number'] = $value->phone_number;
                $row_data['qrc'] = $value->audit->qrc_2;
                $row_data['overall_summary'] = $value->audit->overall_summary;
                $row_data['with_fatal_score'] = $value->audit->with_fatal_score_per;
                $row_data['without_fatal_score'] = $value->audit->overall_score;
                $row_data['parameter'] = $value_b->parameter->parameter;
                $row_data['sub_parameter'] = $value_b->sub_parameter->sub_parameter;
                $audit_result = AuditResult::where('audit_id',$value->audit->id)
                                            ->where('sub_parameter_id',$value_b->sub_parameter_id)
                                            ->with('reason')
                                            ->first();
                                            
                if($audit_result->reason_id)
                    $row_data['failure_reason'] = $audit_result->reason->name;
                else
                    $row_data['failure_reason'] = "N/A";

                $row_data['auditor_remark'] = $value->audit->feedback;
                $row_data['rebuttal_date'] = $value_b->created_at;
                $row_data['partner_remark'] = $value_b->remark;
                $row_data['revert_date'] = $value_b->updated_at;
                $row_data['revert_remark'] = $value_b->reply_remark;
                $row_data['status'] = rebuttal_status($value_b->status);

                if(isset($value_b->re_rebuttal_row))
                {
                    $row_data['re_rebuttal_date'] = $value_b->re_rebuttal_row->created_at;
                    $row_data['re_partner_remark'] = $value_b->re_rebuttal_row->remark;
                    $row_data['re_revert_date'] = $value_b->re_rebuttal_row->updated_at;
                    $row_data['re_revert_remark'] = $value_b->re_rebuttal_row->reply_remark;
                    $row_data['re_status'] = rebuttal_status($value_b->re_rebuttal_row->status);                    

                    $row_data['final_status'] = rebuttal_status($value_b->re_rebuttal_row->status);                    

                    if($value_b->re_rebuttal_row->status==1)
                        $row_data['score_revised'] = 'Y';
                    else
                        $row_data['score_revised'] = 'N';

                }else
                {
                    $row_data['re_rebuttal_date'] = "-";
                    $row_data['re_partner_remark'] = "-";
                    $row_data['re_revert_date'] = "-";
                    $row_data['re_revert_remark'] = "-";
                    $row_data['re_status'] = "-";

                    $row_data['final_status'] = rebuttal_status($value_b->status);                    

                    if($value_b->status==1)
                        $row_data['score_revised'] = 'Y';
                    else
                        $row_data['score_revised'] = 'N';
                }


                $row_data['score_with_fatal'] = $value_b->revised_score_with_fatal;
                $row_data['score_without_fatal'] = $value_b->revised_score_without_fatal;



                $data[] = $row_data;


            }
            
        }

        return view('reports.rebuttal_status_report',compact('data','client_list','client_id'));
    }

    public function get_agent_report1(Request $request) {
        
        $dates = explode("-", $request->date);
        $start_date = date_to_db($dates[0]);
        $end_date = date_to_db($dates[1]);
        $range=$request->range;
        $location_id =$request->location_id;
        
        if(Auth::user()->hasRole('partner-admin'))        {
           $client_id = Auth::user()->partner_admin_detail->client_id;            
        }elseif(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head')||
                Auth::user()->hasRole('partner-quality-head'))
        {
            $client_id = Auth::user()->spoc_detail->partner->client_id;
        }elseif(Auth::user()->hasRole('client')){
            if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
        }    

        $audit_data = Audit::where('client_id',$client_id)
                                       ->where('partner_id',$request->partner_id)
                                       ->where('process_id',$request->process_id)
                                       ->where('audit_date',">=",$start_date)
                                       ->where('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id) {
                                            $query->where('partner_location_id', 'like', $location_id);

                                        })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])
                                       ->get(); 
        $all_agents = [];
        $agent_name = [];
        foreach ($audit_data as $key => $value) {
            $all_agents[] = $value->raw_data->emp_id;
            $agent_name[] = $value->raw_data->agent_name;
        }

        $all_unique_agents = array_unique($all_agents);
        $all_audit_score=array();        
        foreach ($all_unique_agents as $key => $value) {

            $agent_all_audit_score = Audit::where('client_id',$client_id)
                                       ->where('partner_id',$request->partner_id)
                                       ->where('process_id',$request->process_id)
                                       ->where('audit_date',">=",$start_date)
                                       ->where('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($value) {
                                            $query->where('emp_id', 'like', $value);                                                   
                                        })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])->get();

                
            if($agent_all_audit_score->count()==0)
                $score = 0;
            else
                $score = round($agent_all_audit_score->sum('with_fatal_score_per')/$agent_all_audit_score->count());

            $data = [   "emp_id" => $value, 
                        "name"=>$agent_name[$key],
                        "audit_count"=>$agent_all_audit_score->count(),
                        "with_fatal_score_per_sum"=>$agent_all_audit_score->sum('with_fatal_score_per'),
                        "score"=>$score];
                if($score >= 0 && $score < 41)
                    $all_audit_score[0][] = $data;
                else if($score >= 41  && $score < 61)
                    $all_audit_score[1][] = $data;
                else if($score >= 61  && $score < 81)
                    $all_audit_score[2][] = $data;
                else if($score > 80)
                    $all_audit_score[3][] = $data;           
        }
       
        ksort($all_audit_score);          
        if(array_key_exists(0, $all_audit_score) && $range == 0) 
            $new_ar=$this->array_sort_by_column($all_audit_score[0], 'score');
        else if(array_key_exists(1, $all_audit_score) && $range == 1)
            $new_ar=$this->array_sort_by_column($all_audit_score[1], 'score');
        else if(array_key_exists(2, $all_audit_score) && $range == 2)
            $new_ar=$this->array_sort_by_column($all_audit_score[2], 'score');
        else if(array_key_exists(3, $all_audit_score) && $range == 3)
            $new_ar=$this->array_sort_by_column($all_audit_score[3], 'score');        
        
        if(count($new_ar) > 0) {
            return view('reports.ajax_view.agent_performance_ajax',compact('new_ar'));                   
        } else {
            return "Record not found";
        }                           
    }

    public function agent_performance_report1(Request $request) {
        echo "<pre>";
        print_r($request->all()); die;
        return view('exports.agent_performance_report1');
    }

    public function agent_performance_report_view() {
        if(Auth::user()->hasRole('partner-admin'))
        {
            $all_partners = Partner::where('id',Auth::user()->partner_admin_detail->id)->pluck('name','id');  
        }elseif(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head')||
                Auth::user()->hasRole('partner-quality-head'))
        {
            $all_partners = Partner::where('id',Auth::user()->spoc_detail->partner_id)->pluck('name','id');  
        }elseif(Auth::user()->hasRole('client')){
        	if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) { 
        		$all_partners = Partner::select('name','id')->where('id',32)->get();    
        	} 
            elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                $all_partners = Partner::select('name','id')->where('id',40)->get(); 
            }
            elseif(Auth::user()->id == 139) {
                $all_partners = Partner::select('name','id')->where('id',38)->get(); 
            }
            elseif(Auth::user()->id == 195) {
                $all_partners = Partner::select('name','id')->where('id',39)->get(); 
            }
            elseif(Auth::user()->id == 254) {
                $all_partners = Partner::select('name','id')->where('id',43)->get(); 
            }
            elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $all_partners = Partner::select('name','id')->where('id',41)->get(); 
            }
            elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {
                $all_partners = Partner::select('name','id')->whereIn('id',[38,39,43,45])->get(); 
            }
            elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
            }
            elseif(Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 280) {
                $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',45)->get();
            }
            elseif(Auth::user()->id == 282) {
                $all_partners = Partner::select('name','id')->where('id',39)->orWhere('id',43)->get();
            }
            elseif(Auth::user()->id == 256 || Auth::user()->id == 255) { 
                $all_partners = Partner::select('name','id')->where('id',44)->get();
            }
            elseif(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
            	$all_partners = Partner::select('name','id')->where('client_id',9)->get();
            }
            else {
                if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
        		$all_partners = Partner::select('name','id')->where('client_id',$client_id)->get();    
        	}
            
        }
        if(Auth::user()->id == 172 || Auth::user()->id == 198) {
                $all_partners = Partner::select('name','id')->where('client_id',9)->get();
        } else if(Auth::user()->id == 41){
            $all_partners = Partner::select('name','id')->where('client_id',1)->get();
        }        
        return view('reports.agent_performance_report',compact('all_partners'));
    }

    public function get_agent_report(Request $request) {
        //echo "<pre>"; print_r($request->all());
        $dates = explode(",", $request->date);
        $start_date = date_to_db($dates[0]);
        $end_date = date_to_db($dates[1]);
        $range=$request->range;
        $location_id =$request->location_id;
        $lob=$request->lob;
        if(Auth::user()->hasRole('partner-admin'))        {
           $client_id = Auth::user()->partner_admin_detail->client_id;            
        }elseif(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head')||
                Auth::user()->hasRole('partner-quality-head'))
        {
            $client_id = Auth::user()->spoc_detail->partner->client_id;
        }elseif(Auth::user()->hasRole('client')){
        	if(Auth::user()->id == 42) {
        		$client_id = 9;
        	} else {
        		if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
        	}
            
        } 
        if(Auth::user()->id == 172 || Auth::user()->id == 198) {
            $client_id = 9;
        } else if(Auth::user()->id == 41){
            $client_id = 1;
        }

        if($request->partner_id == 'all') {
                if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {
                    $partner_id=32;
                } 
                else if(Auth::user()->id == 248 || Auth::user()->id == 253) {
                    $partner_id=40;
                }
                elseif(Auth::user()->id == 249 || Auth::user()->id == 250){
                    $partner_id=41;
                }

                elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                    $partner_id=44;
                }

                elseif(Auth::user()->id == 139) {
                    $partner_id=38;
                }

                elseif (Auth::user()->id == 195) {
                   $partner_id=39;
                }

                elseif (Auth::user()->id == 254) {
                   $partner_id=43;
                }
                
                else {
                    $partner_id='%';
                }
                
            } else {
                $partner_id = $request->partner_id;
            }
        

        $audit_data = Audit::where('client_id',$client_id)
           ->where('partner_id','LIKE',$partner_id)
           ->where('process_id',$request->process_id)
           ->whereDate('audit_date',">=",$start_date)
           ->whereDate('audit_date',"<=",$end_date)->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
                $query->where('partner_location_id', 'like', $location_id);
                $query->where('lob', 'like', $lob); 
            })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])
           ->get(); 
        $all_agents = [];        
        foreach ($audit_data as $key => $value) {
            $all_agents[] = $value->raw_data->emp_id;
        }

        $all_unique_agents = array_unique($all_agents);
        $all_audit_score=array();  
        $new_ar=array();    
        foreach ($all_unique_agents as $key => $value) {

           /* $agent_all_audit_score = Audit::where('client_id',$client_id)
           ->where('partner_id',$request->partner_id)
           ->where('process_id',$request->process_id)
           ->whereDate('audit_date',">=",$start_date)
           ->whereDate('audit_date',"<=",$end_date)->whereHas('raw_data', function (Builder $query) use ($value,$location_id,$lob) {
                    $query->where('partner_location_id', 'like', $location_id);
                    $query->where('lob', 'like', $lob);
                    $query->where('emp_id', 'like', $value);     
                })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])->get();*/
            $score=0;
            $scorable=0;
            //$agent_name="";
            $au_Count=0;
            foreach ($audit_data as $key => $value1) {
            if($value1->raw_data->emp_id == $value) {
                    foreach ($value1->audit_parameter_result->where('is_non_scoring','!=',1) as $keyb => $valueb) {
                        if($value1->is_critical == 0) {
                            $score += $valueb->with_fatal_score;
                        }                         
                        $scorable += $valueb->temp_weight;              
                    }
                    $au_Count+=1;
                }                
            }   
                if($scorable != 0) {
                	$final_score=round(($score/$scorable)*100);
                } else {
                	$final_score=0;
                }
                        
                $data=array();                 
                if($final_score >= 0 && $final_score < 41){
                    $data = [   "emp_id" => $value, 
                        "name"=>$value,
                        "audit_count"=>$au_Count,
                        "bucket"=>"0 to 40 %",
                        "score"=>$final_score];
                    $all_audit_score[1][] = $data;
                    $all_audit_score[0][] = $data;
                }
                else if($final_score >= 41  && $final_score < 61){
                     $data = [   "emp_id" => $value, 
                        "name"=>$value,
                        "audit_count"=>$au_Count,
                        "bucket"=>"41 to 60 %",
                        "score"=>$final_score];
                    $all_audit_score[2][] = $data;
                    $all_audit_score[0][] = $data;
                }
                else if($final_score >= 61  && $final_score < 81){
                     $data = [   "emp_id" => $value, 
                        "name"=>$value,
                        "audit_count"=>$au_Count,
                        "bucket"=>"61 to 80 %",
                        "score"=>$final_score];
                    $all_audit_score[3][] = $data;
                    $all_audit_score[0][] = $data;
                }
                else if($final_score > 80){
                     $data = [   "emp_id" => $value, 
                        "name"=>$value,
                        "audit_count"=>$au_Count,
                        "bucket"=>"Greater than 80 %",
                        "score"=>$final_score];
                    $all_audit_score[4][] = $data;
                    $all_audit_score[0][] = $data;
                }                
                    
        }       
        ksort($all_audit_score);          
        if(array_key_exists(0, $all_audit_score) && $range == 0) 
            $new_ar=$this->array_sort_by_column($all_audit_score[0], 'score');
        else if(array_key_exists(1, $all_audit_score) && $range == 1)
            $new_ar=$this->array_sort_by_column($all_audit_score[1], 'score');
        else if(array_key_exists(2, $all_audit_score) && $range == 2)
            $new_ar=$this->array_sort_by_column($all_audit_score[2], 'score');
        else if(array_key_exists(3, $all_audit_score) && $range == 3)
            $new_ar=$this->array_sort_by_column($all_audit_score[3], 'score');   
        else if(array_key_exists(4, $all_audit_score) && $range == 4)
            $new_ar=$this->array_sort_by_column($all_audit_score[4], 'score');        
        
        //echo "<pre>";
        //print_r($new_ar); die;        
         
        if(count($new_ar) > 0) {
            return view('reports.ajax_view.agent_performance_ajax',compact('new_ar'));                   
        } else {
            return "Record not found";
        }        
    }

    public function array_sort_by_column($arr, $col, $dir = SORT_ASC) {
        $sort_col = array();
        foreach ($arr as $key=> $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
        return $arr;
    }

    public function qc_report(Request $request)
    {
        $data = [];
        $partner_id = $request->partner_id;
        $partner_location_id = $request->location_id;
        $client_id = $request->client_id;

        $temp_audit_data = Audit::whereHas('raw_data', function (Builder $query) use ($partner_id,
                                                                                      $partner_location_id,
                                                                                      $client_id) {
                                 $query->where('client_id',$client_id)
                                       ->where('partner_id','LIKE',$partner_id)
                                       ->where('partner_location_id','LIKE',$partner_location_id);
                                 })->where('qm_sheet_id',$request->qm_sheet_id)
                                 ->whereDate('audit_date','>=',date_to_db($request->start_date))
                                 ->whereDate('audit_date','<=',date_to_db($request->end_date))
                                 ->where('qc_status','>',0)->with(['raw_data',
                                                                   'raw_data.location_data',
                                                                   'raw_data.partner_detail',
                                                                   'audit_results',
                                                                   'qa_qtl_detail',
                                                                   'audit_results.reason_type',
                                                                   'audit_results.reason'])->get();
         foreach ($temp_audit_data as $key => $value) {
             $basic['brand'] = $value->raw_data->brand_name;
             $basic['language_2'] = $value->language_2;
             $basic['circle'] = $value->raw_data->circle;
             $basic['call_id'] = $value->raw_data->call_id;
             $basic['auditor'] = $value->auditor->name;
             $basic['customer_phone'] = $value->raw_data->customer_phone;
             $basic['partner'] = $value->partner->name;
             $basic['evaluation_date'] = "-";
             $basic['lob'] = $value->raw_data->lob;
             $basic['call_time'] = $value->raw_data->call_time;
             $basic['call_duration'] = $value->raw_data->call_duration;
             $basic['agent_id'] = $value->raw_data->emp_id;
             $basic['evaluation_score'] = "-";
             $basic['call_sub_type'] = $value->raw_data->call_sub_type;
             $basic['dff_1'] = "-";
             $basic['overall_remark'] = $value->overall_summary;
             $basic['qc_date'] = $value->qc_date;

             $defect_param=' ';
             foreach ($value->qc_defect_sub_parameter as $key_b => $value_b) {
                 $defect_param .= $value_b->qm_sheet_sub_parameter->sub_parameter.',';
             }
             $basic['qc_deffect_parameter'] = $defect_param;
             $basic['qc_deffect_parameter_count'] = $value->qc_defect_sub_parameter->count();
             $basic['variance'] = $value->overall_score - $value->qc_revised_score_without_fatal;
             $basic['qc_remark'] = $value->qc_comment;
             $basic['status'] = return_qc_status($value->qc_status);


             $data[] = $basic;
         }

        return view('reports.qc_report',compact('data'));
    }

    public function monthly_trending_report_view() {
        if(Auth::user()->hasRole('partner-admin'))
        {
            $all_partners = Partner::where('id',Auth::user()->partner_admin_detail->id)->pluck('name','id');  
        }elseif(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head')||
                Auth::user()->hasRole('partner-quality-head'))
        {
            $all_partners = Partner::where('id',Auth::user()->spoc_detail->partner_id)->pluck('name','id');  
        }elseif(Auth::user()->hasRole('client')){
        	if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {
        		$all_partners = Partner::select('name','id')->where('id',32)->get(); 
        	} 
            elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                $all_partners = Partner::select('name','id')->where('id',40)->get(); 
            }
            elseif (Auth::user()->id == 139) {
                $all_partners = Partner::select('name','id')->where('id',38)->get();
            }
            elseif (Auth::user()->id == 195) {
                $all_partners = Partner::select('name','id')->where('id',39)->get();
            }
            elseif (Auth::user()->id == 254) {
                $all_partners = Partner::select('name','id')->where('id',43)->get();
            }
            elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $all_partners = Partner::select('name','id')->where('id',41)->get(); 
            }
            elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {
                $all_partners = Partner::select('name','id')->whereIn('id',[38,39,43,45])->get(); 
            }
            elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
            }
            elseif(Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 280) {
                $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',45)->get();
            }

            elseif(Auth::user()->id == 282) {
                $all_partners = Partner::select('name','id')->where('id',39)->orWhere('id',43)->get();
            }
            elseif(Auth::user()->id == 256 || Auth::user()->id == 255) { 
                $all_partners = Partner::select('name','id')->where('id',44)->get();
            } 
            elseif(Auth::user()->id == 42) {
            	$all_partners = Partner::select('name','id')->where('client_id',9)->get();
            }
            else {
                if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
        		$all_partners = Partner::select('name','id')->where('client_id',$client_id)->get(); 
        	}
               
        }
        if(Auth::user()->id == 172 || Auth::user()->id == 198) {
                $all_partners = Partner::select('name','id')->where('client_id',9)->get();
            }        
        return view('reports.monthly_trending_report',compact('all_partners'));
        	
    }

    public function mtr_data_parameter_wise(Request $request)
    { 
        $last_six_month_list = [];
        $last_six_month_list[] = date('F');
        for ($i = 1; $i <6; $i++) {
            $last_six_month_list[] = date('F', strtotime("-$i month"));
        }
        $last_six_month_list = array_reverse($last_six_month_list,false);
        $temp_date = '01 '.$last_six_month_list[0].' '.date('Y');

        $request_time = $request->audit_cycle;
        $date_array = explode (",", $request_time);
        // $start_day = $date_array[0];
        // $end_day = $date_array[1]; 
        $start_day = date("d",strtotime($date_array[0]));
        $end_day = date("d",strtotime($date_array[1]));

        $start_month=date("F",strtotime($date_array[0]));
        $end_month=date("F",strtotime($date_array[1]));

        if($start_month == $end_month) {
            $end_date = date('Y-m-d');
        } else {
            $end_date = date('Y-m-d',strtotime($date_array[1]));
        }

        $start_date = date('Y-m-d', strtotime($temp_date));
        $end_date; 
        //$end_date = date('Y-m-d');
        if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
        	$client_id=9;
        } else {
        	if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
    	}
        if($request->partner_id == 'all') {
            $partner_id = '%';
        } else {
            $partner_id = $request->partner_id;
        }
        
        $location_id = $request->location_id;
        $process_id = $request->process_id;

        

        $lob=$request->lob;
        $audit_data = Audit::where('client_id',$client_id)
                                       ->where('partner_id','like',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       //->whereDay('audit_date','>=',$start_day)
                                       //->whereDay('audit_date',"<=",$end_day)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                            $query->where('lob', 'like', $lob);
                                        })->withCount(['audit_parameter_result','raw_data','audit_results'])
                                       ->get();
        //dd($audit_data); die;
        //get latest QM Sheet
        $latest_qm_sheet = QmSheet::where('client_id',$client_id)->where('process_id',$process_id)->orderBy('version','desc')->first();
        $latest_qm_sheet_id = $latest_qm_sheet->id;
        $all_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('is_non_scoring','!=',1)->get();
        // month array
        $month_array=[];
        foreach ($last_six_month_list as $key => $value) {
            $month_array[$value]=['scored'=>0,'scorable'=>0,'score_per'=>0,'count'=>0];
        }

        $param_data=[];
        foreach ($all_params as $key => $value) {
            $param_data[$value->id] = ['data'=>$month_array,'name'=>$value->parameter];
        }

        $param_data['Overall With Fatal'] = ['data'=>$month_array,'name'=>'Overall With Fatal'];
        $param_data['Overall Without Fatal'] = ['data'=>$month_array,'name'=>'Overall Without Fatal'];
        $score_array=array();
        foreach ($last_six_month_list as $key => $value) {
            $temp_date = $start_day.$value.' '.date('Y');
            $temp_start_date = date('Y-m-d', strtotime($temp_date));
            $temp_date = $end_day.$value.' '.date('Y');
            if($start_month == $end_month) {
                $temp_end_date = date('Y-m-d', strtotime($temp_date));
            } else {
               $temp_end_date = date('Y-m-d', strtotime("+1 month", strtotime($temp_date)));
            }


            $parent_score = 0;
            $parent_scorable=0; 
            $p_wo_score=0;

            $sorted_audits = $audit_data->where('audit_date','>=',$temp_start_date)->where('audit_date','<=',$temp_end_date); 
            foreach ($sorted_audits as $k2 => $value1) {  
                foreach ($value1->audit_parameter_result->where('is_non_scoring','!=',1) as $keyb => $valueb) {                   
                        $parent_score += $valueb->with_fatal_score;
                        $parent_scorable += $valueb->temp_weight; 
                        $p_wo_score +=$valueb->without_fatal_score;   
                } 
            }          
            if($parent_scorable != 0) {
                $score_array[$value]=round(($parent_score/$parent_scorable)*100);
            } else {
                $score_array[$value]=0;
            } 

                
            //with fatal
            $param_data['Overall With Fatal']['data'][$value]['scored'] = $sorted_audits->sum('with_fatal_score_per');
            $param_data['Overall With Fatal']['data'][$value]['count'] = $sorted_audits->count();
            if($sorted_audits->count())
                $param_data['Overall With Fatal']['data'][$value]['score_per'] = round(($param_data['Overall With Fatal']['data'][$value]['scored']/$sorted_audits->count()));
            else
                $param_data['Overall With Fatal']['data'][$value]['score_per'] = 0;
            //with fatal

            //without fatal
            $param_data['Overall Without Fatal']['data'][$value]['scored'] = $p_wo_score;
            $param_data['Overall Without Fatal']['data'][$value]['count'] = $parent_scorable;
            if($parent_scorable!= 0)
                //$param_data['Overall Without Fatal']['data'][$value]['score_per'] = round(($param_data['Overall Without Fatal']['data'][$value]['scored']/$sorted_audits->count()));
                $param_data['Overall Without Fatal']['data'][$value]['score_per']=round(($p_wo_score/$parent_scorable)*100);
            else
                $param_data['Overall Without Fatal']['data'][$value]['score_per'] = 0;
            //without fatal


            // following code is for fatal score //
            //$sorted_audits = $sorted_audits->where('is_critical',1);
            // fatal score ends//
            //parameter wise
            foreach ($sorted_audits as $keyb => $valueb) {
                foreach ($valueb->audit_parameter_result as $keyc => $valuec) {
                    if(array_key_exists($valuec->parameter_id,$param_data))
                    {
                        $param_data[$valuec->parameter_id]['data'][$value]['scored'] += $valuec->with_fatal_score;
                        $param_data[$valuec->parameter_id]['data'][$value]['count'] += $valuec->temp_weight;
                    }
                }
            }
            //parameter wise
        }    
        
        //settle parameter wise
        foreach ($param_data as $key => $value) {
            foreach ($value['data'] as $keyb => $valueb) {
                if($param_data[$key]['data'][$keyb]['count'])
                    $param_data[$key]['data'][$keyb]['score_per'] = round(($param_data[$key]['data'][$keyb]['scored']/$param_data[$key]['data'][$keyb]['count']) * 100);
                else
                    $param_data[$key]['data'][$keyb]['score_per'] = 0;
            }
        }
        //echo "<pre>"; print_r($param_data); die;
        $final_data = ['month_list'=>$last_six_month_list,'param_data'=>$param_data];                
        return view('reports.ajax_view.mtr_ajax_parameter_wise',compact('final_data'));
    }

    public function mtr_data_sub_parameter_wise(Request $request) 
    {   
         
        $last_six_month_list = [];
        $last_six_month_list[] = date('F');
        for ($i = 1; $i <6; $i++) {
            $last_six_month_list[] = date('F', strtotime("-$i month"));
        }
        $last_six_month_list = array_reverse($last_six_month_list,false);

        if($last_six_month_list[0] > date('m')){
            $year =  date('Y') - 1;
            /* dd(); */
            $temp_date = '01 '.$last_six_month_list[0].' '.$year;
        } else {
            $temp_date = '01 '.$last_six_month_list[0].' '.date('Y');
        }

        $temp_date = '01 '.$last_six_month_list[0].' '.date('Y');
        
        //$start_date = date('Y-m-d', strtotime($temp_date));
        
        $request_time = $request->audit_cycle;
        $date_array = explode (",", $request_time);
        // $start_day = $date_array[0];
        // $end_day = $date_array[1]; 
        $start_day = date("d",strtotime($date_array[0]));
        $end_day = date("d",strtotime($date_array[1]));

        $start_month=date("F",strtotime($date_array[0]));
        $end_month=date("F",strtotime($date_array[1]));

        if($start_month == $end_month) {
            $end_date = date('Y-m-d');
        } else {
            $end_date = date('Y-m-d',strtotime($date_array[1]));
        }

        $start_date = date('Y-m-d', strtotime($temp_date));
        if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
        	$client_id=9;
        } else {
        	if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
        }

        if($request->partner_id == 'all') {
            $partner_id = '%';
        } else {
            $partner_id = $request->partner_id;
        }
        
        
        $location_id = $request->location_id;
        $process_id = $request->process_id;
        
        $lob = $request->lob;
        $audit_data = Audit::where('client_id',$client_id)
                                       ->where('partner_id','like',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                      //->whereDay('audit_date','>=',$start_day)
                                      // ->whereDay('audit_date',"<=",$end_day)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                            $query->where('lob', 'like', $lob);
                                        })->withCount(['audit_results','raw_data'])
                                       ->get();
        //dd($audit_data); die;
        //get latest QM Sheet
        $latest_qm_sheet = QmSheet::where('client_id',$client_id)->where('process_id',$process_id)->orderBy('version','desc')->first();
            $latest_qm_sheet_id = $latest_qm_sheet->id;
        $all_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('is_non_scoring','!=',1)->with('qm_sheet_sub_parameter')->get();
        $parameter_data=array();        
        $overall_with_fatal['scored']=array();
        $overall_without_fatal['scored']=array();
        foreach ($last_six_month_list as $key => $value) {
            $temp_date = $start_day.$value.' '.date('Y');
            $temp_start_date = date('Y-m-d', strtotime($temp_date));
            $temp_date = $end_day.$value.' '.date('Y');
            if($start_month == $end_month) {
                $temp_end_date = date('Y-m-d', strtotime($temp_date));
            } else {
                $temp_end_date = date('Y-m-d', strtotime("+1 month", strtotime($temp_date)));
            } 
            $sorted_audits = $audit_data->where('audit_date','>=',$temp_start_date)->where('audit_date','<=',$temp_end_date); 
            if($sorted_audits->count() != 0) {
                $overall_with_fatal['scored'][$value]=round($sorted_audits->sum('with_fatal_score_per')/$sorted_audits->count());  
            } else {
               $overall_with_fatal['scored'][$value] =0; 
            }
                      
            $score=0;
            $scorable=0; 
            $final_score=0;           
            foreach ($sorted_audits as $key => $value1) {                
                foreach ($value1->audit_results->where('is_non_scoring','=',0) as $keyb => $valueb) {
                    $score += $valueb->score;
                    $scorable += $valueb->after_audit_weight;                                   
                }      
            }
            if($scorable != 0) {
               $final_score=round(($score/$scorable)*100);
            } else {
                $final_score=0;
            }
            $overall_without_fatal['scored'][$value]=$final_score;
        }
        $final_data['overall_with_fatal'] = $overall_with_fatal['scored'];
        $final_data['overall_without_fatal'] = $overall_without_fatal['scored'];   

            $s=0;
            foreach ($all_params as $k1 => $val) {

                $parameter_data[$s]['parameter_name']=$val->parameter;
                $parameter_data[$s]['scored']=array();
                //Parameter Wise Score   
                foreach ($last_six_month_list as $key => $value) {
                    $temp_date = $start_day.$value.' '.date('Y');
                    $temp_start_date = date('Y-m-d', strtotime($temp_date));
                    $temp_date = $end_day.$value.' '.date('Y');
                    
                    if($start_month == $end_month) {
                $temp_end_date = date('Y-m-d', strtotime($temp_date));
            } else {
               $temp_end_date = date('Y-m-d', strtotime("+1 month", strtotime($temp_date)));
            }
                    
                    $parent_score = 0;
                    $parent_scorable=0;         
                    $sorted_audits = $audit_data->where('audit_date','>=',$temp_start_date)->where('audit_date','<=',$temp_end_date); 
                    foreach ($sorted_audits as $k2 => $value1) {  
                        foreach ($value1->audit_parameter_result->where('is_non_scoring','!=',1) as $keyb => $valueb) {
                            if($valueb->parameter_id == $val->id) {
                                $parent_score += $valueb->with_fatal_score;
                                $parent_scorable += $valueb->temp_weight;                                                          
                            }     
                        } 
                    }               

                    if($parent_scorable != 0) {
                        $parameter_data[$s]['scored'][$value]=round(($parent_score/$parent_scorable)*100);   
                    } else {
                        $parameter_data[$s]['scored'][$value]=0; 
                    }
                }
                $parameter_data[$s]['sub_parameter_detail']=array();              
                              
                foreach($val->qm_sheet_sub_parameter as $k3 => $sub_param){ 
                                  
                    $sub_parameter=array();
                    $sub_parameter['name']=$sub_param->sub_parameter;
                    $sub_parameter['fatal']=$sub_param->critical;
                    $sub_parameter['scored']=array();   
                    // Sub Parameter Wise Score.                            
                    foreach ($last_six_month_list as $key => $value) {
                        
                        $temp_date = $start_day.$value.' '.date('Y');
                        $temp_start_date = date('Y-m-d', strtotime($temp_date));
                        $temp_date = $end_day.$value.' '.date('Y');
                        
                        if($start_month == $end_month) {
                $temp_end_date = date('Y-m-d', strtotime($temp_date));
            } else {
               $temp_end_date = date('Y-m-d', strtotime("+1 month", strtotime($temp_date)));
            }
                               
                        $sorted_audits = $audit_data->where('audit_date','>=',$temp_start_date)->where('audit_date','<=',$temp_end_date);
                        $sp_score=0;
                        $sp_scorable=0;
                        foreach ($sorted_audits as $k2 => $value1) {  
                            foreach ($value1->audit_results->where('is_non_scoring','=',0) as $keyb => $valueb) {
                                if($valueb->parameter_id == $val->id) {
                                   // $parent_score += $valueb->score;
                                    //$parent_scorable += $valueb->after_audit_weight;
                                    if($valueb->sub_parameter_id == $sub_param->id) {
                                        $sp_score += $valueb->score;
                                        $sp_scorable += $valueb->after_audit_weight;
                                    }                           
                                }     
                            } 
                        }
                        if($sp_scorable != 0) {
                           $sub_parameter['scored'][$value]=round(($sp_score/$sp_scorable)*100);   
                        } else {
                           $sub_parameter['scored'][$value]=0; 
                        }
                                                                                             
                    }                                       
                    $parameter_data[$s]['sub_parameter_detail'][]=$sub_parameter; 
                }                    
                $s++;
            }  
        $final_data['month_list']=$last_six_month_list;
        $final_data['param_data']=$parameter_data;
        // echo "<pre>";
        // print_r($final_data['param_data']); die; 
        return view('reports.ajax_view.mtr_ajax_sub_parameter_wise',compact('final_data'));           
    }

    public function mtr_data_agent_wise(Request $request) {
         
        $last_six_month_list = [];
        $last_six_month_list[] = date('F');
        for ($i = 1; $i <6; $i++) {
            $last_six_month_list[] = date('F', strtotime("-$i month"));
        }
        $last_six_month_list = array_reverse($last_six_month_list,false);

        if($last_six_month_list[0] > date('m')){
            $year =  date('Y') - 1;
            /* dd(); */
            $temp_date = '01 '.$last_six_month_list[0].' '.$year;
        } else {
            $temp_date = '01 '.$last_six_month_list[0].' '.date('Y');
        }

        $temp_date = '01 '.$last_six_month_list[0].' '.date('Y');
        
        //$start_date = date('Y-m-d', strtotime($temp_date));
        
        $request_time = $request->audit_cycle;
        $date_array = explode (",", $request_time);
        // $start_day = $date_array[0];
        // $end_day = $date_array[1]; 
        $start_day = date("d",strtotime($date_array[0]));
        $end_day = date("d",strtotime($date_array[1]));

        $start_month=date("F",strtotime($date_array[0]));
        $end_month=date("F",strtotime($date_array[1]));

        if($start_month == $end_month) {
            $end_date = date('Y-m-d');
        } else {
            $end_date = date('Y-m-d',strtotime($date_array[1]));
        }

        $start_date = date('Y-m-d', strtotime($temp_date));
       	if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
       		$client_id=9;
       	} else {
       		if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
       	}
        
        if($request->partner_id == 'all') {
            $partner_id = '%';
        } else {
            $partner_id = $request->partner_id;
        }
        $location_id = $request->location_id;
        $process_id = $request->process_id;   

        
        $lob = $request->lob;
        $audit_data = Audit::where('client_id',$client_id)
                                       ->where('partner_id','like',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       //->whereDay('audit_date','>=',$start_day)
                                      // ->whereDay('audit_date',"<=",$end_day)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                            $query->where('lob', 'like', $lob);
                                        })->withCount(['audit_results','raw_data'])
                                       ->get();
        //dd($audit_data); die;
        //get latest QM Sheet
        $latest_qm_sheet = QmSheet::where('client_id',$client_id)->where('process_id',$process_id)->orderBy('version','desc')->first();
            $latest_qm_sheet_id = $latest_qm_sheet->id;
        $all_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('is_non_scoring','!=',1)->get();
        $temp_all_unique_agents=[];
        foreach ($audit_data as $key => $value) {
            // get all unique agents
            $temp_all_unique_agents[] = $value->raw_data->emp_id;
        }                      
        $all_unique_agents = array_unique($temp_all_unique_agents);        
        $params=array();
        $all_score=array();        
        foreach($last_six_month_list as $month) { 
            $all_score[$month]['score']=0;
            $all_score[$month]['scorable']=0;
            $all_score[$month]['with_fatal']=0;
            $all_score[$month]['fatal_count']=0;
        }       
        foreach ($all_unique_agents as $key => $value) {
            $params[$key]['scored']=array();
            foreach($last_six_month_list as $month) {
                $temp_date = $start_day.$month.' '.date('Y');
                $temp_start_date = date('Y-m-d', strtotime($temp_date));
                $temp_date = $end_day.$month.' '.date('Y');
                
                if($start_month == $end_month) {
                $temp_end_date = date('Y-m-d', strtotime($temp_date));
            } else {
               $temp_end_date = date('Y-m-d', strtotime("+1 month", strtotime($temp_date)));
            }
                $sorted_audits = Audit::where('client_id',$client_id)
                                       ->where('partner_id','like',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$temp_start_date)
                                       ->whereDate('audit_date',"<=",$temp_end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($value,$location_id) {
                                            $query->where('emp_id', 'like', $value);
                                            $query->where('partner_location_id', 'like', $location_id);  
                                        })->withCount(['audit_results','raw_data','audit_parameter_result'])->get();
                $scored=0;
                $scorable=0;

                // following code is for fatal score //
                //$sorted_audits = $sorted_audits->where('is_critical',1);
                // fatal score ends//

                foreach($sorted_audits as $keya => $audit) {
                    foreach ($audit->audit_parameter_result->where('is_non_scoring','!=',1) as $keyb => $valueb) {
                    	if($audit->is_critical == 0) { 
                    		$scored += $valueb->with_fatal_score;
                    	}                        
                        $scorable += $valueb->temp_weight;                                   
                    } 
                    //$scored+=$audit->with_fatal_score_per;

                }
                $all_score[$month]['score'] += $scored;
                $all_score[$month]['scorable'] += $scorable;
                $all_score[$month]['with_fatal'] += $sorted_audits->sum('with_fatal_score_per');
                $all_score[$month]['fatal_count'] += $sorted_audits->count();
                if($scorable != 0) {
                    $params[$key]['scored'][$month]=round(($scored/$scorable)*100);
                } else {
                    $params[$key]['scored'][$month]=0;
                }
            }           
        }
        $overall_with_fatal=array();
        $overall_without_fatal=array();
        foreach($last_six_month_list as $month) {
            if($all_score[$month]['scorable'] != 0) {
                $overall_without_fatal[$month]=round(($all_score[$month]['score']/$all_score[$month]['scorable'])*100); 
            }else {
                $overall_without_fatal[$month]=0; 
            }

            if($all_score[$month]['fatal_count'] != 0) {
                $overall_with_fatal[$month]=round((round($all_score[$month]['with_fatal'])/$all_score[$month]['fatal_count'])); 
            }else {
                $overall_with_fatal[$month]=0; 
            }    
        }   
        $final_data['month_list']=$last_six_month_list;
        $final_data['param_data']=$params;
        $final_data['unique_agents']=$all_unique_agents;
        $final_data['overall_with_fatal'] = $overall_with_fatal;
        $final_data['overall_without_fatal'] = $overall_without_fatal;
        return view('reports.ajax_view.mtr_ajax_agent_wise',compact('final_data','audit_data'));           
    }


    public function new_agent_compliance(Request $request) {
        if(Auth::user()->hasRole('partner-admin'))
        {    
            if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) { 
                $all_partners = Partner::select('name','id')->where('id',32)->get(); 
            } 
            elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                $all_partners = Partner::select('name','id')->where('id',40)->get(); 
            }
            elseif (Auth::user()->id == 139) {
                $all_partners = Partner::select('name','id')->where('id',38)->get(); 
            }
            elseif (Auth::user()->id == 195) {
                $all_partners = Partner::select('name','id')->where('id',39)->get(); 
            }
            elseif (Auth::user()->id == 254) {
                $all_partners = Partner::select('name','id')->where('id',43)->get(); 
            }
            elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $all_partners = Partner::select('name','id')->where('id',41)->get(); 
            }
            elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {
                $all_partners = Partner::select('name','id')->whereIn('id',[38,39,43,45])->get(); 
            }
            elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
            }
            elseif(Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 280) {
                $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',45)->get();
            }

            elseif(Auth::user()->id == 282) {
                $all_partners = Partner::select('name','id')->where('id',39)->orWhere('id',43)->get();
            }
            elseif(Auth::user()->id == 256 || Auth::user()->id == 255) { 
                $all_partners = Partner::select('name','id')->where('id',44)->get();
            } 
            elseif(Auth::user()->id == 307 || Auth::user()->id == 308) { 
                $all_partners = Partner::select('name','id')->where('id',48)->get();
            } 
            else {
              $all_partners = Partner::select('name','id')->where('id',Auth::user()->partner_admin_detail->id)->get();   
            }
             
        }elseif(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head'))
                
        {
            $all_partners = Partner::where('id',Auth::user()->spoc_detail->partner_id)->pluck('name','id');  
        }elseif( Auth::user()->hasRole('partner-quality-head') ){



            $partner = PartnersProcessSpoc::where('user_id',Auth::user()->id)->get();  

            $partnerid =  $partner[0]['partner_id'];    

            $all_partners = Partner::where('id',$partnerid)->get(); 

           

        }elseif(Auth::user()->hasRole('client')){
        	if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) { 
        		$all_partners = Partner::select('name','id')->where('id',32)->get(); 
        	} 
            elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                $all_partners = Partner::select('name','id')->where('id',40)->get(); 
            }
            elseif (Auth::user()->id == 139) {
                $all_partners = Partner::select('name','id')->where('id',38)->get(); 
            }
            elseif (Auth::user()->id == 195) {
                $all_partners = Partner::select('name','id')->where('id',39)->get(); 
            }
            elseif (Auth::user()->id == 254) {
                $all_partners = Partner::select('name','id')->where('id',43)->get(); 
            }
            elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $all_partners = Partner::select('name','id')->where('id',41)->get(); 
            }
            elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {
                $all_partners = Partner::select('name','id')->whereIn('id',[38,39,43,45])->get(); 
            }
            elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
            }
            elseif(Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 280) {
                $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',45)->get();
            }
            elseif(Auth::user()->id == 282) {
                $all_partners = Partner::select('name','id')->where('id',39)->orWhere('id',43)->get();
            }
            elseif(Auth::user()->id == 256 || Auth::user()->id == 255) { 
                $all_partners = Partner::select('name','id')->where('id',44)->get();
            }
            elseif(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
            	$all_partners = Partner::select('name','id')->where('client_id',9)->get();
            }
            else {
                if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
        		$all_partners = Partner::select('name','id')->where('client_id',$client_id)->get(); 
        	}
               
        }
        if(Auth::user()->id == 253) {
            $all_partners = Partner::select('name','id')->where('id',40)->get(); 
        }
        if(Auth::user()->id == 139) {
            $all_partners = Partner::select('name','id')->where('id',38)->get(); 
        }
        if(Auth::user()->id == 195) {
            $all_partners = Partner::select('name','id')->where('id',39)->get(); 
        }
        if(Auth::user()->id == 254) {
            $all_partners = Partner::select('name','id')->where('id',43)->get(); 
        }
        if(Auth::user()->id == 250) {
            $all_partners = Partner::select('name','id')->where('id',41)->get(); 
        }
        if(Auth::user()->id == 255) {
           $all_partners = Partner::select('name','id')->where('id',44)->get();  
        }
        if(Auth::user()->id == 246) {
            $all_partners = Partner::select('name','id')->where('id',32)->get(); 
        }
        if(Auth::user()->id == 172 || Auth::user()->id == 198) {
                $all_partners = Partner::select('name','id')->where('client_id',9)->get();
        }
        if(Auth::user()->id == 41 ) {
            $all_partners = Partner::select('name','id')->where('client_id',1)->get();
        }
        $data=0;        
        return view('reports.new_agent_compliance',compact('all_partners','data'));
    }

    public function new_parameter_compliance(Request $request) {
        if(Auth::user()->hasRole('partner-admin'))
        {   
            if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) { 
                $all_partners = Partner::select('name','id')->where('id',32)->get();
            } 
            elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                $all_partners = Partner::select('name','id')->where('id',40)->get();
            }
            elseif (Auth::user()->id == 139) {
                $all_partners = Partner::select('name','id')->where('id',38)->get();
            }
            elseif (Auth::user()->id == 195) {
                $all_partners = Partner::select('name','id')->where('id',39)->get();
            }
            elseif (Auth::user()->id == 254) {
                $all_partners = Partner::select('name','id')->where('id',43)->get();
            }
            elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $all_partners = Partner::select('name','id')->where('id',41)->get();
            }
            elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
            }
            elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {
                $all_partners = Partner::select('name','id')->whereIn('id',[38,39,43,45])->get();
            }
            elseif(Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 280) {
                $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',45)->get();
            }
            elseif(Auth::user()->id == 282) {
                $all_partners = Partner::select('name','id')->where('id',39)->orWhere('id',43)->get();
            }
            elseif(Auth::user()->id == 256) { 
                $all_partners = Partner::select('name','id')->where('id',44)->get();
            }  
            elseif(Auth::user()->id == 274) { 
                $all_partners = Partner::select('name','id')->where('id',46)->get();
            }    
            elseif(Auth::user()->id == 307 || Auth::user()->id == 308) { 
                $all_partners = Partner::select('name','id')->where('id',48)->get();
            }            
            else {
              $all_partners = Partner::select('name','id')->where('id',Auth::user()->partner_admin_detail->id)->get();
            }
              
        }elseif(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head'))
               
        {
            $all_partners = Partner::where('id',Auth::user()->spoc_detail->partner_id)->pluck('name','id');  
        }
        elseif( Auth::user()->hasRole('partner-quality-head') ){



            $partner = PartnersProcessSpoc::where('user_id',Auth::user()->id)->get();  

            $partnerid =  $partner[0]['partner_id'];    

            $all_partners = Partner::where('id',$partnerid)->get(); 

           

        }
        elseif(Auth::user()->hasRole('client')){
        	if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) { 
        		$all_partners = Partner::select('name','id')->where('id',32)->get();
        	} 
            elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                $all_partners = Partner::select('name','id')->where('id',40)->get();
            }
            elseif (Auth::user()->id == 139) {
                $all_partners = Partner::select('name','id')->where('id',38)->get();
            }
            elseif (Auth::user()->id == 195) {
                $all_partners = Partner::select('name','id')->where('id',39)->get();
            }
            elseif (Auth::user()->id == 254) {
                $all_partners = Partner::select('name','id')->where('id',43)->get();
            }
            elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $all_partners = Partner::select('name','id')->where('id',41)->get();
            }
            elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
            }
            elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {
                $all_partners = Partner::select('name','id')->whereIn('id',[38,39,43,45])->get();
            }
            elseif(Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 280) {
                $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',45)->get();
            }
            elseif(Auth::user()->id == 282) {
                $all_partners = Partner::select('name','id')->where('id',39)->orWhere('id',43)->get();
            }
            elseif(Auth::user()->id == 256) { 
                $all_partners = Partner::select('name','id')->where('id',44)->get();
            }
            elseif(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
            	$all_partners = Partner::select('name','id')->where('client_id',9)->get();
            }
            else {
                if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
        		$all_partners = Partner::select('name','id')->where('client_id',$client_id)->get();
        	}
                
        }
        if(Auth::user()->id == 253) {
            $all_partners = Partner::select('name','id')->where('id',40)->get();
        }
        if (Auth::user()->id == 139) {
            $all_partners = Partner::select('name','id')->where('id',38)->get();
        }
        if (Auth::user()->id == 195) {
            $all_partners = Partner::select('name','id')->where('id',39)->get();
        }
        if (Auth::user()->id == 254) {
            $all_partners = Partner::select('name','id')->where('id',43)->get();
        }
        if(Auth::user()->id == 250) {
            $all_partners = Partner::select('name','id')->where('id',41)->get();
        }
        if(Auth::user()->id == 255) {
           $all_partners = Partner::select('name','id')->where('id',44)->get();  
        }
        
        if(Auth::user()->id == 246) {
            $all_partners = Partner::select('name','id')->where('id',32)->get(); 
        }
        if(Auth::user()->id == 172 || Auth::user()->id == 198) {
                $all_partners = Partner::select('name','id')->where('client_id',9)->get();
        }
         /*echo $all_partners;
            dd();*/
        $data=0;        
        return view('reports.new_parameter_compliance',compact('all_partners','data'));
    }
        
    

    public function new_parameter_compliance_report(Request $request) {

        $dates = explode(",", $request->date);

        $start_date = date_to_db($dates[0]);
        $end_date = date_to_db($dates[1]);

        if( Auth::user()->hasRole('partner-quality-head') ){          
            
            $partner = PartnersProcessSpoc::where('user_id',Auth::user()->id)->get();  
            if(Auth::user()->id == 307 || Auth::user()->id == 308) {
                $partnerid =  48;   
            } else {
                $partnerid =  $partner[0]['partner_id'];  
            }
            //$partnerid =  $partner[0]['partner_id'];    

            $client_id1 = Partner::where('id',$partnerid)->first(); 

            
            $client_id = $client_id1->client_id;

             
        }
        else {
            if(Auth::user()->id == 253 || Auth::user()->id == 250 || Auth::user()->id == 252 ||
                Auth::user()->id == 269 || Auth::user()->id == 255 || Auth::user()->id == 246 || Auth::user()->id == 139 || Auth::user()->id == 195 || Auth::user()->id == 254 || Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198 || Auth::user()->id == 270 || Auth::user()->id == 271 || 
                Auth::user()->id == 279 || Auth::user()->id == 283 || Auth::user()->id == 274) {
                $client_id=9;
            } else {
                if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
            }
            
        }

     //   $client_id = Auth::user()->client_detail->client_id;


        if($request->partner == 'all') {
            $partner_id = '%';

        } else {
            $partner_id = $request->partner;
        }
        
        $location_id = $request->location;
        $process_id = $request->process;
        $lob = $request->lob;
        // starts rebuttal 
          $rebuttal_data = [];

          $audit_data = Audit::where('client_id',$client_id)
                                       ->where('partner_id','like',$partner_id)
                                       ->where('process_id',$process_id)
                                       
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                            $query->where('lob', 'like', $lob);         
                                        })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])
                                       ->get();                                       
            //echo $audit_data; die;
           //get latest QM Sheet
            $latest_qm_sheet = QmSheet::where('client_id',$client_id)->where('process_id',$process_id)->orderBy('version','desc')->first();
            $latest_qm_sheet_id = $latest_qm_sheet->id;

            $all_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('is_non_scoring',0)->with('qm_sheet_sub_parameter')->get();
            
            $temp_all_unique_despos=[];
            foreach ($audit_data as $key => $value) {
                // get all unique despositions
                $temp_all_unique_despos[] = $value->raw_data->disposition;
            }
           
            $all_unique_despos = array_unique($temp_all_unique_despos);
            $all_unique_despos_count = array_count_values($temp_all_unique_despos);  

            $all_unique_despos_counts = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_unique_despos_score_total = array_map(function($val) { return 0; }, $all_unique_despos);
            $all_unique_despos_score = array_map(function($val) { return 0; }, $all_unique_despos);
            foreach ($audit_data as $key => $value) {
                if($temp_id = array_search($value->raw_data->disposition,$all_unique_despos,true))
                {   
                    $all_unique_despos_counts[$temp_id] += 1;
                    $all_unique_despos_score_total[$temp_id] += $value->overall_score;
                }
            }
            foreach ($all_unique_despos_counts as $key => $value) {
                if($all_unique_despos_counts[$key])
                    $all_unique_despos_score[$key] = round(($all_unique_despos_score_total[$key]/$all_unique_despos_counts[$key]));
                else
                    $all_unique_despos_score[$key] = 0;
            }  
           
            foreach ($all_params as $key => $value) {
                $temp_param_data[$value->id] = [];
                $temp_param_data[$value->id]['name'] = $value->parameter;
                $temp_param_data[$value->id]['data']=[];
                $temp_subp_data=[];
                $temp_sum=[];
                $temp_tot_score=[];
                $temp_tot_scorable=[];
                foreach ($value->qm_sheet_sub_parameter as $keyb => $valueb) {
                    $temp_subp_data[$valueb->id] = [];
                    $temp_subp_data[$valueb->id]['name'] = $valueb->sub_parameter;
                    $temp_subp_data[$valueb->id]['fatal'] = $valueb->critical;
                    $temp_data=[];
                    foreach ($all_unique_despos as $keyc => $valuec) {
                        $temp_data[$valuec]['scored'] = 0;
                        $temp_data[$valuec]['scorable'] = 0;
                        $temp_data[$valuec]['score']=0;
                        $temp_data[$valuec]['result_id']=[];
                        $temp_data[$valuec]['fatal_count']=0;                        
                        $temp_sum[$valuec] = 0;
                        $temp_tot_score[$valuec] = 0;
                        $temp_tot_scorable[$valuec] = 0;
                    }
                    $temp_data['total'] = 0;
                    $temp_sum['total'] = 0;
                    $temp_tot_score['total']=0;
                    $temp_tot_scorable['total']=0;
                    $temp_subp_data[$valueb->id]['data'] = $temp_data;
                }
                $temp_param_data[$value->id]['data'] = $temp_subp_data;
                $temp_param_data[$value->id]['sum'] = $temp_sum;
                $temp_param_data[$value->id]['temp_tot_score'] = $temp_tot_score;
                $temp_param_data[$value->id]['temp_tot_scorable'] = $temp_tot_scorable;

            }

           
            
            foreach ($audit_data as $key => $value) {
                
                foreach ($value->audit_results->where('is_non_scoring',0) as $keyb => $valueb) {
                    $temp_param_data[$valueb->parameter_id]['data'][$valueb->sub_parameter_id]['data'][$value->raw_data->disposition]['scored'] += $valueb->score;
                    $temp_param_data[$valueb->parameter_id]['data'][$valueb->sub_parameter_id]['data'][$value->raw_data->disposition]['scorable'] += $valueb->after_audit_weight;
                    $temp_param_data[$valueb->parameter_id]['data'][$valueb->sub_parameter_id]['data'][$value->raw_data->disposition]['result_id'][] = $valueb->id;
                    
                    if($valueb->is_critical)
                    {
                        $temp_param_data[$valueb->parameter_id]['data'][$valueb->sub_parameter_id]['data'][$value->raw_data->disposition]['fatal_count'] += 1; 
                    }                                   
                   
                }

            }
           
            // set scores
            $dispostion=[];            
            foreach ($temp_param_data as $key => $value) {
                $p_score=0;
                $p_scorable=0;
                foreach ($value['data'] as $keyb => $valueb) {
                        $score_total = 0;

                        foreach ($all_unique_despos_count as $keyc => $valuec) {
                            if($temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'] != 0)
                            $score = round(($temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scored']/$temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'])*100);
                            else
                                $score = 0;

                            $temp_param_data[$key]['data'][$keyb]['data'][$keyc]['score'] = $score;
                            $temp_param_data[$key]['data'][$keyb]['data']['total'] += $score;
                            $p_score+=$temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scored'];
                            $p_scorable+=$temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'];

                            $temp_param_data[$key]['sum'][$keyc] += $score;
                            $temp_param_data[$key]['temp_tot_score'][$keyc] += $temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scored'];
                            $temp_param_data[$key]['temp_tot_scorable'][$keyc] += $temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'];
                        }

                        $temp_param_data[$key]['data'][$keyb]['data']['total'] =(count($all_unique_despos_count) != 0) ? round(($temp_param_data[$key]['data'][$keyb]['data']['total']/count($all_unique_despos_count))) : 0;

                        $temp_param_data[$key]['sum']['total'] += $temp_param_data[$key]['data'][$keyb]['data']['total'];
                }
                $p_scr=0;
                $p_sco=0;
                foreach ($all_unique_despos_count as $keyd => $valued) {
                	$scr=0;
	            	$sca=0;
	            	foreach ($audit_data as $ke => $val) {
	            	            	
		                foreach ($val->audit_parameter_result->where('is_non_scoring',0) as $ke_1 => $valb) {
		                	if($val->raw_data->disposition == $keyd && $valb->parameter_id == $key){
		                		$scr+=$valb->with_fatal_score;
		                		$sca+=$valb->temp_weight;
		                		$p_scr+=$valb->with_fatal_score;
                				$p_sco+=$valb->temp_weight;
		                	}
		                }
	             	}

	             	if($sca != 0) {
                        $temp_param_data[$key]['sum'][$keyd] = round(($scr/$sca)*100);                                                      
                    } else {
                        $temp_param_data[$key]['sum'][$keyd] =0;
                    }
                    /*if($temp_param_data[$key]['temp_tot_scorable'][$keyd] != 0) {
                        $temp_param_data[$key]['sum'][$keyd] = round(($temp_param_data[$key]['temp_tot_score'][$keyd]/$temp_param_data[$key]['temp_tot_scorable'][$keyd])*100);                                                      
                    } else {
                        $temp_param_data[$key]['sum'][$keyd] =0;
                    }*/
                }
                if($p_sco != 0) {
                	$temp_param_data[$key]['sum']['total']=round(($p_scr/$p_sco)*100);
                } else {
                	$temp_param_data[$key]['sum']['total']=0;
                }
                /*if($p_scorable!=0) {
                    $temp_param_data[$key]['sum']['total']=round(($p_score/$p_scorable)*100);
                } else {
                    $temp_param_data[$key]['sum']['total']=0;
                }*/
                //$temp_param_data[$key]['sum']['total'] = round(($temp_param_data[$key]['sum']['total']/count($value['data'])));
        }
        $score=[];
        $score['scored']=[];
        $score['scorable']=[];
        foreach ($all_unique_despos_count as $keyc => $valuec) {
            $score['scored'][$keyc]=0;
            $score['scorable'][$keyc]=0;
        }
        foreach($temp_param_data as $key => $value) {
            foreach ($value['data'] as $keyb => $valueb) { 
               foreach ($all_unique_despos_count as $keyc => $valuec) {
                  $score['scored'][$keyc] +=$temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scored'];
                  $score['scorable'][$keyc] +=$temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'];
               }
            }
        }
        //echo "<pre>";
        //print_r($score); die;
        $all_scores=array();
        $all_audit_counts=array();
        $all_na_counts=array();
        foreach ($all_unique_despos_count as $keyc => $valuec) {

        	$scr=0;
            	$sca=0;
            	foreach ($audit_data as $key => $value) {
            	//p_id=$valueb->parameter_id;            	
	                foreach ($value->audit_parameter_result->where('is_non_scoring','!=',1) as $keyb => $valueb) {
	                	if($value->raw_data->disposition == $keyc){
	                		if($value->is_critical == 0) {
		                        $scr += $valueb->with_fatal_score;
		                    }  
	                		//$sca+=$valueb->temp_weight;
                            $sca+=$valueb->temp_weight;
	                	}
	                }
             	}
            if($sca != 0){
                	$all_scores[$keyc]=round(($scr/$sca)*100);
                } else {
                     $all_scores[$keyc]=0;
                }
            $all_audit_counts[$keyc]=Audit::where('client_id',$client_id)
                                       ->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$keyc) {
                                            $query->where('partner_location_id', 'like', $location_id);  
                                            $query->where('disposition', 'like', $keyc);                              
                                        })->get()->count(); 
            $all_na_counts[$keyc]=Audit::where('client_id',$client_id)
                                       ->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->where('case_id','like',"NA%")
                                       
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$keyc) {
                                            $query->where('partner_location_id', 'like', $location_id);  
                                            $query->where('disposition', 'like', $keyc);                              
                                        })->get()->count();            
        }

        $fatal_audit_score_sum=0;
        $scr=0;
        $sca=0;
            foreach ($audit_data as $key => $value) {   
                foreach ($value->audit_parameter_result->where('is_non_scoring','!=',1) as $keyb => $valueb) {
                        
                        if($value->is_critical == 0) {
                            $scr += $valueb->with_fatal_score;
                        }  
                            //$sca+=$valueb->temp_weight;
                        $sca+=$valueb->temp_weight;    
                    }               
            }
            if($sca != 0) {
                $fatal_audit_score_sum=round(($scr/$sca)*100);
            }
        
        // $final_data['data'] = $temp_param_data;
        $final_data['audit_count'] = array_values($all_audit_counts);
        $final_data['audit_score'] = array_values($all_scores); 
        $final_data['na_counts'] = array_values($all_na_counts);
        $divideby=round(count($all_unique_despos) * 100);
        //$final_data['audit_score_total']=($audit_data->count() != 0) ? round(($fatal_audit_score_sum/$audit_data->count())) : 0; 
        $final_data['audit_score_total']=$fatal_audit_score_sum;
        $final_data['audit_count_total']=round(array_sum($final_data['audit_count']));
        $final_data['data'] = $temp_param_data;
        $final_data['despositions'] = $all_unique_despos;

        // echo "<pre>";
        // print_r($final_data['data'][151]['data']['357']); die;

        $data=1;

        if(Auth::user()->hasRole('partner-admin'))
        {
            if(Auth::user()->id == 139) {
                $all_partners = Partner::where('id',38)->pluck('name','id');
            }
            elseif(Auth::user()->id == 195) {
                $all_partners = Partner::where('id',39)->pluck('name','id');
            }
            elseif(Auth::user()->id == 307 || Auth::user()->id == 308) {
                $all_partners = Partner::where('id',48)->pluck('name','id');
            }
             else {
                $all_partners = Partner::select('name','id')->where('id',Auth::user()->partner_admin_detail->id)->get(); 
            }
             
        }elseif(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head')
                )
        {
            $all_partners = Partner::where('id',Auth::user()->spoc_detail->partner_id)->pluck('name','id');  
        }elseif( Auth::user()->hasRole('partner-quality-head') ){



            $partner = PartnersProcessSpoc::where('user_id',Auth::user()->id)->get();  

            $partnerid =  $partner[0]['partner_id'];    

            $all_partners = Partner::where('id',$partnerid)->get(); 

           

        }
        
        elseif(Auth::user()->hasRole('client')){
            if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {
                $all_partners = Partner::select('name','id')->where('id',32)->get();
            } 
            elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                 $all_partners = Partner::select('name','id')->where('id',40)->get();
            } 
            elseif (Auth::user()->id == 139) {
                $all_partners = Partner::select('name','id')->where('id',38)->get();
            } 
            elseif (Auth::user()->id == 195) {
                $all_partners = Partner::select('name','id')->where('id',39)->get();
            }
            elseif (Auth::user()->id == 254) {
                $all_partners = Partner::select('name','id')->where('id',43)->get();
            }
            elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $all_partners = Partner::select('name','id')->where('id',41)->get();
            }
            elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
            }
            elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {
                $all_partners = Partner::select('name','id')->whereIn('id',[38,39,43,45])->get();
            }
            elseif (Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 280) {
                $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',45)->get();
            }
            elseif (Auth::user()->id == 282) {
                $all_partners = Partner::select('name','id')->where('id',39)->orWhere('id',43)->get();
            }
            elseif(Auth::user()->id == 256) { 
                $all_partners = Partner::select('name','id')->where('id',44)->get();
            }
            elseif(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
            	$all_partners = Partner::select('name','id')->where('client_id',9)->get();
            }
            else {
                if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
                $all_partners = Partner::select('name','id')->where('client_id',$client_id)->get();
            }
                
        }
        if(Auth::user()->id == 253) {
            $all_partners = Partner::select('name','id')->where('id',40)->get();
        }
        if(Auth::user()->id == 250) {
            $all_partners = Partner::select('name','id')->where('id',41)->get();
        }
        if(Auth::user()->id == 139) {
            $all_partners = Partner::select('name','id')->where('id',38)->get();
        }
        if(Auth::user()->id == 195) {
            $all_partners = Partner::select('name','id')->where('id',39)->get();
        }
        if(Auth::user()->id == 254) {
            $all_partners = Partner::select('name','id')->where('id',43)->get();
        }
        if(Auth::user()->id == 255) {
           $all_partners = Partner::select('name','id')->where('id',44)->get();  
        }
        if(Auth::user()->id == 246) {
                $all_partners = Partner::select('name','id')->where('id',32)->get();
            } 
        if(Auth::user()->id == 172 || Auth::user()->id == 198) {
                $all_partners = Partner::select('name','id')->where('client_id',9)->get();
        }
        if(Auth::user()->id == 307 || Auth::user()->id == 308) {
            $all_partners = Partner::where('id',48)->get();
        }
        return view('reports.new_parameter_compliance',compact('final_data','data','all_partners'));
    }  

    public function rebuttal_summary_view(Request $request) {
        if(Auth::user()->hasRole('partner-admin'))
        {
            $all_partners = Partner::where('id',Auth::user()->partner_admin_detail->id)->pluck('name','id');  
        }elseif(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head')||
                Auth::user()->hasRole('partner-quality-head'))
        {
            $all_partners = Partner::where('id',Auth::user()->spoc_detail->partner_id)->pluck('name','id');  
        }elseif(Auth::user()->hasRole('client')){
            if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) { 
                $all_partners = Partner::select('name','id')->where('id',32)->get();
            } 
            elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                $all_partners = Partner::select('name','id')->where('id',40)->get();
            }
            elseif (Auth::user()->id == 139) {
                $all_partners = Partner::select('name','id')->where('id',38)->get();
            }
            elseif (Auth::user()->id == 195) {
                $all_partners = Partner::select('name','id')->where('id',39)->get();
            }
            elseif (Auth::user()->id == 254) {
                $all_partners = Partner::select('name','id')->where('id',43)->get();
            }
            elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $all_partners = Partner::select('name','id')->where('id',41)->get();
            }
            elseif(Auth::user()->id == 279 || Auth::user()->id == 283) {
                $all_partners = Partner::select('name','id')->whereIn('id',[38,45,43,39])->get();
            }
            elseif(Auth::user()->id == 252 || Auth::user()->id == 269) {
                $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
            }
            elseif(Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 280) {
                $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',45)->get();
            }
            elseif(Auth::user()->id == 282) {
                $all_partners = Partner::select('name','id')->where('id',39)->orWhere('id',43)->get();
            }
            elseif(Auth::user()->id == 256) { 
                $all_partners = Partner::select('name','id')->where('id',44)->get();
            } 
            elseif(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
            	$all_partners = Partner::select('name','id')->where('client_id',9)->get();
            }
            else {
                if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
                $all_partners = Partner::select('name','id')->where('client_id',$client_id)->get();
            }
                
        }elseif(Auth::user()->hasRole('process-owner')){

            /* $data = Auth::user()->company_id;
            echo "<pre>";
            print_r($data);
            echo "</pre>";
            dd(); */

            $all_partners = Partner::select('name','id')->where('company_id',Auth::user()->company_id)->get();
            $client_details = Client::select('id','name')->where('company_id',Auth::user()->company_id)->get();

            
            return view('reports.rebuttal_summary',compact('all_partners','client_details'));
        }
        if(Auth::user()->id == 253) {
            $all_partners = Partner::select('name','id')->where('id',40)->get();
        }
        if (Auth::user()->id == 139) {
            $all_partners = Partner::select('name','id')->where('id',38)->get();
        }
        if (Auth::user()->id == 195) {
            $all_partners = Partner::select('name','id')->where('id',39)->get();
        }
        if (Auth::user()->id == 254) {
            $all_partners = Partner::select('name','id')->where('id',43)->get();
        }
        if(Auth::user()->id == 250) {
            $all_partners = Partner::select('name','id')->where('id',41)->get();
        }
        if(Auth::user()->id == 255) {
           $all_partners = Partner::select('name','id')->where('id',44)->get();  
        }
        
        if(Auth::user()->id == 246) {
            $all_partners = Partner::select('name','id')->where('id',32)->get(); 
        }
        if(Auth::user()->id == 172 || Auth::user()->id == 198) {
                $all_partners = Partner::select('name','id')->where('client_id',9)->get();
        }
        if(Auth::user()->id == 41) {
            $all_partners = Partner::select('name','id')->where('client_id',1)->get();
        }
        return view('reports.rebuttal_summary',compact('all_partners'));
    }

    public function rebuttal_overall(Request $request) {
        //echo "<pre>"; print_r($request->all()); die;
        $dates = explode(",", $request->date);

        $start_date = date_to_db($dates[0]);
        $end_date = date_to_db($dates[1]);

        if(Auth::user()->hasRole('partner-admin'))
        {
            $client_id = Auth::user()->partner_admin_detail->client_id;            
        }elseif(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head')||
                Auth::user()->hasRole('partner-quality-head'))
        {
            $client_id = Auth::user()->spoc_detail->partner->client_id;
        }elseif(Auth::user()->hasRole('client')){
        	if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
        		$client_id=9;
        	} else {
        		if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
        	}
            
        }     
        if(Auth::user()->id == 44) {
            $partner_id = 1;
            $location_id = 2;
        } else {
            $partner_id = $request->partner_id;
            $location_id = $request->location_id;
        }

        if(Auth::user()->id == 172 || Auth::user()->id == 198) {
            $client_id=9;
        }
        if(Auth::user()->id == 41) {
            $client_id=1;
        } 
        if($request->partner_id == 'all') {
            $partner_id = '%';
        } else {
            $partner_id = $request->partner_id;
        }  
        
        $process_id = $request->process_id;
        $lob = $request->lob;
        // starts rebuttal 
        $rebuttal_data = [];

        $audit_data = Audit::where('client_id',$client_id)
                                       ->where('partner_id','like',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                            $query->where('lob', 'like', $lob);
                                        })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])
                                       ->get();
        $final_data=array();
        
        $final_data['raised']=0;
        $final_data['accepted']=0;
        $final_data['rejected']=0;            
            $temp_total_rebuttal = 0;
            $temp_accepted_rebuttal = 0;
            $temp_rejected_rebuttal=0;
            foreach ($audit_data as $key => $value) {
                if($value->rebuttal_status > 0)
                {
                    $temp_total_rebuttal += $value->audit_rebuttal_count;
                    $temp_accepted_rebuttal += $value->audit_rebuttal_accepted->count();
                    $temp_rejected_rebuttal += $value->audit_rebuttal_rejected->count();
                }
            }
            
            $final_data['raised'] += $temp_total_rebuttal;
            $final_data['accepted'] += $temp_accepted_rebuttal;
            $final_data['rejected'] += $temp_rejected_rebuttal;
        
        $final_data['wip']=($final_data['raised']-($final_data['accepted']+$final_data['rejected']));

        if($audit_data->count())
        $final_data['raised_per'] = round(($final_data['raised']/$audit_data->count())*100);
        else
        $final_data['raised_per'] =0; 

        if($final_data['accepted'])
           $final_data['accepted_per'] = round((($final_data['accepted']/$audit_data->count())*100));
        else
            $final_data['accepted_per'] = 0;

        if($final_data['rejected'])
           $final_data['rejected_per'] = round((($final_data['rejected']/$audit_data->count())*100));
        else
            $final_data['rejected_per'] = 0;

        if($final_data['wip'])
           $final_data['wip_per'] = round((($final_data['wip']/$audit_data->count())*100));
        else
            $final_data['wip_per'] = 0;
        
        $p_data = Partner::with('client')->find($partner_id);
        $getClient=Client::find($client_id);
        $client_qc_time = $getClient->qc_time;
        $client_rebuttal_time = $getClient->rebuttal_time;
        $client_rebuttal_time +=$client_qc_time;
        $start_date_time = date('Y-m-d H:i:s');

        $data = RawData::where('status',1)
                        ->where('partner_id','like',$partner_id)
                        ->where('partner_location_id',$location_id)
                        ->whereHas('audit', function ($query) use ($start_date,$end_date){
                            $query->whereDate('audit_date','>=',$start_date)->whereDate('audit_date','<=',$end_date);
                        })->whereHas('audit', function ($query) use  ($start_date_time,$client_qc_time,$client_rebuttal_time) {
                            $query->whereRaw('audit_date + interval '.$client_qc_time.' hour >= ?', [$start_date_time]);
                        })->with('audit')
                        ->get();

        $partner_bucket=array();
        $partner_bucket['Seen']=0;
        $partner_bucket['Un-seen']=0;
        $partner_bucket['Raised']=0;
        $partner_bucket['Re-Rebuttal Raised']=0;
        $call_id_raw=array();
        foreach($data as $row) {
           $status=audit_rebuttal_status($row->audit->rebuttal_status);
           $partner_bucket[$status] += $row->audit->audit_rebuttal->count();
           if($row->id) {
               $getRow=$row->audit->audit_rebuttal->where('raw_data_id',$row->id)->get();
               if($getRow) {
                    foreach($getRow as $i) {
                        $call_id_raw[] = RawData::select('call_id')->where('id',$i->raw_data_id)->get();
                    }
               }               
           }
           $partner_bucket['Re-Rebuttal Raised'] += $row->audit->audit_rebuttal->where('re_rebuttal_id','>',0)->count();
        }

        $partner_bucket['Raised']=count(array_unique($call_id_raw));
        
        $partner_bucket['Seen_per']=($final_data['raised']) ? round($partner_bucket['Seen']/$final_data['raised']) : 0;
        $partner_bucket['Un-seen_per']=($final_data['raised']) ? round($partner_bucket['Un-seen']/$final_data['raised']) : 0;
        $partner_bucket['Raised_per']=($final_data['raised']) ? round($partner_bucket['Raised']/$final_data['raised']) : 0;
        $partner_bucket['Re-Rebuttal-Raised-per']=($final_data['rejected']) ? round($partner_bucket['Re-Rebuttal Raised']/$final_data['rejected']) : 0;
       
        //echo "<pre>";
        //print_r($partner_bucket); die;
        return view('reports.ajax_view.rebuttal_overall',compact('final_data','partner_bucket'));
    }

    public function rebuttal_disposition_wise(Request $request) {
        //echo "<pre>"; print_r($request->all()); die;
        $dates = explode(",", $request->date);

        $start_date = date_to_db($dates[0]);
        $end_date = date_to_db($dates[1]);

        if(Auth::user()->hasRole('partner-admin'))
        {
            $client_id = Auth::user()->partner_admin_detail->client_id;            
        }elseif(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head')||
                Auth::user()->hasRole('partner-quality-head'))
        {
            $client_id = Auth::user()->spoc_detail->partner->client_id;
        }elseif(Auth::user()->hasRole('client')){
        	if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
        		$client_id=9;
        	} else {
        		if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
        	}
            
        }
        if(Auth::user()->id == 172 || Auth::user()->id == 198) {
            $client_id=9;
        } 
        if(Auth::user()->id == 41) {
            $client_id=1;
        }      
        if(Auth::user()->id == 44) {
            $partner_id = 1;
            $location_id = 2;
        } else {
            $partner_id = $request->partner_id;
            $location_id = $request->location_id;
        }

        if($request->partner_id == 'all') {
            $partner_id = '%';
        } else {
            $partner_id = $request->partner_id;
        }
        
        $process_id = $request->process_id;
        $lob = $request->lob;
        // starts rebuttal 
        $rebuttal_data = [];

        $audit_data = Audit::where('client_id',$client_id)
                                       ->where('partner_id','like',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                            $query->where('lob', 'like', $lob);
                                        })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])
                                       ->get();
        $final_data=array();
        $temp_all_unique_despos=[];
        foreach ($audit_data as $key => $value) {
            // get all unique despositions
            $temp_all_unique_despos[] = $value->raw_data->disposition;
        }
        $all_unique_despos = array_unique($temp_all_unique_despos);
        //echo "<pre>"; print_r($all_unique_despos);
        $rebuttal_array=array();
        $final_data['total']=array();
        $final_data['total']['raised']=0;
        $final_data['total']['accepted']=0;
        $final_data['total']['rejected']=0;        
        $final_data['total']['contribution']=0;
        foreach($all_unique_despos as $despo) {
            $rebuttal_array[$despo]=array();
            $temp_total_rebuttal = 0;
            $temp_accepted_rebuttal = 0;
            $temp_rejected_rebuttal=0;
            foreach ($audit_data as $key => $value) {
                if($value->rebuttal_status > 0 && $value->raw_data->disposition == $despo)
                {
                    $temp_total_rebuttal += $value->audit_rebuttal_count;
                    $temp_accepted_rebuttal += $value->audit_rebuttal_accepted->count();
                    $temp_rejected_rebuttal += $value->audit_rebuttal_rejected->count();
                }
            }
            $rebuttal_array[$despo]['raised'] = $temp_total_rebuttal;            
            $rebuttal_array[$despo]['accepted'] = $temp_accepted_rebuttal;
            $rebuttal_array[$despo]['rejected'] = $temp_rejected_rebuttal;
            $rebuttal_array[$despo]['wip'] = ($temp_total_rebuttal-($temp_accepted_rebuttal+$temp_rejected_rebuttal));
            $final_data['total']['raised'] += $temp_total_rebuttal;
            $final_data['total']['accepted'] += $temp_accepted_rebuttal;
            $final_data['total']['rejected'] += $temp_rejected_rebuttal;
        }

        foreach($all_unique_despos as $despo) {           
            $temp_total_rebuttal = 0;           
            foreach ($audit_data as $key => $value) {
                if($value->rebuttal_status > 0 && $value->raw_data->disposition == $despo)
                {
                    $temp_total_rebuttal += $value->audit_rebuttal_count;                    
                }
            }
            if($final_data['total']['raised'] != 0) {
                $rebuttal_array[$despo]['contribution'] = round(($temp_total_rebuttal/$final_data['total']['raised'])*100);  
            } else {
                $rebuttal_array[$despo]['contribution'] = 0;
            }
            
            $final_data['total']['contribution'] += $rebuttal_array[$despo]['contribution'];
        }

        $final_data['total']['wip']=($final_data['total']['raised']-($final_data['total']['accepted']+$final_data['total']['rejected']));
        $final_data['dispositions'] = $all_unique_despos;
        $final_data['rebuttal_array'] = $rebuttal_array;
        return view('reports.ajax_view.rebuttal_disposition',compact('final_data'));
    }

    public function rebuttal_agent_wise(Request $request) {
        //echo "<pre>"; print_r($request->all()); die;
        $dates = explode(",", $request->date);

        $start_date = date_to_db($dates[0]);
        $end_date = date_to_db($dates[1]);

        if(Auth::user()->hasRole('partner-admin'))
        {
            $client_id = Auth::user()->partner_admin_detail->client_id;            
        }elseif(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head')||
                Auth::user()->hasRole('partner-quality-head'))
        {
            $client_id = Auth::user()->spoc_detail->partner->client_id;
        }elseif(Auth::user()->hasRole('client')){
        	if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
        		$client_id=9;
        	} else {
        		if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
        	}
            
        }     
        if(Auth::user()->id == 44) {
            $partner_id = 1;
            $location_id = 2;
        } else {
            $partner_id = $request->partner_id;
            $location_id = $request->location_id;
        }

        if(Auth::user()->id == 172 || Auth::user()->id == 198) {
            $client_id=9;
        }
        if(Auth::user()->id == 41) {
            $client_id=1;
        } 

        if($request->partner_id == 'all') {
            $partner_id = '%';
        } else {
            $partner_id = $request->partner_id;
        }  
        
        $process_id = $request->process_id;
        $lob = $request->lob;
        // starts rebuttal 
        $rebuttal_data = [];

        $audit_data = Audit::where('client_id',$client_id)
                                       ->where('partner_id','like',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                            $query->where('lob', 'like', $lob);
                                        })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])
                                       ->get();
        $final_data=array();
        $temp_all_unique_despos=[];
        foreach ($audit_data as $key => $value) {
            // get all unique despositions
            $temp_all_unique_despos[] = $value->raw_data->emp_id;
        }
        $all_unique_agents = array_unique($temp_all_unique_despos);
        //echo "<pre>"; print_r($all_unique_despos);
        $rebuttal_array=array();
        $final_data['total']=array();
        $final_data['total']['raised']=0;
        $final_data['total']['accepted']=0;
        $final_data['total']['rejected']=0; 
        foreach($all_unique_agents as $despo) {
            $rebuttal_array[$despo]=array();
            $temp_total_rebuttal = 0;
            $temp_accepted_rebuttal = 0;
            $temp_rejected_rebuttal=0;
            foreach ($audit_data as $key => $value) {
                if($value->rebuttal_status > 0 && $value->raw_data->emp_id == $despo)
                {
                    $temp_total_rebuttal += $value->audit_rebuttal_count;
                    $temp_accepted_rebuttal += $value->audit_rebuttal_accepted->count();
                    $temp_rejected_rebuttal += $value->audit_rebuttal_rejected->count();
                }
            }
            $rebuttal_array[$despo]['raised'] = $temp_total_rebuttal;
            $rebuttal_array[$despo]['accepted'] = $temp_accepted_rebuttal;
            $rebuttal_array[$despo]['rejected'] = $temp_rejected_rebuttal;
            $rebuttal_array[$despo]['wip'] = ($temp_total_rebuttal-($temp_accepted_rebuttal+$temp_rejected_rebuttal));
            $final_data['total']['raised'] += $temp_total_rebuttal;
            $final_data['total']['accepted'] += $temp_accepted_rebuttal;
            $final_data['total']['rejected'] += $temp_rejected_rebuttal;
        }
        $final_data['total']['wip']=($final_data['total']['raised']-($final_data['total']['accepted']+$final_data['total']['rejected']));
        $final_data['all_unique_agents'] = $all_unique_agents;
        $final_data['rebuttal_array'] = $rebuttal_array;
        return view('reports.ajax_view.rebuttal_agent_sum',compact('final_data'));
    }

    public function rebuttal_parameter_wise(Request $request) {
        //echo "<pre>"; print_r($request->all()); die;
        $dates = explode(",", $request->date);

        $start_date = date_to_db($dates[0]);
        $end_date = date_to_db($dates[1]);

        if(Auth::user()->hasRole('partner-admin'))
        {
            $client_id = Auth::user()->partner_admin_detail->client_id;            
        }elseif(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head')||
                Auth::user()->hasRole('partner-quality-head'))
        {
            $client_id = Auth::user()->spoc_detail->partner->client_id;
        }elseif(Auth::user()->hasRole('client')){
        	if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
        		$client_id=9;
        	} else {
        		if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
        	}
            
        }     
        if(Auth::user()->id == 44) {
            $partner_id = 1;
            $location_id = 2;
        } else {
            $partner_id = $request->partner_id;
            $location_id = $request->location_id;
        }

        if(Auth::user()->id == 172 || Auth::user()->id == 198) {
            $client_id=9;
        }  
        if(Auth::user()->id == 41) {
            $client_id=1;
        } 
        if($request->partner_id == 'all') {
            $partner_id = '%';
        } else {
            $partner_id = $request->partner_id;
        }

        $process_id = $request->process_id;
        $lob = $request->lob;
        
        $audit_data = Audit::where('client_id',$client_id)
                                       ->where('partner_id','like',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                            $query->where('lob', 'like', $lob);
                                        })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])
                                       ->get();
       
        $latest_qm_sheet = QmSheet::where('client_id',$client_id)->where('process_id',$process_id)->orderBy('version','desc')->first();
        $latest_qm_sheet_id = $latest_qm_sheet->id;
        $all_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->with('qm_sheet_sub_parameter')->get();

        $final_data=array();
        
        //echo "<pre>"; print_r($all_unique_despos);
        $rebuttal_array=array();
        $final_data['total']=array();
        $final_data['total']['raised']=0;
        $final_data['total']['accepted']=0;
        $final_data['total']['rejected']=0; 
        
        foreach ($all_params as $key => $despo) {
            //echo $despo->id; 
            $rebuttal_array[$despo->parameter]=array(); 
            $temp_total_rebuttal = 0;
            $temp_accepted_rebuttal = 0;
            $temp_rejected_rebuttal=0;                                
            foreach($audit_data as $key => $value) {              
                if($value->rebuttal_status > 0)
                {   
                    $temp_total_rebuttal += $value->audit_rebuttal->where('parameter_id',$despo->id)->count();
                    $temp_accepted_rebuttal += $value->audit_rebuttal_accepted->where('parameter_id',$despo->id)->count();
                    $temp_rejected_rebuttal += $value->audit_rebuttal_rejected->where('parameter_id',$despo->id)->count(); 
                    //$temp_total_rebuttal += $value->audit_rebuttal_count;
                    //$temp_accepted_rebuttal += $value->audit_rebuttal_accepted->count();
                    //$temp_rejected_rebuttal += $value->audit_rebuttal_rejected->count();                            
                }                                                                 
            }
            $rebuttal_array[$despo->parameter]['raised'] = $temp_total_rebuttal;
            $rebuttal_array[$despo->parameter]['accepted'] = $temp_accepted_rebuttal;
            $rebuttal_array[$despo->parameter]['rejected'] = $temp_rejected_rebuttal;
            $rebuttal_array[$despo->parameter]['wip'] = ($temp_total_rebuttal-($temp_accepted_rebuttal+$temp_rejected_rebuttal));  

            $final_data['total']['raised'] += $temp_total_rebuttal;
            $final_data['total']['accepted'] += $temp_accepted_rebuttal;
            $final_data['total']['rejected'] += $temp_rejected_rebuttal;               
        }     

        $final_data['total']['wip']=($final_data['total']['raised']-($final_data['total']['accepted']+$final_data['total']['rejected']));
        $final_data['rebuttal_array'] = $rebuttal_array;
        //echo "<pre>";
        //print_r($final_data); die;
        return view('reports.ajax_view.rebuttal_parameter',compact('final_data'));
    }

    public function get_partner_lob($partner_id)
    {
        /*  echo $partner_id;
            dd(); */

        if($partner_id == 'all'){
          
        	if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) {  
        		$temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',32)->get()->toArray();
                
            }
            elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',40)->get()->toArray();
                
            }
            elseif (Auth::user()->id == 139) {
                
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',38)->get()->toArray();
            }
            elseif (Auth::user()->id == 195) {
                
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',39)->get()->toArray();
            }
            elseif (Auth::user()->id == 254) {
                
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',43)->get()->toArray();
            }
            elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',41)->get()->toArray();
            }
            elseif(Auth::user()->id == 252 || Auth::user()->id == 269 || Auth::user()->id == 279 || Auth::user()->id == 283) {
                
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',38)->orWhere('partner_id',39)->orWhere('partner_id',43)->orWhere('partner_id',45)->get()->toArray();
            }
            elseif(Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 280) {
                
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',38)->orWhere('partner_id',45)->get()->toArray();
            }
            elseif(Auth::user()->id == 282) {
                
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',39)->orWhere('partner_id',43)->get()->toArray();
            }
            elseif(Auth::user()->id == 256 || Auth::user()->id == 255) { 
                
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',44)->get()->toArray();
            }
            else if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198){
                
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('client_id',9)->get()->toArray();
            }
            else if(Auth::user()->id == 307 || Auth::user()->id == 308){
                
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',48)->get()->toArray();
            }
            else {
                if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('client_id',$client_id)->get()->toArray();
                
        	}
            
        } else {
        	if((Auth::user()->id == 241 || Auth::user()->id == 242 || Auth::user()->id == 251 || Auth::user()->id == 246)) { 
        		$temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',32)->get()->toArray();
        	} 
            elseif(Auth::user()->id == 248 || Auth::user()->id == 253) {
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',40)->get()->toArray();
            }
            elseif (Auth::user()->id == 139) {
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',38)->get()->toArray();
            }
            elseif (Auth::user()->id == 195) {
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',39)->get()->toArray();
            }
            elseif (Auth::user()->id == 254) {
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',43)->get()->toArray();
            }
            elseif(Auth::user()->id == 249 || Auth::user()->id == 250) {
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',41)->get()->toArray();
            } elseif(Auth::user()->id == 252 || Auth::user()->id == 269 || Auth::user()->id == 279 || Auth::user()->id == 283) {
                 $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',38)->orWhere('partner_id',39)->orWhere('partner_id',43)->orWhere('partner_id',45)->get()->toArray();
            }
            elseif(Auth::user()->id == 270 || Auth::user()->id == 271 || Auth::user()->id == 280) {
                 $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',38)->orWhere('partner_id',45)->get()->toArray();
            }
            elseif(Auth::user()->id == 282) {
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',39)->orWhere('partner_id',43)->get()->toArray();
           }
            elseif(Auth::user()->id == 256 || Auth::user()->id == 255) {
                 $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',44)->get()->toArray();
            }
            else if(Auth::user()->id == 307 || Auth::user()->id == 308){
                
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',48)->get()->toArray();
            }
            else {
        		$temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',$partner_id)->get()->toArray();
        	}

           
        }
        
        /* echo "<pre>";
        print_r($temp_all_lob); die; */
        $a=0;
        $html='<option value="%">ALL</option>';
        foreach($temp_all_lob as $val) {
            $html.='<option value="'.$val['lob'].'">'.$val['lob'].'</option>';
        } 
        /* $html.="<option value='%'>ALL</option>";  */      
        return $html;               
    }

    public function get_partner_audit_cycle($partner_id)
    {
       // $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',$partner_id)->get()->toArray();
        //echo "<pre>";
        //print_r($temp_all_lob); die;
        if(Auth::user()->id == 253 || Auth::user()->id == 255 || Auth::user()->id == 250 || Auth::user()->id == 246 || Auth::user()->id == 139 || Auth::user()->id == 195 || Auth::user()->id == 254 || Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198 || Auth::user()->id == 267 || Auth::user()->id == 274 ) {
            $client_id=9;
        } else {
            //$client_id=1;
            if(Auth::user()->id == 85){
                $client_id = 1;
            } else if(Auth::user()->id == 41){
                $client_id = 1;
            } else {
                if(Auth::user()->hasRole('partner-training-head')||
                    Auth::user()->hasRole('partner-operation-head')||
                    Auth::user()->hasRole('partner-quality-head') || 
                    Auth::user()->hasRole('partner-admin')){
                        $detail = Partner::where('admin_user_id',Auth::user()->id)->first();
                        if(Auth::user()->id == 307 || Auth::user()->id == 308) {
                          $client_id = 13;  
                        } else {
                           $client_id = $detail->client_id; 
                        } 
                        
                } else {
                    if(Auth::user()->client_detail) {
                        $client_id = Auth::user()->client_detail->client_id;
                    } else {
                        $client_id = Auth::user()->parent_client;
                    }
                }
                
               
            }
            
        }
        
        $audit_cyle_data = Auditcycle::where('client_id',$client_id)->where('process_id',$partner_id)->orderby('start_date','desc')->get();

        $a=0;
        $html='';
        foreach($audit_cyle_data as $val) {
            $html.='<option value="'.$val['start_date'].','.$val['end_date'].'">'.$val['name'].' '.$val['start_date'].','.$val['end_date'].'</option>';
        }      
        $html.='<option value="2020-04-01,2021-03-31">Current Financial Year</option>';
        
        return $html;               
    }

     public function get_partner_lob_1($partner_id)
    {   
        $all_lobs = [];

        if($partner_id == "all") {
            if(Auth::user()->hasRole('partner-admin'))
            {
                $all_partners = Partner::where('id',Auth::user()->partner_admin_detail->id)->get();  
            }elseif(Auth::user()->hasRole('partner-training-head')||
                    Auth::user()->hasRole('partner-operation-head')||
                    Auth::user()->hasRole('partner-quality-head'))
            {   
                $all_partners = Partner::where('id',Auth::user()->spoc_detail->partner_id)->get();  
            }elseif(Auth::user()->hasRole('client')){
                if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
                $all_partners = Partner::where('client_id',$client_id)->get();  
            }
            foreach($all_partners as $p) {
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',$p->id)->get()->toArray();
                foreach ($temp_all_lob as $key => $value) {  
                    if($value['lob'] != "" && !is_null($value['lob'])) {
                        $all_lobs[$value['lob']] = $value['lob'];
                    }                    
                } 
            }    

        }else {
           $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',$partner_id)->get()->toArray();
            foreach ($temp_all_lob as $key => $value) {
                if($value['lob'] != "" && !is_null($value['lob'])) {
                        $all_lobs[$value['lob']] = $value['lob'];
                    }   
            }  
        }        
         $all_lobs['%']='All';
        return response()->json(['status'=>200,'message'=>"Success",'data'=>$all_lobs], 200);   
    }

    public function new_agent_compliance_report(Request $request) {
        //echo "<pre>"; print_r($request->all()); die;

        $dates = explode(",", $request->date);
        $start_date = date_to_db($dates[0]);
        $end_date = date_to_db($dates[1]);

        if( Auth::user()->hasRole('partner-quality-head') ){
            $partner = PartnersProcessSpoc::where('user_id',Auth::user()->id)->get(); 
            if(Auth::user()->id == 307 || Auth::user()->id == 308) {
                $partnerid=48;
            } else {
                $partnerid =  $partner[0]['partner_id'];
            }
                
            $client_id1 = Partner::where('id',$partnerid)->first(); 
            $client_id = $client_id1->client_id;
        }
        else {
            if(Auth::user()->id == 253 || Auth::user()->id == 250 || Auth::user()->id == 255 || Auth::user()->id == 246 || Auth::user()->id == 139 || Auth::user()->id == 195 || Auth::user()->id == 254 || Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198 || Auth::user()->id == 274) {
                $client_id=9;
            } else if(Auth::user()->id == 41) {
                $client_id=1;
            } else {
                if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
            }
            
        }

       // $client_id = Auth::user()->client_detail->client_id;

        if($request->partner == 'all') {
            $partner_id='%';
        } else {
            $partner_id = $request->partner;
        }
        
        $location_id = $request->location;
        $process_id = $request->process;
        $lob = $request->lob;  

        $audit_data = Audit::where('client_id',$client_id)
       ->where('partner_id','like',$partner_id)
       ->where('process_id',$process_id)
       ->whereDate('audit_date',">=",$start_date)
       ->whereDate('audit_date',"<=",$end_date)
       ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
            $query->where('partner_location_id', 'like', $location_id);
             $query->where('lob', 'like', $lob);                                 
        })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])
       ->get();

            //get latest QM Sheet
            $latest_qm_sheet = QmSheet::where('client_id',$client_id)->where('process_id',$process_id)->orderBy('version','desc')->first();
            $latest_qm_sheet_id = $latest_qm_sheet->id;
            $all_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('is_non_scoring',0)->with('qm_sheet_sub_parameter')->get();         

            $temp_all_unique_despos=[];
            foreach ($audit_data as $key => $value) {
                // get all unique despositions
                $temp_all_unique_despos[] = $value->raw_data->emp_id;
            }
                      
            $all_unique_despos = array_unique($temp_all_unique_despos);
            $all_unique_despos_count = array_count_values($temp_all_unique_despos);

            foreach ($all_params as $key => $value) {
                $temp_param_data[$value->id] = [];
                $temp_param_data[$value->id]['name'] = $value->parameter;
                $temp_param_data[$value->id]['data']=[];
                $temp_param_data[$value->id]['na_count_total']=[];
                $temp_subp_data=[];
                $temp_sum=[];
                $temp_tot_score=[];
                $temp_tot_scorable=[];
                foreach ($value->qm_sheet_sub_parameter as $keyb => $valueb) {
                    $temp_subp_data[$valueb->id] = [];
                    $temp_subp_data[$valueb->id]['name'] = $valueb->sub_parameter;
                    $temp_subp_data[$valueb->id]['fatal'] = $valueb->critical;
                    $temp_data=[];
                    foreach ($all_unique_despos as $keyc => $valuec) {
                        $temp_data[$valuec]['scored'] = 0;
                        $temp_data[$valuec]['scorable'] = 0;
                        $temp_data[$valuec]['score']=0;
                        $temp_data[$valuec]['result_id']=[];
                        $temp_data[$valuec]['na_count']=0;
                        $temp_param_data[$value->id]['na_count_total'][$valuec]=0;
                        $temp_sum[$valuec] = 0;
                        $temp_tot_score[$valuec] = 0;
                        $temp_tot_scorable[$valuec] = 0;
                    }
                    $temp_data['total'] = 0;
                    $temp_sum['total'] = 0;
                    $temp_tot_score['total']=0;
                    $temp_tot_scorable['total']=0;
                    $temp_subp_data[$valueb->id]['data'] = $temp_data;
                }
                $temp_param_data[$value->id]['data'] = $temp_subp_data;
                $temp_param_data[$value->id]['sum'] = $temp_sum;
                $temp_param_data[$value->id]['temp_tot_score'] = $temp_tot_score;
                $temp_param_data[$value->id]['temp_tot_scorable'] = $temp_tot_scorable;
            }
            
            foreach ($audit_data as $key => $value) {
            	//$p_id=$valueb->parameter_id;
            	
                foreach ($value->audit_results->where('is_non_scoring',0) as $keyb => $valueb) {
                    $temp_param_data[$valueb->parameter_id]['data'][$valueb->sub_parameter_id]['data'][$value->raw_data->emp_id]['scored'] += $valueb->score;
                    $temp_param_data[$valueb->parameter_id]['data'][$valueb->sub_parameter_id]['data'][$value->raw_data->emp_id]['scorable'] += $valueb->after_audit_weight;
                    $temp_param_data[$valueb->parameter_id]['data'][$valueb->sub_parameter_id]['data'][$value->raw_data->emp_id]['result_id'][] = $valueb->id;
                    if($valueb->selected_option == 4) {
                        $temp_param_data[$valueb->parameter_id]['data'][$valueb->sub_parameter_id]['data'][$value->raw_data->emp_id]['na_count'] += 1;  
                        $temp_param_data[$valueb->parameter_id]['na_count_total'][$value->raw_data->emp_id] += 1;                      
                    } else {
                        $temp_param_data[$valueb->parameter_id]['data'][$valueb->sub_parameter_id]['data'][$value->raw_data->emp_id]['na_count'] += 0;
                        $temp_param_data[$valueb->parameter_id]['na_count_total'][$value->raw_data->emp_id] += 0;
                    }
                    
                }
            }

            // set scores
            $dispostion=[];
            foreach ($temp_param_data as $key => $value) {
                
                foreach ($value['data'] as $keyb => $valueb) {
                        $score_total = 0;

                        foreach ($all_unique_despos_count as $keyc => $valuec) {
                            if($temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'])
                            $score = round(($temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scored']/$temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'])*100);
                            else
                                $score = 0;

                            $temp_param_data[$key]['data'][$keyb]['data'][$keyc]['score'] = $score;
                            $temp_param_data[$key]['data'][$keyb]['data']['total'] += $score;

                            $temp_param_data[$key]['sum'][$keyc] += $score;
                            $temp_param_data[$key]['temp_tot_score'][$keyc] += $temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scored'];
                            $temp_param_data[$key]['temp_tot_scorable'][$keyc] += $temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'];
                        }

                        if(count($all_unique_despos_count) == 0) {
                            $temp_param_data[$key]['data'][$keyb]['data']['total'] = 0;
                        } else { 
                            $temp_param_data[$key]['data'][$keyb]['data']['total'] = round(($temp_param_data[$key]['data'][$keyb]['data']['total']/count($all_unique_despos_count)));
                        }

                        $temp_param_data[$key]['sum']['total'] += $temp_param_data[$key]['data'][$keyb]['data']['total'];
                }
                $p_scr=0;
                $p_sco=0;
                foreach ($all_unique_despos_count as $keyd => $valued) {

                	$scr=0;
	            	$sca=0;
	            	foreach ($audit_data as $ke => $val) {
	            	            	
		                foreach ($val->audit_parameter_result->where('is_non_scoring',0) as $ke_1 => $valb) {
		                	if($val->raw_data->emp_id == $keyd && $valb->parameter_id == $key){
		                		$scr+=$valb->with_fatal_score;
		                		$sca+=$valb->temp_weight;
		                		$p_scr+=$valb->with_fatal_score;
                				$p_sco+=$valb->temp_weight;
		                	}
		                }
	             	}
                	
                    if($sca != 0) {
                        $temp_param_data[$key]['sum'][$keyd] = round(($scr/$sca)*100);                                                      
                    } else {
                        $temp_param_data[$key]['sum'][$keyd] =0;
                    }
                }

                //$temp_param_data[$key]['sum']['total'] = round(($temp_param_data[$key]['sum']['total']/count($value['data'])));
                if($p_sco != 0) {
                	$temp_param_data[$key]['sum']['total']=round(($p_scr/$p_sco)*100);
                } else {
                	$temp_param_data[$key]['sum']['total']=0;
                }
                $temp_param_data[$key]['sum']['na_count_total_got'] = array_sum($temp_param_data[$key]['na_count_total']);
            }

            $score=[];
            $score['scored']=[];
            $score['scorable']=[];
            foreach ($all_unique_despos_count as $keyc => $valuec) {
                $score['scored'][$keyc]=0;
                $score['scorable'][$keyc]=0;
            }
            foreach($temp_param_data as $key => $value) {
                foreach ($value['data'] as $keyb => $valueb) { 
                   foreach ($all_unique_despos_count as $keyc => $valuec) {
                      $score['scored'][$keyc] +=$temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scored'];
                      $score['scorable'][$keyc] +=$temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'];
                   }
                }
            }
              
            $all_scores=array();
            $all_audit_counts=array();
            $all_na_counts=array();
            $all_audit_single_skill_set=array();
            foreach ($all_unique_despos_count as $keyc => $valuec) {
            	$scr=0;
            	$sca=0;
                $ag_count=0;
            	foreach ($audit_data as $key => $value) {
            	//$p_id=$valueb->parameter_id; 
                if($value->raw_data->emp_id == $keyc){
                    $ag_count+=1;           	
    	                foreach ($value->audit_parameter_result->where('is_non_scoring',0) as $keyb => $valueb) {
    	                	
    	                		// $scr+=$value->with_fatal_score_per;
    	                		// $sca+=1;
    	                		if($value->is_critical == 0) {
                            		$scr += $valueb->with_fatal_score;
                        		}                         
                        		$sca += $valueb->temp_weight; 
    	                	
    	                }
                    }                    
             	}

                // if($score['scorable'][$keyc] != 0){
                //     $all_scores[$keyc]=round(($score['scored'][$keyc]/$score['scorable'][$keyc])*100);
                if($sca != 0){
                	$all_scores[$keyc]=round(($scr/$sca)*100);
                } else {
                     $all_scores[$keyc]=0;
                }
                $all_audit_counts_temp=Audit::where('client_id',$client_id)
                   ->where('partner_id',$partner_id)
                   ->where('process_id',$process_id)
                   ->whereDate('audit_date',">=",$start_date)
                   ->whereDate('audit_date',"<=",$end_date)
                   
                   ->with('raw_data')
                   ->whereHas('raw_data', function (Builder $query) use ($lob,$location_id,$keyc) {
                        $query->where('partner_location_id', 'like', $location_id);  
                        $query->where('emp_id', 'like', $keyc);
                        $query->where('lob', 'like', $lob); 
                    })->get();
               $all_audit_counts[$keyc] = $ag_count;

               if($all_audit_counts_temp->count()>0)
               $all_audit_single_skill_set[$keyc] = $all_audit_counts_temp[0]->raw_data->info_1;
               else
               $all_audit_single_skill_set[$keyc] = 'N/A';
               
            }

            //echo "<pre>";
            //print_r($all_scores);
            //die;
            $fatal_audit_score_sum=0;
        $scr=0;
        $sca=0;
            foreach ($audit_data as $key => $value) {   
                foreach ($value->audit_parameter_result->where('is_non_scoring','!=',1) as $keyb => $valueb) {
                        
                        if($value->is_critical == 0) {
                            $scr += $valueb->with_fatal_score;
                        }  
                            //$sca+=$valueb->temp_weight;
                        $sca+=$valueb->temp_weight;    
                    }               
            }
            if($sca != 0) {
                $fatal_audit_score_sum=round(($scr/$sca)*100);
            }
        
        $final_data['data'] =$temp_param_data;
        $final_data['audit_score'] =  $all_scores;
        $final_data['audit_count']=$all_audit_counts;
        $final_data['audit_single_skill_set']=$all_audit_single_skill_set;
        $final_data['na_count']=$all_na_counts;

        $total_audit_by_agent=array_sum($all_audit_counts);
        $overall_score_count=count($all_unique_despos)*100; 
        $total_audit_score=$fatal_audit_score_sum; 

        $final_data['total_audit_count'] = $total_audit_by_agent;
        $final_data['total_audit_score'] = $total_audit_score;       
        $final_data['despositions'] = $all_unique_despos;
        $data=1;

        if(Auth::user()->hasRole('partner-admin'))
        {
            if(Auth::user()->id == 139) {
                $all_partners = Partner::where('id',38)->pluck('name','id');
            } 
            elseif(Auth::user()->id == 195) {
                $all_partners = Partner::where('id',39)->pluck('name','id');
            }
            elseif(Auth::user()->id == 307 || Auth::user()->id == 308) {
                $all_partners = Partner::where('id',48)->pluck('name','id');
            }
            else {
                $all_partners = Partner::where('id',Auth::user()->partner_admin_detail->id)->pluck('name','id');
            }
              
        }elseif(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head'))
        {
            $all_partners = Partner::where('id',Auth::user()->spoc_detail->partner_id)->pluck('name','id');  
        }elseif( Auth::user()->hasRole('partner-quality-head') ){

            $partner = PartnersProcessSpoc::where('user_id',Auth::user()->id)->get();  

            $partnerid =  $partner[0]['partner_id'];    

            $all_partners = Partner::where('id',$partnerid)->get(); 

        }
        elseif(Auth::user()->hasRole('client')){
        	if(Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198) {
        		$all_partners = Partner::select('name','id')->where('client_id',9)->get();
        	} else {
                if(Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    $client_id = Auth::user()->parent_client;
                }
                $all_partners = Partner::select('name','id')->where('client_id',$client_id)->get(); 
            }   
        }

        if(Auth::user()->id == 253) {
            $all_partners = Partner::select('name','id')->where('id',40)->get();    
        }

        if(Auth::user()->id == 139) {
            $all_partners = Partner::select('name','id')->where('id',38)->get(); 
        }

        if(Auth::user()->id == 195) {
            $all_partners = Partner::select('name','id')->where('id',39)->get(); 
        }

        if(Auth::user()->id == 254) {
            $all_partners = Partner::select('name','id')->where('id',43)->get(); 
        }

        if(Auth::user()->id == 250) {
            $all_partners = Partner::select('name','id')->where('id',41)->get();    
        }
        if(Auth::user()->id == 255) {
           $all_partners = Partner::select('name','id')->where('id',44)->get();  
        }
        if(Auth::user()->id == 246) {
            $all_partners = Partner::select('name','id')->where('id',32)->get(); 
        }

        if(Auth::user()->id == 172 || Auth::user()->id == 198 || Auth::user()->id == 274) {
                $all_partners = Partner::select('name','id')->where('client_id',9)->get();
        }
        if(Auth::user()->id == 41) {
            $all_partners = Partner::select('name','id')->where('client_id',1)->get();
        }
        if(Auth::user()->id == 307 || Auth::user()->id == 308) {
            $all_partners = Partner::where('id',48)->get();
        }

        //echo "<pre>";
        //print_r($final_data['data']); die;
        //dd($final_data);
        return view('reports.new_agent_compliance',compact('final_data','data','all_partners'));
    } 

    public function new_agent_compliance_report_bkup(Request $request) {
        //echo "<pre>"; print_r($request->all()); die;

        $dates = explode("-", $request->date);
        $start_date = date_to_db($dates[0]);
        $end_date = date_to_db($dates[1]);
        if(Auth::user()->client_detail) {
            $client_id = Auth::user()->client_detail->client_id;
        } else {
            $client_id = Auth::user()->parent_client;
        }
        $partner_id = $request->partner;
        $location_id = $request->location;
        $process_id = $request->process;
        $lob = $request->lob;  

        $audit_data = Audit::where('client_id',$client_id)
                                       ->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$lob) {
                                            $query->where('partner_location_id', 'like', $location_id);
                                             $query->where('lob', 'like', $lob);                                 
                                        })->withCount(['audit_rebuttal','audit_rebuttal_accepted','audit_parameter_result','raw_data','audit_results'])
                                       ->get();

            //get latest QM Sheet
            $latest_qm_sheet = QmSheet::where('client_id',$client_id)->where('process_id',$process_id)->orderBy('version','desc')->first();
            $latest_qm_sheet_id = $latest_qm_sheet->id;
            $all_params = QmSheetParameter::where('qm_sheet_id',$latest_qm_sheet_id)->where('is_non_scoring',0)->with('qm_sheet_sub_parameter')->get();         

            $temp_all_unique_despos=[];
            foreach ($audit_data as $key => $value) {
                // get all unique despositions
                $temp_all_unique_despos[] = $value->raw_data->emp_id;
            }
                      
            $all_unique_despos = array_unique($temp_all_unique_despos);
            $all_unique_despos_count = array_count_values($temp_all_unique_despos);

            foreach ($all_params as $key => $value) {
                $temp_param_data[$value->id] = [];
                $temp_param_data[$value->id]['name'] = $value->parameter;
                $temp_param_data[$value->id]['data']=[];
                $temp_subp_data=[];
                $temp_sum=[];
                $temp_tot_score=[];
                $temp_tot_scorable=[];
                foreach ($value->qm_sheet_sub_parameter as $keyb => $valueb) {
                    $temp_subp_data[$valueb->id] = [];
                    $temp_subp_data[$valueb->id]['name'] = $valueb->sub_parameter;
                    $temp_data=[];
                    foreach ($all_unique_despos as $keyc => $valuec) {
                        $temp_data[$valuec]['scored'] = 0;
                        $temp_data[$valuec]['scorable'] = 0;
                        $temp_data[$valuec]['score']=0;
                        $temp_data[$valuec]['result_id']=[];
                        $temp_sum[$valuec] = 0;
                        $temp_tot_score[$valuec] = 0;
                        $temp_tot_scorable[$valuec] = 0;
                    }
                    $temp_data['total'] = 0;
                    $temp_sum['total'] = 0;
                    $temp_tot_score['total']=0;
                    $temp_tot_scorable['total']=0;
                    $temp_subp_data[$valueb->id]['data'] = $temp_data;
                }
                $temp_param_data[$value->id]['data'] = $temp_subp_data;
                $temp_param_data[$value->id]['sum'] = $temp_sum;
                $temp_param_data[$value->id]['temp_tot_score'] = $temp_tot_score;
                $temp_param_data[$value->id]['temp_tot_scorable'] = $temp_tot_scorable;
            }

            foreach ($audit_data as $key => $value) {
                foreach ($value->audit_results->where('is_non_scoring',0) as $keyb => $valueb) {
                    $temp_param_data[$valueb->parameter_id]['data'][$valueb->sub_parameter_id]['data'][$value->raw_data->emp_id]['scored'] += $valueb->score;
                    $temp_param_data[$valueb->parameter_id]['data'][$valueb->sub_parameter_id]['data'][$value->raw_data->emp_id]['scorable'] += $valueb->after_audit_weight;
                    $temp_param_data[$valueb->parameter_id]['data'][$valueb->sub_parameter_id]['data'][$value->raw_data->emp_id]['result_id'][] = $valueb->id;
                }
            }

            // set scores
            $dispostion=[];
            foreach ($temp_param_data as $key => $value) {
                
                foreach ($value['data'] as $keyb => $valueb) {
                        $score_total = 0;

                        foreach ($all_unique_despos_count as $keyc => $valuec) {
                            if($temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'])
                            $score = round(($temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scored']/$temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'])*100);
                            else
                                $score = 0;

                            $temp_param_data[$key]['data'][$keyb]['data'][$keyc]['score'] = $score;
                            $temp_param_data[$key]['data'][$keyb]['data']['total'] += $score;

                            $temp_param_data[$key]['sum'][$keyc] += $score;
                            $temp_param_data[$key]['temp_tot_score'][$keyc] += $temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scored'];
                            $temp_param_data[$key]['temp_tot_scorable'][$keyc] += $temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'];
                        }

                        $temp_param_data[$key]['data'][$keyb]['data']['total'] = round(($temp_param_data[$key]['data'][$keyb]['data']['total']/count($all_unique_despos_count)));

                        $temp_param_data[$key]['sum']['total'] += $temp_param_data[$key]['data'][$keyb]['data']['total'];
                }

                foreach ($all_unique_despos_count as $keyd => $valued) {
                    if($temp_param_data[$key]['temp_tot_scorable'][$keyd] != 0) {
                        $temp_param_data[$key]['sum'][$keyd] = round(($temp_param_data[$key]['temp_tot_score'][$keyd]/$temp_param_data[$key]['temp_tot_scorable'][$keyd])*100);                                                      
                    } else {
                        $temp_param_data[$key]['sum'][$keyd] =0;
                    }
                }

                $temp_param_data[$key]['sum']['total'] = round(($temp_param_data[$key]['sum']['total']/count($value['data'])));
            }

            $score=[];
            $score['scored']=[];
            $score['scorable']=[];
            foreach ($all_unique_despos_count as $keyc => $valuec) {
                $score['scored'][$keyc]=0;
                $score['scorable'][$keyc]=0;
            }
            foreach($temp_param_data as $key => $value) {
                foreach ($value['data'] as $keyb => $valueb) { 
                   foreach ($all_unique_despos_count as $keyc => $valuec) {
                      $score['scored'][$keyc] +=$temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scored'];
                      $score['scorable'][$keyc] +=$temp_param_data[$key]['data'][$keyb]['data'][$keyc]['scorable'];
                   }
                }
            }
              
            $all_scores=array();
            $all_audit_counts=array();
            $all_na_counts=array();
            foreach ($all_unique_despos_count as $keyc => $valuec) {
                if($score['scorable'][$keyc] != 0){
                    $all_scores[$keyc]=round(($score['scored'][$keyc]/$score['scorable'][$keyc])*100);
                } else {
                     $all_scores[$keyc]=0;
                }
                $all_audit_counts[$keyc]=Audit::where('client_id',$client_id)
                                           ->where('partner_id',$partner_id)
                                           ->where('process_id',$process_id)
                                           ->whereDate('audit_date',">=",$start_date)
                                           ->whereDate('audit_date',"<=",$end_date)
                                           ->whereHas('raw_data', function (Builder $query) use ($location_id,$keyc) {
                                                $query->where('partner_location_id', 'like', $location_id);  
                                                $query->where('emp_id', 'like', $keyc);                              
                                            })->get()->count(); 
                $all_na_counts[$keyc]=Audit::where('client_id',$client_id)
                                       ->where('partner_id',$partner_id)
                                       ->where('process_id',$process_id)
                                       ->whereDate('audit_date',">=",$start_date)
                                       ->whereDate('audit_date',"<=",$end_date)
                                       ->where('case_id','like',"NA%")
                                       ->whereHas('raw_data', function (Builder $query) use ($location_id,$keyc) {
                                            $query->where('partner_location_id', 'like', $location_id);  
                                            $query->where('emp_id', 'like', $keyc);                              
                                        })->get()->count();             
            }

            //echo "<pre>";
            //print_r($all_scores);
            //die;
        $final_data['data'] =$temp_param_data;
        $final_data['audit_score'] =  $all_scores;
        $final_data['audit_count']=$all_audit_counts;
        $final_data['na_count']=$all_na_counts;

        $total_audit_by_agent=array_sum($all_audit_counts);
        $overall_score_count=count($all_unique_despos)*100; 
        $total_audit_score=round((array_sum($all_scores)/$overall_score_count)*100); 

        $final_data['total_audit_count'] = $total_audit_by_agent;
        $final_data['total_audit_score'] = $total_audit_score;       
        $final_data['despositions'] = $all_unique_despos;
        $data=1;

        if(Auth::user()->hasRole('partner-admin'))
        {
            $all_partners = Partner::where('id',Auth::user()->partner_admin_detail->id)->pluck('name','id');  
        }elseif(Auth::user()->hasRole('partner-training-head')||
                Auth::user()->hasRole('partner-operation-head')||
                Auth::user()->hasRole('partner-quality-head'))
        {
            $all_partners = Partner::where('id',Auth::user()->spoc_detail->partner_id)->pluck('name','id');  
        }elseif(Auth::user()->hasRole('client')){
            if(Auth::user()->client_detail) {
                $client_id = Auth::user()->client_detail->client_id;
            } else {
                $client_id = Auth::user()->parent_client;
            }
            $all_partners = Partner::select('name','id')->where('client_id',$client_id)->get();    
        }

        //echo "<pre>";
        //print_r($final_data['data']); die;
        return view('reports.new_agent_compliance',compact('final_data','data','all_partners'));
    } 
    public function fetch_date()
    {
        $date= strtotime(date('2019-12-27 02:10:00'));
        $holiday='2';
        $delay1= '9'; 
        $delay2= '10';
        $result = skipHoliday($date,$holiday,$delay1,$delay2);
        dd($result);
    } 
    

    public function get_date()
    {
        $data = Audit::with('client')->where('id','>=',0)->where('id','<',1000)->get();
        //dd($data);
        foreach ($data as $value) {
            //dd($value->client->rebuttal_time);
            $result = skipHoliday(strtotime($value->audit_date),$value->client->holiday,$value->client->qc_time,$value->client->rebuttal_time);
            // dd($result[0]);
            $value->qc_tat = $result[0];
            $value->rebuttal_tat = $result[1];
            $value->save();
            
        }
    } 
    
    public function Calculate_process_rebuttal_welcome_dashboard($client_id,$process_id,$month_first_data,$today,$process_audit_count){
        // Add Rebuttal on welcome dashboard on PWS 
         $process_audit_data = array();
         $temp_rebuttal_data_process = Audit::where('client_id',$client_id)->where('rebuttal_status','>',0)
         ->where('process_id',$process_id)
         ->whereDate('audit_date','>=',$month_first_data)
         ->whereDate('audit_date','<=',$today)
         ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
 
         $temp_total_rebuttal_process = 0;
         $temp_accepted_rebuttal_process = 0;
         $temp_rejected_rebuttal_process = 0;
        
         foreach ($temp_rebuttal_data_process as $kay => $value) {
             $temp_total_rebuttal_process += $value->audit_rebuttal->count();
             $temp_accepted_rebuttal_process += $value->audit_rebuttal_accepted->count();
             $temp_rejected_rebuttal_process += $value->audit_rebuttal_rejected->count();
         }
        
         $process_audit_data['raised'] = $temp_total_rebuttal_process;
         $process_audit_data['accepted'] = $temp_accepted_rebuttal_process;
         $process_audit_data['rejected'] = $temp_rejected_rebuttal_process;
         $process_audit_data['wip'] = ($temp_total_rebuttal_process-($temp_accepted_rebuttal_process+$temp_rejected_rebuttal_process));
     
         echo "<script>console.log('".$process_audit_data['raised']."')</script>";
         if($process_audit_count)
             $process_audit_data['rebuttal_per'] = round(($process_audit_data['raised']/$process_audit_count)*100);
         else
             $process_audit_data['rebuttal_per'] =0; 
 
         if($process_audit_count)
             $process_audit_data['accepted_per'] = round((($process_audit_data['accepted']/$process_audit_count)*100));
         else
             $process_audit_data['accepted_per'] = 0;
 
         if($process_audit_count)
             $process_audit_data['rejected_per'] = round((($process_audit_data['rejected']/$process_audit_count)*100));
         else
             $process_audit_data['rejected_per'] = 0;
 
         // End Rebuttal on welcome dashboard on PWS
 
         return $process_audit_data;
    }

    public function Calculate_qrc_process_rebuttal_welcome_dashboard($client_id,$process_id,$month_first_data,$today,$process_audit_count,$qrc){
        // Add Rebuttal on welcome dashboard on PWS 

        
         $process_audit_data = array();
         $temp_rebuttal_data_process = Audit::where('client_id',$client_id)->where('rebuttal_status','>',0)
         ->where('process_id',$process_id)
         ->whereIn('qrc_2',$qrc)
         ->whereDate('audit_date','>=',$month_first_data)
         ->whereDate('audit_date','<=',$today)
         ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
        
         
         $temp_total_rebuttal_process = 0;
         $temp_accepted_rebuttal_process = 0;
         $temp_rejected_rebuttal_process = 0;
        
         foreach ($temp_rebuttal_data_process as $kay => $value) {
             $temp_total_rebuttal_process += $value->audit_rebuttal->count();
             $temp_accepted_rebuttal_process += $value->audit_rebuttal_accepted->count();
             $temp_rejected_rebuttal_process += $value->audit_rebuttal_rejected->count();
         }
        
         $process_audit_data['raised'] = $temp_total_rebuttal_process;
         $process_audit_data['accepted'] = $temp_accepted_rebuttal_process;
         $process_audit_data['rejected'] = $temp_rejected_rebuttal_process;
         $process_audit_data['wip'] = ($temp_total_rebuttal_process-($temp_accepted_rebuttal_process+$temp_rejected_rebuttal_process));
     
        // echo "<script>console.log('".$process_audit_data['raised']."')</script>";
         if($process_audit_count)
             $process_audit_data['rebuttal_per'] = round(($process_audit_data['raised']/$process_audit_count)*100);
         else
             $process_audit_data['rebuttal_per'] =0; 
 
         if($process_audit_count)
             $process_audit_data['accepted_per'] = round((($process_audit_data['accepted']/$process_audit_count)*100));
         else
             $process_audit_data['accepted_per'] = 0;
 
         if($process_audit_count)
             $process_audit_data['rejected_per'] = round((($process_audit_data['rejected']/$process_audit_count)*100));
         else
             $process_audit_data['rejected_per'] = 0;
 
         // End Rebuttal on welcome dashboard on PWS
 
         return $process_audit_data;
    }

}