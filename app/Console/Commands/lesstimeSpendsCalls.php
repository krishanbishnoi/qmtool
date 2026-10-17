<?php

namespace App\Console\Commands;

use App\Mail\lesstimecalls;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class lesstimeSpendsCalls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lesstime:calls';

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

        $date = Carbon::now();

        $incomplete_audit =
        DB::select("SELECT audits.id, audits.audit_date , audits.audited_by_id, c.name AS Client , audits.auditor_time_spend_in_secs, p.name AS processName, r.call_id AS callId,
         u.name, TIME_TO_SEC(r.call_duration) AS call_duration_in_seconds FROM audits INNER JOIN raw_data AS r ON audits.raw_data_id = r.id
         INNER JOIN users AS u ON audits.audited_by_id = u.id INNER JOIN processes AS p ON audits.process_id = p.id INNER JOIN clients AS c ON audits.client_id = c.id
         WHERE DATE(audits.audit_date) =  CURDATE()
         HAVING audits.auditor_time_spend_in_secs < call_duration_in_seconds AND auditor_time_spend_in_secs != 0 ");


        // BETWEEN DATE_FORMAT(NOW() ,'%Y-%m-01') AND NOW()

        // dd($incomplete_audit);

        $number = 1;

        $test = "<table style='border:1px solid black;'>
         <tr>
         <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> No.</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Audit Date</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Call ID</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> QA NAme </th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> auditor Time Spend in secs</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Call Duration In Seconds</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Difference</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Process Name</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Client</th>";
        $test .= "</tr>";

        foreach ($incomplete_audit as $value) {

            $diff =  $value->call_duration_in_seconds - $value->auditor_time_spend_in_secs ;

            $test .= "<tr>";
            $test .= "<td style='border:1px solid black;'>$number</td>";
            $test .= "<td style='border:1px solid black;'>$value->audit_date</td>";
            $test .= "<td style='border:1px solid black;'>$value->callId</td>";
            $test .= "<td style='border:1px solid black;'>$value->name</td>";
            $test .= "<td style='border:1px solid black;'>$value->auditor_time_spend_in_secs</td>";
            $test .= "<td style='border:1px solid black;'>$value->call_duration_in_seconds</td>";
            $test .= "<td style='border:1px solid black;'> $diff</td>";
            $test .= "<td style='border:1px solid black;'>$value->processName</td>";
            $test .= "<td style='border:1px solid black;'>$value->Client</td>";

            $number++;
        }


        $test .= "</tbody></table>";

        // send excel

        $file = "LessTimeCalls.xls";

        require_once('HtmlConvert2021.php');
        $xls = new \HtmlExcel();
        $xls->addSheet("LessTimeCalls", $test);
        $ddddd = $xls->buildFile();
        $files = file_put_contents("LessTimeCalls.xls", $ddddd);
        $corelPath = 'LessTimeCalls.xls';

        $subject = "QA's call time summary";

        // $to_emails = ["sb@qdegrees.com","abhinav.agarwal@qdegrees.com","amit.kumar3@qdegrees.com","atul.singh@qdegrees.com","gurupej.singh@qdegrees.com","jasim.ali@qdegrees.com","ranjit.chandran@qdegrees.com","ruchika.nath@qdegrees.com"];
        // $cc_emails = ["sb@qdegrees.com","palak.khandelwal@qdegrees.org","akhil.kumawat@qdegrees.com","govind.meena@qdegrees.org","ravi.verma@qdegrees.com","shubhda.sharma@qdegrees.com"];


        Mail::to($to_emails)->cc($cc_emails)->send(new lesstimecalls(
            [

                'data' => $test,
                'subject' => $subject,
                'QA_report' => $corelPath,

            ]
        ));
    }
}
