<?php

namespace App\Console\Commands;

use App\Partner;
use App\PartnersProcess;
use Illuminate\Console\Command;
use App\AuditParameterResult;
use App\ParameterAuditLog;
use App\RawData;
use App\Audit;
use App\User;
use App\Rebuttal;
use App\Process;
use App\QmSheet;
use App\ErrorCode;
use App\IncompleteAudit;
use App\QmSheetParameter;
use App\QmSheetSubParameter;
use App\Mail\Agentfeedback;
use App\Mail\raw_dump;
use Illuminate\Support\Facades\Mail;
use DateTime;
use Illuminate\Support\Facades\Storage;

class TATA_Raw_dump extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TATA:RawDump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $partnerData = Partner::where("client_id", 9)->pluck('id')->toArray();
            $processData = PartnersProcess::whereIn('partner_id', $partnerData)->pluck('process_id')->toArray();

            // $startDate = date('Y-m-01');
            // $lastDate = date("Y-m-d");
            $startDate = date('2025-09-01');
            // $lastDate = date("Y-m-d");
            $lastDate = date("2025-09-30");


            $process =  Audit::where('client_id', 9)->whereDate('audit_date', '>=', $startDate)->whereDate('audit_date', '<=', $lastDate)->distinct()->pluck('process_id')->toArray();

            for ($i = 0; $i < count($process); $i++) {
                $processInfo = Process::find($process[$i]);

                // $qmSheet = QmSheet::where('client_id', 23)->where('process_id', $process[$i])->orderBy('version', 'desc')->first();


                $qmSheetIds = Audit::whereDate('audit_date', '>=', $startDate)
                    ->whereDate('audit_date', '<=', $lastDate)
                    ->where('process_id', $process[$i])
                    ->distinct()
                    ->pluck('qm_sheet_id')
                    ->toArray();

                foreach ($qmSheetIds as $qmSheetId) {
                    $qmSheet = QmSheet::find($qmSheetId);
                    $subParameters = QmSheetSubParameter::where('qm_sheet_id', $qmSheet->id)->pluck('sub_parameter')->toArray();
                    $temp_audit_data = Audit::where('qm_sheet_id', $qmSheet->id)
                        ->where('process_id', $process[$i])->whereDate('audit_date', '>=', $startDate)->whereDate('audit_date', '<=', $lastDate)->where('deleted', 0)
                        ->with(['raw_data', 'qa_qtl_detail', 'raw_data.partner_detail', 'audit_results', 'audit_results.reason_type', 'audit_results.reason', 'bajaj_aht_calls'])->get();
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
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Recording LInk</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Call Count</th>
                    <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Employee ID</th>";


                  

            

                  



                    foreach ($temp_audit_data[0]->audit_results as $key => $value) {
                        $subParameter = QmSheetSubParameter::find($value->sub_parameter_id);
                        $test1 .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>" . $subParameter->sub_parameter . "</th><th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Scored</th><th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Scorable</th><th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Reason Type</th><th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Reason</th>
                        <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Remark</th>";
                    }

                    $test1 .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Total Score</th>
                <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Total Scoreable</th>
                </tr>
                <tbody>";


                    foreach ($temp_audit_data as $key => $value) {
                        if ($value->good_bad_call_file) {
                            $path_name = str_replace("https://qmtool.s3.ap-south-1.amazonaws.com/", "", $value->good_bad_call_file);
                            $url = Storage::disk('s3')->temporaryUrl(
                                $path_name,
                                now()->addMinutes(8640) //Minutes for which the signature will stay valid
                            );
                            $callFile = $url;
                        } else {
                            $callFile = $value->good_bad_call_file;
                        }
                        $partnerName = '';
                        if (isset($value->raw_data->partner_detail->name) && $value->raw_data->partner_detail->name != '') {
                            $partnerName = $value->raw_data->partner_detail->name;
                        }
                        try {
                            $test1 .= "<tr>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->qa_qtl_detail->name . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->audit_date . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $partnerName . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->raw_data->location . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->raw_data->call_id . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $callFile . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->raw_data->agent_name . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->feedback . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->raw_data->doj . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->raw_data->lob . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->language_2 . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->case_id . "</td>";                          
                           
                            $h = intval($value->auditor_time_spend_in_secs / 3600);
                            $value->auditor_time_spend_in_secs = $value->auditor_time_spend_in_secs - $h * 3600;
                            $m = intval($value->auditor_time_spend_in_secs / 60);
                            $s = $value->auditor_time_spend_in_secs - $m * 60;

                            if (!isset($h)) {
                                $test1 .= "<td style='border:1px solid black;text-align: center;'>{$h} Hours {$m} Minutes {$s} Secs </td>";
                            } else {
                                $test1 .= "<td style='border:1px solid black;text-align: center;'>{$m} Minutes {$s} Secs </td>";
                            }


                            // $test1 .= "<td style='border:1px solid black;'>".$value->auditor_time_spend_in_secs."</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->raw_data->call_time . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->raw_data->call_duration . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->raw_data->call_type . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->raw_data->call_sub_type . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->raw_data->disposition . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->raw_data->campaign_name . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->raw_data->customer_name . "</td>";                           
                            $test1 .= "<td style='border:1px solid black;'>" . $value->reference_no . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->qrc_2 . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->overall_summary . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->with_fatal_score_per . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->overall_score . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->raw_data->brand_name . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->raw_data->circle . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->raw_data->info_1 . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->raw_data->info_2 . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->raw_data->info_3 . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->raw_data->info_4 . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->raw_data->info_5 . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->recording_link . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->call_count . "</td>";
                            $test1 .= "<td style='border:1px solid black;'>" . $value->raw_data->emp_id . "</td>";

                          
                          
                            $total_scored = 0;
                            $total_scorable = 0;
                            $is_critical = 0;

                            foreach ($value->audit_results as $results) {

                                if ($results->is_non_scoring) {
                                    $observation = return_non_scoring_observation($results->selected_option);
                                } else {
                                    $observation = return_general_observation($results->selected_option);
                                }


                                if ($observation == 'Critical') {
                                    $is_critical = 1;
                                }

                                if ($results->is_non_scoring) {
                                    $test1 .= "<td style='border:1px solid black;'>" . $observation . "</td>";
                                } else {
                                    $test1 .= "<td style='border:1px solid black;'>" . $observation . "</td>";
                                }

                                $test1 .= "<td style='border:1px solid black;'>" . $results->score . "</td>";
                                $test1 .= "<td style='border:1px solid black;'>" . $results->after_audit_weight . "</td>";
                                if (!empty($results->reason_type_id)) {
                                    $test1 .= "<td style='border:1px solid black;'>" . htmlspecialchars($results->reason_type->name ?? '') . "</td>";
                                } else {
                                    $test1 .= "<td style='border:1px solid black;'></td>";
                                }
                                
                                if (!empty($results->reason_id)) {
                                    $test1 .= "<td style='border:1px solid black;'>" . htmlspecialchars($results->reason->name ?? '') . "</td>";
                                } else {
                                    $test1 .= "<td style='border:1px solid black;'>" . htmlspecialchars($results->reason_id ?? '') . "</td>";
                                }

                                $test1 .= "<td style='border:1px solid black;'>" . $results->remark . "</td>";
                                $total_scored  +=  $results->score;
                                $total_scorable += $results->after_audit_weight;
                            }

                            if ($is_critical == 1) {
                                $test1 .= "<td style='border:1px solid black;text-align: center;'>0</td>";
                            } else {
                                $test1 .= "<td style='border:1px solid black;text-align: center;'>" . min($total_scored, $total_scorable) . "</td>";
                            }

                            $test1 .= "<td style='border:1px solid black;text-align: center;'>" . min($total_scorable, 100) . "</td>";

                            $test1 .= "</tr>";
                        } catch (\Throwable $th) {
                            $incom = new IncompleteAudit();
                            $incom->audit_id = $value->id;
                            $incom->save();
                        }
                    }
                    $test1 .= "</tbody></table>";

                    $processName = $processInfo->name;
                    $processName = str_replace(' ', '_', (string)$processInfo->name);
                    $fileName = $processName . "_qm_sheet_" . $qmSheet->version . "_audit_report.xls";
                    $directory = public_path('processWiseFileTATA');
    
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true); // Create the directory if it doesn't exist
                    }
    
                    $file1 = $directory . '/' . $fileName;
    
                    $xls1 = new \HtmlExcel();
                    $xls1->addSheet("raw_dump_audit_report", $test1);
                    $ddddd1 = $xls1->buildFile();
    
                    file_put_contents($file1, $ddddd1);
                    // $processName = $processInfo->name;
                    // $processName = str_replace('', '_', (string) $processName);
                    // $fileName =   $processName . "_audit_report.xls";
                
                }
            }
        } catch (\Exception $th) {
            dd($th->getMessage());
        }
    }
}
