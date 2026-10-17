<?php

namespace App\Http\Controllers;

ini_set('memory_limit', '-1');
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

use App\Audit;
use App\ReasonType;
use App\QmSheetParameter;
use App\ReLabel;
use App\RawData;
use App\AuditDeletionHistory;
use App\DataPurge;



use App\AuditLogPurge;
use App\AuditLog;
use App\LoggedUser;
use App\AuditorPerformenceReport;
use DateTime;

use App\AuditParameterResult;
use App\AuditResult;

use App\TmpAudit;
use App\TmpAuditParameterResult;
use App\TmpAuditResult;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class DataPurgeController extends Controller
{
    public function index(){
        $purge = RawData::where('dump_date', '<', Carbon::now()->subDays(7))->where('status',0)->delete();
        $no_records = new DataPurge;
        $no_records->no_of_record = $purge; 
        $no_records->save();
    }

    public function start_audit_log_purge(){

        

        $report_data = AuditLog::where('updated_at', '<', Carbon::now()->subDays(7))->get();

        $auditors = AuditLog::where('updated_at', '<', Carbon::now()->subDays(7))->distinct()->pluck('auditor_id');

        
        foreach($auditors as $key => $value){

            $audits = Audit::where('created_at', '<=', Carbon::now()->subDays(7))->where('created_at', '>=', Carbon::now()->subDays(14))->where('audited_by_id',$value)->count('id');
            
          //  $audits = Audit::where('created_at', '<=', Carbon::now()->subDays(7))->where('created_at', '>=', Carbon::now()->subDays(14))->where('audited_by_id',$value)->count('id');
            
            $data = AuditLog::where('updated_at', '<', Carbon::now()->subDays(7))->where('auditor_id',$value)->sum('total_minuts');
            
            $performance_report = new AuditorPerformenceReport;
            $performance_report->auditor_id = $value;
            $performance_report->total_spend_time = $data;
            $performance_report->audit_done = $audits;
            $performance_report->start_date = Carbon::now()->subDays(14);
            $performance_report->end_date = Carbon::now()->subDays(7);
            $performance_report->save();
            
        }
        $purge = AuditLog::where('updated_at', '<', Carbon::now()->subDays(7))->delete();
        $no_records = new AuditLogPurge;
        $no_records->no_of_record = $purge; 
        $no_records->save();
    }

    public function temp_to_main_pool(){
        $tmpdata = TmpAudit::with(['tmp_audit_parameter_result','tmp_audit_results','client'])->whereDate('created_at', '<', date("Y-m-d"))->get();

        foreach ($tmpdata as $data) {
            $date = date("Y-m-d H:i:s",strtotime("+1 day", strtotime("$data->audit_date")));
            $new_ar = new Audit;
            $new_ar->company_id = $data->company_id;
            $new_ar->client_id = $data->client_id;
            $new_ar->partner_id = $data->partner_id;
            $new_ar->qm_sheet_id = $data->qm_sheet_id;
            $new_ar->process_id = $data->process_id;
            $new_ar->raw_data_id = $data->raw_data_id;
            $new_ar->audited_by_id = $data->audited_by_id;
            $new_ar->is_critical = $data->is_critical;
            $new_ar->overall_score = $data->overall_score;
            $new_ar->with_fatal_score_per = $data->with_fatal_score_per;
            $new_ar->audit_date = $date;
            $new_ar->qrc_2 = $data->qrc_2;
            $new_ar->language_2 = $data->language_2;
            $new_ar->rebuttal_status = $data->rebuttal_status;
            $new_ar->case_id = $data->case_id;
            $new_ar->overall_summary = $data->overall_summary;
            $new_ar->feedback_status = $data->feedback_status;
            $new_ar->feedback_date = $data->feedback_date;
            $new_ar->feedback = $data->feedback;
            $new_ar->type_id = $data->type_id;

            $new_ar->mode_id = $data->mode_id;
            $new_ar->rca1_id = $data->rca1_id;
            $new_ar->rca2_id = $data->rca2_id;
            $new_ar->rca3_id = $data->rca3_id;
            $new_ar->rca_other_detail = $data->rca_other_detail;

            $new_ar->type_2_rca_mode_id = $data->type_2_rca_mode_id;
            $new_ar->type_2_rca1_id = $data->type_2_rca1_id;
            $new_ar->type_2_rca2_id = $data->type_2_rca2_id;
            $new_ar->type_2_rca3_id = $data->type_2_rca3_id;
            $new_ar->type_2_rca_other_detail = $data->type_2_rca_other_detail;

            $new_ar->feedback_comment = $data->feedback_comment;
            $new_ar->qc_status = $data->qc_status;
            $new_ar->qc_comment = $data->qc_comment;
            $new_ar->good_bad_call = $data->good_bad_call;
            $new_ar->good_bad_call_file = $data->good_bad_call_file;
            $new_ar->refrence_number = $data->refrence_number;
            $new_ar->qc_date = $data->qc_date;
            $new_ar->qc_revised_score_with_fatal = $data->qc_revised_score_with_fatal;
            $new_ar->qc_revised_score_with_fatal = $data->qc_revised_score_without_fatal;
            $new_ar->last_updated_at = $data->updated_at;

            // function to fetch date
                $result = skipHoliday(strtotime($date),$data->client->holiday,$data->client->qc_time,$data->client->rebuttal_time);
            // function end

            $new_ar->qc_tat = $result[0];
            $new_ar->rebuttal_tat = $result[1];
            $new_ar->save();
                foreach ($data->tmp_audit_parameter_result as $value) {
                    $new_arb = new AuditParameterResult;
                    $new_arb->audit_id =  $new_ar->id;
                    $new_arb->parameter_id = $value->parameter_id;
                    $new_arb->qm_sheet_id = $value->qm_sheet_id;
                    $new_arb->orignal_weight = $value->orignal_weight;
                    $new_arb->temp_weight = $value->temp_weight;
                    $new_arb->with_fatal_score = $value->with_fatal_score;
                    $new_arb->without_fatal_score = $value->without_fatal_score;
                    $new_arb->with_fatal_score_per = $value->with_fatal_score_per;
                    $new_arb->without_fatal_score_pre = $value->without_fatal_score_pre;
                    $new_arb->is_critical = $value->is_critical;
                    $new_arb->save();
                }
                foreach ($data->tmp_audit_results as $extra) {
                    $new_arc = new AuditResult;
                    $new_arc->audit_id = $new_ar->id;
                    $new_arc->parameter_id = $extra->parameter_id;
                    $new_arc->sub_parameter_id = $extra->sub_parameter_id;
                    $new_arc->is_critical = $extra->is_critical;
                    $new_arc->is_non_scoring =  $extra->is_non_scoring;
                    $new_arc->selected_option = $extra->selected_option;
                    $new_arc->score = $extra->score;
                    $new_arc->after_audit_weight = $extra->after_audit_weight;
                    $new_arc->failure_reason = $extra->failure_reason;
                    $new_arc->remark = $extra->remark;
                    $new_arc->reason_type_id = $extra->reason_type_id;
                    $new_arc->reason_id = $extra->reason_id;
                    $new_arc->save();
                }
                $data->tmp_audit_results->each->delete();
                $data->tmp_audit_parameter_result->each->delete();
                $data->delete();
        }
    }

    public function transfer_audit_from_temp_to_main_pool(Request $request)
    {
        $tmpdata = TmpAudit::with(['tmp_audit_parameter_result','tmp_audit_results','client'])->whereDate('created_at', '<', date("Y-m-d"))->get();

       /*  echo "<pre>";
        print_r($tmpdata);
        dd(); */
        //$data = TmpAudit::with(['tmp_audit_parameter_result','tmp_audit_results','client'])->where('id',264)->first();
       foreach ($tmpdata as $data) {
            $date = date("Y-m-d H:i:s",strtotime("+1 day", strtotime("$data->audit_date")));
            $new_ar = new Audit;
            $new_ar->company_id = $data->company_id;
            $new_ar->client_id = $data->client_id;
            $new_ar->partner_id = $data->partner_id;
            $new_ar->qm_sheet_id = $data->qm_sheet_id;
            $new_ar->process_id = $data->process_id;
            $new_ar->raw_data_id = $data->raw_data_id;
            $new_ar->audited_by_id = $data->audited_by_id;
            $new_ar->is_critical = $data->is_critical;
            $new_ar->overall_score = $data->overall_score;
            $new_ar->with_fatal_score_per = $data->with_fatal_score_per;
            $new_ar->audit_date = $date;
            $new_ar->qrc_2 = $data->qrc_2;
            $new_ar->language_2 = $data->language_2;
            $new_ar->rebuttal_status = $data->rebuttal_status;
            $new_ar->case_id = $data->case_id;
            $new_ar->overall_summary = $data->overall_summary;
            $new_ar->feedback_status = $data->feedback_status;
            $new_ar->feedback_date = $data->feedback_date;
            $new_ar->feedback = $data->feedback;
            $new_ar->type_id = $data->type_id;
            $new_ar->mode_id = $data->mode_id;
            $new_ar->rca1_id = $data->rca1_id;
            $new_ar->rca2_id = $data->rca2_id;
            $new_ar->rca3_id = $data->rca3_id;
            $new_ar->rca_other_detail = $data->rca_other_detail;
            $new_ar->feedback_comment = $data->feedback_comment;
            $new_ar->qc_status = $data->qc_status;
            $new_ar->qc_comment = $data->qc_comment;
            $new_ar->good_bad_call = $data->good_bad_call;
            $new_ar->good_bad_call_file = $data->good_bad_call_file;
            $new_ar->refrence_number = $data->refrence_number;
            $new_ar->qc_date = $data->qc_date;
            $new_ar->qc_revised_score_with_fatal = $data->qc_revised_score_with_fatal;
            $new_ar->qc_revised_score_with_fatal = $data->qc_revised_score_without_fatal;
            $new_ar->last_updated_at = $data->updated_at;

            // function to fetch date
                $result = skipHoliday(strtotime($date),$data->client->holiday,$data->client->qc_time,$data->client->rebuttal_time);
            // function end

            $new_ar->qc_tat = $result[0];
            $new_ar->rebuttal_tat = $result[1];
            $new_ar->save();
            /* echo "<pre>";
            print_r("hiiii");
            dd(); */
            foreach ($data->tmp_audit_parameter_result as $value) {
               /*  echo "<pre>";
                    echo $new_ar->id;
                dd(); */
                $new_arb = new AuditParameterResult;
                $new_arb->audit_id =  $new_ar->id;
                $new_arb->parameter_id = $value->parameter_id;
                $new_arb->qm_sheet_id = $value->qm_sheet_id;
                $new_arb->orignal_weight = $value->orignal_weight;
                $new_arb->temp_weight = $value->temp_weight;
                $new_arb->with_fatal_score = $value->with_fatal_score;
                $new_arb->without_fatal_score = $value->without_fatal_score;
                $new_arb->with_fatal_score_per = $value->with_fatal_score_per;
                $new_arb->without_fatal_score_pre = $value->without_fatal_score_pre;
                $new_arb->is_critical = $value->is_critical;
                $new_arb->save();
                
            }
            foreach ($data->tmp_audit_results as $extra) {
                $new_arc = new AuditResult;
                $new_arc->audit_id = $new_ar->id;
                $new_arc->parameter_id = $extra->parameter_id;
                $new_arc->sub_parameter_id = $extra->sub_parameter_id;
                $new_arc->is_critical = $extra->is_critical;
                $new_arc->is_non_scoring =  $extra->is_non_scoring;
                $new_arc->selected_option = $extra->selected_option;
                $new_arc->score = $extra->score;
                $new_arc->after_audit_weight = $extra->after_audit_weight;
                $new_arc->failure_reason = $extra->failure_reason;
                $new_arc->remark = $extra->remark;
                $new_arc->reason_type_id = $extra->reason_type_id;
                $new_arc->reason_id = $extra->reason_id;
                $new_arc->save();
            }
            $data->tmp_audit_results->each->delete();
            $data->tmp_audit_parameter_result->each->delete();
            $data->delete();
    
       }

       echo "hooo";
       dd();
         //   return redirect('tmp_audited_list/'.$request->qm_sheet_id)->with('success','Audit moved to main pool successfully.');
    }
    public function delete_audit($rawdata_id, $audit_id){
      //  echo $rawdata_id . '<br>'. $audit_id;
        $user_ip_address = request()->ip();
        $user_id = Auth::user()->id;
        DB::table('raw_data')->where('id', $rawdata_id)->update(['deleted' => 1]);
        DB::table('audits')->where('id', $audit_id)->update(['deleted' => 1]);

        $delete_history = new AuditDeletionHistory;
        $delete_history->raw_data_id = $rawdata_id; 
        $delete_history->audit_id = $audit_id;
        $delete_history->user_id = $user_id; 
        $delete_history->user_ip_address = $user_ip_address;
        $delete_history->save();
        echo "Data deleted successfully";
        
       
        dd();
    
    }

    public function delete_audit_page(Request $request){
        // Continue
        $data=[];

      
       $all_reason_type = ReasonType::with('reasons')->get();

       $params_data = QmSheetParameter::where('qm_sheet_id',$request->qm_sheet_id)->with('qm_sheet_sub_parameter')->get();
       $repeater_param_data=[];
       foreach ($params_data as $key => $value) {
           foreach ($value->qm_sheet_sub_parameter as $keyb => $valueb) {
               $repeater_param_data[$value->id][$valueb->id]=['name'=>$valueb->sub_parameter,'observation'=>null,'scored'=>0,'scorable'=>0,'remark'=>null,'reason_type'=>null,'reason'=>null];
           }
       }
       
       $partner_id = $request->partner_id;
       $partner_location_id = $request->location_id;
       $client_id = $request->client_id;
       $lob = $request->lob;
       $brand_name = $request->brand_name;
       $circle = $request->circle;

         /* echo $request->end_date;
         dd(); */


       //dd(date_to_db($request->end_date));
/* 
       ->where('partner_id','LIKE',$partner_id)
       ->where('lob','LIKE',$lob)
       ->where('brand_name','LIKE',$brand_name)
       ->where('circle','LIKE',$circle) */
       
       $temp_audit_data = Audit::whereHas('raw_data', function (Builder $query) use ($partner_id,$partner_location_id,$client_id,$lob,$brand_name,$circle) {
                                 $query->where('lob','LIKE',$lob)
                                 ->where('partner_location_id','LIKE',$partner_location_id);

                                 })->where('qm_sheet_id',$request->qm_sheet_id)
                                 ->where('client_id',$client_id)
                                ->where('partner_id','LIKE',$partner_id)
                                 ->whereDate('audit_date','>=',date_to_db($request->start_date))
                                 ->whereDate('audit_date','<=',date_to_db($request->end_date))->with(['raw_data','raw_data.location_data','raw_data.partner_detail','audit_results','qa_qtl_detail','audit_results.reason_type','audit_results.reason'])->get();
        $labels = ReLabel::where('qm_sheet_id',$request->qm_sheet_id)->first();
        foreach ($temp_audit_data as $key => $value) {
            
                $basic['temp_raw_data'] = $value->raw_data;
                $basic['auditor'] = $value->auditor->name;
                $basic['audit_date'] = $value->audit_date;
                $basic['audit_id'] = $value->id;
                $basic['partner'] = $value->raw_data->partner_detail->name;
                //dd($value->raw_data->location_data->name);
                $basic['location'] = $value->raw_data->location_data->name;
                $basic['case_id'] = $value->case_id;
                $basic['audited_by'] = $value->qa_qtl_detail->name;
                $basic['qrc_2'] = $value->qrc_2;
                $basic['language_2'] = $value->language_2;
                $basic['refrence_number'] = $value->refrence_number;

                $basic['overall_summary'] = $value->overall_summary;
                $basic['without_fatal_score'] = $value->overall_score;
                $basic['with_fatal_score_per'] = $value->with_fatal_score_per;

                $basic['rca_type'] = ($value->type_id)?$value->rca_type->name:'-';

                $basic['a_rca_mode'] = ($value->mode_id)?$value->a_rca_mode->name:'-';
                $basic['a_rca_type_1'] = ($value->rca1_id)?$value->a_rca_type_1->name:'-';
                $basic['a_rca_type_2'] = ($value->rca2_id)?$value->a_rca_type_2->name:'-';
                $basic['a_rca_type_3'] = ($value->rca3_id)?$value->a_rca_type_3->name:'-';
                $basic['a_rca_type_other'] = $value->rca_other_detail;

                $basic['b_rca_mode'] = ($value->type_2_rca_mode_id)?$value->b_rca_mode->name:'-';
                $basic['b_rca_type_1'] = ($value->type_2_rca1_id)?$value->b_rca_type_1->name:'-';
                $basic['b_rca_type_2'] = ($value->type_2_rca2_id)?$value->b_rca_type_2->name:'-';
                $basic['b_rca_type_3'] = ($value->type_2_rca3_id)?$value->b_rca_type_3->name:'-';
                $basic['b_rca_type_other'] = $value->type_2_rca_other_detail;

                $temp_result = $value->audit_results;
                foreach ($repeater_param_data as $keyb => $valueb) {
                    
                    foreach ($valueb as $keyc => $valuec) {
                        $to_filter_row_id = $temp_result->where('parameter_id',$keyb)->where('sub_parameter_id',$keyc)->sum('id');

                        $to_filter_row = $temp_result->find($to_filter_row_id);
                        if(!is_null($to_filter_row))
                        {
                        if($to_filter_row->is_non_scoring == 1)
                            $repeater_param_data[$keyb][$keyc]['observation'] = return_non_scoring_observation($to_filter_row->selected_option);
                        else
                            $repeater_param_data[$keyb][$keyc]['observation'] = return_general_observation($to_filter_row->selected_option);

                        $repeater_param_data[$keyb][$keyc]['scorable'] = $to_filter_row->after_audit_weight;

                        
                        if($to_filter_row->reason_type_id)
                            {
                                $temp_reason_type = $all_reason_type->find($to_filter_row->reason_type_id);
                                $reason_type = $temp_reason_type->name;
                                $reasons = $temp_reason_type->reasons;
                                $temp_reason = $reasons->find($to_filter_row->reason_id);

                                if($temp_reason)
                                    $reason = $temp_reason->name;
                                else
                                    $reason = $to_filter_row->reason_id;
                            }
                        else
                            {
                                $reason_type = '-';
                                $reason = '-';
                            }
                        $repeater_param_data[$keyb][$keyc]['reason_type'] = $reason_type;
                        $repeater_param_data[$keyb][$keyc]['reason'] = $reason;
                        $repeater_param_data[$keyb][$keyc]['remark'] = $to_filter_row->remark;
                        $repeater_param_data[$keyb][$keyc]['scored']=$temp_result->where('parameter_id',$keyb)->where('sub_parameter_id',$keyc)->sum('score');
                        }
                    }
                }
                $basic['audit'] = $repeater_param_data;

                $data[] = $basic;

       }
       // dd($data);
       

       return view('deletion.audit_raw_data_deletion',compact(['data','repeater_param_data','labels']));
    }
}
