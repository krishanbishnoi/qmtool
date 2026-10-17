<?php

namespace App\Console\Commands;

use App\Audit;
use Illuminate\Support\Facades\DB;
use App\Mail\CombinedReports;
use App\RawData;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateInterval;
use DatePeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CombineBFLReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BFL:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a Daily email with productivity report of tool client wise';

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


        // $startOfMonth = date('Y-m-01');
        // $today =date('Y-m-d');
        // $startOfMonth = '2023-08-12 ';
        // $today = '2023-08-25 ';
        // $dateRange = CarbonPeriod::create($startOfMonth, $today);

        // date range implementation (/month/04-month/03)
        // $currentDate = now();
        // $startOfMonth = $currentDate->copy()->startOfMonth()->addDays(3); // Start from the 4th of the current month
        // $endOfMonth = $currentDate->copy()->addMonthNoOverflow()->startOfMonth()->addDays(3); // End at the 3rd of the next month
        // $today = now();

        // if ($today->lt($endOfMonth)) {
        //     $endOfMonth = $today->copy();
        // }

            //date range

        // $currentDate = Carbon::now();
        // $startOfMonth = $currentDate->copy()->startOfMonth()->subMonth()->addDays(3); // Start from the 4th of the last month
        // $today = Carbon::now();


        // // Check if the current date is greater than or equal to the 4th of the current month
        // if ($today->day >= 4) {
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



        $auditors = Audit::where('client_id', 23)
        ->join('users', 'audits.audited_by_id', '=', 'users.id')
        ->select('audits.audited_by_id', 'users.name')
        ->distinct()
        ->get();

     // dd($auditors);

        $auditData = [];

        foreach ($auditors as $auditor) {
            $auditorData = [
                'Auditor' => $auditor->name,
            ];

            foreach ($dateRange as $date) {
                $count = DB::table('audits')
                    ->where('audited_by_id', $auditor->audited_by_id)
                    ->whereDate('audit_date', $date)
                    ->count();

                 $auditorData[$date->format('Y-m-d')] = $count;
            }

            $auditData[] = $auditorData;
        }
        // dd($auditData);

        /**
         *  Excel start for Auditor wise audit report
         */

        $test1 = "<table style='border:1px solid black;'>

        <tr>
            <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> BPA Name</th>";

        $dateTotals = [];
        foreach ($dateRange as $date) {
            $dateFormat = $date->format('Y-m-d');
            $test1 .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>$dateFormat</th>";
            $dateTotals[$dateFormat] = 0; // Initialize date totals to 0
        }

        $test1 .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>MTD</th></tr> <tbody>";

        $mtdTotal = 0;

        foreach ($auditors as $auditor) {
            $auditorData = [
                'Auditor' => $auditor->name,
            ];

            $totalAuditor = 0;
            $auditorShouldBeIncluded = false;

            foreach ($dateRange as $date) {
                $count = DB::table('audits')
                    ->where('audited_by_id', $auditor->audited_by_id)
                    ->whereDate('audit_date', $date)
                    ->count();

                $totalAuditor += $count;
                $auditorData[$date->format('Y-m-d')] = $count;

                // Increment date-wise totals
                $dateTotals[$date->format('Y-m-d')] += $count;

                if ($count > 0) {
                    $auditorShouldBeIncluded = true;
                }
            }

            if ($auditorShouldBeIncluded) {
                $test1 .= "<tr>
                    <td style='border:1px solid black;text-align: center;'>{$auditor->name}</td>";

                foreach ($dateRange as $date) {
                    $test1 .= "<td style='border:1px solid black;text-align: center;'>{$auditorData[$date->format('Y-m-d')]}</td>";
                }

                $test1 .= "<td style='border:1px solid black;text-align: center;'>$totalAuditor</td></tr>";
            }

            // Increment MTD total
            $mtdTotal += $totalAuditor;
        }

        $test1 .= "<tr><td style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Total</td>";

        foreach ($dateTotals as $total) {
            $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>$total</td>";
        }

        $test1 .= "<td style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>$mtdTotal</td></tr>";

        $test1 .= "</tbody></table>";


    // second cron Report for LOB wise

        $All_LOB = RawData::select('raw_data.lob as lob')
            ->where('client_id', 23)
            ->distinct()
            ->pluck('lob');

        //Excel start for lob wise bfl

        $test2 = "<table style='border:1px solid black;'>
                <tr>
         <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> LOB Name</th>";

        foreach ($dateRange as $date) {
            $dateFormat = $date->format('Y-m-d');
            $test2 .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>$dateFormat</th>";
            $dateTotals[$dateFormat] = 0;
        }

        $test2 .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>MTD</th></tr> <tbody>";
        $mtdTotal = 0;
        $total_lob_day_wise = array();

        foreach ($All_LOB as $lob) {

            $lobRow = [
                'LOB' =>  $lob
            ];
            // print_r($lobRow);


            $totalLob = 0;
            $lobShouldBeIncluded = false;

            foreach ($dateRange as $date) {
                $count = DB::table('raw_data')
                    ->join('audits', 'audits.raw_data_id', '=', 'raw_data.id')
                    ->where('raw_data.lob', $lob)
                    ->whereDate('audits.audit_date', $date)
                    ->where('audits.client_id', 23)
                    ->count();

                $totalLob += $count;
                $lobRow[$date->format('Y-m-d')] = $count;

                // Incremented  date-wise totals
                $dateTotals[$date->format('Y-m-d')] += $count;

                if ($count > 0) {
                    $lobShouldBeIncluded = true;
                }
            }

            if ($lobShouldBeIncluded) {
                $test2 .= "<tr><td style='border:1px solid black;text-align: center;'> $lob</td>";

                foreach ($dateRange as $date) {
                    $test2 .= "<td style='border:1px solid black;text-align: center;'>{$lobRow[$date->format('Y-m-d')]}</td>";
                }
                $test2 .= "<td style='border:1px solid black;text-align: center;'>$totalLob</td></tr>";
                $mtdTotal += $totalLob;
            }
            if (!empty($total_lob_day_wise) && isset($total_lob_day_wise['mtd'])) {
                $total_lob_day_wise['mtd'] += $totalLob;
            } else {
                $total_lob_day_wise['mtd'] = $totalLob;
            }
        }

        $test2 .= "<tr><td style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Total</td>";

        foreach ($dateTotals as $total) {
            $test2 .= "<td style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>$total</td>";
        }

        $test2 .= "<td style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>$mtdTotal</td></tr>";

        $test2 .= "</tr></tbody>

         </table>";


        // Third cron for lob wise auditor audits report

        $all_auditors = RawData::where('client_id', 23)
            ->join('users', 'raw_data.qa_id', '=', 'users.id')
            ->select('raw_data.qa_id', 'users.name')
            ->distinct()
            ->pluck('name', 'qa_id');

        $all_data = [];

        foreach ($all_auditors as $qa_id => $qaName) {

            $all_lob = RawData::where('qa_id', $qa_id)
                ->where('client_id', 23)
                ->distinct()
                ->pluck('lob')
                ->toArray();

            $lob_data = [];

            $auditorHasAudits = false;

            foreach ($all_lob as $lob) {
                $lob_counts = [];

                $lobHasAudits = false;

                foreach ($dateRange as $date) {
                    $count = DB::table('raw_data')
                        ->join('audits', 'audits.raw_data_id', '=', 'raw_data.id')
                        ->where('qa_id', $qa_id)
                        ->where('raw_data.lob', $lob)
                        ->whereDate('audits.audit_date', $date)
                        ->count();

                    $lob_counts[$date->format('Y-m-d')] = $count;

                    if ($count > 0) {
                        $lobHasAudits = true;
                        $auditorHasAudits = true;
                    }
                }

                if ($lobHasAudits) {
                    $lobData = [
                        'lob' => $lob,
                        'date_counts' => $lob_counts,
                    ];
                    $lob_data[] = $lobData;
                }
            }
            if ($auditorHasAudits) {
                $auditorData = [
                    'name' => $qaName,
                    'lob_data' => $lob_data,
                ];
                $all_data[] = $auditorData;
            }
        }
        // print_r($all_data);
        // die;
        // Excel format start


        $test3 = "<table style='border:1px solid black;'>
<tr>
<th style='border:1px solid black;text-align: center; background-color: #FFE4C4;'> BPA Name</th>
<th style='border:1px solid black;text-align: center; background-color: #FFE4C4;'> LOB Name</th>";

        foreach ($dateRange as $date) {
            $dateFormat = $date->format('d-M-y');
            $test3 .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>$dateFormat</th>";
        }
        $total_audits_day_wise = array();
        $test3 .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>MTD</th>";
        $test3 .= "</tr>";

        // Loop through auditors and their data
        foreach ($all_data as $auditorData) {
            $qaName = $auditorData['name'];
            $lobData = $auditorData['lob_data'];
            $auditor_lobWise_count = array();

            foreach ($lobData as $lobInfo) {
                $lob = $lobInfo['lob'];
                $dateCounts = $lobInfo['date_counts'];

                $lobCount = count($lobData);
                $test3 .= "<tr>";

                if ($lob === $lobData[0]['lob']) {
                    $test3 .= "<td style='border:1px solid black;vertical-align: middle;' rowspan='$lobCount'>$qaName</td>";
                }

                $test3 .= "<td style='border:1px solid black;'>$lob</td>";
                $totalAudits = 0;

                foreach ($dateRange as $date) {
                    $dateFormat = $date->format('Y-m-d');
                    $count = isset($dateCounts[$dateFormat]) ? $dateCounts[$dateFormat] : 0;
                    $totalAudits += $count;

                    $test3 .= "<td style='border:1px solid black;text-align: center;'>$count</td>";

                    //  total audits per day wise for all auditors
                    if (!empty($total_audits_day_wise) && isset($total_audits_day_wise[$date->format('Y-m-d')])) {
                        $total_audits_day_wise[$date->format('Y-m-d')] += $count;
                    } else {
                        $total_audits_day_wise[$date->format('Y-m-d')] = $count;
                    }

                    // total audits for auditor wise
                    if (!empty($auditor_lobWise_count) && isset($auditor_lobWise_count[$date->format('Y-m-d')])) {
                        $auditor_lobWise_count[$date->format('Y-m-d')] += $count;
                    } else {
                        $auditor_lobWise_count[$date->format('Y-m-d')] = $count;
                    }
                }

                // total mtd for all audiors
                if (!empty($total_audits_day_wise) &&  isset($total_audits_day_wise['mtd'])) {
                    $total_audits_day_wise['mtd'] += $totalAudits;
                } else {
                    $total_audits_day_wise['mtd'] = $totalAudits;
                }
                $test3 .= "<td style='border:1px solid black;text-align: center;'>$totalAudits</td></tr>";

                //total mtd for per auditor wise
                if (!empty($auditor_lobWise_count) && isset($auditor_lobWise_count[$date->format('mtd')])) {
                    $auditor_lobWise_count[$date->format('mtd')] += $totalAudits;
                } else {
                    $auditor_lobWise_count[$date->format('mtd')] = $totalAudits;
                }
            }

            // Total for each auditor
            $test3 .= "<tr><td style='border:1px solid black;vertical-align: middle;background-color: #d4bf79; color: blue;' colspan='2'>$qaName Total</td>";


            $auditorTotal = 0;


           
            foreach ($dateRange as $date) {
                $dateFormat = $date->format('Y-m-d');
                if(!isset($auditor_lobWise_count)){
                    $auditor_lobWise_count[$dateFormat]=0;
                }

                $test3 .= "<td style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>{$auditor_lobWise_count[$dateFormat]}</td>";

                // if($auditor_lobWise_count[$dateFormat] <= 30){

                //     $test3 .= "<td style='border:1px solid black;text-align: center;background-color: #FFE4C4; color:red;'>{$auditor_lobWise_count[$dateFormat]}</td>";
                // }else{
                // }
            }

            $test3 .= "<td style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>$auditorTotal</td></tr>";
        }

        // Total row
        $test3 .= "<tr><td style='border:1px solid black;text-align: center;' colspan='2'>Total</td>";

        $totalMTD = 0;

        // foreach ($dateRange as $date) {
        //     $dateFormat = $date->format('Y-m-d');
        //     $totalMTD += $total_audits_day_wise[$dateFormat];
        //     $test3 .= "<td style='border:1px solid black;text-align: center;'>{$total_audits_day_wise[$dateFormat]}</td>";
        // }
        foreach ($dateRange as $date) {
            $dateFormat = $date->format('Y-m-d');

            if (!isset($total_audits_day_wise[$dateFormat])) {
                $total_audits_day_wise[$dateFormat] = 0;
            }

            // Add the cell to your table
            $test3 .= "<td style='border:1px solid black;text-align: center;'>{$total_audits_day_wise[$dateFormat]}</td>";
        }

        $test3 .= "<td style='border:1px solid black;text-align: center;'>$totalMTD</td></tr>";

        $test3 .= "</table>";


        // Generate the first Excel file
        $file1 = "BPAProductivity.xls";
        $xls1 = new \HtmlExcel();
        $xls1->addSheet("BPAProductivity", $test1);
        $ddddd1 = $xls1->buildFile();
        $file1Content = file_put_contents($file1, $ddddd1);

        // Generate the second Excel file
        $file2 = "BPAProductivityLOB.xls";
        $xls2 = new \HtmlExcel();
        $xls2->addSheet("BPAProductivityLOB", $test2);
        $ddddd2 = $xls2->buildFile();
        $file2Content = file_put_contents($file2, $ddddd2);

        // Generate the third Excel file
        $file3 = "BPALobWiseProductivity.xls";
        $xls3 = new \HtmlExcel();
        $xls3->addSheet("BPALobWiseProductivity", $test3);
        $ddddd3 = $xls3->buildFile();
        $file3Content = file_put_contents($file3, $ddddd3);

        $attachments = [
            [
                'file' => $file1,
                'options' => [
                    'as' => 'BPAProductivity.xls',
                ],
            ],
            [
                'file' => $file2,
                'options' => [
                    'as' => 'BPAProductivityLOB.xls',
                ],
            ],
            [
                'file' => $file3,
                'options' => [
                    'as' => 'BPALobWiseProductivity.xls',
                ],
            ],
        ];


        $subject = "Auditor wise Daily Productivity";

        // $to_emails = ["palak.khandelwal@qdegrees.org"];
        // $cc_emails = ["palak.khandelwal@qdegrees.org"];

        $to_emails = ["Rahul.adiwal@bajajfinserv.in","Sagar.Khajekar@bajajfinserv.in"];
        $cc_emails = ["Manmitraj.Mahajan@bajajfinserv.in","divy.bhatnagar@qdegrees.com","shubhda.sharma@qdegrees.com","Jasim.Ali@qdegrees.com","palak.khandelwal@qdegrees.org","kiran.ingulkar@qdegrees.org","Sumith.Kalaiselvan@qdegrees.com","NISHA.KRISHNA@qdegrees.com","SME.BFL@QDegrees.org","quality.bfl.pune@qdegrees.com","Ranjit.Chandran@qdegrees.com"];

        // $to_emails = ["palak.khandelwal@qdegrees.org"];
        // $cc_emails = ["palak.khandelwal@qdegrees.org"];
        Mail::to($to_emails)->cc($cc_emails)->send(new CombinedReports($subject, $attachments));


    }
}
