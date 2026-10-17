<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Audit;
use App\Mail\Agentfeedback2;
use App\Partner;
use App\RawData;
use App\User;

use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;

class AgentfeedbackOverallLastmonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agentfeedback:report2';

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
         $startOfMonth = Carbon::now()->startOfMonth()->subMonth()->toDateString();
        $today = now()->subMonth()->endOfMonth()->toDateString();

         $locations = RawData::where('client_id', 23)->where('status',1)
        ->whereNotIn('partner_id' , [113,114] )->where('process_id', '!=' ,88)->distinct()->pluck('location');

        $allData = [];

        foreach ($locations as $locs) {

            $all_lobs = RawData::where('client_id', 23)
            ->where('location', $locs)
            ->where('process_id', '!=' ,88)
            ->whereNotIn('partner_id' , [113,114] )
            ->whereNotIn('process_id' ,[115,116,117])
            ->where('status', 1)
            ->whereHas('audit',function($query) use ($startOfMonth , $today){
                $query->whereDate('audit_date', '>=', $startOfMonth)
                ->whereDate('audit_date', '<=', $today);
            })
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
                        ->where('lob', $loB)
                        ->where('process_id', '!=' ,88)
                        ->whereNotIn('partner_id' , [113,114] )
                        ->whereNotIn('process_id' ,[115,116,117]);
                    })->count();

                $totalfeedbackShared = Audit::where('client_id', 23)
                    ->whereDate('audit_date', '>=', $startOfMonth)
                    ->whereDate('audit_date', '<=', $today)
                    ->where('feedback_shared_status', 1)
                    ->whereHas('raw_data', function ($query) use ($locs, $loB) {
                        $query->where('location', $locs)
                        ->where('lob', $loB)
                        ->where('process_id', '!=' ,88)
                        ->whereNotIn('process_id' ,[115,116,117])
                        ->whereNotIn('partner_id' , [113,114] );
                    })
                    ->count();
                $totalnotsharedfeedback = Audit::where('client_id', 23)
                    ->whereDate('audit_date', '>=', $startOfMonth)
                    ->whereDate('audit_date', '<=', $today)
                    ->where('feedback_shared_status', '!=', 1)
                    ->whereHas('raw_data', function ($query) use ($locs, $loB) {
                        $query->where('location', $locs)
                            ->where('lob', $loB)
                            ->where('process_id', '!=' ,88)
                            ->whereNotIn('process_id' ,[115,116,117])
                            ->whereNotIn('partner_id' , [113,114] );
                    })
                    ->count();

                $totalWithinTAT = Audit::where('client_id', 23)
                    ->whereDate('audit_date', '>=', $startOfMonth)
                    ->whereDate('audit_date', '<=', $today)
                    ->where('feedback_shared_status', 1)
                    ->whereHas('raw_data', function ($query) use ($locs, $loB) {
                        $query->where('location', $locs)
                            ->where('lob', $loB)
                            ->where('process_id', '!=' ,88)
                            ->whereNotIn('process_id' ,[115,116,117])
                            ->whereNotIn('partner_id' , [113,114] )
                            ->where(function ($query) {
                                $query->whereRaw('TIMESTAMPDIFF(HOUR,
                        CASE
                            WHEN DAYOFWEEK(audit_date) = 6 THEN DATE_ADD(audit_date, INTERVAL 3 DAY)
                            WHEN DAYOFWEEK(audit_date) = 7 THEN DATE_ADD(audit_date, INTERVAL 2 DAY)
                            ELSE DATE_ADD(audit_date, INTERVAL 1 DAY)  -- All other days: add 1 day
                        END,
                        feedback_shared_date
                    ) <= 72')
                         ->orWhereRaw('TIMESTAMPDIFF(HOUR,
                        CASE
                            WHEN DAYOFWEEK(audit_date) = 6 THEN DATE_ADD(audit_date, INTERVAL 3 DAY)
                            WHEN DAYOFWEEK(audit_date) = 7 THEN DATE_ADD(audit_date, INTERVAL 2 DAY)
                            ELSE DATE_ADD(audit_date, INTERVAL 1 DAY)  -- All other days: add 1 day
                        END,
                        rebuttal_tat
                    ) > 48 AND rebuttal_tat >= feedback_shared_date');
                            });
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

            // Reset totals for each location
            $totals = [
                'totalAudits' => 0,
                'totalfeedbackShared' => 0,
                'totalnotsharedfeedback' => 0,
                'totalWithinTAT' => 0,
            ];
            foreach ($filteredLocationData as $loB => $lobData) {
                $withinTATPercent = ($lobData['totalfeedbackShared'] >0)?round(($lobData['totalWithinTAT'] / $lobData['totalfeedbackShared']) * 100) : 0;
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


            $total_of_withinTAT_percent =  ($totals['totalfeedbackShared'] > 0) ? round(($totals['totalWithinTAT'] / $totals['totalfeedbackShared']) * 100):0;
            $total_of_feedback_shared_percent = round(($totals['totalfeedbackShared'] / $totals['totalAudits']) * 100);

            $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #FFFFE0;'>{$totals['totalfeedbackShared']}</td>";
            $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #FFFFE0;'>{$totals['totalnotsharedfeedback']}</td>";
            $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #FFFFE0;'>{$totals['totalAudits']}</td>";
            $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #FFFFE0;'>{$totals['totalWithinTAT']}</td>";
            $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #FFFFE0;'> {$total_of_withinTAT_percent}%</td>";
            $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #FFFFE0;'>{$total_of_feedback_shared_percent}%</td>";
            $test1 .= "</tr>";
            // Accumulate totals`
            $overall_totals['OverallAudits'] += $totals['totalAudits'];
            $overall_totals['OverallfeedbackShared'] += $totals['totalfeedbackShared'];
            $overall_totals['Overallnotsharedfeedback'] += $totals['totalnotsharedfeedback'];
            $overall_totals['OverallWithinTAT'] += $totals['totalWithinTAT'];
        }
        $test1 .= "<tr><td style='border:1px solid black;vertical-align: middle;background-color: #1E90FF; color: blue;' colspan='2'>Overall Total </td>";

        $Overall_total_of_withinTAT_percent = ($overall_totals['OverallfeedbackShared'] > 0 ) ? round(($overall_totals['OverallWithinTAT'] / $overall_totals['OverallfeedbackShared']) * 100) : 0;
        $Overall_total_of_feedback_shared_percent = round(($overall_totals['OverallfeedbackShared'] / $overall_totals['OverallAudits']) * 100);

        $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #1E90FF;'>{$overall_totals['OverallfeedbackShared']}</td>";
        $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #1E90FF;'>{$overall_totals['Overallnotsharedfeedback']}</td>";
        $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #1E90FF;'>{$overall_totals['OverallAudits']}</td>";
        $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #1E90FF;'>{$overall_totals['OverallWithinTAT']}</td>";
        $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #1E90FF;'> {$Overall_total_of_withinTAT_percent}%</td>";
        $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #1E90FF;'>{$Overall_total_of_feedback_shared_percent}%</td>";
        $test1 .= "</tr>";

        $test1 .= "</tbody></table>";

            $file2 = public_path("AgentfRLastMonth2.xls");
        // Generate the second Excel file
        $xls2 = new \HtmlExcel();
        $xls2->addSheet("AgentfeedbackReportLastMonth2", $test1);
        $ddddd2 = $xls2->buildFile();
        file_put_contents($file2, $ddddd2);
    }
}
