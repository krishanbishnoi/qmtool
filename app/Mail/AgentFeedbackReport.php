<?php

namespace App\Console\Commands;

use App\Audit;
use App\Mail\Agentfeedback;
use App\Partner;
use App\RawData;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;

class AgentFeedbackReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Feedback:Report';

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

        // $currentDate = Carbon::now();
        // $startOfMonth = $currentDate->copy()->startOfMonth()->subMonth()->addDays(3); // Start from the 4th of the last month
        // $today = Carbon::now();
        // // Check if the current date is greater than or equal to the 4th of the current month
        // if ($today->day >= 04) {
        //     $startOfMonth = $currentDate->copy()->startOfMonth()->addDays(3);
        // }
        // $endOfMonth = $currentDate->copy()->startOfMonth()->addDays(3); // End at the 3rd of the current month
        // $dateRange = CarbonPeriod::create($startOfMonth, $today);

        $currentDate = Carbon::now();
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $today = Carbon::now();

        // Check if the current date is greater than or equal to the 1st of the current month
        if ($today->day >= 1) {
            $startOfMonth = $currentDate->copy()->startOfMonth();
        } else {
            // If it's a new month, start from the 1st of the last month
            $startOfMonth = $currentDate->copy()->startOfMonth()->subMonth();
        }

        $dateRange = CarbonPeriod::create($startOfMonth, $today);

        // getting excel data
        $data1 = [];
        $data = Audit::whereHas('raw_data', function (Builder $query) {
            $query->where('client_id', 23);
        })
            ->whereDate('audit_date', '>=', $startOfMonth)
            ->whereDate('audit_date', '<=',  $today)
            ->with(['raw_data', 'raw_data.partner_detail', 'raw_data.process'])->get();

        foreach ($data as $key => $value) {
            $basic['data'] = $value->raw_data;
            $basic['audit_date'] = $value->audit_date;
            $basic['partner'] = $value->raw_data->partner_detail->name;
            $basic['process'] = $value->raw_data->process->name;
            $basic['feedback_shared_status'] = $value->feedback_shared_status;
            $basic['feedback_shared_date'] = $value->feedback_shared_date;
            $basic['feedback_remarks'] = $value->feedback_comment;
            $basic['with_fatal_score_per'] = $value->with_fatal_score_per;
            $basic['location'] = $value->location;
            $basic['lob'] = $value->lob;
            $data1[] = $basic;
        }

        $test = "<table style='border:1px solid black;'>
      <tr>
      <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> No.</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Audit Date</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Call ID</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Partner </th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Process</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Location</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> LOB</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>feedback_shared_status</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> feedback_shared_date</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> feedback_remarks</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>  Agent name</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>  transaction score</th></tr> <tbody>";

        $number = 1;
        foreach ($data1 as $value) {
            $auditDate = Carbon::parse($value['audit_date']);
            $feedbacksharedate = Carbon::parse($value['feedback_shared_date']);
            $test .= "<tr>
            <td style='border:1px solid black;text-align: center;'>{$number}</td>";
            $test .= "<td style='border:1px solid black;text-align: center;'>$auditDate</td>";
            $test .= "<td style='border:1px solid black;text-align: center;'>{$value['data']->call_id}</td>";
            $test .= "<td style='border:1px solid black;text-align: center;'>{$value['partner']}</td>";
            $test .= "<td style='border:1px solid black;text-align: center;'>{$value['process']}</td>";
            $test .= "<td style='border:1px solid black;text-align: center;'>{$value['data']->location}</td>";
            $test .= "<td style='border:1px solid black;text-align: center;'>{$value['data']->lob}</td>";
            if ($value['feedback_shared_status'] == 1) {
                $test .= "<td style='border:1px solid black;text-align: center;'>Feedback Shared</td>";
            } else {
                $test .= "<td style='border:1px solid black;text-align: center;'>Feedback Not Shared</td>";
            }
            $test .= "<td style='border:1px solid black;text-align: center;'>$feedbacksharedate</td>";
            $test .= "<td style='border:1px solid black;text-align: center;'>" . (isset($value['feedback_remarks']) ? $value['feedback_remarks'] : '') . "</td>";
            $test .= "<td style='border:1px solid black;text-align: center;'>{$value['data']->agent_name}</td>";
            $test .= "<td style='border:1px solid black;text-align: center;'>{$value['with_fatal_score_per']}</td></tr>";
            $number++;
        }

        $test .= "</tbody></table>";

        $locations = RawData::where('client_id', 23)->distinct()->pluck('location');
        $allData = [];

        foreach ($locations as $locs) {
            $all_lobs = RawData::where('client_id', 23)
                ->where('location', $locs)
                ->distinct()
                ->pluck('lob')
                ->toArray();

            foreach ($all_lobs as $index => $loB) {

                $lobData = [];
                $totalAudits = Audit::where('client_id', 23)
                    ->whereDate('audit_date', '>=', $startOfMonth)
                    ->whereDate('audit_date', '<=', $today)
                    ->whereHas('raw_data', function ($query) use ($locs, $loB) {
                        $query->where('location', $locs)
                            ->where('lob', $loB);
                    })->count();

                $totalfeedbackShared = Audit::where('client_id', 23)
                    ->whereDate('audit_date', '>=', $startOfMonth)
                    ->whereDate('audit_date', '<=', $today)
                    ->where('feedback_shared_status', 1)
                    ->whereHas('raw_data', function ($query) use ($locs, $loB) {
                        $query->where('location', $locs)
                            ->where('lob', $loB);
                    })
                    ->count();
                $totalnotsharedfeedback = Audit::where('client_id', 23)
                    ->whereDate('audit_date', '>=', $startOfMonth)
                    ->whereDate('audit_date', '<=', $today)
                    ->where('feedback_shared_status', '!=', 1)
                    ->whereHas('raw_data', function ($query) use ($locs, $loB) {
                        $query->where('location', $locs)
                            ->where('lob', $loB);
                    })
                    ->count();

                $totalWithinTAT = Audit::where('client_id', 23)
                    ->whereDate('audit_date', '>=', $startOfMonth)
                    ->whereDate('audit_date', '<=', $today)
                    ->where('feedback_shared_status', 1)
                    ->whereHas('raw_data', function ($query) use ($locs, $loB) {
                        $query->where('location', $locs)
                            ->where('lob', $loB)
                            ->whereRaw('TIMESTAMPDIFF(HOUR, audit_date, feedback_shared_date) <= 72');
                    })
                    ->count();

                $allData[$locs][$loB] = [
                    'totalAudits' => $totalAudits,
                    'totalfeedbackShared' => $totalfeedbackShared,
                    'totalnotsharedfeedback' => $totalnotsharedfeedback,
                    'totalWithinTAT' =>  $totalWithinTAT,
                ];
            }
        }


        $test1 = "<table style='border:1px solid black;'>
        <tr>
            <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Location</th>
            <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> LOB</th>
            <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Feedback Shared</th>
            <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Feedback Not shared</th>
            <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Total Audit</th>
            <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Within TAT(72 hours) </th>
            <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Within TAT % </th>
            <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Feedback shared % </th>
        </tr>
        <tbody>";

        $displayedLocations = [];

        $totals = [
            'totalAudits' => 0,
            'totalfeedbackShared' => 0,
            'totalnotsharedfeedback' => 0,
            'totalWithinTAT' => 0,
        ];

        $overall_totals = [
            'OverallAudits' => 0,
            'OverallfeedbackShared' => 0,
            'Overallnotsharedfeedback' => 0,
            'OverallWithinTAT' => 0,
        ];

        foreach ($allData as $location => $locationData) {
            $filteredLocationData = array_filter($locationData, function ($lobData) {
                return $lobData['totalAudits'] > 0;
            });

            if (empty($filteredLocationData)) {
                continue; // Skip location if all LOBs have zero audits
            }

            $locationRowSpan = count($filteredLocationData);
            $totalauditcount = 0;
            foreach ($filteredLocationData as $loB => $lobData) {
                $withinTATPercent = round(($lobData['totalWithinTAT'] / $lobData['totalfeedbackShared']) * 100);
                $feedbacksharedperc = round(($lobData['totalfeedbackShared'] / $lobData['totalAudits']) * 100);

                $test1 .= "<tr>";
                if (!in_array($location, $displayedLocations)) {
                    $test1 .= "<td style='border:1px solid black;vertical-align: middle;' rowspan='$locationRowSpan'>$location</td>";
                    $displayedLocations[] = $location;
                }
                $test1 .= "<td style='border:1px solid black;'>$loB</td>";
                $test1 .= "<td style='border:1px solid black;text-align: center;'>{$lobData['totalfeedbackShared']}</td>";
                $test1 .= "<td style='border:1px solid black;text-align: center;'>{$lobData['totalnotsharedfeedback']}</td>";
                $test1 .= "<td style='border:1px solid black;text-align: center;'>{$lobData['totalAudits']}</td>";
                $test1 .= "<td style='border:1px solid black;text-align: center;'>{$lobData['totalWithinTAT']}</td>";
                $test1 .= "<td style='border:1px solid black;text-align: center;'>{$withinTATPercent}%</td>";
                $test1 .= "<td style='border:1px solid black;text-align: center;'>{$feedbacksharedperc}%</td>";
                $test1 .= "</tr>";

                // Accumulate totals
                $totals['totalAudits'] += $lobData['totalAudits'];
                $totals['totalfeedbackShared'] += $lobData['totalfeedbackShared'];
                $totals['totalnotsharedfeedback'] += $lobData['totalnotsharedfeedback'];
                $totals['totalWithinTAT'] += $lobData['totalWithinTAT'];
            }
            $test1 .= "<tr><td style='border:1px solid black;vertical-align: middle;background-color: #FFFFE0; color: blue;' colspan='2'>{$location}  Total </td>";

            $total_of_withinTAT_percent = round(($totals['totalWithinTAT'] / $totals['totalfeedbackShared']) * 100);
            $total_of_feedback_shared_percent = round(($totals['totalfeedbackShared'] / $totals['totalAudits']) * 100);

            $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #FFFFE0;'>{$totals['totalfeedbackShared']}</td>";
            $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #FFFFE0;'>{$totals['totalnotsharedfeedback']}</td>";
            $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #FFFFE0;'>{$totals['totalAudits']}</td>";
            $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #FFFFE0;'>{$totals['totalWithinTAT']}</td>";
            $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #FFFFE0;'> {$total_of_withinTAT_percent}%</td>";
            $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #FFFFE0;'>{$total_of_feedback_shared_percent}%</td>";
            $test1 .= "</tr>";
            // Accumulate totals
            $overall_totals['OverallAudits'] += $totals['totalAudits'];
            $overall_totals['OverallfeedbackShared'] += $totals['totalfeedbackShared'];
            $overall_totals['Overallnotsharedfeedback'] += $totals['totalnotsharedfeedback'];
            $overall_totals['OverallWithinTAT'] += $totals['totalWithinTAT'];
        }
        $test1 .= "<tr><td style='border:1px solid black;vertical-align: middle;background-color: #1E90FF; color: blue;' colspan='2'>Overall Total </td>";

        $Overall_total_of_withinTAT_percent = round(($overall_totals['OverallWithinTAT'] / $overall_totals['OverallfeedbackShared']) * 100);
        $Overall_total_of_feedback_shared_percent = round(($overall_totals['OverallfeedbackShared'] / $overall_totals['OverallAudits']) * 100);

        $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #1E90FF;'>{$overall_totals['OverallfeedbackShared']}</td>";
        $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #1E90FF;'>{$overall_totals['Overallnotsharedfeedback']}</td>";
        $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #1E90FF;'>{$overall_totals['OverallAudits']}</td>";
        $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #1E90FF;'>{$overall_totals['OverallWithinTAT']}</td>";
        $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #1E90FF;'> {$Overall_total_of_withinTAT_percent}%</td>";
        $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #1E90FF;'>{$Overall_total_of_feedback_shared_percent}%</td>";
        $test1 .= "</tr>";



        $test1 .= "</tbody></table>";


        $file1 = "AgentfeedbackReport1.xls";
        $xls1 = new \HtmlExcel();
        $xls1->addSheet("AgentfeedbackReport", $test);
        $ddddd1 = $xls1->buildFile();
        $file1Content = file_put_contents($file1, $ddddd1);

        // Generate the second Excel file
        $file2 = "AgentfeedbackReport2.xls";
        $xls2 = new \HtmlExcel();
        $xls2->addSheet("AgentfeedbackReport", $test1);
        $ddddd2 = $xls2->buildFile();
        $file2Content = file_put_contents($file2, $ddddd2);

        $attachments = [
            [
                'file' => $file1,
                'options' => [
                    'as' => 'AgentfeedbackReport1.xls',
                ],
            ],
            [
                'file' => $file2,
                'options' => [
                    'as' => 'AgentfeedbackReport2.xls',
                ],
            ],
        ];
        $subject = " Monthly Feedback Report";

        $to_emails = ["Rahul.adiwal@bajajfinserv.in", "Sagar.Khajekar@bajajfinserv.in"];
        $cc_emails = ["Manmitraj.Mahajan@bajajfinserv.in", "divy.bhatnagar@qdegrees.com","palak.khandelwal@qdegrees.org"];
        Mail::to($to_emails)->cc($cc_emails)->send(new Agentfeedback($subject, $attachments));
    }
}
