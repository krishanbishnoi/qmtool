<?php

namespace App\Http\Controllers;

ini_set('memory_limit', '-1');

use App\Audit;
use App\AuditFormValues;
use App\Region;
use App\LocationMapping;
use App\AuditAlertBox;
use App\AuditParameterResult;
use App\AuditResult;
use App\Auditcycle;
use App\Partner;
use App\BajajAhtCalls;
use App\BajajBotCall;
use App\QmSheet;
use App\RawData;
use App\IncompleteAudit;
use App\Rca2Mode;
use App\AuditErrorCode;
use App\ErrorCode;
use App\RcaMode;
use App\RcaType;
use App\Reason;
use App\ReasonType;
use App\TmpAudit;
use App\TmpAuditParameterResult;
use App\TmpAuditResult;
use App\TypeBScoringOption;
use App\AuditFormFields;
use Auth;
use Crypt;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\User;

use App\AuditLog;
use App\QmSheetSubParameter;
use DateTime;

class AuditController extends Controller
{
    public function render_audit_sheet($qm_sheet_id)
    {
        // added by nisha
        $sheet_data = [];
        $assigned_process_id = User::where('id', Auth::user()->id)->pluck('assigned_process_ids');
        $assigned_sheet_id = User::select('assigned_sheet_id')->where('id', Auth::user()->id)->first();
        $proList = json_decode($assigned_process_id[0]);
        $sheet_id = QmSheet::where('id', $assigned_sheet_id->assigned_sheet_id)->pluck('process_id');
        //echo "<pre>"; print_r($proList); die;
        $getSheetId = array();
        if (Auth::user()->hasRole('qtl')) {
        } else {
            if (gettype($proList) == 'NULL') {
                $proList = $sheet_id;
            }
            foreach ($proList as $key => $value) {
                $sh = QmSheet::select('id')->where('process_id', $value)->orderBy('version', 'desc')->first();
                $getSheetId[] = $sh->id;
            }
        }
        if (!empty($assigned_process_id[0]) && isset($assigned_process_id[0])) {
            if (Auth::user()->hasRole('qtl')) {
                $sheet_data = QmSheet::whereIn('process_id', json_decode($assigned_process_id[0]))->with('process')->get();
            } else {
                $sheet_data = QmSheet::whereIn('process_id', json_decode($assigned_process_id[0]))->with('process')->whereIn('id', $getSheetId)->get();
            }
        }
        // end by nisha
        $data = QmSheet::with(['client'])->find(Crypt::decrypt($qm_sheet_id));
        return view('audit.render_sheet', compact('qm_sheet_id', 'data', 'sheet_data'));
    }

    public function upload_call(Request $request)
    {
        if ($request->call_file) {
            $data_recording = "";
            //echo Storage::url('raw_data_dump_file/');
            $request->call_file->store("qa_views_call_files");
            if (isset($request->recording_file)) {
                $request->recording_file->store("qa_views_recording_feedback");
                $recording_file_name = $request->recording_file->hashName();
                $data_recording = Storage::url('qa_views_recording_feedback/') . $recording_file_name;
            }
            $file_name = $request->call_file->hashName();
            $data = Storage::url('qa_views_call_files/') . $file_name;

            return response()->json(['status' => 200, 'message' => "File uploaded successfuly", 'data' => $data, 'data_recording' => $data_recording], 200);
        } else {
            echo '';
        }
    }
    public function render_audit_sheet_dev($qm_sheet_id)
    {
        // added by nisha
        $sheet_data = [];
        $assigned_process_id = User::where('id', Auth::user()->id)->pluck('assigned_process_ids');
        $proList = json_decode($assigned_process_id[0]);
        $getSheetId = array();
        foreach ($proList as $key => $value) {
            $sh = QmSheet::select('id')->where('process_id', $value)->orderBy('version', 'desc')->first();
            $getSheetId[] = $sh->id;
        }
        if (!empty($assigned_process_id[0]) && isset($assigned_process_id[0])) {
            $sheet_data = QmSheet::whereIn('process_id', json_decode($assigned_process_id[0]))->with('process')->whereIn('id', $getSheetId)->get();
        }
        // end by nisha
        $data = QmSheet::with(['client'])->find(Crypt::decrypt($qm_sheet_id));
        return view('audit.render_sheet', compact('qm_sheet_id', 'data', 'sheet_data'));
    }

    // public function get_qm_sheet_details_for_audit($qm_sheet_id)
    // {
    //     $data = QmSheet::with(['client', 'process', 'parameter', 'parameter.qm_sheet_sub_parameter'])->find(Crypt::decrypt($qm_sheet_id));
    //     $partners_list = Partner::where('client_id', $data->client_id)->pluck('name', 'id');
    //     $final_data['partners_list'] = $partners_list;
    //     // Tiwari code
    //     $sk_data['delay1'] = $data->client->qc_time;
    //     $sk_data['delay2'] = $data->client->rebuttal_time;
    //     $sk_data['holiday'] = $data->client->holiday;
    //     $final_data['sk_client'] = $sk_data;
    //     //Tiwari code

    //     // Code by Abhilasha QRC replacement
    //     /* $getUniqueCallType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$data->client_id)->where('process_id',$data->process->id)->limit(3)->orderby('id','desc')->pluck('call_type');
    //     $final_data['client_id']=$data->client_id;
    //     $final_data['callTypeList'] = $getUniqueCallType; */
    //     // Code by Abhilasha QRC replacement
    //     // Above abhilasha code change by shailendra for Hotstar
    //     if ($data->client_id == 13) {
    //         $getUniqueCallType = RawData::select(DB::raw("distinct(call_type)"))->where('client_id', $data->client_id)->where('process_id', $data->process->id)->orderby('id', 'desc')->pluck('call_type');
    //         $final_data['client_id'] = $data->client_id;
    //         $final_data['callTypeList'] = $getUniqueCallType;
    //     } else {
    //         $getUniqueCallType = RawData::select(DB::raw("distinct(call_type)"))->where('client_id', $data->client_id)->where('process_id', $data->process->id)->limit(3)->orderby('id', 'desc')->pluck('call_type');
    //         $final_data['client_id'] = $data->client_id;
    //         $final_data['callTypeList'] = $getUniqueCallType;
    //     }
    //     // Above abhilasha code change by shailendra for Hotstar
    //     // get audit cycle
    //     $latest_audit_cycle = Auditcycle::where('qmsheet_id', $data->id)->orderBy('start_date')->first();
    //     $start_date = '';
    //     $end_date = '';
    //     if ($latest_audit_cycle) {
    //         $start_date = $latest_audit_cycle->start_date;
    //         $end_date = $latest_audit_cycle->end_date;
    //     }
    //     $final_data['audit_cycle'] = $start_date . " To " . $end_date;
    //     $final_data['audit_timestamp'] = date('Y-m-d H:i:s');
    //     // get audit cycle
    //     $temp_my_alloted_call_list = RawData::where('qa_id', Auth::user()->id)
    //         ->where('process_id', $data->process_id)
    //         ->where('dump_date', '>=', $start_date)
    //         ->where('dump_date', '<=', $end_date)
    //         ->where('status', 0)->pluck('call_id', 'id');

    //     $my_alloted_call_list = [];
    //     foreach ($temp_my_alloted_call_list as $key => $value) {
    //         $my_alloted_call_list[] = ['key' => $key, "value" => $value];
    //     }
    //     $final_data['my_alloted_call_list'] = $my_alloted_call_list;
    //     $final_data['sheet_details'] = $data->toArray();
    //     //$final_data['type_b_scoring_option'] = TypeBScoringOption::all();
    //     $all_type_b_scoring_option = TypeBScoringOption::where('company_id', $data->company_id)->pluck('name', 'id');
    //     //process data
    //     $pds = [];
    //     foreach ($data->parameter as $key => $value_p) {
    //         $pds[$value_p->id]['id'] = $value_p->id;
    //         $pds[$value_p->id]['name'] = $value_p->parameter;
    //         $pds[$value_p->id]['is_non_scoring'] = $value_p->is_non_scoring;
    //         $total_parameter_weight = 0;
    //         $pds[$value_p->id]['is_fatal'] = 0;
    //         $pds[$value_p->id]['is_partial_marking'] = 0;
    //         $pds[$value_p->id]['score'] = 0;
    //         $pds[$value_p->id]['score_with_fatal'] = 0;
    //         $pds[$value_p->id]['score_without_fatal'] = 0;
    //         $pds[$value_p->id]['temp_total_weightage'] = 0;
    //         foreach ($value_p->qm_sheet_sub_parameter as $key => $value_s) {
    //             $pds[$value_p->id]['subs'][$value_s->id]['id'] = $value_s->id;
    //             $pds[$value_p->id]['subs'][$value_s->id]['name'] = $value_s->sub_parameter;
    //             $pds[$value_p->id]['subs'][$value_s->id]['details'] = $value_s->details;
    //             $pds[$value_p->id]['subs'][$value_s->id]['is_fatal'] = 0;
    //             $pds[$value_p->id]['subs'][$value_s->id]['is_partial_marking'] = 0;
    //             $pds[$value_p->id]['subs'][$value_s->id]['is_non_scoring'] = $value_p->is_non_scoring;
    //             $pds[$value_p->id]['subs'][$value_s->id]['failure_reason'] = '';
    //             $pds[$value_p->id]['subs'][$value_s->id]['remark'] = '';
    //             $pds[$value_p->id]['subs'][$value_s->id]['orignal_weight'] = $value_s->weight;
    //             $pds[$value_p->id]['subs'][$value_s->id]['temp_weight'] = 0;
    //             $scoring_opts = [];
    //             if ($value_p->is_non_scoring) {
    //                 $total_parameter_weight += 0;
    //                 if ($value_s->non_scoring_option_group) {
    //                     foreach (all_non_scoring_obs_options($value_s->non_scoring_option_group) as $key_ns => $value_ns) {
    //                         $scoring_opts[$value_p->id . "_" . $value_s->id . "_" . $value_ns . "_" . $key_ns . "_0"] = ["key" => $value_p->id . "_" . $value_s->id . "_" . $value_ns . "_" . $key_ns . "_0", "value" => $value_ns, "alert_box" => null];
    //                     }
    //                 } else {
    //                     $scoring_opts = null;
    //                 }
    //             } else {
    //                 $total_parameter_weight += $value_s->weight;
    //                 $alert_box = null;
    //                 $all_reason_type_fail = null;
    //                 $all_reason_type_cric = null;
    //                 $all_reason_type_pwd = null;
    //                 $all_reason_type_partial_marking = null;
    //                 if ($value_s->pass) {
    //                     if ($value_s->pass_alert_box_id)
    //                         $alert_box = AuditAlertBox::find($value_s->pass_alert_box_id);
    //                     else
    //                         $alert_box = null;
    //                     $scoring_opts[$value_p->id . "_" . $value_s->id . "_" . $value_s->weight . "_1_0"] = ["key" => $value_p->id . "_" . $value_s->id . "_" . $value_s->weight . '_1_0', "value" => "Pass", "alert_box" => $alert_box];
    //                 }
    //                 if ($value_s->fail) {
    //                     if ($value_s->fail_alert_box_id)
    //                         $alert_box = AuditAlertBox::find($value_s->fail_alert_box_id);
    //                     else
    //                         $alert_box = null;

    //                     if ($value_s->fail_reason_types) {
    //                         $temp_index_f = $value_p->id . "_" . $value_s->id . "_" . "0" . "_2_1";
    //                         $temp_r_fail = ReasonType::find(explode(',', $value_s->fail_reason_types))->pluck('name', 'id');
    //                         foreach ($temp_r_fail as $keycc => $valuecc) {
    //                             $all_reason_type_fail[] = ["key" => $value_p->id . "_" . $value_s->id . "_" . $keycc, "value" => $valuecc];
    //                         }
    //                     } else {
    //                         $temp_index_f = $value_p->id . "_" . $value_s->id . "_" . "0" . "_2_0";
    //                         $all_reason_type_fail = null;
    //                     }
    //                     $scoring_opts[$temp_index_f] = ["key" => $temp_index_f, "value" => "Fail", "alert_box" => $alert_box];
    //                 }
    //                 if ($value_s->critical) {
    //                     if ($value_s->critical_alert_box_id)
    //                         $alert_box = AuditAlertBox::find($value_s->critical_alert_box_id);
    //                     else
    //                         $alert_box = null;
    //                     if ($value_s->critical_reason_types) {
    //                         $temp_index_cri = $value_p->id . "_" . $value_s->id . "_" . "Critical" . "_3_1";
    //                         $temp_cric = ReasonType::find(explode(',', $value_s->critical_reason_types))->pluck('name', 'id');
    //                         foreach ($temp_cric as $keycc => $valuecc) {
    //                             $all_reason_type_cric[] = ["key" => $value_p->id . "_" . $value_s->id . "_" . $keycc, "value" => $valuecc];
    //                         }
    //                     } else {
    //                         $temp_index_cri = $value_p->id . "_" . $value_s->id . "_" . "Critical" . "_3_0";
    //                         $all_reason_type_cric = null;
    //                     }
    //                     $scoring_opts[$temp_index_cri] = ["key" => $temp_index_cri, "value" => "Critical", "alert_box" => $alert_box];
    //                 }
    //                 if ($value_s->na) {
    //                     if ($value_s->na_alert_box_id)
    //                         $alert_box = AuditAlertBox::find($value_s->na_alert_box_id);
    //                     else
    //                         $alert_box = null;
    //                     $scoring_opts[$value_p->id . "_" . $value_s->id . "_" . "N/A" . "_4_0"] = ["key" => $value_p->id . "_" . $value_s->id . "_" . "N/A" . "_4_0", "value" => "N/A", "alert_box" => $alert_box];
    //                 }
    //                 if ($value_s->pwd) {
    //                     if ($value_s->pwd_alert_box_id)
    //                         $alert_box = AuditAlertBox::find($value_s->pwd_alert_box_id);
    //                     else
    //                         $alert_box = null;

    //                     if ($value_s->pwd_reason_types) {
    //                         $temp_index_pwd = $value_p->id . "_" . $value_s->id . "_" . ($value_s->weight / 2) . "_5_1";
    //                         $temp_pwd = ReasonType::find(explode(',', $value_s->pwd_reason_types))->pluck('name', 'id');
    //                         foreach ($temp_pwd as $keycc => $valuecc) {
    //                             $all_reason_type_pwd[] = ["key" => $value_p->id . "_" . $value_s->id . "_" . $keycc, "value" => $valuecc];
    //                         }
    //                     } else {
    //                         $temp_index_pwd = $value_p->id . "_" . $value_s->id . "_" . ($value_s->weight / 2) . "_5_0";
    //                         $all_reason_type_pwd = null;
    //                     }
    //                     // $scoring_opts[$value_p->id."_".$value_s->id."_".($value_s->weight/2)."_5_0"] = ["key"=>$value_p->id."_".$value_s->id."_".($value_s->weight/2)."_5_0","value"=>"PWD","alert_box"=>$alert_box];
    //                     $scoring_opts[$temp_index_pwd] = ["key" => $temp_index_pwd, "value" => "PWD", "alert_box" => $alert_box];
    //                 }
    //                 if ($value_s->partial_marking) {
    //                     if ($value_s->partial_marking_alert_box_id)
    //                         $alert_box = AuditAlertBox::find($value_s->partial_marking_alert_box_id);
    //                     else
    //                         $alert_box = null;

    //                     if ($value_s->partial_marking_reason_types) {
    //                         $temp_index_partial_marking = $value_p->id . "_" . $value_s->id . "_" . $value_s->weight . "_6_1";
    //                         $temp_partial_marking = ReasonType::find(explode(',', $value_s->partial_marking_reason_types))->pluck('name', 'id');
    //                         foreach ($temp_partial_marking as $keycc => $valuecc) {
    //                             $all_reason_type_partial_marking[] = ["key" => $value_p->id . "_" . $value_s->id . "_" . $keycc, "value" => $valuecc];
    //                         }
    //                     } else {
    //                         $temp_index_partial_marking = $value_p->id . "_" . $value_s->id . "_" . $value_s->weight . "_6_0";
    //                         $all_reason_type_partial_marking = null;
    //                     }
    //                     // var readonly = document.getElementById('sp_id_'+$value_s->id);
    //                     // readonly.readOnly = false;
    //                     // $scoring_opts[$value_p->id."_".$value_s->id."_".($value_s->weight/2)."_5_0"] = ["key"=>$value_p->id."_".$value_s->id."_".($value_s->weight/2)."_5_0","value"=>"PWD","alert_box"=>$alert_box];

    //                     $scoring_opts[$temp_index_partial_marking] = ["key" => $temp_index_partial_marking, "value" => "Partial Marking", "alert_box" => $alert_box];
    //                 }
    //             }
    //             $pds[$value_p->id]['subs'][$value_s->id]['options'] = $scoring_opts;
    //             $pds[$value_p->id]['subs'][$value_s->id]['score'] = 0;
    //             $pds[$value_p->id]['subs'][$value_s->id]['selected_options'] = null;
    //             $pds[$value_p->id]['subs'][$value_s->id]['selected_option_model'] = '';
    //             $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_fail'] = $all_reason_type_fail;
    //             $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_cric'] = $all_reason_type_cric;
    //             $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_pwd'] = $all_reason_type_pwd;
    //             $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_partial_marking'] = $all_reason_type_partial_marking;
    //             $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type'] = null;
    //             $pds[$value_p->id]['subs'][$value_s->id]['selected_reason_type'] = '';
    //             $pds[$value_p->id]['subs'][$value_s->id]['all_reasons'] = null;
    //             $pds[$value_p->id]['subs'][$value_s->id]['selected_reason'] = '';
    //         }
    //         $pds[$value_p->id]['parameter_weight'] = $total_parameter_weight;
    //     }
    //     $final_data['simple_data'] = $pds;
    //     // rca starts
    //     $rca_type =  RcaType::where('company_id', $data->company_id)->where('process_id', $data->process_id)->pluck('name', 'id');
    //     $rca_mode =  RcaMode::where('company_id', $data->company_id)->where('process_id', $data->process_id)->pluck('name', 'id');
    //     $final_data['rca_type'] = $rca_type;
    //     $final_data['rca_mode'] = $rca_mode;
    //     // rca end
    //     // rca type 2starts
    //     $type_2_rca_mode = Rca2Mode::where('company_id', $data->company_id)->where('process_id', $data->process_id)->pluck('name', 'id');
    //     $final_data['type_2_rca_mode'] = $type_2_rca_mode;
    //     // rca type 2 ends
    //     return response()->json(['status' => 200, 'message' => "Success", 'data' => $final_data,], 200);
    // }

    public function get_qm_sheet_details_for_audit($qm_sheet_id)
    {


        $data = QmSheet::with(['client', 'process', 'parameter', 'parameter.qm_sheet_sub_parameter'])->find(Crypt::decrypt($qm_sheet_id));
        $partners_list = Partner::where('client_id', $data->client_id)->pluck('name', 'id');
        $final_data['partners_list'] = $partners_list;
        // Tiwari code
        $sk_data['delay1'] = $data->client->qc_time;
        $sk_data['delay2'] = $data->client->rebuttal_time;
        $sk_data['holiday'] = $data->client->holiday;
        $final_data['sk_client'] = $sk_data;
        //Tiwari code

        // Code by Abhilasha QRC replacement
        /* $getUniqueCallType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$data->client_id)->where('process_id',$data->process->id)->limit(3)->orderby('id','desc')->pluck('call_type');
        $final_data['client_id']=$data->client_id;
        $final_data['callTypeList'] = $getUniqueCallType; */
        // Code by Abhilasha QRC replacement
        // Above abhilasha code change by shailendra for Hotstar
        if ($data->client_id == 13 || $data->client_id == 9) {
            $getUniqueCallType = RawData::select(DB::raw("distinct(call_type)"))->where('client_id', $data->client_id)->where('process_id', $data->process->id)->orderby('id', 'desc')->pluck('call_type');
            $final_data['client_id'] = $data->client_id;
            $final_data['callTypeList'] = $getUniqueCallType;
        } else {
            // $getUniqueCallType = RawData::select(DB::raw("distinct(call_type)"))->where('client_id', $data->client_id)->where('process_id', $data->process->id)->limit(3)->orderby('id', 'desc')->pluck('call_type');
            $getUniqueCallType  = [];
            $final_data['client_id'] = $data->client_id;
            $final_data['callTypeList'] = $getUniqueCallType;
        }
        // Above abhilasha code change by shailendra for Hotstar
        // get audit cycle
        $latest_audit_cycle = Auditcycle::where('qmsheet_id', $data->id)->orderBy('start_date')->first();
        $start_date = '';
        $end_date = '';
        if ($latest_audit_cycle) {
            $start_date = $latest_audit_cycle->start_date;
            $end_date = $latest_audit_cycle->end_date;
        }
        $final_data['audit_cycle'] = $start_date . " To " . $end_date;
        $final_data['audit_timestamp'] = date('Y-m-d H:i:s');

        $tl_ids = User::where('parent_client', 23)->where('status', 1)->whereHas('roles', function ($query) {
            $query->where('name', '=', 'qtl');
        })->pluck('email', 'id');
        $final_data['tl_ids'] =  $tl_ids;
        // get audit cycle
        $temp_my_alloted_call_list = RawData::where('qa_id', Auth::user()->id)
            ->where('process_id', $data->process_id)
            ->where('dump_date', '>=', $start_date)
            ->where('dump_date', '<=', $end_date)
            ->where('status', 0)->pluck('call_id', 'id');

        $my_alloted_call_list = [];
        foreach ($temp_my_alloted_call_list as $key => $value) {
            $my_alloted_call_list[] = ['key' => $key, "value" => $value];
        }
        $final_data['my_alloted_call_list'] = $my_alloted_call_list;
        $final_data['sheet_details'] = $data->toArray();
        //$final_data['type_b_scoring_option'] = TypeBScoringOption::all();
        $all_type_b_scoring_option = TypeBScoringOption::where('company_id', $data->company_id)->pluck('name', 'id');
        //process data
        $pds = [];

        foreach ($data->parameter as $key => $value_p) {
            $pds[$value_p->id]['id'] = $value_p->id;
            $pds[$value_p->id]['name'] = $value_p->parameter;
            $pds[$value_p->id]['is_non_scoring'] = $value_p->is_non_scoring;
            $total_parameter_weight = 0;
            $pds[$value_p->id]['is_fatal'] = 0;
            $pds[$value_p->id]['is_partial_marking'] = 0;
            $pds[$value_p->id]['score'] = 0;
            $pds[$value_p->id]['score_with_fatal'] = 0;
            $pds[$value_p->id]['score_without_fatal'] = 0;
            $pds[$value_p->id]['temp_total_weightage'] = 0;
            foreach ($value_p->qm_sheet_sub_parameter as $key => $value_s) {
                $pds[$value_p->id]['subs'][$value_s->id]['id'] = $value_s->id;
                $pds[$value_p->id]['subs'][$value_s->id]['name'] = $value_s->sub_parameter;
                $pds[$value_p->id]['subs'][$value_s->id]['details'] = $value_s->details;
                $pds[$value_p->id]['subs'][$value_s->id]['is_fatal'] = 0;
                $pds[$value_p->id]['subs'][$value_s->id]['is_partial_marking'] = 0;
                $pds[$value_p->id]['subs'][$value_s->id]['is_non_scoring'] = $value_p->is_non_scoring;
                $pds[$value_p->id]['subs'][$value_s->id]['failure_reason'] = '';
                $pds[$value_p->id]['subs'][$value_s->id]['remark'] = '';
                $pds[$value_p->id]['subs'][$value_s->id]['orignal_weight'] = $value_s->weight;
                $pds[$value_p->id]['subs'][$value_s->id]['temp_weight'] = 0;
                $scoring_opts = [];
                if ($value_p->is_non_scoring) {
                    $total_parameter_weight += 0;
                    if ($value_s->non_scoring_option_group) {
                        foreach (all_non_scoring_obs_options($value_s->non_scoring_option_group) as $key_ns => $value_ns) {
                            $scoring_opts[$value_p->id . "_" . $value_s->id . "_" . $value_ns . "_" . $key_ns . "_0"] = ["key" => $value_p->id . "_" . $value_s->id . "_" . $value_ns . "_" . $key_ns . "_0", "value" => $value_ns, "alert_box" => null];
                        }
                    } else {
                        $scoring_opts = null;
                    }
                } else {
                    $total_parameter_weight += $value_s->weight;
                    $alert_box = null;
                    $all_reason_type_fail = null;
                    $all_reason_type_pass = null;
                    $all_reason_type_cric = null;
                    $all_reason_type_pwd = null;
                    $all_reason_type_partial_marking = null;
                    if ($value_s->pass) {
                        if ($value_s->pass_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->pass_alert_box_id);
                        else
                            $alert_box = null;




                        if (Crypt::decrypt($qm_sheet_id) == 233) {


                            // added require bajaj
                            if ($value_s->pass_reason_types) {
                                $temp_index_p = $value_p->id . "_" . $value_s->id . "_" . "0" . "_1_0";
                                $temp_r_pass = ReasonType::find(explode(',', $value_s->pass_reason_types))->pluck('name', 'id');
                                foreach ($temp_r_pass as $keycc => $valuecc) {
                                    $all_reason_type_pass[] = ["key" => $value_p->id . "_" . $value_s->id . "_" . $keycc, "value" => $valuecc];
                                }
                            } else {
                                $temp_index_p = $value_p->id . "_" . $value_s->id . "_" . "0" . "_1_0";
                                $all_reason_type_pass = null;
                            }

                            $scoring_opts[$temp_index_p] = ["key" => $temp_index_p, "value" => "Pass", "alert_box" => $alert_box];
                            // added require bajaj

                        } else {

                            $scoring_opts[$value_p->id . "_" . $value_s->id . "_" . $value_s->weight . "_1_0"] = ["key" => $value_p->id . "_" . $value_s->id . "_" . $value_s->weight . '_1_0', "value" => "Pass", "alert_box" => $alert_box];
                        }
                    }
                    if ($value_s->fail) {
                        if ($value_s->fail_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->fail_alert_box_id);
                        else
                            $alert_box = null;

                        if ($value_s->fail_reason_types) {
                            $temp_index_f = $value_p->id . "_" . $value_s->id . "_" . "0" . "_2_1";
                            $temp_r_fail = ReasonType::find(explode(',', $value_s->fail_reason_types))->pluck('name', 'id');
                            foreach ($temp_r_fail as $keycc => $valuecc) {
                                $all_reason_type_fail[] = ["key" => $value_p->id . "_" . $value_s->id . "_" . $keycc, "value" => $valuecc];
                            }
                        } else {
                            $temp_index_f = $value_p->id . "_" . $value_s->id . "_" . "0" . "_2_0";
                            $all_reason_type_fail = null;
                        }
                        $scoring_opts[$temp_index_f] = ["key" => $temp_index_f, "value" => "Fail", "alert_box" => $alert_box];
                    }
                    if ($value_s->critical) {
                        if ($value_s->critical_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->critical_alert_box_id);
                        else
                            $alert_box = null;
                        if ($value_s->critical_reason_types) {
                            $temp_index_cri = $value_p->id . "_" . $value_s->id . "_" . "Critical" . "_3_1";
                            $temp_cric = ReasonType::find(explode(',', $value_s->critical_reason_types))->pluck('name', 'id');
                            foreach ($temp_cric as $keycc => $valuecc) {
                                $all_reason_type_cric[] = ["key" => $value_p->id . "_" . $value_s->id . "_" . $keycc, "value" => $valuecc];
                            }
                        } else {
                            $temp_index_cri = $value_p->id . "_" . $value_s->id . "_" . "Critical" . "_3_0";
                            $all_reason_type_cric = null;
                        }
                        $scoring_opts[$temp_index_cri] = ["key" => $temp_index_cri, "value" => "Critical", "alert_box" => $alert_box];
                    }
                    if ($value_s->na) {
                        if ($value_s->na_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->na_alert_box_id);
                        else
                            $alert_box = null;
                        $scoring_opts[$value_p->id . "_" . $value_s->id . "_" . "N/A" . "_4_0"] = ["key" => $value_p->id . "_" . $value_s->id . "_" . "N/A" . "_4_0", "value" => "N/A", "alert_box" => $alert_box];
                    }
                    if ($value_s->pwd) {
                        if ($value_s->pwd_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->pwd_alert_box_id);
                        else
                            $alert_box = null;

                        if ($value_s->pwd_reason_types) {
                            $temp_index_pwd = $value_p->id . "_" . $value_s->id . "_" . ($value_s->weight / 2) . "_5_1";
                            $temp_pwd = ReasonType::find(explode(',', $value_s->pwd_reason_types))->pluck('name', 'id');
                            foreach ($temp_pwd as $keycc => $valuecc) {
                                $all_reason_type_pwd[] = ["key" => $value_p->id . "_" . $value_s->id . "_" . $keycc, "value" => $valuecc];
                            }
                        } else {
                            $temp_index_pwd = $value_p->id . "_" . $value_s->id . "_" . ($value_s->weight / 2) . "_5_0";
                            $all_reason_type_pwd = null;
                        }
                        // $scoring_opts[$value_p->id."_".$value_s->id."_".($value_s->weight/2)."_5_0"] = ["key"=>$value_p->id."_".$value_s->id."_".($value_s->weight/2)."_5_0","value"=>"PWD","alert_box"=>$alert_box];
                        $scoring_opts[$temp_index_pwd] = ["key" => $temp_index_pwd, "value" => "PWD", "alert_box" => $alert_box];
                    }
                    if ($value_s->partial_marking) {
                        if ($value_s->partial_marking_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->partial_marking_alert_box_id);
                        else
                            $alert_box = null;

                        if ($value_s->partial_marking_reason_types) {
                            $temp_index_partial_marking = $value_p->id . "_" . $value_s->id . "_" . $value_s->weight . "_6_1";
                            $temp_partial_marking = ReasonType::find(explode(',', $value_s->partial_marking_reason_types))->pluck('name', 'id');
                            foreach ($temp_partial_marking as $keycc => $valuecc) {
                                $all_reason_type_partial_marking[] = ["key" => $value_p->id . "_" . $value_s->id . "_" . $keycc, "value" => $valuecc];
                            }
                        } else {
                            $temp_index_partial_marking = $value_p->id . "_" . $value_s->id . "_" . $value_s->weight . "_6_0";
                            $all_reason_type_partial_marking = null;
                        }
                        // var readonly = document.getElementById('sp_id_'+$value_s->id);
                        // readonly.readOnly = false;
                        // $scoring_opts[$value_p->id."_".$value_s->id."_".($value_s->weight/2)."_5_0"] = ["key"=>$value_p->id."_".$value_s->id."_".($value_s->weight/2)."_5_0","value"=>"PWD","alert_box"=>$alert_box];

                        $scoring_opts[$temp_index_partial_marking] = ["key" => $temp_index_partial_marking, "value" => "Partial Marking", "alert_box" => $alert_box];
                    }
                }
                $pds[$value_p->id]['subs'][$value_s->id]['options'] = $scoring_opts;
                $pds[$value_p->id]['subs'][$value_s->id]['score'] = 0;
                $pds[$value_p->id]['subs'][$value_s->id]['selected_options'] = null;
                $pds[$value_p->id]['subs'][$value_s->id]['selected_option_model'] = '';
                $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_fail'] = $all_reason_type_fail ?? null;
                $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_pass'] = $all_reason_type_pass ?? null;
                $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_cric'] = $all_reason_type_cric ?? null;
                $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_pwd'] = $all_reason_type_pwd ?? null;
                $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_partial_marking'] = $all_reason_type_partial_marking ?? null;
                $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type'] = null;
                $pds[$value_p->id]['subs'][$value_s->id]['selected_reason_type'] = '';
                $pds[$value_p->id]['subs'][$value_s->id]['all_reasons'] = null;
                $pds[$value_p->id]['subs'][$value_s->id]['selected_reason'] = '';
            }
            $pds[$value_p->id]['parameter_weight'] = $total_parameter_weight;
        }
        $final_data['simple_data'] = $pds;
        // rca starts
        $rca_type =  RcaType::where('company_id', $data->company_id)->where('process_id', $data->process_id)->pluck('name', 'id');
        $rca_mode =  RcaMode::where('company_id', $data->company_id)->where('process_id', $data->process_id)->pluck('name', 'id');
        $final_data['rca_type'] = $rca_type;
        $final_data['rca_mode'] = $rca_mode;
        $id = Auth::user()->id;

        $qa_location = LocationMapping::where('client_id', $id)->first('location_id');

        $region = null; // default value

        if ($qa_location && isset($qa_location->location_id)) {
            $region = Region::where('id', $qa_location->location_id)
                ->select('id', 'name')
                ->first();
        }

        $sheet_headers = AuditFormFields::with('options')
            ->where('status', 1)
            ->where('process_id', $data->process_id)
            ->get();


        $final_data['sheet_headers'] = $sheet_headers;


        $final_data['region'] = $region ?? ['id' => null, 'name' => ''];
        // rca end
        // rca type 2starts
        $type_2_rca_mode = Rca2Mode::where('company_id', $data->company_id)->where('process_id', $data->process_id)->pluck('name', 'id');
        $final_data['type_2_rca_mode'] = $type_2_rca_mode;
        // rca type 2 ends


        return response()->json(['status' => 200, 'message' => "Success", 'data' => $final_data,], 200);
    }
    public function get_raw_data_for_audit($comm_instance_id)
    {
        $data = RawData::with('partner_detail')->find($comm_instance_id);
        if ($data) {
            $check_log = AuditLog::where('auditor_id', Auth::user()->id)->orderBy('created_at', 'DESC')->first();
            if ($check_log) {
                if (gettype($check_log->end_time) == "NULL") {
                    $update_old = AuditLog::find($check_log->id);
                    $datetime1 = new DateTime($update_old->start_time);
                    $datetime2 = new DateTime(now());
                    $interval = $datetime1->diff($datetime2);
                    $minuts = 0;
                    $hours = 0;
                    $total_minutes = 0;
                    foreach ($interval as $key => $value) {
                        if ($key == "i") {
                            $minutes = $value;
                        }
                        if ($key == "h") {
                            $hours = $value;
                        }
                    }
                    $total_minutes = ($hours * 60) + $minutes;
                    $diff = $datetime2->diff($datetime1);
                    $daysInSecs = $diff->format('%r%a') * 24 * 60 * 60;
                    $hoursInSecs = $diff->h * 60 * 60;
                    $minsInSecs = $diff->i * 60;
                    $seconds = $daysInSecs + $hoursInSecs + $minsInSecs + $diff->s;
                    $update_old->end_time = now();
                    if ($total_minutes > 50) {
                        $total_minutes = 50;
                    }
                    $update_old->total_minuts = $total_minutes;
                    $update_old->auditor_time_spend_in_secs = $seconds;
                    $update_old->save();
                }
            }
            $new_ar = new AuditLog;
            $new_ar->auditor_id = Auth::user()->id;
            $new_ar->start_time = now();
            $new_ar->raw_data_id = $comm_instance_id;
            $new_ar->call_id = $data->call_id;
            $new_ar->save();
            return response()->json(['status' => 200, 'message' => "Call found.", 'data' => $data], 200);
        } else {
            return response()->json(['status' => 404, 'message' => "Call not found."], 404);
        }
    }
    public function audited_list($qm_sheet_id)
    {
        $auditor_id = Auth::Id();
        /* $data = Audit::where('qm_sheet_id',Crypt::decrypt($qm_sheet_id))
                       ->whereHas('raw_data', function (Builder $query) use ($auditor_id) {
                        $query->where('qa_id',$auditor_id);
                       })
                       ->with(['raw_data'])
                       ->get(); */

        $data = Audit::whereHas('raw_data', function (Builder $query) use ($auditor_id) {
            $query->where('qa_id', $auditor_id);
        })
            ->with(['raw_data', 'partner'])
            ->orderBy('id', 'DESC')->get();
        //   return response()->json(['status'=>200,'message'=>"Call found.",'data'=>$data], 200); die;
        return view('audit.audit_list', compact('data'));
    }
    public function tmp_audited_list($qm_sheet_id)
    {
        $auditor_id = Auth::Id();
        /* $data = TmpAudit::where('qm_sheet_id',Crypt::decrypt($qm_sheet_id))
                       ->whereHas('raw_data', function (Builder $query) use ($auditor_id) {
                        $query->where('qa_id',$auditor_id);
                       })
                       ->with(['raw_data'])
                       ->get(); */
        $data = TmpAudit::whereHas('raw_data', function (Builder $query) use ($auditor_id) {
            $query->where('qa_id', $auditor_id);
        })->with(['raw_data'])->get();
        return view('audit.tmp_audit_list', compact('data'));
    }
    // public function store_audit(Request $request)
    // {

    //     try {
    //         $check_log = AuditLog::where('auditor_id', Auth::user()->id)->orderBy('created_at', 'DESC')->first();
    //         $seconds = 0;
    //         if ($check_log) {
    //             if (gettype($check_log->end_time) == "NULL") {
    //                 $update_old = AuditLog::find($check_log->id);
    //                 $datetime1 = new DateTime($update_old->created_at);
    //                 $datetime2 = new DateTime(now());
    //                 $interval = $datetime1->diff($datetime2);
    //                 $minuts = 0;
    //                 $hours = 0;
    //                 $total_minutes = 0;

    //                 foreach ($interval as $key => $value) {
    //                     if ($key == "i") {
    //                         $minutes = $value;
    //                     }
    //                     if ($key == "h") {
    //                         $hours = $value;
    //                     }
    //                 }
    //                 $total_minutes = ($hours * 60) + $minutes;
    //                 if ($total_minutes > 50) {
    //                     $total_minutes = 50;
    //                 }
    //                 $diff = $datetime2->diff($datetime1);
    //                 $daysInSecs = $diff->format('%r%a') * 24 * 60 * 60;
    //                 $hoursInSecs = $diff->h * 60 * 60;
    //                 $minsInSecs = $diff->i * 60;
    //                 $seconds = $daysInSecs + $hoursInSecs + $minsInSecs + $diff->s;
    //                 $update_old->end_time = now();
    //                 $update_old->total_minuts = $total_minutes;
    //                 $update_old->auditor_time_spend_in_secs = $seconds;
    //                 $update_old->status = 1;
    //                 $update_old->save();
    //             }
    //         }

    //         $raw_data_id = Audit::where('raw_data_id', $request->submission_data[0]['raw_data_id'])->first();
    //         if (!$raw_data_id && $request->submit) {
    //             $new_ar = new Audit;
    //             $new_ar->company_id = $request->submission_data[0]['company_id'];
    //             $new_ar->client_id = $request->submission_data[0]['client_id'];
    //             $new_ar->partner_id = $request->submission_data[0]['partner_id'];
    //             $new_ar->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
    //             $new_ar->process_id = $request->submission_data[0]['process_id'];
    //             $new_ar->raw_data_id = $request->submission_data[0]['raw_data_id'];
    //             $new_ar->audited_by_id = Crypt::decrypt($request->submission_data[0]['audited_by_id']);
    //             $new_ar->is_critical = $request->submission_data[0]['is_critical'];
    //             // $new_ar->is_partial_marking = $request->submission_data[0]['is_partial_marking'];
    //             $new_ar->overall_score = $request->submission_data[0]['overall_score'];
    //             //$new_ar->audit_date = $request->submission_data[0]['audit_date'];
    //             $new_ar->audit_date = date('Y-m-d H:i:s');
    //             $new_ar->case_id = $request->submission_data[0]['case_id'];
    //             $new_ar->overall_summary = $request->submission_data[0]['overall_summary'];
    //             $new_ar->refrence_number = $request->submission_data[0]['refrence_number'];
    //             if (isset($request->submission_data[0]['error_code'])) {
    //                 $new_ar->error_code = $request->submission_data[0]['error_code'];
    //                 $porterProcess = array(45, 46, 47);
    //                 if (in_array($request->submission_data[0]['process_id'], $porterProcess)) {
    //                     $new_ar->scanerio_codes = $request->submission_data[0]['error_code'];
    //                 }
    //             }
    //             if (isset($request->submission_data[0]['vehicle_type'])) {
    //                 $new_ar->vehicle_type = $request->submission_data[0]['vehicle_type'];
    //             }
    //             if (isset($request->submission_data[0]['new_error_code'])) {

    //                 if($request->submission_data[0]['process_id'] == 45 || $request->submission_data[0]['process_id'] == 47 ){
    //                     $new_ar->new_error_code = implode(",",$request->submission_data[0]['new_error_code']);
    //                     $strWithoutSpaces = $new_ar->new_error_code;
    //                 }else {
    //                     $str = $request->submission_data[0]['new_error_code'];
    //                     $strWithoutSpaces = preg_replace('/\s+/', '', $str);
    //                     $new_ar->new_error_code = $strWithoutSpaces;
    //                 }

    //             }
    //             if(isset($request->submission_data[0]['order_id'])){
    //                 $new_ar->order_id = $request->submission_data[0]['order_id'];
    //             }
    //             if(isset($request->submission_data[0]['order_stage'])){
    //                 $new_ar->order_stage = $request->submission_data[0]['order_stage'];
    //             }
    //             if(isset($request->submission_data[0]['error_reason_type'])){
    //                 $new_ar->error_reason_type = implode(",",$request->submission_data[0]['error_reason_type']);
    //             }
    //             if(isset($request->submission_data[0]['error_reasons'])){
    //                 $new_ar->error_code_reasons = implode(",",$request->submission_data[0]['error_reasons']);
    //             }
    //             if(isset($request->submission_data[0]['issues'])){
    //                 $new_ar->issues = $request->submission_data[0]['issues'];
    //             }
    //             if(isset($request->submission_data[0]['sub_issues'])){
    //                 $new_ar->sub_issues = $request->submission_data[0]['sub_issues'];
    //             }
    //             if(isset($request->submission_data[0]['scanerio'])){
    //                 $new_ar->scanerio = $request->submission_data[0]['scanerio'];
    //             }
    //             if(isset($request->submission_data[0]['scanerio_codes'])){
    //                 $new_ar->scanerio_codes = $request->submission_data[0]['scanerio_codes'];
    //             }


    //             $new_ar->qrc_2 = $request->submission_data[0]['qrc_2'];
    //             $new_ar->language_2 = $request->submission_data[0]['language_2'];
    //             $new_ar->type_id = $request->submission_data[0]['selected_rca_type'];
    //             $new_ar->mode_id = $request->submission_data[0]['selected_rca_mode'];
    //             $new_ar->rca1_id = $request->submission_data[0]['selected_rca1'];
    //             $new_ar->rca2_id = $request->submission_data[0]['selected_rca2'];
    //             $new_ar->rca3_id = $request->submission_data[0]['selected_rca3'];
    //             $new_ar->rca_other_detail = $request->submission_data[0]['rca_other_detail'];
    //             $new_ar->type_2_rca_mode_id = $request->submission_data[0]['selected_type_2_rca_mode'];
    //             $new_ar->type_2_rca1_id = $request->submission_data[0]['selected_type_2_rca1'];
    //             $new_ar->type_2_rca2_id = $request->submission_data[0]['selected_type_2_rca2'];
    //             $new_ar->type_2_rca3_id = $request->submission_data[0]['selected_type_2_rca3'];
    //             $new_ar->type_2_rca_other_detail = $request->submission_data[0]['type_2_rca_other_detail'];
    //             $new_ar->good_bad_call = $request->submission_data[0]['good_bad_call'];
    //             $new_ar->good_bad_call_file = $request->submission_data[0]['good_bad_call_file'];
    //             if ($request->submission_data[0]['is_critical'] == 1)
    //                 $new_ar->with_fatal_score_per = 0;
    //             else
    //                 $new_ar->with_fatal_score_per = $request->submission_data[0]['overall_score'];
    //             //feedback
    //             if (!is_null($request->submission_data[0]['feedback_to_agent'])) {
    //                 $new_ar->feedback_status = 1;
    //                 $new_ar->feedback = $request->submission_data[0]['feedback_to_agent'];
    //             }
    //             //feedback

    //             // function to fetch and save date
    //             $date = date('Y-m-d H:i:s');
    //             $delay1 = $request->submission_data[0]['delay1'];
    //             $delay2 = $request->submission_data[0]['delay2'];
    //             $holiday = $request->submission_data[0]['holiday'];
    //             $result = skipHoliday(strtotime($date), $holiday, $delay1, $delay2);
    //             $new_ar->qc_tat = $result[0];
    //             $new_ar->rebuttal_tat = $result[1];
    //             // function end
    //             $new_ar->auditor_time_spend_in_secs = $seconds;
    //             $new_ar->save();
    //             // update raw data status
    //             $raw_data = RawData::find($request->submission_data[0]['raw_data_id']);
    //             $raw_data->customer_name = $request->submission_data[0]['customer_name'];
    //             $raw_data->phone_number = $request->submission_data[0]['customer_phone'];
    //             $raw_data->disposition = $request->submission_data[0]['disposition'];
    //             $raw_data->call_time = $request->submission_data[0]['call_time'];
    //             $raw_data->call_duration = $request->submission_data[0]['call_duration'];
    //             $raw_data->call_sub_type = $request->submission_data[0]['call_sub_type'];
    //             $raw_data->campaign_name = $request->submission_data[0]['campaign_name'];
    //             $raw_data->status = 1;
    //             $raw_data->save();
    //             if ($new_ar->id) {
    //                 $porterProcess = array(45, 46, 47);
    //                 if (in_array($request->submission_data[0]['process_id'], $porterProcess)) {
    //                     if (isset($request->submission_data[0]['new_error_code'])) {

    //                         if($request->submission_data[0]['process_id'] == 45 || $request->submission_data[0]['process_id'] == 47 ){
    //                             $errorArray  = $request->submission_data[0]['new_error_code'];
    //                             $strWithoutSpaces = $errorArray;

    //                         }else {
    //                             $str = $request->submission_data[0]['new_error_code'];
    //                             $strWithoutSpaces = preg_replace('/\s+/', '', $str);
    //                             $errorArray = explode(',', $strWithoutSpaces);
    //                         }


    //                         // $str = $request->submission_data[0]['new_error_code'];
    //                         // $strWithoutSpaces = preg_replace('/\s+/', '', $str);
    //                         // $errorArray = explode(',', $strWithoutSpaces);
    //                         foreach ($errorArray as $key => $value) {
    //                             $errorCode = ErrorCode::where('error_codes', $value)->first();
    //                             if ($errorCode) {
    //                                 $auditErrorCode = new AuditErrorCode;
    //                                 $auditErrorCode->audit_id = $new_ar->id;
    //                                 $auditErrorCode->error_code = $value;
    //                                 if ($errorCode->experience_impacting) {
    //                                     $auditErrorCode->is_impact = 1;
    //                                 }
    //                                 $auditErrorCode->save();
    //                             }
    //                         }
    //                         $new_ar->new_error_code = $strWithoutSpaces;
    //                     }
    //                 }
    //                 // store parameter wise data
    //                 foreach ($request->parameters as $key => $value) {
    //                     $new_arb = new AuditParameterResult;
    //                     $new_arb->audit_id =  $new_ar->id;
    //                     $new_arb->parameter_id = $key;
    //                     $new_arb->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
    //                     $new_arb->orignal_weight = $value['parameter_weight'];
    //                     $new_arb->temp_weight = $value['temp_total_weightage'];
    //                     $new_arb->with_fatal_score = $value['score_with_fatal'];
    //                     $new_arb->without_fatal_score = $value['score_without_fatal'];
    //                     if ($value['temp_total_weightage'] != 0) {
    //                         $new_arb->with_fatal_score_per = ($value['score_with_fatal'] / $value['temp_total_weightage']) * 100;
    //                         $new_arb->without_fatal_score_pre = ($value['score_without_fatal'] / $value['temp_total_weightage']) * 100;
    //                     }
    //                     $new_arb->is_critical = $value['is_fatal'];
    //                     //  $new_arb->is_partial_marking = $value['is_partial_marking'];
    //                     $new_arb->save();
    //                     // store sub parameter wise data
    //                     foreach ($value['subs'] as $key_sb => $value_sb) {
    //                         if (isset($value_sb['type']) && !empty($value_sb['type'])) {
    //                             $image_64 = $value_sb['type']; //your base64 encoded data
    //                             $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
    //                             $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
    //                             $image = str_replace($replace, '', $image_64);
    //                             $image = str_replace(' ', '+', $image);
    //                             $imageName = rand() . '.' . $extension;
    //                             Storage::disk('public')->put($imageName, base64_decode($image));
    //                         }
    //                         if ($value_sb['selected_option_model'] != '' || $value_sb['selected_option_model'] != null); {
    //                             $new_arc = new AuditResult;
    //                             $new_arc->audit_id =  $new_ar->id;
    //                             $new_arc->parameter_id = $key;
    //                             $new_arc->sub_parameter_id = $key_sb;
    //                             $new_arc->is_critical = $value_sb['is_fatal'];
    //                             // $new_arc->before_is_critical = $value_sb['is_fatal'];
    //                             $new_arc->is_non_scoring = $value_sb['is_non_scoring'];
    //                             $temp_selected_opt = explode("_", $value_sb['selected_option_model']);
    //                             if (count($temp_selected_opt) == 5) {
    //                                 $new_arc->selected_option = $temp_selected_opt[3];
    //                                 // $new_arc->before_selected = $temp_selected_opt[3];
    //                                 if ($temp_selected_opt[3] == 2 || $temp_selected_opt[3] == 3 || $temp_selected_opt[3] == 5 || $temp_selected_opt[3] == 6) {
    //                                     if (isset($value_sb['selected_reason_type']) == 1 && $value_sb['selected_reason_type'] != '' && isset($value_sb['selected_reason']) == 1 && $value_sb['selected_reason'] != '') {
    //                                         $temp_selected_reason_type = explode("_", $value_sb['selected_reason_type']);
    //                                         $new_arc->reason_type_id = $temp_selected_reason_type[2];
    //                                         $new_arc->reason_id = $value_sb['selected_reason'];
    //                                     }
    //                                 }
    //                             } else {
    //                                 $new_arc->selected_option = 0;
    //                                 // $new_arc->before_selected = 0;
    //                                 $new_arc->reason_type_id = null;
    //                                 $new_arc->reason_id = null;
    //                             }
    //                             $new_arc->score = $value_sb['score'];
    //                             $new_arc->after_audit_weight = $value_sb['temp_weight'];
    //                             // $new_arc->before_qc_with_fatal = $value_sb['score'];
    //                             // $new_arc->before_qc_without_fatal = $value_sb['temp_weight'];


    //                             if ($new_arc->is_critical == 1) {
    //                                 $new_arc->screenshot =  (isset($imageName)) ? $imageName : '';
    //                             }
    //                             //   $new_arc->is_partial_marking = $value_sb['is_partial_marking'];
    //                             $new_arc->remark = $value_sb['remark'];
    //                             $new_arc->save();
    //                         }
    //                     }
    //                 }
    //                 // Bajaj AHT Calls
    //                 if ($request->AHTTimes != null) {
    //                     if ($request->submission_data[0]['process_id'] == 88) {
    //                         foreach ($request->AHTTimes as $value) {
    //                             $newAht = new BajajAhtCalls();
    //                             $newAht->audit_id = $new_ar->id;
    //                             $newAht->qa_id = Crypt::decrypt($request->submission_data[0]['audited_by_id']);
    //                             $newAht->aht_time = $value['aht'];
    //                             $newAht->call_id = $value['callId'];
    //                             $newAht->save();
    //                         }
    //                     }
    //                 }
    //             }
    //             return response()->json(['status' => 200, 'message' => "Audit saved successfully."], 200);
    //         } else {
    //             $temp_raw_data_id = TmpAudit::where('raw_data_id', $request->submission_data[0]['raw_data_id'])->first();
    //             if (!$temp_raw_data_id) {
    //                 $new_ar = new TmpAudit;
    //                 $new_ar->company_id = $request->submission_data[0]['company_id'];
    //                 $new_ar->client_id = $request->submission_data[0]['client_id'];
    //                 $new_ar->partner_id = $request->submission_data[0]['partner_id'];
    //                 $new_ar->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
    //                 $new_ar->process_id = $request->submission_data[0]['process_id'];
    //                 $new_ar->raw_data_id = $request->submission_data[0]['raw_data_id'];
    //                 $new_ar->audited_by_id = Crypt::decrypt($request->submission_data[0]['audited_by_id']);
    //                 $new_ar->is_critical = $request->submission_data[0]['is_critical'];
    //                 $new_ar->overall_score = $request->submission_data[0]['overall_score'];
    //                 //$new_ar->audit_date = $request->submission_data[0]['audit_date'];
    //                 $new_ar->audit_date = date('Y-m-d H:i:s');
    //                 $new_ar->case_id = $request->submission_data[0]['case_id'];
    //                 $new_ar->overall_summary = $request->submission_data[0]['overall_summary'];
    //                 $new_ar->refrence_number = $request->submission_data[0]['refrence_number'];
    //                 $new_ar->qrc_2 = $request->submission_data[0]['qrc_2'];
    //                 $new_ar->language_2 = $request->submission_data[0]['language_2'];
    //                 $new_ar->type_id = $request->submission_data[0]['selected_rca_type'];
    //                 $new_ar->mode_id = $request->submission_data[0]['selected_rca_mode'];
    //                 $new_ar->rca1_id = $request->submission_data[0]['selected_rca1'];
    //                 $new_ar->rca2_id = $request->submission_data[0]['selected_rca2'];
    //                 $new_ar->rca3_id = $request->submission_data[0]['selected_rca3'];
    //                 $new_ar->rca_other_detail = $request->submission_data[0]['rca_other_detail'];
    //                 $new_ar->type_2_rca_mode_id = $request->submission_data[0]['selected_type_2_rca_mode'];
    //                 $new_ar->type_2_rca1_id = $request->submission_data[0]['selected_type_2_rca1'];
    //                 $new_ar->type_2_rca2_id = $request->submission_data[0]['selected_type_2_rca2'];
    //                 $new_ar->type_2_rca3_id = $request->submission_data[0]['selected_type_2_rca3'];
    //                 $new_ar->type_2_rca_other_detail = $request->submission_data[0]['type_2_rca_other_detail'];
    //                 $new_ar->good_bad_call = $request->submission_data[0]['good_bad_call'];
    //                 $new_ar->good_bad_call_file = $request->submission_data[0]['good_bad_call_file'];
    //                 if ($request->submission_data[0]['is_critical'] == 1)
    //                     $new_ar->with_fatal_score_per = 0;
    //                 else
    //                     $new_ar->with_fatal_score_per = $request->submission_data[0]['overall_score'];
    //                 //feedback
    //                 if (!is_null($request->submission_data[0]['feedback_to_agent'])) {
    //                     $new_ar->feedback_status = 1;
    //                     $new_ar->feedback = $request->submission_data[0]['feedback_to_agent'];
    //                 }
    //                 //feedback

    //                 if(isset($request->submission_data[0]['vehicle_type'])){


    //                     $new_ar->vehicle_type = $request->submission_data[0]['vehicle_type'];
    //                 }
    //                 if(isset($request->submission_data[0]['new_error_code'])){
    //                     $new_ar->new_error_code = implode(",",$request->submission_data[0]['new_error_code']);                       
    //                 }                  
    //                 if(isset($request->submission_data[0]['order_id'])){
    //                     $new_ar->order_id = $request->submission_data[0]['order_id'];
    //                 }
    //                 if(isset($request->submission_data[0]['caller_type'])){
    //                     $new_ar->caller_type = $request->submission_data[0]['caller_type'];
    //                 }
    //                 if(isset($request->submission_data[0]['order_stage'])){
    //                     $new_ar->order_stage = $request->submission_data[0]['order_stage'];
    //                 }
    //                 if(isset($request->submission_data[0]['error_reason_type'])){
    //                     $new_ar->error_reason_type = implode(",",$request->submission_data[0]['error_reason_type']);
    //                 }
    //                 if(isset($request->submission_data[0]['error_reasons'])){
    //                     $new_ar->error_code_reasons = implode(",",$request->submission_data[0]['error_reasons']);
    //                 }
    //                 if(isset($request->submission_data[0]['issues'])){
    //                     $new_ar->issues = $request->submission_data[0]['issues'];
    //                 }
    //                 if(isset($request->submission_data[0]['sub_issues'])){
    //                     $new_ar->sub_issues = $request->submission_data[0]['sub_issues'];
    //                 }
    //                 if(isset($request->submission_data[0]['scanerio'])){
    //                     $new_ar->scanerio = $request->submission_data[0]['scanerio'];
    //                 }
    //                 if(isset($request->submission_data[0]['scanerio_codes'])){
    //                     $new_ar->scanerio_codes = $request->submission_data[0]['scanerio_codes'];
    //                 }

    //                 // $new_ar->is_partial_marking = $request->submission_data[0]['is_partial_marking'];
    //                 $new_ar->auditor_time_spend_in_secs = $seconds;
    //                 $new_ar->save();
    //                 // update raw data status
    //                 $raw_data = RawData::find($request->submission_data[0]['raw_data_id']);
    //                 $raw_data->customer_name = $request->submission_data[0]['customer_name'];
    //                 $raw_data->phone_number = $request->submission_data[0]['customer_phone'];
    //                 $raw_data->disposition = $request->submission_data[0]['disposition'];
    //                 $raw_data->call_time = $request->submission_data[0]['call_time'];
    //                 $raw_data->call_duration = $request->submission_data[0]['call_duration'];
    //                 $raw_data->call_sub_type = $request->submission_data[0]['call_sub_type'];
    //                 $raw_data->campaign_name = $request->submission_data[0]['campaign_name'];
    //                 $raw_data->status = 1;
    //                 $raw_data->save();
    //                 if ($new_ar->id) {
    //                     // store parameter wise data
    //                     foreach ($request->parameters as $key => $value) {
    //                         $new_arb = new TmpAuditParameterResult;
    //                         $new_arb->audit_id =  $new_ar->id;
    //                         $new_arb->parameter_id = $key;
    //                         $new_arb->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
    //                         $new_arb->orignal_weight = $value['parameter_weight'];
    //                         $new_arb->temp_weight = $value['temp_total_weightage'];
    //                         $new_arb->with_fatal_score = $value['score_with_fatal'];
    //                         $new_arb->without_fatal_score = $value['score_without_fatal'];
    //                         if ($value['temp_total_weightage'] != 0) {
    //                             $new_arb->with_fatal_score_per = ($value['score_with_fatal'] / $value['temp_total_weightage']) * 100;
    //                             $new_arb->without_fatal_score_pre = ($value['score_without_fatal'] / $value['temp_total_weightage']) * 100;
    //                         }
    //                         $new_arb->is_critical = $value['is_fatal'];
    //                         //   $new_arb->is_partial_marking = $value['is_partial_marking'];
    //                         $new_arb->save();
    //                         // store sub parameter wise data
    //                         foreach ($value['subs'] as $key_sb => $value_sb) {
    //                             // added by nisha
    //                             if (isset($value_sb['type']) && !empty($value_sb['type'])) {
    //                                 $image_64 = $value_sb['type']; //your base64 encoded data
    //                                 $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf               
    //                                 $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
    //                                 $image = str_replace($replace, '', $image_64);
    //                                 $image = str_replace(' ', '+', $image);
    //                                 $imageName = rand() . '.' . $extension;
    //                                 Storage::disk('public')->put($imageName, base64_decode($image));
    //                             }
    //                             // end by nisha
    //                             if ($value_sb['selected_option_model'] != '' || $value_sb['selected_option_model'] != null); {
    //                                 $new_arc = new TmpAuditResult;
    //                                 $new_arc->audit_id =  $new_ar->id;
    //                                 $new_arc->parameter_id = $key;
    //                                 $new_arc->sub_parameter_id = $key_sb;
    //                                 $new_arc->is_critical = $value_sb['is_fatal'];
    //                                 //   $new_arc->is_partial_marking = $value_sb['is_partial_marking'];
    //                                 $new_arc->is_non_scoring = $value_sb['is_non_scoring'];
    //                                 $temp_selected_opt = explode("_", $value_sb['selected_option_model']);
    //                                 if (count($temp_selected_opt) == 5) {
    //                                     $new_arc->selected_option = $temp_selected_opt[3];
    //                                     // $new_arc->before_selected = $temp_selected_opt[3];
    //                                     if ($temp_selected_opt[3] == 2 || $temp_selected_opt[3] == 3) {
    //                                         if (isset($value_sb['selected_reason_type']) == 1 && $value_sb['selected_reason_type'] != '' && isset($value_sb['selected_reason']) == 1 && $value_sb['selected_reason'] != '') {
    //                                             $temp_selected_reason_type = explode("_", $value_sb['selected_reason_type']);
    //                                             $new_arc->reason_type_id = $temp_selected_reason_type[2];
    //                                             $new_arc->reason_id = $value_sb['selected_reason'];
    //                                         }
    //                                     }
    //                                 } else {
    //                                     $new_arc->selected_option = 0;
    //                                     // $new_arc->before_selected = 0;
    //                                     $new_arc->reason_type_id = null;
    //                                     $new_arc->reason_id = null;
    //                                 }
    //                                 $new_arc->score = $value_sb['score'];
    //                                 $new_arc->after_audit_weight = $value_sb['temp_weight'];
    //                                 if ($new_arc->is_critical == 1) {
    //                                     $new_arc->screenshot =  (isset($imageName)) ? $imageName : '';
    //                                 }
    //                                 $new_arc->remark = $value_sb['remark'];
    //                                 $new_arc->save();
    //                             }
    //                         }
    //                     }

    //                     // Bajaj AHT Calls
    //                     if ($request->AHTTimes != null) {
    //                         foreach ($request->AHTTimes as $value) {

    //                             $newAht = new BajajAhtCalls();
    //                             $newAht->audit_id = $new_ar->id;
    //                             $newAht->qa_id = Crypt::decrypt($request->submission_data[0]['audited_by_id']);
    //                             $newAht->aht_time = $value['aht'];
    //                             $newAht->call_id = $value['callId'];
    //                             $newAht->save();
    //                         }
    //                     }
    //                 }
    //             }
    //             return response()->json(['status' => 200, 'message' => "Tmp Audit saved successfully."], 200);
    //         }
    //     } catch (\Exception $e) {

    //         if (isset($new_ar)) {
    //             Audit::destroy($new_ar->id);
    //             RawData::where('id', $request->submission_data[0]['raw_data_id'])->update(['status' => 0]);
    //             AuditParameterResult::where('audit_id', $new_ar->id)->delete();
    //             AuditErrorCode::where('audit_id', $new_ar->id)->delete();
    //         }
    //         return response()->json(['status' => false, 'message' => $e->getMessage()]);
    //     }
    // }

    public function store_audit(Request $request)
    {

        try {
            Log::info("StoreAuditCalled");
            $check_log = AuditLog::where('auditor_id', Auth::user()->id)->orderBy('created_at', 'DESC')->first();
            $seconds = 0;
            if ($check_log) {
                if (gettype($check_log->end_time) == "NULL") {
                    $update_old = AuditLog::find($check_log->id);
                    $datetime1 = new DateTime($update_old->created_at);
                    $datetime2 = new DateTime(now());
                    $interval = $datetime1->diff($datetime2);
                    $minuts = 0;
                    $hours = 0;
                    $total_minutes = 0;

                    foreach ($interval as $key => $value) {
                        if ($key == "i") {
                            $minutes = $value;
                        }
                        if ($key == "h") {
                            $hours = $value;
                        }
                    }
                    $total_minutes = ($hours * 60) + $minutes;
                    if ($total_minutes > 50) {
                        $total_minutes = 50;
                    }
                    $diff = $datetime2->diff($datetime1);
                    $daysInSecs = $diff->format('%r%a') * 24 * 60 * 60;
                    $hoursInSecs = $diff->h * 60 * 60;
                    $minsInSecs = $diff->i * 60;
                    $seconds = $daysInSecs + $hoursInSecs + $minsInSecs + $diff->s;
                    $update_old->end_time = now();
                    $update_old->total_minuts = $total_minutes;
                    $update_old->auditor_time_spend_in_secs = $seconds;
                    $update_old->status = 1;
                    $update_old->save();
                }
            }

            $raw_data_id = Audit::where('raw_data_id', $request->submission_data[0]['raw_data_id'])->first();
            if (!$raw_data_id && $request->submit) {
                $new_ar = new Audit;
                $new_ar->company_id = $request->submission_data[0]['company_id'];
                $new_ar->client_id = $request->submission_data[0]['client_id'];
                $new_ar->partner_id = $request->submission_data[0]['partner_id'];
                $new_ar->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
                $new_ar->process_id = $request->submission_data[0]['process_id'];
                $new_ar->raw_data_id = $request->submission_data[0]['raw_data_id'];
                $new_ar->audited_by_id = Crypt::decrypt($request->submission_data[0]['audited_by_id']);
                $new_ar->is_critical = $request->submission_data[0]['is_critical'];
                // $new_ar->is_partial_marking = $request->submission_data[0]['is_partial_marking'];
                $new_ar->overall_score = $request->submission_data[0]['overall_score'];
                $new_ar->without_fatal_score_real = $request->submission_data[0]['overall_score'];

                //$new_ar->audit_date = $request->submission_data[0]['audit_date'];
                $new_ar->audit_date = date('Y-m-d H:i:s');
                $new_ar->case_id = $request->submission_data[0]['case_id'];
                $new_ar->overall_summary = $request->submission_data[0]['overall_summary'];
                $new_ar->refrence_number = $request->submission_data[0]['refrence_number'];



                if (isset($request->submission_data[0]['error_code'])) {
                    $new_ar->error_code = $request->submission_data[0]['error_code'];
                    $porterProcess = array(45, 46, 47);
                    if (in_array($request->submission_data[0]['process_id'], $porterProcess)) {
                        $new_ar->scanerio_codes = $request->submission_data[0]['error_code'];
                    }
                }
                if (isset($request->submission_data[0]['vehicle_type'])) {
                    $new_ar->vehicle_type = $request->submission_data[0]['vehicle_type'];
                }
                if (isset($request->submission_data[0]['recording_link'])) {
                    $new_ar->recording_link = $request->submission_data[0]['recording_link'];
                }
                if (isset($request->submission_data[0]['sfdc_disposition'])) {
                    $new_ar->sfdc_disposition = $request->submission_data[0]['sfdc_disposition'];
                }
                if (isset($request->submission_data[0]['consent_nonconcet_lead'])) {
                    $new_ar->consent_nonconcet_lead = $request->submission_data[0]['consent_nonconcet_lead'];
                }
                if (isset($request->submission_data[0]['call_count'])) {
                    $new_ar->call_count = $request->submission_data[0]['call_count'];
                }
                if (isset($request->submission_data[0]['new_error_code'])) {
                    $str = $request->submission_data[0]['new_error_code'];
                    $strWithoutSpaces = preg_replace('/\s+/', '', $str);
                    $new_ar->new_error_code = $strWithoutSpaces;
                }
                if (isset($request->submission_data[0]['hot_lead'])) {
                    $new_ar->hot_leads = $request->submission_data[0]['hot_lead'];
                }
                if (isset($request->submission_data[0]['hotLead_reason'])) {
                    $new_ar->hot_leads_reason = $request->submission_data[0]['hotLead_reason'];
                }

                if (isset($request->submission_data[0]['confidence_score'])) {
                    $new_ar->confidence_score = $request->submission_data[0]['confidence_score'];
                }
                if (isset($request->submission_data[0]['customer_stt_1'])) {
                    $new_ar->customer_stt_1 = $request->submission_data[0]['customer_stt_1'];
                }
                if (isset($request->submission_data[0]['customer_stt2'])) {
                    $new_ar->customer_stt2 = $request->submission_data[0]['customer_stt2'];
                }
                if (isset($request->submission_data[0]['customer_stt_3'])) {
                    $new_ar->customer_stt_3 = $request->submission_data[0]['customer_stt_3'];
                }
                if (isset($request->submission_data[0]['customer_stt_4'])) {
                    $new_ar->customer_stt_4 = $request->submission_data[0]['customer_stt_4'];
                }
                if (isset($request->submission_data[0]['customer_stt_5'])) {
                    $new_ar->customer_stt_5 = $request->submission_data[0]['customer_stt_5'];
                }
                if (isset($request->submission_data[0]['sent_to_bot_or_not'])) {
                    $new_ar->sent_to_bot_or_not = $request->submission_data[0]['sent_to_bot_or_not'];
                }

                if (isset($request->submission_data[0]['intervention_in_call'])) {
                    $new_ar->intervention_in_call = $request->submission_data[0]['intervention_in_call'];
                }
                if (isset($request->submission_data[0]['intervention_drop_down'])) {
                    $new_ar->intervention_drop_down = $request->submission_data[0]['intervention_drop_down'];
                }
                if (isset($request->submission_data[0]['agent_intervenses_count'])) {
                    $new_ar->agent_intervenses_count = $request->submission_data[0]['agent_intervenses_count'];
                }
                if (isset($request->submission_data[0]['agent_intervenes_reason'])) {
                    $new_ar->agent_intervenes_reason = $request->submission_data[0]['agent_intervenes_reason'];
                }
                if (isset($request->submission_data[0]['info_given_by_sinior'])) {
                    $new_ar->info_given_by_sinior = $request->submission_data[0]['info_given_by_sinior'];
                }
                if (isset($request->submission_data[0]['intervention_remarks'])) {
                    $new_ar->intervention_remarks = $request->submission_data[0]['intervention_remarks'];
                }

                if (isset($request->submission_data[0]['customer_voice'])) {
                    $new_ar->customer_voice = $request->submission_data[0]['customer_voice'];
                }

                if (isset($request->submission_data[0]['consent'])) {
                    $new_ar->consent_nonconcet_lead = $request->submission_data[0]['consent'];
                }
                if (isset($request->submission_data[0]['spdc_dispostion'])) {
                    $new_ar->sfdc_disposition = $request->submission_data[0]['spdc_dispostion'];
                }
                if (isset($request->submission_data[0]['gen_ai'])) {
                    $new_ar->gen_ai = $request->submission_data[0]['gen_ai'];
                }
                if (isset($request->submission_data[0]['variable'])) {
                    $new_ar->variable = $request->submission_data[0]['variable'];
                }
                if (isset($request->submission_data[0]['hot_leads'])) {
                    $new_ar->hot_leads = $request->submission_data[0]['hot_leads'];
                }
                if (isset($request->submission_data[0]['case_dropdown'])) {
                    $new_ar->case_dropdown = $request->submission_data[0]['case_dropdown'];
                }
                if (isset($request->submission_data[0]['caller_type'])) {
                    $new_ar->caller_type = $request->submission_data[0]['caller_type'];
                }
                if (isset($request->submission_data[0]['scanerio'])) {
                    $new_ar->scanerio = $request->submission_data[0]['scanerio'];
                }
                if (isset($request->submission_data[0]['scanerio_codes'])) {
                    $new_ar->scanerio_codes = $request->submission_data[0]['scanerio_codes'];
                }
                if (isset($request->submission_data[0]['l2_ticket_disposition'])) {
                    $new_ar->l2_ticket_disposition = $request->submission_data[0]['l2_ticket_disposition'];
                }

                if (isset($request->submission_data[0]['call_disposition'])) {
                    $new_ar->call_disposition = $request->submission_data[0]['call_disposition'];
                }
                if (isset($request->submission_data[0]['bot_disposition_as_per_logic'])) {
                    $new_ar->bot_disposition_as_per_logic = $request->submission_data[0]['bot_disposition_as_per_logic'];
                }
                if (isset($request->submission_data[0]['case_comment'])) {
                    $new_ar->case_comment = $request->submission_data[0]['case_comment'];
                }
                if (isset($request->submission_data[0]['acpt_l2'])) {
                    $new_ar->acpt_l2 = $request->submission_data[0]['acpt_l2'];
                }

                if (isset($request->submission_data[0]['call_stage'])) {
                    $new_ar->call_stage = $request->submission_data[0]['call_stage'];
                }
                if (isset($request->submission_data[0]['case_reason_if_incorrect'])) {
                    $new_ar->case_reason_if_incorrect = $request->submission_data[0]['case_reason_if_incorrect'];
                }
                if (isset($request->submission_data[0]['empowerment_validation'])) {
                    $new_ar->empowerment_validation = $request->submission_data[0]['empowerment_validation'];
                }
                if (isset($request->submission_data[0]['lan_incorrect_comment'])) {
                    $new_ar->lan_incorrect_comment = $request->submission_data[0]['lan_incorrect_comment'];
                }


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
                if ($request->submission_data[0]['is_critical'] == 1) {

                    $new_ar->with_fatal_score_per = 0;
                    $new_ar->with_fatal_score_real = 0;
                } else {
                    $new_ar->with_fatal_score_per = $request->submission_data[0]['overall_score'];
                    $new_ar->with_fatal_score_real = $request->submission_data[0]['overall_score'];
                }

                if ($request->submission_data[0]['partner_id'] == 153) {
                    if (
                        $request->submission_data[0]['is_critical'] != 1 &&
                        $request->submission_data[0]['overall_score'] == 100
                    ) {
                        $new_ar->feedback_comment = 'Shared';
                        $new_ar->feedback_shared_status = 'Feedback Shared.';
                        $new_ar->feedback_shared_date = date('Y-m-d H:i:s');
                    }
                }
                //feedback
                if (!is_null($request->submission_data[0]['feedback_to_agent'])) {
                    $new_ar->feedback_status = 1;
                    $new_ar->feedback = $request->submission_data[0]['feedback_to_agent'];
                }

                if ($request->submission_data[0]['second_level_drill']) {
                    $new_ar->second_level_drill = $request->submission_data[0]['second_level_drill'];
                }
                //feedback

                // function to fetch and save date
                $date = date('Y-m-d H:i:s');
                $delay1 = $request->submission_data[0]['delay1'];
                $delay2 = $request->submission_data[0]['delay2'];
                $holiday = $request->submission_data[0]['holiday'];
                $result = skipHoliday(strtotime($date), $holiday, $delay1, $delay2);
                $new_ar->qc_tat = $result[0];
                $new_ar->audit_view_date = $result[0];
                $new_ar->rebuttal_tat = $result[1];
                // function end
                $new_ar->auditor_time_spend_in_secs = $seconds;
                if (isset($request->submission_data[0]['secondTimeData'])) {
                    $new_ar->auditor_sheet_time_secs = $request->submission_data[0]['secondTimeData'];
                }

                $new_ar->save();
                // update raw data status
                // $raw_data = RawData::find($request->submission_data[0]['raw_data_id']);
                // $raw_data->customer_name = $request->submission_data[0]['customer_name'];
                // if (isset($request->submission_data[0]['call_type'])) {
                //     $raw_data->call_type = $request->submission_data[0]['call_type'];
                // }
                // if (isset($request->submission_data[0]['relationship_number'])) {
                //     $raw_data->relationship_number = $request->submission_data[0]['relationship_number'];
                // }
                // $raw_data->phone_number = $request->submission_data[0]['customer_phone'];
                // $raw_data->disposition = $request->submission_data[0]['disposition'];
                // $raw_data->call_time = $request->submission_data[0]['call_time'];
                // $raw_data->call_duration = $request->submission_data[0]['call_duration'];
                // $raw_data->call_sub_type = $request->submission_data[0]['call_sub_type'];
                // $raw_data->campaign_name = $request->submission_data[0]['campaign_name'];
                // if (isset($request->submission_data[0]['language'])) {
                //     $raw_data->language = $request->submission_data[0]['language'];
                // }
                //  if (isset($request->submission_data[0]['event_c'])) {
                //     $raw_data->event_c = $request->submission_data[0]['event_c'];
                // }
                // if (isset($request->submission_data[0]['doj'])) {
                //     $raw_data->doj = $request->submission_data[0]['doj'];
                // }
                // if (isset($request->submission_data[0]['call_duration'])) {
                //     $raw_data->call_duration = $request->submission_data[0]['call_duration'];
                // }
                // if (isset($request->submission_data[0]['agent_name'])) {
                //     $raw_data->agent_name = $request->submission_data[0]['agent_name'];
                // }
                // if (isset($request->submission_data[0]['agent_id'])) {
                //     $raw_data->agent_id = $request->submission_data[0]['agent_id'];
                // }

                // if (isset($request->submission_data[0]['case_create_date'])) {
                //     $raw_data->case_create_date = $request->submission_data[0]['case_create_date'];
                // }
                // if (isset($request->submission_data[0]['close_date'])) {
                //     $raw_data->close_date = $request->submission_data[0]['close_date'];
                // }


                // if (isset($request->submission_data[0]['audit_type'])) {
                //     $raw_data->audit_type = $request->submission_data[0]['audit_type'];
                // }
                // if (isset($request->submission_data[0]['scenerio'])) {
                //     $raw_data->scenerio = $request->submission_data[0]['scenerio'];
                // }
                // if (isset($request->submission_data[0]['scenerio_code'])) {
                //     $raw_data->scenerio_code = $request->submission_data[0]['scenerio_code'];
                // }
                // if (isset($request->submission_data[0]['error_reason_type'])) {
                //     $raw_data->error_reason_type = $request->submission_data[0]['error_reason_type'];
                // }
                // if (isset($request->submission_data[0]['solution'])) {
                //     $raw_data->solution = $request->submission_data[0]['solution'];
                // }
                // if (isset($request->submission_data[0]['issues'])) {
                //     $raw_data->issues = $request->submission_data[0]['issues'];
                // }
                // if (isset($request->submission_data[0]['sub_issues'])) {
                //     $raw_data->sub_issues = $request->submission_data[0]['sub_issues'];
                // }
                // if (isset($request->submission_data[0]['solution_based_category_c'])) {
                //     $raw_data->solution_based_category_c = $request->submission_data[0]['solution_based_category_c'];
                // }
                // if (isset($request->submission_data[0]['reference_no'])) {
                //     $raw_data->reference_no = $request->submission_data[0]['reference_no'];
                // }

                $raw_data = RawData::find($request->submission_data[0]['raw_data_id']);


                if (isset($request->submission_data[0]['customer_name'])) {
                    $raw_data->customer_name = $request->submission_data[0]['customer_name'];
                }

                if (isset($request->submission_data[0]['customer_phone'])) {
                    $raw_data->phone_number = $request->submission_data[0]['customer_phone'];
                }

                if (isset($request->submission_data[0]['disposition'])) {
                    $raw_data->disposition = $request->submission_data[0]['disposition'];
                }

                if (isset($request->submission_data[0]['call_time'])) {
                    $raw_data->call_time = $request->submission_data[0]['call_time'];
                }

                if (isset($request->submission_data[0]['call_duration'])) {
                    $raw_data->call_duration = $request->submission_data[0]['call_duration'];
                }
                if (isset($request->submission_data[0]['case_origin_channel'])) {
                    $raw_data->case_origin_channel = $request->submission_data[0]['case_origin_channel'];
                }
                if (isset($request->submission_data[0]['vendor_name'])) {
                    $raw_data->vendor_name = $request->submission_data[0]['vendor_name'];
                }
                if (isset($request->submission_data[0]['call_sub_type'])) {
                    $raw_data->call_sub_type = $request->submission_data[0]['call_sub_type'];
                }

                if (isset($request->submission_data[0]['campaign_name'])) {
                    $raw_data->campaign_name = $request->submission_data[0]['campaign_name'];
                }
                if (isset($request->submission_data[0]['call_type'])) {
                    $raw_data->call_type = $request->submission_data[0]['call_type'];
                }
                if (isset($request->submission_data[0]['relationship_number'])) {
                    $raw_data->relationship_number = $request->submission_data[0]['relationship_number'];
                }
                if (isset($request->submission_data[0]['language'])) {
                    $raw_data->language = $request->submission_data[0]['language'];
                }
                if (isset($request->submission_data[0]['event_c'])) {
                    $raw_data->event_c = $request->submission_data[0]['event_c'];
                }
                if (isset($request->submission_data[0]['doj'])) {
                    $raw_data->doj = $request->submission_data[0]['doj'];
                }
                if (isset($request->submission_data[0]['call_duration'])) {
                    $raw_data->call_duration = $request->submission_data[0]['call_duration'];
                }
                if (isset($request->submission_data[0]['agent_name'])) {
                    $raw_data->agent_name = $request->submission_data[0]['agent_name'];
                }
                if (isset($request->submission_data[0]['agent_id'])) {
                    $raw_data->agent_id = $request->submission_data[0]['agent_id'];
                }

                if (isset($request->submission_data[0]['case_create_date'])) {
                    $raw_data->case_create_date = $request->submission_data[0]['case_create_date'];
                }
                if (isset($request->submission_data[0]['close_date'])) {
                    $raw_data->close_date = $request->submission_data[0]['close_date'];
                }


                if (isset($request->submission_data[0]['audit_type'])) {
                    $raw_data->audit_type = $request->submission_data[0]['audit_type'];
                }
                if (isset($request->submission_data[0]['scenerio'])) {
                    $raw_data->scenerio = $request->submission_data[0]['scenerio'];
                }
                if (isset($request->submission_data[0]['scenerio_code'])) {
                    $raw_data->scenerio_code = $request->submission_data[0]['scenerio_code'];
                }
                if (isset($request->submission_data[0]['error_reason_type'])) {
                    $raw_data->error_reason_type = $request->submission_data[0]['error_reason_type'];
                }
                if (isset($request->submission_data[0]['solution'])) {
                    $raw_data->solution = $request->submission_data[0]['solution'];
                }
                if (isset($request->submission_data[0]['issues'])) {
                    $raw_data->issues = $request->submission_data[0]['issues'];
                }
                if (isset($request->submission_data[0]['sub_issues'])) {
                    $raw_data->sub_issues = $request->submission_data[0]['sub_issues'];
                }
                if (isset($request->submission_data[0]['solution_based_category_c'])) {
                    $raw_data->solution_based_category_c = $request->submission_data[0]['solution_based_category_c'];
                }
                if (isset($request->submission_data[0]['reference_no'])) {
                    $raw_data->reference_no = $request->submission_data[0]['reference_no'];
                }

                // added due to rcc sheet of bfl
                if (isset($request->submission_data[0]['qrc_1'])) {
                    $raw_data->qrc_1 = $request->submission_data[0]['qrc_1'];
                }

                if (isset($request->submission_data[0]['bfl_process'])) {
                    $raw_data->bfl_process = $request->submission_data[0]['bfl_process'];
                }

                if (isset($request->submission_data[0]['opened_date'])) {
                    $raw_data->opened_date = $request->submission_data[0]['opened_date'];
                }

                if (isset($request->submission_data[0]['emp_id'])) {
                    $raw_data->emp_id = $request->submission_data[0]['emp_id'];
                }

                if (isset($request->submission_data[0]['auditor_name'])) {
                    $raw_data->auditor_name = $request->submission_data[0]['auditor_name'];
                }

                if (isset($request->submission_data[0]['location'])) {
                    $raw_data->location = $request->submission_data[0]['location'];
                }

                if (isset($request->submission_data[0]['call_id'])) {
                    $raw_data->call_id = $request->submission_data[0]['call_id'];
                }

                if (isset($request->submission_data[0]['case_status'])) {
                    $raw_data->case_status = $request->submission_data[0]['case_status'];
                }

                if (isset($request->submission_data[0]['case_type'])) {
                    $raw_data->case_type = $request->submission_data[0]['case_type'];
                }

                if (isset($request->submission_data[0]['case_reason'])) {
                    $raw_data->case_reason = $request->submission_data[0]['case_reason'];
                }

                if (isset($request->submission_data[0]['customer_id'])) {
                    $raw_data->customer_id = $request->submission_data[0]['customer_id'];
                }

                if (isset($request->submission_data[0]['brand_name'])) {
                    $raw_data->brand_name = $request->submission_data[0]['brand_name'];
                }

                if (isset($request->submission_data[0]['order_stage'])) {
                    $raw_data->order_stage = $request->submission_data[0]['order_stage'];
                }

                if (isset($request->submission_data[0]['sr_mgr'])) {
                    $raw_data->sr_mgr = $request->submission_data[0]['sr_mgr'];
                }

                if (isset($request->submission_data[0]['fpr'])) {
                    $raw_data->fpr = $request->submission_data[0]['fpr'];
                }

                if (isset($request->submission_data[0]['hrs_tat_bucket'])) {
                    $raw_data->hrs_tat_bucket = $request->submission_data[0]['hrs_tat_bucket'];
                }

                if (isset($request->submission_data[0]['info_1'])) {
                    $raw_data->info_1 = $request->submission_data[0]['info_1'];
                }

                if (isset($request->submission_data[0]['info_2'])) {
                    $raw_data->info_2 = $request->submission_data[0]['info_2'];
                }

                if (isset($request->submission_data[0]['info_3'])) {
                    $raw_data->info_3 = $request->submission_data[0]['info_3'];
                }

                if (isset($request->submission_data[0]['info_4'])) {
                    $raw_data->info_4 = $request->submission_data[0]['info_4'];
                }

                if (isset($request->submission_data[0]['info_5'])) {
                    $raw_data->info_5 = $request->submission_data[0]['info_5'];
                }

                if (isset($request->submission_data[0]['error_code_reason'])) {
                    $raw_data->error_code_reason = $request->submission_data[0]['error_code_reason'];
                }

                if (isset($request->submission_data[0]['case_reason_when_case_closed'])) {
                    $raw_data->case_reason_when_case_closed = $request->submission_data[0]['case_reason_when_case_closed'];
                }
                if (isset($request->submission_data[0]['bfl_tl_name'])) {
                    $raw_data->bfl_tl_name = $request->submission_data[0]['bfl_tl_name'];
                }

                if (isset($request->submission_data[0]['product_id'])) {
                    $raw_data->product_id = $request->submission_data[0]['product_id'];
                }

                $raw_data->status = 1;
                $raw_data->save();
                if ($new_ar->id) {
                    $porterProcess = array(45, 46, 47);
                    if (in_array($request->submission_data[0]['process_id'], $porterProcess)) {
                        if (isset($request->submission_data[0]['new_error_code'])) {
                            $str = $request->submission_data[0]['new_error_code'];
                            $strWithoutSpaces = preg_replace('/\s+/', '', $str);
                            $errorArray = explode(',', $strWithoutSpaces);
                            foreach ($errorArray as $key => $value) {
                                $errorCode = ErrorCode::where('error_codes', $value)->first();
                                if ($errorCode) {
                                    $auditErrorCode = new AuditErrorCode;
                                    $auditErrorCode->audit_id = $new_ar->id;
                                    $auditErrorCode->error_code = $value;
                                    if ($errorCode->experience_impacting) {
                                        $auditErrorCode->is_impact = 1;
                                    }
                                    $auditErrorCode->save();
                                }
                            }
                            $new_ar->new_error_code = $strWithoutSpaces;
                        }
                    }
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
                        if ($value['temp_total_weightage'] != 0) {
                            $new_arb->with_fatal_score_per = ($value['score_with_fatal'] / $value['temp_total_weightage']) * 100;
                            $new_arb->without_fatal_score_pre = ($value['score_without_fatal'] / $value['temp_total_weightage']) * 100;
                        }
                        $new_arb->is_critical = $value['is_fatal'];
                        //  $new_arb->is_partial_marking = $value['is_partial_marking'];
                        $new_arb->save();
                        // store sub parameter wise data
                        foreach ($value['subs'] as $key_sb => $value_sb) {
                            if (isset($value_sb['type']) && !empty($value_sb['type'])) {
                                $image_64 = $value_sb['type']; //your base64 encoded data
                                $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
                                $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
                                $image = str_replace($replace, '', $image_64);
                                $image = str_replace(' ', '+', $image);
                                $imageName = rand() . '.' . $extension;
                                Storage::disk('public')->put($imageName, base64_decode($image));
                            }
                            if ($value_sb['selected_option_model'] != '' || $value_sb['selected_option_model'] != null); {
                                $new_arc = new AuditResult;
                                $new_arc->audit_id =  $new_ar->id;
                                $new_arc->parameter_id = $key;
                                $new_arc->sub_parameter_id = $key_sb;
                                $new_arc->is_critical = $value_sb['is_fatal'];
                                // $new_arc->before_is_critical = $value_sb['is_fatal'];
                                $new_arc->is_non_scoring = $value_sb['is_non_scoring'];
                                $temp_selected_opt = explode("_", $value_sb['selected_option_model']);
                                if (count($temp_selected_opt) == 5) {
                                    $new_arc->selected_option = $temp_selected_opt[3];
                                    $new_arc->before_selected = $temp_selected_opt[3];
                                    if ($temp_selected_opt[3] == 2 || $temp_selected_opt[3] == 3 || $temp_selected_opt[3] == 5 || $temp_selected_opt[3] == 6 || $temp_selected_opt[3] == 1) {
                                        if (isset($value_sb['selected_reason_type']) == 1 && $value_sb['selected_reason_type'] != '' && isset($value_sb['selected_reason']) == 1 && $value_sb['selected_reason'] != '') {
                                            $temp_selected_reason_type = explode("_", $value_sb['selected_reason_type']);
                                            $new_arc->reason_type_id = $temp_selected_reason_type[2];
                                            $new_arc->reason_id = $value_sb['selected_reason'];
                                        }
                                    }
                                } else {
                                    $new_arc->selected_option = 0;
                                    $new_arc->before_selected = 0;
                                    $new_arc->reason_type_id = null;
                                    $new_arc->reason_id = null;
                                }
                                $new_arc->score = $value_sb['score'];
                                $new_arc->after_audit_weight = $value_sb['temp_weight'];
                                $new_arc->before_qc_with_fatal = $value_sb['score'];
                                $new_arc->before_qc_without_fatal = $value_sb['temp_weight'];


                                if ($new_arc->is_critical == 1) {
                                    $new_arc->screenshot =  (isset($imageName)) ? $imageName : '';
                                }
                                //   $new_arc->is_partial_marking = $value_sb['is_partial_marking'];
                                $new_arc->remark = $value_sb['remark'];
                                $new_arc->save();
                            }
                        }
                    }
                    // Bajaj AHT Calls
                    if ($request->AHTTimes != null) {
                        // if ($request->submission_data[0]['process_id'] == 88||$request->submission_data[0]['process_id'] == 120  ) {
                        foreach ($request->AHTTimes as $value) {
                            $newAht = new BajajAhtCalls();
                            $newAht->audit_id = $new_ar->id;
                            $newAht->qa_id = Crypt::decrypt($request->submission_data[0]['audited_by_id']);
                            $newAht->aht_time = $value['aht'];
                            $newAht->call_id = $value['callId'];
                            $newAht->save();
                        }
                        // }
                    }
                }
                return response()->json(['status' => 200, 'message' => "Audit saved successfully."], 200);
            } else {
                $temp_raw_data_id = TmpAudit::where('raw_data_id', $request->submission_data[0]['raw_data_id'])->first();
                if (!$temp_raw_data_id) {
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

                    if (isset($request->submission_data[0]['vehicle_type'])) {
                        $new_ar->vehicle_type = $request->submission_data[0]['vehicle_type'];
                    }
                    if (isset($request->submission_data[0]['customer_voice'])) {
                        $new_ar->customer_voice = $request->submission_data[0]['customer_voice'];
                    }
                    if ($request->submission_data[0]['is_critical'] == 1)
                        $new_ar->with_fatal_score_per = 0;
                    else
                        $new_ar->with_fatal_score_per = $request->submission_data[0]['overall_score'];
                    //feedback
                    if (!is_null($request->submission_data[0]['feedback_to_agent'])) {
                        $new_ar->feedback_status = 1;
                        $new_ar->feedback = $request->submission_data[0]['feedback_to_agent'];
                    }
                    //feedback

                    // $new_ar->is_partial_marking = $request->submission_data[0]['is_partial_marking'];
                    if ($seconds > 0) {
                        $new_ar->auditor_time_spend_in_secs = $seconds;
                    } else {
                        $new_ar->auditor_time_spend_in_secs = 400;
                    }
                    $new_ar->save();
                    // update raw data status
                    $raw_data = RawData::find($request->submission_data[0]['raw_data_id']);
                    $raw_data->customer_name = $request->submission_data[0]['customer_name'];
                    if (isset($request->submission_data[0]['call_type'])) {
                        $raw_data->call_type = $request->submission_data[0]['call_type'];
                    }
                    if (isset($request->submission_data[0]['relationship_number'])) {
                        $raw_data->relationship_number = $request->submission_data[0]['relationship_number'];
                    }
                    $raw_data->phone_number = $request->submission_data[0]['customer_phone'];
                    $raw_data->disposition = $request->submission_data[0]['disposition'];
                    $raw_data->call_time = $request->submission_data[0]['call_time'];
                    $raw_data->call_duration = $request->submission_data[0]['call_duration'];
                    $raw_data->call_sub_type = $request->submission_data[0]['call_sub_type'];
                    $raw_data->campaign_name = $request->submission_data[0]['campaign_name'];
                    $raw_data->status = 1;
                    $raw_data->save();
                    if ($new_ar->id) {
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
                            if ($value['temp_total_weightage'] != 0) {
                                $new_arb->with_fatal_score_per = ($value['score_with_fatal'] / $value['temp_total_weightage']) * 100;
                                $new_arb->without_fatal_score_pre = ($value['score_without_fatal'] / $value['temp_total_weightage']) * 100;
                            }
                            $new_arb->is_critical = $value['is_fatal'];
                            //   $new_arb->is_partial_marking = $value['is_partial_marking'];
                            $new_arb->save();
                            // store sub parameter wise data
                            foreach ($value['subs'] as $key_sb => $value_sb) {
                                // added by nisha
                                if (isset($value_sb['type']) && !empty($value_sb['type'])) {
                                    $image_64 = $value_sb['type']; //your base64 encoded data
                                    $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
                                    $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
                                    $image = str_replace($replace, '', $image_64);
                                    $image = str_replace(' ', '+', $image);
                                    $imageName = rand() . '.' . $extension;
                                    Storage::disk('public')->put($imageName, base64_decode($image));
                                }
                                // end by nisha
                                if ($value_sb['selected_option_model'] != '' || $value_sb['selected_option_model'] != null); {
                                    $new_arc = new TmpAuditResult;
                                    $new_arc->audit_id =  $new_ar->id;
                                    $new_arc->parameter_id = $key;
                                    $new_arc->sub_parameter_id = $key_sb;
                                    $new_arc->is_critical = $value_sb['is_fatal'];
                                    //   $new_arc->is_partial_marking = $value_sb['is_partial_marking'];
                                    $new_arc->is_non_scoring = $value_sb['is_non_scoring'];
                                    $temp_selected_opt = explode("_", $value_sb['selected_option_model']);
                                    if (count($temp_selected_opt) == 5) {
                                        $new_arc->selected_option = $temp_selected_opt[3];
                                        // $new_arc->before_selected = $temp_selected_opt[3];
                                        if ($temp_selected_opt[3] == 2 || $temp_selected_opt[3] == 3) {
                                            if (isset($value_sb['selected_reason_type']) == 1 && $value_sb['selected_reason_type'] != '' && isset($value_sb['selected_reason']) == 1 && $value_sb['selected_reason'] != '') {
                                                $temp_selected_reason_type = explode("_", $value_sb['selected_reason_type']);
                                                $new_arc->reason_type_id = $temp_selected_reason_type[2];
                                                $new_arc->reason_id = $value_sb['selected_reason'];
                                            }
                                        }
                                    } else {
                                        $new_arc->selected_option = 0;
                                        // $new_arc->before_selected = 0;
                                        $new_arc->reason_type_id = null;
                                        $new_arc->reason_id = null;
                                    }
                                    $new_arc->score = $value_sb['score'];
                                    $new_arc->after_audit_weight = $value_sb['temp_weight'];
                                    if ($new_arc->is_critical == 1) {
                                        $new_arc->screenshot =  (isset($imageName)) ? $imageName : '';
                                    }
                                    $new_arc->remark = $value_sb['remark'];
                                    $new_arc->save();
                                }
                            }
                        }

                        // Bajaj AHT Calls
                        if ($request->AHTTimes != null) {
                            foreach ($request->AHTTimes as $value) {

                                $newAht = new BajajAhtCalls();
                                $newAht->audit_id = $new_ar->id;
                                $newAht->qa_id = Crypt::decrypt($request->submission_data[0]['audited_by_id']);
                                $newAht->aht_time = $value['aht'];
                                $newAht->call_id = $value['callId'];
                                $newAht->save();
                            }
                        }
                        if ($request->BotTimes != null) {
                            if ($request->submission_data[0]['process_id'] == 88) {
                                foreach ($request->BotTimes as $value) {
                                    $newAht = new BajajBotCall();
                                    $newAht->audit_id = $new_ar->id;

                                    $newAht->confidenceScore = $value['confidenceScore'];
                                    $newAht->words = $value['words'];
                                    $newAht->bot_status = $value['bot_status'];
                                    $newAht->save();
                                }
                            }
                        }
                    }
                }
                return response()->json(['status' => 200, 'message' => "Tmp Audit saved successfully."], 200);
            }
        } catch (\Exception $th) {
            Log::info("StoreAuditCalledCatch");
            Log::info("errorMessage: " . $th->getMessage());
            Log::info("RawData ID: " . $request->submission_data[0]['raw_data_id']);
            if (isset($new_ar)) {
                $deleteAudit = Audit::find($new_ar->id);
                $deleteAudit->deleted = 1;
                $deleteAudit->save();

                RawData::where('id', $request->submission_data[0]['raw_data_id'])->update(['status' => 0, 'deleted' => 1]);
                Log::info("errorMessageEndHere:");
                // $incom_audit = new IncompleteAudit();
                // $incom_audit->audit_id = $new_ar->id;
                // $incom_audit->client_id = $request->submission_data[0]['client_id'];
                // $incom_audit->save();     
                // AuditParameterResult::where('audit_id', $new_ar->id)->delete();
                // AuditResult::where('audit_id', $new_ar->id)->delete();
                // BajajAhtCalls::where('audit_id', $new_ar->id)->delete();
                // AuditErrorCode::where('audit_id', $new_ar->id)->delete();
                // Audit::destroy($new_ar->id);
                // RawData::where('id', $request->submission_data[0]['raw_data_id'])->update(['status' => 0]);
            }
            return response()->json(['status' => false, 'message' => $th->getMessage()]);
        }
    }

    //  pop up

    public function store_audit_rcc(Request $request)
    {

        try {
            Log::info("in ty");
            $check_log = AuditLog::where('auditor_id', Auth::user()->id)->orderBy('created_at', 'DESC')->first();
            $seconds = 0;
            if ($check_log) {
                Log::info("in if:");
                if (gettype($check_log->end_time) == "NULL") {
                    $update_old = AuditLog::find($check_log->id);
                    $datetime1 = new DateTime($update_old->created_at);
                    $datetime2 = new DateTime(now());
                    $interval = $datetime1->diff($datetime2);
                    $minuts = 0;
                    $hours = 0;
                    $total_minutes = 0;

                    foreach ($interval as $key => $value) {
                        if ($key == "i") {
                            $minutes = $value;
                        }
                        if ($key == "h") {
                            $hours = $value;
                        }
                    }
                    $total_minutes = ($hours * 60) + $minutes;
                    if ($total_minutes > 50) {
                        $total_minutes = 50;
                    }
                    $diff = $datetime2->diff($datetime1);
                    $daysInSecs = $diff->format('%r%a') * 24 * 60 * 60;
                    $hoursInSecs = $diff->h * 60 * 60;
                    $minsInSecs = $diff->i * 60;
                    $seconds = $daysInSecs + $hoursInSecs + $minsInSecs + $diff->s;
                    $update_old->end_time = now();
                    $update_old->total_minuts = $total_minutes;
                    $update_old->auditor_time_spend_in_secs = $seconds;
                    $update_old->status = 1;
                    $update_old->save();
                }
            }

            $submission = $request->submission_data[0];

            $raw_data = new RawData();

            $raw_data->dump_date  = date('Y-m-d');
            $raw_data->company_id = 1;
            $raw_data->client_id  = 23;

            $specialProcesses = [224, 225, 226, 227, 228, 229];

            $raw_data->partner_id = in_array($submission['process_id'], $specialProcesses)
                ? 153
                : 153;

            $raw_data->qtl_id = in_array($submission['process_id'], $specialProcesses)
                ? 3539
                : $submission['tl_name'];

            $raw_data->process_id = $submission['process_id'];
            $raw_data->qa_id      = Crypt::decrypt($submission['audited_by_id']);

            // $raw_data->partner_location_id = 36 ?? null;
            // $raw_data->location   = 'Pune' ?? null;
            $processId = $submission['process_id'];


            if (in_array($processId, [228, 229, 264, 267])) {
                $raw_data->partner_location_id = 31;
                $raw_data->location = 'Jamshedpur';
            } elseif (in_array($processId, [224, 225])) {
                $raw_data->partner_location_id = 61;
                $raw_data->location = 'Bareilly';
            } elseif (in_array($processId, [266, 252, 253, 254, 255, 256, 257, 258, 259, 260, 268, 274, 275, 276])) {
                $raw_data->partner_location_id = 67;
                $raw_data->location = 'Mangalore';
            } else {
                $raw_data->partner_location_id = 36;
                $raw_data->location = 'Pune';
            }

            $requiredFields = [
                'call_id',
                'agent_name',
                'emp_id',
                'lob',
                'language',
                'call_time',
                'call_duration',
                'call_sub_type',
                'disposition',
                'campaign_name',
                'hangup_details',
                'customer_name',
                'call_type',
            ];

            foreach ($requiredFields as $field) {
                $raw_data->$field = $submission[$field] ?? null;
            }

            $raw_data->phone_number = $submission['customer_phone'] ?? null;


            $optionalFields = [
                'audit_type',
                'focusd_sub_type',
                'month',
                'user_name',
                'case_reason',
                'bfl_process',
                'customer_id',
                'vendor_name',
                'case_create_date',
                'internal_case_reason_c',
                'solution_based_category_c',
                'tl',
                'sr_mgr',

                // Newly added columns
                'auditor_id',
                'auditor_name',
                'case_type',
                'bfl_tl_name',
                'case_status',
                'case_number',
                'case_origin',
                'relationship_number',
                'overdue_wavier_amount',
                'wavier_status',
                'wavier_amount',
                'other_charges',
                'cbc',
                'lpp',
                'email',
                'vertical',
                'diff',
                'solution'
            ];

            foreach ($optionalFields as $field) {
                if (isset($submission[$field])) {
                    $raw_data->$field = $submission[$field];
                }
            }


            if (isset($submission['focusd_sub_type'])) {
                $raw_data->sub_issues = $submission['focusd_sub_type'];
            }

            $raw_data->tl = $submission['tl'] ?? 'NA';

            $raw_data->visiblity = 1;
            $raw_data->doj       = 'NA';
            $raw_data->circle    = 'Pune';
            $raw_data->brand_name = 'BFL';

            $raw_data->info_1 = 'NA';
            $raw_data->info_2 = 'NA';
            $raw_data->info_3 = 'NA';
            $raw_data->info_4 = 'NA';
            $raw_data->info_5 = 'NA';

            $raw_data->status = 1;

            Log::info('above rawdata save:');
            $raw_data->save();
            Log::info("below rawdata save:");

            $new_ar = new Audit;
            $new_ar->company_id = $request->submission_data[0]['company_id'];
            $new_ar->client_id = $request->submission_data[0]['client_id'];

            $new_ar->partner_id = 153;


            $new_ar->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
            $new_ar->process_id = $request->submission_data[0]['process_id'];
            $new_ar->raw_data_id = $raw_data->id;
            $new_ar->audited_by_id = Crypt::decrypt($request->submission_data[0]['audited_by_id']);
            $new_ar->is_critical = $request->submission_data[0]['is_critical'];
            // $new_ar->is_partial_marking = $request->submission_data[0]['is_partial_marking'];
            $new_ar->overall_score = $request->submission_data[0]['overall_score'];
            //$new_ar->audit_date = $request->submission_data[0]['audit_date'];
            $new_ar->audit_date = date('Y-m-d H:i:s');
            $new_ar->case_id = $request->submission_data[0]['case_id'];
            $new_ar->overall_summary = $request->submission_data[0]['overall_summary'];
            $new_ar->refrence_number = $request->submission_data[0]['refrence_number'];
            $new_ar->gender_identification = $request->submission_data[0]['gender_identifivation'];
            $new_ar->bot_identification_on_gender = $request->submission_data[0]['gender_identifivation_bot'];
            $new_ar->customer_voice = $request->submission_data[0]['customer_voice'];

            if (isset($request->submission_data[0]['confidence_score'])) {
                $new_ar->confidence_score = $request->submission_data[0]['confidence_score'];
            }
            if (isset($request->submission_data[0]['focused_audit_text'])) {
                $new_ar->focused_audit_text = $request->submission_data[0]['focused_audit_text'];
            }
            if (isset($request->submission_data[0]['sfdc_disposition'])) {
                $new_ar->sfdc_disposition = $request->submission_data[0]['sfdc_disposition'];
            }
            if (isset($request->submission_data[0]['sfdc_disposition'])) {
                $new_ar->sfdc_disposition = $request->submission_data[0]['sfdc_disposition'];
            }
            if (isset($request->submission_data[0]['acpt'])) {
                $new_ar->acpt = $request->submission_data[0]['acpt'];
            }
            if (isset($request->submission_data[0]['acpt_l2'])) {
                $new_ar->acpt_l2 = $request->submission_data[0]['acpt_l2'];
            }
            if (isset($request->submission_data[0]['focused_audit_text'])) {
                $new_ar->focused_audit_text = $request->submission_data[0]['focused_audit_text'];
            }
            if (isset($request->submission_data[0]['case_comment'])) {
                $new_ar->case_comment = $request->submission_data[0]['case_comment'];
            }
            if (isset($request->submission_data[0]['call_stage'])) {
                $new_ar->call_stage = $request->submission_data[0]['call_stage'];
            }
            if (isset($request->submission_data[0]['gen_ai'])) {
                $new_ar->gen_ai = $request->submission_data[0]['gen_ai'];
            }
            if (isset($request->submission_data[0]['variable'])) {
                $new_ar->variable = $request->submission_data[0]['variable'];
            }

            if (isset($request->submission_data[0]['customer_stt_1'])) {
                $new_ar->customer_stt_1 = $request->submission_data[0]['customer_stt_1'];
            }
            if (isset($request->submission_data[0]['customer_stt2'])) {
                $new_ar->customer_stt2 = $request->submission_data[0]['customer_stt2'];
            }
            if (isset($request->submission_data[0]['customer_stt_3'])) {
                $new_ar->customer_stt_3 = $request->submission_data[0]['customer_stt_3'];
            }
            if (isset($request->submission_data[0]['customer_stt_4'])) {
                $new_ar->customer_stt_4 = $request->submission_data[0]['customer_stt_4'];
            }
            if (isset($request->submission_data[0]['customer_stt_5'])) {
                $new_ar->customer_stt_5 = $request->submission_data[0]['customer_stt_5'];
            }
            if (isset($request->submission_data[0]['sent_to_bot_or_not'])) {
                $new_ar->sent_to_bot_or_not = $request->submission_data[0]['sent_to_bot_or_not'];
            }
            if (isset($request->submission_data[0]['disconnected_status'])) {
                $new_ar->disconnected_status = $request->submission_data[0]['disconnected_status'];
            }
            if (isset($request->submission_data[0]['call_stage'])) {
                $new_ar->call_stage = $request->submission_data[0]['call_stage'];
            }
            if (isset($request->submission_data[0]['call_disconnected_by'])) {
                $new_ar->call_disconnected_by = $request->submission_data[0]['call_disconnected_by'];
            }
            if (isset($request->submission_data[0]['bot_disposition_as_per_logic'])) {
                $new_ar->bot_disposition_as_per_logic = $request->submission_data[0]['bot_disposition_as_per_logic'];
            }


            if (isset($request->submission_data[0]['error_code'])) {
                $new_ar->error_code = $request->submission_data[0]['error_code'];
                $porterProcess = array(45, 46, 47);
                if (in_array($request->submission_data[0]['process_id'], $porterProcess)) {
                    $new_ar->scanerio_codes = $request->submission_data[0]['error_code'];
                }
            }

            if (isset($request->submission_data[0]['vehicle_type'])) {
                $new_ar->vehicle_type = $request->submission_data[0]['vehicle_type'];
            }
            if (isset($request->submission_data[0]['recording_link'])) {
                $new_ar->recording_link = $request->submission_data[0]['recording_link'];
            }
            if (isset($request->submission_data[0]['call_count'])) {
                $new_ar->call_count = $request->submission_data[0]['call_count'];
            }
            if (isset($request->submission_data[0]['call_types'])) {
                $new_ar->call_types = $request->submission_data[0]['call_types'];
            }
            if (isset($request->submission_data[0]['order_id'])) {
                $new_ar->order_id = $request->submission_data[0]['order_id'];
            }
            if (isset($request->submission_data[0]['caller_type'])) {
                $new_ar->caller_type = $request->submission_data[0]['caller_type'];
            }
            if (isset($request->submission_data[0]['order_stage'])) {
                $new_ar->order_stage = $request->submission_data[0]['order_stage'];
            }
            if (isset($request->submission_data[0]['error_reason_type'])) {
                $new_ar->error_reason_type = implode(",", $request->submission_data[0]['error_reason_type']);
            }
            if (isset($request->submission_data[0]['error_reasons'])) {
                $new_ar->error_code_reasons = implode(",", $request->submission_data[0]['error_reasons']);
            }
            if (isset($request->submission_data[0]['issues'])) {
                $new_ar->issues = $request->submission_data[0]['issues'];
            }
            if (isset($request->submission_data[0]['sub_issues'])) {
                $new_ar->sub_issues = $request->submission_data[0]['sub_issues'];
            }
            if (isset($request->submission_data[0]['scanerio'])) {
                $new_ar->scanerio = $request->submission_data[0]['scanerio'];
            }
            if (isset($request->submission_data[0]['scanerio_codes'])) {
                $new_ar->scanerio_codes = $request->submission_data[0]['scanerio_codes'];
            }
            if (isset($request->submission_data[0]['hot_lead'])) {
                $new_ar->hot_leads = $request->submission_data[0]['hot_lead'];
            }
            if (isset($request->submission_data[0]['hotLead_reason'])) {
                $new_ar->hot_leads_reason = $request->submission_data[0]['hotLead_reason'];
            }

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
            if ($request->submission_data[0]['is_critical'] == 1)
                $new_ar->with_fatal_score_per = 0;
            else
                $new_ar->with_fatal_score_per = $request->submission_data[0]['overall_score'];


            if (
                $request->submission_data[0]['is_critical'] != 1 &&
                $request->submission_data[0]['overall_score'] == 100
            ) {
                $new_ar->feedback_comment = 'Shared';
                $new_ar->feedback_shared_status = 'Feedback Shared.';
                $new_ar->feedback_shared_date = date('Y-m-d H:i:s');
            }


            //feedback
            if (!is_null($request->submission_data[0]['feedback_to_agent'])) {
                $new_ar->feedback_status = 1;
                $new_ar->feedback = $request->submission_data[0]['feedback_to_agent'];
            }
            //feedback

            // function to fetch and save date
            $date = date('Y-m-d H:i:s');
            $delay1 = $request->submission_data[0]['delay1'];
            $delay2 = $request->submission_data[0]['delay2'];
            $holiday = $request->submission_data[0]['holiday'];
            $result = skipHoliday(strtotime($date), $holiday, $delay1, $delay2);
            $new_ar->qc_tat = $result[0];
            $new_date = date('Y-m-d H:i:s', strtotime($date . ' +1 day'));
            $new_ar->audit_view_date = $new_date;
            // $new_ar->audit_view_date = $result[0];
            $new_ar->rebuttal_tat = $result[1];
            // function end

            // $new_ar->auditor_time_spend_in_secs = $request->submission_data[0]['secondTimeData'];
            $new_ar->auditor_time_spend_in_secs = $seconds;
            $new_ar->auditor_sheet_time_secs = $request->submission_data[0]['secondTimeData'];
            Log::info("below audit save:");

            $new_ar->save();
            Log::info("below audit save:");
            if ($new_ar->id) {
                if (!empty($request->dynamic_fields)) {
                    foreach ($request->dynamic_fields as $field_id => $field_value) {
                        $fieldData = new AuditFormValues;

                        $fieldData->audit_id = $new_ar->id;
                        $fieldData->field_id = $field_id;
                        $fieldData->field_value = $field_value;

                        $fieldData->save();
                    }
                }
            }

            // update raw data status

            if ($new_ar->id) {

                // store parameter wise data
                foreach ($request->parameters as $key => $value) {
                    Log::info("auditparameterresult start");

                    $new_arb = new AuditParameterResult;
                    $new_arb->audit_id =  $new_ar->id;
                    $new_arb->parameter_id = $key;
                    $new_arb->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
                    $new_arb->orignal_weight = $value['parameter_weight'];
                    $new_arb->temp_weight = $value['temp_total_weightage'];
                    $new_arb->with_fatal_score = $value['score_with_fatal'];
                    $new_arb->without_fatal_score = $value['score_without_fatal'];
                    if ($value['temp_total_weightage'] != 0) {
                        $new_arb->with_fatal_score_per = ($value['score_with_fatal'] / $value['temp_total_weightage']) * 100;
                        $new_arb->without_fatal_score_pre = ($value['score_without_fatal'] / $value['temp_total_weightage']) * 100;
                    }
                    $new_arb->is_critical = $value['is_fatal'];
                    //  $new_arb->is_partial_marking = $value['is_partial_marking'];
                    $new_arb->save();
                    // store sub parameter wise data
                    foreach ($value['subs'] as $key_sb => $value_sb) {
                        if (isset($value_sb['type']) && !empty($value_sb['type'])) {
                            $image_64 = $value_sb['type']; //your base64 encoded data
                            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
                            $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
                            $image = str_replace($replace, '', $image_64);
                            $image = str_replace(' ', '+', $image);
                            $imageName = rand() . '.' . $extension;
                            Storage::disk('public')->put($imageName, base64_decode($image));
                        }
                        if ($value_sb['selected_option_model'] != '' || $value_sb['selected_option_model'] != null); {
                            $new_arc = new AuditResult;
                            $new_arc->audit_id =  $new_ar->id;
                            $new_arc->parameter_id = $key;
                            $new_arc->sub_parameter_id = $key_sb;
                            $new_arc->is_critical = $value_sb['is_fatal'];
                            // $new_arc->before_is_critical = $value_sb['is_fatal'];
                            $new_arc->is_non_scoring = $value_sb['is_non_scoring'];
                            $temp_selected_opt = explode("_", $value_sb['selected_option_model']);
                            if (count($temp_selected_opt) == 5) {
                                $new_arc->selected_option = $temp_selected_opt[3];
                                // $new_arc->before_selected = $temp_selected_opt[3];
                                if ($temp_selected_opt[3] == 2 || $temp_selected_opt[3] == 3 || $temp_selected_opt[3] == 5 || $temp_selected_opt[3] == 6 || $temp_selected_opt[3] == 1) {


                                    if (isset($value_sb['selected_reason_type']) == 1 && $value_sb['selected_reason_type'] != '' && isset($value_sb['selected_reason']) == 1 && $value_sb['selected_reason'] != '') {

                                        $temp_selected_reason_type = explode("_", $value_sb['selected_reason_type']);
                                        $new_arc->reason_type_id = $temp_selected_reason_type[2];
                                        $new_arc->reason_id = $value_sb['selected_reason'];
                                    }
                                }
                            } else {

                                $new_arc->selected_option = 0;
                                // $new_arc->before_selected = 0;
                                $new_arc->reason_type_id = null;
                                $new_arc->reason_id = null;
                            }
                            $new_arc->score = $value_sb['score'];
                            $new_arc->after_audit_weight = $value_sb['temp_weight'];
                            // $new_arc->before_qc_with_fatal = $value_sb['score'];
                            // $new_arc->before_qc_without_fatal = $value_sb['temp_weight'];


                            if ($new_arc->is_critical == 1) {
                                $new_arc->screenshot =  (isset($imageName)) ? $imageName : '';
                            }
                            //   $new_arc->is_partial_marking = $value_sb['is_partial_marking'];
                            $new_arc->remark = $value_sb['remark'];
                            $new_arc->save();
                        }
                    }
                }
                // Bajaj AHT Calls
                if ($request->AHTTimes != null) {
                    if ($request->submission_data[0]['process_id'] == 88) {
                        foreach ($request->AHTTimes as $value) {
                            $newAht = new BajajAhtCalls();
                            $newAht->audit_id = $new_ar->id;
                            $newAht->qa_id = Crypt::decrypt($request->submission_data[0]['audited_by_id']);
                            $newAht->aht_time = $value['aht'];
                            $newAht->call_id = $value['callId'];
                            $newAht->save();
                        }
                    }
                }

                if ($request->BotTimes != null) {
                    if ($request->submission_data[0]['process_id'] == 215 || $request->submission_data[0]['process_id'] == 220) {
                        foreach ($request->BotTimes as $value) {
                            Log::info("here1");
                            $newAht = new BajajBotCall();
                            $newAht->audit_id = $new_ar->id;
                            Log::info($new_ar->id);
                            Log::info($value);


                            $newAht->confidenceScore = $value['confidenceScore'];
                            $newAht->words = $value['words'];
                            $newAht->bot_status = $value['botStatus'];
                            Log::info("here3");
                            $newAht->save();
                            Log::info("here4");
                        }
                    }
                }
            }
            return response()->json(['status' => 200, 'message' => "Audit saved successfully."], 200);
        } catch (\Exception $th) {

            if (isset($new_ar)) {
                Audit::destroy($new_ar->id);
                RawData::where('id', $request->submission_data[0]['raw_data_id'])->update(['status' => 0]);
                AuditParameterResult::where('audit_id', $new_ar->id)->delete();
                AuditErrorCode::where('audit_id', $new_ar->id)->delete();
            }
            return response()->json(['status' => false, 'message' => $th->getMessage()]);
        }
    }

    public function store_audit_bot(Request $request)
    {

        try {
            Log::info("in ty");
            $check_log = AuditLog::where('auditor_id', Auth::user()->id)->orderBy('created_at', 'DESC')->first();
            $seconds = 0;
            if ($check_log) {
                Log::info("in if:");
                if (gettype($check_log->end_time) == "NULL") {
                    $update_old = AuditLog::find($check_log->id);
                    $datetime1 = new DateTime($update_old->created_at);
                    $datetime2 = new DateTime(now());
                    $interval = $datetime1->diff($datetime2);
                    $minuts = 0;
                    $hours = 0;
                    $total_minutes = 0;

                    foreach ($interval as $key => $value) {
                        if ($key == "i") {
                            $minutes = $value;
                        }
                        if ($key == "h") {
                            $hours = $value;
                        }
                    }
                    $total_minutes = ($hours * 60) + $minutes;
                    if ($total_minutes > 50) {
                        $total_minutes = 50;
                    }
                    $diff = $datetime2->diff($datetime1);
                    $daysInSecs = $diff->format('%r%a') * 24 * 60 * 60;
                    $hoursInSecs = $diff->h * 60 * 60;
                    $minsInSecs = $diff->i * 60;
                    $seconds = $daysInSecs + $hoursInSecs + $minsInSecs + $diff->s;
                    $update_old->end_time = now();
                    $update_old->total_minuts = $total_minutes;
                    $update_old->auditor_time_spend_in_secs = $seconds;
                    $update_old->status = 1;
                    $update_old->save();
                }
            }
            Log::info("endif:");
            $raw_data = new  RawData;
            // $raw_data = RawData::find($request->submission_data[0]['raw_data_id']);
            $raw_data->dump_date = date('Y-m-d');
            $raw_data->company_id = 1;
            $raw_data->client_id = 23;

            if ($request->submission_data[0]['process_id'] == 192 || $request->submission_data[0]['process_id'] == 193 || $request->submission_data[0]['process_id'] == 210 || $request->submission_data[0]['process_id'] == 211 || $request->submission_data[0]['process_id'] == 212 || $request->submission_data[0]['process_id'] == 215 || $request->submission_data[0]['process_id'] == 216 || $request->submission_data[0]['process_id'] == 220) {
                $raw_data->partner_id = 119;
            } else {
                $raw_data->partner_id = $request->submission_data[0]['partner_id'];
            }
            if ($request->submission_data[0]['process_id'] == 192 || $request->submission_data[0]['process_id'] == 193 || $request->submission_data[0]['process_id'] == 210 || $request->submission_data[0]['process_id'] == 211 || $request->submission_data[0]['process_id'] == 212 || $request->submission_data[0]['process_id'] == 215 || $request->submission_data[0]['process_id'] == 216 || $request->submission_data[0]['process_id'] == 220) {
                $raw_data->qtl_id = 3539;
            } else {
                $raw_data->qtl_id = $request->submission_data[0]['tl_name'];;
            }

            $raw_data->process_id = $request->submission_data[0]['process_id'];;
            $raw_data->qa_id = Crypt::decrypt($request->submission_data[0]['audited_by_id']);

            $raw_data->call_id = $request->submission_data[0]['call_id'];
            $raw_data->agent_name = $request->submission_data[0]['agent_name'];
            $raw_data->emp_id = $request->submission_data[0]['emp_id'];
            $raw_data->lob = $request->submission_data[0]['lob'];


            if ($request->submission_data[0]['partner_id'] == 146) {
                $raw_data->partner_location_id = $request->submission_data[0]['partner_location_id']; // id
                $raw_data->location = $request->submission_data[0]['location']; // name
            } else {
                $raw_data->location = $request->submission_data[0]['location'];
                $raw_data->partner_location_id = 36; // Pune
            }
            if (isset($request->submission_data[0]['audit_type'])) {
                $raw_data->audit_type = $request->submission_data[0]['audit_type'];
            }
            if (isset($request->submission_data[0]['focusd_sub_type'])) {
                $raw_data->sub_issues = $request->submission_data[0]['focusd_sub_type'];
            }
            if (isset($request->submission_data[0]['month'])) {
                $raw_data->month = $request->submission_data[0]['month'];
            }



            if (isset($request->submission_data[0]['user_name'])) {
                $raw_data->user_name = $request->submission_data[0]['user_name'];
            }
            if (isset($request->submission_data[0]['case_reason'])) {
                $raw_data->case_reason = $request->submission_data[0]['case_reason'];
            }
            if (isset($request->submission_data[0]['bfl_process'])) {
                $raw_data->bfl_process = $request->submission_data[0]['bfl_process'];
            }
            if (isset($request->submission_data[0]['customer_id'])) {
                $raw_data->customer_id = $request->submission_data[0]['customer_id'];
            }
            if (isset($request->submission_data[0]['vendor_name'])) {
                $raw_data->vendor_name = $request->submission_data[0]['vendor_name'];
            }
            if (isset($request->submission_data[0]['case_create_date'])) {
                $raw_data->case_create_date = $request->submission_data[0]['case_create_date'];
            }
            if (isset($request->submission_data[0]['internal_case_reason_c'])) {
                $raw_data->internal_case_reason_c = $request->submission_data[0]['internal_case_reason_c'];
            }
            if (isset($request->submission_data[0]['solution_based_category_c'])) {
                $raw_data->solution_based_category_c = $request->submission_data[0]['solution_based_category_c'];
            }
            if (isset($request->submission_data[0]['month'])) {
                $raw_data->month = $request->submission_data[0]['month'];
            }

            if (isset($request->submission_data[0]['tl'])) {
                $raw_data->tl = $request->submission_data[0]['tl'];
            } else {
                $raw_data->tl = 'NA';
            }
            if (isset($request->submission_data[0]['sr_mgr'])) {
                $raw_data->sr_mgr = $request->submission_data[0]['sr_mgr'];
            }





            $raw_data->location = $request->submission_data[0]['location'];
            $raw_data->language = $request->submission_data[0]['language'];

            $raw_data->call_time = $request->submission_data[0]['call_time'];
            $raw_data->call_duration = $request->submission_data[0]['call_duration'];
            $raw_data->call_sub_type = $request->submission_data[0]['call_sub_type'];
            $raw_data->disposition = $request->submission_data[0]['disposition'];
            $raw_data->campaign_name = $request->submission_data[0]['campaign_name'];
            $raw_data->phone_number = $request->submission_data[0]['customer_phone'];
            $raw_data->hangup_details = $request->submission_data[0]['hangup_details'];
            $raw_data->customer_name = $request->submission_data[0]['customer_name'];
            $raw_data->call_type = $request->submission_data[0]['call_type'];
            $raw_data->visiblity = 1;


            $raw_data->doj = 'NA';
            $raw_data->circle = 'Pune';
            $raw_data->brand_name = 'BFL';
            $raw_data->info_1 = 'NA';
            $raw_data->info_2 = 'NA';
            $raw_data->info_3 = 'NA';
            $raw_data->info_4 = 'NA';
            $raw_data->info_5 = 'NA';



            $raw_data->status = 1;
            Log::info("above rawdata save:");
            $raw_data->save();
            Log::info("below rawdata save:");

            $new_ar = new Audit;
            $new_ar->company_id = $request->submission_data[0]['company_id'];
            $new_ar->client_id = $request->submission_data[0]['client_id'];
            // $new_ar->partner_id = $request->submission_data[0]['partner_id'];
            if ($request->submission_data[0]['process_id'] == 192 || $request->submission_data[0]['process_id'] == 193 || $request->submission_data[0]['process_id'] == 210 || $request->submission_data[0]['process_id'] == 211 || $request->submission_data[0]['process_id'] == 212 || $request->submission_data[0]['process_id'] == 215 || $request->submission_data[0]['process_id'] == 216 || $request->submission_data[0]['process_id'] == 220) {
                $new_ar->partner_id = 119;
            } else {
                $new_ar->partner_id = $request->submission_data[0]['partner_id'];
            }
            $new_ar->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
            $new_ar->process_id = $request->submission_data[0]['process_id'];
            $new_ar->raw_data_id = $raw_data->id;
            $new_ar->audited_by_id = Crypt::decrypt($request->submission_data[0]['audited_by_id']);
            $new_ar->is_critical = $request->submission_data[0]['is_critical'];
            // $new_ar->is_partial_marking = $request->submission_data[0]['is_partial_marking'];
            $new_ar->overall_score = $request->submission_data[0]['overall_score'];
            //$new_ar->audit_date = $request->submission_data[0]['audit_date'];
            $new_ar->audit_date = date('Y-m-d H:i:s');
            $new_ar->case_id = $request->submission_data[0]['case_id'];
            $new_ar->overall_summary = $request->submission_data[0]['overall_summary'];
            $new_ar->refrence_number = $request->submission_data[0]['refrence_number'];
            $new_ar->gender_identification = $request->submission_data[0]['gender_identifivation'];
            $new_ar->bot_identification_on_gender = $request->submission_data[0]['gender_identifivation_bot'];
            $new_ar->customer_voice = $request->submission_data[0]['customer_voice'];

            if (isset($request->submission_data[0]['confidence_score'])) {
                $new_ar->confidence_score = $request->submission_data[0]['confidence_score'];
            }
            if (isset($request->submission_data[0]['focused_audit_text'])) {
                $new_ar->focused_audit_text = $request->submission_data[0]['focused_audit_text'];
            }
            if (isset($request->submission_data[0]['sfdc_disposition'])) {
                $new_ar->sfdc_disposition = $request->submission_data[0]['sfdc_disposition'];
            }
            if (isset($request->submission_data[0]['sfdc_disposition'])) {
                $new_ar->sfdc_disposition = $request->submission_data[0]['sfdc_disposition'];
            }
            if (isset($request->submission_data[0]['acpt'])) {
                $new_ar->acpt = $request->submission_data[0]['acpt'];
            }
            if (isset($request->submission_data[0]['acpt_l2'])) {
                $new_ar->acpt_l2 = $request->submission_data[0]['acpt_l2'];
            }
            if (isset($request->submission_data[0]['focused_audit_text'])) {
                $new_ar->focused_audit_text = $request->submission_data[0]['focused_audit_text'];
            }
            if (isset($request->submission_data[0]['case_comment'])) {
                $new_ar->case_comment = $request->submission_data[0]['case_comment'];
            }
            if (isset($request->submission_data[0]['call_stage'])) {
                $new_ar->call_stage = $request->submission_data[0]['call_stage'];
            }
            if (isset($request->submission_data[0]['gen_ai'])) {
                $new_ar->gen_ai = $request->submission_data[0]['gen_ai'];
            }
            if (isset($request->submission_data[0]['variable'])) {
                $new_ar->variable = $request->submission_data[0]['variable'];
            }

            if (isset($request->submission_data[0]['customer_stt_1'])) {
                $new_ar->customer_stt_1 = $request->submission_data[0]['customer_stt_1'];
            }
            if (isset($request->submission_data[0]['customer_stt2'])) {
                $new_ar->customer_stt2 = $request->submission_data[0]['customer_stt2'];
            }
            if (isset($request->submission_data[0]['customer_stt_3'])) {
                $new_ar->customer_stt_3 = $request->submission_data[0]['customer_stt_3'];
            }
            if (isset($request->submission_data[0]['customer_stt_4'])) {
                $new_ar->customer_stt_4 = $request->submission_data[0]['customer_stt_4'];
            }
            if (isset($request->submission_data[0]['customer_stt_5'])) {
                $new_ar->customer_stt_5 = $request->submission_data[0]['customer_stt_5'];
            }
            if (isset($request->submission_data[0]['sent_to_bot_or_not'])) {
                $new_ar->sent_to_bot_or_not = $request->submission_data[0]['sent_to_bot_or_not'];
            }
            if (isset($request->submission_data[0]['disconnected_status'])) {
                $new_ar->disconnected_status = $request->submission_data[0]['disconnected_status'];
            }
            if (isset($request->submission_data[0]['call_stage'])) {
                $new_ar->call_stage = $request->submission_data[0]['call_stage'];
            }
            if (isset($request->submission_data[0]['call_disconnected_by'])) {
                $new_ar->call_disconnected_by = $request->submission_data[0]['call_disconnected_by'];
            }
            if (isset($request->submission_data[0]['bot_disposition_as_per_logic'])) {
                $new_ar->bot_disposition_as_per_logic = $request->submission_data[0]['bot_disposition_as_per_logic'];
            }


            if (isset($request->submission_data[0]['error_code'])) {
                $new_ar->error_code = $request->submission_data[0]['error_code'];
                $porterProcess = array(45, 46, 47);
                if (in_array($request->submission_data[0]['process_id'], $porterProcess)) {
                    $new_ar->scanerio_codes = $request->submission_data[0]['error_code'];
                }
            }
            // new porter call type
            //new for porter 
            // if (isset($request->submission_data[0]['new_error_code'])) {
            //     if ($request->submission_data[0]['process_id'] == 45 || $request->submission_data[0]['process_id'] == 47) {
            //         $new_ar->new_error_code = implode(",", $request->submission_data[0]['new_error_code']);
            //         $strWithoutSpaces = $new_ar->new_error_code;
            //     } else {
            //         $str = $request->submission_data[0]['new_error_code'];
            //         $strWithoutSpaces = preg_replace('/\s+/', '', $str);
            //         $new_ar->new_error_code = $strWithoutSpaces;
            //     }
            // }
            if (isset($request->submission_data[0]['vehicle_type'])) {
                $new_ar->vehicle_type = $request->submission_data[0]['vehicle_type'];
            }
            if (isset($request->submission_data[0]['recording_link'])) {
                $new_ar->recording_link = $request->submission_data[0]['recording_link'];
            }
            if (isset($request->submission_data[0]['call_count'])) {
                $new_ar->call_count = $request->submission_data[0]['call_count'];
            }
            if (isset($request->submission_data[0]['call_types'])) {
                $new_ar->call_types = $request->submission_data[0]['call_types'];
            }
            if (isset($request->submission_data[0]['order_id'])) {
                $new_ar->order_id = $request->submission_data[0]['order_id'];
            }
            if (isset($request->submission_data[0]['caller_type'])) {
                $new_ar->caller_type = $request->submission_data[0]['caller_type'];
            }
            if (isset($request->submission_data[0]['order_stage'])) {
                $new_ar->order_stage = $request->submission_data[0]['order_stage'];
            }
            if (isset($request->submission_data[0]['error_reason_type'])) {
                $new_ar->error_reason_type = implode(",", $request->submission_data[0]['error_reason_type']);
            }
            if (isset($request->submission_data[0]['error_reasons'])) {
                $new_ar->error_code_reasons = implode(",", $request->submission_data[0]['error_reasons']);
            }
            if (isset($request->submission_data[0]['issues'])) {
                $new_ar->issues = $request->submission_data[0]['issues'];
            }
            if (isset($request->submission_data[0]['sub_issues'])) {
                $new_ar->sub_issues = $request->submission_data[0]['sub_issues'];
            }
            if (isset($request->submission_data[0]['scanerio'])) {
                $new_ar->scanerio = $request->submission_data[0]['scanerio'];
            }
            if (isset($request->submission_data[0]['scanerio_codes'])) {
                $new_ar->scanerio_codes = $request->submission_data[0]['scanerio_codes'];
            }
            if (isset($request->submission_data[0]['hot_lead'])) {
                $new_ar->hot_leads = $request->submission_data[0]['hot_lead'];
            }
            if (isset($request->submission_data[0]['hotLead_reason'])) {
                $new_ar->hot_leads_reason = $request->submission_data[0]['hotLead_reason'];
            }

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
            if ($request->submission_data[0]['is_critical'] == 1)
                $new_ar->with_fatal_score_per = 0;
            else
                $new_ar->with_fatal_score_per = $request->submission_data[0]['overall_score'];
            //feedback
            if (!is_null($request->submission_data[0]['feedback_to_agent'])) {
                $new_ar->feedback_status = 1;
                $new_ar->feedback = $request->submission_data[0]['feedback_to_agent'];
            }
            //feedback

            // function to fetch and save date
            $date = date('Y-m-d H:i:s');
            $delay1 = $request->submission_data[0]['delay1'];
            $delay2 = $request->submission_data[0]['delay2'];
            $holiday = $request->submission_data[0]['holiday'];
            $result = skipHoliday(strtotime($date), $holiday, $delay1, $delay2);
            $new_ar->qc_tat = $result[0];
            $new_date = date('Y-m-d H:i:s', strtotime($date . ' +1 day'));
            $new_ar->audit_view_date = $new_date;
            // $new_ar->audit_view_date = $result[0];
            $new_ar->rebuttal_tat = $result[1];
            // function end

            // $new_ar->auditor_time_spend_in_secs = $request->submission_data[0]['secondTimeData'];
            $new_ar->auditor_time_spend_in_secs = $seconds;
            $new_ar->auditor_sheet_time_secs = $request->submission_data[0]['secondTimeData'];
            Log::info("below audit save:");

            $new_ar->save();
            Log::info("below audit save:");

            // update raw data status

            if ($new_ar->id) {

                // store parameter wise data
                foreach ($request->parameters as $key => $value) {
                    Log::info("auditparameterresult start");

                    $new_arb = new AuditParameterResult;
                    $new_arb->audit_id =  $new_ar->id;
                    $new_arb->parameter_id = $key;
                    $new_arb->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
                    $new_arb->orignal_weight = $value['parameter_weight'];
                    $new_arb->temp_weight = $value['temp_total_weightage'];
                    $new_arb->with_fatal_score = $value['score_with_fatal'];
                    $new_arb->without_fatal_score = $value['score_without_fatal'];
                    if ($value['temp_total_weightage'] != 0) {
                        $new_arb->with_fatal_score_per = ($value['score_with_fatal'] / $value['temp_total_weightage']) * 100;
                        $new_arb->without_fatal_score_pre = ($value['score_without_fatal'] / $value['temp_total_weightage']) * 100;
                    }
                    $new_arb->is_critical = $value['is_fatal'];
                    //  $new_arb->is_partial_marking = $value['is_partial_marking'];
                    $new_arb->save();
                    // store sub parameter wise data
                    foreach ($value['subs'] as $key_sb => $value_sb) {
                        if (isset($value_sb['type']) && !empty($value_sb['type'])) {
                            $image_64 = $value_sb['type']; //your base64 encoded data
                            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
                            $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
                            $image = str_replace($replace, '', $image_64);
                            $image = str_replace(' ', '+', $image);
                            $imageName = rand() . '.' . $extension;
                            Storage::disk('public')->put($imageName, base64_decode($image));
                        }
                        if ($value_sb['selected_option_model'] != '' || $value_sb['selected_option_model'] != null); {
                            $new_arc = new AuditResult;
                            $new_arc->audit_id =  $new_ar->id;
                            $new_arc->parameter_id = $key;
                            $new_arc->sub_parameter_id = $key_sb;
                            $new_arc->is_critical = $value_sb['is_fatal'];
                            // $new_arc->before_is_critical = $value_sb['is_fatal'];
                            $new_arc->is_non_scoring = $value_sb['is_non_scoring'];
                            $temp_selected_opt = explode("_", $value_sb['selected_option_model']);
                            if (count($temp_selected_opt) == 5) {
                                $new_arc->selected_option = $temp_selected_opt[3];
                                // $new_arc->before_selected = $temp_selected_opt[3];
                                if ($temp_selected_opt[3] == 2 || $temp_selected_opt[3] == 3 || $temp_selected_opt[3] == 5 || $temp_selected_opt[3] == 6 || $temp_selected_opt[3] == 1) {


                                    if (isset($value_sb['selected_reason_type']) == 1 && $value_sb['selected_reason_type'] != '' && isset($value_sb['selected_reason']) == 1 && $value_sb['selected_reason'] != '') {

                                        $temp_selected_reason_type = explode("_", $value_sb['selected_reason_type']);
                                        $new_arc->reason_type_id = $temp_selected_reason_type[2];
                                        $new_arc->reason_id = $value_sb['selected_reason'];
                                    }
                                }
                            } else {

                                $new_arc->selected_option = 0;
                                // $new_arc->before_selected = 0;
                                $new_arc->reason_type_id = null;
                                $new_arc->reason_id = null;
                            }
                            $new_arc->score = $value_sb['score'];
                            $new_arc->after_audit_weight = $value_sb['temp_weight'];
                            // $new_arc->before_qc_with_fatal = $value_sb['score'];
                            // $new_arc->before_qc_without_fatal = $value_sb['temp_weight'];


                            if ($new_arc->is_critical == 1) {
                                $new_arc->screenshot =  (isset($imageName)) ? $imageName : '';
                            }
                            //   $new_arc->is_partial_marking = $value_sb['is_partial_marking'];
                            $new_arc->remark = $value_sb['remark'];
                            $new_arc->save();
                        }
                    }
                }
                // Bajaj AHT Calls
                if ($request->AHTTimes != null) {
                    if ($request->submission_data[0]['process_id'] == 88) {
                        foreach ($request->AHTTimes as $value) {
                            $newAht = new BajajAhtCalls();
                            $newAht->audit_id = $new_ar->id;
                            $newAht->qa_id = Crypt::decrypt($request->submission_data[0]['audited_by_id']);
                            $newAht->aht_time = $value['aht'];
                            $newAht->call_id = $value['callId'];
                            $newAht->save();
                        }
                    }
                }

                if ($request->BotTimes != null) {
                    if ($request->submission_data[0]['process_id'] == 215 || $request->submission_data[0]['process_id'] == 220) {
                        foreach ($request->BotTimes as $value) {
                            Log::info("here1");
                            $newAht = new BajajBotCall();
                            $newAht->audit_id = $new_ar->id;
                            Log::info($new_ar->id);
                            Log::info($value);


                            $newAht->confidenceScore = $value['confidenceScore'];
                            $newAht->words = $value['words'];
                            $newAht->bot_status = $value['botStatus'];
                            Log::info("here3");
                            $newAht->save();
                            Log::info("here4");
                        }
                    }
                }
            }
            return response()->json(['status' => 200, 'message' => "Audit saved successfully."], 200);
        } catch (\Exception $th) {

            if (isset($new_ar)) {
                Audit::destroy($new_ar->id);
                RawData::where('id', $request->submission_data[0]['raw_data_id'])->update(['status' => 0]);
                AuditParameterResult::where('audit_id', $new_ar->id)->delete();
                AuditErrorCode::where('audit_id', $new_ar->id)->delete();
            }
            return response()->json(['status' => false, 'message' => $th->getMessage()]);
        }
    }


    public function audit_count_agent(Request $request, $comm_instance_id)
    {

        // dd($request->process_id);
        $data = [];
        $todays_date = new DateTime();

        $todays_date->setISODate($todays_date->format('o'), $todays_date->format('W'));
        $todays_date->modify('last sunday');
        $start_date_week = $todays_date->format('Y-m-d');
        $todays_date->modify('next saturday');
        $end_date_week = $todays_date->format('Y-m-d');
        $today = new DateTime();

        $agent_name = RawData::where('id', $comm_instance_id)->pluck('agent_name');
        $check_agent_rawdata = RawData::where('agent_name', $agent_name)->wheredate('updated_at', $today)->where('process_id', $request->process_id)->where('status', 1)->get();
        // $check_agent_rawdata = RawData::where('agent_name',$agent_name)->where('status',1)->get();
        $thisweekaudit = RawData::where('agent_name', $agent_name)->wheredate('updated_at', '>=', $start_date_week)->wheredate('updated_at', '<=', $end_date_week)->where('process_id', $request->process_id)->where('status', 1)->count();

        $audit_count =  $check_agent_rawdata->count();
        $data = [
            'audit_count' =>   $audit_count,
            'email' => $agent_name,
            'thisWeekaudits' => $thisweekaudit,
            'client_id' => 23,
        ];

        return response()->json(['status' => 200, 'message' => "Call found.", 'data' => $data], 200);
    }



    //
    public function get_reasons_by_type($type_id)
    {
        $all_reasons = Reason::where('reason_type_id', $type_id)->pluck('name', 'id');
        return response()->json(['status' => 200, 'message' => ".", 'data' => $all_reasons], 200);
    }

    public function get_raw_data_on_data_range(Request $request)
    {
        $temp_data = explode(' To ', $request->date_range);
        if (isset($request->call_id))
            $call_id =  '%' . $request->call_id . '%';
        else
            $call_id = '%';
        $temp_my_alloted_call_list = RawData::where('qa_id', Auth::user()->id)
            ->where('process_id', $request->process_id)
            ->where('dump_date', '>=', $temp_data[0])
            ->where('dump_date', '<=', $temp_data[1])
            ->where('call_id', 'like', $call_id)
            ->where('status', 0)->pluck('call_id', 'id');
        $my_alloted_call_list = [];
        foreach ($temp_my_alloted_call_list as $key => $value) {
            $my_alloted_call_list[] = ['key' => $key, "value" => $value];
        }
        return response()->json(['status' => 200, 'message' => ".", 'data' => $my_alloted_call_list, 'dd' => $temp_data], 200);
    }


    public function change_scores_bajaj()
    {


        // Update SubParameter Weitage in Qm sheet Subparameter table 

        $subparameter_id = 0;
        $subparameter =  QmSheetSubParameter::where('id', $subparameter_id)->get();
        $subparameter->weight = 0; // Can be done manually if less number of subparameter Change only for pass

        // change in audit result table now

        $audit_results = AuditResult::where('sub_parameter_id', $subparameter_id)->get();
        foreach ($audit_results as $key => $value) {
            if ($value->selected_option == 'fail' || $value->selected_option == 'Critical') {
                // there will a  number
                $value->after_audit_weight = 0; // Whatever weighatge of that
                $value->score = 0;  //for fail / critical its 0
            } else {
                $value->after_audit_weight = 0; // Whatever weighatge of that
                $value->score = 0;  // Actual weighatage of that subparameter
            }
        }


        // Now get that Qm Sheet and Process of that client to change audit table without fatal and with fatal score changes

        $audit_data = Audit::where('qm_sheet_id', 0)->where('client_id', 0)->get();
        foreach ($audit_data as $key => $value) {
            if ($value->is_critical == 1) {
            } else {
            }
        }
    }
}
