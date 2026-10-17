<?php

namespace App\Http\Controllers;
ini_set('memory_limit', '-1');
use App\AuditAlertBox;
use App\Calibration;
use App\CalibrationParameterResult;
use App\CalibrationResult;
use App\Calibrator;
use App\Mail\CalibrationRequest;
use App\Notifications\CalibrationRequestNoti;
use App\Partner;
use App\QmSheet;
use App\QmSheetParameter;
use App\RawData;
use App\ReasonType;
use App\TypeBScoringOption;
use App\User;
use Auth;
use Crypt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Notification;
use Storage;
use Validator;

class CalibrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Calibration::orderby('due_date','DESC')->get();
        return view('calibration.list',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('calibration.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'title' => 'required',
            'client_id' => 'required',
            'process_id' => 'required',
            'qm_sheet_id' => 'required',
            'master_calibrator' => 'email|required',
            'due_date' => 'required',
        ]);

        if($validator->fails())
        {
            return redirect('calibration/create')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            $new_calib = new Calibration;
            $new_calib->company_id = $request->company_id;
            $new_calib->title = $request->title;
            $new_calib->client_id = $request->client_id;
            $new_calib->process_id = $request->process_id;
            $new_calib->qm_sheet_id = $request->qm_sheet_id;
            $new_calib->due_date = date_to_db($request->due_date);
            $new_calib->qm_sheet_id = $request->qm_sheet_id;
            $new_calib->created_by = Auth::user()->id;

            if($request->attachment)
            {
                $name = date('mdYHis') . uniqid();
                $ext = $request->attachment->getClientOriginalExtension();
                $name = $name.".".$ext;
                Storage::put("company/_".Auth::user()->company_id."/calibration/".$name, $request->attachment->getClientOriginalName());

                $new_calib->attachment = $name;
            }else
            {
                $new_calib->attachment = null;
            }

            $new_calib->save();
            if($new_calib->id)
            {
                //master calibrator
                $new_calbrt = new Calibrator;
                $new_calbrt->calibration_id = $new_calib->id;
                $new_calbrt->email = $request->master_calibrator;

                $user_data = User::where('email',$request->master_calibrator)->first();
                if($user_data)
                {
                    $new_calbrt->user_id = $user_data->id;
                    Notification::send($user_data, new CalibrationRequestNoti(['upper_text'=>"Calibration requested by ".Auth::user()->name,'lower_text'=>$request->due_date." is due date."]));

                }
                else
                {
                    $new_calbrt->user_id = null;
                }

                $new_calbrt->is_master = 1;

                $new_calbrt->save();


                $url = url('calibrate/'.Crypt::encrypt($new_calbrt->id));
                Mail::to($request->master_calibrator)->send(new CalibrationRequest(['company_name'=>Auth::user()->company->name,
                                                                        'client_name'=>$new_calib->client->name,
                                                                        'process_name'=>$new_calib->process->name,
                                                                        'due_date'=>$request->due_date,
                                                                        'as'=>"Master Calibrator",
                                                                        'url'=>$url]));


                foreach ($request->calibrator as $key => $value) {
                    $new_calbrt = new Calibrator;
                    $new_calbrt->calibration_id = $new_calib->id;
                    $new_calbrt->email = $value['email'];

                    $user_data = User::where('email',$value['email'])->first();
                    if($user_data)
                    {
                        $new_calbrt->user_id = $user_data->id;
                        Notification::send($user_data, new CalibrationRequestNoti(['upper_text'=>"Calibration requested by ".Auth::user()->name,'lower_text'=>$request->due_date." is due date."]));
                    }
                    else
                    {
                        $new_calbrt->user_id = null;
                    }

                    $new_calbrt->save();

                    $url = url('calibrate/'.Crypt::encrypt($new_calbrt->id));


                    Mail::to($value['email'])->send(new CalibrationRequest(['company_name'=>Auth::user()->company->company_name,
                                                                        'client_name'=>$new_calib->client->name,
                                                                        'process_name'=>$new_calib->process->name,
                                                                        'due_date'=>$request->due_date,
                                                                        'as'=>"Calibrator",
                                                                        'url'=>$url]));
                }
            }

        }
        return redirect('calibration')->with('success', 'calibration initiated successfully'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Calibration::with('calibrator')->find(Crypt::decrypt($id));
        $temp= $data->calibrator;
        $mc = $temp->where('is_master',1);
        return view('calibration.edit',compact('data','mc'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'due_date' => 'required',
        ]);

        if($validator->fails())
        {
            return redirect('calibration/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            $new_calib =  Calibration::find(Crypt::decrypt($id));
            $new_calib->title = $request->title;
            $new_calib->due_date = date_to_db($request->due_date);

            if($request->attachment)
            {
                $name = date('mdYHis') . uniqid();
                $ext = $request->attachment->getClientOriginalExtension();
                $name = $name.".".$ext;
                Storage::put("company/_".Auth::user()->company_id."/calibration/".$name, $request->attachment->getClientOriginalName());

                $new_calib->attachment = $name;
            }else
            {
                //$new_calib->attachment = null;
            }

            $new_calib->save();
            if($new_calib->id)
            {

                foreach ($request->calibrator as $key => $value) {
                    if(isset($value['email']))
                    {
                    $new_calbrt = new Calibrator;
                    $new_calbrt->calibration_id = $new_calib->id;
                    $new_calbrt->email = $value['email'];

                    $user_data = User::where('email',$value['email'])->first();
                    if($user_data)
                    {
                        $new_calbrt->user_id = $user_data->id;
                        Notification::send($user_data, new CalibrationRequestNoti(['upper_text'=>"Calibration requested by ".Auth::user()->name,'lower_text'=>$request->due_date." is due date."]));
                    }
                    else
                    {
                        $new_calbrt->user_id = null;
                    }

                    $new_calbrt->save();

                    $url = url('calibrate/'.Crypt::encrypt($new_calbrt->id));


                    Mail::to($value['email'])->send(new CalibrationRequest(['company_name'=>Auth::user()->company->company_name,
                                                                        'client_name'=>$new_calib->client->name,
                                                                        'process_name'=>$new_calib->process->name,
                                                                        'due_date'=>$request->due_date,
                                                                        'as'=>"Calibrator",
                                                                        'url'=>$url]));
                    }
                }
            }

        }
        return redirect('calibration')->with('success', 'calibration updated successfully'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Calibration::find(Crypt::decrypt($id))->delete();
        Calibrator::where('calibration_id',Crypt::decrypt($id))->delete();
        return redirect('calibration')->with('success', 'Calibration instance deleted successfully.');    
    }

    public function my_request()
    {
        $data = Calibrator::orderBy('id','DESC')->get();
        return view('calibration.request',compact('data'));
    }
    public function delete_calibrator($cid)
    {
        Calibrator::find($cid)->delete();
        return "true";
    }

    public function render_calibration_sheet($calibrator_id)
    {
        $calibrator_data = Calibrator::with('calibration')->find(Crypt::decrypt($calibrator_id));
        if($calibrator_data)
        {
            $data = QmSheet::with(['client'])->find($calibrator_data->calibration->qm_sheet_id);
            $qm_sheet_id = Crypt::encrypt($calibrator_data->calibration->qm_sheet_id);
            $calibration_data = Calibration::find($calibrator_data->calibration_id);

            return view('calibration.render_calib_sheet',compact('qm_sheet_id','data','calibrator_id','calibrator_data','calibration_data'));
        }else
        {
            abort(404);
        }
        
    }

    public function get_qm_sheet_details_for_calibration($qm_sheet_id,$calibration_id)
    {

        //calibration data
            $calibration = Calibration::with('calibrator')->find($calibration_id);

            $calib_data['title'] = $calibration->title;
            $calib_data['process'] = $calibration->process->name;
            $calib_data['due_date'] = $calibration->due_date;

            $temp= $calibration->calibrator;
            $mc = $temp->where('is_master',1);
            $calib_data['master_calibrator'] = $mc[0]->email;

            $calib_data['total_calibrator'] = $calibration->calibrator->count();
            if($calibration->attachment){
                $path_name = str_replace("https://qmtool.s3.ap-south-1.amazonaws.com/", "","company/_".$calibration->company_id."/calibration/".$calibration->attachment);
                $url = Storage::disk('s3')->temporaryUrl(
                    $path_name,
                    now()->addMinutes(8640) //Minutes for which the signature will stay valid
                );
                $calib_data['attachment'] = $url;
            } else {
                $calib_data['attachment'] = Storage::url("company/_".$calibration->company_id."/calibration/".$calibration->attachment);
            }
            // $calib_data['attachment'] = Storage::url("company/_".$calibration->company_id."/calibration/".$calibration->attachment);

            $final_data['calibration_data'] = $calib_data;


            //calibration data

            $data = QmSheet::with(['client','process','parameter','parameter.qm_sheet_sub_parameter'])->find(Crypt::decrypt($qm_sheet_id));

        $partners_list = [];
        $final_data['partners_list'] = $partners_list;

        $final_data['my_alloted_call_list'] = [];

        $final_data['sheet_details'] = $data->toArray();
        //$final_data['type_b_scoring_option'] = TypeBScoringOption::all();
        $all_type_b_scoring_option = TypeBScoringOption::where('company_id',$data->company_id)->pluck('name','id');

        //process data
        $pds = [];
        foreach ($data->parameter as $key => $value_p) {
            $pds[$value_p->id]['name'] = $value_p->parameter;
            $pds[$value_p->id]['is_non_scoring']=$value_p->is_non_scoring;
            $total_parameter_weight = 0;
            $pds[$value_p->id]['is_fatal']=0;
            $pds[$value_p->id]['score']=0;
            $pds[$value_p->id]['score_with_fatal']=0;
            $pds[$value_p->id]['score_without_fatal']=0;
            $pds[$value_p->id]['temp_total_weightage']=0;
            foreach ($value_p->qm_sheet_sub_parameter as $key => $value_s) {

                $pds[$value_p->id]['subs'][$value_s->id]['name'] = $value_s->sub_parameter;
                $pds[$value_p->id]['subs'][$value_s->id]['details'] = $value_s->details;
                $pds[$value_p->id]['subs'][$value_s->id]['is_fatal']=0;
                $pds[$value_p->id]['subs'][$value_s->id]['is_non_scoring'] = $value_p->is_non_scoring;
                $pds[$value_p->id]['subs'][$value_s->id]['failure_reason'] = '';
                $pds[$value_p->id]['subs'][$value_s->id]['remark'] = '';
                $pds[$value_p->id]['subs'][$value_s->id]['orignal_weight'] = $value_s->weight;
                $pds[$value_p->id]['subs'][$value_s->id]['temp_weight'] = 0;
                $scoring_opts = [];
                if($value_p->is_non_scoring)
                {   
                    //total weight
                    $total_parameter_weight +=0;
                    if($value_s->non_scoring_option_group)
                    {                  
                        foreach (all_non_scoring_obs_options($value_s->non_scoring_option_group) as $key_ns => $value_ns) {
                                                $scoring_opts[$value_p->id."_".$value_s->id."_".$value_ns."_".$key_ns."_0"] = ["key"=>$value_p->id."_".$value_s->id."_".$value_ns."_".$key_ns."_0","value"=>$value_ns,"alert_box"=>null];
                                        }
                    }else
                    {
                        $scoring_opts=null;
                    }

                    
                }else
                {
                    //total weight
                    $total_parameter_weight +=$value_s->weight;
                    //total weight
                    $alert_box=null;

                    $all_reason_type_fail=null;
                    $all_reason_type_cric=null;
                    $all_reason_type_pwd=null;

                    if($value_s->pass)
                    { 
                        if($value_s->pass_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->pass_alert_box_id);
                        else
                            $alert_box = null;


                        $scoring_opts[$value_p->id."_".$value_s->id."_".$value_s->weight."_1_0"] = ["key"=>$value_p->id."_".$value_s->id."_".$value_s->weight.'_1_0',"value"=>"Pass","alert_box"=>$alert_box];
                   }

                    if($value_s->fail)
                     {
                        if($value_s->fail_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->fail_alert_box_id);
                        else
                            $alert_box = null;

                        if($value_s->fail_reason_types)
                        {
                            $temp_index_f = $value_p->id."_".$value_s->id."_"."0"."_2_1";
                            $temp_r_fail = ReasonType::find(explode(',',$value_s->fail_reason_types))->pluck('name','id');
                            foreach ($temp_r_fail as $keycc => $valuecc) {
                                $all_reason_type_fail[] = ["key"=>$value_p->id."_".$value_s->id."_".$keycc,"value"=>$valuecc]; 
                            }
                        }
                        else
                        {
                            $temp_index_f = $value_p->id."_".$value_s->id."_"."0"."_2_0";
                            $all_reason_type_fail = null;
                        }

                        $scoring_opts[$temp_index_f] = ["key"=>$temp_index_f,"value"=>"Fail","alert_box"=>$alert_box];
                    }

                    if($value_s->critical)
                     {
                        if($value_s->critical_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->critical_alert_box_id);
                        else
                            $alert_box = null;

                        if($value_s->critical_reason_types)
                        {
                            $temp_index_cri = $value_p->id."_".$value_s->id."_"."Critical"."_3_1";
                            $temp_cric = ReasonType::find(explode(',',$value_s->critical_reason_types))->pluck('name','id');
                            foreach ($temp_cric as $keycc => $valuecc) {
                                $all_reason_type_cric[] = ["key"=>$value_p->id."_".$value_s->id."_".$keycc,"value"=>$valuecc]; 
                            }
                        }
                        else
                        {
                            $temp_index_cri = $value_p->id."_".$value_s->id."_"."Critical"."_3_0";
                            $all_reason_type_cric = null;
                        }


                        $scoring_opts[$temp_index_cri] = ["key"=>$temp_index_cri,"value"=>"Critical","alert_box"=>$alert_box];
                    }

                    if($value_s->na)
                     {

                        if($value_s->na_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->na_alert_box_id);
                        else
                            $alert_box = null;


                        $scoring_opts[$value_p->id."_".$value_s->id."_"."N/A"."_4_0"] = ["key"=>$value_p->id."_".$value_s->id."_"."N/A"."_4_0","value"=>"N/A","alert_box"=>$alert_box];
                    }

                    if($value_s->pwd)
                     {  
                        if($value_s->pwd_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->pwd_alert_box_id);
                        else
                            $alert_box = null;

                        if($value_s->pwd_reason_types)
                        {
                            $temp_index_pwd = $value_p->id."_".$value_s->id."_".($value_s->weight/2)."_5_1";
                            $temp_pwd = ReasonType::find(explode(',',$value_s->pwd_reason_types))->pluck('name','id');
                            foreach ($temp_pwd as $keycc => $valuecc) {
                                $all_reason_type_pwd[] = ["key"=>$value_p->id."_".$value_s->id."_".$keycc,"value"=>$valuecc]; 
                            }
                        }
                        else
                        {
                            $temp_index_pwd = $value_p->id."_".$value_s->id."_".($value_s->weight/2)."_5_0";
                            $all_reason_type_pwd = null;
                        }


                        // $scoring_opts[$value_p->id."_".$value_s->id."_".($value_s->weight/2)."_5_0"] = ["key"=>$value_p->id."_".$value_s->id."_".($value_s->weight/2)."_5_0","value"=>"PWD","alert_box"=>$alert_box];

                        $scoring_opts[$temp_index_pwd] = ["key"=>$temp_index_pwd,"value"=>"PWD","alert_box"=>$alert_box];
                    }

                }


                $pds[$value_p->id]['subs'][$value_s->id]['options'] = $scoring_opts;
                $pds[$value_p->id]['subs'][$value_s->id]['score'] = 0;
                $pds[$value_p->id]['subs'][$value_s->id]['selected_options'] = null;
                $pds[$value_p->id]['subs'][$value_s->id]['selected_option_model'] = '';
                $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_fail'] = $all_reason_type_fail;
                $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_cric'] = $all_reason_type_cric;
                $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_pwd'] = $all_reason_type_pwd;
                $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type']=null;
                $pds[$value_p->id]['subs'][$value_s->id]['selected_reason_type']='';
                $pds[$value_p->id]['subs'][$value_s->id]['all_reasons']=null;
                $pds[$value_p->id]['subs'][$value_s->id]['selected_reason']='';

            }
            $pds[$value_p->id]['parameter_weight'] = $total_parameter_weight;
            
        }
        
        $final_data['simple_data'] = $pds;

        return response()->json(['status'=>200,'message'=>"Success",'data'=>$final_data,], 200);
        
    }
    public function store_calibration(Request $request)
    {
        $calibrator = Calibrator::find($request->calibrator_id);
        $calibrator->is_critical =  $request->submission_data[0]['is_critical'];
        $calibrator->without_fatal_score = $request->submission_data[0]['overall_score'];
        $calibrator->overall_summary = $request->submission_data[0]['overall_summary'];

        if($request->submission_data[0]['is_critical']==1)
            $calibrator->with_fatal_score = 0;
        else
            $calibrator->with_fatal_score = $request->submission_data[0]['overall_score'];

        $calibrator->status = 1;

        $calibrator->save();

        if($calibrator->id)
        {
            
               // store parameter wise data
                foreach ($request->parameters as $key => $value) {

                    $new_arb = new CalibrationParameterResult;
                    $new_arb->audit_id =  $calibrator->id;
                    $new_arb->parameter_id = $key;
                    $new_arb->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
                    $new_arb->orignal_weight = $value['parameter_weight'];
                    $new_arb->temp_weight = $value['temp_total_weightage'];
                    $new_arb->with_fatal_score = $value['score_with_fatal'];
                    $new_arb->without_fatal_score = $value['score_without_fatal'];

                    if($value['temp_total_weightage']!=0)
                    {
                        $new_arb->with_fatal_score_per = ($value['score_with_fatal'] / $value['temp_total_weightage'])*100;
                        $new_arb->without_fatal_score_pre = ($value['score_without_fatal'] / $value['temp_total_weightage'])*100;
                    }
                    $new_arb->is_critical = $value['is_fatal'];

                    $new_arb->save();

                    // store sub parameter wise data
                    foreach ($value['subs'] as $key_sb => $value_sb) {
                        if($value_sb['selected_option_model']!=''||$value_sb['selected_option_model']!=null);
                        {
                            $new_arc = new CalibrationResult;
                            $new_arc->audit_id =  $calibrator->id;
                            $new_arc->parameter_id = $key;
                            $new_arc->sub_parameter_id = $key_sb;
                            $new_arc->is_critical = $value_sb['is_fatal'];
                            $new_arc->is_non_scoring = $value_sb['is_non_scoring'];
                            $temp_selected_opt = explode("_",$value_sb['selected_option_model']);
                            
                            if(count($temp_selected_opt)==5)
                            {
                                $new_arc->selected_option = $temp_selected_opt[3];

                                if($temp_selected_opt[3]==2||$temp_selected_opt[3]==3)
                                {
                                    if(isset($value_sb['selected_reason_type'])==1&&$value_sb['selected_reason_type']!=''&&isset($value_sb['selected_reason'])==1&&$value_sb['selected_reason']!='')
                                    {
                                        $temp_selected_reason_type = explode("_",$value_sb['selected_reason_type']);
                                        $new_arc->reason_type_id = $temp_selected_reason_type[2];
                                        $new_arc->reason_id = $value_sb['selected_reason'];
                                    }
                                }

                            }
                            else
                            {
                                $new_arc->selected_option = 0;
                                $new_arc->reason_type_id = null;
                                $new_arc->reason_id = null;
                            }


                            $new_arc->score = $value_sb['score'];
                            $new_arc->after_audit_weight = $value_sb['temp_weight'];

                            
                            $new_arc->remark = $value_sb['remark'];
                            $new_arc->save();
                        }
                    }

                }
        }
        return response()->json(['status'=>200,'message'=>"Calibration saved successfully."], 200);

    }
    public function calibration_result($calibration_id)
    {
        $calibration = Calibration::with(['client','process','qm_sheet'])->find(Crypt::decrypt($calibration_id));
        $calibrator = Calibrator::where('calibration_id',$calibration->id)->orderBy('is_master','desc')->with(['calibration_parameter_result','calibration_results'])->get();

        $all_params = QmSheetParameter::where('qm_sheet_id',$calibration->qm_sheet_id)->where('is_non_scoring',0)->with('qm_sheet_sub_parameter')->get();

        foreach ($all_params as $key => $value) {

                $temp_param_data[$value->id] = [];
                $temp_param_data[$value->id]['name'] = $value->parameter;
                $temp_param_data[$value->id]['id'] = $value->id;
                $temp_param_data[$value->id]['with_fatal_score'] = 0;
                $temp_param_data[$value->id]['with_fatal_score_vary'] = 0;
                $temp_param_data[$value->id]['without_fatal_score'] = 0;
                $temp_param_data[$value->id]['without_fatal_score_vary'] = 0;

                $temp_param_data[$value->id]['subp']=[];
                $temp_subp_data=[];
                $temp_sum=[];

                foreach ($value->qm_sheet_sub_parameter as $keyb => $valueb) {
                    $temp_subp_data[$valueb->id] = [];
                    $temp_subp_data[$valueb->id]['name'] = $valueb->sub_parameter;
                    $temp_subp_data[$valueb->id]['id'] = $valueb->id;
                    $temp_subp_data[$valueb->id]['score'] = 0;
                    $temp_subp_data[$valueb->id]['score_vary'] = 0;
                }

                $temp_param_data[$value->id]['subp'] = $temp_subp_data;

        }

        //result
        $all_calibrator = [];
        foreach ($calibrator as $key => $value) {
            if($value->status)
                {
                    
                    $all_calibrator[$value->id]['email'] = $value->email;
                    $all_calibrator[$value->id]['data'] = $temp_param_data;
                    $all_calibrator[$value->id]['with_fatal_score'] = 0;
                    $all_calibrator[$value->id]['with_fatal_score_vary'] = 0;
                    $all_calibrator[$value->id]['without_fatal_score'] = 0;
                    $all_calibrator[$value->id]['without_fatal_score_vary'] = 0;
                }
        }

        

        foreach ($calibrator as $key => $value) {
            $all_calibrator[$value->id]['with_fatal_score'] = $value->with_fatal_score;
            $all_calibrator[$value->id]['without_fatal_score'] = $value->without_fatal_score;
                
            foreach ($all_params as $keyb => $valueb) {
                
                $all_calibrator[$value->id]['data'][$valueb->id]['with_fatal_score'] = $value->calibration_parameter_result->where('parameter_id',$valueb->id)->sum('with_fatal_score_per');
                $all_calibrator[$value->id]['data'][$valueb->id]['without_fatal_score'] = $value->calibration_parameter_result->where('parameter_id',$valueb->id)->sum('without_fatal_score_per');
                
                foreach ($valueb->qm_sheet_sub_parameter as $keyc => $valuec) {
                    $all_calibrator[$value->id]['data'][$valueb->id]['subp'][$valuec->id]['score'] = $value->calibration_results->where('parameter_id',$valueb->id)->where('sub_parameter_id',$valuec->id)->sum('score');
                }
            }
        }

        $mc_key = array_key_first($all_calibrator);
        foreach ($calibrator as $key => $value) {

            if($value->id!=$mc_key)
            {
                $all_calibrator[$value->id]['with_fatal_score_vary'] = ($all_calibrator[$mc_key]['with_fatal_score']-$all_calibrator[$value->id]['with_fatal_score']);
                $all_calibrator[$value->id]['without_fatal_score_vary'] = ($all_calibrator[$mc_key]['without_fatal_score']-$all_calibrator[$value->id]['without_fatal_score']);
                
            foreach ($all_params as $keyb => $valueb) {
                
                $all_calibrator[$value->id]['data'][$valueb->id]['with_fatal_score_vary'] = ($all_calibrator[$mc_key]['data'][$valueb->id]['with_fatal_score']-$all_calibrator[$value->id]['data'][$valueb->id]['with_fatal_score']);

                $all_calibrator[$value->id]['data'][$valueb->id]['without_fatal_score_vary'] = ($all_calibrator[$mc_key]['data'][$valueb->id]['without_fatal_score'] - $all_calibrator[$value->id]['data'][$valueb->id]['without_fatal_score']);
                
                foreach ($valueb->qm_sheet_sub_parameter as $keyc => $valuec) {
                    $all_calibrator[$value->id]['data'][$valueb->id]['subp'][$valuec->id]['score_vary'] = ($all_calibrator[$mc_key]['data'][$valueb->id]['subp'][$valuec->id]['score']-$all_calibrator[$value->id]['data'][$valueb->id]['subp'][$valuec->id]['score']);
                }
            }    

            }

        }
        

        // dd($all_calibrator);

        return view('calibration.calibration_result',compact('calibration','all_calibrator','all_params','calibrator'));
    }


}
