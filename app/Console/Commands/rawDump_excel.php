<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AuditParameterResult;
use App\ParameterAuditLog;
use App\RawData;
use App\Audit;
use App\User;
use App\Rebuttal;
use App\Partner;
use App\PartnersProcess;
use App\Process;
use App\QmSheet;
use App\ErrorCode;
use App\IncompleteAudit;
use App\QmSheetParameter;
use App\QmSheetSubParameter;
use App\AuditFormFields;
use App\Mail\Agentfeedback;
use App\Mail\raw_dump;
use Illuminate\Support\Facades\Mail;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class rawDump_excel extends Command
{
    protected $signature = 'raw_dump_data:report';

    protected $description = 'This command for show client dashboard data';

    /**
     * Process ids that use the "224-230" manual layout (Group A)
     */
    private $groupA = [224, 225, 226, 227, 228, 229, 230];

    /**
     * Process ids that use the DYNAMIC HEADER layout (Group B)
     * -> only Sr.No / Audit Date / Auditor Name / Call Id / dynamic fields / Overall Summary /
     *    Agent feedback / Good-Bad Call are shown, no other manual columns.
     */
    private $groupB = [243, 244, 245, 246, 247, 248, 249, 250, 251, 252, 253, 254, 255, 256, 257, 258, 259, 264, 267, 269, 271, 274, 275, 276, 277, 278, 279, 280, 281,285];

    /**
     * Process ids that use the "complaint/RCC" layout (Group C)
     */
    private $groupC = [262, 272, 282, 284];

    /**
     * Process ids that use the "QC call review" layout (Group D)
     */
    private $groupD = [261, 263];

    /**
     * Process ids that use the "loan a/c" layout (Group E)
     */
    private $groupE = [283];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $partnerData = Partner::where("client_id", 23)->pluck('id')->toArray();
            $processData = PartnersProcess::whereIn('partner_id', $partnerData)->pluck('process_id')->toArray();

            $startDate = date('Y-m-01');
            $lastDate  = date("Y-m-d");

            $process = [
                216, 120, 155, 188, 182, 191, 199, 200, 204, 208, 205, 203, 215,
                224, 225, 226, 227, 228, 229, 230, 232, 233, 234, 235, 236, 237, 238, 239, 240,
                241, 242, 243, 244, 245, 246, 247, 248, 249, 250, 251, 252, 253, 254, 255, 256,
                257, 258, 259, 260, 261, 262, 263, 264, 265, 266, 267, 268, 269, 270, 271, 272,
                273, 274, 275, 276, 277, 278, 279, 280, 281, 282, 283, 284,285,286,287
            ];
            Log::info($process);

            $newGroupIds = array_merge($this->groupA, $this->groupB, $this->groupC, $this->groupD, $this->groupE);

            for ($i = 0; $i < count($process); $i++) {
                $processInfo = Process::find($process[$i]);

                $qmSheetIds = Audit::whereDate('audit_date', '>=', $startDate)
                    ->whereDate('audit_date', '<=', $lastDate)
                    ->where('process_id', $process[$i])
                    ->distinct()
                    ->pluck('qm_sheet_id')
                    ->toArray();
                Log::info($qmSheetIds);

                foreach ($qmSheetIds as $qmSheetId) {
                    $qmSheet = QmSheet::find($qmSheetId);
                    if (!$qmSheet) {
                        continue;
                    }

                    $temp_audit_data = Audit::where('qm_sheet_id', $qmSheet->id)
                        ->where('process_id', $process[$i])
                        ->whereDate('audit_date', '>=', $startDate)
                        ->whereDate('audit_date', '<=', $lastDate)
                        ->where('deleted', 0)
                        ->with([
                            'raw_data',
                            'auditor',
                            'qa_qtl_detail',
                            'raw_data.partner_detail',
                            'audit_results',
                            'audit_results.reason_type',
                            'audit_results.reason',
                            'bajaj_aht_calls',
                            'bajaj_bot_calls',
                            'audit_field_values',
                            'audit_field_values.field',
                            'audit_parameter_result',
                        ])->get();

                    Log::info("Audit count", ['count' => $temp_audit_data->count()]);

                    if ($temp_audit_data->isEmpty()) {
                        continue;
                    }

                    if (in_array($process[$i], $newGroupIds)) {
                        $test1 = $this->buildNewGroupTable($process[$i], $qmSheet, $temp_audit_data);
                    } else {
                        $test1 = $this->buildLegacyTable($process[$i], $qmSheet, $temp_audit_data);
                    }

                    $processName = $processInfo->name;
                    $processName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $processName);
                    $processName = preg_replace('/_+/', '_', $processName);
                    $fileName  = $processName . "_qm_sheet_" . $qmSheet->version . "_audit_report.xls";
                    $directory = public_path('processWiseFile');
                    Log::info($fileName);
                    Log::info($directory);
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }
                    $file1 = $directory . '/' . $fileName;

                    $xls1 = new \HtmlExcel();
                    $xls1->addSheet("raw_dump_audit_report", $test1);
                    $ddddd1 = $xls1->buildFile();

                    file_put_contents($file1, $ddddd1);
                    Log::info("file written: " . $file1);
                }
            }
        } catch (\Exception $th) {
            dd($th->getMessage());
        }
    }

    /* ---------------------------------------------------------------- *
     *  Shared helpers
     * ---------------------------------------------------------------- */

    private function th($label)
    {
        return "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>" . $label . "</th>";
    }

    private function td($value)
    {
        return "<td style='border:1px solid black;'>" . e($value === null || $value === '' ? 'NA' : $value) . "</td>";
    }

    private function tdCenter($value)
    {
        return "<td style='border:1px solid black;text-align: center;'>" . e($value === null || $value === '' ? '' : $value) . "</td>";
    }

    private function goodBadCallLabel($val)
    {
        if ($val == 1) {
            return 'Good Call';
        } elseif ($val == 2) {
            return 'Bad Call';
        } elseif ($val == 3) {
            return 'Average Call';
        }
        return '';
    }

    /**
     * Given a collection of audit_results rows, work out:
     * - total sub parameters observed
     * - how many were marked Fatal (selected_option == 3)
     * - how many were Not Fatal
     * - both as a percentage of the total
     *
     * Example: 5 sub-parameters, 2 have selected_option == 3
     *   -> fatal_count = 2, fatal_percent = 2/5*100 = 40
     *   -> not_fatal_count = 3, not_fatal_percent = 3/5*100 = 60
     *
     * Returns ['total'=>int,'fatal_count'=>int,'not_fatal_count'=>int,'fatal_percent'=>float,'not_fatal_percent'=>float]
     */
    private function computeFatalStats($auditResults)
    {
        $total = $auditResults->count();
        $fatalCount = $auditResults->where('selected_option', 3)->count();
        $notFatalCount = $total - $fatalCount;

        $fatalPercent = $total > 0 ? round(($fatalCount / $total) * 100, 2) : 0;
        $notFatalPercent = $total > 0 ? round(($notFatalCount / $total) * 100, 2) : 0;

        return [
            'total'             => $total,
            'fatal_count'       => $fatalCount,
            'not_fatal_count'   => $notFatalCount,
            'fatal_percent'     => $fatalPercent,
            'not_fatal_percent' => $notFatalPercent,
        ];
    }

    private function fatalStatsHeaderHtml($labelPrefix = '')
    {
        $html = '';
        $html .= $this->th($labelPrefix . 'Total Sub Parameters');
        $html .= $this->th($labelPrefix . 'Fatal Count');
        $html .= $this->th($labelPrefix . 'Fatal %');
        $html .= $this->th($labelPrefix . 'Not Fatal Count');
        $html .= $this->th($labelPrefix . 'Not Fatal %');
        return $html;
    }

    private function fatalStatsRowHtml($stats)
    {
        $html = '';
        $html .= $this->td($stats['total']);
        $html .= $this->td($stats['fatal_count']);
        $html .= $this->td($stats['fatal_percent'] . '%');
        $html .= $this->td($stats['not_fatal_count']);
        $html .= $this->td($stats['not_fatal_percent'] . '%');
        return $html;
    }

    /**
     * Build $parameter_names (id => name) and $repeater_param_data (id => [sub_id => ['name'=>..]])
     * for a given qm sheet - mirrors the controller logic.
     */
    private function buildParamStructures($qmSheetId)
    {
        $paramsData = QmSheetParameter::where('qm_sheet_id', $qmSheetId)->with('qm_sheet_sub_parameter')->get();
        $parameter_names     = [];
        $repeater_param_data = [];
        foreach ($paramsData as $p) {
            $parameter_names[$p->id] = $p->parameter;
            foreach ($p->qm_sheet_sub_parameter as $sp) {
                $repeater_param_data[$p->id][$sp->id] = ['name' => $sp->sub_parameter];
            }
        }
        return [$parameter_names, $repeater_param_data];
    }

    private function repeaterHeaderHtml($repeater_param_data)
    {
        $html = '';
        foreach ($repeater_param_data as $group) {
            foreach ($group as $param) {
                $html .= $this->th($param['name']);
                $html .= $this->th('Scored');
                $html .= $this->th('Scorable');
                $html .= $this->th('Reason Type');
                $html .= $this->th('Reason');
                $html .= $this->th('Remark');
            }
        }
        return $html;
    }

    private function parameterSummaryHeaderHtml($parameter_names)
    {
        $html = '';
        foreach ($parameter_names as $pname) {
            $html .= $this->th('Parameter Name: ' . $pname);
            $html .= $this->th('Fatal/ Or Not');
            $html .= $this->th('Scorable');
            $html .= $this->th('Scored');
            $html .= $this->th('Sub Parameters');
            $html .= $this->th('Fatal Count');
            $html .= $this->th('Fatal %');
            $html .= $this->th('Not Fatal Count');
            $html .= $this->th('Not Fatal %');
        }
        return $html;
    }

    /**
     * Builds the repeater row html + total scored/scorable/critical flag for one audit record.
     * Returns [html, total_scored, total_scorable, is_critical]
     */
    private function repeaterRowHtml($value, $repeater_param_data)
    {
        $html           = '';
        $total_scored   = 0;
        $total_scorable = 0;
        $is_critical    = 0;
        $temp_result    = $value->audit_results;

        foreach ($repeater_param_data as $pid => $subs) {
            foreach ($subs as $spid => $sp) {
                $rows    = $temp_result->where('parameter_id', $pid)->where('sub_parameter_id', $spid);
                $matched = $rows->first();

                $obs = '';
                $scored = 0;
                $scorable = 0;
                $reasonType = '';
                $reason = '';
                $remark = '';

                if ($matched) {
                    $obs = $matched->is_non_scoring
                        ? return_non_scoring_observation($matched->selected_option)
                        : return_general_observation($matched->selected_option);
                    $scored     = $rows->sum('score');
                    $scorable   = $matched->after_audit_weight;
                    $reasonType = $matched->reason_type->name ?? ($matched->reason_type_id ?? '');
                    $reason     = $matched->reason->name ?? ($matched->reason_id ?? '');
                    $remark     = $matched->remark;

                    if ($obs == 'Critical') {
                        $is_critical = 1;
                    }
                }

                $html .= $this->td($obs);
                $html .= $this->td($scored);
                $html .= $this->td($scorable);
                $html .= $this->td($reasonType);
                $html .= $this->td($reason);
                $html .= $this->td($remark);

                $total_scored   += (float) $scored;
                $total_scorable += (float) $scorable;
            }
        }

        return [$html, $total_scored, $total_scorable, $is_critical];
    }

    private function parameterSummaryRowHtml($value, $parameter_names)
    {
        $row_parameter_result = [];
        foreach ($parameter_names as $pid => $pname) {
            $row_parameter_result[$pid] = ['is_critical' => 0, 'scorable' => 0, 'scored' => 0];
        }
        foreach ($value->audit_parameter_result as $apr) {
            if (isset($row_parameter_result[$apr->parameter_id])) {
                $row_parameter_result[$apr->parameter_id] = [
                    'is_critical' => $apr->is_critical,
                    'scorable'    => $apr->temp_weight,
                    'scored'      => $apr->with_fatal_score,
                ];
            }
        }

        $html = '';
        foreach ($parameter_names as $pid => $pname) {
            $r = $row_parameter_result[$pid];
            $html .= $this->td($pname);
            $html .= $this->td($r['is_critical'] ? 'Fatal' : 'Not Fatal');
            $html .= $this->td($r['scorable']);
            $html .= $this->td($r['scored']);

            // Fatal / Not-Fatal count + % scoped to this parameter's own sub-parameters
            $paramResults = $value->audit_results->where('parameter_id', $pid);
            $stats = $this->computeFatalStats($paramResults);
            $html .= $this->fatalStatsRowHtml($stats);
        }
        return $html;
    }

    /* ---------------------------------------------------------------- *
     *  New layouts (process 224-284) - mirrors reports.raw_duml_with_audit_report
     * ---------------------------------------------------------------- */

    private function buildNewGroupTable($processId, $qmSheet, $temp_audit_data)
    {
        [$parameter_names, $repeater_param_data] = $this->buildParamStructures($qmSheet->id);
        $repeaterHeader = $this->repeaterHeaderHtml($repeater_param_data);
        $paramSummaryHeader = $this->parameterSummaryHeaderHtml($parameter_names);

        $dynamicHeaders = collect();
        if (in_array($processId, $this->groupB)) {
            $dynamicHeaders = AuditFormFields::where('process_id', $processId)
                ->where('status', 1)
                ->orderBy('id')
                ->get();
        }

        $header = "<table style='border:1px solid black;'><tr>";
        $header .= $this->th('Sr. No');
        $header .= $this->th('Audit Date');
        $header .= $this->th('Auditor Name');

        if (in_array($processId, $this->groupA)) {
            $header .= $this->th('Call Id/CaseNumber');
            $header .= $this->th('Case Audit date');
            $header .= $this->th('Emp Id');
            $header .= $this->th('QC Username');
            $header .= $this->th('Auditor ID');
            $header .= $this->th('Advisior Name');
            $header .= $this->th('Case Type');
            $header .= $this->th('TL Name');
            $header .= $this->th('Status');
            $header .= $this->th('Agent Name');
            $header .= $this->th('LOB');
            $header .= $this->th('Case Reason');
            $header .= $this->th('Agent processing date');
            $header .= $this->th('System Name');
            $header .= $this->th('Case Number');
            $header .= $this->th('Case Origin');
            $header .= $this->th('Relationship Number');
            $header .= $this->th('Overdue Waiver Amount');
            $header .= $this->th('Waiver Status');
            $header .= $this->th('Waiver Amount');
            $header .= $this->th('Other charges');
            $header .= $this->th('CBC');
            $header .= $this->th('LPP');
            $header .= $this->th('Call duration');
            $header .= $this->th('Email');
            $header .= $this->th('Language');
            $header .= $this->th('Vertical');
            $header .= $this->th('Tenure');
            $header .= $this->th('Category (ABCU)');
        } elseif (in_array($processId, $this->groupB)) {
            $header .= $this->th('Call Id/CaseNumber');
            foreach ($dynamicHeaders as $field) {
                $header .= $this->th($field->field_name);
            }
        } elseif (in_array($processId, $this->groupC)) {
            $header .= $this->th('Call Id/CaseNumber');
            $header .= $this->th('Current Assigned Queue');
            $header .= $this->th('Case origin');
            $header .= $this->th('Case Number');
            $header .= $this->th('Relationship Number');
            $header .= $this->th('Complaint');
            $header .= $this->th('Customer Redressal Decision');
            $header .= $this->th('Case ID');
            $header .= $this->th('Case Opened date');
            $header .= $this->th('Case Closing Date');
            $header .= $this->th('Type');
            $header .= $this->th('Sub Type');
            $header .= $this->th('Case Reason');
            $header .= $this->th('Case Disposition Name');
            $header .= $this->th('Customer Query');
            $header .= $this->th('Case Owner');
            $header .= $this->th('Status');
            $header .= $this->th('Case reason Status');
            $header .= $this->th('Case reasons');
            $header .= $this->th('Outcall Status');
            $header .= $this->th('UUID Captured in Comments');
            $header .= $this->th('Allegation category');
            $header .= $this->th('Impact');
            $header .= $this->th('Transaction Exectution');
            $header .= $this->th('Empowerment Validation');
            $header .= $this->th('Empowerment Validation-2');
            $header .= $this->th('Comments Updated');
            $header .= $this->th('Response Template Selection');
            $header .= $this->th('Correct LAN');
            $header .= $this->th('Correct LAN comment (If incorrect)');
            $header .= $this->th('Corrective action taken');
            $header .= $this->th('service recovery');
        } elseif (in_array($processId, $this->groupD)) {
            $header .= $this->th('Call Id/CaseNumber');
            $header .= $this->th('Call Date');
            $header .= $this->th('Call Duration');
            $header .= $this->th('Case Date');
            $header .= $this->th('Date of QC');
            $header .= $this->th('EMP ID');
            $header .= $this->th('Agent Name');
            $header .= $this->th('TL Name');
            $header .= $this->th('Platform');
            $header .= $this->th('Source');
            $header .= $this->th('Case Reason');
            $header .= $this->th('Disposition');
            $header .= $this->th('Event');
            $header .= $this->th('Reason for Closure');
            $header .= $this->th('Appropriate/Inappropriate / QC Status');
            $header .= $this->th('Observation');
            $header .= $this->th('Corrective Action');
            $header .= $this->th('Corrective action Status (Yes/No)');
            $header .= $this->th('Preventive Action');
            $header .= $this->th('QC Done BY');
            $header .= $this->th('API No.');
            $header .= $this->th('Case Reason Tagged');
            $header .= $this->th('Case Number');
        } elseif (in_array($processId, $this->groupE)) {
            $header .= $this->th('Location');
            $header .= $this->th('Call Id/CaseNumber');
            $header .= $this->th('Employee ID');
            $header .= $this->th('Auditor Name');
            $header .= $this->th('Loan A/c Number');
            $header .= $this->th('Case Owner');
            $header .= $this->th('Case Orgin');
            $header .= $this->th('Case Type');
            $header .= $this->th('Type');
            $header .= $this->th('Sub Type');
            $header .= $this->th('Case Reason');
            $header .= $this->th('Solution');
            $header .= $this->th('Action Date');
            $header .= $this->th('Monitor Date');
        } else {
            // Group F - everything else in 224-284
            $header .= $this->th('Location');
            $header .= $this->th('Call Id/CaseNumber');
            $header .= $this->th('Unit');
            $header .= $this->th('Process');
            $header .= $this->th('Action Date');
            $header .= $this->th('Audit Type');
            $header .= $this->th('Agent Name');
            $header .= $this->th('Employee ID');
            $header .= $this->th('Loan A/c Number');
            $header .= $this->th('Case Status');
            $header .= $this->th('Case Type');
            $header .= $this->th('Type');
            $header .= $this->th('Sub Type');
            $header .= $this->th('Case Reason');
            $header .= $this->th('Solution');
            $header .= $this->th('Associate Name');
            $header .= $this->th('Customer ID');
            $header .= $this->th('Customer Name');
            $header .= $this->th('Brand Name');
            $header .= $this->th('Order Stage');
            $header .= $this->th('Sr Mgr');
            $header .= $this->th('FPR');
            $header .= $this->th('HRS TAT Bucket');
            $header .= $this->th('Info 1');
            $header .= $this->th('Info 2');
            $header .= $this->th('Info 3');
            $header .= $this->th('Info 4');
            $header .= $this->th('Info 5');
            $header .= $this->th('Error Code');
            $header .= $this->th('Error Reason Type');
            $header .= $this->th('Error Code Reason');
            $header .= $this->th('Issues');
            $header .= $this->th('Sub Issues');
            $header .= $this->th('Case Reason When Closed');
            $header .= $this->th('Product Id');
            $header .= $this->th('Solution Based Category C');
            $header .= $this->th('SFDC Disposition');
            $header .= $this->th('Gen AI');
            $header .= $this->th('Variable');
            $header .= $this->th('Hot Leads');
            $header .= $this->th('Consented/Non consented leads');
            $header .= $this->th('Customer STT 1');
            $header .= $this->th('Customer STT 2');
            $header .= $this->th('Customer STT 3');
            $header .= $this->th('Customer STT 4');
            $header .= $this->th('Customer STT 5');
            $header .= $this->th('Intervention in call');
            $header .= $this->th('Intervention Drop down');
            $header .= $this->th('Agent intervenes Reason');
            $header .= $this->th('Info given by senior');
            $header .= $this->th('Case Create Date');
        }

        $header .= $this->th('Overall Summary');
        $header .= $this->th('Agent feedback');
        $header .= $this->th('Good/Bad Call');
        $header .= $repeaterHeader;
        $header .= $this->th('Total Scored');
        $header .= $this->th('Total Scorable');
        $header .= $this->fatalStatsHeaderHtml('Overall ');
        $header .= $paramSummaryHeader;
        $header .= "</tr><tbody>";

        $body = '';
        $srNo = 0;
        foreach ($temp_audit_data as $value) {
            $srNo++;
            try {
                $rd = $value->raw_data;

                $row = "<tr>";
                $row .= $this->td($srNo);
                $row .= $this->td($value->audit_date);
                $row .= $this->td(optional($value->auditor)->name);

                if (in_array($processId, $this->groupA)) {
                    $row .= $this->td(optional($rd)->call_id);
                    $row .= $this->td(optional($rd)->case_create_date);
                    $row .= $this->td(optional($rd)->emp_id);
                    $row .= $this->td(optional($rd)->user_name);
                    $row .= $this->td(optional($rd)->auditor_id);
                    $row .= $this->td(optional($rd)->auditor_name);
                    $row .= $this->td(optional($rd)->case_type);
                    $row .= $this->td(optional($rd)->bfl_tl_name);
                    $row .= $this->td(optional($rd)->case_status);
                    $row .= $this->td(optional($rd)->agent_name);
                    $row .= $this->td(optional($rd)->lob);
                    $row .= $this->td(optional($rd)->case_reason);
                    $row .= $this->td(optional($rd)->case_create_date);
                    $row .= $this->td(optional($rd)->audit_type);
                    $row .= $this->td(optional($rd)->case_number);
                    $row .= $this->td(optional($rd)->case_origin);
                    $row .= $this->td(optional($rd)->relationship_number);
                    $row .= $this->td(optional($rd)->overdue_wavier_amount);
                    $row .= $this->td(optional($rd)->wavier_status);
                    $row .= $this->td(optional($rd)->wavier_amount);
                    $row .= $this->td(optional($rd)->other_charges);
                    $row .= $this->td(optional($rd)->cbc);
                    $row .= $this->td(optional($rd)->lpp);
                    $row .= $this->td(optional($rd)->call_duration);
                    $row .= $this->td(optional($rd)->email);
                    $row .= $this->td(optional($rd)->language);
                    $row .= $this->td(optional($rd)->vertical);
                    $row .= $this->td(optional($rd)->diff);
                    $row .= $this->td(optional($rd)->audit_type);
                } elseif (in_array($processId, $this->groupB)) {
                    $row .= $this->td(optional($rd)->call_id);

                    $dynamicFieldsMap = [];
                    foreach ($value->audit_field_values as $fv) {
                        $dynamicFieldsMap[$fv->field_id] = $fv->field_value;
                    }
                    foreach ($dynamicHeaders as $fieldDef) {
                        $row .= $this->td($dynamicFieldsMap[$fieldDef->id] ?? 'NA');
                    }
                } elseif (in_array($processId, $this->groupC)) {
                    $row .= $this->td(optional($rd)->call_id);
                    $row .= $this->td(optional($rd)->current_assigned_queue);
                    $row .= $this->td(optional($rd)->case_origin);
                    $row .= $this->td(optional($rd)->case_number);
                    $row .= $this->td(optional($rd)->relationship_number);
                    $row .= $this->td(optional($rd)->error_code_reason);
                    $row .= $this->td(optional($rd)->error_code);
                    $row .= $this->td(optional($rd)->case_id);
                    $row .= $this->td(optional($rd)->case_create_date);
                    $row .= $this->td(optional($rd)->close_date);
                    $row .= $this->td(optional($rd)->call_type);
                    $row .= $this->td(optional($rd)->call_sub_type);
                    $row .= $this->td(optional($rd)->case_reason);
                    $row .= $this->td(optional($rd)->disposition);
                    $row .= $this->td(optional($rd)->scenerio);
                    $row .= $this->td(optional($rd)->case_origin_channel);
                    $row .= $this->td(optional($rd)->case_status);
                    $row .= $this->td($value->case_dropdown);
                    $row .= $this->td($value->case_reason_if_incorrect);
                    $row .= $this->td($value->caller_type);
                    $row .= $this->td($value->scenario);
                    $row .= $this->td($value->scanerio_codes);
                    $row .= $this->td($value->l2_ticket_disposition);
                    $row .= $this->td($value->call_disposition);
                    $row .= $this->td($value->bot_disposition_as_per_logic);
                    $row .= $this->td($value->empowerment_validation);
                    $row .= $this->td($value->case_comment);
                    $row .= $this->td($value->acpt_l2);
                    $row .= $this->td($value->call_stage);
                    $row .= $this->td($value->lan_incorrect_comment);
                    $row .= $this->td($value->sfdc_disposition);
                    $row .= $this->td($value->hot_leads);
                } elseif (in_array($processId, $this->groupD)) {
                    $row .= $this->td(optional($rd)->call_id);
                    $row .= $this->td(optional($rd)->call_time);
                    $row .= $this->td(optional($rd)->call_duration);
                    $row .= $this->td(optional($rd)->case_create_date);
                    $row .= $this->td(optional($rd)->close_date);
                    $row .= $this->td(optional($rd)->emp_id);
                    $row .= $this->td(optional($rd)->agent_name);
                    $row .= $this->td(optional($rd)->bfl_tl_name);
                    $row .= $this->td(optional($rd)->product_business_family);
                    $row .= $this->td(optional($rd)->scenerio);
                    $row .= $this->td(optional($rd)->case_reason);
                    $row .= $this->td(optional($rd)->disposition);
                    $row .= $this->td(optional($rd)->event_c);
                    $row .= $this->td(optional($rd)->case_reason_when_case_closed);
                    $row .= $this->td(optional($rd)->issues);
                    $row .= $this->td(optional($rd)->sub_issues);
                    $row .= $this->td($value->sfdc_disposition);
                    $row .= $this->td($value->hot_leads);
                    $row .= $this->td($value->variable);
                    $row .= $this->td(optional($rd)->vendor_name);
                    $row .= $this->td(optional($rd)->reference_no);
                    $row .= $this->td(optional($rd)->case_origin_channel);
                    $row .= $this->td(optional($rd)->case_number);
                } elseif (in_array($processId, $this->groupE)) {
                    $row .= $this->td(optional(optional($rd)->location_data)->name);
                    $row .= $this->td(optional($rd)->call_id);
                    $row .= $this->td(optional($rd)->emp_id);
                    $row .= $this->td(optional($rd)->auditor_name);
                    $row .= $this->td(optional($rd)->reference_no);
                    $row .= $this->td(optional($rd)->case_origin_channel);
                    $row .= $this->td(optional($rd)->case_origin);
                    $row .= $this->td(optional($rd)->case_type);
                    $row .= $this->td(optional($rd)->call_type);
                    $row .= $this->td(optional($rd)->call_sub_type);
                    $row .= $this->td(optional($rd)->case_reason);
                    $row .= $this->td(optional($rd)->solution);
                    $row .= $this->td(optional($rd)->case_create_date);
                    $row .= $this->td(optional($rd)->close_date);
                } else {
                    // Group F
                    $row .= $this->td(optional(optional($rd)->location_data)->name);
                    $row .= $this->td(optional($rd)->call_id);
                    $row .= $this->td(optional($rd)->qrc_1);
                    $row .= $this->td(optional($rd)->bfl_process);
                    $row .= $this->td(optional($rd)->opened_date);
                    $row .= $this->td(optional($rd)->audit_type);
                    $row .= $this->td(optional($rd)->agent_name);
                    $row .= $this->td(optional($rd)->emp_id);
                    $row .= $this->td(optional($rd)->reference_no);
                    $row .= $this->td(optional($rd)->case_status);
                    $row .= $this->td(optional($rd)->case_type);
                    $row .= $this->td(optional($rd)->call_type);
                    $row .= $this->td(optional($rd)->call_sub_type);
                    $row .= $this->td(optional($rd)->case_reason);
                    $row .= $this->td(optional($rd)->solution);
                    $row .= $this->td(optional($rd)->campaign_name);
                    $row .= $this->td(optional($rd)->customer_id);
                    $row .= $this->td(optional($rd)->customer_name);
                    $row .= $this->td(optional($rd)->brand_name);
                    $row .= $this->td(optional($rd)->order_stage);
                    $row .= $this->td(optional($rd)->sr_mgr);
                    $row .= $this->td(optional($rd)->fpr);
                    $row .= $this->td(optional($rd)->hrs_tat_bucket);
                    $row .= $this->td(optional($rd)->info_1);
                    $row .= $this->td(optional($rd)->info_2);
                    $row .= $this->td(optional($rd)->info_3);
                    $row .= $this->td(optional($rd)->info_4);
                    $row .= $this->td(optional($rd)->info_5);
                    $row .= $this->td(optional($rd)->error_code);
                    $row .= $this->td(optional($rd)->error_reason_type);
                    $row .= $this->td(optional($rd)->error_code_reason);
                    $row .= $this->td(optional($rd)->issues);
                    $row .= $this->td(optional($rd)->sub_issues);
                    $row .= $this->td(optional($rd)->case_reason_when_case_closed);
                    $row .= $this->td(optional($rd)->product_id);
                    $row .= $this->td(optional($rd)->solution_based_category_c);
                    $row .= $this->td($value->sfdc_disposition);
                    $row .= $this->td($value->gen_ai);
                    $row .= $this->td($value->variable);
                    $row .= $this->td($value->hot_leads);
                    $row .= $this->td($value->consent_nonconcet_lead);
                    $row .= $this->td($value->customer_stt_1);
                    $row .= $this->td($value->customer_stt2);
                    $row .= $this->td($value->customer_stt_3);
                    $row .= $this->td($value->customer_stt_4);
                    $row .= $this->td($value->customer_stt_5);
                    $row .= $this->td($value->intervention_in_call);
                    $row .= $this->td($value->intervention_drop_down);
                    $row .= $this->td($value->agent_intervenes_reason);
                    $row .= $this->td($value->info_given_by_sinior);
                    $row .= $this->td(optional($rd)->case_create_date);
                }

                $row .= $this->td($value->overall_summary);
                $row .= $this->td($value->feedback);
                $row .= $this->td($this->goodBadCallLabel($value->good_bad_call));

                [$repeaterRow, $totalScored, $totalScorable, $isCritical] = $this->repeaterRowHtml($value, $repeater_param_data);
                $row .= $repeaterRow;

                $row .= $this->tdCenter($isCritical ? 0 : min($totalScored, $totalScorable));
                $row .= $this->tdCenter($totalScorable > 100 ? 100 : $totalScorable);

                // Overall (whole-audit) fatal / not-fatal count + percentage
                $overallStats = $this->computeFatalStats($value->audit_results);
                $row .= $this->fatalStatsRowHtml($overallStats);

                $row .= $this->parameterSummaryRowHtml($value, $parameter_names);

                $row .= "</tr>";
                $body .= $row;
            } catch (\Throwable $th) {
                Log::error('rawDump_excel row error: ' . $th->getMessage());
                $incom = new IncompleteAudit();
                $incom->audit_id = $value->id;
                $incom->save();
            }
        }

        return $header . $body . "</tbody></table>";
    }

    /* ---------------------------------------------------------------- *
     *  Legacy layout - unchanged, used for processes NOT in 224-284
     * ---------------------------------------------------------------- */

    private function buildLegacyTable($processId, $qmSheet, $temp_audit_data)
    {
        $test1 = "<table style='border:1px solid black;'>
                <tr>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Auditor Name</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Audit Date</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Partner</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Location</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Call Id</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Call File</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Agent Name</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Agent Feedback</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>DOJ</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>LOB</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Language</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Case ID</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Auditor Total Spend Spend Time</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Call Time</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Call Duration</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Call Type</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Call Subtype</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Disposition</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Compaign Name</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Customer Name</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Reference Number</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>QRC</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Overall Summary</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>With Fatal Score</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Without Fatal Score</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Brand Name</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Circle</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Info 1</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Info 2</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Info 3</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Info 4</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Info 5</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Good/Bad Call</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Consented/Non consented leads</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>SFDC Disposition</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Focused Audit Sub type</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Recording LInk</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Call Count</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Hot Leads</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Hot Leads Reason</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Employee ID</th>";

        if ($processId == 88 || $processId == 120) {
            for ($j = 1; $j < 21; $j++) {
                $test1 .= $this->th('AHT ' . $j);
                $test1 .= $this->th('Mobile No ' . $j);
            }
        }

        if ($processId == 138) {
            $test1 .= $this->th('case_number');
            $test1 .= $this->th('case_origin');
            $test1 .= $this->th('current_assigned_queue');
            $test1 .= $this->th('sub_type');
            $test1 .= $this->th('partner_emp_id');
            $test1 .= $this->th('partner_agent_name');
            $test1 .= $this->th('partner_agent_tenure');
            $test1 .= $this->th('case_reason');
            $test1 .= $this->th('solution');
            $test1 .= $this->th('opened_date');
            $test1 .= $this->th('close_date');
            $test1 .= $this->th('fpr');
            $test1 .= $this->th('sr_mgr');
            $test1 .= $this->th('relationship_number');
        }
        if ($processId == 109) {
            $test1 .= $this->th('First Level Drill Down');
            $test1 .= $this->th('Second Level Drill Down');
            $test1 .= $this->th('Third Level Drill Down');
            $test1 .= $this->th('ACPT');
        }

        if (!isset($temp_audit_data[0]) || !$temp_audit_data[0]->audit_results) {
            $test1 .= "</tr><tbody></tbody></table>";
            return $test1;
        }

        foreach ($temp_audit_data[0]->audit_results as $value) {
            $subParameter = QmSheetSubParameter::find($value->sub_parameter_id);
            $test1 .= $this->th($subParameter->sub_parameter ?? '');
            $test1 .= $this->th('Scored');
            $test1 .= $this->th('Scorable');
            $test1 .= $this->th('Reason Type');
            $test1 .= $this->th('Reason');
            $test1 .= $this->th('Remark');
        }

        $test1 .= $this->th('Total Score');
        $test1 .= $this->th('Total Scoreable');
        $test1 .= "</tr><tbody>";

        foreach ($temp_audit_data as $value) {
            if ($value->good_bad_call_file) {
                $path_name = str_replace("https://qmtool.s3.ap-south-1.amazonaws.com/", "", $value->good_bad_call_file);
                $url = Storage::disk('s3')->temporaryUrl($path_name, now()->addMinutes(8640));
                $callFile = $url;
            } else {
                $callFile = $value->good_bad_call_file;
            }
            $partnerName = optional(optional($value->raw_data)->partner_detail)->name ?? '';

            try {
                $test1 .= "<tr>";
                $test1 .= $this->td(optional($value->qa_qtl_detail)->name);
                $test1 .= $this->td($value->audit_date);
                $test1 .= $this->td($partnerName);
                $test1 .= $this->td(optional($value->raw_data)->location);
                $test1 .= $this->td(optional($value->raw_data)->call_id);
                $test1 .= $this->td($callFile);
                $test1 .= $this->td(optional($value->raw_data)->agent_name);
                $test1 .= $this->td($value->feedback);
                $test1 .= $this->td(optional($value->raw_data)->doj);
                $test1 .= $this->td(optional($value->raw_data)->lob);
                $test1 .= $this->td($value->language_2);
                $test1 .= $this->td($value->case_id);

                $sheet_time = $value->auditor_time_spend_in_secs > $value->auditor_sheet_time_secs
                    ? $value->auditor_time_spend_in_secs
                    : $value->auditor_sheet_time_secs;
                $h = intval($sheet_time / 3600);
                $sheet_time -= $h * 3600;
                $m = intval($sheet_time / 60);
                $s = $sheet_time - $m * 60;
                $test1 .= $this->tdCenter("{$m} Minutes {$s} Secs");

                $test1 .= $this->td(optional($value->raw_data)->call_time);
                $test1 .= $this->td(optional($value->raw_data)->call_duration);
                $test1 .= $this->td(optional($value->raw_data)->call_type);
                $test1 .= $this->td(optional($value->raw_data)->call_sub_type);
                $test1 .= $this->td(optional($value->raw_data)->disposition);
                $test1 .= $this->td(optional($value->raw_data)->campaign_name);
                $test1 .= $this->td(optional($value->raw_data)->customer_name);
                $test1 .= $this->td($value->reference_no);
                $test1 .= $this->td($value->qrc_2);
                $test1 .= $this->td($value->overall_summary);
                $test1 .= $this->td($value->with_fatal_score_per);
                $test1 .= $this->td($value->overall_score);
                $test1 .= $this->td(optional($value->raw_data)->brand_name);
                $test1 .= $this->td(optional($value->raw_data)->circle);
                $test1 .= $this->td(optional($value->raw_data)->info_1);
                $test1 .= $this->td(optional($value->raw_data)->info_2);
                $test1 .= $this->td(optional($value->raw_data)->info_3);
                $test1 .= $this->td(optional($value->raw_data)->info_4);
                $test1 .= $this->td(optional($value->raw_data)->info_5);
                $test1 .= $this->td($this->goodBadCallLabel($value->good_bad_call));
                $test1 .= $this->td($value->consent_nonconcet_lead);
                $test1 .= $this->td($value->sfdc_disposition);
                $test1 .= $this->td(optional($value->raw_data)->sub_issues);
                $test1 .= $this->td($value->recording_link);
                $test1 .= $this->td($value->call_count);
                $test1 .= $this->td($value->hot_leads);
                $test1 .= $this->td($value->hot_leads_reason);
                $test1 .= $this->td(optional($value->raw_data)->emp_id);

                $aht_times = $call_ids = [];
                foreach ($value->bajaj_aht_calls as $valued) {
                    $aht_times[] = $valued['aht_time'];
                    $call_ids[]  = $valued['call_id'];
                }

                if ($processId == 88 || $processId == 120) {
                    for ($j = 1; $j < 21; $j++) {
                        $ath      = $aht_times[$j] ?? '-';
                        $callTime = $call_ids[$j] ?? '-';
                        $test1 .= $this->tdCenter($ath);
                        $test1 .= $this->tdCenter($callTime);
                    }
                }

                if ($processId == 138) {
                    $test1 .= $this->td(optional($value->raw_data)->case_number);
                    $test1 .= $this->td(optional($value->raw_data)->case_origin);
                    $test1 .= $this->td(optional($value->raw_data)->current_assigned_queue);
                    $test1 .= $this->td(optional($value->raw_data)->sub_type);
                    $test1 .= $this->td(optional($value->raw_data)->partner_emp_id);
                    $test1 .= $this->td(optional($value->raw_data)->partner_agent_name);
                    $test1 .= $this->td(optional($value->raw_data)->partner_agent_tenure);
                    $test1 .= $this->td(optional($value->raw_data)->case_reason);
                    $test1 .= $this->td(optional($value->raw_data)->solution);
                    $test1 .= $this->td(optional($value->raw_data)->opened_date);
                    $test1 .= $this->td(optional($value->raw_data)->close_date);
                    $test1 .= $this->td(optional($value->raw_data)->fpr);
                    $test1 .= $this->td(optional($value->raw_data)->sr_mgr);
                    $test1 .= $this->td(optional($value->raw_data)->relationship_number);
                }

                if ($processId == 109) {
                    $test1 .= $this->td(optional($value->raw_data)->call_time);
                    $test1 .= $this->td(optional($value->raw_data)->call_duration);
                    $test1 .= $this->td($value->refrence_number);
                    $test1 .= $this->td($value->error_code);
                }

                $total_scored   = 0;
                $total_scorable = 0;
                $is_critical    = 0;

                foreach ($value->audit_results as $results) {
                    $observation = $results->is_non_scoring
                        ? return_non_scoring_observation($results->selected_option)
                        : return_general_observation($results->selected_option);

                    if ($observation == 'Critical') {
                        $is_critical = 1;
                    }

                    $test1 .= $this->td($observation);
                    $test1 .= $this->td($results->score);
                    $test1 .= $this->td($results->after_audit_weight);
                    $test1 .= $this->td(!empty($results->reason_type_id) ? ($results->reason_type->name ?? '') : '');
                    $test1 .= $this->td(!empty($results->reason_id) ? ($results->reason->name ?? $results->reason_id) : '');
                    $test1 .= $this->td($results->remark);

                    $total_scored   += $results->score;
                    $total_scorable += $results->after_audit_weight;
                }

                $test1 .= $this->tdCenter($is_critical == 1 ? 0 : min($total_scored, $total_scorable));
                $test1 .= $this->tdCenter(min($total_scorable, 100));

                $test1 .= "</tr>";
            } catch (\Throwable $th) {
                Log::error('rawDump_excel legacy row error: ' . $th->getMessage());
                $incom = new IncompleteAudit();
                $incom->audit_id = $value->id;
                $incom->save();
            }
        }

        $test1 .= "</tbody></table>";
        return $test1;
    }
}