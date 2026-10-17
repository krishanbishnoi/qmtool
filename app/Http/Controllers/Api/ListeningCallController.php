<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use Crypt;
use App\RawData;
use App\Region;
use Carbon\Carbon;
use DB;
use App\Process;
use App\ListeningCallFeedback;
use App\Audit;
use App\Client;
use App\Partner;
use App\Rebuttal;
use App\AuditParameterResult;
use App\Rca2Mode;
use App\RcaMode;
use App\RcaType;
use App\Reason;
use App\ReasonType;
use App\AuditAlertBox;
use App\TypeBScoringOption;
use App\QmSheet;
use App\Auditcycle;
use App\AuditResult;
use App\AgentCallFeedbackLog;
use App\ParameterWisePlanOfAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use App\Rca1;
use App\Rca2;
use App\Rca3;
use App\AuditLog;
use App\TmpAudit;
use App\TmpAuditParameterResult;
use App\TmpAuditResult;



class ListeningCallController extends Controller
{
    public function index()
    {
        echo "hiiii";

    }

    public function recording_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required',

        ]);

        /* echo $request->email;
              dd(); */
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $client_id = $request->client_id;
            $audit_detail = DB::select("
            select a.good_bad_call_file as call_link,a.audit_date,a.good_bad_call,r.call_id,r.lob,r.location,r.call_type,r.call_sub_type,r.disposition,r.campaign_name,r.phone_number,r.customer_name,r.circle,r.brand_name
            from audits a inner join raw_data r on a.raw_data_id = r.id where a.good_bad_call_file is not null and  a.client_id = " . $client_id . "");

            $data = array('status' => 1, 'message' => 'Data fetch successfully', 'data' => $audit_detail);
            return response(json_encode($data), 200);
        }

    }

    public function listenning_call_filters()
    {
        $response = array();
        $response['location'] = RawData::select('location')->distinct()->get(); //filter for location
        $response['process'] = Process::select('name', 'id')->get();
        $response['lob'] = RawData::select('lob')->distinct()->get();
        $response['call_type'] = RawData::select('call_type')->distinct()->get();
        $response['call_sub_type'] = RawData::select('call_sub_type')->distinct()->get();
        $response['disposition'] = RawData::select('disposition')->distinct()->get();
        $response['campaign_name'] = RawData::select('campaign_name')->distinct()->get();
        $response['circle'] = RawData::select('circle')->distinct()->get();
        $response['brand_name'] = RawData::select('brand_name')->distinct()->get();
        return response()->json(['status' => 200, 'message' => "Success", 'data' => $response], 200);
    }

    public function searching(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'location' => 'required',
            // 'lob' => 'required',
            // 'process_id' => 'required',
            // 'call_type' => 'required',
            // 'call_sub_type' => 'required',
            // 'campaign_name' => 'required',
            // 'disposition' => 'required',
            // 'circle' => 'required',
            // 'brand_name' => 'required',
            // 'good_bad_call' => 'required',



        ]);

        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {

            $location = $request->location;
            //echo $location; die;
            $lob = $request->lob;
            //echo $lob; die;
            $process_id = $request->process_id;
            //echo $process_id; die;
            $call_type = $request->call_type;
            //echo $call_type; die;
            $call_sub_type = $request->call_sub_type;
            //echo $call_sub_type; die;
            $disposition = $request->disposition;
            $campaign_name = $request->campaign_name;
            $circle = $request->circle;

            $brand_name = $request->brand_name;
            //echo $brand_name; die;
            $audit_detail = DB::select("
            select a.good_bad_call_file as call_link,a.audit_date,a.good_bad_call,r.call_id,r.lob,r.location,r.call_type,r.call_sub_type,r.disposition,r.campaign_name,r.phone_number,r.customer_name,r.circle,r.brand_name
            from audits a inner join raw_data r on a.raw_data_id = r.id where a.good_bad_call_file is not null and a.good_bad_call = '" . $request->good_bad_call . "' and
             r.process_id like '%" . $process_id . "%' 
            and r.lob like '%" . $request->lob . "%' and r.call_type like '%" . $request->call_type . "%' and 
             r.call_sub_type like '%" . $request->call_sub_type . "%' and r.campaign_name like '%" . $request->campaign_name . "%'
             and r.circle like '%" . $request->circle . "%' and r.brand_name like '%" . $request->brand_name . "%' ");
            //print_r($audit_detail); die;

        }
        return response()->json(['status' => 200, 'message' => "Success", 'data' => $audit_detail], 200);
    }

    public function auditors_audit(Request $request)
    {
        if (!$request->start_date) {
            return response()->json([
                'status' => 0,
                'message' => 'Validation Errors',
                'data' => 'Please provide Audit Date',
            ], 200);
        }
        
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
        $daysCount = $start_date->diffInDays($end_date) + 1;
        
        $allResults = [];
        
        for ($i = 0; $i < $daysCount; $i++) {
            $currentDate = $start_date->copy()->addDays($i)->format('Y-m-d');
            $results = DB::table('audits as u')
                ->leftJoin('users as o', 'u.audited_by_id', '=', 'o.id')
                ->select(DB::raw("'" . $currentDate . "' as date"),'u.audited_by_id','o.qd_id','o.email',
                    DB::raw('COUNT(u.audited_by_id) as total')
                )
                ->whereDate('u.audit_date', $currentDate)
                ->groupBy('u.audited_by_id', 'o.qd_id', 'o.email')
                ->get();
        
            foreach ($results as $row) {
                $allResults[] = (array) $row;
            }
        }
        
        return response()->json(['status' => 1,'message' => 'Success','data' => $allResults,]);
        
    }
    public function diactivate_users(Request $request)
    {
        if (!$request->email && !$request->qd_id) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please provide Valid Email and QD ID');
            return response(json_encode($data), 200);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if ($request->status == "active") {
                $results = User::where('email', $request->email)->update(['status' => 1]);
                return response()->json(['status' => 200, 'message' => "Success", 'data' => 'User Activate Successfully'], 200);
            } else {
                $results = User::where('email', $request->email)->update(['status' => 99]);
                return response()->json(['status' => 200, 'message' => "Success", 'data' => 'User Deactivate Successfully'], 200);
            }
        } else {
            return response()->json(['status' => 200, 'message' => "Success", 'data' => 'Please provide Valid Email and QD ID'], 200);
        }
    }
    public function listning_call_feedback(Request $request)
    {

        /*  $validator = Validator::make($request->all(), [
              'audit_id' => 'audit_id',
              'user_id' => 'user_id',
              'remark' => 'remark',
         ]);
         
         if($validator->fails()) {
             $data=array('status'=>0,'message'=>'Validation Errors','data' => $validator->errors());
             return response(json_encode($data), 200);
         } else { */
        $old_feedback = ListeningCallFeedback::where('user_id', $request->user_id)->where('audit_id', $request->audit_id)->first();
        $audit_detail = Audit::find($request->audit_id);

        $auditor_email = User::where('id', $audit_detail->audited_by_id)->first();

        $client_email = Client::join('client_admins', 'client_admins.client_id', '=', 'clients.id')
            ->join('users', 'client_admins.user_id', '=', 'users.id')->where('clients.id', $audit_detail->client_id)->pluck('users.email');
        $tl_email = User::where('id', $auditor_email->reporting_user_id)->pluck('email');
        $partner_eamil = Partner::where('id', $audit_detail->partner_id)->pluck('contact_email');
        // print_r($client_email);
        // die;
        if (!$old_feedback) {
            $new_feedback = new ListeningCallFeedback();
            $new_feedback->audit_id = $request->audit_id;
            $new_feedback->user_id = $request->user_id;
            $new_feedback->remark = $request->remark;

            if ($new_feedback->save()) {
                return response()->json(['status' => 1, 'message' => "Success", 'data' => 'Feedback inserted'], 200);
            } else {
                return response()->json(['status' => 0, 'message' => "fail", 'data' => 'DB error'], 200);
            }

        } else {
            return response()->json(['status' => 0, 'message' => "Fail", 'data' => 'Particular user allready provided feedback'], 200);
        }
        /* } */

    }
    public function notification_feedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {


            $email = $request->email;
            // $notification_logs = Audit::join('agent_call_feedback_logs','agent_call_feedback_logs.audit_id','=','audits.id')
            // ->join('raw_data','audits.raw_data_id','=','raw_data.id')->where('raw_data.agent_name',$email)
            // ->pluck('agent_call_feedback_logs.agent_feedback');
            // dd($request);
            $notification_logs = DB::select("
            select al.audit_id, al.agent_feedback_date, al.agent_feedback,a.good_bad_call_file, a.audit_date, pr.name as process_name,r.call_id,r.location,r.phone_number,r.customer_name,r.circle,u.name as auditor_name,u.email as auditor_email,
            a.is_critical, a.overall_score as without_fatal, a.with_fatal_score_per as with_fatal from agent_call_feedback_logs al inner join audits a on al.audit_id = a.id inner join raw_data r on a.raw_data_id = r.id 
            inner join processes pr on a.process_id = pr.id inner join users u on a.audited_by_id = u. id where al.mail_trigger_status is not null and r.agent_name = '" . $email . "'
            ");

        }

        return response()->json(['status' => 200, 'message' => "Success", 'data' => $notification_logs], 200);



    }

    public function parameter_wise_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'audit_id' => 'required',
        ]);

        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {

            $audit_id = $request->audit_id;
            // $notification_logs = Audit::join('agent_call_feedback_logs','agent_call_feedback_logs.audit_id','=','audits.id')
            // ->join('raw_data','audits.raw_data_id','=','raw_data.id')->where('raw_data.agent_name',$email)
            // ->pluck('agent_call_feedback_logs.agent_feedback');


            $overall_sheet_parameters = AuditResult::select('audit_results.audit_id', 'qm_sheet_parameters.parameter', 'qm_sheet_parameters.id as paramter_id', 'qm_sheet_sub_parameters.id as subparameter_id', 'qm_sheet_sub_parameters.sub_parameter', 'qm_sheet_sub_parameters.weight', 'qm_sheet_parameters.is_non_scoring', 'audit_results.selected_option', 'audit_results.score', 'audit_parameter_results.with_fatal_score_per', 'reason_types.name as reason_type_name', 'reasons.name as reason_name', 'audit_results.remark as remarks', 'audit_results.is_critical')
                ->join('audits', 'audit_results.audit_id', '=', 'audits.id')
                ->join('qm_sheet_parameters', 'qm_sheet_parameters.id', '=', 'audit_results.parameter_id')
                ->join('qm_sheet_sub_parameters', 'audit_results.sub_parameter_id', '=', 'qm_sheet_sub_parameters.id')
                ->join('audit_parameter_results', 'audit_results.parameter_id', '=', 'audit_parameter_results.parameter_id')
                ->leftJoin('reason_types', 'audit_results.reason_type_id', '=', 'reason_types.id')
                ->leftJoin('reasons', 'audit_results.reason_id', '=', 'reasons.id')
                ->where('audit_results.audit_id', $audit_id)
                ->where('audit_parameter_results.audit_id', $audit_id)
                ->get();
        }

        return response()->json(['status' => 200, 'message' => "Success", 'data' => $overall_sheet_parameters], 200);



    }

    public function accepted(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'agent_feedback_status' => 'required',
            'feedback_log_id' => 'required',

        ]);

        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {

            $agent_feedback_status = $request->agent_feedback_status;
            $feedback_log_id = $request->feedback_log_id;



            $audit_feedback = AgentCallFeedbackLog::find($feedback_log_id);
            $audit_feedback->agent_feedback_status = $agent_feedback_status;

            $audit_feedback->save();


        }

        return response()->json(['status' => 200, 'message' => "Success", 'data' => $audit_feedback], 200);



    }

    public function plan_of_action(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'agent_feedback_status' => 'required',
            'feedback_log_id' => 'required',
            'feedback_remark' => 'required',
            'audit_id' => 'required',
        ]);

        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {

            $agent_feedback_status = $request->agent_feedback_status;
            $feedback_log_id = $request->feedback_log_id;
            $feedback_remark = $request->feedback_remark;
            if (isset($request->agent_feedback_recording)) {
                $request->agent_feedback_recording->store("agent_feedback_recording");

                $file_name = $request->agent_feedback_recording->hashName();
                $data = Storage::url('agent_feedback_recording/') . $file_name;
                $audit_feedback->agent_feedback_recording = $data;
            }

            $audit_feedback = AgentCallFeedbackLog::find($feedback_log_id);
            $audit_feedback->agent_feedback_status = $agent_feedback_status;
            $audit_feedback->agent_feedback = $feedback_remark;


            $audit_feedback->save();


        }
        //  return response()->json(['status'=>200,'message'=>"File uploaded successfuly",
        // 'data'=>$data,'data_recording'=>$data], 200);

        return response()->json(['status' => 200, 'message' => "Success", 'data' => $audit_feedback], 200);



    }

    public function Rebuttal(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'audit_id' => 'required',
            'sub_parameter_id' => 'required',
            'rebuttal_remark' => 'required',

        ]);

        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $old_rebuttal = Rebuttal::where('sub_parameter_id', $request->sub_parameter_id)->where('audit_id', $request->audit_id)->first();

            $audit_detail = DB::select("
                select p.admin_user_id,a.raw_data_id, a.rebuttal_status
                from audits a inner join raw_data r on a.raw_data_id = r.id inner join partners p on p.id = a.partner_id where a.id = " . $request->audit_id . "");


            if (!$old_rebuttal) {

                if ($audit_detail[0]->rebuttal_status == 0) {
                    $rebuttal_status = Audit::find($request->audit_id);
                    $rebuttal_status->rebuttal_status = 1;
                    $rebuttal_status->save();
                }


                $rebuttal = new Rebuttal();
                $rebuttal->audit_id = $request->audit_id;
                $rebuttal->sub_parameter_id = $request->sub_parameter_id;
                $rebuttal->remark = $request->remark;
                $rebuttal->raised_by_user_id = $audit_detail[0]->admin_user_id;
                $rebuttal->raw_data_id = $audit_detail[0]->raw_data_id;
                $rebuttal->parameter_id = $request->parameter_id;
                if ($request->artifact) {
                    $request->artifact->store("artifact");
                    $file_name = $request->artifact->hashName();
                    $data = Storage::url('artifact/') . $file_name;
                }
                if ($rebuttal->save()) {
                    return response()->json(['status' => 1, 'message' => "pass", 'data' => 'Rebuttal raised sucessfully'], 200);
                }
            } else {
                return response()->json(['status' => 0, 'message' => "Fail", 'data' => 'Rebuttal already inserted'], 200);
            }

        }

    }
    public function parameter_wise_plan_of_action(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'audit_id' => 'required',
            'parameter_id' => 'required',
            'sub_parameter_id' => 'required',
            'remark' => 'required',
        ]);
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $remark = ParameterWisePlanOfAction::where('audit_id', $request->audit_id)->where('parameter_id', $request->parameter_id)->where('sub_parameter_id', $request->sub_parameter_id)->first();
            if (!$remark) {
                $new_remark = new ParameterWisePlanOfAction();
                $new_remark->audit_id = $request->audit_id;
                $new_remark->parameter_id = $request->parameter_id;
                $new_remark->sub_parameter_id = $request->sub_parameter_id;
                $new_remark->remark = $request->remark;
                if (isset($request->recording_file)) {
                    $request->recording_file->store("agent_feedback_recording");

                    $file_name = $request->recording_file->hashName();
                    $data = Storage::url('agent_feedback_recording/') . $file_name;
                    $new_remark->recording_file = $data;
                }


                if ($new_remark->save()) {
                    return response()->json(['status' => 1, 'message' => "Success", 'data' => 'Feedback inserted'], 200);
                } else {
                    return response()->json(['status' => 0, 'message' => "fail", 'data' => 'DB error'], 200);
                }
            } else {
                return response()->json(['status' => 0, 'message' => "Fail", 'data' => 'Particular user allready provided feedback'], 200);
            }
        }
    }
    public function agent_dashboard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'agent_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',

        ]);
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $data = array();


            $audit_data = Audit::
                whereDate('audit_date', '>=', $request->start_date)
                ->whereDate('audit_date', '<=', $request->end_date)
                //->where('raw_data.agent_name',$request->agent_name)
                ->whereHas('raw_data', function (Builder $query) use ($request) {

                    $query->where('agent_name', $request->agent_id);

                })
                ->get();


            $fatal_dialer_data['with_fatal_score'] = 0;
            $fatal_dialer_data['without_fatal_score'] = 0;

            $fatal_audit_count = $audit_data->where('is_critical', 1)->count();
            $without_fatal_audit_count = $audit_data->where('is_critical', 0)->count();
            $fatal_audit_score_sum = 0;
            $without_fatal_audit_score_sum = 0;
            $scr = 0;
            $sca = 0;
            foreach ($audit_data as $key => $value) {
                foreach ($value->audit_parameter_result->where('is_non_scoring', '!=', 1) as $keyb => $valueb) {
                    if ($value->is_critical == 0) {

                        $scr += $valueb->with_fatal_score;

                    }
                    $sca += $valueb->temp_weight;
                }
                $without_fatal_audit_score_sum += $value->overall_score;
            }

            if ($audit_data->count()) {

                $fatal_dialer_data['with_fatal_score'] = round(($scr / $sca) * 100);
                $fatal_dialer_data['without_fatal_score'] = round(($without_fatal_audit_score_sum / $audit_data->count()));

            } else {
                $fatal_dialer_data['with_fatal_score'] = 0;
                $fatal_dialer_data['with_fatal_score'] = 0;
            }
            $data['fatal_dialer_data'] = $fatal_dialer_data;

            $data['rebuttal_count'] = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
                ->whereDate('audits.audit_date', ">=", $request->start_date)
                ->whereDate('audits.audit_date', "<=", $request->end_date)
                ->whereHas('raw_data', function (Builder $query) use ($request) {

                    $query->where('agent_name', $request->agent_id);

                })
                ->count('rebuttals.id');


            $data['fatal_count'] = AuditResult::join('audits', 'audit_results.audit_id', '=', 'audits.id')

                ->whereDate('audits.audit_date', ">=", $request->start_date)
                ->whereDate('audits.audit_date', "<=", $request->end_date)
                ->where('audit_results.selected_option', 3)
                ->whereHas('audit.raw_data', function (Builder $query) use ($request) {

                    $query->where('agent_name', $request->agent_id);

                })
                ->count('audit_results.id');


            $data['audit_count'] = Audit::whereDate('audit_date', ">=", $request->start_date)
                ->whereDate('audit_date', "<=", $request->end_date)
                ->whereHas('raw_data', function (Builder $query) use ($request) {

                    $query->where('agent_name', $request->agent_id);

                })

                ->count('id');

            $data['rebuttal_accepted'] = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
                ->whereDate('audits.audit_date', ">=", $request->start_date)
                ->whereDate('audits.audit_date', "<=", $request->end_date)
                ->where('rebuttals.status', 1)

                ->whereHas('raw_data', function (Builder $query) use ($request) {

                    $query->where('agent_name', $request->agent_id);

                })
                ->count('rebuttals.id');

            $data['rebuttal_rejected'] = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
                ->whereDate('audits.audit_date', ">=", $request->start_date)
                ->whereDate('audits.audit_date', "<=", $request->end_date)
                ->where('rebuttals.status', 2)

                ->whereHas('raw_data', function (Builder $query) use ($request) {

                    $query->where('agent_name', $request->agent_id);

                })
                ->count('rebuttals.id');


            return response()->json(['status' => 0, 'message' => "success", 'data' => $data], 200);
        }


    }


    public function auditor_dashboard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'auditor_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',

        ]);
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $data = array();

            $audit_data = Audit::with('raw_data')
                ->whereDate('audit_date', '>=', $request->start_date)
                ->whereDate('audit_date', '<=', $request->end_date)
                ->where('audited_by_id', $request->auditor_id)
                ->get();

            $fatal_dialer_data['with_fatal_score'] = 0;
            $fatal_dialer_data['without_fatal_score'] = 0;

            $fatal_audit_count = $audit_data->where('is_critical', 1)->count();
            $without_fatal_audit_count = $audit_data->where('is_critical', 0)->count();
            $fatal_audit_score_sum = 0;
            $without_fatal_audit_score_sum = 0;
            $scr = 0;
            $sca = 0;
            foreach ($audit_data as $key => $value) {
                foreach ($value->audit_parameter_result->where('is_non_scoring', '!=', 1) as $keyb => $valueb) {
                    if ($value->is_critical == 0) {

                        $scr += $valueb->with_fatal_score;

                    }
                    $sca += $valueb->temp_weight;
                }
                $without_fatal_audit_score_sum += $value->overall_score;
            }

            if ($audit_data->count()) {

                $fatal_dialer_data['with_fatal_score'] = round(($scr / $sca) * 100);
                $fatal_dialer_data['without_fatal_score'] = round(($without_fatal_audit_score_sum / $audit_data->count()));

            } else {
                $fatal_dialer_data['with_fatal_score'] = 0;
                $fatal_dialer_data['with_fatal_score'] = 0;
            }
            $data['fatal_dialer_data'] = $fatal_dialer_data;

            $data['rebuttal_count'] = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
                ->whereDate('audits.audit_date', ">=", $request->start_date)
                ->whereDate('audits.audit_date', "<=", $request->end_date)
                ->where('audited_by_id', $request->auditor_id)
                ->count('rebuttals.id');


            $data['fatal_count'] = AuditResult::join('audits', 'audit_results.audit_id', '=', 'audits.id')
                ->whereDate('audits.audit_date', ">=", $request->start_date)
                ->whereDate('audits.audit_date', "<=", $request->end_date)
                ->where('audit_results.selected_option', 3)
                ->where('audits.audited_by_id', $request->auditor_id)
                ->count('audit_results.id');

            $data['audit_count'] = Audit::whereDate('audit_date', ">=", $request->start_date)
                ->whereDate('audit_date', "<=", $request->end_date)
                ->where('audited_by_id', $request->auditor_id)
                ->count('id');

            $data['rebuttal_accepted'] = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
                ->whereDate('audits.audit_date', ">=", $request->start_date)
                ->whereDate('audits.audit_date', "<=", $request->end_date)
                ->where('rebuttals.status', 1)
                ->where('audits.audited_by_id', $request->auditor_id)
                ->count('rebuttals.id');

            $data['rebuttal_rejected'] = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
                ->whereDate('audits.audit_date', ">=", $request->start_date)
                ->whereDate('audits.audit_date', "<=", $request->end_date)
                ->where('rebuttals.status', 2)
                ->where('audits.audited_by_id', $request->auditor_id)
                ->count('rebuttals.id');


            return response()->json(['status' => 0, 'message' => "success", 'data' => $data], 200);
        }


    }

    public function auditor_process_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',


        ]);
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $sheet_data = [];
            $assigned_process_id = User::where('id', $request->user_id)->pluck('assigned_process_ids');
            $proList = json_decode($assigned_process_id[0]);
            //echo "<pre>"; print_r($proList); die;
            $getSheetId = array();
            foreach ($proList as $key => $value) {
                $sh = QmSheet::select('id')->where('process_id', $value)->orderBy('version', 'desc')->first();
                $getSheetId[] = $sh->id;
            }
            $sheet_data = QmSheet::whereIn('process_id', json_decode($assigned_process_id[0]))->with('process')->whereIn('id', $getSheetId)->get();
            return response()->json(['status' => 0, 'message' => "success", 'data' => $sheet_data], 200);
        }
    }

    public function audit_page_agent_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qmsheet_id' => 'required',

        ]);
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $agents = Partner::get();
            return response()->json(['status' => 0, 'message' => "success", 'data' => $agents], 200);
        }
    }

    public function audit_page_render_sheet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qmsheet_id' => 'required',
            'user_id' => 'required',

        ]);
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $data = QmSheet::with(['client', 'process', 'parameter', 'parameter.qm_sheet_sub_parameter'])->find($request->qmsheet_id);


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


            $getUniqueCallType = RawData::select(DB::raw("distinct(call_type)"))->where('client_id', $data->client_id)->where('process_id', $data->process->id)->limit(3)->pluck('call_type');
            $final_data['client_id'] = $data->client_id;
            $final_data['callTypeList'] = $getUniqueCallType;

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
            // get audit cycle
            $temp_my_alloted_call_list = RawData::where('qa_id', $request->user_id)
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
                $pds[$value_p->id]['name'] = $value_p->parameter;
                $pds[$value_p->id]['is_non_scoring'] = $value_p->is_non_scoring;
                $total_parameter_weight = 0;
                $pds[$value_p->id]['is_fatal'] = 0;
                $pds[$value_p->id]['score'] = 0;
                $pds[$value_p->id]['score_with_fatal'] = 0;
                $pds[$value_p->id]['score_without_fatal'] = 0;
                $pds[$value_p->id]['temp_total_weightage'] = 0;
                foreach ($value_p->qm_sheet_sub_parameter as $key => $value_s) {

                    $pds[$value_p->id]['subs'][$value_s->id]['name'] = $value_s->sub_parameter;
                    $pds[$value_p->id]['subs'][$value_s->id]['details'] = $value_s->details;
                    $pds[$value_p->id]['subs'][$value_s->id]['is_fatal'] = 0;
                    $pds[$value_p->id]['subs'][$value_s->id]['is_non_scoring'] = $value_p->is_non_scoring;
                    $pds[$value_p->id]['subs'][$value_s->id]['failure_reason'] = '';
                    $pds[$value_p->id]['subs'][$value_s->id]['remark'] = '';
                    $pds[$value_p->id]['subs'][$value_s->id]['orignal_weight'] = $value_s->weight;
                    $pds[$value_p->id]['subs'][$value_s->id]['temp_weight'] = 0;
                    $scoring_opts = [];
                    if ($value_p->is_non_scoring) {
                        //total weight
                        $total_parameter_weight += 0;
                        if ($value_s->non_scoring_option_group) {
                            foreach (all_non_scoring_obs_options($value_s->non_scoring_option_group) as $key_ns => $value_ns) {
                                $scoring_opts[$value_p->id . "_" . $value_s->id . "_" . $value_ns . "_" . $key_ns . "_0"] = ["key" => $value_p->id . "_" . $value_s->id . "_" . $value_ns . "_" . $key_ns . "_0", "value" => $value_ns, "alert_box" => null];
                            }
                        } else {
                            $scoring_opts = null;
                        }


                    } else {
                        //total weight
                        $total_parameter_weight += $value_s->weight;
                        //total weight
                        $alert_box = null;

                        $all_reason_type_fail = null;
                        $all_reason_type_cric = null;
                        $all_reason_type_pwd = null;

                        if ($value_s->pass) {
                            if ($value_s->pass_alert_box_id)
                                $alert_box = AuditAlertBox::find($value_s->pass_alert_box_id);
                            else
                                $alert_box = null;


                            $scoring_opts[$value_p->id . "_" . $value_s->id . "_" . $value_s->weight . "_1_0"] = ["key" => $value_p->id . "_" . $value_s->id . "_" . $value_s->weight . '_1_0', "value" => "Pass", "alert_box" => $alert_box];
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

                    }


                    $pds[$value_p->id]['subs'][$value_s->id]['options'] = $scoring_opts;
                    $pds[$value_p->id]['subs'][$value_s->id]['score'] = 0;
                    $pds[$value_p->id]['subs'][$value_s->id]['selected_options'] = null;
                    $pds[$value_p->id]['subs'][$value_s->id]['selected_option_model'] = '';
                    $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_fail'] = $all_reason_type_fail;
                    $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_cric'] = $all_reason_type_cric;
                    $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_pwd'] = $all_reason_type_pwd;
                    $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type'] = null;
                    $pds[$value_p->id]['subs'][$value_s->id]['selected_reason_type'] = '';
                    $pds[$value_p->id]['subs'][$value_s->id]['all_reasons'] = null;
                    $pds[$value_p->id]['subs'][$value_s->id]['selected_reason'] = '';

                }
                $pds[$value_p->id]['parameter_weight'] = $total_parameter_weight;

            }

            $final_data['simple_data'] = $pds;

            // rca starts
            $rca_type = RcaType::where('company_id', $data->company_id)->where('process_id', $data->process_id)->pluck('name', 'id');
            $rca_mode = RcaMode::where('company_id', $data->company_id)->where('process_id', $data->process_id)->pluck('name', 'id');

            $final_data['rca_type'] = $rca_type;
            $final_data['rca_mode'] = $rca_mode;
            // rca end

            // rca type 2starts
            $type_2_rca_mode = Rca2Mode::where('company_id', $data->company_id)->where('process_id', $data->process_id)->pluck('name', 'id');
            $final_data['type_2_rca_mode'] = $type_2_rca_mode;
            // rca type 2 ends
        }

        return response()->json(['status' => 0, 'message' => "success", 'data' => $final_data], 200);
    }

    public function get_rca1_by_rca_mode_id(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rca_mode' => 'required',


        ]);
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $data = Rca1::where('mode_id', $request->rca_mode)->pluck('name', 'id');
            return response()->json(['status' => 200, 'data' => $data], 200);
        }
    }
    public function get_rca2_by_rca1_id(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rca1' => 'required',


        ]);
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $data = Rca2::where('rca1_id', $request->rca1)->pluck('name', 'id');
            return response()->json(['status' => 200, 'data' => $data], 200);
        }
    }
    public function get_rca3_by_rca2_id(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rca2' => 'required',


        ]);
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $data = Rca3::where('rca2_id', $request->rca2)->pluck('name', 'id');
            return response()->json(['status' => 200, 'data' => $data], 200);
        }
    }

    public function store_audit(Request $request)
    {
        $check_log = AuditLog::where('auditor_id', $request->submission_data[0]['auditor_id'])->orderBy('created_at', 'DESC')->first();

        if ($check_log) {
            if (gettype($check_log->end_time) == "NULL") {
                // echo $check_log->id;
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

                $update_old->end_time = now();
                $update_old->total_minuts = $total_minutes;
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
            $raw_data->status = 1;
            $raw_data->save();

            if ($new_ar->id) {
                // store parameter wise data
                foreach ($request->parameters as $key => $value) {

                    $new_arb = new AuditParameterResult;
                    $new_arb->audit_id = $new_ar->id;
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

                    $new_arb->save();

                    // store sub parameter wise data
                    foreach ($value['subs'] as $key_sb => $value_sb) {

                        if (isset($value_sb['type']) && !empty($value_sb['type'])) {


                            $image_64 = $value_sb['type']; //your base64 encoded data

                            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1]; // .jpg .png .pdf


                            $replace = substr($image_64, 0, strpos($image_64, ',') + 1);


                            $image = str_replace($replace, '', $image_64);
                            $image = str_replace(' ', '+', $image);
                            $imageName = rand() . '.' . $extension;

                            Storage::disk('public')->put($imageName, base64_decode($image));
                        }

                        if ($value_sb['selected_option_model'] != '' || $value_sb['selected_option_model'] != null)
                            ; {
                            $new_arc = new AuditResult;
                            $new_arc->audit_id = $new_ar->id;
                            $new_arc->parameter_id = $key;
                            $new_arc->sub_parameter_id = $key_sb;
                            $new_arc->is_critical = $value_sb['is_fatal'];
                            $new_arc->is_non_scoring = $value_sb['is_non_scoring'];
                            $temp_selected_opt = explode("_", $value_sb['selected_option_model']);

                            if (count($temp_selected_opt) == 5) {
                                $new_arc->selected_option = $temp_selected_opt[3];

                                if ($temp_selected_opt[3] == 2 || $temp_selected_opt[3] == 3 || $temp_selected_opt[3] == 5) {
                                    if (isset($value_sb['selected_reason_type']) == 1 && $value_sb['selected_reason_type'] != '' && isset($value_sb['selected_reason']) == 1 && $value_sb['selected_reason'] != '') {
                                        $temp_selected_reason_type = explode("_", $value_sb['selected_reason_type']);
                                        $new_arc->reason_type_id = $temp_selected_reason_type[2];
                                        $new_arc->reason_id = $value_sb['selected_reason'];
                                    }
                                }

                            } else {
                                $new_arc->selected_option = 0;
                                $new_arc->reason_type_id = null;
                                $new_arc->reason_id = null;
                            }


                            $new_arc->score = $value_sb['score'];
                            $new_arc->after_audit_weight = $value_sb['temp_weight'];
                            if ($new_arc->is_critical == 1) {
                                $new_arc->screenshot = (isset($imageName)) ? $imageName : '';
                            }


                            $new_arc->remark = $value_sb['remark'];
                            $new_arc->save();
                        }
                    }

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
                $raw_data->status = 1;
                $raw_data->save();

                if ($new_ar->id) {
                    // store parameter wise data
                    foreach ($request->parameters as $key => $value) {

                        $new_arb = new TmpAuditParameterResult;
                        $new_arb->audit_id = $new_ar->id;
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

                        $new_arb->save();

                        // store sub parameter wise data
                        foreach ($value['subs'] as $key_sb => $value_sb) {
                            // added by nisha
                            if (isset($value_sb['type']) && !empty($value_sb['type'])) {


                                $image_64 = $value_sb['type']; //your base64 encoded data

                                $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1]; // .jpg .png .pdf


                                $replace = substr($image_64, 0, strpos($image_64, ',') + 1);


                                $image = str_replace($replace, '', $image_64);
                                $image = str_replace(' ', '+', $image);
                                $imageName = rand() . '.' . $extension;

                                Storage::disk('public')->put($imageName, base64_decode($image));
                            }
                            // end by nisha

                            if ($value_sb['selected_option_model'] != '' || $value_sb['selected_option_model'] != null)
                                ; {
                                $new_arc = new TmpAuditResult;
                                $new_arc->audit_id = $new_ar->id;
                                $new_arc->parameter_id = $key;
                                $new_arc->sub_parameter_id = $key_sb;
                                $new_arc->is_critical = $value_sb['is_fatal'];
                                $new_arc->is_non_scoring = $value_sb['is_non_scoring'];
                                $temp_selected_opt = explode("_", $value_sb['selected_option_model']);

                                if (count($temp_selected_opt) == 5) {
                                    $new_arc->selected_option = $temp_selected_opt[3];

                                    if ($temp_selected_opt[3] == 2 || $temp_selected_opt[3] == 3) {
                                        if (isset($value_sb['selected_reason_type']) == 1 && $value_sb['selected_reason_type'] != '' && isset($value_sb['selected_reason']) == 1 && $value_sb['selected_reason'] != '') {
                                            $temp_selected_reason_type = explode("_", $value_sb['selected_reason_type']);
                                            $new_arc->reason_type_id = $temp_selected_reason_type[2];
                                            $new_arc->reason_id = $value_sb['selected_reason'];
                                        }
                                    }

                                } else {
                                    $new_arc->selected_option = 0;
                                    $new_arc->reason_type_id = null;
                                    $new_arc->reason_id = null;
                                }


                                $new_arc->score = $value_sb['score'];
                                $new_arc->after_audit_weight = $value_sb['temp_weight'];

                                if ($new_arc->is_critical == 1) {
                                    $new_arc->screenshot = (isset($imageName)) ? $imageName : '';
                                }


                                $new_arc->remark = $value_sb['remark'];
                                $new_arc->save();
                            }
                        }

                    }


                }
            }
            return response()->json(['status' => 200, 'message' => "Tmp Audit saved successfully."], 200);

        }
    }

    public function getTotalData()
    {
        $data = [];
        $data['client'] = Client::select('id', 'name')->get();
        return response()->json(['status' => 200, 'message' => "Total Audit Data.", 'data' => $data], 200);

    }


}