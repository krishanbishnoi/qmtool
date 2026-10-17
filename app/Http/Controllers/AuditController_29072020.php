<?php

namespace App\Http\Controllers;
ini_set('memory_limit', '-1');
use App\Audit;
use App\AuditAlertBox;
use App\AuditParameterResult;
use App\AuditResult;
use App\Auditcycle;
use App\Partner;
use App\QmSheet;
use App\RawData;
use App\Rca2Mode;
use App\RcaMode;
use App\RcaType;
use App\Reason;
use App\ReasonType;
use App\TmpAudit;
use App\TmpAuditParameterResult;
use App\TmpAuditResult;
use App\TypeBScoringOption;
use Auth;
use Crypt;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function render_audit_sheet($qm_sheet_id)
    {
        //dd(all_non_scoring_obs_options(1));

        $data = QmSheet::with(['client'])->find(Crypt::decrypt($qm_sheet_id));
        //dd($data); die;
    	return view('audit.render_sheet',compact('qm_sheet_id','data'));
    }
    public function get_qm_sheet_details_for_audit($qm_sheet_id)
    {

    	$data = QmSheet::with(['client','process','parameter','parameter.qm_sheet_sub_parameter'])->find(Crypt::decrypt($qm_sheet_id));


    	$partners_list = Partner::where('client_id',$data->client_id)->pluck('name','id');
    	$final_data['partners_list'] = $partners_list;

        // Tiwari code
        $sk_data['delay1'] = $data->client->qc_time;
        $sk_data['delay2'] = $data->client->rebuttal_time;
        $sk_data['holiday'] = $data->client->holiday;
        $final_data['sk_client'] = $sk_data;

        //Tiwari code

        // Code by Abhilasha QRC replacement
        $getUniqueCallType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$data->client_id)->where('process_id',$data->process->id)->limit(3)->orderby('id','desc')->pluck('call_type');
        $final_data['client_id']=$data->client_id;
       $final_data['callTypeList'] = $getUniqueCallType;
       // Code by Abhilasha QRC replacement

        // get audit cycle
        $latest_audit_cycle = Auditcycle::where('qmsheet_id',$data->id)->orderBy('start_date')->first();
        $start_date = '';
        $end_date = '';
        if($latest_audit_cycle)
        {
            $start_date = $latest_audit_cycle->start_date;
            $end_date = $latest_audit_cycle->end_date;
        }
        $final_data['audit_cycle'] = $start_date." To ".$end_date;
        $final_data['audit_timestamp'] = date('Y-m-d H:i:s');
        // get audit cycle
        $temp_my_alloted_call_list = RawData::where('qa_id',Auth::user()->id)
          ->where('process_id',$data->process_id)
          ->where('dump_date','>=',$start_date)
          ->where('dump_date','<=',$end_date)
          ->where('status',0)->pluck('call_id','id');

        $my_alloted_call_list=[];
        foreach ($temp_my_alloted_call_list as $key => $value) {
            $my_alloted_call_list[] = ['key'=>$key,"value"=>$value];
        }

        $final_data['my_alloted_call_list'] = $my_alloted_call_list;

    	$final_data['sheet_details'] = $data->toArray();
    	//$final_data['type_b_scoring_option'] = TypeBScoringOption::all();
        $all_type_b_scoring_option = TypeBScoringOption::where('company_id',$data->company_id)->pluck('name','id');

        //process data
        $pds = [];
        foreach ($data->parameter as $key => $value_p) {
            $pds[$value_p->id]['name'] = $value_p->parameter;
            $pds[$value_p->id]['is_non_scoring']=$value_p->is_non_scoring;
            $total_parameter_weight = 0;
            $pds[$value_p->id]['is_fatal']=0;
            $pds[$value_p->id]['score']=0;
            $pds[$value_p->id]['score_with_fatal']=0;
            $pds[$value_p->id]['score_without_fatal']=0;
            $pds[$value_p->id]['temp_total_weightage']=0;
            foreach ($value_p->qm_sheet_sub_parameter as $key => $value_s) {

                $pds[$value_p->id]['subs'][$value_s->id]['name'] = $value_s->sub_parameter;
                $pds[$value_p->id]['subs'][$value_s->id]['details'] = $value_s->details;
                $pds[$value_p->id]['subs'][$value_s->id]['is_fatal']=0;
                $pds[$value_p->id]['subs'][$value_s->id]['is_non_scoring'] = $value_p->is_non_scoring;
                $pds[$value_p->id]['subs'][$value_s->id]['failure_reason'] = '';
                $pds[$value_p->id]['subs'][$value_s->id]['remark'] = '';
                $pds[$value_p->id]['subs'][$value_s->id]['orignal_weight'] = $value_s->weight;
                $pds[$value_p->id]['subs'][$value_s->id]['temp_weight'] = 0;
                $scoring_opts = [];
                if($value_p->is_non_scoring)
                {   
                    //total weight
                    $total_parameter_weight +=0;
                    if($value_s->non_scoring_option_group)
                    {                  
                        foreach (all_non_scoring_obs_options($value_s->non_scoring_option_group) as $key_ns => $value_ns) {
                                                $scoring_opts[$value_p->id."_".$value_s->id."_".$value_ns."_".$key_ns."_0"] = ["key"=>$value_p->id."_".$value_s->id."_".$value_ns."_".$key_ns."_0","value"=>$value_ns,"alert_box"=>null];
                                        }
                    }else
                    {
                        $scoring_opts=null;
                    }

                    
                }else
                {
                    //total weight
                    $total_parameter_weight +=$value_s->weight;
                    //total weight
                    $alert_box=null;

                    $all_reason_type_fail=null;
                    $all_reason_type_cric=null;
                    $all_reason_type_pwd=null;

                    if($value_s->pass)
                    { 
                        if($value_s->pass_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->pass_alert_box_id);
                        else
                            $alert_box = null;


                        $scoring_opts[$value_p->id."_".$value_s->id."_".$value_s->weight."_1_0"] = ["key"=>$value_p->id."_".$value_s->id."_".$value_s->weight.'_1_0',"value"=>"Pass","alert_box"=>$alert_box];
                   }

                    if($value_s->fail)
                     {
                        if($value_s->fail_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->fail_alert_box_id);
                        else
                            $alert_box = null;

                        if($value_s->fail_reason_types)
                        {
                            $temp_index_f = $value_p->id."_".$value_s->id."_"."0"."_2_1";
                            $temp_r_fail = ReasonType::find(explode(',',$value_s->fail_reason_types))->pluck('name','id');
                            foreach ($temp_r_fail as $keycc => $valuecc) {
                                $all_reason_type_fail[] = ["key"=>$value_p->id."_".$value_s->id."_".$keycc,"value"=>$valuecc]; 
                            }
                        }
                        else
                        {
                            $temp_index_f = $value_p->id."_".$value_s->id."_"."0"."_2_0";
                            $all_reason_type_fail = null;
                        }

                        $scoring_opts[$temp_index_f] = ["key"=>$temp_index_f,"value"=>"Fail","alert_box"=>$alert_box];
                    }

                    if($value_s->critical)
                     {
                        if($value_s->critical_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->critical_alert_box_id);
                        else
                            $alert_box = null;

                        if($value_s->critical_reason_types)
                        {
                            $temp_index_cri = $value_p->id."_".$value_s->id."_"."Critical"."_3_1";
                            $temp_cric = ReasonType::find(explode(',',$value_s->critical_reason_types))->pluck('name','id');
                            foreach ($temp_cric as $keycc => $valuecc) {
                                $all_reason_type_cric[] = ["key"=>$value_p->id."_".$value_s->id."_".$keycc,"value"=>$valuecc]; 
                            }
                        }
                        else
                        {
                            $temp_index_cri = $value_p->id."_".$value_s->id."_"."Critical"."_3_0";
                            $all_reason_type_cric = null;
                        }


                        $scoring_opts[$temp_index_cri] = ["key"=>$temp_index_cri,"value"=>"Critical","alert_box"=>$alert_box];
                    }

                    if($value_s->na)
                     {

                        if($value_s->na_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->na_alert_box_id);
                        else
                            $alert_box = null;


                        $scoring_opts[$value_p->id."_".$value_s->id."_"."N/A"."_4_0"] = ["key"=>$value_p->id."_".$value_s->id."_"."N/A"."_4_0","value"=>"N/A","alert_box"=>$alert_box];
                    }

                    if($value_s->pwd)
                     {  
                        if($value_s->pwd_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->pwd_alert_box_id);
                        else
                            $alert_box = null;

                        if($value_s->pwd_reason_types)
                        {
                            $temp_index_pwd = $value_p->id."_".$value_s->id."_".($value_s->weight/2)."_5_1";
                            $temp_pwd = ReasonType::find(explode(',',$value_s->pwd_reason_types))->pluck('name','id');
                            foreach ($temp_pwd as $keycc => $valuecc) {
                                $all_reason_type_pwd[] = ["key"=>$value_p->id."_".$value_s->id."_".$keycc,"value"=>$valuecc]; 
                            }
                        }
                        else
                        {
                            $temp_index_pwd = $value_p->id."_".$value_s->id."_".($value_s->weight/2)."_5_0";
                            $all_reason_type_pwd = null;
                        }


                        // $scoring_opts[$value_p->id."_".$value_s->id."_".($value_s->weight/2)."_5_0"] = ["key"=>$value_p->id."_".$value_s->id."_".($value_s->weight/2)."_5_0","value"=>"PWD","alert_box"=>$alert_box];

                        $scoring_opts[$temp_index_pwd] = ["key"=>$temp_index_pwd,"value"=>"PWD","alert_box"=>$alert_box];
                    }

                }


                $pds[$value_p->id]['subs'][$value_s->id]['options'] = $scoring_opts;
                $pds[$value_p->id]['subs'][$value_s->id]['score'] = 0;
                $pds[$value_p->id]['subs'][$value_s->id]['selected_options'] = null;
                $pds[$value_p->id]['subs'][$value_s->id]['selected_option_model'] = '';
                $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_fail'] = $all_reason_type_fail;
                $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_cric'] = $all_reason_type_cric;
                $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_pwd'] = $all_reason_type_pwd;
                $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type']=null;
                $pds[$value_p->id]['subs'][$value_s->id]['selected_reason_type']='';
                $pds[$value_p->id]['subs'][$value_s->id]['all_reasons']=null;
                $pds[$value_p->id]['subs'][$value_s->id]['selected_reason']='';

            }
            $pds[$value_p->id]['parameter_weight'] = $total_parameter_weight;
            
        }
        
        $final_data['simple_data'] = $pds;

        // rca starts
        $rca_type =  RcaType::where('company_id',$data->company_id)->where('process_id',$data->process_id)->pluck('name','id');
        $rca_mode =  RcaMode::where('company_id',$data->company_id)->where('process_id',$data->process_id)->pluck('name','id');

        $final_data['rca_type'] = $rca_type;
        $final_data['rca_mode'] = $rca_mode;
        // rca end

        // rca type 2starts
        $type_2_rca_mode = Rca2Mode::where('company_id',$data->company_id)->where('process_id',$data->process_id)->pluck('name','id');
        $final_data['type_2_rca_mode'] = $type_2_rca_mode;
        // rca type 2 ends
        

    	return response()->json(['status'=>200,'message'=>"Success",'data'=>$final_data,], 200);
    }
    public function get_raw_data_for_audit($comm_instance_id)
    {
    	$data = RawData::with('partner_detail')->find($comm_instance_id);
    	if($data)
    	{
    		return response()->json(['status'=>200,'message'=>"Call found.",'data'=>$data], 200);
    	}else
    	{
    		return response()->json(['status'=>404,'message'=>"Call not found."], 404);
    	}
    	
    }
    public function audited_list($qm_sheet_id)
    {
        $auditor_id = Auth::Id();
        $data = Audit::where('qm_sheet_id',Crypt::decrypt($qm_sheet_id))
                       ->whereHas('raw_data', function (Builder $query) use ($auditor_id) {
                        $query->where('qa_id',$auditor_id);
                       })
                       ->with(['raw_data'])
                       ->get();
        return view('audit.audit_list',compact('data'));
    }
    
    public function tmp_audited_list($qm_sheet_id)
    {
        $auditor_id = Auth::Id();
        $data = TmpAudit::where('qm_sheet_id',Crypt::decrypt($qm_sheet_id))
                       ->whereHas('raw_data', function (Builder $query) use ($auditor_id) {
                        $query->where('qa_id',$auditor_id);
                       })
                       ->with(['raw_data'])
                       ->get();
        return view('audit.tmp_audit_list',compact('data'));
    }
    public function store_audit(Request $request)
    {
        $raw_data_id = Audit::where('raw_data_id', $request->submission_data[0]['raw_data_id'])->get();


        if ($request->submit && sizeof($raw_data_id)==0) {
        
            $new_ar = new Audit;
            $new_ar->company_id = $request->submission_data[0]['company_id'];
            $new_ar->client_id = $request->submission_data[0]['client_id'];
            $new_ar->partner_id = $request->submission_data[0]['partner_id'];
            $new_ar->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
            $new_ar->process_id = $request->submission_data[0]['process_id'];
            $new_ar->raw_data_id = $request->submission_data[0]['raw_data_id'];
            $new_ar->audited_by_id = Crypt::decrypt($request->submission_data[0]['audited_by_id']);
            $new_ar->is_critical = $request->submission_data[0]['is_critical'];
            $new_ar->overall_score = $request->submission_data[0]['overall_score'];
            //$new_ar->audit_date = $request->submission_data[0]['audit_date'];
            $new_ar->audit_date = date('Y-m-d H:i:s');
            $new_ar->case_id = $request->submission_data[0]['case_id'];
            $new_ar->overall_summary = $request->submission_data[0]['overall_summary'];
            $new_ar->refrence_number = $request->submission_data[0]['refrence_number'];

            $new_ar->qrc_2 = $request->submission_data[0]['qrc_2'];
            $new_ar->language_2 = $request->submission_data[0]['language_2'];

            $new_ar->type_id = $request->submission_data[0]['selected_rca_type'];

            $new_ar->mode_id = $request->submission_data[0]['selected_rca_mode'];
            $new_ar->rca1_id = $request->submission_data[0]['selected_rca1'];
            $new_ar->rca2_id = $request->submission_data[0]['selected_rca2'];
            $new_ar->rca3_id = $request->submission_data[0]['selected_rca3'];
            $new_ar->rca_other_detail = $request->submission_data[0]['rca_other_detail'];

            $new_ar->type_2_rca_mode_id = $request->submission_data[0]['selected_type_2_rca_mode'];
            $new_ar->type_2_rca1_id = $request->submission_data[0]['selected_type_2_rca1'];
            $new_ar->type_2_rca2_id = $request->submission_data[0]['selected_type_2_rca2'];
            $new_ar->type_2_rca3_id = $request->submission_data[0]['selected_type_2_rca3'];
            $new_ar->type_2_rca_other_detail = $request->submission_data[0]['type_2_rca_other_detail'];

            $new_ar->good_bad_call = $request->submission_data[0]['good_bad_call'];
            $new_ar->good_bad_call_file = $request->submission_data[0]['good_bad_call_file'];

            if($request->submission_data[0]['is_critical']==1)
                $new_ar->with_fatal_score_per = 0;
            else
                $new_ar->with_fatal_score_per = $request->submission_data[0]['overall_score'];


            //feedback
            if(!is_null($request->submission_data[0]['feedback_to_agent']))
            {
                $new_ar->feedback_status = 1;
                $new_ar->feedback = $request->submission_data[0]['feedback_to_agent'];
            }
            //feedback

            // function to fetch and save date
            $date = date('Y-m-d H:i:s');
            $delay1 = $request->submission_data[0]['delay1'];
            $delay2 = $request->submission_data[0]['delay2'];
            $holiday = $request->submission_data[0]['holiday'];
            $result = skipHoliday(strtotime($date),$holiday,$delay1,$delay2);
            $new_ar->qc_tat = $result[0];
            $new_ar->rebuttal_tat = $result[1];
            // function end



            $new_ar->save();

            // update raw data status
            $raw_data = RawData::find($request->submission_data[0]['raw_data_id']);
            $raw_data->customer_name = $request->submission_data[0]['customer_name'];
            $raw_data->phone_number = $request->submission_data[0]['customer_phone'];
            $raw_data->disposition = $request->submission_data[0]['disposition'];
            $raw_data->call_time = $request->submission_data[0]['call_time'];
            $raw_data->call_duration = $request->submission_data[0]['call_duration'];
            $raw_data->call_sub_type = $request->submission_data[0]['call_sub_type'];
            $raw_data->campaign_name = $request->submission_data[0]['campaign_name'];
            $raw_data->status=1;
            $raw_data->save();

            if($new_ar->id)
            {
               // store parameter wise data
                foreach ($request->parameters as $key => $value) {

                    $new_arb = new AuditParameterResult;
                    $new_arb->audit_id =  $new_ar->id;
                    $new_arb->parameter_id = $key;
                    $new_arb->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
                    $new_arb->orignal_weight = $value['parameter_weight'];
                    $new_arb->temp_weight = $value['temp_total_weightage'];
                    $new_arb->with_fatal_score = $value['score_with_fatal'];
                    $new_arb->without_fatal_score = $value['score_without_fatal'];

                    if($value['temp_total_weightage']!=0)
                    {
                        $new_arb->with_fatal_score_per = ($value['score_with_fatal'] / $value['temp_total_weightage'])*100;
                        $new_arb->without_fatal_score_pre = ($value['score_without_fatal'] / $value['temp_total_weightage'])*100;
                    }
                    $new_arb->is_critical = $value['is_fatal'];

                    $new_arb->save();

                    // store sub parameter wise data
                    foreach ($value['subs'] as $key_sb => $value_sb) {
                        if($value_sb['selected_option_model']!=''||$value_sb['selected_option_model']!=null);
                        {
                            $new_arc = new AuditResult;
                            $new_arc->audit_id =  $new_ar->id;
                            $new_arc->parameter_id = $key;
                            $new_arc->sub_parameter_id = $key_sb;
                            $new_arc->is_critical = $value_sb['is_fatal'];
                            $new_arc->is_non_scoring = $value_sb['is_non_scoring'];
                            $temp_selected_opt = explode("_",$value_sb['selected_option_model']);
                            
                            if(count($temp_selected_opt)==5)
                            {
                                $new_arc->selected_option = $temp_selected_opt[3];

                                if($temp_selected_opt[3]==2||$temp_selected_opt[3]==3)
                                {
                                    if(isset($value_sb['selected_reason_type'])==1&&$value_sb['selected_reason_type']!=''&&isset($value_sb['selected_reason'])==1&&$value_sb['selected_reason']!='')
                                    {
                                        $temp_selected_reason_type = explode("_",$value_sb['selected_reason_type']);
                                        $new_arc->reason_type_id = $temp_selected_reason_type[2];
                                        $new_arc->reason_id = $value_sb['selected_reason'];
                                    }
                                }

                            }
                            else
                            {
                                $new_arc->selected_option = 0;
                                $new_arc->reason_type_id = null;
                                $new_arc->reason_id = null;
                            }


                            $new_arc->score = $value_sb['score'];
                            $new_arc->after_audit_weight = $value_sb['temp_weight'];

                            
                            $new_arc->remark = $value_sb['remark'];
                            $new_arc->save();
                        }
                    }

                }

            }
            return response()->json(['status'=>200,'message'=>"Audit saved successfully."], 200);
        } else {
            $new_ar = new TmpAudit;
            $new_ar->company_id = $request->submission_data[0]['company_id'];
            $new_ar->client_id = $request->submission_data[0]['client_id'];
            $new_ar->partner_id = $request->submission_data[0]['partner_id'];
            $new_ar->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
            $new_ar->process_id = $request->submission_data[0]['process_id'];
            $new_ar->raw_data_id = $request->submission_data[0]['raw_data_id'];
            $new_ar->audited_by_id = Crypt::decrypt($request->submission_data[0]['audited_by_id']);
            $new_ar->is_critical = $request->submission_data[0]['is_critical'];
            $new_ar->overall_score = $request->submission_data[0]['overall_score'];
            //$new_ar->audit_date = $request->submission_data[0]['audit_date'];
            $new_ar->audit_date = date('Y-m-d H:i:s');
            $new_ar->case_id = $request->submission_data[0]['case_id'];
            $new_ar->overall_summary = $request->submission_data[0]['overall_summary'];
            $new_ar->refrence_number = $request->submission_data[0]['refrence_number'];

            $new_ar->qrc_2 = $request->submission_data[0]['qrc_2'];
            $new_ar->language_2 = $request->submission_data[0]['language_2'];

            $new_ar->type_id = $request->submission_data[0]['selected_rca_type'];

            $new_ar->mode_id = $request->submission_data[0]['selected_rca_mode'];
            $new_ar->rca1_id = $request->submission_data[0]['selected_rca1'];
            $new_ar->rca2_id = $request->submission_data[0]['selected_rca2'];
            $new_ar->rca3_id = $request->submission_data[0]['selected_rca3'];
            $new_ar->rca_other_detail = $request->submission_data[0]['rca_other_detail'];

            $new_ar->type_2_rca_mode_id = $request->submission_data[0]['selected_type_2_rca_mode'];
            $new_ar->type_2_rca1_id = $request->submission_data[0]['selected_type_2_rca1'];
            $new_ar->type_2_rca2_id = $request->submission_data[0]['selected_type_2_rca2'];
            $new_ar->type_2_rca3_id = $request->submission_data[0]['selected_type_2_rca3'];
            $new_ar->type_2_rca_other_detail = $request->submission_data[0]['type_2_rca_other_detail'];

            $new_ar->good_bad_call = $request->submission_data[0]['good_bad_call'];
            $new_ar->good_bad_call_file = $request->submission_data[0]['good_bad_call_file'];

            if($request->submission_data[0]['is_critical']==1)
                $new_ar->with_fatal_score_per = 0;
            else
                $new_ar->with_fatal_score_per = $request->submission_data[0]['overall_score'];


            //feedback
            if(!is_null($request->submission_data[0]['feedback_to_agent']))
            {
                $new_ar->feedback_status = 1;
                $new_ar->feedback = $request->submission_data[0]['feedback_to_agent'];
            }
            //feedback



            $new_ar->save();

            // update raw data status
            $raw_data = RawData::find($request->submission_data[0]['raw_data_id']);
            $raw_data->customer_name = $request->submission_data[0]['customer_name'];
            $raw_data->phone_number = $request->submission_data[0]['customer_phone'];
            $raw_data->disposition = $request->submission_data[0]['disposition'];
            $raw_data->call_time = $request->submission_data[0]['call_time'];
            $raw_data->call_duration = $request->submission_data[0]['call_duration'];
            $raw_data->call_sub_type = $request->submission_data[0]['call_sub_type'];
            $raw_data->campaign_name = $request->submission_data[0]['campaign_name'];
            $raw_data->status=1;
            $raw_data->save();

            if($new_ar->id)
            {
               // store parameter wise data
                foreach ($request->parameters as $key => $value) {

                    $new_arb = new TmpAuditParameterResult;
                    $new_arb->audit_id =  $new_ar->id;
                    $new_arb->parameter_id = $key;
                    $new_arb->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
                    $new_arb->orignal_weight = $value['parameter_weight'];
                    $new_arb->temp_weight = $value['temp_total_weightage'];
                    $new_arb->with_fatal_score = $value['score_with_fatal'];
                    $new_arb->without_fatal_score = $value['score_without_fatal'];

                    if($value['temp_total_weightage']!=0)
                    {
                        $new_arb->with_fatal_score_per = ($value['score_with_fatal'] / $value['temp_total_weightage'])*100;
                        $new_arb->without_fatal_score_pre = ($value['score_without_fatal'] / $value['temp_total_weightage'])*100;
                    }
                    $new_arb->is_critical = $value['is_fatal'];

                    $new_arb->save();

                    // store sub parameter wise data
                    foreach ($value['subs'] as $key_sb => $value_sb) {
                        if($value_sb['selected_option_model']!=''||$value_sb['selected_option_model']!=null);
                        {
                            $new_arc = new TmpAuditResult;
                            $new_arc->audit_id =  $new_ar->id;
                            $new_arc->parameter_id = $key;
                            $new_arc->sub_parameter_id = $key_sb;
                            $new_arc->is_critical = $value_sb['is_fatal'];
                            $new_arc->is_non_scoring = $value_sb['is_non_scoring'];
                            $temp_selected_opt = explode("_",$value_sb['selected_option_model']);
                            
                            if(count($temp_selected_opt)==5)
                            {
                                $new_arc->selected_option = $temp_selected_opt[3];

                                if($temp_selected_opt[3]==2||$temp_selected_opt[3]==3)
                                {
                                    if(isset($value_sb['selected_reason_type'])==1&&$value_sb['selected_reason_type']!=''&&isset($value_sb['selected_reason'])==1&&$value_sb['selected_reason']!='')
                                    {
                                        $temp_selected_reason_type = explode("_",$value_sb['selected_reason_type']);
                                        $new_arc->reason_type_id = $temp_selected_reason_type[2];
                                        $new_arc->reason_id = $value_sb['selected_reason'];
                                    }
                                }

                            }
                            else
                            {
                                $new_arc->selected_option = 0;
                                $new_arc->reason_type_id = null;
                                $new_arc->reason_id = null;
                            }


                            $new_arc->score = $value_sb['score'];
                            $new_arc->after_audit_weight = $value_sb['temp_weight'];

                            
                            $new_arc->remark = $value_sb['remark'];
                            $new_arc->save();
                        }
                    }

                }

            }
            return response()->json(['status'=>200,'message'=>"Tmp Audit saved successfully."], 200);
        }
    }
    public function get_reasons_by_type($type_id)
    {
        $all_reasons = Reason::where('reason_type_id',$type_id)->pluck('name','id');
        return response()->json(['status'=>200,'message'=>".",'data'=>$all_reasons], 200);
    }

    public function get_raw_data_on_data_range(Request $request)
    {
        $temp_data = explode(' To ', $request->date_range);

        $temp_my_alloted_call_list = RawData::where('qa_id',Auth::user()->id)
          ->where('process_id',$request->process_id)
          ->where('dump_date','>=',$temp_data[0])
          ->where('dump_date','<=',$temp_data[1])
          ->where('status',0)->pluck('call_id','id');

        $my_alloted_call_list=[];
        foreach ($temp_my_alloted_call_list as $key => $value) {
            $my_alloted_call_list[] = ['key'=>$key,"value"=>$value];
        }
        return response()->json(['status'=>200,'message'=>".",'data'=>$my_alloted_call_list,'dd'=>$temp_data], 200);
    }

    

}
