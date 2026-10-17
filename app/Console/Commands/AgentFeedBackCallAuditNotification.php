<?php

namespace App\Console\Commands;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '10000');
use App\Audit;
use App\AuditParameterResult;
use App\AuditResult;
use App\QmSheet;
use App\QmSheetParameter;
use App\QmSheetSubParameter;
use App\RawData;
use App\Mail\FeedbackToAgent;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;
use App\AgentCallFeedbackLog;
use Carbon\Carbon;
use App\AgentFeedbackEmail;

class AgentFeedbackCallAuditNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'callaudit:notification';

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
        
        $today = Carbon::now()->subDays(1);
        $previous_day = date("Y-m-d", strtotime($today));
        $previous_day .= "%";

        $current = Carbon::now();
        $current_date = date("Y-m-d", strtotime($current));
        $current_date .= "%";

        /* $mobile = "9509485768";
        $mobile = "8107677968";
                $otp = "Agent Notification";
                $msg = "Hello, agent notification could not be sent due to email server error. Please contact developer team or IT support at QDegrees.";
                
                $this->sendSms($mobile,$msg);
                $msg = "Hello, agent notification could not be sent due to email server error. Please contact developer team or IT support.";

                die; */
        
        $all_todays_audits = Audit::where('client_id',14)->where('qc_tat', 'like',$previous_day)->count();
        $logs = AgentCallFeedbackLog::where('created_at', 'like',$current_date)->count();
        echo $logs;
        die;
        /* echo gettype($logs);
        die; */
        //foreach($all_todays_audits as $value){

            if($all_todays_audits > $logs){
                
                $mobile = "8107677968";
                $otp = "Agent Notification";
                $msg = "Hello, agent notification could not be sent due to email server error. Please contact developer team or IT support at QDegrees.";
                
                $this->sendSms("8107677968",$msg);
                $this->sendSms("7568864318",$msg);
                $this->sendSms("9509485768",$msg);
                die;
 
            }
           
        //}
        
    }

    public function sendSms($mobile,$msg) {
        $message=$msg;
    
        $xml_data ='<?xml version="1.0"?>
                    <smslist>
                    <sms>
                    <user>qdegree</user>
                    <password>7bc845dab5XX</password>
                    <message>'.$message.'</message>
                    <mobiles>'.$mobile.'</mobiles>
                    <senderid>QDSSUR</senderid>
                        <accusage>1</accusage>
                        <responsein>csv</responsein>
                    </sms>
                    </smslist>';
        $URL = "http://sms.smsmenow.in/sendsms.jsp?"; 
        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        //curl_close($ch);
        //$response = curl_exec($ch);        
        // print_r($response);die;
        $err = curl_error($ch);
        curl_close($ch);
        return true;
           /* if ($err) {
                $model = new \frontend\modules\nps\models\SmsLog();
                $model->details = "cURL Error #:" . $err.$response.'---'.$response_id;
                ;
                $model->status = 0;
                $model->save(false);
                //echo "cURL Error #:" . $err;
            } else {
                $model = new \frontend\modules\nps\models\SmsLog();
                $model->details = $response.'---'.$response_id;
                $model->status = 1;
                $model->save(false);
                //echo $response;
            }*/
        //print_r($output); die;
    }
}
