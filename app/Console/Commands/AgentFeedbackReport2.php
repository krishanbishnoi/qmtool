<?php

namespace App\Console\Commands;

use App\Audit;
use App\Mail\Agentfeedback2;
use App\Partner;
use App\RawData;
use App\Rebuttal;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class AgentFeedbackReport2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feedback:report2';

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

 

       
        // getting excel data
        $data1 = [];
        $data = Audit::whereHas('raw_data', function (Builder $query) {
            $query->where('client_id', 23);           
        })
            ->whereDate('audit_date', '>=', $startOfMonth)
            ->whereDate('audit_date', '<=',  $today)
            ->where('deleted',0)
            ->with(['raw_data', 'raw_data.partner_detail', 'raw_data.process'])->get();
        
        foreach ($data as $key => $value) {
            $basic['data'] = $value->raw_data;
            $basic['audit_date'] = $value->audit_date;
            $basic['partner'] = $value->raw_data->partner_detail->name;
            $basic['process'] = $value->raw_data->process->name;
            $basic['rebuttal_status'] = $value->rebuttal_status;
            $basic['feedback_shared_status'] = $value->feedback_shared_status;
            $basic['feedback_shared_date'] = $value->feedback_shared_date;
            $basic['feedback_remarks'] = $value->feedback_comment;
            $basic['qc_tat'] = $value->qc_tat;
            $basic['audit_view_date'] = $value->audit_view_date;
            $basic['rebuttal_tat'] = $value->rebuttal_tat; 
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
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Partner </th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Process</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Location</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> LOB</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> feedback_shared_status</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> feedback ViEW DATE</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> feedback_shared_date</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> feedback_remarks</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Agent name</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Quality Score</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> TAT(days)</th>";
        $test .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Within TAT</th>";


        $number = 1;
        $hoursDifference = 0;
        foreach ($data1 as $value) {
            $auditDate = Carbon::parse($value['audit_date']);
            // $hoursDifference = Carbon::parse($value['feedback_shared_date'])->diffInHours(Carbon::parse($value['qc_tat']));

            $feedbacksharedate = $value['feedback_shared_date'] ? Carbon::parse($value['feedback_shared_date']) : null;
            $rebuttaldate = $value['rebuttal_tat'] ? Carbon::parse($value['rebuttal_tat']) : null;
            // $feedbackViewDate = null;
            // if ($auditDate->dayOfWeek === Carbon::FRIDAY) {
            //     $feedbackViewDate = $auditDate->copy()->addDays(3);
            // } elseif ($auditDate->dayOfWeek === Carbon::SATURDAY) {
            //     $feedbackViewDate = $auditDate->copy()->addDays(2);
            // } else {
            //     $feedbackViewDate = $auditDate->copy()->addDay();
            // }
                $feedbackViewDate  = Carbon::parse($value['audit_view_date']);   
          
            $diff_share_date = 0;
          
            
            $diffInHours = null;
            $diffInDays = null;
            if ($feedbacksharedate !== null) {
                $diffInHours = $feedbacksharedate->diffInHours($feedbackViewDate);
                $diffInDays = $feedbacksharedate->diffInDays($feedbackViewDate);
            }

            if ($diffInHours !== null && $diffInHours % 24 != 0) {
                $diffInDays += 1;
            }


            $diffinrebuttals = null;
            if ($feedbacksharedate !== null) {
                $diffinrebuttals = $rebuttaldate->diffInHours($feedbackViewDate);
            }


            // $diff = Carbon::parse($value['qc_tat'])->diffInDays(Carbon::parse($value['feedback_shared_date']));
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
            $test .= "<td style='border:1px solid black;text-align: center;'>" . date_format($feedbackViewDate, 'Y-m-d') . "</td>";
            $test .= "<td style='border:1px solid black;text-align: center;'>" . ($feedbacksharedate ? $feedbacksharedate->format('Y-m-d') : '') . "</td>";
            $test .= "<td style='border:1px solid black;text-align: center;'>" . (isset($value['feedback_remarks']) ? $value['feedback_remarks'] : '') . "</td>";
            $test .= "<td style='border:1px solid black;text-align: center;'>{$value['data']->agent_name}</td>";
            $test .= "<td style='border:1px solid black;text-align: center;'>" . (round($value['with_fatal_score_per'])) . "%</td>";
            $test .= "<td style='border:1px solid black;text-align: center;'>$diffInDays</td>";

            $rebuttal_data = Rebuttal::where('raw_data_id', $value['data']->id)->get();

            if ($feedbacksharedate && $diffInDays !== null) {
                if ($rebuttal_data->count() > 0) {
                    $test .= "<td style='border:1px solid black;text-align: center;'>Rebuttal Affected</td>";
                } elseif ($diffInDays <= 3) {
                    $test .= "<td style='border:1px solid black;text-align: center;'>Within TAT</td>";
                } else {
                    $test .= "<td style='border:1px solid black;text-align: center;'>Not in TAT</td>";
                }
            } else {
                if($rebuttal_data->count() > 0){
                    $test .= "<td style='border:1px solid black;text-align: center;'>Rebuttal Affected</td>";
                }else{
                    $test .= "<td style='border:1px solid black;text-align: center;'>No feedback date</td>";
                }               
            }
            $number++;
        }

        $test .= "</tbody></table>";

       

      

        $file1 = public_path("AgentfRLastMonth1.xls");
      

        $xls1 = new \HtmlExcel();
        $xls1->addSheet("AgentfeedbackReportLastMonth1", $test);
        $ddddd1 = $xls1->buildFile();
        file_put_contents($file1, $ddddd1);

      

     
        // $to_emails = ["Rahul.adiwal@bajajfinserv.in","Sagar.Khajekar@bajajfinserv.in"];    
        // $cc_emails = ["Manmitraj.Mahajan@bajajfinserv.in","Jasim.Ali@qdegrees.com","shubhda.sharma@qdegrees.com","palak.khandelwal@qdegrees.org","kiran.ingulkar@qdegrees.org","Sumith.Kalaiselvan@qdegrees.com","NISHA.KRISHNA@qdegrees.com","divy.bhatnagar@qdegrees.com","quality.bfl.pune@qdegrees.com","SME.BFL@QDegrees.org" ,"Ranjit.Chandran@qdegrees.com"];
        // $to_emails = ["Jasim.Ali@qdegrees.com","shubhda.sharma@qdegrees.com","Sumith.Kalaiselvan@qdegrees.com","NISHA.KRISHNA@qdegrees.com","divy.bhatnagar@qdegrees.com"];
        // $cc_emails = ["Ranjit.Chandran@qdegrees.com","akhil.kumawat@qdegrees.com"];
        // $cc_emails = ["palak.khandelwal@qdegrees.org"];
        // $to_emails = ["palak.khandelwal@qdegrees.org"];
        // Mail::to($to_emails)->cc($cc_emails)->send(new Agentfeedback2($subject, $attachments));
       
    }
}

