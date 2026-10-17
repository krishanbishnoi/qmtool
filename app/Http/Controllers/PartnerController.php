<?php

namespace App\Http\Controllers;

ini_set('memory_limit', '-1');

use App\Audit;
use App\Client;
use App\Mail\UserCreated;
use App\Partner;
use App\PartnerLocation;
use App\AuditFormFields;
use App\PartnersProcess;
use App\PartnersProcessSpoc;
use App\Process;
use App\QmSheet;
use App\QmSheetParameter;
use App\RawData;
use App\Rebuttal;
use App\Region;
use App\Role;
use App\User;
use Auth;
use Crypt;
use Illuminate\Http\Request;
use Mail;
use Notification;
use Storage;
use Validator;
use DB;



class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($client_id)
    {
        $client_data = Client::find(Crypt::decrypt($client_id));
        $all_regions = Region::where('company_id', Auth::user()->company_id)->pluck('name', 'id');
        $all_process = Process::where('company_id', Auth::user()->company_id)->pluck('name', 'id');
        $all_partner_admin = User::where('company_id', Auth::user()->company_id)->whereHas('roles', function ($query) {
            $query->where('name', '=', 'partner-admin');
        })->pluck('name', 'id');
        return view('partners.create', compact('client_data', 'all_regions', 'all_process', 'all_partner_admin'));
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
            'client_id' => 'required',
            'partner_name' => 'required',
            'admin_user_id' => 'required',
            'partner_process_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('client/' . $request->client_id . '/create/partner')
                ->withErrors($validator)
                ->withInput();
        } else {
            // //create user
            // $temp_roles = '';
            // $new_rec = new User;
            // $new_rec->company_id = Auth::user()->company_id;
            // $new_rec->name = $request->user_name;
            // $new_rec->email = $request->email;
            // $new_rec->mobile = $request->mobile;
            // $new_rec->password = bcrypt($request->password);
            // $new_rec->status=1;
            // $new_rec->save();
            // // attach role
            //     $role  = Role::where('name','partner-admin')->first();
            //     $temp_roles .= $role->display_name;
            //     $new_rec->attachRole($role);
            //create user


            /* if(true) */
            $check_multiple = Partner::where('contact_email', $request->contact_email)->get();

            if (sizeof($check_multiple) == 0) {
                $new_rc = new Partner;
                $new_rc->company_id = Auth::user()->company_id;
                $new_rc->client_id = Crypt::decrypt($request->client_id);
                $new_rc->name = $request->partner_name;
                $new_rc->details = $request->details;
                $new_rc->admin_user_id = $request->admin_user_id;
                $new_rc->contact_email = $request->contact_email;

                if ($request->logo) {
                    $request->logo->store("company/_" . Auth::user()->company_id . '/client');
                    $new_rc->logo = $request->logo->hashName();
                }

                $new_rc->save();

                if ($new_rc->id) {
                    foreach ($request->partner_process_id as $key => $value) {
                        $new_rec_cq = new PartnersProcess;
                        $new_rec_cq->partner_id = $new_rc->id;
                        $new_rec_cq->process_id = $value;
                        $new_rec_cq->save();
                    }

                    foreach ($request->locations as $key => $value) {
                        $new_rec_cq = new PartnerLocation;
                        $new_rec_cq->partner_id = $new_rc->id;
                        $new_rec_cq->location_id = $value['location_id'];
                        $new_rec_cq->save();
                    }


                    // $mail_data = ['name'=>$request->user_name,'roles'=>"Partner",'url'=>url('/login'),'company_name'=>Auth::user()->company->company_name,'email'=>$request->email,'password'=>$request->password];
                    // Mail::to($new_rec)->send(new UserCreated($mail_data));
                }
            } else {
                return redirect('client/' . $request->client_id . '/partner')->with('warning', 'Email id already exist with different client, Please select different email id');
            }
        }
        return redirect('client/' . $request->client_id . '/partner')->with('success', 'Partner created successfully');
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
        $data = Partner::with(['partner_process', 'partner_location'])->find(Crypt::decrypt($id));
        $client_data = Client::find($data->client_id);
        $all_regions = Region::where('company_id', Auth::user()->company_id)->pluck('name', 'id');
        $all_process = Process::where('company_id', Auth::user()->company_id)->pluck('name', 'id');
        $all_partner_admin = User::where('company_id', Auth::user()->company_id)->whereHas('roles', function ($query) {
            $query->where('name', '=', 'partner-admin');
        })->pluck('name', 'id');

        //dd($data->partner_process);
        if (count($data->partner_process))
            $selected_partner_process = $data->partner_process->pluck('process_id');
        else
            $selected_partner_process = [];

        return view('partners.edit', compact('data', 'client_data', 'all_regions', 'all_process', 'selected_partner_process', 'all_partner_admin'));
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

        //dd($request->locations);
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'client_id' => 'required',
            'partner_name' => 'required',
            'admin_user_id' => 'required',
            'partner_process_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('partner/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            $new_rc = Partner::find(Crypt::decrypt($id));
            $new_rc->name = $request->partner_name;
            $new_rc->details = $request->details;
            $new_rc->admin_user_id = $request->admin_user_id;
            $new_rc->contact_email = $request->contact_email;

            if ($request->logo) {
                Storage::delete("company/_" . Auth::user()->company_id . "/client/" . $new_rc->logo);
                $request->logo->store("company/_" . Auth::user()->company_id . '/client');
                $new_rc->logo = $request->logo->hashName();
            }

            $new_rc->save();

            PartnersProcess::where('partner_id', $new_rc->id)->delete();
            foreach ($request->partner_process_id as $key => $value) {
                $new_rec_cq = new PartnersProcess;
                $new_rec_cq->partner_id = $new_rc->id;
                $new_rec_cq->process_id = $value;
                $new_rec_cq->save();
            }

            PartnerLocation::where('partner_id', $new_rc->id)->delete();
            foreach ($request->locations as $key => $value) {
                if ($value['location_id']) {
                    $new_rec_cq = new PartnerLocation;
                    $new_rec_cq->partner_id = $new_rc->id;
                    $new_rec_cq->location_id = $value['location_id'];
                    $new_rec_cq->save();
                }
            }
        }
        return redirect('client/' . $request->client_id . '/partner')->with('success', 'Partner updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //dd(Crypt::decrypt($id));
        PartnersProcess::where('partner_id', Crypt::decrypt($id))->delete();
        PartnersProcessSpoc::where('partner_id', Crypt::decrypt($id))->delete();
        $data = Partner::find(Crypt::decrypt($id));
        $client_id = $data->client_id;
        Storage::delete("company/_" . Auth::user()->company_id . "/client/" . $data->logo);
        $data->delete();
        return redirect('client/' . Crypt::encrypt($client_id) . '/partner')->with('success', 'Partner deleted successfully');
    }
    public function add_spocs($partner_id)
    {
        $partner_data = Partner::with(['partner_process', 'partner_process.process'])->find(Crypt::decrypt($partner_id));

        $selected_processes = [];
        foreach ($partner_data->partner_process as $key => $value) {
            $selected_processes[$value->id] = $value->process->name;
        }
        $all_partner_spocs = User::where('company_id', Auth::user()->company_id)->whereHas('roles', function ($query) {
            $query->where('name', '=', 'partner-training-head')->orWhere('name', '=', 'partner-operation-head')->orWhere('name', '=', 'partner-quality-head');
        })->pluck('name', 'id');

        $all_attached_spocs = PartnersProcessSpoc::with(['user', 'process'])->where('partner_id', $partner_data->id)->get();

        $all_partner_locations = [];
        $all_partner_locations_temp = PartnerLocation::where('partner_id', $partner_data->id)->with('location_detail')->get();
        foreach ($all_partner_locations_temp as $key => $value) {
            $all_partner_locations[$value->location_id] = $value->location_detail->name;
        }

        return view('partners.manage_spocs', compact('partner_data', 'selected_processes', 'all_partner_spocs', 'all_attached_spocs', 'all_partner_locations'));
    }
    public function store_spocs(Request $request)
    {
        $pp_data = PartnersProcess::find($request->pp_id);

        $new = new PartnersProcessSpoc;
        $new->pp_id = $request->pp_id;
        $new->partner_id = Crypt::decrypt($request->partner_id);
        $new->process_id = $pp_data->process_id;
        $new->user_id = $request->user_id;
        $new->partner_location_id = $request->partner_location_id;
        $new->save();

        return redirect('partner/' . $request->partner_id . '/add/spocs')->with('success', 'Partner spocs updated successfully');
    }
    public function partner_process_spoc_delete($id)
    {
        $data = PartnersProcessSpoc::find(Crypt::decrypt($id));
        $partner_id = $data->partner_id;
        $data->delete();
        return redirect('partner/' . Crypt::encrypt($partner_id) . '/add/spocs')->with('success', 'Partner spocs deleted successfully');
    }
    public function audit_completed(Request $request)
    {

        $start_date = date_to_db($request->start_date);
        $end_date = date_to_db($request->end_date);
        $selectedProcess = $request->process_id ?? 'all';
        $partner_id = null;
        $process_id = null;
        $requiredFields = [];
        $fieldMap = [];
        $partner_location_id = null;
        if (Auth::user()->parent_client) {
            $client_id = Auth::user()->parent_client;
            $all_cluster_processes = get_helper_cluster_processes(Auth::user()->id);
            $all_cluster_partners = get_helper_cluster_partners(Auth::user()->id);
            $all_cluster_locations = get_helper_cluster_locations(Auth::user()->id);
            // $all_cluster_lobs = get_helper_cluster_lobs(Auth::user()->id);
        }
        if (Auth::user()->hasRole('partner-admin') ||  Auth::user()->hasRole('partner-qa')) {

            $all_cluster_partners = get_helper_cluster_partners(Auth::user()->id);
            if (sizeof($all_cluster_partners) > 0) {
                $partner_id = $all_cluster_partners[0];
            } else {
                if (isset(Auth::user()->partner_admin_detail->id)) {
                    $partner_id = Auth::user()->partner_admin_detail->id;
                } else {
                    return redirect('error')->with('warning', 'Please contact to admin partner detail not mapped.');
                }
            }
            $process_id = '%';
            $partner_location_id = '%';
        } else {
            $spoc_data = Auth::user()->spoc_detail;
            if (gettype($spoc_data) == "NULL") {
                $partner_id = '';
                $process_id = "%";
                $partner_location_id = '';
            } else {
                $partner_id = $spoc_data->partner_id;
                $process_id = "%";
                $partner_location_id = $spoc_data->partner_location_id;
            }
        }
        $current_date_time = date('Y-m-d H:i:s');
        if (Auth::user()->parent_client) {

            if (Auth::user()->parent_client == 23) {
                $isSpecificProcess = in_array(189, $all_cluster_processes);
                $partner_id_not_hundred_score = in_array(153, $all_cluster_partners);
            

                $baseQuery = RawData::where('status', 1)
                    ->whereIn('partner_id', $all_cluster_partners)
                    ->whereIn('partner_location_id', $all_cluster_locations)
                    ->whereIn('process_id', $all_cluster_processes)
                    ->whereHas('audit', function ($query) use ($start_date, $end_date) {
                        $query->whereDate('audit_date', '>=', $start_date)
                            ->whereDate('audit_date', '<=', $end_date)
                            ->where('deleted', 0);
                    });
                $baseQuery->whereHas('audit', function ($query) use ($current_date_time) {
                    $query->where('feedback_shared_status', '!=', 1);
                });
                if ($selectedProcess != 'all') {
                    $baseQuery->where('process_id', $selectedProcess);
                }

                // if ($partner_id_not_hundred_score) {
                //     $baseQuery->whereHas('audit', function ($query) {
                //         $query->where('with_fatal_score_per', '!=', 100);
                //     });
                // }




                $data = $baseQuery->with(
                    'audit',
                    'audit.audit_field_values',
                    'audit.audit_field_values.field',
                    'audit.qa_qtl_detail'
                )
                    ->orderBy('updated_at', 'desc')
                    ->get();

                $requiredFields = [
                    'Emp ID',
                    'Emp Name',
                    'BPA Name',
                    'Agent Name',
                    'Auditee Name',
                    'Auditee ID',
                    'Advisor Name',
                    'BPA ID',
                    'LOB'
                ];

                $dynamicHeaders = collect();

                $requiredFields = array_map('strtolower', $requiredFields);

                if ($selectedProcess != 'all') {

                    $dynamicHeaders = AuditFormFields::where('process_id', $selectedProcess)
                        ->where('status', 1)
                        ->whereIn(DB::raw('LOWER(field_name)'), $requiredFields)
                        ->get();

                    // dd( $dynamicHeaders );

                    foreach ($dynamicHeaders as $field) {
                        $fieldMap[strtolower($field->field_name)] = $field->id;
                    }
                }

                if ($selectedProcess != 'all') {

                    foreach ($data as $row) {

                        $dynamicFields = [];

                        if ($row->audit && $row->audit->audit_field_values) {

                            foreach ($row->audit->audit_field_values as $value) {

                                $dynamicFields[$value->field_id] = $value->field_value;
                            }
                        }

                        $row->dynamic_fields = $dynamicFields;
                    }
                }


                //     $data = RawData::where('status',1)
                //     ->whereIn('partner_id',$all_cluster_partners)
                //     ->whereIn('partner_location_id', $all_cluster_locations)
                //     ->whereIn('process_id', $all_cluster_processes)
                //    ->whereHas('audit', function ($query) use ($start_date,$end_date){
                //         $query->whereDate('audit_date','>=',$start_date)->whereDate('audit_date','<=',$end_date)
                //         ->where('deleted',0);
                //     })                    
                //     ->whereHas('audit', function ($query) use  ($current_date_time) {
                //         $query

                //             //   ->where('qc_tat','<',$current_date_time);
                //            // ->where('rebuttal_tat','>=',$current_date_time)
                //             ->where('qc_tat','<',$current_date_time)
                //             ->where('feedback_shared_status','!=',1);                            

                //     })->with('audit')
                //     ->orderBy('updated_at','desc')->get();
            } else {
                $data = RawData::where('status', 1)
                    ->whereIn('partner_id', $all_cluster_partners)
                    ->whereIn('partner_location_id', $all_cluster_locations)
                    ->whereIn('process_id', $all_cluster_processes)
                    ->whereHas('audit', function ($query) use ($start_date, $end_date) {
                        $query->whereDate('audit_date', '>=', $start_date)->whereDate('audit_date', '<=', $end_date)->where('deleted', 0);
                    })->whereHas('audit', function ($query) use ($current_date_time) {
                        $query->where('rebuttal_tat', '>=', $current_date_time)->where('qc_tat', '<', $current_date_time);
                    })->with('audit')
                    ->orderBy('updated_at', 'desc')->get();
            }
        } else {

            $data = RawData::where('status', 1)
                ->where('partner_id', $partner_id)
                ->where('partner_location_id', 'like', $partner_location_id)
                ->whereHas('audit', function ($query) use ($start_date, $end_date) {
                    $query->whereDate('audit_date', '>=', $start_date)->whereDate('audit_date', '<=', $end_date)->where('deleted', 0);
                })->whereHas('audit', function ($query) use ($current_date_time) {
                    $query
                        ->where('rebuttal_tat', '>=', $current_date_time)
                        ->where('qc_tat', '<', $current_date_time);
                })->with('audit')
                ->orderBy('updated_at', 'desc')->get();
        }


        $processes = Process::whereIn('id', $all_cluster_processes)
            ->orderBy('name')
            ->get();
        return view('partners.audit_completed', compact('data', 'processes', 'selectedProcess', 'requiredFields', 'fieldMap'));
    }
    public function single_audit_detail($audit_id)
    {

        $audit_data = Audit::with(['audit_parameter_result', 'audit_results', 'audit_results.reason_type', 'audit_results.reason'])->find(Crypt::decrypt($audit_id));
        if ($audit_data->rebuttal_status != 2)
            $audit_data->rebuttal_status = 1;


        $audit_data->save();

        $raw_data = RawData::find($audit_data->raw_data_id);

        $qm_sheet_para_data = QmSheetParameter::with(['qm_sheet_sub_parameter'])->where('qm_sheet_id', $audit_data->qm_sheet_id)->get();

        $audit_sp_results = $audit_data->audit_results;
        $final_data = [];
        $all_sub_parameters = [];
        foreach ($qm_sheet_para_data as $key => $value) {

            //all subparameters
            foreach ($value->qm_sheet_sub_parameter as $sskey => $ssvalue) {
                $all_sub_parameters[] = ["key" => $value->id . "_" . $ssvalue->id, "value" => $ssvalue->sub_parameter];
            }
            //all subparameters


            $final_data[$value->id]['name'] = $value->parameter;
            if ($value->is_non_scoring) {
                foreach ($value->qm_sheet_sub_parameter as $skey => $svalue) {
                    $t_1 = $audit_sp_results->where('parameter_id', $value->id)->where('sub_parameter_id', $svalue->id)->toArray();
                    $temp_result = $t_1[array_key_first($t_1)];
                    $final_data[$value->id]['sp'][] = ['name' => $svalue->sub_parameter, 'detail' => $svalue->details, 'selected_option' => return_non_scoring_observation($temp_result['selected_option']), 'scored' => $temp_result['score'], 'reason_type' => "-", 'reason' => '-', 'remark' => $temp_result['remark'], 'screenshot' => $temp_result['screenshot']];
                }
            } else {
                foreach ($value->qm_sheet_sub_parameter as $skey => $svalue) {

                    $t_1 = $audit_sp_results->where('parameter_id', $value->id)->where('sub_parameter_id', $svalue->id)->toArray();

                    if (!empty($t_1)) {
                        $temp_result = $t_1[array_key_first($t_1)];
                        $final_data[$value->id]['sp'][] = [
                            'name' => $svalue->sub_parameter,
                            'detail' => $svalue->details,
                            'selected_option' => $value->is_non_scoring ? return_non_scoring_observation($temp_result['selected_option']) : return_general_observation($temp_result['selected_option']),
                            'scored' => $temp_result['score'],
                            'reason_type' => isset($temp_result['reason_type']['name']) ? $temp_result['reason_type']['name'] : "-",
                            'reason' => isset($temp_result['reason']['name']) ? $temp_result['reason']['name'] : "-",
                            'remark' => $temp_result['remark'],
                            'screenshot' => $temp_result['screenshot']
                        ];
                    } else {
                        $final_data[$value->id]['sp'][] = [
                            'name' => $svalue->sub_parameter,
                            'detail' => $svalue->details,
                            'selected_option' => "-",
                            'scored' => "-",
                            'reason_type' => "-",
                            'reason' => "-",
                            'remark' => "-",
                            'screenshot' => "-"
                        ];
                    }


                    //         $temp_result = $t_1[array_key_first($t_1)];
                    //         if(isset($temp_result['reason_type']['name'])){

                    //         } else {
                    //             $temp_result['reason']['name'] = "-";
                    //             $temp_result['reason_type']['name'] = "-";
                    //            // print_r(json_encode($svalue)); die;
                    //         }
                    //  $final_data[$value->id]['sp'][] = ['name'=>$svalue->sub_parameter,'detail'=>$svalue->details,'selected_option'=>return_general_observation($temp_result['selected_option']),'scored'=>$temp_result['score'],'reason_type'=>$temp_result['reason_type']['name'],'reason'=>$temp_result['reason']['name'],'remark'=>$temp_result['remark'],'screenshot'=>$temp_result['screenshot']];
                }
            }
        }

        $rebuttal_data = Rebuttal::where('raised_by_user_id', Auth::user()->id)->where('audit_id', $audit_data->id)->with(['parameter', 'sub_parameter'])->get();

        return view('partners.single_audit_detail', compact(['raw_data', 'audit_data', 'final_data', 'all_sub_parameters', 'rebuttal_data']));
    }
    public function store_feedback(Request $request)
    {
        $audit_data = Audit::find(Crypt::decrypt($request->audit_id));

        // $audit_data->feedback_status = $request->feedback_status;
        // $audit_data->feedback_date = $request->feedback_date;
        $audit_data->feedback_comment = $request->feedback_remarks;
        $audit_data->feedback_shared_status = $request->feedback;
        $audit_data->feedback_shared_date =  date('Y-m-d h:i:s');
        $audit_data->save();

        return redirect('partner/single_audit_detail/' . $request->audit_id)->with('success', 'Feedback saved successfully.');
    }
    public function error_page()
    {
        return view('error.error_page');
    }
}
