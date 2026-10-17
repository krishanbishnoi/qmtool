<?php

namespace App\Http\Controllers;

ini_set('memory_limit', '-1');

use App\Audit;
use App\AuditAlertBox;
use App\AuditParameterResult;
use App\AuditResult;
use App\BajajAhtCalls;
use App\Client;
use App\FailedQc;
use App\QcDefectSubParameter;
use App\QmSheetParameter;
use App\RawData;
use App\Reason;
use App\ReasonType;
use App\Rebuttal;
use Auth;
use Crypt;
use Illuminate\Http\Request;
use Validator;

use App\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Facades\Mail;
use App\Mail\QcFeedback;
use App\RoleUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;


class QcController extends Controller
{
    public function audits(Request $request)
    {
        $client_id = $request->client_id;
        $start_date = date_to_db($request->start_date);
        $end_date = date_to_db($request->end_date);

        if (Auth::user()->id == 293) {
            $client_list = Client::where('id', 13)->pluck('name', 'id');
        } else {
            $client_list = Client::where('company_id', Auth::user()->company_id)->pluck('name', 'id');
        }


        if ($client_id == null) {
            $unique_partner = [];
            $unique_location = [];
            $unique_agent = [];
            $unique_qa = [];

            $audits = [];
        } else {
            $unique_partner[''] = 'Select one!';
            $unique_location[''] = 'Select one!';
            $unique_agent[''] = 'Select one!';
            $unique_qa[''] = 'Select one!';

            $audits = [];


            $client_data = Client::find(Crypt::decrypt($client_id));
            $client_qc_time = $client_data->qc_time;
            $current_date_time = date('Y-m-d H:i:s');

            // get audits
            $qc_process = Auth::user()->my_processes;

            if ($client_data->id == 1 || $client_data->id == 9 || $client_data->id == 13 || $client_data->id == 23 ||$client_data->id == 22 || $client_data->id == 17 || $client_data->id == 15) {
                $audits = Audit::where('client_id', $client_data->id)
                    //->where('process_id',$qc_process[0]->master_id)       
                    //  ->where('qc_tat','>=',$current_date_time) 
                    ->whereDate('audit_date', '>=', $start_date)
                    ->whereDate('audit_date', '<=', $end_date)
                    ->with(['raw_data', 'raw_data.partner_location', 'raw_data.partner_location.location_detail', 'partner'])
                    ->get();
            } else {
                $audits = Audit::where('client_id', $client_data->id)
                    //->where('process_id',$qc_process[0]->master_id)       
                    ->where('qc_tat', '>=', $current_date_time)
                    ->whereDate('audit_date', '>=', $start_date)
                    ->whereDate('audit_date', '<=', $end_date)
                    ->with(['raw_data', 'raw_data.partner_location', 'raw_data.partner_location.location_detail', 'partner'])
                    ->get();
            }



            // $audits = Audit::where('client_id',$client_data->id)->where('process_id',$qc_process[0]->master_id)->with(['raw_data','raw_data.partner_location','partner'])->get();

            $temp_unique_partner = $audits->unique('partner_id');
            foreach ($temp_unique_partner as $key => $value) {
                $unique_partner[$value->partner->name] = $value->partner->name;
            }
            foreach ($audits as $key => $value) {
                if (isset($value->raw_data->location_data)) {

                    $unique_location[$value->raw_data->location_data->name] = $value->raw_data->location_data->name;
                }
            }
            foreach ($audits as $key => $value) {
                try {
                    $unique_agent[$value->raw_data->agent_name] = $value->raw_data->agent_name;
                } catch (\Exception $th) {
                    // dd($value);
                }
            }

            $temp_unique_qa = $audits->unique('audited_by_id');
            foreach ($temp_unique_qa as $key => $value) {
                $unique_qa[$value->qa_qtl_detail->name] = $value->qa_qtl_detail->name;
            }
        }

        return view('qc.audit_list', compact('client_list', 'unique_partner', 'unique_location', 'unique_agent', 'unique_qa', 'audits'));
    }
    public function single_audit_detail($audit_id){
        $audit_data = Audit::with(['audit_parameter_result', 'audit_results', 'audit_results.reason_type', 'audit_results.reason'])->find(Crypt::decrypt($audit_id));
        $raw_data = RawData::find($audit_data->raw_data_id);
        $qm_sheet_para_data = QmSheetParameter::with(['qm_sheet_sub_parameter'])->where('qm_sheet_id', $audit_data->qm_sheet_id)->get();

        $audit_sp_results = $audit_data->audit_results;
        $final_data = [];
        $all_sub_parameters = [];
        foreach ($qm_sheet_para_data as $key => $value) {

            //all subparameters
            foreach ($value->qm_sheet_sub_parameter as $sskey => $ssvalue) {
                $all_sub_parameters[] = ["key" => $value->id . "_" . $ssvalue->id, "value" => $ssvalue->sub_parameter];
            }
            //all subparameters


            $final_data[$value->id]['name'] = $value->parameter;
            $final_data[$value->id]['id'] = $value->id;
            if ($value->is_non_scoring) {
                foreach ($value->qm_sheet_sub_parameter as $skey => $svalue) {
                    $t_1 = $audit_sp_results->where('parameter_id', $value->id)->where('sub_parameter_id', $svalue->id)->toArray();
                    if (count($t_1) > 0) {
                        $temp_result = $t_1[array_key_first($t_1)];
                        $final_data[$value->id]['sp'][] = ['is_non_scoring' => $value->is_non_scoring, 'id' => $svalue->id, 'name' => $svalue->sub_parameter, 'detail' => $svalue->details, 'selected_option' => return_non_scoring_observation($temp_result['selected_option']), 'scored' => $temp_result['score'], 'reason_type' => "-", 'reason' => '-', 'remark' => $temp_result['remark']];
                    }
                }
            } else {
                foreach ($value->qm_sheet_sub_parameter as $skey => $svalue) {
                    // try {
                    //     $t_1 = $audit_sp_results->where('parameter_id',$value->id)->where('sub_parameter_id',$svalue->id)->toArray();
                    //     $temp_result = $t_1[array_key_first($t_1)];
                    // } catch (\Throwable $th) {
                    //     $t_1 = ["id" => 15464978,
                    //     "audit_id" => 1284911,
                    //     "parameter_id" => 815,
                    //     "sub_parameter_id" => 2383,
                    //     "is_critical" => 0,
                    //     "is_non_scoring" => 0,
                    //     "selected_option" => 1,
                    //     "score" => 20.0,
                    //     "after_audit_weight" => 20.0,
                    //     "failure_reason" => null,
                    //     "remark" => null,
                    //     "created_at" => "2024-04-24 00:01:43",
                    //     "updated_at" => "2024-04-24 00:01:43",
                    //     "reason_type_id" => 0,
                    //     "reason_id" => 0,
                    //     "screenshot" => null,
                    //     "qc_to_qa_feedback" => null,
                    //     "qc_to_qa_artifact" => null,
                    //     "is_partial_marking" => 0,
                    //     "reason_type" => null,
                    //     "reason" => null];
                    // }
                    $t_1 = $audit_sp_results->where('parameter_id', $value->id)->where('sub_parameter_id', $svalue->id)->toArray();
                    // $temp_result = $t_1[array_key_first($t_1)];
                    if (!empty($t_1)) {
                        $first_key = array_key_first($t_1);
                        $temp_result = $t_1[$first_key];
                    } else {
                        $temp_result = null;
                    }
                    // $t_1 = $audit_sp_results->where('parameter_id',$value->id)->where('sub_parameter_id',$svalue->id)->toArray();
                    //         $temp_result = $t_1[array_key_first($t_1)];

                    if (isset($temp_result['reason_type']['name'])) {
                        //print_r(json_encode($audit_data)); die;
                    } else {
                        $temp_result['reason']['name'] = "-";
                        $temp_result['reason_type']['name'] = "-";
                        //print_r(json_encode($audit_data)); die;
                    }
                    if (!isset($temp_result['reason']['name'])) {
                        $temp_result['reason']['name'] = "-";
                        $temp_result['reason_type']['name'] = "-";
                    }
                    // try {
                    //     $final_data[$value->id]['sp'][] = ['is_non_scoring'=>$value->is_non_scoring,'id'=>$svalue->id,'name'=>$svalue->sub_parameter,'detail'=>$svalue->details,'selected_option'=>return_general_observation($temp_result['selected_option']),'scored'=>$temp_result['score'],'reason_type'=>$temp_result['reason_type']['name'],'reason'=>$temp_result['reason']['name'],'remark'=>$temp_result['remark']];
                    // } catch (\Throwable $th) {
                    //     $final_data[$value->id]['sp'][] = ['is_non_scoring'=>$value->is_non_scoring,'id'=>$svalue->id,'name'=>$svalue->sub_parameter,'detail'=>$svalue->details,'selected_option'=>'Pass','scored'=>20,'reason_type'=>$temp_result['reason_type']['name'],'reason'=>$temp_result['reason']['name'],'remark'=>null];
                    // }
                    $final_data[$value->id]['sp'][] = ['is_non_scoring' => $value->is_non_scoring, 'id' => $svalue->id, 'name' => $svalue->sub_parameter, 'detail' => $svalue->details, 'selected_option' => return_general_observation($temp_result['selected_option'] ?? 1), 'scored' => $temp_result['score'] ?? 0, 'reason_type' => $temp_result['reason_type']['name'], 'reason' => $temp_result['reason']['name'], 'remark' => $temp_result['remark'] ?? ''];
                }
            }
        }
        // return response()->json(['status'=>200,'message'=>".",'data'=>$final_data], 200); die;
        $rebuttal_data = Rebuttal::where('raised_by_user_id', Auth::user()->id)->where('audit_id', $audit_data->id)->with(['parameter', 'sub_parameter'])->get();

        $aht_data = BajajAhtCalls::where('audit_id', $audit_data->id)->get();
        if ($audit_data->good_bad_call_file) {
            $path_name = str_replace(["https://qmtool.s3.ap-south-1.amazonaws.com/", "https://qmtool1.s3.ap-south-1.amazonaws.com/"], "", $audit_data->good_bad_call_file);
            $url = Storage::disk('s3')->temporaryUrl(
                $path_name,
                now()->addMinutes(8640) //Minutes for which the signature will stay valid
            );
        } else {
            $url = "";
        }
        return view('qc.qc_single_audit_detail', compact(['raw_data', 'audit_data', 'final_data', 'all_sub_parameters', 'rebuttal_data', 'url', 'aht_data']));
    }
    public function get_details_for_update_audit_sub_parameter(Request $request)
    {

        $pdata = AuditParameterResult::where('audit_id', $request->audit_id)
            ->where('parameter_id', $request->parameter_id)
            ->with('parameter_detail')
            ->first();
        $sdata = AuditResult::where('audit_id', $request->audit_id)
            ->where('parameter_id', $request->parameter_id)
            ->where('sub_parameter_id', $request->sub_parameter_id)
            ->with('sub_parameter_detail')
            ->first();
        $intro_data['p_name'] = $pdata->parameter_detail->parameter;
        $intro_data['p_id'] = $pdata->parameter_detail->id;
        $intro_data['sp_id'] = $sdata->sub_parameter_detail->id;
        $intro_data['s_name'] = $sdata->sub_parameter_detail->sub_parameter;
        $intro_data['weight'] = $sdata->sub_parameter_detail->weight;
        $intro_data['is_critical'] = $sdata->is_critical;

        $intro_data['s_detail'] = $sdata->sub_parameter_detail->details;
        $intro_data['remarks'] = $sdata->remark;
        $intro_data['reason_type_id'] = $sdata->reason_type_id;
        $intro_data['reason_id'] = $sdata->reason_id;

        $intro_data['selected_option'] = $sdata->selected_option;

        $intro_data['qc_to_qa_feedback'] = $sdata->qc_to_qa_feedback;
        $intro_data['qc_to_qa_artifact'] = $sdata->qc_to_qa_artifact;
        $intro_data['screenshot'] = ($sdata->screenshot) ? $sdata->screenshot : '';

        $sub_parameter_data = $sdata->sub_parameter_detail;
        $role_auth_user = Auth::user()->id;
        $role_id = RoleUser::where('user_id', $role_auth_user)->pluck('role_id');
        //  dd($sub_parameter_data);

        if ($pdata->parameter_detail->is_non_scoring == 1) {
            if ($sdata->sub_parameter_detail->non_scoring_option_group) {
                foreach (all_non_scoring_obs_options($sdata->sub_parameter_detail->non_scoring_option_group) as $key_ns => $value_ns) {
                    $scoring_opts["0_" . $key_ns . "_0"] = ["key" => "0_" . $key_ns . "_0", "value" => $value_ns, "alert_box" => null];
                }
            } else {
                $scoring_opts = null;
            }

            $intro_data['selected_observation'] = "0_" . $sdata->selected_option . "_0";

            $intro_data['score_view'] = 0;
            $intro_data['scored'] = $sdata->score;
            $intro_data['after_audit_weight'] = 0;
            $intro_data['reason_type'] = [];

            $reason_type_master = [];
            $reasons_master = [];
        } else {

            if ($sub_parameter_data->pass) {
                if ($sub_parameter_data->pass_alert_box_id)
                    $alert_box = AuditAlertBox::find($sub_parameter_data->pass_alert_box_id);
                else
                    $alert_box = null;

                $scoring_opts[$sub_parameter_data->weight . "_1_0"] = ["key" => $sub_parameter_data->weight . '_1_0', "value" => "Pass", "alert_box" => $alert_box];

                if ($sdata->selected_option == 1) {
                    $intro_data['selected_observation'] = $sub_parameter_data->weight . "_1_0";
                }
            }

            if ($sub_parameter_data->fail) {
                if ($sub_parameter_data->fail_alert_box_id)
                    $alert_box = AuditAlertBox::find($sub_parameter_data->fail_alert_box_id);
                else
                    $alert_box = null;

                if ($sub_parameter_data->fail_reason_types) {
                    $temp_index_f = "0" . "_2_1";
                    $temp_r_fail = ReasonType::find(explode(',', $sub_parameter_data->fail_reason_types))->pluck('name', 'id');
                    foreach ($temp_r_fail as $keycc => $valuecc) {
                        $all_reason_type_fail[] = ["key" => $keycc, "value" => $valuecc];
                    }
                } else {
                    $temp_index_f = "0" . "_2_0";
                    $all_reason_type_fail = [];
                }

                $scoring_opts[$temp_index_f] = ["key" => $temp_index_f, "value" => "Fail", "alert_box" => $alert_box];

                if ($sdata->selected_option == 2) {
                    $intro_data['selected_observation'] = $temp_index_f;
                }
            } else {
                $all_reason_type_fail = [];
            }

            if ($sub_parameter_data->critical) {
                if ($sub_parameter_data->critical_alert_box_id)
                    $alert_box = AuditAlertBox::find($sub_parameter_data->critical_alert_box_id);
                else
                    $alert_box = null;

                if ($sub_parameter_data->critical_reason_types) {
                    $temp_index_cri = "Critical" . "_3_1";
                    $temp_cric = ReasonType::find(explode(',', $sub_parameter_data->critical_reason_types))->pluck('name', 'id');
                    foreach ($temp_cric as $keycc => $valuecc) {
                        $all_reason_type_cric[] = ["key" => $keycc, "value" => $valuecc];
                    }
                } else {
                    $temp_index_cri = "Critical" . "_3_0";
                    $all_reason_type_cric = null;
                }
                $scoring_opts[$temp_index_cri] = ["key" => $temp_index_cri, "value" => "Critical", "alert_box" => $alert_box];

                if ($sdata->selected_option == 3) {
                    $intro_data['selected_observation'] = $temp_index_cri;
                }
            } else {
                $all_reason_type_cric = [];
            }

            if ($sub_parameter_data->na) {

                if ($sub_parameter_data->na_alert_box_id)
                    $alert_box = AuditAlertBox::find($sub_parameter_data->na_alert_box_id);
                else
                    $alert_box = null;

                $scoring_opts["N/A" . "_4_0"] = ["key" => "N/A" . "_4_0", "value" => "N/A", "alert_box" => $alert_box];

                if ($sdata->selected_option == 4) {
                    $intro_data['selected_observation'] = "N/A" . "_4_0";
                }
            }

            if ($sub_parameter_data->pwd) {
                if ($sub_parameter_data->pwd_alert_box_id)
                    $alert_box = AuditAlertBox::find($sub_parameter_data->pwd_alert_box_id);
                else
                    $alert_box = null;

                if ($sub_parameter_data->pwd_reason_types) {
                    $temp_index_pwd = "Pwd" . "_5_1";
                    $temp_cric = ReasonType::find(explode(',', $sub_parameter_data->pwd_reason_types))->pluck('name', 'id');
                    foreach ($temp_cric as $keycc => $valuecc) {
                        $all_reason_type_pwd[] = ["key" => $keycc, "value" => $valuecc];
                    }
                } else {
                    $temp_index_pwd = "Pwd" . "_5_0";
                    $all_reason_type_pwd = null;
                }

                $scoring_opts[($sub_parameter_data->weight / 2) . "_5_0"] = ["key" => ($sub_parameter_data->weight / 2) . "_5_0", "value" => "PWD", "alert_box" => $alert_box];

                if ($sdata->selected_option == 5) {
                    $intro_data['selected_observation'] = ($sub_parameter_data->weight / 2) . "_5_0";
                }
            } else {
                $all_reason_type_pwd = [];
            }
            //start of partial
            if ($sub_parameter_data->partial_marking) {
                if ($sub_parameter_data->partial_marking)
                    $alert_box = AuditAlertBox::find($sub_parameter_data->partial_marking);
                else
                    $alert_box = null;

                if ($sub_parameter_data->partial_marking) {
                    $temp_index_partial_marking = "partial_marking" . "_6_1";
                    $temp_cric = ReasonType::find(explode(',', $sub_parameter_data->partial_marking_reason_types))->pluck('name', 'id');
                    foreach ($temp_cric as $keycc => $valuecc) {
                        $all_reason_type_partial[] = ["key" => $keycc, "value" => $valuecc];
                    }
                } else {
                    $temp_index_partial_marking = "partial_marking" . "_6_0";
                    $all_reason_type_partial = null;
                }

                $scoring_opts[($sub_parameter_data->weight / 2) . "_6_0"] = ["key" => ($sub_parameter_data->weight / 2) . "_6_0", "value" => "Partial Marking", "alert_box" => $alert_box];

                if ($sdata->selected_option == 6) {
                    $intro_data['selected_observation'] = ($sub_parameter_data->weight / 2) . "_6_0";
                }
            } else {
                $all_reason_type_partial = [];
            }

            //end of partial

            switch ($sdata->selected_option) {
                case 1: {
                        $intro_data['score_view'] = $sdata->score;
                        $intro_data['scored'] = $sdata->score;
                        $intro_data['after_audit_weight'] = $sdata->sub_parameter_detail->weight;
                        $intro_data['reason_type'] = [];
                        break;
                    }
                case 2: {
                        $intro_data['score_view'] = 0;
                        $intro_data['scored'] = $sdata->score;
                        $intro_data['after_audit_weight'] = $sdata->sub_parameter_detail->weight;
                        $intro_data['reason_type'] = $all_reason_type_fail;
                        break;
                    }
                case 3: {
                        $intro_data['score_view'] = "Critical";
                        $intro_data['scored'] = $sdata->score;
                        $intro_data['after_audit_weight'] = $sdata->sub_parameter_detail->weight;
                        $intro_data['reason_type'] = $all_reason_type_cric;
                        break;
                    }
                case 4: {
                        $intro_data['score_view'] = "N/A";
                        $intro_data['scored'] = $sdata->score;
                        $intro_data['after_audit_weight'] = 0;
                        $intro_data['reason_type'] = [];
                        break;
                    }
                case 5: {
                        $intro_data['score_view'] = "PWA";
                        $intro_data['scored'] = $sdata->score;
                        $intro_data['after_audit_weight'] = $sdata->sub_parameter_detail->weight;
                        $intro_data['reason_type'] = $all_reason_type_pwd;
                        break;
                    }
                case 6: {
                        $intro_data['score_view'] = "Partial Marking";
                        $intro_data['scored'] = $sdata->score;
                        $intro_data['after_audit_weight'] = $sdata->sub_parameter_detail->weight;
                        $intro_data['reason_type'] = $all_reason_type_partial;
                        break;
                    }
            }
            $reason_type_master[2] = $all_reason_type_fail;
            $reason_type_master[3] = $all_reason_type_cric;
            $reason_type_master[5] = $all_reason_type_pwd;
            $reason_type_master[6] = $all_reason_type_partial;
            $final_data['role_id'] = $role_id;

            $intro_data['selected_reason_type_id'] = $sdata->reason_type_id;
            $intro_data['selected_reason_id'] = $sdata->reason_id;

            if ($intro_data['selected_reason_type_id']) {
                $reasons_master = Reason::where('reason_type_id', $intro_data['selected_reason_type_id'])->pluck('name', 'id');
            } else {
                $reasons_master = [];
            }

            // non scoring else ends
        }


        $final_data['intro_data'] = $intro_data;
        $final_data['scoring_opts'] = $scoring_opts;
        $final_data['reason_type_master'] = $reason_type_master;
        $final_data['reasons_master'] = $reasons_master;

        return response()->json(['status' => 200, 'message' => ".", 'data' => $final_data], 200);
    }
    public function update_basic_audit_data(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'audit_id' => 'required',
            // 'customer_name' => 'required',
            // 'phone_number' => 'required',
            // 'qrc_2' => 'required',
            // 'language_2' => 'required',
            // 'case_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('qc/single_audit_detail/' . $request->audit_id)
                ->withErrors($validator)
                ->withInput();
        } else {
             
            $audit_data = Audit::with('raw_data')->find(Crypt::decrypt($request->audit_id));
            $audit_data->raw_data->customer_name = $request->customer_name;
            $audit_data->raw_data->phone_number = $request->phone_number;
            $audit_data->raw_data->disposition = $request->disposition;
            $audit_data->raw_data->solution_based_category_c = $request->solution_based_category_c;
            $audit_data->raw_data->call_time = $request->call_time;
            $audit_data->raw_data->call_duration = $request->call_duration;
            $audit_data->raw_data->issues = $request->issues;
            $audit_data->raw_data->sub_issues = $request->sub_issues;
            $audit_data->raw_data->error_reason_type = $request->error_reason_type;
            $audit_data->raw_data->solution_based_category_c = $request->solution_based_category_c;
            $audit_data->raw_data->case_status = $request->case_status;
            $audit_data->raw_data->current_assigned_queue = $request->current_assigned_queue;
            $audit_data->raw_data->user_name = $request->user_name;
            $audit_data->raw_data->solution = $request->solution;
            $audit_data->raw_data->case_reason = $request->case_reason;
            $audit_data->raw_data->call_sub_type = $request->call_sub_type;
            $audit_data->raw_data->call_type = $request->call_type;
            $audit_data->raw_data->case_origin = $request->case_origin;
            $audit_data->raw_data->reference_no = $request->reference_no;
            $audit_data->raw_data->case_id = $request->case_id;
            $audit_data->raw_data->case_number = $request->case_number;
            $audit_data->raw_data->month = $request->month;
            $audit_data->raw_data->event_c = $request->event_c;
          


           
            $audit_data->audit_date = $request->audit_date;
            $audit_data->refrence_number = $request->refrence_number;
            $audit_data->error_code = $request->error_code;
            $audit_data->l2_ticket_disposition = $request->l2_ticket_disposition;
            $audit_data->case_dropdown = $request->case_dropdown;
            $audit_data->sfdc_disposition = $request->sfdc_disposition;
            $audit_data->hot_leads = $request->hot_leads;
            $audit_data->caller_type = $request->caller_type;
            $audit_data->scanerio = $request->scanerio;
            $audit_data->scanerio_codes = $request->scanerio_codes;
            $audit_data->call_disposition = $request->call_disposition;
              
            $audit_data->vehicle_type = $request->vehicle_type;
            $audit_data->new_error_code = $request->new_error_code;
            $audit_data->recording_link = $request->recording_link;
            $audit_data->customer_voice = $request->customer_voice;
            $audit_data->raw_data->scenerio_code = $request->scenerio_code ;
            
            $audit_data->raw_data->scenerio = $request->scenerio;
        
            $audit_data->raw_data->save();
  
            $audit_data->qrc_2 = $request->qrc_2;
            $audit_data->language_2 = $request->language_2;
            $audit_data->case_id = $request->case_id;
            $audit_data->overall_summary = $request->overall_summary;
            $audit_data->feedback = $request->feedback;
 
            if ($request->good_bad_call_file) {
                $request->good_bad_call_file->store("raw_data_dump_file");
                $file_name = $request->good_bad_call_file->hashName();
                $data = Storage::url('raw_data_dump_file/') . $file_name;
                $audit_data->good_bad_call_file = $data;
            }

            $audit_data->save();
           
            return redirect('qc/single_audit_detail/' . $request->audit_id)->with('success', 'Audit basic data updated successfully.');
        }
    }
    public function update_sp_data(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'audit_id' => 'required',
            'parameter_id' => 'required',
            'sub_parameter_id' => 'required',
            'basic_data' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => "Data validation error."], 422);
        } else {
            $new_data = [];
            /*  echo $request->screenshot_to_qa;
           dd(); */

            if (isset($request->basic_data['type']) && !empty($request->basic_data['type'])) {

                $image_64 = $request->basic_data['type'];
                $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
                $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
                $image = str_replace($replace, '', $image_64);
                $image = str_replace(' ', '+', $image);
                $imageName = rand() . '.' . $extension;

                Storage::disk('public')->put($imageName, base64_decode($image));
            }

            $audit_result = AuditResult::where('audit_id', $request->audit_id)
                ->where('parameter_id', $request->parameter_id)
                ->where('sub_parameter_id', $request->sub_parameter_id)
                ->first();


            $new_data['sub_parameter_id'] = $audit_result->sub_parameter_id;
            $new_data['previous_observation'] = $audit_result->selected_option;

            $audit_result->is_critical = $request->basic_data['is_critical'];
            $audit_result->selected_option = $request->basic_data['selected_option'];
            $audit_result->score = $request->basic_data['scored'];
            $audit_result->after_audit_weight = $request->basic_data['after_audit_weight'];
            $audit_result->remark = $request->basic_data['remarks'];
            $audit_result->qc_to_qa_feedback = $request->basic_data['qc_to_qa_feedback'];
            if ($request->basic_data['selected_option'] == 2 || $request->basic_data['selected_option'] == 3 || $request->basic_data['selected_option'] == 6 || $request->basic_data['selected_option'] == 1) {
                $audit_result->reason_type_id = $request->basic_data['selected_reason_type_id'];
                $audit_result->reason_id = $request->basic_data['selected_reason_id'];
            } else {
                $audit_result->reason_type_id = 0;
                $audit_result->reason_id = 0;
            }

            /*  if($audit_result->is_critical == 1){ */
            $audit_result->qc_to_qa_artifact =  (isset($imageName)) ? $imageName : '';
            /* } */

            // dd( $audit_result);
            $audit_result->save();


            // update Parameter score starts

            if ($audit_result->is_non_scoring) {
                $all_sub_parameter = AuditResult::where('audit_id', $request->audit_id)
                    ->where('parameter_id', $request->parameter_id)
                    ->get();
                $parameter_revised_data = [];
                $parameter_revised_data['score_sum'] = 0;
                $parameter_revised_data['scorable_sum'] = 0;
                $parameter_revised_data['is_critical'] = 0;
                foreach ($all_sub_parameter as $key => $value) {
                    $parameter_revised_data['score_sum'] += $value->score;
                    $parameter_revised_data['scorable_sum'] += $value->after_audit_weight;

                    if ($value->is_critical)
                        $parameter_revised_data['is_critical'] = 1;
                }

                if ($parameter_revised_data['scorable_sum'] == 0) {
                    $parameter_revised_data['final_score_per'] = 0.00;
                } else {
                    $parameter_revised_data['final_score_per'] = round(($parameter_revised_data['score_sum'] / $parameter_revised_data['scorable_sum']) * 100, 2);
                }


                $audit_parameter = AuditParameterResult::where('audit_id', $request->audit_id)
                    ->where('parameter_id', $request->parameter_id)
                    ->first();
                $audit_parameter->is_critical = $parameter_revised_data['is_critical'];
                $audit_parameter->temp_weight = $parameter_revised_data['scorable_sum'];

                $audit_parameter->without_fatal_score = $parameter_revised_data['score_sum'];
                $audit_parameter->without_fatal_score_pre = $parameter_revised_data['final_score_per'];

                if ($parameter_revised_data['is_critical']) {
                    $audit_parameter->with_fatal_score = 0;
                    $audit_parameter->with_fatal_score_per = 0;
                } else {
                    $audit_parameter->with_fatal_score = $parameter_revised_data['score_sum'];
                    $audit_parameter->with_fatal_score_per = $parameter_revised_data['final_score_per'];
                }

                $audit_parameter->save();
                // update Parameter score ends

                //update entire audit reised score starts
                $all_parameter = AuditParameterResult::where('audit_id', $request->audit_id)->get();


                $parameter_revised_data['score_sum'] = 0;
                $parameter_revised_data['scorable_sum'] = 0;
                $parameter_revised_data['is_critical'] = 0;
                foreach ($all_parameter as $key => $value) {
                    $parameter_revised_data['score_sum'] += $value->without_fatal_score;
                    $parameter_revised_data['scorable_sum'] += $value->temp_weight;

                    if ($value->is_critical)
                        $parameter_revised_data['is_critical'] = 1;
                }

                $parameter_revised_data['final_score_per'] = round(($parameter_revised_data['score_sum'] / $parameter_revised_data['scorable_sum']) * 100, 2);

                $audit_data = Audit::find($request->audit_id);

                $new_data['score_with_fatal'] = $audit_data->with_fatal_score_per;
                $new_data['score_without_fatal'] = $audit_data->overall_score;

                $audit_data->is_critical = $parameter_revised_data['is_critical'];
                $audit_data->overall_score = $parameter_revised_data['final_score_per'];

                if ($parameter_revised_data['is_critical']) {
                    $audit_data->with_fatal_score_per = 0;
                } else {
                    $audit_data->with_fatal_score_per = $parameter_revised_data['final_score_per'];
                }
                $audit_data->save();
                //update entire audit reised score ends
            } else {

                $all_sub_parameter = AuditResult::where('audit_id', $request->audit_id)
                    ->where('parameter_id', $request->parameter_id)
                    ->get();


                $parameter_revised_data = [];
                $parameter_revised_data['score_sum'] = 0;
                $parameter_revised_data['scorable_sum'] = 0;
                $parameter_revised_data['is_critical'] = 0;
                foreach ($all_sub_parameter as $key => $value) {
                    $parameter_revised_data['score_sum'] += $value->score;
                    $parameter_revised_data['scorable_sum'] += $value->after_audit_weight;

                    if ($value->is_critical)
                        $parameter_revised_data['is_critical'] = 1;
                }

                if ($parameter_revised_data['scorable_sum'] == 0) {
                    $parameter_revised_data['final_score_per'] = 0.00;
                } else {
                    $parameter_revised_data['final_score_per'] =  $parameter_revised_data['scorable_sum'] > 0 ? round(($parameter_revised_data['score_sum'] / $parameter_revised_data['scorable_sum']) * 100, 2) : 0;
                }


                $audit_parameter = AuditParameterResult::where('audit_id', $request->audit_id)
                    ->where('parameter_id', $request->parameter_id)
                    ->first();
                $audit_parameter->is_critical = $parameter_revised_data['is_critical'];
                $audit_parameter->temp_weight = $parameter_revised_data['scorable_sum'];

                $audit_parameter->without_fatal_score = $parameter_revised_data['score_sum'];
                $audit_parameter->without_fatal_score_pre = $parameter_revised_data['final_score_per'];

                if ($parameter_revised_data['is_critical']) {
                    $audit_parameter->with_fatal_score = 0;
                    $audit_parameter->with_fatal_score_per = 0;
                } else {
                    $audit_parameter->with_fatal_score = $parameter_revised_data['score_sum'];
                    $audit_parameter->with_fatal_score_per = $parameter_revised_data['final_score_per'];
                }



                $audit_parameter->save();

                // update Parameter score ends

                //update entire audit reised score starts
                $all_parameter = AuditParameterResult::where('audit_id', $request->audit_id)->get();


                $parameter_revised_data['score_sum'] = 0;
                $parameter_revised_data['scorable_sum'] = 0;
                $parameter_revised_data['is_critical'] = 0;
                foreach ($all_parameter as $key => $value) {
                    $parameter_revised_data['score_sum'] += $value->without_fatal_score;
                    $parameter_revised_data['scorable_sum'] += $value->temp_weight;

                    if ($value->is_critical)
                        $parameter_revised_data['is_critical'] = 1;
                }

                $parameter_revised_data['final_score_per'] =  $parameter_revised_data['scorable_sum'] > 0 ?  round(($parameter_revised_data['score_sum'] / $parameter_revised_data['scorable_sum']) * 100, 2) : 0;

                $audit_data = Audit::find($request->audit_id);

                $new_data['score_with_fatal'] = $audit_data->with_fatal_score_per;
                $new_data['score_without_fatal'] = $audit_data->overall_score;

                $audit_data->is_critical = $parameter_revised_data['is_critical'];
                $audit_data->overall_score = $parameter_revised_data['final_score_per'];

                if ($parameter_revised_data['is_critical']) {
                    $audit_data->with_fatal_score_per = 0;
                } else {
                    $audit_data->with_fatal_score_per = $parameter_revised_data['final_score_per'];
                }
                $audit_data->save();
                //update entire audit reised score ends
            }


            //update qc/rebuttal status
            if ($request->rebuttal_id == 0) {


                $audit_data = Audit::find($request->audit_id);
                $audit_data->qc_revised_score_with_fatal = $new_data['score_with_fatal'];
                $audit_data->qc_revised_score_without_fatal = $new_data['score_without_fatal'];
                $audit_data->save();

                $new_defect = new QcDefectSubParameter;
                $new_defect->audit_id = $request->audit_id;
                $new_defect->sub_parameter_id = $new_data['sub_parameter_id'];
                $new_defect->previous_observation = $new_data['previous_observation'];
                $new_defect->save();
            } else {

                $rebuttal_data = Rebuttal::find($request->rebuttal_id);
                $rebuttal_data->revised_score_with_fatal = $new_data['score_with_fatal'];
                $rebuttal_data->revised_score_without_fatal = $new_data['score_without_fatal'];
                $rebuttal_data->save();
            }


            //update qc/rebuttal status
            $audit = Audit::where('id', $request->audit_id)->first();
            $qa_id = $audit->audited_by_id;
            $qa_detail = User::where('id', $qa_id)->first();
            $to_emails = [$qa_detail->email];
            //$cc_emails = [$qtl_detail->email];

            // $subject = 'QC added Feedback on particular audit ';
            // Mail::to($to_emails)->send(new QcFeedback(
            //     [
            //         'subject' => $subject,
            //         'link' => 'http://preqmtool.qdegrees.com/qc/single_audit_detail/' . $request->audit_id,
            //     ]
            // ));


            return response()->json(['status' => 200, 'message' => "Success"], 200);
        }
    }

    public function update_qc_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'audit_id' => 'required',
            'qc_status' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('qc/single_audit_detail/' . $request->audit_id)
                ->withErrors($validator)
                ->withInput();
        } else {

            $audit_id = Crypt::decrypt($request->audit_id);
            $audit_data = Audit::with('raw_data')->find($audit_id);

            $auditDate = Carbon::parse($audit_data->audit_date);
            $now = Carbon::now();

            // Check if the difference in days is less than 1
            if ($now->diffInDays($auditDate) < 1) {
                $audit_data->audit_view_date = $now->toDateTimeString();
            }



            if ($request->qc_status < 3) {
                $audit_data->qc_status = $request->qc_status;
                $audit_data->qc_comment = $request->qc_comment;
                $audit_data->qc_to_qa_feedback = $request->qa_feedback;
                $audit_data->qc_date = date('Y-m-d');
                $audit_data->qc_id = Auth::user()->id;
                $audit_data->qc_tat = date('Y-m-d H:i:s');



                // Qc artifact start

                if ($request->qc_feedback_file) {
                    $file = Input::file('qc_feedback_file');
                    if ($file !== null) {
                        $ext = $request->file('qc_feedback_file')->getClientOriginalExtension();
                        if (
                            $ext == "img" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" ||
                            $ext == "IMG" || $ext == "JPEG" || $ext == "JPG" || $ext == "PNG"
                        ) {
                            $image = $request->file('qc_feedback_file');
                            $imageName = time() . '_' . $image->getClientOriginalName();
                            $ex = explode('.', $imageName);

                            $image->move(public_path('storage\screenshot'), $imageName);

                            $audit_data->qc_feedback_file = $imageName;
                        }
                    }
                }

                // Qc artifact end


                $audit_data->save();
                //
                // Feedback mail 
                $audit = Audit::where('id', $audit_id)->first();
                $qa_id = $audit->audited_by_id;
                $qa_detail = User::where('id', $qa_id)->first();
                $to_emails = [$qa_detail->email];
                //$cc_emails = [$qtl_detail->email];

                $subject = 'QC added Feedback on particular audit ';

                // Mail::to($to_emails)->send(new QcFeedback(
                //     [
                //         'subject' => $subject,
                //         'link' => 'http://qmtool.qdegrees.com/qc_qa/single_audit_detail/' . $request->audit_id,
                //     ]
                // ));

                // Feedback mail 

            } else {
                AuditResult::where('audit_id', $audit_id)->delete();
                AuditParameterResult::where('audit_id', $audit_id)->delete();

                $failed_qc = new FailedQc;
                $failed_qc->company_id = $audit_data->raw_data->company_id;
                $failed_qc->client_id = $audit_data->raw_data->client_id;
                $failed_qc->partner_id = $audit_data->raw_data->partner_id;
                $failed_qc->partner_location_id = $audit_data->raw_data->partner_location_id;
                $failed_qc->process_id = $audit_data->raw_data->process_id;
                $failed_qc->qa_id = $audit_data->raw_data->qa_id;
                $failed_qc->raw_data_id = $audit_data->raw_data_id;
                $failed_qc->comment = $request->qc_comment;
                $failed_qc->save();
                if ($failed_qc->id) {
                    $audit_data->raw_data->status = 0;
                    $audit_data->raw_data->save();

                    $audit_data->delete();
                }
            }
            return redirect('qc/audits')->with('success', 'QC status updated successfully.');
        }
    }

    public function qc_feedback_audits(Request $request)
    {
        /*  echo "hi";
        dd(); */
        $client_id = $request->client_id;
        $start_date = date_to_db($request->start_date);
        $end_date = date_to_db($request->end_date);

        $client_list = Client::where('company_id', Auth::user()->company_id)->pluck('name', 'id');

        if ($client_id == null) {
            $unique_partner = [];
            $unique_location = [];
            $unique_agent = [];
            $unique_qa = [];

            $audits = [];
        } else {
            $unique_partner[''] = 'Select one!';
            $unique_location[''] = 'Select one!';
            $unique_agent[''] = 'Select one!';
            $unique_qa[''] = 'Select one!';

            $audits = [];


            $client_data = Client::find(Crypt::decrypt($client_id));
            $client_qc_time = $client_data->qc_time;
            $current_date_time = date('Y-m-d H:i:s');

            // get audits
            $qc_process = Auth::user()->my_processes;

            $audits = Audit::where('audited_by_id', Auth::user()->id)
                //->where('process_id',$qc_process[0]->master_id)       
                ->where('qc_status', 1)
                ->whereDate('audit_date', '>=', $start_date)
                ->whereDate('audit_date', '<=', $end_date)
                /* ->whereNotNull('qc_comment') */
                ->with(['raw_data', 'raw_data.partner_location', 'raw_data.partner_location.location_detail', 'partner'])
                ->get();


            // $audits = Audit::where('client_id',$client_data->id)->where('process_id',$qc_process[0]->master_id)->with(['raw_data','raw_data.partner_location','partner'])->get();

            $temp_unique_partner = $audits->unique('partner_id');
            foreach ($temp_unique_partner as $key => $value) {
                $unique_partner[$value->partner->name] = $value->partner->name;
            }
            foreach ($audits as $key => $value) {

                $unique_location[$value->raw_data->location_data->name] = $value->raw_data->location_data->name;
            }
            foreach ($audits as $key => $value) {
                $unique_agent[$value->raw_data->agent_name] = $value->raw_data->agent_name;
            }

            $temp_unique_qa = $audits->unique('audited_by_id');
            foreach ($temp_unique_qa as $key => $value) {
                $unique_qa[$value->qa_qtl_detail->name] = $value->qa_qtl_detail->name;
            }
        }

        return view('qc_to_qa_feedback.audit_list', compact('client_list', 'unique_partner', 'unique_location', 'unique_agent', 'unique_qa', 'audits'));
    }

    public function single_audit_detail_feedback($audit_id)
    {

        $audit_data = Audit::with(['audit_parameter_result', 'audit_results', 'audit_results.reason_type', 'audit_results.reason'])->find(Crypt::decrypt($audit_id));
        $raw_data = RawData::find($audit_data->raw_data_id);

        $qm_sheet_para_data = QmSheetParameter::with(['qm_sheet_sub_parameter'])->where('qm_sheet_id', $audit_data->qm_sheet_id)->get();

        $audit_sp_results = $audit_data->audit_results;
        $final_data = [];
        $all_sub_parameters = [];
        foreach ($qm_sheet_para_data as $key => $value) {

            //all subparameters
            foreach ($value->qm_sheet_sub_parameter as $sskey => $ssvalue) {
                $all_sub_parameters[] = ["key" => $value->id . "_" . $ssvalue->id, "value" => $ssvalue->sub_parameter];
            }
            //all subparameters

            $final_data[$value->id]['name'] = $value->parameter;
            $final_data[$value->id]['id'] = $value->id;
            if ($value->is_non_scoring) {
                foreach ($value->qm_sheet_sub_parameter as $skey => $svalue) {
                    $t_1 = $audit_sp_results->where('parameter_id', $value->id)->where('sub_parameter_id', $svalue->id)
                        /* ->whereColumn('created_at','!=','updated_at') */->toArray();
                    if (count($t_1) > 0) {
                        $temp_result = $t_1[array_key_first($t_1)];
                        $final_data[$value->id]['sp'][] = ['is_non_scoring' => $value->is_non_scoring, 'id' => $svalue->id, 'name' => $svalue->sub_parameter, 'detail' => $svalue->details, 'selected_option' => return_non_scoring_observation($temp_result['selected_option']), 'scored' => $temp_result['score'], 'reason_type' => "-", 'reason' => '-', 'remark' => $temp_result['remark']];
                    }
                }
            } else {
                foreach ($value->qm_sheet_sub_parameter as $skey => $svalue) {

                    $t_1 = $audit_sp_results->where('parameter_id', $value->id)->where('sub_parameter_id', $svalue->id)
                        /* ->whereColumn('created_at','!=','updated_at') */->toArray();
                    $temp_result = $t_1[array_key_first($t_1)];
                    if (isset($temp_result['reason_type']['name'])) {

                        //print_r(json_encode($audit_data)); die;
                    } else {
                        $temp_result['reason']['name'] = "-";
                        $temp_result['reason_type']['name'] = "-";

                        //print_r(json_encode($audit_data)); die;
                    }
                    $final_data[$value->id]['sp'][] = ['is_non_scoring' => $value->is_non_scoring, 'id' => $svalue->id, 'name' => $svalue->sub_parameter, 'detail' => $svalue->details, 'selected_option' => return_general_observation($temp_result['selected_option']), 'scored' => $temp_result['score'], 'reason_type' => $temp_result['reason_type']['name'], 'reason' => $temp_result['reason']['name'], 'remark' => $temp_result['remark']];
                }
            }
        }

        $rebuttal_data = Rebuttal::where('raised_by_user_id', Auth::user()->id)->where('audit_id', $audit_data->id)->with(['parameter', 'sub_parameter'])->get();

        $aht_data = BajajAhtCalls::where('audit_id', $audit_data->id)->get();

        return view('qc_to_qa_feedback.qc_single_audit_detail', compact(['raw_data', 'audit_data', 'final_data', 'all_sub_parameters', 'rebuttal_data', 'aht_data']));
    }




    public function qa_to_qc_reply(Request $request)
    {

        // dd($request->audit_id);
        $audit_id = Crypt::decrypt($request->audit_id);

        $audit_data = Audit::with('raw_data')->find($audit_id);

        if ($request->qa_reply == 1) {
            $audit_data->qa_to_qc_reply_status = 1; // accept
        } else {
            $audit_data->qa_to_qc_reply_status = 2; // reject
        }

        $audit_data->save();

        return redirect('qc_qa/single_audit_detail/' . $request->audit_id)->with('success', 'Done.');
    }



    public function qc_qa_feedbacks(Request $request)
    {
        $final_data = [];
        $client_name = '';
        $total_shared_qc = 0;
        $total_accepted_feedbacks = 0;
        $total_rejected_feedbacks = 0;


        if ($request->isMethod('post')) {
            $audits = Audit::where('client_id', $request->client)
                ->where('qc_status', '>', 0)
                ->whereDate('audit_date', '>=', $request->start_date)
                ->whereDate('audit_date', '<=', $request->end_date)
                ->get();

            $client_name = Client::where('id', $request->client)->value('name');

            $total_shared_qc = $audits->count();
            $total_accepted_feedbacks = $audits->where('qa_to_qc_reply_status', '1')->count();
            $total_rejected_feedbacks = $audits->where('qa_to_qc_reply_status', '0')->count();

            $final_data = $audits->map(function ($audit) {
                return [
                    'qc_date' => $audit->qc_date,
                    'audit_date' => $audit->audit_date,
                    'qa_name' => $audit->auditor->name,
                    'feedback_by_qc' => $audit->qc_to_qa_feedback,
                    'previous_score' => $audit->with_fatal_score_per,
                    'after_qc' => $audit->qc_revised_score_with_fatal,
                    'qc_reply' => $audit->qa_to_qc_reply_status,
                    'qc_id' => $audit->qc_id,
                ];
            });
        }

        return view('reports.qc_qa_feedbacks', compact('client_name', 'total_shared_qc', 'total_accepted_feedbacks', 'total_rejected_feedbacks', 'final_data'));
    }


    public function update_aht(Request $request)
    {

        $audit_id = Crypt::decrypt($request->input('audit_id'));
        $ahts = $request->input('ahts');

        $auditor = Audit::select('audited_by_id')->where('id', $audit_id)->pluck('audited_by_id');


        foreach ($ahts as $aht) {
            if (isset($aht['id']) && $aht['id']) {
                // Update existing AHT entry
                $existingAHT = BajajAhtCalls::find($aht['id']);
                if ($existingAHT) {
                    $existingAHT->aht_time = $aht['aht_time'];
                    $existingAHT->call_id = $aht['call_id'];
                    $existingAHT->save();
                }
                // dd($existingAHT);
            } else {
                // Insert new AHT entry
                BajajAhtCalls::create([
                    'audit_id' => $audit_id,
                    'aht_time' => $aht['aht_time'],
                    'call_id' => $aht['call_id'],
                    'qa_id' =>  $auditor[0],
                ]);
            }
        }


        return redirect('qc_qa/single_audit_detail/' . $request->audit_id)->with('success', 'Done.');
    }
}
