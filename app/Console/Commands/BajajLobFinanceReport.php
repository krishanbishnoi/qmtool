<?php

namespace App\Console\Commands;

use App\Mail\bajajLOBwiseProductivity;
use App\RawData;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BajajLobFinanceReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'LobWise:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a Daily email with LOB Wise report of tool client wise';

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


        $All_LOB = RawData::select('raw_data.lob as lob')
            ->where('client_id', 23)
            ->distinct()
            ->pluck('lob');

        // $startOfMonth = '2023-08-05';
        // $today = '2023-08-10';

        $currentDate = Carbon::now();
        $startOfMonth = $currentDate->copy()->startOfMonth()->subMonth()->addDays(3); // Start from the 4th of the last month
        $today = Carbon::now();


        // Check if the current date is greater than or equal to the 4th of the current month
        if ($today->day >= 4) {
            $startOfMonth = $currentDate->copy()->startOfMonth()->addDays(3);
        }

        $endOfMonth = $currentDate->copy()->startOfMonth()->addDays(3); // End at the 3rd of the current month

        $dateRange = CarbonPeriod::create($startOfMonth, $today);

        //Excel start

        $test = "<table style='border:1px solid black;'>
                    <tr>
             <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> LOB Name</th>";

        foreach ($dateRange as $date) {
            $dateFormat = $date->format('Y-m-d');
            $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>$dateFormat</th>";
            $dateTotals[$dateFormat] = 0;
        }

        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>MTD</th></tr> <tbody>";
        $mtdTotal = 0;
        $total_lob_day_wise = array();

        foreach ($All_LOB as $lob) {

            // if($lob != "NA"){
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
        // }

            if ($lobShouldBeIncluded) {
                // if($lob != "NA"){
                    $test .= "<tr><td style='border:1px solid black;text-align: center;'> $lob</td>";
                // }

                foreach ($dateRange as $date) {
                    $test .= "<td style='border:1px solid black;text-align: center;'>{$lobRow[$date->format('Y-m-d')]}</td>";

                }

                $test .= "<td style='border:1px solid black;text-align: center;'>$totalLob</td></tr>";

                $mtdTotal += $totalLob;

            }

            if (!empty($total_lob_day_wise) && isset($total_lob_day_wise['mtd'])) {
                $total_lob_day_wise['mtd'] += $totalLob;
            } else {
                $total_lob_day_wise['mtd'] = $totalLob;
            }
        }




        $test .= "<tr><td style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>Total</td>";

        foreach ($dateTotals as $total) {
            $test .= "<td style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>$total</td>";
        }

        $test .= "<td style='border:1px solid black;text-align: center;background-color: #FFE4C4;'>$mtdTotal</td></tr>";

        $test .= "</tr></tbody>

        </table>";

        // print_r($test);

        $file = "BPAProductivityLOB.xls";

        require_once('HtmlConvert2021.php');
        $xls = new \HtmlExcel();
        $xls->addSheet("BPAProductivityLOB", $test);
        $ddddd = $xls->buildFile();
        $files = file_put_contents("BPAProductivityLOB.xls", $ddddd);
        $corelPath = 'BPAProductivityLOB.xls';

        $subject = "Daily Audit Count";

        $to_emails = ["Rahul.adiwal@bajajfinserv.in","Sagar.Khajekar@bajajfinserv.in"];
        $cc_emails = ["Manmitraj.Mahajan@bajajfinserv.in","palak.khandelwal@qdegrees.org"];


        Mail::to($to_emails)->cc($cc_emails)->send(new bajajLOBwiseProductivity(
            [

                'data' => $test,
                'subject' => $subject,
                'qa_LOB_report' => $corelPath,

            ]
        ));
    }
}
