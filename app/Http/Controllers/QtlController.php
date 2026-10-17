<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Audit;
use App\Rebuttal;
use App\ClientsQtl;
use App\AuditResult;
use DB;
use Illuminate\Database\Eloquent\Builder;
use App\AuditParameterResult;
use App\Partner;
use App\QmSheet;
use App\QmSheetSubParameter;
use App\PartnersProcess;
use App\RawData;
use App\Region;
use Auth;

class QtlController extends Controller
{

    public function index()
    {

        return view('dashboards.qtl_dashboard_new');
    }
    public function my_team_score($start_date, $end_date)
    {

        $response = array();
        $qtl = Auth::user()->id;
        $qa_list = get_qtl_qa_list($qtl);

        $audit_count = Audit::whereDate('audit_date', ">=", $start_date)
            ->whereDate('audit_date', "<=", $end_date)
            ->whereIn('audited_by_id', $qa_list)
            ->count('id');

        $rebuttal_count = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
            ->whereDate('audits.audit_date', ">=", $start_date)
            ->whereDate('audits.audit_date', "<=", $end_date)
            ->whereIn('audits.audited_by_id', $qa_list)
            ->count('rebuttals.id');

        $rebuttal_accepted = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
            ->whereDate('audits.audit_date', ">=", $start_date)
            ->whereDate('audits.audit_date', "<=", $end_date)
            ->where('rebuttals.status', 1)
            ->whereIn('audits.audited_by_id', $qa_list)
            ->count('rebuttals.id');

        $rebuttal_rejected = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
            ->whereDate('audits.audit_date', ">=", $start_date)
            ->whereDate('audits.audit_date', "<=", $end_date)
            ->where('rebuttals.status', 2)
            ->whereIn('audits.audited_by_id', $qa_list)
            ->count('rebuttals.id');

        $fatal_count = AuditResult::join('audits', 'audit_results.audit_id', '=', 'audits.id')
            ->whereDate('audits.audit_date', ">=", $start_date)
            ->whereDate('audits.audit_date', "<=", $end_date)
            ->where('audit_results.selected_option', 3)
            ->whereIn('audits.audited_by_id', $qa_list)
            ->count('audit_results.id');

        $fatal_score_sum = AuditParameterResult::join('audits', 'audit_parameter_results.audit_id', '=', 'audits.id')
            ->whereDate('audits.audit_date', ">=", $start_date)
            ->whereDate('audits.audit_date', "<=", $end_date)
            ->where('audits.is_critical', 0)
            ->whereIn('audits.audited_by_id', $qa_list)
            ->sum('audit_parameter_results.with_fatal_score');

        $temp_wait_sum = AuditParameterResult::join('audits', 'audit_parameter_results.audit_id', '=', 'audits.id')
            ->whereDate('audits.audit_date', ">=", $start_date)
            ->whereDate('audits.audit_date', "<=", $end_date)
            ->whereIn('audits.audited_by_id', $qa_list)
            ->sum('audit_parameter_results.temp_weight');

        $overall_score = Audit::whereDate('audit_date', ">=", $start_date)
            ->whereDate('audit_date', "<=", $end_date)
            ->whereIn('audited_by_id', $qa_list)
            ->sum('overall_score');

        if ($audit_count) {

            $with_fatal_score = round(($fatal_score_sum / $temp_wait_sum) * 100);
            $without_fatal_score = round(($overall_score / $audit_count));
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

        return response()->json(['status' => 200, 'message' => "Success", 'data' => $response], 200);
    }
    public function overall_score($start_date, $end_date)
    {
        $response = array();
        $client_id = ClientsQtl::where('qtl_user_id', Auth::user()->id)->pluck('client_id');
        /* echo $client_id;
        die; */
        $audit_count = Audit::whereDate('audit_date', ">=", $start_date)
            ->whereDate('audit_date', "<=", $end_date)
            ->whereIn('client_id', $client_id)
            ->count('id');

        $rebuttal_count = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
            ->whereDate('audits.audit_date', ">=", $start_date)
            ->whereDate('audits.audit_date', "<=", $end_date)
            ->whereIn('audits.client_id', $client_id)
            ->count('rebuttals.id');

        $rebuttal_accepted = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
            ->whereDate('audits.audit_date', ">=", $start_date)
            ->whereDate('audits.audit_date', "<=", $end_date)
            ->where('rebuttals.status', 1)
            ->whereIn('audits.client_id', $client_id)
            ->count('rebuttals.id');

        $rebuttal_rejected = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
            ->whereDate('audits.audit_date', ">=", $start_date)
            ->whereDate('audits.audit_date', "<=", $end_date)
            ->where('rebuttals.status', 2)
            ->whereIn('audits.client_id', $client_id)
            ->count('rebuttals.id');

        $fatal_count = AuditResult::join('audits', 'audit_results.audit_id', '=', 'audits.id')
            ->whereDate('audits.audit_date', ">=", $start_date)
            ->whereDate('audits.audit_date', "<=", $end_date)
            ->where('audit_results.selected_option', 3)
            ->whereIn('audits.client_id', $client_id)
            ->count('audit_results.id');

        $fatal_score_sum = AuditParameterResult::join('audits', 'audit_parameter_results.audit_id', '=', 'audits.id')
            ->whereDate('audits.audit_date', ">=", $start_date)
            ->whereDate('audits.audit_date', "<=", $end_date)
            ->where('audits.is_critical', 0)
            ->whereIn('audits.client_id', $client_id)
            ->sum('audit_parameter_results.with_fatal_score');

        $temp_wait_sum = AuditParameterResult::join('audits', 'audit_parameter_results.audit_id', '=', 'audits.id')
            ->whereDate('audits.audit_date', ">=", $start_date)
            ->whereDate('audits.audit_date', "<=", $end_date)
            ->whereIn('audits.client_id', $client_id)
            ->sum('audit_parameter_results.temp_weight');

        $overall_score = Audit::whereDate('audit_date', ">=", $start_date)
            ->whereDate('audit_date', "<=", $end_date)
            ->whereIn('client_id', $client_id)
            ->sum('overall_score');

        if ($audit_count) {
            $with_fatal_score = round(($fatal_score_sum / $temp_wait_sum) * 100);
            $without_fatal_score = round(($overall_score / $audit_count));
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

        return response()->json(['status' => 200, 'message' => "Success", 'data' => $response], 200);
    }
    public function qtl_dashboard_qa_performance_piller_chart_data($start_date, $end_date)
    {
        $response = array();
        $qtl = Auth::user()->id;
        $qa_list = get_qtl_qa_list($qtl);

        $all_auditor_list = Audit::distinct('audited_by_id')->whereIn('audited_by_id', $qa_list)
            ->whereDate('audit_date', ">=", $start_date)
            ->whereDate('audit_date', "<=", $end_date)->pluck('audited_by_id');

        $auditor_name_list = User::whereIn('id', $all_auditor_list)->pluck('name');
        $audit_done = [];
        $rebuttal_raised = [];
        $rebuttal_accepted = [];
        $fatal = [];
        $sale_audits = [];
        $Non_sale_audits = [];
        foreach ($all_auditor_list as $value) {

            $audit_count = Audit::where('audited_by_id', $value)->whereDate('audit_date', ">=", $start_date)
                ->whereDate('audit_date', "<=", $end_date)->count('id');

            $fatal_count = Audit::where('audited_by_id', $value)->where('is_critical', 1)
                ->whereDate('audit_date', ">=", $start_date)
                ->whereDate('audit_date', "<=", $end_date)->count('id');

            $rebuttal_count = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
                ->where('audits.audited_by_id', $value)->whereDate('audits.audit_date', ">=", $start_date)
                ->whereDate('audits.audit_date', "<=", $end_date)->count('rebuttals.id');

            $sale_audit_count = RawData::where('qa_id', $value)->whereDate('updated_at', ">=", $start_date)
                ->whereDate('updated_at', "<=", $end_date)->where('info_4', 'like', 'Sale')->where('status',1)->count();  
            $non_sale_audit_count = RawData::where('qa_id', $value)->whereDate('updated_at', ">=", $start_date)
                ->whereDate('updated_at', "<=", $end_date)->where('info_4', 'not like', 'Sale')->where('status',1)->count(); 

            array_push($audit_done, $audit_count);
            array_push($fatal, $fatal_count);
            array_push($rebuttal_raised, $rebuttal_count);
            array_push($sale_audits, $sale_audit_count);
            array_push($Non_sale_audits, $non_sale_audit_count); 
        }
        $response['auditor_name_list'] = $auditor_name_list;
        $response['audit_done'] = $audit_done;
        $response['fatal'] = $fatal;
        $response['rebuttal_raised'] = $rebuttal_raised;
        $response['sale_audits'] = $sale_audits;
        $response['non_sale_audits'] = $Non_sale_audits;

        return response()->json(['status' => 200, 'message' => "Success", 'data' => $response], 200);
    }


    public function qtl_dashboard_qc_deviation_piller_chart_data($start_date, $end_date)
    {

        $response = array();
        $qtl = Auth::user()->id;
        $name = [];
        $count = [];
        $qc_count_data = DB::select("select u.name as users, count(audited_by_id) as number  
        from audits a inner join users u on a.audited_by_id= u.id where a.qc_status = 1  and 
        a.audit_date >= '" . $start_date . "' and a.audit_date <= '" . $end_date . "'  and 
        u.reporting_user_id = '" . $qtl . "' group by audited_by_id ");

        foreach ($qc_count_data as $value) {
            array_push($name, $value->users);
            array_push($count, $value->number);
        }



        $response['users'] = $name;
        $response['number'] = $count;



        return response()->json(['status' => 200, 'message' => "Success", 'data' =>  $response], 200);
    }
    public function qtl_dashboard_process_wise_performance_data($start_date, $end_date)
    {
        $response = array();
        $qtl = Auth::user()->id;
        $qa_list = get_qtl_qa_list($qtl);



        $process_list = PartnersProcess::join('partners', 'partners_processes.partner_id', '=', 'partners.id')
            ->join('clients', 'clients.id', '=', 'partners.client_id')
            ->join('clients_qtls', 'partners.client_id', '=', 'clients_qtls.client_id')
            ->join('processes', 'partners_processes.process_id', '=', 'processes.id')
            ->where('clients_qtls.qtl_user_id', $qtl)->distinct()->pluck('processes.name');

        $process_ids = PartnersProcess::join('partners', 'partners_processes.partner_id', '=', 'partners.id')
            ->join('clients', 'clients.id', '=', 'partners.client_id')
            ->join('clients_qtls', 'partners.client_id', '=', 'clients_qtls.client_id')

            ->where('clients_qtls.qtl_user_id', $qtl)->distinct()->pluck('partners_processes.process_id');


        $audit_done = [];
        $rebuttal_raised = [];
        $rebuttal_accepted = [];
        $fatal = [];

        foreach ($process_ids as $value) {

            $audit_count = Audit::whereIn('audited_by_id', $qa_list)->where('process_id', $value)->whereDate('audit_date', ">=", $start_date)
                ->whereDate('audit_date', "<=", $end_date)->count('id');

            $fatal_count = Audit::whereIn('audited_by_id', $qa_list)->where('is_critical', 1)
                ->where('process_id', $value)
                ->whereDate('audit_date', ">=", $start_date)
                ->whereDate('audit_date', "<=", $end_date)->count('id');

            $rebuttal_count = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
                ->whereIn('audits.audited_by_id', $qa_list)->whereDate('audits.audit_date', ">=", $start_date)
                ->whereDate('audits.audit_date', "<=", $end_date)
                ->where('audits.process_id', $value)
                ->count('rebuttals.id');

            array_push($audit_done, $audit_count);
            array_push($fatal, $fatal_count);
            array_push($rebuttal_raised, $rebuttal_count);
        }


        $response['audit_done'] = $audit_done;
        $response['fatal'] = $fatal;
        $response['rebuttal_raised'] = $rebuttal_raised;
        $response['process_list'] = $process_list;
        $response['process_list_id'] = $process_ids;
        return response()->json(['status' => 200, 'message' => "Success", 'data' => $response], 200);
    }

    public function qtl_dashboard_pareto_rebuttal_data($start_date, $end_date, $process_id)
    {
        $qtl = Auth::user()->id;
        $qa_list = get_qtl_qa_list($qtl);
        $qm_sheet_details = QmSheet::where('process_id', $process_id)->orderby('version', 'desc')->first();
        $sub_parameters = QmSheetSubParameter::where('qm_sheet_id', $qm_sheet_details->id)->pluck('sub_parameter', 'id');

        $data = [];
        foreach ($sub_parameters as $key => $value) {

            $rebuttal_count = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
                ->whereIn('audits.audited_by_id', $qa_list)->whereDate('audits.audit_date', ">=", $start_date)
                ->whereDate('audits.audit_date', "<=", $end_date)
                ->where('rebuttals.sub_parameter_id', $key)
                ->count('rebuttals.id');

            $d = array();
            $d['country'] = $value;
            $d['visits'] = $rebuttal_count;
            $data[] = $d;
        }
        uasort($data, $this->make_comparer(array('visits', SORT_DESC)));
        $response = [];
        foreach ($data as $value) {
            $d = array();
            $d['country'] = $value['country'];
            $d['visits'] = $value['visits'];
            $response[] = $d;
        }
        return response()->json(['status' => 200, 'message' => "Success", 'data' => $response], 200);
    }
    function make_comparer()
    {
        $criteriaNames = func_get_args();
        $comparer = function ($first, $second) use ($criteriaNames) {
            // Do we have anything to compare?
            while (!empty($criteriaNames)) {
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
                } else if ($first[$criterion] > $second[$criterion]) {
                    return 1 * $sortOrder;
                }
            }

            // Nothing more to compare with, so $first == $second
            return 0;
        };

        return $comparer;
    }


    public function edit_lob_get()
    {

        return view('audit_alert_box.edit_lob');
    }

    public function edit_lob(Request $request)
    {

        $data = RawData::where('call_id', $request->call_id)
            ->whereHas('audit', function ($query) {
                $query->where('deleted', '=', 0);
            })->with(['partner', 'audit'])->get();

        return view('audit_alert_box.change_lob', compact('data'));
    }

    public function edit_to_loc($raw_data_id)
    {


        // $raw_detail = RawData::find($raw_data_id)->first();      
        $raw_detail = RawData::where('id', $raw_data_id)->first();

        $partners = Partner::where('client_id',23)->pluck('id','name')->toArray();

        // dd( $raw_detail );

        return view('audit_alert_box.edit_lob_loc', compact('raw_detail', 'raw_data_id' ,'partners'));
    }

    public function update_lob_par(Request $request)
    {

      
        $raw = RawData::where('id',$request->raw_id)->first();     
        $audit = Audit::where('raw_data_id',$request->raw_id)->first();

        $updated_location = $request->location;
        $updated_lob = $request->lob;
        $updated_partner = $request->partner_id;
        // dd($updated_partner);

        // Check if the location exists in the Region table
        $exist_loc = Region::where('name', $updated_location)->first();
        if ($exist_loc || !empty($raw->partner_id)) {
    
         
            $raw->location = $updated_location;
            $raw->partner_location_id =  $exist_loc->id;
           
            $raw->lob = $updated_lob;
                    
            if (!empty($updated_partner)) {
                $raw->partner_id = $updated_partner;
            }
        
           
            $raw->save();
                
            if (!empty($updated_partner)) {
                $audit->partner_id = $updated_partner;
                $audit->save();
            }
        
            return redirect('edit_lob_get')->with('success', 'Updated successfully.');
        }else {
            return redirect('edit_lob_get')->with('error', 'Location does not exist.');
        }
    }
}
