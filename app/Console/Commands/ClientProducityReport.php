<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\DB;
use Illuminate\Mail\Mailable;
use App\Mail\ClientWiseProductivityReport;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClientProducityReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:report';

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
       
            $yesterday_date = Carbon::now()->subDays(1)->format('Y-m-d');
            $day_before_yesterday = Carbon::now()->subDays(3)->format('Y-m-d');
            $month =Carbon::now()->subDays(1)->format('Y-m');
            $data = DB::select("SELECT  clients.name as client , clients.id as client_id, count(audits.id) as yesterday_audit_count 

            ,(SELECT COUNT(DISTINCT(audited_by_id)) from audits where client_id = clients.id and audit_date like '$yesterday_date%') as number_of_qa
            ,(SELECT count(audits.id) FROM audits inner join clients
            on audits.client_id=clients.id
            where is_critical = 1
            and audit_date like '$yesterday_date%'
            and client=(select clients.name as client) 
            group by client) as fatal_count_yesterday
    
            ,(SELECT count(audits.id) FROM audits inner join clients
            on audits.client_id=clients.id
            where rebuttal_status !=0
            and audit_date like '$yesterday_date%'
            and client=(select clients.name as client) 
            group by client) as rebuttal_count_yesterday
    
            ,(SELECT round(sum(overall_score) / count(overall_score),2) FROM audits inner join  clients
            on audits.client_id=clients.id where
            audit_date like '$yesterday_date%'
            and client=(select clients.name as client)
            group by client) as avg_yes_score
    
            ,(SELECT count(audits.id)  FROM audits
            inner join clients
            on audits.client_id=clients.id
            where audit_date like '$day_before_yesterday%'
            and client=(select clients.name as client)
            group by client) as audit_count_variance
    
            ,(SELECT count(audits.id) FROM audits
            inner join clients
            on audits.client_id=clients.id
            where audit_date like '$month%'
            and client=(select clients.name as client)
            group by client) as audit_count_month
    
            ,(SELECT count(audits.id) FROM audits
            inner join clients
            on audits.client_id=clients.id
            where is_critical = 1
            and audit_date like '$month%'
            and client=(select clients.name as client)
            group by client) as fatal_count_mtd
    
            ,(SELECT count(audits.id) FROM audits
            inner join clients
            on audits.client_id=clients.id
            where rebuttal_status !=0
            and audit_date like '$month%'
            and client=(select clients.name as client)
            group by client) as rebuttal_count_mtd
    
    
            ,(SELECT round(sum(overall_score) / count(overall_score),2)  FROM audits
            inner join clients
            on audits.client_id=clients.id
            where audit_date like '$month%'
            and client=(select clients.name as client)
            group by client) as avg_month_score
    
            FROM audits
            join clients
            on audits.client_id=clients.id
            and audit_date like '$yesterday_date%'
            group by client ;");
    
               
    
            // compact('data');
             
            $test ="<table style='border:1px solid black;'>
                        
                                <tr> 
                   
                                    <th style='border:1px solid black;text-align: center;'> Client</th>
                                    <th style='border:1px solid black;text-align: center;'>Audit Count($yesterday_date)</th>
                                    <th style='border:1px solid black;text-align: center;'>No of QA </th>
                                    <th style='border:1px solid black;text-align: center;'>Fatal Count($yesterday_date)</th>
                                    
                                    <th style='border:1px solid black;text-align: center;'>Average Yesterday Audit Count </th>
                                    <th style='border:1px solid black;text-align: center;'>Average Score($yesterday_date)</th>
                                    <th style='border:1px solid black;text-align: center;'>Audit Count Variance</th>
                                    <th style='border:1px solid black;text-align: center;'>Audit Count(MTD)</th>
                                    <th style='border:1px solid black;text-align: center;'>Average Audit Done (MTD)</th>
                                    <th style='border:1px solid black;text-align: center;'>Fatal Count(MTD)</th>
                                    <th style='border:1px solid black;text-align: center;'>Rebuttal Count(MTD)</th>
                                    <th style='border:1px solid black;text-align: center;'>Average Score(MTD)</th>
                                </tr> 
    
                            
                            <tbody>";
        
                                foreach($data as $row)
                                {
                                    //audit count variance
                                    $variance = $row->yesterday_audit_count - $row->audit_count_variance;
    
                                    // average of yesterday audit
                                    $yes_audit_count=  $row->yesterday_audit_count; 
                                    $qa = $row->number_of_qa;
                                    $yesterday= round(($yes_audit_count/$qa),2);
                                    // average of month audit
                                    $month_audit_count= $row->audit_count_month;
                                    $month= round(($month_audit_count/$qa),2);
    
                                    $day_number = explode("-",$yesterday_date);
                                    $day_count = (int)$day_number[2];
                                    $avg_client_audit_count_per_audit = 0;
                                    for($i=1; $i<=$day_count; $i++){
                                        if($i<10){
                                            $c_date = $day_number[0]."-".$day_number[1]."-0".$i;
                                        } else {
                                            $c_date = $day_number[0]."-".$day_number[1]."-".$i;
                                        }
                                        

                                        $client_qa = DB::select("SELECT COUNT(DISTINCT(audited_by_id)) as counts from audits where audit_date like '$c_date%' and client_id = $row->client_id");
                                        $client_audits = DB::select("SELECT COUNT(id) as counts from audits where audit_date like '$c_date%' and client_id = $row->client_id");
                                        
                                        $avg_client_audit_count_per_audit += round(divnum($client_audits[0]->counts,$client_qa[0]->counts),2);
                                        
                                    }
                                    //$average_client_auditor = round((divnum($avg_client_audit_count_per_audit,$day_count)),2);
                                    
                                    //color coading
                                    if($variance < 0)
                                    { $colorcode="red"; }
                                    else 
                                    { $colorcode ="green"; }
    
                                $test .="<tr>
                                   
                                    <td style='border:1px solid black;text-align: center;'>$row->client</td>
                                    <td style='border:1px solid black;text-align: center;'>".(int)$row->yesterday_audit_count."</td>
                                    <td style='border:1px solid black;text-align: center;'>".(int)$row->number_of_qa."</td>
                                    <td style='border:1px solid black;text-align: center;'>".(int)$row->fatal_count_yesterday."</td>
                                    
                                    <td style='border:1px solid black;text-align: center;'>".(float)$yesterday."</td>
                                    <td style='border:1px solid black;text-align: center;'>".(float)$row->avg_yes_score."</td>
                                    <td style='border:1px solid black;text-align: center;' bgcolor='".$colorcode."'>".(int)$variance."</td>
                                    <td style='border:1px solid black;text-align: center;'>".(int)$row->audit_count_month."</td>
                                    <td style='border:1px solid black;text-align: center;'>".(float)$avg_client_audit_count_per_audit."</td>
                                    <td style='border:1px solid black;text-align: center;'>".(int)$row->fatal_count_mtd ."</td>
                                    <td style='border:1px solid black;text-align: center;'>".(int)$row->rebuttal_count_mtd ."</td>
                                    <td style='border:1px solid black;text-align: center;'>".(float)$row->avg_month_score."</td>
                                
                                </tr>";
                                 
                              }
                        $test .="</tbody>
    
                    </table>";
                    // Excel file name for download 
                    // Headers for download 
                    
                    //  header("Content-Disposition: attachment; filename=\"$fileName\""); 
                    //  header("Content-Type: application/vnd.ms-excel"); 
                    // echo $test;
                    
                    
                    // require_once('HtmlConvert2021.php');
                    // $xls = new \HtmlExcel();
                    //  $to_mail=['sumeet.goenka@qdegrees.com'];
                    //  Mail::to($to_mail)->send(new ProductivityReportMail(
                    //     [      
                    //        'subject'=>"Productivity Report",
                    //        'file'=> $test,
                    //     ]));
                       
    
                    
                $file="client_wise_report.xls";
               
                require_once('HtmlConvert2021.php');
                $xls = new \HtmlExcel();
                $xls->addSheet("client_wise_report", $test); 
                //$xls->headers();
                $ddddd = $xls->buildFile();
                $files=file_put_contents("client_wise_report.xls", $ddddd);
                
                // $cycle_name = Auditcycle::where('id',$month)->first();
                // $mail_subject = $cycle_name->name;
                $subject = "QA Views Productivity Report of date(".$yesterday_date.")";
                $corelPath = 'client_wise_report.xls';
        
                $client = 'Mama Money';
                $process = 'QM TOOL';
                $qtl = 'Sumeet';
                // $to_emails = ["SHUBHDA.SHARMA@QDEGREES.COM"];
                 $to_emails = ["sb@qdegrees.com","cbrajesh@qdegrees.com","jasim.ali@qdegrees.com"];
                // Mail::to('$to_emails)->send(new \App\Mail\ProductivityReportMail);
                Mail::to($to_emails)->send(new ClientWiseProductivityReport(
                    [
                    // 'partner_name'=>'All',
                    'file'=>$corelPath,
                    'subject'=>$subject,
                    'client'=>$client,
                    'process'=>$process,
                    'qtl'=>$qtl,
                    'data' => $data,
                    'url'=>'https://simpliq.qdegrees.com/'
                    ]
                ));
    
         
        }
    
}
