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
use Illuminate\Support\Facades\Storage;

class AgentFeedbackCallAudit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'callaudit:sendmail';

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
        $count = 0;
        $today = Carbon::now()->subDays(1);
        $previous_day = date("Y-m-d", strtotime($today));
        $previous_day .= "%";
        
        $all_todays_audits = Audit::where('client_id',14)->whereNotIn('process_id', [104,108])->where('qc_tat', 'like',$previous_day)->pluck('id');
        $logs = AgentCallFeedbackLog::whereDate('created_at','>',Carbon::now()->subDays(3))->pluck('audit_id')->toArray();
        // dd($all_todays_audits);

        /* echo gettype($logs);
        die; */
        //echo $today;
       // echo "<br/>";
       // echo $previous_day;
        // print_r(count($logs)); 
        // die;
        foreach($all_todays_audits as $value){
            /* echo array_search($value,$logs);
            die; */
            if(array_search($value,$logs) === FALSE){
                
                try {
                    $audit_id = $value;
                    $response = array();
                    $overall_sheet_parameters = AuditResult::select('audit_results.audit_id','qm_sheet_parameters.parameter','qm_sheet_sub_parameters.sub_parameter','qm_sheet_sub_parameters.weight','qm_sheet_parameters.is_non_scoring','audit_results.selected_option','audit_results.score','audit_parameter_results.with_fatal_score_per','reason_types.name as reason_type_name','reasons.name as reason_name','audit_results.remark as remarks')
                    ->join('audits','audit_results.audit_id','=','audits.id')
                    ->join('qm_sheet_parameters','qm_sheet_parameters.id','=','audit_results.parameter_id')
                    ->join('qm_sheet_sub_parameters','audit_results.sub_parameter_id','=','qm_sheet_sub_parameters.id')
                    ->join('audit_parameter_results','audit_results.parameter_id','=','audit_parameter_results.parameter_id')
                    ->leftJoin('reason_types','audit_results.reason_type_id','=','reason_types.id')
                    ->leftJoin('reasons','audit_results.reason_id','=','reasons.id')
                    ->where('audit_results.audit_id',$audit_id)
                    ->where('audit_parameter_results.audit_id',$audit_id)
                    ->get();
                    $audit_detail = DB::select("select a.overall_summary,a.feedback,a.with_fatal_score_per,r.agent_name as agent_email,r.info_1,r.info_5 as agent_name,r.tl as tl_mail,r.info_3 as call_id,r.call_time,a.audit_date,a.good_bad_call_file,a.process_id,a.refrence_number,a.client_id
                    from audits a inner join raw_data r on a.raw_data_id = r.id where a.id = ". $audit_id. "");
        
                    $response['overall_sheet_parameters'] =  $overall_sheet_parameters;
                    $response['audit_detail'] = $audit_detail; 
                    /* echo "hi";
                    die; */
                    if($audit_detail[0]->good_bad_call_file){
                    $path_name = str_replace("https://qmtool.s3.ap-south-1.amazonaws.com/", "", $audit_detail[0]->good_bad_call_file);
                    $url = Storage::disk('s3')->temporaryUrl(
                        $path_name,
                        now()->addMinutes(8640) //Minutes for which the signature will stay valid
                        );

                    $response['file_url'] = $url; 
                    }
                    else{
                        $response['file_url'] = "";
                    }
                   
                }
                
                //catch exception
                catch(Exception $e) {
                    //echo 'Message: ' .$e->getMessage();
                    
                }
              
    //print_r($response); die;
                //return view('check_sheet_agent_mail',compact('response'));
                try{
                    $agent_email = $audit_detail[0]->agent_email;
                    $tl_mail = $audit_detail[0]->tl_mail;

                    

                   
                    if($audit_detail[0]->process_id == 45) // PORTER_IBCC_EXTERNAL
                    $cc_emails = AgentFeedbackEmail::where('email_type','cc')->where('client_id',14)->where('process_id',45)->pluck('email');
                        //$cc_emails = ['abhinav.agarwal@qdegrees.com','nisha@theporter.in','amruta@theporter.in','mum_cc_tls@porter.in','essencia_support@theporter.in'];
                    else if($audit_detail[0]->process_id == 46) // PORTER_IBCC_INTERNAL
                    $cc_emails = AgentFeedbackEmail::where('email_type','cc')->where('client_id',14)->where('process_id',46)->pluck('email');
                       // $cc_emails = ['abhinav.agarwal@qdegrees.com','nisha@theporter.in','amruta@theporter.in','mum_cc_tls@porter.in'];
                     else if($audit_detail[0]->process_id == 47) // PORTER_OBCC
                     $cc_emails = AgentFeedbackEmail::where('email_type','cc')->where('client_id',14)->where('process_id',47)->pluck('email');
                       // $cc_emails = ['abhinav.agarwal@qdegrees.com','nisha@theporter.in','amruta@theporter.in','mum_cc_tls@porter.in'];
                    else if($audit_detail[0]->process_id == 53) // PORTER_IBCC_House Shifting
                    $cc_emails = AgentFeedbackEmail::where('email_type','cc')->where('client_id',14)->where('process_id',53)->pluck('email');
                       // $cc_emails = ['abhinav.agarwal@qdegrees.com','megha.p@theporter.in','amruta@theporter.in','HS.CC_Leaders@theporter.in','jyoti@theporter.in'];
                    else if($audit_detail[0]->process_id == 54) // PORTER_OBCC_House Shifting
                    $cc_emails = AgentFeedbackEmail::where('email_type','cc')->where('client_id',14)->where('process_id',54)->pluck('email');
                       // $cc_emails = ['abhinav.agarwal@qdegrees.com','megha.p@theporter.in','amruta@theporter.in','HS.CC_Leaders@theporter.in','jyoti@theporter.in'];
                    else if($audit_detail[0]->process_id == 63) // PORTER_IBCC PFE
                    $cc_emails = AgentFeedbackEmail::where('email_type','cc')->where('client_id',14)->where('process_id',63)->pluck('email');
                    else if($audit_detail[0]->process_id == 62) // PORTER_OBCC PFE
                    $cc_emails = AgentFeedbackEmail::where('email_type','cc')->where('client_id',14)->where('process_id',62)->pluck('email');
                    else if($audit_detail[0]->process_id == 102) // PORTER_IBCC_DHAKA
                        $cc_emails = AgentFeedbackEmail::where('email_type','cc')->where('client_id',14)->where('process_id',102)->pluck('email');
                    else if($audit_detail[0]->process_id == 70) // PORTER_OBCC_UAE
                        $cc_emails = AgentFeedbackEmail::where('email_type','cc')->where('client_id',14)->where('process_id',70)->pluck('email');
                    else
                    $cc_emails = ['abhinav.agarwal@qdegrees.com','nisha@theporter.in','amruta@theporter.in'];
                    
                    /* $to_mail = ['saurav.sarkar@qdegrees.com','samarjeet.singh@qdegrees.com','rahul.gupta@qdegrees.com','abhinav.agarwal@qdegrees.com','amit.kumar3@qdegrees.com'];
                    $cc_emails = ['shailendra.kumar@qdegrees.com','monika.wadhwani@qdegrees.com','sb@qdegrees.com'];
                     */
                    
                    // return "hi";
                     
                    
                    
                    /* $to_mail = [$audit_detail[0]->agent_email];
                    $cc_emails = [$audit_detail[0]->tl_mail]; */
                    if(!$this->email_validation($tl_mail)) {
                        $to_mail = [$agent_email];

                    }
                    else {
                        $to_mail = [$agent_email,$tl_mail];
                    }
                    
                   /*  $to_mail = ['monika.wadhwani@qdegrees.com'];
                    $cc_emails = ['shailendra.kumar@qdegrees.com','shailendra1994@hotmail.com'];
                    */
                    try {
                        //code...
                        $subject = 'Latest Audit Feedback';
                        Mail::to($to_mail)->cc($cc_emails)->send(new FeedbackToAgent(
                            [  
                                'msg_1'=>'Please ensure that same will be closed before 24hrs time window. In case of any doubt or query while addressing Re-rebuttal please connect with your PM. If there is any error by QA during Rebuttal closure, please ensure to share the feedback on the same to prevent repetition of same re-rebuttal issue.',
                                'subject'=>$subject,
                                'response'=>$response,
                            
                            ]
                        ));
                        
                        $new_log = new AgentCallFeedbackLog();
                        $new_log->audit_id = $audit_id;
                        $new_log->mail_trigger_date = Carbon::now();
                        $new_log->mail_trigger_status = 1;
                        $new_log->save();
                    } catch (\Exception $e) {
                        $count++;
                        echo 'Message: ' .$e->getMessage();
                        echo "<br>";
                        //print_r($to_mail);
                        die;
                    }

                    
                    
                }
                        
                catch(Exception $e){

                }
                
            }
           
        }
        
    }
   
// PHP program to validate email

// Function to validate email using regular expression
 public function email_validation($str) {
	return (!preg_match(
    "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $str))
		? FALSE : TRUE;

}

// Function call






}
