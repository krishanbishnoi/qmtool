<?php

namespace App\Http\Controllers;
ini_set('memory_limit', '-1');
use Illuminate\Http\Request;

use App\Client;
use App\Process;
use App\Partner;
use App\Auditcycle;
use App\RawData;
use App\User;
use Crypt;
use App\Region;

use Auth;
use Illuminate\Support\Facades\DB;
use App\Audit;

class TargetController extends Controller
{
    public function index(){
        
        return view('target.set_target');
    }




    public function get_client($company_id, $process_owner_id){

    	if(Auth::user()->id == 42 || Auth::user()->id == 198) {
    		$data = Client::select('id','name')->where('company_id',$company_id)->get();
    	} else {
    		$data = Client::select('id','name')->where('company_id',$company_id)->where('process_owner_id',$process_owner_id)->get();
    	}
       
      
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

        /* echo "hiiii";
        dd(); */
        $data = Partner::select('id','name')->where('company_id',$company_id)->where('client_id',$client_id)->get();
      
        $html='<option value="">Select Partner</option>';
        foreach($data as $val) {
            $html.='<option value='.$val->id.'>'.$val->name.'</option>';
        }
        return $html; 
    }

    public function get_location($client_id, $company_id){

        /* echo "hiiii";
        dd(); */
        $data = Region::select('id','name')->where('company_id',$company_id)->get();
      
        $html='<option value="">Select Location</option>';
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
            $html.="<option value='".$val->lob."'>".$val->lob."</option>";
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
        //echo "fun in";
        /* print_r($request->target);
        dd(); */

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

    public function location(){
        
        $all_location = Region::where('company_id',1)->pluck('name','id')->toArray();
        $data = RawData::where('client_id', 9)->get();

        foreach($data as $value){
            if(array_search($value->location,$all_location) != null){
               /*  $product_sub = DB::select("
                    select r1.name,
                    (select count(a.id) from audits a inner join users u on a.audited_by_id = u.id where a.rca1_id = r1.id 
                    and u.reporting_user_id = ". Auth::user()->id. " and a.audit_date like '" .$month. "%') as rca_lavel_count
                    from rca1s r1 inner join rca_modes m on r1.mode_id = m.id 
                    where m.name like 'Product%'"); */

                $users = DB::table('raw_data')->where('id', $value->id)->update([
                'partner_location_id' => array_search($value->location,$all_location)
                ]);
            }
        }

        
    }

    public function change_location(){

        $data = Audit:: select('audits.*','raw_data.call_id','raw_data.agent_name','raw_data.location')
        ->join('raw_data', 'audits.raw_data_id', '=', 'raw_data.id')
        ->where('audit_date', 'like', '2020-%')
        ->raw('raw_data.client_id',9)
        ->raw('raw_data.partner_id',32)
        ->get();
       /*  echo "<pre>";
        print_r($data);
        dd(); */
       /*  print_r($data);
        dd(); */
        return view('change_location',compact('data'));
      }
      public function edit_to_loc($audit_id, $raw_id, $partner_id, $location){

        $partner = Partner::
        where('id',$partner_id)
        
        ->first();
       /*  echo "<pre>";
        print_r($data);
        dd(); */
       /*  print_r($data);
        dd(); */
        return view('edit_location',compact('partner','audit_id','raw_id','location'));
      }

      public function update_loc_par (Request $request){
        $location = Region::where('id', $request->location_id)->first();


        $raw = RawData::find($request->raw_id);
        $raw->partner_id = $request->partner_id;
        $raw->partner_location_id = $request->location_id;
        $raw->location = $location->name;
        $raw->save();

        $audit = Audit::find($request->audit_id);
        $audit->partner_id = $request->partner_id;
        
        $audit->save();
       
        echo "updated successfuly";
        dd();
        return view('edit_location',compact('partner','audit_id','raw_id','location'));
      }

      public function change_location_agent(Request $request){
       
    
        if($request->process_id){
           
           
            $month = $request->month. "%";
            $process_id = $request->process_id;
            $agent = $request->agent;
            $location = $request->location_id;
            $after_post = 1;
            $data = Audit:: select('audits.*','raw_data.call_id','raw_data.agent_name','raw_data.location')
            ->join('raw_data', 'audits.raw_data_id', '=', 'raw_data.id')
            ->where('audits.audit_date', 'like', $month)
            ->where('raw_data.client_id',9)
            ->where('raw_data.process_id',$request->process_id)
            ->where('raw_data.agent_name',$request->agent)
            ->where('raw_data.partner_location_id',$request->location_id)

            ->where('raw_data.partner_id',32)
            ->get();

          /*  print_r($data);
           dd(); */
        }else {
            $month = $request->month. "%";
            $process_id = "";
            $agent = "";
            $location = "";
            $after_post = 1;
            $data = Audit:: select('audits.*','raw_data.call_id','raw_data.agent_name','raw_data.location')
            ->join('raw_data', 'audits.raw_data_id', '=', 'raw_data.id')
            ->where('audit_date', 'like', '2020-%')
            ->where('raw_data.client_id',9)
            ->where('raw_data.partner_id',32)
            ->get();
        }
       
    
        return view('agent_wise',compact('data','month','process_id','agent','location','after_post'));
      }

      public function new_loc_agent($month, $process_id, $agent, $location){

        
    
        return view('update_agent_wise',compact('month','process_id','agent','location'));
      }

      public function update_loc_par_agent (Request $request){

        $location = Region::where('id', $request->location_id)->first();


        $month = $request->month;
            $process_id = $request->process_id;
            $agent = $request->agent;
            

            
            
            $data = Audit:: select('audits.*','raw_data.call_id','raw_data.agent_name','raw_data.location')
            ->join('raw_data', 'audits.raw_data_id', '=', 'raw_data.id')
            ->where('audits.audit_date', 'like', $request->month)
            ->where('raw_data.client_id',9)
            ->where('raw_data.process_id',$request->process_id)
            ->where('raw_data.agent_name',$request->agent)
            ->where('raw_data.partner_location_id',$request->location)

            ->where('raw_data.partner_id',32)
            ->get();
           
            foreach($data as $value){
               
                $raw = RawData::find($value->raw_data_id);
                $raw->partner_id = $request->partner_id;
                $raw->partner_location_id = $request->location_id;
                $raw->location = $location->name;
                $raw->save();
        
                $audit = Audit::find($value->id);
                $audit->partner_id = $request->partner_id;
                
                $audit->save();
            }
            
        
       
        echo "updated successfuly";
        dd();
        return view('edit_location',compact('partner','audit_id','raw_id','location'));
      }

}
