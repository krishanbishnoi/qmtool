<?php

namespace App\Http\Controllers\Api;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '0');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use Illuminate\Support\Facades\Hash;
Use App\User;
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
use App\ClientAdmin;
use Auth;
use Crypt;
use DB;
use Illuminate\Database\Eloquent\Builder;

use Maatwebsite\Excel\Facades\Excel;
use App\PartnersProcessSpoc;
use PDF;
use App\MonthTarget;
use App\Process;

class ClientController extends Controller
{
    public function welcome_dashboard(Request $request){
        
        

        if($request->user_id) {
            $login_uesr_id = $request->user_id;
            

            if($login_uesr_id == 42 || $login_uesr_id == 172 || $login_uesr_id == 198) {
                $client_id=9;
            } else {
                $client_id = User::where('id',$login_uesr_id)->first();
                $client_admin = ClientAdmin::where('user_id',$client_id->id)->first();
                $client_id = $client_admin->client_id;
            }
    
            if(isset($request->date)){
              
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
              
               if($login_uesr_id == 241 || $login_uesr_id == 242 || $login_uesr_id == 251 || $login_uesr_id == 246) { 
                    $audit_data = Audit::with('raw_data')->where('client_id',$client_id)
                    ->whereDate('audit_date','>=',$month_first_data)
                    ->whereDate('audit_date','<=',$today)->where('partner_id',32)->whereIn('process_id',array(25,26,30))->get();
                    $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->where('partner_id',32)->whereIn('process_id',array(25,26,30))->orderby('id','asc')->get();
               } 
               else if($login_uesr_id == 248 || $login_uesr_id == 253) {
                     $audit_data = Audit::with('raw_data')->where('client_id',$client_id)
                                   ->whereDate('audit_date','>=',$month_first_data)
                                   ->whereDate('audit_date','<=',$today)->where('partner_id',40)->where('process_id',23)->get();
                    $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->where('partner_id',40)->where('process_id',23)->orderby('id','asc')->get();
               }
               elseif($login_uesr_id == 249 || $login_uesr_id == 250) {
                    $audit_data = Audit::with('raw_data')->where('client_id',$client_id)
                                   ->whereDate('audit_date','>=',$month_first_data)
                                   ->whereDate('audit_date','<=',$today)->where('partner_id',41)->where('process_id',31)->get();
                    $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->where('partner_id',41)->where('process_id',31)->orderby('id','asc')->get();
               }
                
               elseif($login_uesr_id == 252 || $login_uesr_id == 269 || $login_uesr_id == 270 || $login_uesr_id == 271) {
                $audit_data = Audit::with('raw_data')->where('client_id',$client_id)
                               ->whereDate('audit_date','>=',$month_first_data)
                               ->whereDate('audit_date','<=',$today)->whereIn('process_id',[21,22])->get();
                $callType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$client_id)->where('partner_id',41)->where('process_id',31)->orderby('id','asc')->get();
                
                }
    
               elseif($login_uesr_id == 256 || $login_uesr_id == 255)  {
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
               
               if($login_uesr_id == 241 || $login_uesr_id == 242 || $login_uesr_id == 251 || $login_uesr_id == 246) { 
                $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('partner_id',32)->whereIn('process_id',array(25,26,30))->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
                ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
               } 
               else if($login_uesr_id == 248 || $login_uesr_id == 253) {
                    $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('partner_id',40)->where('process_id',23)->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
                ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
               }
               elseif($login_uesr_id == 249 || $login_uesr_id == 250) {
                    $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('partner_id',41)->where('process_id',31)->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
                ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
               }
               elseif($login_uesr_id == 256 || $login_uesr_id == 255)  {
                  $temp_rebuttal_data = Audit::where('client_id',$client_id)->where('partner_id',44)->where('process_id',32)->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
                   ->withCount(['audit_rebuttal','audit_rebuttal_accepted'])->get();
               }
               
               elseif($login_uesr_id == 252 || $login_uesr_id == 269 || $login_uesr_id == 270 || $login_uesr_id == 271)  {
                $temp_rebuttal_data = Audit::where('client_id',$client_id)->whereIn('process_id',[21,22])->where('rebuttal_status','>',0)->whereDate('audit_date','>=',$month_first_data)->whereDate('audit_date','<=',$today)
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
                
                
                
                if($login_uesr_id == 241 || $login_uesr_id == 242 || $login_uesr_id == 251 || $login_uesr_id == 246) { 
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
                elseif($login_uesr_id == 248 || $login_uesr_id == 253) {
                    $target=MonthTarget::
                        join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
                    ->where('month_targets.client_id',$client_id)
                        ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',23)->sum('month_targets.eq_audit_target_mtd');
                        }
                        elseif($login_uesr_id == 139) {
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
                elseif($login_uesr_id == 195) {
                    $target=MonthTarget::
                        join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
                    ->where('month_targets.client_id',$client_id)
                        ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',22)->sum('month_targets.eq_audit_target_mtd');
                }
                elseif($login_uesr_id == 254) {
                    $target=MonthTarget::
                        join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
                    ->where('month_targets.client_id',$client_id)
                        ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',22)->sum('month_targets.eq_audit_target_mtd');
                }
                elseif($login_uesr_id == 249 || $login_uesr_id == 250) {
                    $target=MonthTarget::
                        join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')            
                    ->where('month_targets.client_id',$client_id)
                        ->where('auditcycles.name','like',"$month%")->where('month_targets.process_id',31)->sum('month_targets.eq_audit_target_mtd');
                }
                elseif($login_uesr_id == 252 || $login_uesr_id == 269 || $login_uesr_id == 270 || $login_uesr_id == 271) {
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
                elseif($login_uesr_id == 256 || $login_uesr_id == 255) { 
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
            if(($login_uesr_id == 241 || $login_uesr_id == 242 || $login_uesr_id == 251 || $login_uesr_id == 246)) {
                        
                        $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',32)->get();
                    } 
                    else if($login_uesr_id == 248 || $login_uesr_id == 253) {
                        
                        $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',40)->get();
                    }
                    elseif($login_uesr_id == 249 || $login_uesr_id == 250){
                       
                        $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',41)->get();
                    }
    
                    elseif($login_uesr_id == 256 || $login_uesr_id == 255) {
                        
                        $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',44)->get();
                    }
    
                    elseif($login_uesr_id == 139) {
                        
                        $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',38)->get();
                    }
    
                    elseif ($login_uesr_id == 195) {
                       
                       $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',39)->get();
                    }
    
                    elseif ($login_uesr_id == 254) {
                       
                       $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',43)->get();
                    } elseif($login_uesr_id == 252 || $login_uesr_id == 269) {
                        $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',38)->orwhere('id',39)->orwhere('id',43)->orWhere('id',45)->get();
                    } elseif($login_uesr_id == 270 || $login_uesr_id == 271) {
                        $partner_list = Partner::with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->where('id',38)->orWhere('id',45)->get();
                    }
                    else  {
                        $partner_list = Partner::where('client_id',$client_id)->with(['partner_process','partner_process.process','partner_location','partner_location.location_detail'])->get();
                    }
                    
            $partner_process_list=array();
            foreach ($partner_list as $akey => $avalue) {
                foreach ($avalue->partner_process as $bkey => $bvalue) {
                    if($login_uesr_id == 241 || $login_uesr_id == 242 || $login_uesr_id == 251 || $login_uesr_id == 246) {
                        if($bvalue->process_id == 25 || $bvalue->process_id == 26 || $bvalue->process_id == 30) {
                            $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                        }
                    }
                    elseif($login_uesr_id == 248 || $login_uesr_id == 253) {
                            if($bvalue->process_id == 23) {
                                $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                            }
                        }
                        elseif($login_uesr_id == 139) {
                            if($bvalue->process_id == 21 || $bvalue->process_id == 22) {
                                $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                            }
                        }
                        elseif($login_uesr_id == 195) {
                            if($bvalue->process_id == 22) {
                                $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                            }
                        }
                        elseif($login_uesr_id == 254) {
                            if($bvalue->process_id == 22) {
                                $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                            }
                        }
                        elseif($login_uesr_id == 249 || $login_uesr_id == 250) {
                            if($bvalue->process_id == 31) {
                                $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                            }
                        }
                        elseif($login_uesr_id == 252 || $login_uesr_id == 269 || $login_uesr_id == 270 || $login_uesr_id == 271) {
                            if($bvalue->process_id == 21 || $bvalue->process_id == 22) {
                                $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;
                            }
                        }
                        elseif($login_uesr_id == 256 || $login_uesr_id == 255) {
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
                $partner_process_list[$key]['id'] = $key;
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

            
            $final_list  = array();
            
            foreach($partner_process_list as $key => $value){
                $new_arr = array();

                $new_arr['id'] = $key;
                $new_arr['name'] = $value['name'];
                $new_arr['data'] = $value['data'];
                $new_arr['class'] = $value['class'];

                $final_list[] = $new_arr;
            }



          /*   print_r($final_list);
            dd(); */

            $final_data['pws'] = $final_list;


            
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
            /* echo "hii";
            dd(); */
            $audit_cyle_data = Auditcycle::where('client_id',$client_id)->orderby('start_date','desc')->groupBy('name')->get();
    
            $final_data['audit_cycle'] = $audit_cyle_data;
    
            
            $final_data['ov_scored']=$ov_scored;
    
            $final_data['start_date'] = $month_first_data;
            $final_data['end_date'] = $today;
            
            //return view('dashboards.client_welcome_dashboard_new',compact('final_data','month_first_data','today'));
            return response()->json(['status'=>200,'message'=>"Success",'data'=>$final_data], 200);



        } else {

            $data=array('status'=>0,'message'=>'Validation Errors','data' => "Please provide user id");
            return response(json_encode($data), 200);
        }

    }

    public function detail_dashboard(Request $request) {

       
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'partner_id' => 'required',
            'lob' => 'required',
            'location_id' => 'required',
            'process_id' => 'required',
            'date' => 'required',
		]);
		
		/* echo $request->email;
		dd(); */
        if($validator->fails()) {
            $data=array('status'=>0,'message'=>'Validation Errors','data' => $validator->errors());
            return response(json_encode($data), 200);
		} else {


            $user_id = $request->user_id;
            $user = User::where('id',$user_id)->first();
            
            $dates = explode(",", $request->date);


        $start_date = date_to_db($dates[0]);
        $end_date = date_to_db($dates[1]);

       /*  echo $end_date;
        dd(); */

        if($user->id == 42 || $user->id == 172 || $user->id == 198 || $user->id == 267 ) {
            $client_id=9;

        }

        if($user->hasRole('partner-admin'))
        {
            if($user->hasRole('partner-quality-head')){
                if($user->id == 267) {
                    $client_id=9;
                } else {
                    $client_id = $user->spoc_detail->partner->client_id;
                }
                
            } else {
                $client_id = $user->partner_admin_detail->client_id;
            }
            

        }elseif($user->hasRole('partner-training-head')||
                $user->hasRole('partner-operation-head')||
                $user->hasRole('partner-quality-head'))
        {
            if($user->id == 267) {
                $client_id =9;
            } else {
                $client_id = $user->spoc_detail->partner->client_id;
            }
            
        }elseif($user->hasRole('client')){
        	if($user->id == 42 || $user->id == 172 || $user->id == 198 ) {
        		$client_id=9;
        	} else {
        		$client_id = $user->client_detail->client_id;
        	}
            
        }     
        if($user->id == 44) {
            $partner_id = 1;
            $location_id = 2;
        } else {
            if($request->partner_id == 'all') {
                if(($user->id == 241 || $user->id == 242 || $user->id == 251 || $user->id == 246)) {
                    $partner_id=32;
                } 

                else if($user->id == 280 ) {
                    $partner_id=38;
                }

                else if($user->id == 248 || $user->id == 253) {
                    $partner_id=40;
                }
                elseif($user->id == 249 || $user->id == 250){
                    $partner_id=41;
                }

                elseif($user->id == 256 || $user->id == 255) {
                    $partner_id=44;
                }

                elseif($user->id == 139) {
                    $partner_id=38;
                }

                elseif ($user->id == 195) {
                   $partner_id=39;
                }

                elseif ($user->id == 254) {
                   $partner_id=43;
                }

                elseif ($user->id == 267) {
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

        if($user->id == 252 || $user->id == 269) {
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

        } elseif ($user->id == 270 || $user->id == 271) {
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
        
        if($client_id == 9) {
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
        

        $final_data['user_id']=$user->id;
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
            $final_data['user_id']=$user->id;
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

       


            if($user->hasRole('partner-admin'))
            {
                if(($user->id == 241 || $user->id == 242 || $user->id == 251 || $user->id == 246)) {
                    $all_partners = Partner::select('name','id')->where('id',32)->get();
                }  
                else if($user->id == 248 || $user->id == 253) {
                    $all_partners = Partner::select('name','id')->where('id',40)->get();
                }
                elseif($user->id == 139) {
                    $all_partners = Partner::select('name','id')->where('id',38)->get();
                }
                elseif($user->id == 270 || $user->id == 271 ||  $user->id == 280) {
                   $all_partners = Partner::select('name','id')->where('id',38)->orwhere('id',45)->get(); 
                }
                elseif($user->id == 282 ) {
                    $all_partners = Partner::select('name','id')->where('id',39)->orwhere('id',43)->get(); 
                 }
                elseif($user->id == 195) {
                    $all_partners = Partner::select('name','id')->where('id',39)->get();
                }
                elseif($user->id == 254) {
                    $all_partners = Partner::select('name','id')->where('id',43)->get();
                }
                elseif($user->id == 249 || $user->id == 250) {
                    $all_partners = Partner::select('name','id')->where('id',41)->get();
                }
                elseif($user->id == 252 || $user->id == 269) {
                    $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
                }
                elseif($user->id == 256 || $user->id == 255) {
                    $all_partners = Partner::select('name','id')->where('id',44)->get();
                }
                elseif ($user->id == 267) {
                   
                   $all_partners = Partner::select('name','id')->where('id',45)->get();
                } 
                else {
                $all_partners = Partner::where('id',$user->partner_admin_detail->id)->pluck('name','id'); 
                } 
            }elseif($user->hasRole('partner-training-head')||
                    $user->hasRole('partner-operation-head'))
                    
            {   
                if(($user->id == 241 || $user->id == 242 || $user->id == 251 || $user->id == 246)) {
                    $all_partners = Partner::select('name','id')->where('id',32)->get();
                }  
                else if($user->id == 248 || $user->id == 253) {
                    $all_partners = Partner::select('name','id')->where('id',40)->get();
                }



                elseif($user->id == 139) {
                    $all_partners = Partner::select('name','id')->where('id',38)->get();
                }
                elseif($user->id == 195) {
                    $all_partners = Partner::select('name','id')->where('id',39)->get();
                }
                elseif($user->id == 254) {
                    $all_partners = Partner::select('name','id')->where('id',43)->get();
                }
                elseif($user->id == 249 || $user->id == 250) {
                    $all_partners = Partner::select('name','id')->where('id',41)->get();
                }
                elseif($user->id == 252 || $user->id == 269) {
                    $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
                }
                elseif($user->id == 270 || $user->id == 271 ||  $user->id == 280) {
                    $all_partners = Partner::select('name','id')->where('id',38)->orwhere('id',45)->get(); 
                 }
                 elseif($user->id == 282 ) {
                     $all_partners = Partner::select('name','id')->where('id',39)->orwhere('id',43)->get(); 
                  }
                elseif($user->id == 256 || $user->id == 255) {
                    $all_partners = Partner::select('name','id')->where('id',44)->get();
                } 
                elseif ($user->id == 267) {
                   
                   $all_partners = Partner::select('name','id')->where('id',45)->get();
                } 
                else {
                $all_partners = Partner::where('id',$user->spoc_detail->partner_id)->pluck('name','id'); 
                } 
            }elseif( $user->hasRole('partner-quality-head') ){



                $partner = PartnersProcessSpoc::where('user_id',$user->id)->get();  

                $partnerid =  $partner[0]['partner_id']; 
                if ($user->id == 267) {
                   
                   $all_partners = Partner::where('id',45)->get();
                } else {
                    $all_partners = Partner::where('id',$partnerid)->get(); 
                }            

            }elseif($user->hasRole('client')){        
                if(($user->id == 241 || $user->id == 242 || $user->id == 251 || $user->id == 246)) {
                	$all_partners = Partner::select('name','id')->where('id',32)->get();
                }  elseif($user->id == 248 || $user->id == 253) {
                    $all_partners = Partner::select('name','id')->where('id',40)->get();
                }
                elseif($user->id == 249 || $user->id == 250) {
                    $all_partners = Partner::select('name','id')->where('id',41)->get();
                }
                elseif($user->id == 252 || $user->id == 269) {
                    $all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
                }
                elseif($user->id == 270 || $user->id == 271 || $user->id == 280) {
                    $all_partners = Partner::select('name','id')->where('id',38)->orwhere('id',45)->get(); 
                 }
                 elseif($user->id == 282 ) {
                     $all_partners = Partner::select('name','id')->where('id',39)->orwhere('id',43)->get(); 
                  }
                elseif($user->id == 256 || $user->id == 255) {
                    $all_partners = Partner::select('name','id')->where('id',44)->get();
                }
                elseif($user->id == 139) {
                    $all_partners = Partner::select('name','id')->where('id',38)->get();
                }
                elseif ($user->id == 195) {
                     $all_partners = Partner::select('name','id')->where('id',39)->get();
                }
                elseif ($user->id == 254) {
                     $all_partners = Partner::select('name','id')->where('id',43)->get();
                }
                elseif($user->id == 42 || $user->id == 172 || $user->id == 198) {
                	$all_partners = Partner::select('name','id')->where('client_id',9)->get();
                }
                else {

                	$all_partners = Partner::select('name','id')->where('client_id',$user->client_detail->client_id)->get();
                }   
            }

            if($user->id == 42 || $user->id == 172 || $user->id == 198) {
                $all_partners = Partner::select('name','id')->where('client_id',9)->get();
            }
              /*  echo $final_data['fatal_dialer_data']['with_fatal_score'];
               dd(); */
            /* echo $final_data['fatal_first_row_block']['total_fatal_count_sub_parameter'];
                dd(); */

            $final_data['all_partners'] = $all_partners;
            
            return response()->json(['status'=>200,'message'=>"Success",'data'=>$final_data], 200);

        }


        
        
    }
    /// For reference welcome dashboard

    public function get_qrc_lob_wise_welcome_dashboard(Request $request){
        // starts QRC

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'process_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        
        if($validator->fails()) {
            $data=array('status'=>0,'message'=>'Validation Errors','data' => $validator->errors());
            return response(json_encode($data), 200);
		} else {
            $user_id = $request->user_id;
            $process_id = $request->process_id;
            $month_first_data = $request->start_date;
            $today = $request->end_date;
    
            $user = User::where('id',$user_id)->first();
            
            
            if($user->id == 42 || $user->id == 172 || $user->id == 198) {
                $client_id=9;
            } else {
                $client_id = $user->client_detail->client_id;
            }
            /*
             $temp_qrc['query_count'] = Audit::where('client_id',$client_id)
                ->where('process_id',$process_id)
               ->where('qrc_2','Query')
               ->whereDate('audit_date','>=',$month_first_data)
               ->whereDate('audit_date','<=',$today)
               ->count();
           $temp_qrc['query_fatal_count'] = Audit::where('client_id',$client_id)
                ->where('process_id',$process_id)
                ->where('qrc_2','Query')
                ->where('is_critical',1)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();
           $temp_qrc['request_count'] = Audit::where('client_id',$client_id)
                ->where('process_id',$process_id)
                ->where('qrc_2','Request')
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();
           $temp_qrc['request_fatal_count'] = Audit::where('client_id',$client_id)
                ->where('process_id',$process_id)
                ->where('qrc_2','Request')
                ->where('is_critical',1)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();
           $temp_qrc['complaint_count'] = Audit::where('client_id',$client_id)
                ->where('process_id',$process_id)
                ->where('qrc_2','Complaint')
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();
           $temp_qrc['complaint_fatal_count'] = Audit::where('client_id',$client_id)
                ->where('process_id',$process_id)
                ->where('qrc_2','Complaint')
                ->where('is_critical',1)
                ->whereDate('audit_date','>=',$month_first_data)
                ->whereDate('audit_date','<=',$today)
                ->count();
            */
        
            if($user->id == 241 || $user->id == 242 || $user->id == 251 || $user->id == 246) { 
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
            else if($user->id == 248 || $user->id == 253) {
                            
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
            elseif($user->id == 249 || $user->id == 250) {
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
            elseif($user->id == 256 || $user->id == 255)  {
                        
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


            $final_list  = array();
            
            foreach($partner_process_list as $key => $value){
                $new_arr = array();

                $new_arr['id'] = $key;
                $new_arr['name'] = $value['name'];
                $new_arr['data'] = $value['data'];
                $new_arr['class'] = $value['class'];

                $final_list[] = $new_arr;
            }
        
            $final_data['pws'] = $final_list;
            // ends Process Wise Score
        
            // Partner & Location Wise Report    
                    
            return response()->json(['status'=>200,'message'=>"Success",'data'=>$final_data], 200);    
        }


    }
    
}
