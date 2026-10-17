<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AgentFeedbackEmail;
use App\Client;
use Validator;
use App\Process;
use Auth;
use Crypt;
use App\AgentDownTime;
use App\Audit;
use App\Imports\UploadPktScores;
use App\QcDefectSubParameter;
use App\QmSheetSubParameter;
use App\Rebuttal;
use App\SixMonthQaPerformance;
use App\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class AgentFeedbackController extends Controller
{
    public function index()
    {

        return view('agent_feedback_email.set_target');
    }
    public function getList()
    {

        $data = AgentFeedbackEmail::select('agent_feedback_emails.id', 'processes.name as process_name', 'agent_feedback_emails.email', 'agent_feedback_emails.email_type', 'clients.name as client_name')

            ->join('processes', 'agent_feedback_emails.process_id', '=', 'processes.id')
            ->join('clients', 'agent_feedback_emails.client_id', '=', 'clients.id')

            /*  ->join('partners', 'lobs.partner_id', '=', 'partners.id') */


            ->get();

        return view('agent_feedback_email.list', compact('data'));
    }

    public function single_target($target_id)
    {

        //echo Crypt::decrypt($target_id);

        $deleted_data = AgentFeedbackEmail::where('id', $target_id)->delete();
        /* echo Crypt::decrypt($target_id); */
        /*  echo '<pre>';
        print_r($data[0]->client_name);
        dd(); */
        $data = AgentFeedbackEmail::select('agent_feedback_emails.id', 'processes.name as process_name', 'agent_feedback_emails.email', 'agent_feedback_emails.email_type', 'clients.name as client_name')

            ->join('processes', 'agent_feedback_emails.process_id', '=', 'processes.id')
            ->join('clients', 'agent_feedback_emails.client_id', '=', 'clients.id')

            /*  ->join('partners', 'lobs.partner_id', '=', 'partners.id') */


            ->get();
        return view('agent_feedback_email.list', compact('data'));
    }

    public function get_client($company_id)
    {

        $data = Client::select('id', 'name')->where('company_id', $company_id)->get();



        $html = '<option value="">Select Client</option>';
        foreach ($data as $val) {
            $html .= '<option value=' . $val->id . '>' . $val->name . '</option>';
        }
        return $html;
    }

    public function get_process($company_id)
    {
        $data = Process::select('id', 'name')->where('company_id', $company_id)->get();

        $html = '<option value="">Select Process</option>';
        foreach ($data as $val) {
            $html .= '<option value=' . $val->id . '>' . $val->name . '</option>';
        }
        return $html;
    }

    public function get_partners($client_id, $company_id)
    {
        $data = Partner::select('id', 'name')->where('company_id', $company_id)->where('client_id', $client_id)->get();

        $html = '<option value="0">Select Partner</option>';
        foreach ($data as $val) {
            $html .= '<option value=' . $val->id . '>' . $val->name . '</option>';
        }
        $html .= '<option value="all">All</option>';
        return $html;
    }

    public function get_audit_cycle($client_id, $process_id)
    {

        $data = AuditCycle::select('id', 'name')->where('client_id', $client_id)->where('process_id', $process_id)->get();

        $html = '<option value="">Select Audit Cycle</option>';
        foreach ($data as $val) {
            $html .= '<option value=' . $val->id . '>' . $val->name . '</option>';
        }
        return $html;
    }

    public function get_lob($partner_id)
    {

        if ($partner_id == 'all') {
            $data = RawData::select(DB::raw("distinct lob"))->get();
        } else {

            $data = RawData::select(DB::raw("distinct lob"))->where('partner_id', $partner_id)->get();
        }

        $html = '<option value="">Select Lob</option>';
        foreach ($data as $val) {
            $html .= '<option value=' . $val->lob . '>' . $val->lob . '</option>';
        }
        $html .= '<option value="%">All</option>';
        return $html;
    }

    public function get_brand($partner_id)
    {

        $data = RawData::select(DB::raw("distinct brand_name"))->where('partner_id', $partner_id)->get();
        $html = '<option value="">Select Audit brand</option>';
        foreach ($data as $val) {
            $html .= '<option value=' . $val->brand_name . '>' . $val->brand_name . '</option>';
        }
        return $html;
    }

    public function get_circle($partner_id)
    {

        $data = RawData::select(DB::raw("distinct circle"))->where('partner_id', $partner_id)->get();
        $html = '<option value="">Select Circle</option>';
        foreach ($data as $val) {
            $html .= '<option value=' . $val->circle . '>' . $val->circle . '</option>';
        }
        return $html;
    }

    public function save_target(Request $request)
    {
        //echo "fun in";
        /* print_r($request->target);
    dd(); */

        $check_email = AgentFeedbackEmail::where('email', $request->email)->count();

        // if($check_email){

        //     return redirect('agent_feedback_email/set')->with('warning', 'Please enter different name.');
        // }
        // else {

        $target = new AgentFeedbackEmail;
        $target->process_id = $request->process_id;;
        $target->client_id = $request->client_id;
        $target->email_type = $request->email_type;
        $target->email = $request->email;
        $target->save();


        return redirect('agent_feedback_email/set')->with('success', 'Email Set Successfully.');
        //}

    }

    public function updae_target(Request $request)
    {
        //$data = Target::where('audit_cycle_id',$request->audit_cycle_id)->where('circle_name',$request->circle)->count();

        $update_data = Target::find($request->target_id);

        $check_cycle = AuditCycle::whereDate('end_date', '<=', date("Y-m-d"))->where('id', $update_data->audit_cycle_id)->count();


        if ($check_cycle) {
            $update_data->target = $request->target;
            $update_data->save();
            return redirect('target/list')->with('success', 'Target Set Successfully.');
        } else {
            return redirect('target/list')->with('warning', 'You can not update target after ending Audit cycle end date.');
        }
    }

    function agentDownTime()
    {
        return view('agent_feedback_email.agent_downTime');
    }

    function agentDownTimeStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'down_timeTo' => 'required',
            'down_timeFrom' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('agent_downTime')
                ->withErrors($validator)
                ->withInput();
        } else {
            $new_rc = new AgentDownTime;
            $new_rc->auditor_id = Auth::user()->id;
            $new_rc->down_time_date = $request->down_timeDate;
            $new_rc->down_time_to = $request->down_timeTo;
            $new_rc->down_time_from = $request->down_timeFrom;
            $new_rc->reason_type = $request->reason_type;
            $new_rc->reason = $request->reason;
            $new_rc->company_id = $request->company_id;
            $new_rc->save();
        }
        return redirect('agent_downTime')->with('success', 'Down Time Submited successfully');
    }
    function checkAgentDownTime()
    {
        $downTime = AgentDownTime::with('auditor')->orderBy('id', 'desc')->get();
        return view('agent_feedback_email.agent_down_time_list', compact('downTime'));
    }
    function deleteAgentDownTime($id)
    {
        $downTime = AgentDownTime::findOrFail(Crypt::decrypt($id));
        $downTime->delete();
        return redirect('check/down_time')->with('success', 'Down Time Deleted successfully');
    }
    function updateAgentDownTimeStatus($slug, $id)
    {
        $downTime = AgentDownTime::findOrFail(Crypt::decrypt($id));
        $downTime->status = $slug;
        $downTime->save();
        return redirect('check/down_time')->with('success', 'Down Time Updated successfully');
    }

    function searchReasonType(Request $request)
    {
        $data = AgentDownTime::where('reason_type', $request->reason_type)->orderBy('id', 'desc')->get();
        return view('agent_feedback_email.agent_down_time_list', compact('data'));
    }




    // function qa_performance_sixMonth()
    // {
    //     $months = [];
    //     $audit_counts = [];
    //     for ($i = 0; $i < 6; $i++) {
    //         $start_of_month = date("Y-m-01", strtotime(date('Y-m-01') . " -$i months"));
    //         $end_of_month = date("Y-m-t", strtotime($start_of_month));
    //         $months[] = date("M Y", strtotime($start_of_month));
    //         $auth_user_client = Auth::user()->parent_client;
    //         if(Auth::user()->hasRole('process-owner')){
    //             $monthlyData = Audit::whereDate('audit_date', '>=', $start_of_month)
    //             ->whereDate('audit_date', '<=', $end_of_month)              
    //             ->groupBy('audited_by_id')
    //             ->selectRaw('audited_by_id, COUNT(*) as count')
    //             ->get()
    //             ->pluck('count', 'audited_by_id');
    //         }else{
    //             $monthlyData = Audit::whereDate('audit_date', '>=', $start_of_month)
    //             ->whereDate('audit_date', '<=', $end_of_month) 
    //             ->where('client_id',$auth_user_client)              
    //             ->groupBy('audited_by_id')
    //             ->selectRaw('audited_by_id, COUNT(*) as count')
    //             ->get()
    //             ->pluck('count', 'audited_by_id');
    //         }
    //         foreach ($monthlyData as $auditor => $count) {
    //             $auditData[$auditor][$start_of_month] = $count;
    //         }
    //     }
    //     // dd($auditData);
    //     foreach ($auditData as $auditor => &$data) {
    //         foreach ($months as $month) {
    //             $monthStart = date("Y-m-01", strtotime($month));
    //             if (!isset($data[$monthStart])) {
    //                 $data[$monthStart] = 0;
    //             }
    //         }
    //         ksort($data);
    //     }
    //     // dd($auditData);
    //     return view('qa_target.qa_performance_six_month', compact('auditData', 'months'));
    // }


    function qa_performance_sixMonth()
    {
        // $months = [];
        // $audit_counts = [];
        // for ($i = 0; $i < 6; $i++) {
        //     $start_of_month = date("Y-m-01", strtotime(date('Y-m-01') . " -$i months"));
        //     $end_of_month = date("Y-m-t", strtotime($start_of_month));
        //     $months[] = date("M Y", strtotime($start_of_month));
        //     $monthlyData = Audit::whereDate('audit_date', '>=', $start_of_month)
        //         ->whereDate('audit_date', '<=', $end_of_month)
        //         ->groupBy('audited_by_id')
        //         ->selectRaw('audited_by_id, COUNT(*) as count')
        //         ->get()
        //         ->pluck('count', 'audited_by_id');
        //     foreach ($monthlyData as $auditor => $count) {
        //         $auditData[$auditor][$start_of_month] = $count;
        //     }
        // }
        // // dd($auditData);
        // foreach ($auditData as $auditor => &$data) {
        //     foreach ($months as $month) {
        //         $monthStart = date("Y-m-01", strtotime($month));
        //         if (!isset($data[$monthStart])) {
        //             $data[$monthStart] = 0;
        //         }
        //     }
        //     ksort($data);
        // }

        $auditData = SixMonthQaPerformance::all();

        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $months[] = date("M Y", strtotime(date('Y-m-01') . " -$i months"));
        }
        // dd($auditData);
        return view('qa_target.qa_performance_six_month', compact('auditData', 'months'));
    }



    public function qa_performance_search(Request $request)
    {
        $auditor_data = [];


        // $start_date  = date('Y-m-01');
        // $end_date  = date('Y-m-d');

        $start_date  = date('2024-12-01');
        $end_date  = date('2024-12-31');

        if ($request->isMethod('post')) {
            if ($request->file) {
                Excel::import(new UploadPktScores, request()->file('file'));
            }
        }

        $auditors = Audit::whereBetween('audit_date', [$start_date, $end_date])
            ->distinct()
            ->pluck('audited_by_id')
            ->toArray();

        foreach ($auditors as $auditor_id) {
            $user_details = User::where('id', $auditor_id)->first();

            // $auditor_id = 4375;
            $target = 0;
            $date_of_joining = $user_details->created_at;
            $date_of_joining = Carbon::parse($date_of_joining);
            // $start_date = Carbon::now()->startOfMonth();
            // $today = Carbon::now();

            // $days_difference = $today->diffInDays($date_of_joining);
            // $ageBracket = QaAgeBracket::where('age_bracket_start', '<=', $days_difference)->where('age_bracket_end', '>=', $days_difference)->first();
            // $qa_assigned_process = $user_details->assigned_process_ids;
            // $qa_assigned_process_array = json_decode($qa_assigned_process, true);
            // $qa_assigned_process_array = array_map('intval', $qa_assigned_process_array);

            // $target_of_process = ProcessTarget::whereIn('process_id', $qa_assigned_process_array)->where('age_bracket_id', $ageBracket->id)->get();

            // if (count($target_of_process) > 0) {
            //     $SumProcessTargets =  $target_of_process->sum('target');
            //     $current_month_taget = $SumProcessTargets * 23;
            // } else {
            //     $current_month_taget = 0;
            // }
           
            $audits = Audit::where('audited_by_id', $auditor_id)
                ->whereDate('audit_date', '>=', $start_date)
                ->whereDate('audit_date', '<=', $end_date)
                ->get();

               
            // Qc Calculation   
            $total_subparameter_defect_count = 0; 
            $qc_audit_ids = $audits->where('qc_status',1)->pluck('id');
            $qc_audits_qm_sheet_id = $audits->where('qc_status',1)->pluck('qm_sheet_id');
            $total_subparameter_defect_count = QmSheetSubParameter::whereIn('qm_sheet_id',$qc_audits_qm_sheet_id)->count();
            $defects = QcDefectSubParameter::whereIn('audit_id',$qc_audit_ids)->count();  
            $qc_per_denominator = $total_subparameter_defect_count* count($qc_audit_ids);
            $qc_percentage =  $qc_per_denominator > 0 ?    100 - $defects/$qc_per_denominator : 0;
            // rebuttal Caluclation

            $audit_ids = $audits->pluck('id')->toArray();
            $accept_rebuttal_count = Rebuttal::where('status', '1')
            ->whereIn('audit_id', $audit_ids)
            ->where('status',1)
            ->count();

            $rebuttal_per = 100 - $accept_rebuttal_count/$audits->count();

            $auditor_data[$auditor_id] = [
                'audit_counts' => $audits->count(),
                'qc_audit_count' => count($qc_audit_ids) ,
                'qc_audits_per' => round($qc_percentage,2) ,
                'fatal_audits' => $audits->where('is_critical', 1)->count(),
                'rebuttal_counts' => Rebuttal::whereIn('audit_id', $audit_ids)->count(),
                'rebuttal_counts_per' => round($rebuttal_per,2),
                'accept_rebuttal' => $accept_rebuttal_count,
                
            ];
        }


        return view('qa_target.qa_performance_overall', compact('auditor_data'));
    }



    public function upload_process_target(Request $request)
    {
        
        if ($request->isMethod('post')) {

           
            $request->validate([
                'file' => 'required|mimes:xlsx,xls'
            ]);

          
            if ($request->hasFile('file') && $request->file('file')->isValid()) {

           
                Excel::import(new UploadProcesstargets, $request->file('file'));                
              
            }
           
        }

        return view('qa_target.upload_Process_target');
    }
}
