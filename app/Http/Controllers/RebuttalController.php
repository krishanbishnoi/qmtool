<?php

namespace App\Http\Controllers;

ini_set('memory_limit', '-1');

use App\Audit;
use App\ClientsQtl;
use App\AuditAlertBox;
use App\AuditResult;
use App\Notifications\RebuttalRaised;
use App\Notifications\RebuttalReply;
use App\QmSheetParameter;
use App\QmSheetSubParameter;
use App\RawData;
use App\BodReason;
use App\ReasonType;
use App\Rebuttal;
use App\User;
use Auth;
use Crypt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Notification;
use Validator;

class RebuttalController extends Controller
{
    public function raise_rebuttal(Request $request)
    {
        $par_names = [];
        $audit_id_2 = Crypt::decrypt($request->audit_id);
        foreach ($request->rebuttals as $key => $value) {


            $temp_data = explode("_", $value['sp']);
            // check for old status
            $sub_par_detail = QmSheetSubParameter::where('id', $temp_data[1])->first();


            $rebuttal_process = Rebuttal::where('audit_id', $audit_id_2)->where('parameter_id', $temp_data[0])->where('sub_parameter_id', $temp_data[1])->where('status', '!=', 1)->where('status', '!=', 2)->get();

            // $data = Rebuttal::where('audit_id',$audit_id)->where('parameter_id',$temp_data[0])->where('sub_parameter_id',$temp_data[1])->get();

            if ($rebuttal_process->count() != 0) {
                //$par_names['name'] = $sub_par_detail->sub_parameter;
                array_push($par_names, $sub_par_detail->sub_parameter);
            }
        }

        $validator = Validator::make($request->all(), [
            'raw_data_id' => 'required',
            'audit_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('partner/single_audit_detail/' . $request->audit_id)
                ->withErrors($validator)
                ->withInput();
        } else if (sizeof($par_names) != 0) {
            $message = "Rebuttal has been already raised for mentioned sub parameters.";
            $message = $message . "\nIn case if you are raising on multiple subparameters then please remove these subparameters from the Rebuttal list";
            $i = 1;
            foreach ($par_names as $values) {
                $message = $message . "\n" . $i . ". " . $values;
                $i++;
            }

            return redirect('partner/single_audit_detail/' . $request->audit_id)->with('warning', $message);
        } else {
            $audit_data = Audit::find(Crypt::decrypt($request->audit_id));
            $audit_data->rebuttal_status = 2;
            $auditor_user_id = $audit_data->audited_by_id;
            $audit_data->save();

            $audit_id = Crypt::decrypt($request->audit_id);
            $raw_data_id = Crypt::decrypt($request->raw_data_id);
            $re_rebuttal_id = null;
            foreach ($request->rebuttals as $key => $value) {
                $temp_data = explode("_", $value['sp']);
                // check for old status
                $rebuttal_process = Rebuttal::where('audit_id', $audit_id)->where('parameter_id', $temp_data[0])->where('sub_parameter_id', $temp_data[1])->where('status', '!=', 1)->where('status', '!=', 2)->get();

                $data = Rebuttal::where('audit_id', $audit_id)->where('parameter_id', $temp_data[0])->where('sub_parameter_id', $temp_data[1])->get();

                if ($data->count() > 1 || $rebuttal_process->count() != 0)

                //if($data->count() > 1)
                {
                    // no rebuttal
                    $re_rebuttal_id = null;
                } elseif ($data->count() == 1) {
                    if ($audit_data->client_id == 9) {
                        Notification::send(User::find($auditor_user_id), new RebuttalRaised(['upper_text' => count($request->rebuttals) . ' Re-Rebuttal can not be raised.']));
                        return redirect('partner/single_audit_detail/' . $request->audit_id)->with('success', 'Re-Rebuttal can not be raised.');
                    } else {
                        $nrc = new Rebuttal;
                        $nrc->raised_by_user_id = Auth::user()->id;
                        $nrc->raw_data_id = $raw_data_id;
                        $nrc->audit_id = $audit_id;
                        $nrc->parameter_id = $temp_data[0];
                        $nrc->sub_parameter_id = $temp_data[1];
                        $nrc->remark = $value['remark'];
                        $nrc->re_rebuttal_id = $data[0]->id;
                        if (isset($value['artifact'])) {
                            $value['artifact']->store("company/_" . Auth::user()->company_id . '/rebuttals/');
                            $nrc->artifact = $value['artifact']->hashName();
                        }
                        $nrc->save();

                        // Notification::send(User::find($auditor_user_id), new RebuttalRaised(['upper_text'=>count($request->rebuttals).' Rebuttal raised on your audit.']));
                    }
                } else {
                    $nrc = new Rebuttal;
                    $nrc->raised_by_user_id = Auth::user()->id;
                    $nrc->raw_data_id = $raw_data_id;
                    $nrc->audit_id = $audit_id;
                    $temp_data = explode("_", $value['sp']);
                    $nrc->parameter_id = $temp_data[0];
                    $nrc->sub_parameter_id = $temp_data[1];
                    $nrc->remark = $value['remark'];
                    $nrc->re_rebuttal_id = null;
                    if (isset($value['artifact'])) {
                        $value['artifact']->store("company/_" . Auth::user()->company_id . '/rebuttals/');
                        $nrc->artifact = $value['artifact']->hashName();
                    }
                    $nrc->save();
                    //Notification::send(User::find($auditor_user_id), new RebuttalRaised(['upper_text'=>count($request->rebuttals).' Rebuttal raised on your audit.']));

                }
            }
        }
        return redirect('partner/single_audit_detail/' . $request->audit_id)->with('success', 'Rebuttal raised successfully.');
    }
    public function raised_rebuttal_list()
    {
        $data = [];
        if (Auth::user()->hasRole('qtl') || Auth::user()->id == 219) {
            if (Auth::user()->hasRole('qc')) {
                $client_id = 9;
            } else {
                $client = ClientsQtl::where('qtl_user_id', Auth::user()->id)->first();
                $client_id = $client->client_id;
            }

          
            if(Auth::user()->parent_client == 23){
           
                $data = Rebuttal::whereHas('raw_data', function (Builder $query) use ($client_id) {
                    $query->where('client_id', $client_id)
                    ->where('qtl_id',Auth::user()->id);
                })->whereHas('audit_data', function (Builder $query) {
                    $query->where('deleted', 0);
                })->where('status', 0)->with(['raw_data', 'audit_data', 'parameter', 'sub_parameter'])->get();
    
            }else{
                $data = Rebuttal::whereHas('raw_data', function (Builder $query) use ($client_id) {
                    $query->where('client_id', $client_id);
                })->whereHas('audit_data', function (Builder $query) {
                    $query->where('deleted', 0);
                })->where('status', 0)->with(['raw_data', 'audit_data', 'parameter', 'sub_parameter'])->get();
    
            }
        } 
        else if (Auth::user()->id == 293) {
            if (Auth::user()->hasRole('qc')) {
                $client_id = 13;
            } else {
                $client = ClientsQtl::where('qtl_user_id', Auth::user()->id)->first();
                $client_id = $client->client_id;
            }

            $data = Rebuttal::whereHas('raw_data', function (Builder $query) use ($client_id) {
                $query->where('client_id', $client_id);
            })->whereHas('audit_data', function (Builder $query) {
                $query->where('deleted', 0);
            })->where('status', 0)->with(['raw_data', 'audit_data', 'parameter', 'sub_parameter'])->get();
        }
        else if(Auth::user()->hasRole('qc')){
          
            $client = ClientsQtl::where('qtl_user_id', Auth::user()->id)->first();
            if(!isset($client)){
               
             $client_id = Auth::user()->parent_client;
            }else{
                $client_id = $client->client_id;
            }
            
                
            $data = Rebuttal::whereHas('raw_data', function (Builder $query) use ($client_id) {
                $query->where('client_id', $client_id);
            })->whereHas('audit_data', function (Builder $query) {
                $query->where('deleted', 0);
            })->where('status', 0)->orderBy('created_at', 'desc')->with(['raw_data', 'audit_data', 'parameter', 'sub_parameter'])->get();
        }
        else {           
            $data = Rebuttal::whereHas('raw_data', function (Builder $query) {
                $query->where('qa_id', Auth::user()->id);
            })->whereHas('audit_data', function (Builder $query) {
                $query->where('deleted', 0);
            })->where('status', 0)->orderby('created_at','desc')
            ->orderBy('created_at', 'desc')->with(['raw_data', 'audit_data', 'parameter', 'sub_parameter'])->get();
        }

        return view('rebuttal.raised_rebuttal_list', compact('data'));
    }

    public function rebuttal_status($rebuttal_id)
    {


        $rebuttal_data = Rebuttal::find(Crypt::decrypt($rebuttal_id));
        // $audit_data = Audit::with(['audit_parameter_result','audit_results','audit_results.reason_type','audit_results.reason'])->find($rebuttal_data->audit_id);
        $audit_data = Audit::with(['audit_parameter_result', 'audit_results', 'audit_results.reason_type', 'audit_results.reason'])->findOrFail($rebuttal_data->audit_id);


        $raw_data = RawData::find($audit_data->raw_data_id);
        //added by palak for bod
        $bod_reasons = BodReason::get();

        $parameter_data = QmSheetParameter::find($rebuttal_data->parameter_id);
        $sub_parameter_data = QmSheetSubParameter::find($rebuttal_data->sub_parameter_id);
        //added by palak for bod end

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
            $final_data[$value->id]['id'] = $value->id;
            if ($value->is_non_scoring) {
                foreach ($value->qm_sheet_sub_parameter as $skey => $svalue) {
                    $t_1 = $audit_sp_results->where('parameter_id', $value->id)->where('sub_parameter_id', $svalue->id)->toArray();
                    $temp_result = $t_1[array_key_first($t_1)];
                    $final_data[$value->id]['sp'][] = ['is_non_scoring' => $value->is_non_scoring, 'id' => $svalue->id, 'name' => $svalue->sub_parameter, 'detail' => $svalue->details, 'selected_option' => return_non_scoring_observation($temp_result['selected_option']), 'scored' => $temp_result['score'], 'reason_type' => "-", 'reason' => '-', 'remark' => $temp_result['remark']];
                }
            } else {
                foreach ($value->qm_sheet_sub_parameter as $skey => $svalue) {

                    $t_1 = $audit_sp_results->where('parameter_id', $value->id)->where('sub_parameter_id', $svalue->id)->toArray();
                    $temp_result = $t_1[array_key_first($t_1)];

                    if (isset($temp_result['reason_type']['name'])) {
                    } else {
                        $temp_result['reason']['name'] = "-";
                        $temp_result['reason_type']['name'] = "-";
                        // print_r(json_encode($svalue)); die;
                    }
                    if (!isset($temp_result['reason']['name'])) {
                        $temp_result['reason']['name'] = "-";
                    }
                    $final_data[$value->id]['sp'][] = ['is_non_scoring' => $value->is_non_scoring, 'id' => $svalue->id, 'name' => $svalue->sub_parameter, 'detail' => $svalue->details, 'selected_option' => return_general_observation($temp_result['selected_option']), 'scored' => $temp_result['score'], 'reason_type' => $temp_result['reason_type']['name'], 'reason' => $temp_result['reason']['name'], 'remark' => $temp_result['remark']];
                }
            }
        }

        return view('rebuttal.rebuttal_status', compact('audit_data', 'raw_data', 'final_data', 'rebuttal_data', 'all_sub_parameters', 'bod_reasons', 'parameter_data', 'sub_parameter_data'));
    }
    public function get_para_subpara_rebuttal_status($rebuttal_id)
    {
        $rebuttal_data = Rebuttal::find($rebuttal_id);
        $parameter_data = QmSheetParameter::find($rebuttal_data->parameter_id);
        $sub_parameter_data = QmSheetSubParameter::find($rebuttal_data->sub_parameter_id);
        $final_data['parameter'] = $parameter_data->parameter;
        $final_data['sub_parameter'] = $sub_parameter_data->sub_parameter;
        $final_data['details'] = $sub_parameter_data->details;
        $final_data['remark'] = $rebuttal_data->remark;


        $final_data['audit_id'] = $rebuttal_data->audit_id;


        return response()->json(['status' => 200, 'message' => "Success", 'data' => $final_data], 200);
    }
    public function reply_rebuttal(Request $request)
    {


        if ($request->reply_status == 1) {

            //accepted
            $rebuttal = Rebuttal::where('id', $request->rebuttal_id)->with('audit_data')->get();
            $rebuttal = $rebuttal[0];
            $rebuttal->status = 1;
            $rebuttal->reply_remark = $request->reply_remark;

            if ($request->bod_reasons != "") {
                $rebuttal->accept_bod = 1;
                $rebuttal->bod_reason = $request->bod_reasons;
            }


            //added by palak for bod end
            if ($request->file('file')) {

                /* $ext = $request->file('file')->getClientOriginalExtension();
                 if($ext == "img" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || 
                     $ext == "IMG" || $ext == "JPEG" || $ext == "JPG" || $ext == "PNG"){ */
                $image = $request->file('file');
                $imageName = time() . '_' . $image->getClientOriginalName();

                $image->move(public_path('rebuttal_reply_artifacts'), $imageName);
                $rebuttal->reply_artifact = $imageName;
            }

            $rebuttal->save();
            Notification::send(User::find($rebuttal->raised_by_user_id), new RebuttalReply(['upper_text' => "Rebuttal Accepted.", 'audit_id' => Crypt::encrypt($rebuttal->audit_id)]));
        } else {

            //rejected
            $rebuttal = Rebuttal::where('id', $request->rebuttal_id)->with(['audit_data', 'audit_data.client'])->get();
            $rebuttal = $rebuttal[0];
            $rebuttal->status = 2;
            $rebuttal->reply_remark = $request->reply_remark;

            //exttention of audit in rebuttal pool after rebuttal reject for more time to partner

            $re_rebuttal_extenstion_times = skipHoliday(strtotime($rebuttal->audit_data->rebuttal_tat), $rebuttal->audit_data->client->holiday, $rebuttal->audit_data->client->re_rebuttal_time, $rebuttal->audit_data->client->re_rebuttal_time);

            $rebuttal->audit_data->rebuttal_tat = $re_rebuttal_extenstion_times[0];
            if ($request->file('file')) {

                /* $ext = $request->file('file')->getClientOriginalExtension();
                 if($ext == "img" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || 
                     $ext == "IMG" || $ext == "JPEG" || $ext == "JPG" || $ext == "PNG"){ */
                $image = $request->file('file');
                $imageName = time() . '_' . $image->getClientOriginalName();


                $image->move(public_path('rebuttal_reply_artifacts'), $imageName);
                $rebuttal->reply_artifact = $imageName;
                /* } else{
                     return response()->json(['status'=>500,'message'=>"Fail File should be image"], 500);
                 } */
            }
            $rebuttal->audit_data->save();


            //exttention of audit in rebuttal pool after rebuttal reject for more time to partner

            $rebuttal->save();
            Notification::send(User::find($rebuttal->raised_by_user_id), new RebuttalReply(['upper_text' => "Rebuttal Rejected.", 'audit_id' => Crypt::encrypt($rebuttal->audit_id)]));
        }
        return response()->json(['status' => 200, 'message' => "Success"], 200);
    }
    public function update_basic_audit_data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'audit_id' => 'required',
            'rebuttal_id' => 'required',
            'customer_name' => 'required',
            'phone_number' => 'required',
            'qrc_2' => 'required',
            'language_2' => 'required',
            'case_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('rebuttal_status/' . $request->rebuttal_id)
                ->withErrors($validator)
                ->withInput();
        } else {
            $audit_data = Audit::with('raw_data')->find(Crypt::decrypt($request->audit_id));
            $audit_data->raw_data->customer_name = $request->customer_name;
            $audit_data->raw_data->phone_number = $request->phone_number;
            $audit_data->raw_data->disposition = $request->disposition;
            $audit_data->raw_data->call_time = $request->call_time;
            $audit_data->raw_data->call_duration = $request->call_duration;
            $audit_data->raw_data->save();

            $audit_data->qrc_2 = $request->qrc_2;
            $audit_data->language_2 = $request->language_2;
            $audit_data->case_id = $request->case_id;
            $audit_data->overall_summary = $request->overall_summary;
            $audit_data->feedback = $request->feedback;
            $audit_data->save();
            return redirect('rebuttal_status/' . $request->rebuttal_id)->with('success', 'Audit basic data updated successfully.');
        }
    }
}
