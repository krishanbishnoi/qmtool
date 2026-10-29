<?php
//
namespace App\Http\Controllers;

ini_set('memory_limit', '-1');

ini_set('max_execution_time', '0');

use App\Audit;
use App\AuditFormFields;

use App\AuditParameterResult;

use App\AuditResult;

use App\Client;
use Illuminate\Support\Facades\File;

use App\Exports\AgentQuartileExport;

use App\Partner;

use App\RawDumpCroneFilter;

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

use App\Mode;

use App\MonthTarget;

use App\Process;
use Illuminate\Support\Facades\Storage;


class PartnerFeedbackController extends Controller

{
    public function agent_feedback_report(Request $request)
    {

        $data1 = [];
        $showDynamicFields = false;
        $requiredFields = []; // ye ab sirf wahi fields honge jo actually exist karte hain us process ke liye
        $fieldMap = [];

        // master list jisse hum match karenge audit_form_fields ke field_name se
        $masterFields = [
            'Emp ID',
            'Emp Name',
            'BPA Name',
            'Agent Name',
            'Auditee Name',
            'Auditee ID',
            'Advisor Name',
            'BPA ID',
            'LOB'
        ];
        $masterFields = array_map('strtolower', $masterFields);

        if (Auth::user()->parent_client) {
            $client_id = Auth::user()->parent_client;
            $all_cluster_processes = get_helper_cluster_processes(Auth::user()->id);
            $all_cluster_partners = get_helper_cluster_partners(Auth::user()->id);
            $all_cluster_locations = get_helper_cluster_locations(Auth::user()->id);
            $client_list = Client::where('id', Auth::user()->parent_client)->get();
        } else {
            $client_list = array();
        }


        if (Auth::user()->hasRole('partner-admin') || Auth::user()->hasRole('partner-qa')) {

            if (Auth::user()->parent_client != 0 && sizeof($all_cluster_partners) > 0) {

                $all_partners = Partner::select('name', 'id')->whereIn('id', $all_cluster_partners)->get();
            } else {

                $all_partners = Partner::select('name', 'id')->where('id', Auth::user()->partner_admin_detail->id)->get();
            }
        } elseif (
            Auth::user()->hasRole('partner-training-head') ||

            Auth::user()->hasRole('partner-operation-head')
        ) {

            if (Auth::user()->parent_client != 0 && sizeof($all_cluster_partners) > 0) {

                $all_partners = Partner::select('name', 'id')->whereIn('id', $all_cluster_partners)->get();
            } else {

                $all_partners = Partner::select('name', 'id')->where('id', Auth::user()->spoc_detail->partner_id)->get();
            }
        } elseif (Auth::user()->hasRole('partner-quality-head')) {

            $partner = PartnersProcessSpoc::where('user_id', Auth::user()->id)->get();

            $partnerid =  $partner[0]['partner_id'];

            if (Auth::user()->parent_client != 0 && sizeof($all_cluster_partners) > 0) {

                $all_partners = Partner::select('name', 'id')->whereIn('id', $all_cluster_partners)->get();
            } else {

                $all_partners = Partner::select('name', 'id')->where('id', $partnerid)->get();
            }
        } elseif (Auth::user()->hasRole('client')) {

            if (Auth::user()->parent_client != 0 && sizeof($all_cluster_partners) > 0) {

                $all_partners = Partner::select('name', 'id')->whereIn('id', $all_cluster_partners)->get();
            } else {

                if (Auth::user()->client_detail) {

                    $client_id = Auth::user()->client_detail->client_id;
                } else {

                    $client_id = Auth::user()->parent_client;
                }

                $all_partners = Partner::select('name', 'id')->where('client_id', $client_id)->get();
            }
        } else {

            if (Auth::user()->parent_client != 0 && sizeof($all_cluster_partners) > 0) {

                $client_id = Auth::user()->parent_client;

                $all_partners = Partner::select('name', 'id')->whereIn('id', $all_cluster_partners)->get();
            } else {

                return redirect('error')->with('warning', 'Please contact to partners role is not set');
            }
        }
         $partner_id  = null;

        if ($request->isMethod('post')) {

            $start_date  = date_to_db($request->start_date);
            $end_date  = date_to_db($request->end_date);
            $location_id = $request->location;
            $process_id = $request->process;
            $lob = $request->lob;
            $partner_id = $request->partner;

            if (Auth::user()->parent_client) {
                if ($partner_id != "all") {
                    $all_cluster_partners = [$partner_id];
                }
                if ($location_id != "%") {
                    $all_cluster_locations = [$location_id];
                }
                if ($process_id != "%") {
                    $all_cluster_processes = [$process_id];
                }
            }

            // ---- Dynamic fields decision ----
            // sirf tab jab: partner = 153 AND ek specific process selected (na ki "all")
            if ($partner_id == 153 && $process_id != 'all' && $process_id != '%' && $process_id != '0') {

                $dynamicHeaders = AuditFormFields::where('process_id', $process_id)
                    ->where('status', 1)
                    ->whereIn(DB::raw('LOWER(field_name)'), $masterFields)
                    ->get();

                if ($dynamicHeaders->count() > 0) {
                    $showDynamicFields = true;
                    foreach ($dynamicHeaders as $field) {
                        $fname = strtolower($field->field_name);
                        $fieldMap[$fname] = $field->id;
                        $requiredFields[] = $fname;
                    }
                }
            }
            // -----------------------------------

            $data = Audit::whereHas('raw_data', function (Builder $query) use ($all_cluster_partners, $all_cluster_locations, $all_cluster_processes) {
                $query->whereIn('partner_location_id', $all_cluster_locations);
            })->whereIn('partner_id', $all_cluster_partners)
                ->whereIn('process_id', $all_cluster_processes)
                ->whereDate('audit_date', ">=", $start_date)
                ->whereDate('audit_date', "<=", $end_date)
                ->where('client_id', $client_id)
                ->with([
                    'raw_data',
                    'raw_data.partner_detail',
                    'raw_data.process',
                    'audit_field_values',
                    'audit_field_values.field',
                    'qa_qtl_detail'
                ])->get();

            foreach ($data as $key => $value) {
                $basic['data'] = $value->raw_data;
                $basic['audit_date'] = $value->audit_date;
                $basic['qc_tat'] = $value->qc_tat;
                $basic['audit_view_date'] = $value->audit_view_date;
                $basic['rebuttal_tat'] = $value->rebuttal_tat;
                $basic['partner'] = $value->raw_data->partner_detail->name;
                $basic['process'] = $value->raw_data->process->name;
                $basic['feedback_shared_status'] = $value->feedback_shared_status;
                $basic['feedback_shared_date'] = $value->feedback_shared_date;
                $basic['with_fatal_score_per'] = $value->with_fatal_score_per;
                $basic['feedback_remarks'] = $value->feedback_comment;

                $dynamicFields = [];
                if ($showDynamicFields && $value->audit_field_values) {
                    foreach ($value->audit_field_values as $fv) {
                        if (isset($fieldMap[strtolower($fv->field->field_name ?? '')])) {
                            $dynamicFields[strtolower($fv->field->field_name)] = $fv->field_value;
                        }
                    }
                }
                $basic['dynamic_fields'] = $dynamicFields;

                $data1[] = $basic;
            }
        }
        return view('reports.agent_feedback_report', compact('all_partners', 'data1', 'requiredFields', 'showDynamicFields','partner_id'));
    }


    public function agent_feedback_report_download()
    {

        return view('reports.agent_feedback_reportdownload');
    }

    // public function raw_dump_download(){
    //     $startDate = date('Y-08-25');
    //     $lastDate = date("Y-09-31");
    //     $process =  RawData::where('client_id',23)->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $lastDate)->distinct()->pluck('process_id')->toArray();
    //     $processInfo = Process::whereIn('id',$process)->pluck('name')->toArray();

    //     return view('reports.raw_dump_download',compact('processInfo'));

    // }


    public function raw_dump_download()
    {
        $fileNames = [];
        $public_path = '';

        if (Auth::user()->parent_client == 23) {

            $directory = public_path('processWiseFile');
            $files = File::allFiles($directory);
            $fileNames = [];
            foreach ($files as $file) {
                $fileNames[] = $file->getFilename();
            }
            $public_path = 'processWiseFile';
        } elseif (Auth::user()->parent_client == 9) {
            $directory = public_path('processWiseFileTATA');
            $files = File::allFiles($directory);
            $fileNames = [];
            foreach ($files as $file) {
                $fileNames[] = $file->getFilename();
            }
            $public_path = 'processWiseFileTATA';
        } elseif (Auth::user()->parent_client == 13) {
            $directory = public_path('processWiseFileHotstar');
            $files = File::allFiles($directory);
            $fileNames = [];
            foreach ($files as $file) {
                $fileNames[] = $file->getFilename();
            }
            $public_path = 'processWiseFileHotstar';
        } else {
            // No processed-file directory is configured for this client.
        }

        return view('reports.raw_dump_download', compact('fileNames', 'public_path'));
    }

    public function agents_multi_audit(Request $request)
    {
        $data1 = [];
        $currentYear = date('Y');
        $currentMonth = date('m');

        $startDate = date('Y-m-01', strtotime("$currentYear-$currentMonth-01"));
        $todayDate = date('Y-m-d');


        if (Auth::user()->parent_client) {
            $client_id = Auth::user()->parent_client;
            $all_cluster_processes = get_helper_cluster_processes(Auth::user()->id);
            $all_cluster_partners = get_helper_cluster_partners(Auth::user()->id);
            $all_cluster_locations = get_helper_cluster_locations(Auth::user()->id);
            $client_list = Client::where('id', Auth::user()->parent_client)->get();
        } else {
            $client_list = array();
        }


        if (Auth::user()->hasRole('partner-admin') ||  Auth::user()->hasRole('partner-qa')) {

            if (Auth::user()->parent_client != 0 && sizeof($all_cluster_partners) > 0) {

                $all_partners = Partner::select('name', 'id')->whereIn('id', $all_cluster_partners)->get();
            } else {

                $all_partners = Partner::select('name', 'id')->where('id', Auth::user()->partner_admin_detail->id)->get();
            }
        } elseif (
            Auth::user()->hasRole('partner-training-head') ||

            Auth::user()->hasRole('partner-operation-head')
        ) {

            if (Auth::user()->parent_client != 0 && sizeof($all_cluster_partners) > 0) {

                $all_partners = Partner::select('name', 'id')->whereIn('id', $all_cluster_partners)->get();
            } else {

                $all_partners = Partner::select('name', 'id')->where('id', Auth::user()->spoc_detail->partner_id)->get();
            }
        } elseif (Auth::user()->hasRole('partner-quality-head')) {



            $partner = PartnersProcessSpoc::where('user_id', Auth::user()->id)->get();



            $partnerid =  $partner[0]['partner_id'];

            if (Auth::user()->parent_client != 0 && sizeof($all_cluster_partners) > 0) {



                $all_partners = Partner::select('name', 'id')->whereIn('id', $all_cluster_partners)->get();
            } else {

                $all_partners = Partner::select('name', 'id')->where('id', $partnerid)->get();
            }
        } elseif (Auth::user()->hasRole('client')) {

            if (Auth::user()->parent_client != 0 && sizeof($all_cluster_partners) > 0) {



                $all_partners = Partner::select('name', 'id')->whereIn('id', $all_cluster_partners)->get();
            } else {

                if (Auth::user()->client_detail) {

                    $client_id = Auth::user()->client_detail->client_id;
                } else {

                    $client_id = Auth::user()->parent_client;
                }

                $all_partners = Partner::select('name', 'id')->where('client_id', $client_id)->get();
            }
        } else {

            if (Auth::user()->parent_client != 0 && sizeof($all_cluster_partners) > 0) {

                $client_id = Auth::user()->parent_client;

                $all_partners = Partner::select('name', 'id')->whereIn('id', $all_cluster_partners)->get();
            } else {

                return redirect('error')->with('warning', 'Please contact to partners role is not set');
            }
        }
        $auditCounts = [];
        if ($request->isMethod('post')) {






            $process_id = $request->process;
            $partner_id = $request->partner;


            if (Auth::user()->parent_client) {
                if ($partner_id != "all") {
                    $all_cluster_partners = [$partner_id];
                }
                if ($process_id != "%") {
                    $all_cluster_processes = [$process_id];
                }
            }
            // dd( $location_id  ,  $process_id  ,  $lob  ,  $partner_id );

            $data = [];

            $agents = RawData::whereIn('partner_id', $all_cluster_partners)
                ->whereIn('process_id', $all_cluster_processes)
                ->whereDate('updated_at', '>=', $startDate)
                ->whereDate('updated_at', '<=', $todayDate)
                ->distinct()
                ->pluck('agent_name');



            foreach ($agents as $agent) {
                $auditCounts[$agent] = [];
                $totalAudits = 0;

                for ($date = strtotime($startDate); $date <= strtotime($todayDate); $date = strtotime('+1 day', $date)) {
                    $day = date('Y-m-d', $date);
                    $count = RawData::where('agent_name', $agent)
                        ->whereDate('updated_at', $day)
                        ->whereIn('partner_id', $all_cluster_partners)
                        ->whereIn('process_id', $all_cluster_processes)
                        ->where('status', 1)
                        ->count();

                    $auditCounts[$agent][$day] = $count;
                    $totalAudits += $count;
                }

                $auditCounts[$agent]['total'] = $totalAudits;
            }
        }
        return view('reports.agents_multi_audit', compact('all_partners', 'auditCounts', 'startDate', 'todayDate'));
    }
}
