<?php

namespace App\Console\Commands;

use App\Audit;
use App\Mail\HourlyReportData;
use App\Partner;
use App\PartnersProcess;
use App\Process;
use App\QaTarget;
use App\RawData;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;

class HourlyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'HourlyReport:Report';

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
    public function handle() {
        $toDate = date("Y-m-d");
        $data = [];
        $userInfo = User::where('parent_client', 23)->where('status', 1)->whereHas('roles', function($query) {
            $query->where('name', 'qa');})->pluck('id')->toArray();
        $partners = Partner::where('client_id', 23)->pluck('id')->toArray();
        $process = PartnersProcess::whereIn('partner_id', $partners)->pluck('process_id')->toArray();
        $auditData = Audit::whereIn('process_id', $process)->whereDate('audit_date',$toDate)->get();
        $auditIds = $auditData->pluck('id')->toArray();

        $total = 0;
        // $total_audits = 0;
        foreach ($userInfo as $key => $auditorsId) {
 
            $qaTarget = QaTarget::where('qa_id', $auditorsId)->orderby('id','desc')->pluck('qa_target')->first() ?? 40;
        
            $user = User::find($auditorsId);
            $auditCount = Audit::whereIn('id', $auditIds)->where('audited_by_id', $auditorsId)->count();
        
            $data[$key]['agentName'] = $user->name;
            $data[$key]['qdId'] = $user->qd_id;
            $process = json_decode($user->assigned_process_ids);
            $data[$key]['process'] = $process;
            $data[$key]['auditCount'] = $auditCount;
            $data[$key]['qaTargets'] = $qaTarget; 
        }
 
 
 
        $test1 = "<table style='border:1px solid black; border-collapse: collapse; width: 100%;'>
        <tr>
            <th style='border:1px solid black; text-align: center; background-color: #FFE4C4;'>Auditor Name</th>
            <th style='border:1px solid black; text-align: center; background-color: #FFE4C4;'>QDId</th>
            <th style='border:1px solid black; text-align: center; background-color: #FFE4C4;'>Target</th>
            <th style='border:1px solid black; text-align: center; background-color: #FFE4C4;'>Process</th>
            <th style='border:1px solid black; text-align: center; background-color: #FFE4C4;'>Achieve</th>
            <th style='border:1px solid black; text-align: center; background-color: #FFE4C4;'>Achieve %</th>
        </tr>";

    foreach ($data as $value) {
        $test1 .= "<tr>";
        $test1 .= "<td style='border:1px solid black; text-align: center;'>{$value['agentName']}</td>";
        $test1 .= "<td style='border:1px solid black; text-align: center;'>{$value['qdId']}</td>";
        $test1 .= "<td style='border:1px solid black; text-align: center;'>{$value['qaTargets']}</td>";

        // Fetch first process name
        $firstProcessName = 'NA';
        if (!empty($value['process'])) {
            $firstProcessId = $value['process'][0];
            $process = Process::find($firstProcessId);
            if ($process) {
                $firstProcessName = $process->name;
            }
        }
        $test1 .= "<td style='border:1px solid black; text-align: center;'>{$firstProcessName}</td>";

        // Achieve
        $test1 .= "<td style='border:1px solid black; text-align: center;'>{$value['auditCount']}</td>";

        // Calculate Achieve %
        $achievePercent = $value['qaTargets'] > 0 ? round(($value['auditCount'] / $value['qaTargets']) * 100, 2) : 0;
        $test1 .= "<td style='border:1px solid black; text-align: center;'>{$achievePercent}%</td>";

        $test1 .= "</tr>";

        // Update total
        $total += $value['auditCount'];
    }

    // Add total row to Test1
    $test1 .= "<tr>
        <td style='border:1px solid black; text-align: center; background-color: #FFE4C4;' colspan='4'><strong>Total Audits</strong></td>
        <td style='border:1px solid black; text-align: center; background-color: #FFE4C4;'><strong>{$total}</strong></td>
        <td style='border:1px solid black; text-align: center; background-color: #FFE4C4;'><strong>-</strong></td>
    </tr>";
    $test1 .= "</table>";

      

        //-----------------------------------------------

        
        $userInfo = User::where('parent_client', 23)
        ->where('status', 1)
        ->whereHas('roles', function ($query) {
            $query->where('name', 'qa');
        })->pluck('id')->toArray();
       $auditorData = [];
       foreach ($userInfo as $auditorId) {

           // Get auditor's name
           $auditorName = User::find($auditorId)->name;

           // Group audits by partner and process

           $groupedAudits = $auditData->where('audited_by_id', $auditorId)
               ->groupBy(['partner_id', 'process_id']);



           $auditorData[$auditorName] = [];

           foreach ($groupedAudits as $partnerId => $processes) {

               foreach ($processes as $processId => $audits) {

                   $auditCounts = [];


                   // Initialize audit counts for each date in the range
                   // while (strtotime($currentDate) <= strtotime($endDate)) {
                   $count = $audits->count();

                   // $toDate = date("Y-m-d", strtotime("+1 day", strtotime($toDate)));
                   // }

                   $partnerName = Partner::find($partnerId)->name;
                   $processName = Process::where('id', $processId)->first()->name;

                   $auditorData[$auditorName][] = [
                       'partnerName' => $partnerName,
                       'processName' => $processName,
                       'auditCounts' => $count,
                   ];
               }
           }
       }

       $test = "<table style='border:1px solid black; border-collapse: collapse;'>
       <tr>
           <th style='border:1px solid black; text-align: center; background-color: #FFE4C4;'>QA Name</th>
           <th style='border:1px solid black; text-align: center; background-color: #FFE4C4;'>Partner</th>
           <th style='border:1px solid black; text-align: center; background-color: #FFE4C4;'>Process</th>
           <th style='border:1px solid black; text-align: center; background-color: #FFE4C4;'>Audit Count</th>
       </tr>";
       $totalCount =  0;
       foreach ($auditorData as $auditorName => $entries) {
           $colspan = count($entries);
           $total_Audits = 0;
           $firstEntry = true;

           foreach ($entries as $entry) {
               $test .= "<tr>";

               // Only add the auditor name cell for the first entry
               if ($firstEntry) {
                   $test .= "<td style='border:1px solid black; text-align: center;' rowspan='$colspan'>{$auditorName}</td>";
                   $firstEntry = false;
               }
               $test .= "<td style='border:1px solid black; text-align: center;'>{$entry['partnerName']}</td>";
               $test .= "<td style='border:1px solid black; text-align: center;'>{$entry['processName']}</td>";
               $test .= "<td style='border:1px solid black; text-align: center;'>{$entry['auditCounts']}</td>";
               $test .= "</tr>";

               $total_Audits += $entry['auditCounts'];
               $totalCount += $entry['auditCounts'];
           }

           // Total row
           $test .= "<tr>
           <td style='border:1px solid black; text-align: center; background-color: #FFE4C4;' colspan='3'>{$auditorName} Total</td>
           <td style='border:1px solid black; text-align: center;background-color: #FFE4C4;'>{$total_Audits}</td>
       </tr>";
       }
       $test .= "<tr>
       <td style='border:1px solid black; text-align: center; background-color: #FFE4C4;' colspan='3'> Total Audits </td>
       <td style='border:1px solid black; text-align: center;background-color: #FFE4C4;'>{$totalCount}</td>
        </tr>";

       $test .= "</table>";



      


       $file1 = "ProcessWiseAuditCount.xls";
       $file2 = "HourlyWorkingReport.xls";


       

       $xls1 = new \HtmlExcel();
       $xls1->addSheet("ProcessWiseAuditCount", $test);
       $ddddd1 = $xls1->buildFile();
       file_put_contents($file1, $ddddd1);

       // Create second Excel file
       $xls2 = new \HtmlExcel();
       $xls2->addSheet("Test1", $test1);
       $ddddd2 = $xls2->buildFile();
       file_put_contents($file2, $ddddd2);

       // Define the paths to the Excel files
       $corelPath1 = $file1;
       $corelPath2 = $file2;


       $attachments = [
        [
            'file' => $file1,
            'options' => [
                'as' => 'ProcessWiseAuditCount.xls',
            ],
        ],
        [
            'file' => $file2,
            'options' => [
                'as' => 'HourlyWorkingReport.xls',
            ],
        ],       
    ];





        $to_emails = ["Jasim.Ali@qdegrees.com","Sumith.Kalaiselvan@qdegrees.com"];
        // $cc_emails = ["sme@qdegrees.com"];

        // $to_emails = ["palak.khandelwal@qdegrees.org"];
        // $cc_emails = ["palak.khandelwal@qdegrees.org"];
        Mail::to($to_emails)->send(new HourlyReportData($data,  $attachments));     
    }
}
