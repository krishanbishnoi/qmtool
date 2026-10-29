<?php

namespace App\Http\Controllers;
ini_set('memory_limit', '-1');
use Illuminate\Http\Request;
use App\Target;
use App\Client;
use App\Process;
use App\Partner;
use App\AuditCycle;
use App\RawData;
use App\User;
use App\QaTarget;
use App\QaDailyTarget;
use Crypt;
use Carbon\Carbon;
use DateTime;

use Auth;
use Illuminate\Support\Facades\DB;

class QaTargetController extends Controller
{
    public function index(){

      
        
        return view('qa_target.qa_target');
    }
    public function set_qa_daily_target(){
    
        
        return view('qa_daily_target.qa_target');
    }
    public function getList(){
       
         $data = QaTarget::select('qa_targets.*','users.name')
        ->join('users', 'qa_targets.qa_id', '=', 'users.id')->
        where('users.reporting_user_id',Auth::user()->id)->get();

        return view('qa_target.list',compact('data'));
    }
    public function getList_daily_target(){

       /*  $data = QaDailyTarget::select('qa_daily_targets.*','users.name')
        ->join('users', 'qa_daily_targets.qa_id', '=', 'users.id')->
        where('users.reporting_user_id',Auth::user()->id)->get(); */

       /*  $final_data=array();
        $data = User::where('reporting_user_id',Auth::user()->id)->get();

        $temp = array();
        foreach($data as $value){
            array($temp['name'] => $value->name,
            $temp['email'] => $value->email);
            
            $qa_target = QaDailyTarget::where('qa_id',$value->id)->get();
            $temp2=[];
          
        }
        print_r($temp); */
        /* dd(); */ 
        $today = date_create(date('y-m-d'));
        $add_1 = date_add($today,date_interval_create_from_date_string("1 days"));
        $tomorrow = date_format($add_1,"Y-m-d");
        $add_2 = date_add($today,date_interval_create_from_date_string("1 days"));
        
        $day_after_tomorrow = date_format($add_2,"Y-m-d");
        
        

        /* echo date('Y-m-t');
        dd();  */   
        $data = DB::select("
            select id, name, 
            (select target from qa_daily_targets where target_day = '".date('y-m-d')."' and qa_id = u.id) as today, 
            (select target from qa_daily_targets where target_day = '".$tomorrow."' and qa_id = u.id) as tomorrow, 
            (select target from qa_daily_targets where target_day = '".$day_after_tomorrow."' and qa_id = u.id) as day_after_tomorrow, 
            (select count(target) from qa_daily_targets where target_day >= '".date('Y-m-01')."' and target_day <= '".date('Y-m-t')."' and qa_id = u.id) as days
            from users u where u.reporting_user_id = ". Auth::user()->id. " order by u.id asc");

           /*  echo "<pre>";
            print_r($data);
            dd();
 */
        return view('qa_daily_target.list',compact('data','today','tomorrow', 'day_after_tomorrow'));
    }

    public function single_user_target($user_id){

        //echo Crypt::decrypt($target_id);

        $data = QaDailyTarget::where('target_day','>=',date('Y-m-01'))
        ->where('target_day','<=',date('Y-m-t'))
        ->where('qa_id',Crypt::decrypt($user_id))
        ->get();

        $qa_details = User::where('id',Crypt::decrypt($user_id))->first();


        //$data = QaTarget::where('id',Crypt::decrypt($target_id))->get();

        /* echo Crypt::decrypt($target_id); */
       /*  echo '<pre>';
        print_r($data[0]->client_name);
        dd(); */

        return view('qa_daily_target.list_single',compact('data','qa_details'));
    }

    public function single_target($target_id){

        //echo Crypt::decrypt($target_id);

        $data = QaTarget::select('qa_targets.*','users.name')
        ->join('users', 'qa_targets.qa_id', '=', 'users.id')->
        where('qa_targets.id',Crypt::decrypt($target_id))->get();

        //$data = QaTarget::where('id',Crypt::decrypt($target_id))->get();

        /* echo Crypt::decrypt($target_id); */
       /*  echo '<pre>';
        print_r($data[0]->client_name);
        dd(); */

        return view('qa_target.edit',compact('data'));
    }

    public function get_client($company_id, $process_owner_id){
        $data = Client::select('id','name')->where('company_id',$company_id)->where('process_owner_id',$process_owner_id)->get();
      
        $html='<option value="">Select Client</option>';
        foreach($data as $val) {
            $html.='<option value='.$val->id.'>'.$val->name.'</option>';
        }
        return $html; 
    }

    public function get_process($company_id){
        $data = Process::select('id','name')->where('company_id',$company_id)->get();
      
        $html='<option value="">Select Process</option>';
        foreach($data as $val) {
            $html.='<option value='.$val->id.'>'.$val->name.'</option>';
        }
        return $html; 
    }

    public function get_partners($client_id, $company_id){
        $data = Partner::select('id','name')->where('company_id',$company_id)->where('client_id',$client_id)->get();
      
        $html='<option value="">Select Partner</option>';
        foreach($data as $val) {
            $html.='<option value='.$val->id.'>'.$val->name.'</option>';
        }
        return $html; 
    }

    public function get_audit_cycle($client_id, $process_id){

        $data = AuditCycle::select('id','name')->where('client_id',$client_id)->where('process_id',$process_id)->get();
      
        $html='<option value="">Select Audit Cycle</option>';
        foreach($data as $val) {
            $html.='<option value='.$val->id.'>'.$val->name.'</option>';
        }
        return $html; 
    }

    public function get_lob($partner_id){

        $data=RawData::select(DB::raw("distinct lob"))->where('partner_id',$partner_id)->get();
        $html='<option value="">Select Lob</option>';
        foreach($data as $val) {
            $html.='<option value='.$val->lob.'>'.$val->lob.'</option>';
        }
        return $html; 
    }

    public function get_brand($partner_id){

        $data=RawData::select(DB::raw("distinct brand_name"))->where('partner_id',$partner_id)->get();
        $html='<option value="">Select Audit brand</option>';
        foreach($data as $val) {
            $html.='<option value='.$val->brand_name.'>'.$val->brand_name.'</option>';
        }
        return $html; 
    }

    public function get_circle($partner_id){

        $data=RawData::select(DB::raw("distinct circle"))->where('partner_id',$partner_id)->get();
        $html='<option value="">Select Circle</option>';
        foreach($data as $val) {
            $html.='<option value='.$val->circle.'>'.$val->circle.'</option>';
        }
        return $html; 
    }
    
    public function save_target(Request $request)
    {
        // //echo "fun in";
        // /* print_r($request->target);
        // dd(); */

      

        $check_circle_and_cycle = Target::where('audit_cycle_id',$request->audit_cycle_id)->where('circle_name',$request->circle)->count();
        $check_cycle = AuditCycle::whereDate('end_date', '>=', date("Y-m-d"))->where('id',$request->audit_cycle_id)->count();
        if($check_circle_and_cycle  && $check_cycle){
           
            return redirect('target/set')->with('warning', 'Please select different audit cycle or Circle.');
        }
        else {
            $target = new Target;
            $target->client_id = $request->client_id;
            $target->process_owner_id = Auth::User()->id;
            $target->process_id = $request->process_id;
            $target->audit_cycle_id = $request->audit_cycle_id;
            $target->partner_id = $request->partner_id;
            $target->lob = $request->lob;
            $target->brand_name = $request->brand;
            $target->circle_name = $request->circle;
            $target->target = $request->target;

            $target->save();
            

            return redirect('target/set')->with('success', 'Target Set Successfully.'); 
        }
       
    }

    public function updae_target(Request $request)
    {
        //$data = Target::where('audit_cycle_id',$request->audit_cycle_id)->where('circle_name',$request->circle)->count();
        
        $update_data = QaTarget::find($request->target_id);
        $update_data->qa_target = $request->target;
        $update_data->target_month = $request->target_month;
        $update_data->save();
        return redirect('qa_target/list')->with('success', 'Target updated Successfully.'); 
        
    }

    public function qa_report(){

        
        $date = date("m/Y");
        $date_array = explode('/', $date);
        
        $compare_date = $date_array[1]. '-'. $date_array[0];
        /* echo $compare_date;
        dd(); */
        $data = DB::select("
            select q.*, u.name, (select count(id) from audits where audited_by_id = q.qa_id and audit_date like '". $compare_date ."%') as count from qa_targets q inner join users u on q.qa_id = u.id
            where u.reporting_user_id = ". Auth::user()->id. " and q.target_month = '". date("m/Y"). "' order by target_month desc");

       
        return view('reports.qa_report',compact('data'));
    }

    public function search_report(Request $request)
    {
        //$data = Target::where('audit_cycle_id',$request->audit_cycle_id)->where('circle_name',$request->circle)->count();
        
        if($request->target_month){

            $date = $request->target_month;
          
        }
        else{
            $date = date("m/Y");
        }
/*   echo $date;
            dd(); */
        
        $date_array = explode('/', $date);
        
        $compare_date = $date_array[1]. '-'. $date_array[0];
       /*  echo $compare_date;
        dd(); */
        $data = DB::select("
            select q.*, u.name, (select count(id) from audits where audited_by_id = q.qa_id and audit_date like '". $compare_date ."%') as count from qa_targets q inner join users u on q.qa_id = u.id
            where u.reporting_user_id = ". Auth::user()->id. " and q.target_month = '". $date. "' and q.deleted = 0 order by target_month desc");

       
        return view('reports.qa_report',compact('data'));
        
    }

    public function qa_performance(Request $request)
    {
        //$data = Target::where('audit_cycle_id',$request->audit_cycle_id)->where('circle_name',$request->circle)->count();
        
        if($request->target_month){
            $dates = explode("-", $request->target_month);
            $start_date = date_to_db($dates[0]);
            $end_date = date_to_db($dates[1]);

            
          
        }
        else{
            $start_date = Carbon::now()->subDays(14);
            $start_date = $start_date->copy();
            $start_date = $start_date->toDateString();

            $end_date = Carbon::now()->subDays(7);
            $end_date = $end_date->copy();
            $end_date = $end_date->toDateString();
        }

       /* echo $next_month. "<br>". $start_date;
       dd(); */
        $data = DB::select("
            select u.*, (select sum(ap.total_spend_time) from auditor_performence_reports ap where ap.auditor_id = u.id and 
            start_date >= '". $start_date ."' and start_date <= '". $end_date ."'
            ) as total_time,
            (select sum(ap.audit_done) from auditor_performence_reports ap where auditor_id = u.id and 
            start_date >= '". $start_date ."' and start_date <= '". $end_date ."') as total_audits,
            (select count(distinct left(a.audit_date, locate(' ', a.audit_date, 1)-1)) from audits a where audited_by_id = u.id and 
            audit_date >= '". $start_date ."' and audit_date <= '". $end_date ."') as present_days,
            (select count(ad.id) from audits ad where ad.audited_by_id = u.id and 
            ad.audit_date >= '". $start_date ."' and ad.audit_date <= '". $end_date ."') as audits,
            
            (select sum(qt.qa_target) from qa_targets qt where qt.qa_id = u.id and qt.deleted != 1 and 
            qt.created_at >= '". $start_date ."' and qt.created_at <= '". $end_date ."') as qa_target

            from users u where u.reporting_user_id = ". Auth::user()->id. " order by audits");
            
            /* $data = DB::select("
            select ap.*, u.name from auditor_performence_reports ap inner join users u on ap.auditor_id = u.id where u.reporting_user_id = ". Auth::user()->id. " order by u.name");
 */
       /*  echo "<pre>";
        print_r($data);
        dd(); */
        return view('reports.qa_report_full',compact('data'));
        
    }

    public function qa_performance_matrix(Request $request)
    {
        //$data = Target::where('audit_cycle_id',$request->audit_cycle_id)->where('circle_name',$request->circle)->count();
 if ($request->filled('target_month')) {
    $selectedDate = Carbon::createFromFormat(
        '!m/Y',
        $request->target_month
    );
} else {
    $selectedDate = Carbon::create(now()->year, 2, 1);
}

$start_date = $selectedDate->copy()
    ->startOfMonth()
    ->toDateString();

$next_month = $selectedDate->copy()
    ->startOfMonth()
    ->addMonth()
    ->toDateString();

$month = $selectedDate->format('Y-m');
$target_month = $selectedDate->format('m/Y');
     

// dd([
//     'target_month_request' => $request->target_month,
//     'start_date' => $start_date,
//     'end_date' => $end_date ?? null,
//     'next_month' => $next_month,
//     'month' => $month,
//     'target_month' => $target_month,
// ]);


       /* echo $next_month. "<br>". $start_date;
       dd(); */

    //    dump()
        $data = DB::select("
            select u.*, (select sum(ap.total_spend_time) from auditor_performence_reports ap where ap.auditor_id = u.id and 
            start_date >= '". $start_date ."' and start_date < '". $next_month ."'
            ) as total_time,
            (select sum(ap.audit_done) from auditor_performence_reports ap where auditor_id = u.id and 
            start_date >= '". $start_date ."' and start_date < '". $next_month ."') as total_audits,
            (select count(distinct left(a.audit_date, locate(' ', a.audit_date, 1)-1)) from audits a where audited_by_id = u.id and 
            audit_date >= '". $start_date ."' and audit_date < '". $next_month ."') as present_days,
            (select count(ad.id) from audits ad where ad.audited_by_id = u.id and 
            ad.audit_date >= '". $start_date ."' and ad.audit_date < '". $next_month ."') as audits,
            (select count(aud.id) from audits aud where aud.audited_by_id = u.id and 
            aud.audit_date >= '". $start_date ."' and aud.audit_date < '". $next_month ."' and aud.qc_date IS NOT NULL) as qc_audits,

            (select sum(qt.qa_target) from qa_targets qt where qt.qa_id = u.id and qt.deleted != 1 and 
            qt.target_month = '". $target_month ."') as qa_target,

            (select count(r.id) from rebuttals r inner join audits ac on r.audit_id = ac.id where ac.audited_by_id = u.id and r.status = 1 and
            r.created_at like '". $month ."%') as rebuttal_accept,

            (select sum(tr.avg_score) from training_pkt_auditors tr where tr.qa_id = u.id and tr.deleted = 0 and
            tr.pkt_month like '". $month ."%') as training_pkt

            from users u where u.reporting_user_id = ". Auth::user()->id. " order by audits");
            
            /* $data = DB::select("
            select ap.*, u.name from auditor_performence_reports ap inner join users u on ap.auditor_id = u.id where u.reporting_user_id = ". Auth::user()->id. " order by u.name");
 */
       /*  echo "<pre>";
        print_r($data);
        dd(); */

        $three_psn = DB::select("
        select u.*, (select sum(ap.total_spend_time) from auditor_performence_reports ap where ap.auditor_id = u.id and 
        start_date >= '". $start_date ."' and start_date < '". $next_month ."'
        ) as total_time,
        (select sum(ap.audit_done) from auditor_performence_reports ap where auditor_id = u.id and 
        start_date >= '". $start_date ."' and start_date < '". $next_month ."') as total_audits,
        (select count(distinct left(a.audit_date, locate(' ', a.audit_date, 1)-1)) from audits a where audited_by_id = u.id and 
        audit_date >= '". $start_date ."' and audit_date < '". $next_month ."') as present_days,
        (select count(ad.id) from audits ad where ad.audited_by_id = u.id and 
        ad.audit_date >= '". $start_date ."' and ad.audit_date < '". $next_month ."') as audits,
        (select count(aud.id) from audits aud where aud.audited_by_id = u.id and 
        aud.audit_date >= '". $start_date ."' and aud.audit_date < '". $next_month ."' and aud.qc_date IS NOT NULL) as qc_audits,

        (select sum(qt.qa_target) from qa_targets qt where qt.qa_id = u.id and qt.deleted != 1 and 
        qt.target_month = '". $target_month ."') as qa_target,

        (select count(r.id) from rebuttals r inner join audits ac on r.audit_id = ac.id where ac.audited_by_id = u.id and r.status = 1 and
        r.created_at like '". $month ."%') as rebuttal_accept,

        (select sum(tr.avg_score) from training_pkt_auditors tr where tr.qa_id = u.id and tr.deleted = 0 and
        tr.pkt_month like '". $month ."%') as training_pkt

        from users u where u.reporting_user_id = ". Auth::user()->id. " order by audits");

        return view('reports.qa_performance_matrix',compact('data'));
        
    }

    public function search_qtl_report(Request $request)
    {
        //$data = Target::where('audit_cycle_id',$request->audit_cycle_id)->where('circle_name',$request->circle)->count();
        
        if($request->target_month){

            $date = $request->target_month;
          
        }
        else{
            $date = date("m/Y");
        }
/*   echo $date;
            dd(); */
        
        $date_array = explode('/', $date);
        
        $compare_date = $date_array[1]. '-'. $date_array[0];
      

        $data = DB::select("
        select u.*, (select count(a.id) from audits a inner join users q on a.audited_by_id = q.id where q.reporting_user_id = t.qtl_user_id and a.audit_date like '".$compare_date."%') as total_sum,
        (select count(us.id) from users us where us.reporting_user_id = t.qtl_user_id ) as auditor_count,
        (select count(distinct left(at.audit_date, locate(' ', at.audit_date, 1)-1)) from audits at inner join users ur
        on at.audited_by_id = ur.id where ur.reporting_user_id = u.id and 
        at.audit_date like '".$compare_date."%') as present_days,

        (select sum(qt.qa_target) from qa_targets qt inner join users qa on qt.qa_id = qa.id where qa.reporting_user_id = t.qtl_user_id and qt.target_month like '".$date."%') as total_target
        from clients_qtls t 
        inner join users u on t.qtl_user_id = u.id
        inner join client_admins c on t.client_id = c.client_id
        where c.user_id = ". Auth::user()->id." order by u.id");
       
       // echo Auth::user()->id;
       /*  echo "<pre>";
        print_r($data);
        dd(); */
        return view('reports.qtl_report',compact('data', 'date'));
        
    }
}
