<?php

namespace App\Http\Controllers;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 1000); //1000 seconds = 10 minutes
use App\Process;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Crypt;
use App\Mail\MailDashboard;
use Illuminate\Support\Facades\Mail;
use App\Audit;
use App\AuditResult;
use App\QmSheet;
use App\QmSheetParameter;
use App\QmSheetSubParameter;
use App\Partner;
use App\Auditcycle;
use App\RawData;
use DB;
use Illuminate\Database\Eloquent\Builder;

class ProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Process::where('company_id',Auth::user()->company_id)->get();
        return view('process.list',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('process.create');
    }
    public function graphExtractText()
    {
        return view('audit_cycle.graphTesting');
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
            'details' =>'required',
            'name' => 'required'
        ]);

        if($validator->fails())
        {
            return redirect('process/create')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            $new_rc = new Process;
            $new_rc->fill($request->all());
            $new_rc->save();
        }
        return redirect('process')->with('success', 'Process created successfully');    
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
        $data = Process::find(Crypt::decrypt($id));
        return view('process.edit',compact('data'));
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
            'company_id' => 'required',
            'name' => 'required'
        ]);

        if($validator->fails())
        {
            return redirect('process/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            $new_rc =  Process::find(Crypt::decrypt($id));
            $new_rc->fill($request->all());
            $new_rc->save();
        }
        return redirect('process')->with('success', 'Process edited successfully'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Process::find(Crypt::decrypt($id))->delete();
        return redirect('process')->with('success', 'Process deleted successfully.');    
    }

    public function checking() {
        try {
            $partner_id = "56";
            $location_id = $lob = "%";
            $processId = "47";
            $current_date_time = date('Y-m-d H:i:s');
            $partner_id = "56";
            $client_id = 14;

            $auditData = Auditcycle::where('client_id',$client_id)->where('process_id',$processId)->orderby('start_date','desc')->first();
            $dates = explode(",", "2023-09-01,2023-09-30");
            $start_date = date_to_db($auditData->start_date);
            $end_date = date_to_db($auditData->end_date);

            // $dates = explode(",", "2023-12-05,2024-01-04");
            // $start_date = date_to_db($dates[0]);
            // $end_date = date_to_db($dates[1]);
            // if (Auth::user()->hasRole('qtl')) {
            //     if (Auth::user()->parent_client) {
            //         $client_id = Auth::user()->parent_client;
            //     } else {
            //         return redirect('error')->with('warning', 'Please set client details');
            //     }
            // } else {
            //     if (Auth::user()->hasRole('partner-quality-head') || Auth::user()->hasRole('partner-admin') || Auth::user()->hasRole('partner-operation-head') || Auth::user()->hasRole('partner-training-head')) {
            //         if (Auth::user()->parent_client) {
            //             $client_id = Auth::user()->parent_client;
            //         } else {
            //             $partner_details = PartnersProcessSpoc::where('user_id', Auth::user()->id)->first();
            //             if (isset($partner_details->partner_id)) {
            //                 $user_details = Partner::where('id', $partner_details->partner_id)->first();
            //                 $client_id = $user_details->client_id;
            //             } else {
            //                 $user_details = Partner::where('admin_user_id', Auth::user()->id)->first();
            //                 if (isset($user_details->client_id)) {
            //                     $client_id = $user_details->client_id;
            //                 } else {
            //                     return redirect('error')->with('warning', 'Your role is not set please contact to admin!');
            //                 }
            //             }
            //         }
            //     } else {
            //         if (Auth::user()->client_detail) {
            //             $client_id = Auth::user()->client_detail->client_id;
            //         } else {
            //             if (Auth::user()->parent_client) {
            //                 $client_id = Auth::user()->parent_client;
            //             } else {
            //                 return redirect('error')->with('warning', 'Your role is not set please contact to admin!');
            //             }
            //         }
            //     }
            // }
            // if (Auth::user()->parent_client) {
            //     dd("yes");
            //     $client_id = Auth::user()->parent_client;
            //     $all_cluster_processes = get_helper_cluster_processes(Auth::user()->id);
            //     $all_cluster_partners = get_helper_cluster_partners(Auth::user()->id);
            //     $all_cluster_locations = get_helper_cluster_locations(Auth::user()->id);
            //     // $all_cluster_lobs = get_helper_cluster_lobs(Auth::user()->id);
            // }
            $userId = 333;
            $all_cluster_processes = get_helper_cluster_processes($userId);
            $all_cluster_partners = get_helper_cluster_partners($userId);
            $all_cluster_locations = get_helper_cluster_locations($userId);
            $location_id = $lob = "%%";
            $process_id = "47";
            $end_date = "2023-09-30";
            $start_date = "2023-09-01";
            $current_date_time = date('Y-m-d H:i:s');
            $rebuttal_data = [];
            $client_id = 14;
            // if (Auth::user()->parent_client) {
            //     if ($partner_id == 'cluster_partners') {
            //         $audit_data = Audit::where('client_id', $client_id)->whereIn('partner_id', $all_cluster_partners)->where('process_id', $process_id)->whereDate('audit_date', ">=", $start_date)->whereDate('audit_date', "<=", $end_date)->where('qc_tat', '<', $current_date_time)->whereHas('raw_data', function (Builder $query) use ($location_id, $lob) {
            //                 $query->where('partner_location_id', 'like', $location_id);
            //                 $query->where('lob', 'like', $lob);
            //             })->withCount(['audit_rebuttal', 'audit_rebuttal_accepted', 'audit_parameter_result', 'raw_data', 'audit_results'])->get();
            //     } else {
            //         $audit_data = Audit::where('client_id', $client_id)->where('partner_id', $partner_id)->where('process_id', $process_id)->whereDate('audit_date', ">=", $start_date)->whereDate('audit_date', "<=", $end_date)->where('qc_tat', '<', $current_date_time)->whereHas('raw_data', function (Builder $query) use ($location_id, $lob) {
            //                 $query->where('partner_location_id', 'like', $location_id);
            //                 $query->where('lob', 'like', $lob);
            //             })->withCount(['audit_rebuttal', 'audit_rebuttal_accepted', 'audit_parameter_result', 'raw_data', 'audit_results'])->get();
            //     }
            // } else {
                
            //     $audit_data = Audit::where('client_id', $client_id)->where('partner_id', 'like', $partner_id)->where('process_id', $process_id)->whereDate('audit_date', ">=", $start_date)->whereDate('audit_date', "<=", $end_date)->where('qc_tat', '<', $current_date_time)->whereHas('raw_data', function (Builder $query) use ($location_id, $lob) {
            //             $query->where('partner_location_id', 'like', $location_id);
            //             $query->where('lob', 'like', $lob);
            //         })->withCount(['audit_rebuttal', 'audit_rebuttal_accepted', 'audit_parameter_result', 'raw_data', 'audit_results'])->get();
            // }
                // echo('partner_id:'. $partner_id. ' process_id:'. $process_id. " start: ".$start_date." enddate: ".$end_date. "current_date_time:". $current_date_time); die;
            $audit_data = Audit::where('client_id', $client_id)->where('partner_id', $partner_id)->where('process_id', $process_id)->whereDate('audit_date', ">=", $start_date)->whereDate('audit_date', "<=", $end_date)->where('qc_tat', '<', $current_date_time)->whereHas('raw_data', function (Builder $query) use ($location_id, $lob) {
                // $query->where('partner_location_id', 'like', $location_id);
                // $query->where('lob', 'like', $lob);
            })->withCount(['audit_rebuttal', 'audit_rebuttal_accepted', 'audit_parameter_result', 'raw_data', 'audit_results'])->get();
            // print_r(count($audit_data)); die();
            

            // if ($client_id == 9 || $client_id == 13 || $client_id == 15 || $client_id == 18) {
            //     if ($request->date == "2022-04-01,2023-03-31") {
            //         if ($request->partner_id == "all") {
            //             $target = MonthTarget::join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')->where('month_targets.client_id', $client_id)->where('month_targets.process_id', $process_id)->where('month_targets.lob', 'like', $lob)->whereDate('auditcycles.start_date', '>=', $start_date)->whereDate('auditcycles.end_date', '<=', $end_date)->sum('month_targets.eq_audit_target_mtd');
            //         } else {
            //             $target = MonthTarget::join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')->where('month_targets.client_id', $client_id)->where('month_targets.partner_id', $partner_id)->where('month_targets.process_id', $process_id)->where('month_targets.lob', 'like', $lob)->whereDate('auditcycles.start_date', '>=', $start_date)->whereDate('auditcycles.end_date', '<=', $end_date)->sum('month_targets.eq_audit_target_mtd');
            //         }
            //     } else {
            //         if ($request->partner_id == "all") {
            //             $target = MonthTarget::join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')->where('month_targets.client_id', $client_id)->where('month_targets.process_id', $process_id)->where('month_targets.lob', 'like', $lob)->whereDate('auditcycles.start_date', $start_date)->whereDate('auditcycles.end_date', $end_date)->sum('month_targets.eq_audit_target_mtd');
            //         } else {
            //             $target = MonthTarget::join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')->where('month_targets.client_id', $client_id)->where('month_targets.partner_id', $partner_id)->where('month_targets.process_id', $process_id)->where('month_targets.lob', 'like', $lob)->whereDate('auditcycles.start_date', $start_date)->whereDate('auditcycles.end_date', $end_date)->sum('month_targets.eq_audit_target_mtd');
            //         }
            //     }
            //     if (gettype($target) == "NULL") {
            //         $coverage['target'] = 0;
            //         $coverage['achived'] = $audit_data->count();
            //         $coverage['achived_per'] = round(($audit_data->count() / 450) * 100);
            //     } else if ($target == 0) {
            //         $coverage['target'] = $target;
            //         $coverage['achived'] = $audit_data->count();
            //         $coverage['achived_per'] = $audit_data->count();
            //     } else {
            //         $coverage['target'] = $target;
            //         $coverage['achived'] = $audit_data->count();
            //         $coverage['achived_per'] = round(($audit_data->count() / $target) * 100);
            //     }
            // } else {
                
            //     $coverage['target'] = 450;
            //     $coverage['achived'] = $audit_data->count();
            //     $coverage['achived_per'] = round(($audit_data->count() / 450) * 100);
            // }

            $coverage['target'] = 450;
            $coverage['achived'] = $audit_data->count();
            $coverage['achived_per'] = round(($audit_data->count() / 450) * 100);
            $partner_id = "56";
            $location_id = $lob = "%";
            $processId = "47";
            $current_date_time = date('Y-m-d H:i:s');
            $end_date = "2023-09-30";
            $start_date = "2023-09-01";
    
    
            $final_data['partner_id'] = $partner_id;
            $final_data['lob'] = $lob;
            $final_data['location_id'] = $location_id;
            $final_data['process_id'] = $process_id;
            $final_data['date'] = "2023-09-01,2023-09-30";
            $final_data['user_id'] = $userId;
            $final_data['coverage'] = $coverage;
            $temp_total_rebuttal = 0;
            $temp_accepted_rebuttal = 0;
            $temp_rejected_rebuttal = 0;
            foreach ($audit_data as $key => $value) {
                if ($value->rebuttal_status > 0) {
                    $temp_total_rebuttal += $value->audit_rebuttal_count;
                    $temp_accepted_rebuttal += $value->audit_rebuttal_accepted->count();
                    $temp_rejected_rebuttal += $value->audit_rebuttal_rejected->count();
                }
            }
            $rebuttal_data['raised'] = $temp_total_rebuttal;
            $rebuttal_data['accepted'] = $temp_accepted_rebuttal;
            $rebuttal_data['rejected'] = $temp_rejected_rebuttal;
            $rebuttal_data['wip'] = ($temp_total_rebuttal - ($temp_accepted_rebuttal + $temp_rejected_rebuttal));
            if ($audit_data->count())
    
                $rebuttal_data['rebuttal_per'] = round(($rebuttal_data['raised'] / $audit_data->count()) * 100);
            else
    
                $rebuttal_data['rebuttal_per'] = 0;
    
            if ($rebuttal_data['accepted'])
    
                $rebuttal_data['accepted_per'] = round((($rebuttal_data['accepted'] / $audit_data->count()) * 100));
            else
    
                $rebuttal_data['accepted_per'] = 0;
    
            if ($rebuttal_data['rejected'])
    
                $rebuttal_data['rejected_per'] = round((($rebuttal_data['rejected'] / $audit_data->count()) * 100));
            else
    
                $rebuttal_data['rejected_per'] = 0;
    
            $final_data['rebuttal'] = $rebuttal_data;
            $fatal_dialer_data['with_fatal_score'] = 0;
            $fatal_dialer_data['without_fatal_score'] = 0;
            $fatal_audit_count = $audit_data->where('is_critical', 1)->count();
            $without_fatal_audit_count = $audit_data->where('is_critical', 0)->count();
            $fatal_audit_score_sum = 0;
            $without_fatal_audit_score_sum = 0;
            $scr =  $sca = 0;
            foreach ($audit_data as $key => $value) {
                foreach ($value->audit_parameter_result->where('is_non_scoring', '!=', 1) as $keyb => $valueb) {
                    if ($value->is_critical == 0) {
                        $scr += $valueb->with_fatal_score;
                    }
                    //$sca+=$valueb->temp_weight;
                    $sca += $valueb->temp_weight;
                }
                $without_fatal_audit_score_sum += $value->overall_score;
            }
            if ($audit_data->count()) {
                $fatal_dialer_data['with_fatal_score'] = round(($scr / $sca) * 100);
                $fatal_dialer_data['without_fatal_score'] = round(($without_fatal_audit_score_sum / $audit_data->count()));
            } else {
                $fatal_dialer_data['with_fatal_score'] = 0;
                $fatal_dialer_data['with_fatal_score'] = 0;
            }
            $final_data['fatal_dialer_data'] = $fatal_dialer_data; 
            $fatal_first_row_block['total_audits'] = $audit_data->count();
            $fatal_first_row_block['total_fatal_count_sub_parameter'] = 0;
            $fatal_first_row_block['total_fatal_audits'] = $fatal_audit_count;
            if ($fatal_audit_count)
    
                $fatal_first_row_block['total_fatal_audit_per'] = round((($fatal_first_row_block['total_fatal_audits'] / $fatal_first_row_block['total_audits']) * 100));
            else
    
                $fatal_first_row_block['total_fatal_audit_per'] = 0;
    
    
            $latest_qm_sheet = QmSheet::where('client_id', $client_id)->where('process_id', $process_id)->orderBy('version', 'desc')->first();
            $latest_qm_sheet_id = $latest_qm_sheet->id;
            $all_params = QmSheetParameter::where('qm_sheet_id', $latest_qm_sheet_id)->where('is_non_scoring', 0)->with('qm_sheet_sub_parameter')->get();
            $pwfcs = [];
            $pws;
            $params_list = [];
            $all_fatal_audits = $audit_data->where('is_critical', 1);
            $count = 1;
            foreach ($all_params as $key => $value) {
                $temp_params['counter'] = $count;
                $temp_params['fatal_count'] = 0;
                $temp_params['fail_count'] = 0;
                $temp_params['fatal_counted_score'] = 0;
                $temp_params['parameter'] = $value->parameter;
                $params_list[] = ucfirst($value->parameter);
                $paramSco = 0;
                $paramScoble = 0;
                foreach ($audit_data as $bkey => $bvalue) {
                    $temp_params['fatal_count'] += $bvalue->audit_parameter_result->where('parameter_id', $value->id)->where('is_critical', 1)->count();
                    $temp_params['fail_count'] += $bvalue->audit_results->where('parameter_id', $value->id)->where('selected_option', 2)->count();
                    // $temp_row = $bvalue->audit_parameter_result->where('parameter_id',$value->id)->where('is_critical',1)->first();
                    $temp_params['fatal_counted_score'] += $bvalue->audit_parameter_result->where('parameter_id', $value->id)->sum('with_fatal_score_per');
                    $paramSco += $bvalue->audit_parameter_result->where('parameter_id', $value->id)->sum('with_fatal_score');
                    $paramScoble += $bvalue->audit_parameter_result->where('parameter_id', $value->id)->sum('temp_weight');
                }
                if ($paramScoble != 0)    
                    $temp_params['fatal_score'] = round(($paramSco / $paramScoble) * 100);
                else
    
                    $temp_params['fatal_score'] = 0;
    
                $pwfcs[] = $temp_params;
                $paramScore = 0;
                $paramScorable = 0;
                $temp_params['param_score_total'] = 0;
                foreach ($audit_data as $keyc => $valuec) {
                    $temp_params['param_score_total'] += $valuec->audit_parameter_result->where('parameter_id', $value->id)->sum('with_fatal_score_per');
                    $paramScore += $valuec->audit_parameter_result->where('parameter_id', $value->id)->sum('with_fatal_score');
                    $paramScorable += $valuec->audit_parameter_result->where('parameter_id', $value->id)->sum('temp_weight');
                }
    
                if ($paramScorable != 0)
                    $temp_params['param_score'] = round(($paramScore / $paramScorable) * 100);
                else
    
                    $temp_params['param_score'] = 0;
    
                // Parameter score 2
                $pws[] = $temp_params['param_score'];
                $count++;
            }
            $final_data['pwfcs'] = $pwfcs;
            $final_data['parameter_list'] = $params_list;
            $final_data['pws'] = $pws;
            $despo = [];
            $temp_all_unique_despos = [];
            foreach ($audit_data as $key => $value) {
                $temp_all_unique_despos[] = $value->raw_data->disposition;
            }
            $all_unique_despos = array_unique($temp_all_unique_despos);
            $all_unique_despos_counts = array_map(function ($val) {
                                            return 0; 
                                        }, $all_unique_despos);
    
            $all_unique_despos_score_total = array_map(function ($val) {
                return 0; }, $all_unique_despos);
    
            $all_unique_despos_score = array_map(function ($val) {
                return 0; }, $all_unique_despos);
            $all_audit_counts = array();
            $all_scores = array();
            foreach ($audit_data as $key => $value) {
                if ($temp_id = array_search($value->raw_data->disposition, $all_unique_despos, true)) {
                    $all_unique_despos_counts[$temp_id] += 1;
                    $all_unique_despos_score_total[$temp_id] += $value->overall_score;
                }
            }
            $s = 0;
            foreach ($all_unique_despos as $key => $value) {
                $all_audit_counts[$s] = Audit::where('client_id', $client_id)->where('partner_id', 'like', $partner_id)->where('process_id', $process_id)->whereDate('audit_date', ">=", $start_date)->whereDate('audit_date', "<=", $end_date)->whereHas('raw_data', function (Builder $query) use ($location_id, $value) {
                        $query->where('partner_location_id', 'like', $location_id);
                        $query->where('disposition', 'like', $value);
                    })->get()->count();
                $audit = Audit::where('client_id', $client_id)->where('partner_id', 'like', $partner_id)->where('process_id', $process_id)->whereDate('audit_date', ">=", $start_date)->whereDate('audit_date', "<=", $end_date)->whereHas('raw_data', function (Builder $query) use ($location_id, $value) {
                        $query->where('partner_location_id', 'like', $location_id);
                        $query->where('disposition', 'like', $value);
                    })->withCount(['audit_results', 'audit_parameter_result'])->get();
                $score = 0;
                $scorable = 0;
                foreach ($audit as $key => $value) {
                    foreach ($value->audit_parameter_result->where('is_non_scoring', 0) as $keyb => $valueb) {
                        if ($value->is_critical == 0) {
                            $score += $valueb->with_fatal_score;
                        }
                        $scorable += $valueb->temp_weight;
                    }
                }
                if ($scorable != 0) {
                    $all_scores[$s] = round(($score / $scorable) * 100);
                } else {
                    $all_scores[$s] = 0;
                }
                $s++;
            }
            $despo['all_unique_despos'] = array_values($all_unique_despos);
            $despo['all_unique_despos_counts'] = array_values($all_audit_counts);
            $despo['all_unique_despos_score'] = array_values($all_scores);
            $final_data['disposition'] = $despo;
            $tmp_params = [];
            foreach ($all_params as $key => $value) {
                $temp_sps = [];
                $temp_sps['parameter_id'] = $value->id;
                $temp_sps['parameter'] = ucfirst($value->parameter);
                $temp_sps_list = [];
                $temp_sps_color = [];
                $temp_sps_detail = [];
                foreach ($value->qm_sheet_sub_parameter as $keyb => $valueb) {
                    $temp_sps_list[] = ucfirst(strtolower($valueb->sub_parameter));
                    if ($valueb->critical == 1) {
                        $temp_sps_color[] = "rgb(65, 105, 225)";
                    } else {
                        $temp_sps_color[] = "grey";
                    }
                    $temp_sps_detail[] = str_replace('"', '', $valueb->details);
                    $temp_sps['sub_p_id'][] = $valueb->id;
                    $temp_sps['sp_p_score'][] = 0;
                    $temp_sps['sp_p_weight'][] = 0;
                    $temp_sps['sp_p_obtained'][] = 0;
                    $temp_sps['sp_p_fatal_count'][] = 0;
                }
                $temp_sps['temp_sps_color'] = $temp_sps_color;
                $temp_sps['temp_sps_list'] = $temp_sps_list;
                $temp_sps['temp_sps_detail'] = $temp_sps_detail;
                $tmp_params[] = $temp_sps;
            }
            foreach ($audit_data as $key => $value) {
                foreach ($tmp_params as $keyb => $valueb) {
                    foreach ($valueb['sub_p_id'] as $keyc => $valuec) {
                        $tmp_params[$keyb]['sp_p_obtained'][$keyc] += $value->audit_results->where('parameter_id', $valueb['parameter_id'])->where('sub_parameter_id', $valuec)->sum('score');
                        $tmp_params[$keyb]['sp_p_weight'][$keyc] += $value->audit_results->where('parameter_id', $valueb['parameter_id'])->where('sub_parameter_id', $valuec)->sum('after_audit_weight');
                        $tmp_params[$keyb]['sp_p_fatal_count'][$keyc] += $value->audit_results->where('parameter_id', $valueb['parameter_id'])->where('sub_parameter_id', $valuec)->where('is_critical', 1)->count();
                    }
                }
            }
            foreach ($tmp_params as $key => $value) {
                foreach ($value['sp_p_score'] as $keyb => $valueb) {
                    $fatal_first_row_block['total_fatal_count_sub_parameter'] += $tmp_params[$key]['sp_p_fatal_count'][$keyb];
                    if ($tmp_params[$key]['sp_p_weight'][$keyb]) {
                        $tmp_params[$key]['sp_p_score'][$keyb] = round(($tmp_params[$key]['sp_p_obtained'][$keyb] / $tmp_params[$key]['sp_p_weight'][$keyb]) * 100, 0);
                    } else {
                        $tmp_params[$key]['sp_p_score'][$keyb] = 0;
                    }
                }
            }
            $final_data['fatal_first_row_block'] = $fatal_first_row_block;
            $final_data['spws'] = $tmp_params;
            $getUniqueCallType = array();
            $callType = RawData::select(DB::raw("distinct(call_type)"))->where('client_id', $client_id)->where('process_id', $process_id)->orderby('id', 'asc')->get();
            $query_w = "";
            $request_w = "";
            $complain_w = "";
            $s = 1;
            foreach ($callType as $key => $value) {
                if ($s == 1) {
                    $query_w = $value->call_type;
                }
                if ($s == 2) {
                    $request_w = $value->call_type;
                }
                if ($s == 3) {
                    $complain_w = $value->call_type;
                }
                $getUniqueCallType[] = $value->call_type;
                $s++;
            }
            $temp_qrc_bam['query_count'] = 0;
            $temp_qrc_bam['query_fatal_count'] = 0;
            $temp_qrc_bam['query_fatal_score_sum'] = 0;
            $temp_qrc_bam['request_count'] = 0;
            $temp_qrc_bam['request_fatal_count'] = 0;
            $temp_qrc_bam['request_fatal_score_sum'] = 0;
            $temp_qrc_bam['complaint_count'] = 0;
            $temp_qrc_bam['complaint_fatal_count'] = 0;
            $temp_qrc_bam['complaint_fatal_score_sum'] = 0;
            foreach ($audit_data as $d) {
                if ($d->raw_data->call_type == $query_w) {
                    $temp_qrc_bam['query_count'] += 1;
                    $temp_qrc_bam['query_fatal_score_sum'] += $d->with_fatal_score_per;
                }
                if ($d->raw_data->call_type == $query_w && $d->is_critical == 1) {
                    $temp_qrc_bam['query_fatal_count'] += 1;
                }
                if ($d->raw_data->call_type == $request_w) {
                    $temp_qrc_bam['request_count'] += 1;
                    $temp_qrc_bam['request_fatal_score_sum'] += $d->with_fatal_score_per;
                }
                if ($d->raw_data->call_type == $request_w && $d->is_critical == 1) {
                    $temp_qrc_bam['request_fatal_count'] += 1;
                }
                if ($d->raw_data->call_type == $complain_w) {
                    $temp_qrc_bam['complaint_count'] += 1;
                    $temp_qrc_bam['complaint_fatal_score_sum'] += $d->with_fatal_score_per;
                }
                if ($d->raw_data->call_type == $complain_w && $d->is_critical == 1) {
                    $temp_qrc_bam['complaint_fatal_count'] += 1;
                }
            }    
            $temp_qrc['query_count'] = $audit_data->where('qrc_2', $query_w)->count();
            $temp_qrc['query_fatal_count'] = $audit_data->where('qrc_2', $query_w)->where('is_critical', 1)->count();
            $temp_qrc['query_fatal_score_sum'] = $audit_data->where('qrc_2', $query_w)->sum('with_fatal_score_per');
    
            if ($temp_qrc['query_count'])
                $temp_qrc['query_fatal_score'] = round(($temp_qrc['query_fatal_score_sum'] / $temp_qrc['query_count']));
            else
                $temp_qrc['query_fatal_score'] = 0;
    
            $temp_qrc['request_count'] = $audit_data->where('qrc_2', $request_w)->count();
            $temp_qrc['request_fatal_count'] = $audit_data->where('qrc_2', $request_w)->where('is_critical', 1)->count();
            $temp_qrc['request_fatal_score_sum'] = $audit_data->where('qrc_2', $request_w)->sum('with_fatal_score_per');
    
            if ($temp_qrc['request_count'])
                $temp_qrc['request_fatal_score'] = round(($temp_qrc['request_fatal_score_sum'] / $temp_qrc['request_count']));
            else
                $temp_qrc['request_fatal_score'] = 0;
    
    
            $temp_qrc['complaint_count'] = $audit_data->where('qrc_2', $complain_w)->count();
            $temp_qrc['complaint_fatal_count'] = $audit_data->where('qrc_2', $complain_w)->where('is_critical', 1)->count();
            $temp_qrc['complaint_fatal_score_sum'] = $audit_data->where('qrc_2', $complain_w)->sum('with_fatal_score_per');
    
            if ($temp_qrc['complaint_count'])
                $temp_qrc['complaint_fatal_score'] = round(($temp_qrc['complaint_fatal_score_sum'] / $temp_qrc['complaint_count']));
            else
                $temp_qrc['complaint_fatal_score'] = 0;
    
            $qrc_data['audit_count'] = [
                $temp_qrc['query_count'],
                $temp_qrc['request_count'],
                $temp_qrc['complaint_count']
            ];
            $qrc_data['fatal_count'] = [
                $temp_qrc['query_fatal_count'],
                $temp_qrc['request_fatal_count'],
                $temp_qrc['complaint_fatal_count']
            ];
            $qrc_data['score'] = [
                $temp_qrc['query_fatal_score'],
                $temp_qrc['request_fatal_score'],
                $temp_qrc['complaint_fatal_score']
            ];
            if ($temp_qrc_bam['query_count'])
                $temp_qrc_bam['query_fatal_score'] = round(($temp_qrc_bam['query_fatal_score_sum'] / $temp_qrc_bam['query_count']));
            else
                $temp_qrc_bam['query_fatal_score'] = 0;
    
            if ($temp_qrc_bam['request_count'])
                $temp_qrc_bam['request_fatal_score'] = round(($temp_qrc_bam['request_fatal_score_sum'] / $temp_qrc_bam['request_count']));
            else
                $temp_qrc_bam['request_fatal_score'] = 0;
    
            if ($temp_qrc_bam['complaint_count'])
                $temp_qrc_bam['complaint_fatal_score'] = round(($temp_qrc_bam['complaint_fatal_score_sum'] / $temp_qrc_bam['complaint_count']));
            else
                $temp_qrc_bam['complaint_fatal_score'] = 0;

            $qrc_data_bam['audit_count'] = [
                $temp_qrc_bam['query_count'],
                $temp_qrc_bam['request_count'],
                $temp_qrc_bam['complaint_count']
            ];
            $qrc_data_bam['fatal_count'] = [
                $temp_qrc_bam['query_fatal_count'],
                $temp_qrc_bam['request_fatal_count'],
                $temp_qrc_bam['complaint_fatal_count']
            ];
            $qrc_data_bam['score'] = [
                $temp_qrc_bam['query_fatal_score'],
                $temp_qrc_bam['request_fatal_score'],
                $temp_qrc_bam['complaint_fatal_score']
            ];
            $final_data['qrc'] = $qrc_data;
            $final_data['qrc_bam'] = $qrc_data_bam;
            $final_data['call_type'] = [$query_w, $request_w, $complain_w];
            $final_data['client_id'] = $client_id;
            $all_agents = [];
            foreach ($audit_data as $key => $value) {
                $all_agents[] = $value->raw_data->emp_id;
            }
            $all_unique_agents = array_unique($all_agents);
            $all_audit_score = [];
            $quartile_audit_count = array();
            $quartile_audit_count[0] = 0;
            $quartile_audit_count[1] = 0;
            $quartile_audit_count[2] = 0;
            $quartile_audit_count[3] = 0;
            foreach ($all_unique_agents as $key => $value) {
                $agent_all_audit_score = Audit::where('client_id', $client_id)->where('partner_id', 'like', $partner_id)->where('process_id', $process_id)->whereDate('audit_date', ">=", $start_date)->whereDate('audit_date', "<=", $end_date)->whereHas('raw_data', function (Builder $query) use ($value, $location_id) {
                        $query->where('emp_id', 'like', $value);
                        $query->where('partner_location_id', 'like', $location_id);
                    })->withCount(['audit_parameter_result'])->get();
                $scored = 0;
                $scorable = 0;
                $ag_count = 0;
                foreach ($audit_data as $key => $value1) {
                    if ($value1->raw_data->emp_id == $value) {
                        $ag_count += 1;
                        foreach ($value1->audit_parameter_result->where('is_non_scoring', '!=', 1) as $keyb => $valueb) {
                            if ($value1->is_critical == 0) {
                                $scored += $valueb->with_fatal_score;
                            }
                            $scorable += $valueb->temp_weight;
                        }
                    }
                }
                if ($scorable == 0) {
                    $score = 0;
                } else {
                    $score = round(($scored / $scorable) * 100);
                }
                if ($score >= 0 && $score < 41) {
                    $quartile_audit_count[0] += $ag_count;
                }
                if ($score >= 41 && $score < 61) {
                    $quartile_audit_count[1] += $ag_count;
                }
                if ($score >= 61 && $score < 81) {
                    $quartile_audit_count[2] += $ag_count;
                }
                if ($score > 80) {
                    $quartile_audit_count[3] += $ag_count;
                }
                $all_audit_score[] = [
                    "name" => $value,
                    "audit_count" => $ag_count,
                    "with_fatal_score_per_sum" => $agent_all_audit_score->sum('with_fatal_score_per'),
                    "score" => $score
                ];
            }
            $quartile_data[0] = 0;
            $quartile_data[1] = 0;
            $quartile_data[2] = 0;
            $quartile_data[3] = 0;    
            foreach ($all_audit_score as $key => $value) {
                if ($value['score'] >= 0 && $value['score'] < 41)
                    $quartile_data[0] += 1;
                else if ($value['score'] >= 41 && $value['score'] < 61)
                    $quartile_data[1] += 1;
                else if ($value['score'] >= 61 && $value['score'] < 81)
                    $quartile_data[2] += 1;
                else if ($value['score'] > 80)
                    $quartile_data[3] += 1;
            }
            $final_data['quartile'] = $quartile_data;
            $final_data['quartile_au_count'] = $quartile_audit_count;
            $totalCount = $audit_data->count();
            $quar_audit_contribution[0] = 0;
            $quar_audit_contribution[1] = 0;
            $quar_audit_contribution[2] = 0;
            $quar_audit_contribution[3] = 0;
            if ($totalCount != 0) {
                $quar_audit_contribution[0] = round(($quartile_audit_count[0] / $totalCount) * 100);
                $quar_audit_contribution[1] = round(($quartile_audit_count[1] / $totalCount) * 100);
                $quar_audit_contribution[2] = round(($quartile_audit_count[2] / $totalCount) * 100);
                $quar_audit_contribution[3] = round(($quartile_audit_count[3] / $totalCount) * 100);
            }
            $final_data['quartile_au_contri'] = $quar_audit_contribution;
            $final_data['user_id'] = $userId;
            $non_scoring_params = QmSheetParameter::where('qm_sheet_id', $latest_qm_sheet_id)->where('is_non_scoring', 1)->with('qm_sheet_sub_parameter')->get();
            $non_scoring_params_name = '';
            if ($non_scoring_params->isNotEmpty()) {
                $non_scoring_params_name = $non_scoring_params[0]->parameter;
                $non_scoring_params = $non_scoring_params[0]->qm_sheet_sub_parameter;
            }
            $temp_non_scoring_params = [];
            foreach ($non_scoring_params as $key => $value) {
                $temp_non_scoring_params[] = ['non_scoring_option_group' => $value->non_scoring_option_group, 'name' => $value->sub_parameter, 'id' => $value->id, 'count' => [0, 0, 0]];
            }
            foreach ($audit_data as $key => $value) {
                foreach ($temp_non_scoring_params as $keyb => $valueb) {
                    $a_result = $value->audit_results->where('sub_parameter_id', $valueb['id']);
                    $temp_a_result = [];
                    foreach ($a_result as $keyx => $valuex) {
                        $temp_a_result = $valuex;
                    }
                    if ($temp_a_result)
                        switch ($valueb['non_scoring_option_group']) {
                            case 1: {
                                    if ($temp_a_result->selected_option == 1)
                                        $temp_non_scoring_params[$keyb]['count'][0] += 1;
                                    else
                                        $temp_non_scoring_params[$keyb]['count'][1] += 1;
                                    break;
                                }
                            case 2: {
                                    if ($temp_a_result->selected_option == 3)
                                        $temp_non_scoring_params[$keyb]['count'][0] += 1;
                                    else
                                        $temp_non_scoring_params[$keyb]['count'][1] += 1;
                                    break;
                                }
                            case 3: {
                                    if ($temp_a_result->selected_option == 6 || $temp_a_result->selected_option == 7)
                                        $temp_non_scoring_params[$keyb]['count'][0] += 1;
                                    else
                                        $temp_non_scoring_params[$keyb]['count'][1] += 1;
                                    break;
                                }
                            case 4: {
                                    if ($temp_a_result->selected_option == 10 || $temp_a_result->selected_option == 11)
                                        $temp_non_scoring_params[$keyb]['count'][0] += 1;
                                    else
                                        $temp_non_scoring_params[$keyb]['count'][1] += 1;
                                    break;
                                }
                            default:
                                break;
                        }
                }
            }
            $temp_all_non_scoring_sub_parameter_list = [];
            $temp_all_non_scoring_sub_parameter_data = [];
            foreach ($temp_non_scoring_params as $key => $value) {
                if (($temp_non_scoring_params[$key]['count'][0] + $temp_non_scoring_params[$key]['count'][1]) != 0) {
                    $temp_non_scoring_params[$key]['count'][2] = round(($temp_non_scoring_params[$key]['count'][0] / ($temp_non_scoring_params[$key]['count'][0] + $temp_non_scoring_params[$key]['count'][1])) * 100);
                } else {
                    $temp_non_scoring_params[$key]['count'][2] = 0;
                }
                $temp_all_non_scoring_sub_parameter_list[] = $value['name'];
                $temp_all_non_scoring_sub_parameter_data[] = $temp_non_scoring_params[$key]['count'][2];    
            }
            $final_data['non_scoring_params'] = ["list" => $temp_all_non_scoring_sub_parameter_list, "score" => $temp_all_non_scoring_sub_parameter_data, "names" => $non_scoring_params_name];
            $fail_count = 0;
            $critical_count = 0;
            $na_count = 0;
            foreach ($audit_data as $key => $value) {
                $fail_count += $value->audit_results->where('selected_option', 2)->count();
                $critical_count += $value->audit_results->where('selected_option', 3)->count();
                $na_count += $value->audit_results->where('selected_option', 4)->count();
            }
            $qm_sheet_sp_count = QmSheetSubParameter::where('qm_sheet_id', $latest_qm_sheet_id)->where('non_scoring_option_group', 0)->count();
            $audit_sp_count = $audit_data->count() * $qm_sheet_sp_count;
            if ($audit_data->count()) {
                $process_stats['dpu'] = round((($fail_count + $critical_count) / $audit_data->count()), 2);
                $process_stats['dpo'] = round((($fail_count + $critical_count) / ($audit_sp_count - $na_count)), 2);
                $process_stats['dpmo'] = $process_stats['dpo'] * 1000000;
                $process_stats['ppm'] = round(($fatal_audit_count / $audit_data->count()) * 1000000, 2);
                $process_stats['fty'] = ($audit_data->where('is_critical', 0)->count() / $audit_data->count());
            } else {
                $process_stats['dpu'] = 0;
                $process_stats['dpo'] = 0;
                $process_stats['dpmo'] = 0;
                $process_stats['ppm'] = 0;
                $process_stats['fty'] = 0;
            }
            $final_data['process_stats'] = $process_stats;
            $pareto_audit_result = AuditResult::whereHas('audit', function (Builder $query) use ($partner_id, $process_id, $start_date, $end_date, $location_id) {
                $query->where('partner_id', 'like', $partner_id)->where('process_id', $process_id)->whereDate('audit_date', ">=", $start_date)->whereDate('audit_date', "<=", $end_date)->whereHas('raw_data', function (Builder $query) use ($location_id) {
                        $query->where('partner_location_id', 'like', $location_id);
                    });
            })->with('reason')->get();
            $all_uni_reaons = [];
            $all_uni_reaons_a = [];
            $all_uni_reaons_b = [];
            $all_uni_reaons_c = [];
            $all_uni_reaons_d = [];
            $all_uni_reaons_e = [];
            foreach ($pareto_audit_result as $key => $value) {
                if ($value->reason)
                    $all_uni_reaons_a[$value->reason_id] = ['count' => 0, 'name' => $value->reason->name];
            }
            foreach ($all_uni_reaons_a as $key => $value) {
                $count = $pareto_audit_result->where('reason_id', $key)->count();
                $all_uni_reaons_a[$key]['count'] = $count;
            }
            usort($all_uni_reaons_a, function ($a, $b) {
                return $b['count'] - $a['count'];
            });
            $runningSum = 0;
            foreach ($all_uni_reaons_a as $kk => $vv) {
                $runningSum += $vv['count'];
                $all_uni_reaons_b[$kk] = $vv['count'];
                $all_uni_reaons_c[$kk] = $runningSum;
                $all_uni_reaons_d[$kk] = $vv['name'];
            }
            foreach ($all_uni_reaons_c as $kk => $vv) {
                $all_uni_reaons_e[$kk] = (round(($vv / $runningSum) * 100, 2));
            }
            $final_data['pareto_data']['count'] = $all_uni_reaons_b;
            $final_data['pareto_data']['per'] = $all_uni_reaons_e;
            $final_data['pareto_data']['reasons'] = $all_uni_reaons_d;
            $client_id =14;
            $all_partners = Partner::select('name', 'id')->whereIn('id', $all_cluster_partners)->get();
            $data = $final_data;
           // $pdf = PDF::loadView('dashboards.demo_dash_pdf', $data); 
    
            Mail::to("akhil.kumawat@qdegrees.com")->send(new MailDashboard($final_data));
            Mail::to("abhijeet.dutta@qdegrees.com")->send(new MailDashboard($final_data));
            dd("maillll done");
    
            // return view('dashboards.demo_dash', compact('all_partners', 'final_data'));
           
            // dd($pdf); 
            // return $pdf->download('medium.pdf');
            
            return view('dashboards.demo_dash_email', compact('all_partners', 'final_data', 'chart1'));
            
            return view('dashboards.demo_dash', compact('all_partners', 'final_data'));

            
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }


    // raw dump with report downloades


    public function raw_data_report(){

        


    }


}
