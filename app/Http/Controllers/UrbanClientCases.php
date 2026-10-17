<?php

namespace App\Http\Controllers;

use App\Audit;
use App\AuditCase;
use App\AuditParameterResult;
use App\AuditResult;
use App\Partner;
use App\PartnersProcess;
use App\Process;
use App\QmSheet;
use App\QmSheetSubParameter;
use App\RawData;
use App\User;
use App\UrbanCase;
use Illuminate\Http\Request;
use Auth;
use Crypt;
use Illuminate\Database\Eloquent\Builder;

class UrbanClientCases extends Controller
{

    public function audit_cases_list(Request $request)
    {
        $partners = Partner::where('client_id', 23)->pluck('id')->toArray();
        $partner_process = PartnersProcess::whereIn('partner_id', $partners)->pluck('process_id')->toArray();
        $process = Process::whereIn('id', $partner_process)->pluck('name', 'id');

        $data = collect();
        $cases = collect();

        if ($request->isMethod('post')) {
            $auditor_id = Auth::id();
            $process_id = $request->input('process');

            $data = Audit::whereHas('raw_data', function (Builder $query) use ($auditor_id, $process_id) {
                $query->where('qa_id', $auditor_id)
                    ->where('process_id', $process_id);
            })
                ->with(['raw_data', 'partner', 'audit_case'])
                ->orderBy('id', 'DESC')->get();

            $cases = UrbanCase::all();
        }

        return view('urban_client.audit_lists', compact('data', 'cases', 'process'));
    }

    private function generateNextCaseName()
    {
        $lastCase = UrbanCase::orderBy('created_at', 'desc')->first();
        if ($lastCase) {
            $lastCaseName = $lastCase->case_name;
            preg_match('/^A(\d+)$/', $lastCaseName, $matches);
            $nextNumber = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
        } else {
            $nextNumber = 1;
        }
        return 'CASE' . $nextNumber;
    }

    public function save_audit_cases(Request $request)
    {
        $existingCaseId = $request->input('existing_case');
        $newCaseName = $request->input('new_case_name') ?? $this->generateNextCaseName();
        $auditRawDataIds = $request->input('audit_raw_data_ids');
        $process_id = $request->input('process_id');

        $user_id = Auth::id();

        if (empty($auditRawDataIds)) {
            return redirect()->route('audit_cases_list')->with('error', 'No audits selected.');
        }

        $auditIds = [];
        $rawDataIds = [];
        foreach ($auditRawDataIds as $ids) {
            list($auditId, $rawDataId) = explode(',', $ids);
            $auditIds[] = $auditId;
            $rawDataIds[] = $rawDataId;
        }

        if ($existingCaseId) {
            foreach ($auditIds as $auditId) {
                AuditCase::create([
                    'case_id' => $existingCaseId,
                    'audit_id' => $auditId,
                    'raw_data_id' => $rawDataIds[array_search($auditId, $auditIds)],
                    'auditor_id' => $user_id,
                ]);
            }
            $this->updateCaseFatalStatus($existingCaseId);
            return redirect()->route('list-cases')->with('success', 'Audits added to the existing case successfully.');
        }

        $case = UrbanCase::create(['case_name' => $newCaseName, 'process_id' => $process_id, 'qa_id' =>  $user_id]);


        foreach ($auditIds as $auditId) {
            AuditCase::create([
                'case_id' => $case->id,
                'audit_id' => $auditId,
                'raw_data_id' => $rawDataIds[array_search($auditId, $auditIds)],
                'auditor_id' => $user_id,
                'process_id' => $process_id
            ]);
        }
        $this->updateCaseFatalStatus($case->id);

        return redirect()->route('list-cases')->with('success', 'New audit case created successfully.');
    }

    private function updateCaseFatalStatus($caseId)
    {
        $case = UrbanCase::with('audit_cases.cases_audits')->find($caseId);

        $audits = $case->audit_cases->pluck('cases_audits')->flatten();

        $isFatal = $audits->contains(function ($audit) {
            return $audit->with_fatal_score_per == 0;
        });

        $case->fatal_status = $isFatal ? 1 : 0;
        $case->save();
    }

    public function list_cases()
    {
        $cases = UrbanCase::with('audit_cases.cases_audits.raw_data', 'audit_cases.cases_audits.partner')->get();
        // dd(  $cases);
        
        $caseData = [];

        foreach ($cases as $case) {

            $audits = $case->audit_cases->map(function ($auditCase) {
                return $auditCase->cases_audits;
            })->flatten();

            $audits_ids = $audits->pluck('id')->toArray();

            $tempweight = AuditParameterResult::whereIn('audit_id', $audits_ids)->sum('temp_weight');
            $WithoutFatalweight = AuditParameterResult::whereIn('audit_id', $audits_ids)->sum('without_fatal_score');
            $WithFatalweight = AuditParameterResult::whereIn('audit_id', $audits_ids)->sum('with_fatal_score');

            $OverallScoreWithoutFatal = $tempweight > 0 ? round(($WithoutFatalweight / $tempweight) * 100, 3) : 0;

            $audits_withfatal = Audit::whereIn('id',$audits_ids)->sum('overall_score');
            $audits_withoutfatal = Audit::whereIn('id',$audits_ids)->sum('with_fatal_score_per');

            $OverallScoreWithFatal = $audits_withoutfatal ==  $audits_withfatal ? $OverallScoreWithoutFatal  : 0;

            $caseData[] = [
                'case_name' => $case->case_name,
                'case_created_at' => $case->created_at,
                'audits' => $audits,
                'overall_score_with_fatal' => $OverallScoreWithFatal,
                'overall_score_without_fatal' => $OverallScoreWithoutFatal,
                'partners' => $audits->pluck('partner.name')->unique()->filter()->implode(' / '),
                'locations' => $audits->pluck('raw_data.location')->unique()->filter()->implode(' / ')
            ];
        }

       

        return view('urban_client.cases_lists', compact('caseData'));
    }

    public function qtl_dash_cases()
    {



        $final_data = [];

        $total_cases = UrbanCase::count();
        $case_audits = AuditCase::pluck('audit_id');
     
        $case_raw_data_id = AuditCase::pluck('raw_data_id')->toArray();
        $total_audits = $case_audits->count();

        $fatal_cases = UrbanCase::where('fatal_status', 1)->count();
        $Not_fatal = $total_cases - $fatal_cases;

        $total_subP_opp = AuditResult::whereIn('audit_id', $case_audits)
                            ->pluck('sub_parameter_id');
        $total_subP_opp_count = $total_subP_opp->count();

        $failed_subp= AuditResult::whereIn('audit_id', $case_audits)
        ->where('audit_results.selected_option', 2)->count();

        // Fatal Non Fatal Score

        $tempweight = AuditParameterResult::whereIn('audit_id', $case_audits)->sum('temp_weight');
        $WithoutFatalweight = AuditParameterResult::whereIn('audit_id', $case_audits)->sum('without_fatal_score');
        $WithFatalweight = AuditParameterResult::whereIn('audit_id', $case_audits)->sum('with_fatal_score');
     

        $OverallScoreWithoutFatal = $tempweight > 0 ? round(($WithoutFatalweight / $tempweight) * 100, 3) : 0;

        $audits_withfatal = Audit::whereIn('id',$case_audits)->sum('overall_score');
        $audits_withoutfatal = Audit::whereIn('id',$case_audits)->sum('with_fatal_score_per');

        $OverallScoreWithFatal = round(($audits_withoutfatal/$total_audits),2);



       
        
        $failed_sub_count = round((($failed_subp/$total_subP_opp_count)*100 ),2);

       
        $final_data['total_cases'] = $total_cases;
        $final_data['total_audits'] = $total_audits;
        $final_data['fatal_cases'] = $fatal_cases;
        $final_data['Not_fatal'] = $Not_fatal;
        $final_data['total_subP_opp_count'] = $total_subP_opp_count;
        $final_data['failed_subp'] = $failed_subp;
        $final_data['failed_sub_count'] = $failed_sub_count;
        $final_data['OverallScoreWithFatal'] = $OverallScoreWithFatal;
        $final_data['OverallScoreWithoutFatal'] = $OverallScoreWithoutFatal;



        // Get cumulative percentages in a single query
        $all_subs = AuditResult::whereIn('audit_id', $case_audits)
            ->join('qm_sheet_sub_parameters', 'audit_results.sub_parameter_id', '=', 'qm_sheet_sub_parameters.id')
            ->where('audit_results.selected_option', 2)
            ->select('audit_results.sub_parameter_id', \DB::raw('COUNT(*) as audit_count'), 'qm_sheet_sub_parameters.sub_parameter')
            ->groupBy('audit_results.sub_parameter_id', 'qm_sheet_sub_parameters.sub_parameter')
            ->orderBy('audit_count', 'desc')
            ->get();

        $totalAudits = $all_subs->sum('audit_count');
        $cumulativeCount = 0;


        // Prepare data for Highcharts
        $highchartsData = [
            'subparameters' => [],
            'auditcounts' => [],
            'cumulatives' => []
        ];
        foreach ($all_subs as $result) {
            $cumulativeCount += $result->audit_count;
            $cumulativePercentage = round(($cumulativeCount / $totalAudits) * 100);

            $cleanedSubparameter = preg_replace('/[^a-z0-9\s]/i', '', $result->sub_parameter);
            $subparameterName = mb_strlen($cleanedSubparameter) > 17
                ? mb_substr($cleanedSubparameter, 0, 17) . '...'
                : $cleanedSubparameter;

            $highchartsData['subparameters'][] = $subparameterName;
            $highchartsData['auditcounts'][] = $result->audit_count;
            $highchartsData['cumulatives'][] = $cumulativePercentage;
        }


        // Agent wise Pareto

        $agents = RawData::whereIn('id', $case_raw_data_id)->distinct()->pluck('agent_name');

        $agentWiseData = [
            'agents_name' => [],
            'defect_percent' => [],
            'total_audits' => []
        ];
        foreach ($agents as $agent) {
            // Total audits for the agent
            $totalAudits = RawData::whereIn('id', $case_raw_data_id)
                ->where('agent_name', $agent)
                ->count();


            $agentWiseData['agents_name'][] = $agent;
            $agentWiseData['defect_percent'][] = $totalAudits;
            $agentWiseData['total_audits'][] = $totalAudits;
  
           
        }


        return view('urban_client.cases_dashboard', compact('final_data', 'highchartsData' , 'agentWiseData'));
    }

    public function getCases()
    {
        $cases = UrbanCase::all();
        return response()->json($cases);
    }

    public function addCase(Request $request)
    {
        $request->validate([
            'case_name' => 'required|unique:urban_cases,case_name',
        ]);

        $case = new UrbanCase;
        $case->case_name = $request->case_name;
        $case->save();

        return response()->json($case);
    }
}
