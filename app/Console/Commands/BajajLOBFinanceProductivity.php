<?php

namespace App\Console\Commands;
use App\Mail\bajajLobwiseFinanceProductivity;
use App\RawData;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BajajLOBFinanceProductivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lobFinance:report';

    /**
     * The console command description.
     * @var string
     */

    protected $description = 'LOb-financeProductivity report';

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

        $all_auditors = RawData::where('client_id', 23)
            ->join('users', 'raw_data.qa_id', '=', 'users.id')
            ->select('raw_data.qa_id', 'users.name')
            ->distinct()
            ->pluck('name', 'qa_id');

        // $startOfMonth = '2023-08-05';
        // $today = '2023-08-10';


        // $currentDate = now();
        // $startOfMonth = $currentDate->copy()->startOfMonth()->addDays(3); // Start from the 4th of the current month
        // $endOfMonth = $currentDate->copy()->addMonthNoOverflow()->startOfMonth()->addDays(3); // End at the 3rd of the next month
        // $today = now();
        // $dateRange = CarbonPeriod::create($startOfMonth, $today);

        // // Adjust the end date if today is before the calculated end of month date
        // if ($today->lt($endOfMonth)) {
        //     $endOfMonth = $today->copy();
        // }

        $currentDate = Carbon::now();
        $startOfMonth = $currentDate->copy()->startOfMonth()->subMonth()->addDays(3); // Start from the 4th of the last month
        $today = Carbon::now();


        // Check if the current date is greater than or equal to the 4th of the current month
        if ($today->day >= 4) {
            $startOfMonth = $currentDate->copy()->startOfMonth()->addDays(3);
        }

        $endOfMonth = $currentDate->copy()->startOfMonth()->addDays(3); // End at the 3rd of the current month

        $dateRange = CarbonPeriod::create($startOfMonth, $today);


       

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
                        ->where('audits.client_id', 23)
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




$test = "<table style='border:1px solid black;'>
<tr>
<th style='border:1px solid black;text-align: center; background-color: #FFE4C4;'> BPA Name</th>
<th style='border:1px solid black;text-align: center; background-color: #FFE4C4;'> LOB Name</th>";

foreach ($dateRange as $date) {
    $dateFormat = $date->format('d-M-y');
    $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>$dateFormat</th>";
}
$total_audits_day_wise = array();
$test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>MTD</th>";
$test .= "</tr>";

// Loop through auditors and their data
foreach ($all_data as $auditorData) {
    $qaName = $auditorData['name'];
    $lobData = $auditorData['lob_data'];
    $auditor_lobWise_count = array();

    foreach ($lobData as $lobInfo) {
        $lob = $lobInfo['lob'];
        $dateCounts = $lobInfo['date_counts'];

        $lobCount = count($lobData);
        $test .= "<tr>";

        if ($lob === $lobData[0]['lob']) {
            $test .= "<td style='border:1px solid black;vertical-align: middle;' rowspan='$lobCount'>$qaName</td>";
        }

        $test .= "<td style='border:1px solid black;'>$lob</td>";
        $totalAudits = 0;

        foreach ($dateRange as $date) {
            $dateFormat = $date->format('Y-m-d');
            $count = isset($dateCounts[$dateFormat]) ? $dateCounts[$dateFormat] : 0;
            $totalAudits += $count;

            $test .= "<td style='border:1px solid black;text-align: center;'>$count</td>";

              //  total audits per day wise for all auditors
              if (!empty($total_audits_day_wise) && isset($total_audits_day_wise[$date->format('Y-m-d')]) ) {
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
        $test .= "<td style='border:1px solid black;text-align: center;'>$totalAudits</td></tr>";

        //total mtd for per auditor wise
        if (!empty($auditor_lobWise_count) && isset($auditor_lobWise_count[$date->format('mtd')])) {
            $auditor_lobWise_count[$date->format('mtd')] += $totalAudits;
        } else {
            $auditor_lobWise_count[$date->format('mtd')] = $totalAudits;
        }


    }

    // Total for each auditor
    $test .= "<tr><td style='border:1px solid black;vertical-align: middle;background-color: #d4bf79; color: blue;' colspan='2'>$qaName Total</td>";


    $auditorTotal = 0;


    foreach ($dateRange as $date) {
        $dateFormat = $date->format('Y-m-d');
        $auditorTotal += $auditor_lobWise_count[$dateFormat];
        $test .= "<td style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>{$auditor_lobWise_count[$dateFormat]}</td>";

        // if($auditor_lobWise_count[$dateFormat] <= 30){

        //     $test .= "<td style='border:1px solid black;text-align: center;background-color: #FFE4C4; color:red;'>{$auditor_lobWise_count[$dateFormat]}</td>";
        // }else{
        // }
    }

    $test .= "<td style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>$auditorTotal</td></tr>";
}

// Total row
$test .= "<tr><td style='border:1px solid black;text-align: center;' colspan='2'>Total</td>";

 $totalMTD = 0;

foreach ($dateRange as $date) {
    $dateFormat = $date->format('Y-m-d');
    $totalMTD += $total_audits_day_wise[$dateFormat];
    $test .= "<td style='border:1px solid black;text-align: center;'>{$total_audits_day_wise[$dateFormat]}</td>";
}

$test .= "<td style='border:1px solid black;text-align: center;'>$totalMTD</td></tr>";

$test .= "</table>";



        // send excel

        $file = "BPALobWiseProductivity.xls";

        require_once('HtmlConvert2021.php');
        $xls = new \HtmlExcel();
        $xls->addSheet("BPALobWiseProductivity", $test);
        $ddddd = $xls->buildFile();
        $files = file_put_contents("BPALobWiseProductivity.xls", $ddddd);
        $corelPath = 'BPALobWiseProductivity.xls';

        $subject = " LOB wise daily audit Productivity";

        $to_emails = ["Rahul.adiwal@bajajfinserv.in","Sagar.Khajekar@bajajfinserv.in"];
        $cc_emails = ["Manmitraj.Mahajan@bajajfinserv.in","palak.khandelwal@qdegrees.org"];



        Mail::to($to_emails)->cc($cc_emails)->send(new bajajLobwiseFinanceProductivity(
            [

                'data' => $test,
                'subject' => $subject,
                'BPA_report' => $corelPath,

            ]
        ));
    }
}

