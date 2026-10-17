<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Audit;
use App\AuditParameterResult;
use App\AuditResult;
use App\RawData;
use App\TmpAudit;
use App\TmpAuditParameterResult;
use App\TmpAuditResult;
use App\AuditLog;
use DateTime;



class auditPool extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tmp:pool';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command is used to save tmp audit data to audit';

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

        $tmpdata = TmpAudit::with(['tmp_audit_parameter_result','tmp_audit_results','client'])->whereDate('created_at', '<', date("Y-m-d"))->get();
        
        // foreach ($tmpdata as $value) {
        //     echo "$value->id,";
            
        // }


        foreach ($tmpdata as $data) {

            $audit_exists = Audit::where('raw_data_id',$data->raw_data_id)->count();

            $raw_data_not_exists = RawData::where('id',$data->raw_data_id)->count();


            if($audit_exists == 0 && $raw_data_not_exists > 0 && $data->tmp_audit_parameter_result->count() > 0){
            
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
            $new_ar->auditor_time_spend_in_secs = $data->auditor_time_spend_in_secs;
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
            else{
                $data->tmp_audit_results->each->delete();
                $data->tmp_audit_parameter_result->each->delete();
                $data->delete();
            }
        }

     }
}
