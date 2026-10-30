<style scoped>
input.screenshot:before {
    width: 71px;
    height: 32px;
    font-size: 12px;
    line-height: 32px;
    content: "ADD Image";
    display: inline-block;
    background: #646c9a;
    color: white;
    text-align: center;
    font-family: Helvetica, Arial, sans-serif;
    cursor: pointer;
}

input.screenshot::-webkit-file-upload-button {
    visibility: hidden;
}
</style>

<template>
    <div>
        <form class="kt-form kt-form--label-right" id="audit_component_form" enctype="multipart/form-data">
            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__head"
                    style="background-image: linear-gradient(to right, rgb(132, 94, 194), rgb(144, 109, 198), rgb(156, 125, 201), rgb(168, 140, 205), rgb(179, 156, 208));">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title kt-font-light">
                            Basic Details
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <a href="#" class="btn btn-primary" id="kt_dashboard_daterangepicker"
                                data-toggle="kt-tooltip" title="Select raw data daterange" data-placement="left">
                                <span class="kt-subheader__btn-daterange-title"
                                    id="kt_dashboard_daterangepicker_title">Today</span>&nbsp;
                                <span class="kt-subheader__btn-daterange-date"
                                    id="kt_dashboard_daterangepicker_date">Aug 16</span>
                                <i class="flaticon2-calendar-1"></i>
                            </a>
                            <input type="text" class="form-control" placeholder="Search Call ID"
                                v-model="search_call_id" />
                            <button type="button" class="btn btn-success btn-elevate btn-icon"
                                @click="getRawDataOnDateRange">
                                <i class="la la-search"></i>
                            </button>
                            <br /><small>By default, calls list is based on current
                                audit Cycle {{ audit_cycle }}</small>
                        </div>
                    </div>


                    <div>

                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Communication Instance ID(SR ID):</label>
                                <v-select label="value" :options="my_alloted_call_list"
                                    v-model="sheet_basic_data[0].comm_instance_id" @input="getRawData()"></v-select>
                            </div>
                            <div class="col-lg-4">
                                <label>Client:</label>
                                <input type="text" class="form-control" v-model="sheet_basic_data[0].client"
                                    readonly="readonly" />
                            </div>
                            <div class="col-lg-4">
                                <label>Partner</label>
                                <input type="text" class="form-control" v-model="sheet_basic_data[0].partner_name"
                                    readonly="readonly" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Audit Date:</label>
                                <input type="text" class="form-control" readonly
                                    :value="submission_data[0].audit_date" />
                            </div>
                            <div class="col-lg-4">
                                <label>QA Name</label>
                                <input type="text" class="form-control" :value="auditorName" readonly="readonly" />
                            </div>
                            <div class="col-lg-4">
                                <label>Agent Name</label>
                                <input type="text" class="form-control" v-model="submission_data[0].agent_name" />
                            </div>
                            <div class="col-lg-4">
                                <label>EMP ID</label>
                                <input type="text" class="form-control" v-model="submission_data[0].emp_id" />
                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>SR Month:</label>
                                <input type="text" class="form-control" :value="submission_data[0].month"
                                    readonly="readonly" />
                            </div>
                            <div class="col-lg-4">
                                <label>Case ID</label>
                                <input type="text" class="form-control" v-model="submission_data[0].case_id" />
                            </div>
                            <div class="col-lg-4">
                                <label>MOBILE_HASH</label>
                                <input type="text" class="form-control" v-model="submission_data[0].reference_no" />
                            </div>
                            <div class="col-lg-4">
                                <label>Cst ID</label>
                                <input type="text" class="form-control" v-model="submission_data[0].customer_id" />
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Relationship Number</label>
                                <input type="text" class="form-control"
                                    v-model="submission_data[0].relationship_number" />
                            </div>
                            <div class="col-lg-4">
                                <label>Case Opened date</label>
                                <input type="text" class="form-control" v-model="submission_data[0].case_create_date" />
                            </div>
                            <div class="col-lg-4">
                                <label>Case origin</label>
                                <input type="text" class="form-control" v-model="submission_data[0].case_origin" />
                            </div>
                            <div class="col-lg-4">
                                <label>CASE_ORIGIN_CHANNEL</label>
                                <input type="text" class="form-control"
                                    v-model="submission_data[0].case_origin_channel" />
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Channel_Master</label>
                                <input type="text" class="form-control" v-model="submission_data[0].channel_master" />
                            </div>
                            <div class="col-lg-4">
                                <label>category</label>
                                <input type="text" class="form-control" v-model="submission_data[0].category" />
                            </div>

                            <div class="col-lg-4">
                                <label>Type</label>
                                <input type="text" class="form-control" v-model="submission_data[0].call_type" />
                            </div>
                            <div class="col-lg-4">
                                <label>Sub Type</label>
                                <input type="text" class="form-control" v-model="submission_data[0].call_sub_type" />
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Case Reason</label>
                                <input type="text" class="form-control" v-model="submission_data[0].case_reason" />
                            </div>
                            <div class="col-lg-4">
                                <label>Solution/Disposition</label>
                                <input type="text" class="form-control" v-model="submission_data[0].solution" />
                            </div>
                            <div class="col-lg-4">
                                <label>Current Assigned Queue</label>
                                <input type="text" class="form-control"
                                    v-model="submission_data[0].current_assigned_queue" />
                            </div>
                            <div class="col-lg-4">
                                <label>Business</label>
                                <input type="text" class="form-control" v-model="submission_data[0].brand_name" />
                            </div>


                        </div>

                        <div class="form-group row">

                            <div class="col-lg-4">
                                <label>Product</label>
                                <input type="text" class="form-control" v-model="submission_data[0].product_id" />
                            </div>
                            <div class="col-lg-4">
                                <label>Product name</label>
                                <input type="text" class="form-control"
                                    v-model="submission_data[0].product_business_family" />
                            </div>
                            <div class="col-lg-4">
                                <label>Unit</label>
                                <input type="text" class="form-control" v-model="submission_data[0].case_number" />
                            </div>
                            <div class="col-lg-4">
                                <label>userName</label>
                                <input type="text" class="form-control" v-model="submission_data[0].user_name" />
                            </div>

                        </div>

                        <div class="form-group row">
                            <!-- <div class="col-lg-4">
                                <label>Partner Name</label>
                                <input type="text" class="form-control" v-model="submission_data[0].partner_name" />
                            </div> -->
                            <div class="col-lg-4">
                                <label>FPR</label>
                                <input type="text" class="form-control" v-model="submission_data[0].fpr" />
                            </div>
                            <div class="col-lg-4">
                                <label>Repeat Number</label>
                                <input type="text" class="form-control" v-model="submission_data[0].issues" />
                            </div>
                            <div class="col-lg-4">
                                <label>REPEAT_DAY_DIFF_BUCKET</label>
                                <input type="text" class="form-control" v-model="submission_data[0].sub_issues" />
                            </div>


                        </div>

                        <div class="form-group row">

                            <div class="col-lg-4">
                                <label>PACT Category</label>
                                <select class="form-control" v-model="submission_data[0].l2_ticket_disposition">
                                    <option value="">Select one!</option>
                                    <option value="Agent">Agent</option>
                                    <option value="Customer">Customer</option>
                                    <option value="Process">Process</option>
                                    <option value="Technology">Technology</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label>Status</label>
                                <select class="form-control" v-model="submission_data[0].case_status">
                                    <option value="">Select one!</option>
                                    <option value="Resolved">Resolved</option>
                                    <option value="Open">Open</option>
                                    <option value="NA">NA</option>

                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label>Owner</label>
                                <select class="form-control" v-model="submission_data[0].owner">
                                    <option value="">Select one!</option>
                                    <option value="TL">TL</option>
                                    <option value="Ops">Ops</option>
                                    <option value="Manager">Manager</option>
                                    <option value="IT">IT</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label>Predective Sentiment</label>
                                <select class="form-control" v-model="submission_data[0].scenerio">
                                    <option value="">Select one!</option>
                                    <option value="Positive">Positive</option>
                                    <option value="Negetive">Negetive</option>

                                </select>
                            </div>



                        </div>
                        <!-- // Manual entry  all in audit table -->
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Customer VOC</label>
                                <input type="text" class="form-control" v-model="submission_data[0].customer_voice" />
                            </div>
                            <div class="col-lg-4">
                                <label>Broad Reason Category</label>
                                <input type="text" class="form-control"
                                    v-model="submission_data[0].current_assigned_queue_when_closed_c" />
                            </div>
                            <div class="col-lg-4">
                                <label>Detailed Reason</label>
                                <input type="text" class="form-control"
                                    v-model="submission_data[0].case_reason_when_case_closed" />
                            </div>
                            <div class="col-lg-4">
                                <label>RCA</label>
                                <input type="text" class="form-control" v-model="submission_data[0].rca_other_detail" />
                            </div>



                        </div>
                        <div class="form-group row">

                            <div class="col-lg-4">
                                <label>QA observation/Remarks</label>
                                <input type="text" class="form-control" v-model="submission_data[0].rcc_remark" />
                            </div>
                            <div class="col-lg-4">
                                <label>Corrective action taken</label>
                                <input type="text" class="form-control" v-model="submission_data[0].sfdc_disposition" />

                            </div>
                            <div class="col-lg-4">
                                <label>Preventive Action</label>
                                <input type="text" class="form-control" v-model="submission_data[0].variable" />

                            </div>


                        </div>


                    </div>




                </div>
            </div>




            <!--end::Portlet-->


            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__head" style="
           background-image: linear-gradient(to right, rgb(132, 94, 194), rgb(144, 109, 198), rgb(156, 125, 201), rgb(168, 140, 205), rgb(179, 156, 208));
          ">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title kt-font-light">
                            Parameter Details
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="col-md-1 kt-font-bolder text-center">
                            Behavior
                        </div>
                        <div class="col-md-11 kt-font-bolder">
                            <div class="row">
                                <div class="col-md-2 kt-font-bolder text-center">
                                    Parameter
                                </div>
                                <div class="col-md-1 kt-font-bolder text-center">
                                    Observation
                                </div>
                                <div class="col-md-1 kt-font-bolder text-center">
                                    Scored
                                </div>
                                <div class="col-md-2 kt-font-bolder text-center">
                                    Failure Type
                                </div>
                                <div class="col-md-2 kt-font-bolder text-center">
                                    Failure Reason
                                </div>
                                <div class="col-md-2 kt-font-bolder text-center">
                                    Remarks
                                </div>
                                <!-- <div
                                    class="col-md-2 kt-font-bolder text-center"
                                >
                                    Screen Shot
                                </div> -->
                            </div>
                        </div>
                    </div>

                    <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg" id="seprator"></div>

                    <div class="row flex-container" v-for="(pm, index) in parameters" style="
              border-bottom: 1px solid rgb(204, 204, 204);
              padding: 20px 0px;
              height: 100%;
            ">
                        <div class="col-md-1 kt-font-bolder kt-font-primary flex-item">
                            {{ pm.name }}
                        </div>
                        <div class="col-md-11 sp-row" style="padding: 0px">
                            <div class="row flex-container" v-for="(sp, i) in pm.subs">
                                <div class="col-md-2 kt-font-bold">
                                    {{ sp.name }}
                                    <i class="la la-info-circle kt-font-warning sp-details-top" :title="sp.details"></i>
                                </div>
                                <div class="col-md-1 pl-0">
                                    <select class="form-control" v-model="sp.selected_option_model"
                                        @change="onObservationChange($event)" required="required">
                                        <option v-for="subs_opt in sp.options" :value="subs_opt.key">
                                            {{ subs_opt.value }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-1 pl-0">
                                    <input type="text" class="form-control" :id="'sp_id_' + sp.id" @change="
                                        onScoreChange($event, sp.id, pm.id)
                                        " v-model="sp.selected_options" min="0" readonly />
                                </div>
                                <div class="col-md-2 pl-0 mt-0">
                                    <select class="form-control mt-0 pt-0" @change="onReasonTypeChange($event)"
                                        v-model="sp.selected_reason_type">
                                        <option v-for="rtype in sp.all_reason_type" :value="rtype.key">
                                            {{ rtype.value }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2 pl-0 mt-0">
                                    <select class="form-control" v-model="sp.selected_reason">
                                        <option v-for="(rtype,
                                            key) in sp.all_reasons" :value="key">
                                            {{ rtype }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <textarea class="form-control" v-model="sp.remark"></textarea>
                                    <!-- <input type="text" class="form-control" v-model="sp.remark"> -->
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 15px">
                        <div class="col-lg-4">
                            <label>Overall Call Summary:</label>
                            <textarea class="form-control" v-model="submission_data[0].overall_summary"></textarea>
                        </div>

                        <div class="col-lg-3">
                            <label>Feedback to Agent:</label>
                            <textarea class="form-control" v-model="submission_data[0].feedback_to_agent"></textarea>
                        </div>
                        <div class="col-lg-2">
                            <label>Good or Bad Call ?:</label>
                            <select class="form-control" v-model="submission_data[0].good_bad_call">
                                <option value="0">Select one!</option>
                                <option value="1">Good Call</option>
                                <option value="2">Bad Call</option>
                                <option value="3">Average Call</option>
                            </select>
                        </div>
                        <!-- <div class="col-lg-2" v-if="n_client_id != 23">
                            <label>Upload Call:</label>
                            <input type="file" name="good_bad_call_file" class="form-control" ref="good_bad_call_file"
                                multiple v-on:change="handleCallUpload()" />
                            <div id="myProgress">
                                <div id="myBar">10%</div>
                            </div>
                        </div>
                        <div class="col-lg-1" v-if="n_client_id != 23">
                            <button type="button" class="btn btn-success" id="myupload" @click="upload_call(1)">
                                Upload
                            </button>
                        </div> -->
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-9">
                    <div class="kt-portlet kt-portlet--mobile">
                        <div class="kt-portlet__head" style="
               background-image: linear-gradient(to right, rgb(132, 94, 194), rgb(144, 109, 198), rgb(156, 125, 201), rgb(168, 140, 205), rgb(179, 156, 208));
              ">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title kt-font-light">
                                    Score Result
                                </h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="row" style="border-bottom: 1px solid #ccc">
                                <div class="col-lg-4 kt-font-bolder">
                                    &nbsp;
                                </div>
                                <div class="col-lg-4 kt-font-bolder">
                                    Scored
                                </div>
                                <div class="col-lg-4 kt-font-bolder">
                                    Scores%
                                </div>
                            </div>
                            <div class="row" style="padding: 15px 0">
                                <div class="col-lg-2 kt-font-bolder">
                                    Behaviour
                                </div>
                                <div class="col-lg-2 kt-font-bolder">
                                    Scorable
                                </div>
                                <div class="col-lg-2 kt-font-bolder">
                                    With FATAL
                                </div>
                                <div class="col-lg-2 kt-font-bolder">
                                    Without FATAL
                                </div>
                                <div class="col-lg-2 kt-font-bolder">
                                    With FATAL
                                </div>
                                <div class="col-lg-2 kt-font-bolder">
                                    Without FATAL
                                </div>
                            </div>
                            <div class="row" v-for="pm in parameters" style="
                  border-bottom: 1px solid #ccc;
                  padding: 20px 0;
                  height: 100%;
                " v-if="pm.is_non_scoring == 0">
                                <div class="col-lg-2 kt-font-bold kt-font-primary">
                                    {{ pm.name }}
                                </div>
                                <div class="col-lg-2">
                                    {{ pm.temp_total_weightage }}
                                </div>
                                <div class="col-lg-2 kt-font-danger">
                                    {{ pm.score_with_fatal }}
                                </div>
                                <div class="col-lg-2">
                                    {{ pm.score_without_fatal }}
                                </div>
                                <div class="col-lg-2 kt-font-danger">
                                    {{
                                        pm.is_non_scoring
                                            ? 0.0
                                            : (
                                                (pm.score_with_fatal /
                                                    pm.temp_total_weightage) *
                                                100
                                            ).toFixed(2)
                                    }}
                                    %
                                </div>
                                <div class="col-lg-2">
                                    {{
                                        pm.is_non_scoring
                                            ? 0.0
                                            : (
                                                (pm.score_without_fatal /
                                                    pm.temp_total_weightage) *
                                                100
                                            ).toFixed(2)
                                    }}
                                    %
                                </div>
                            </div>
                            <div class="row" style="padding: 20px 0; height: 100%">
                                <div class="col-lg-2 kt-font-bold kt-font-success">
                                    Over All
                                </div>
                                <div class="col-lg-2 kt-font-bold">
                                    {{ overAllTotalScoreRow[0].total_scorable }}
                                </div>
                                <div class="col-lg-2 kt-font-bold kt-font-danger">
                                    {{ overAllTotalScoreRow[0].with_fatal }}
                                </div>
                                <div class="col-lg-2 kt-font-bold">
                                    {{ overAllTotalScoreRow[0].without_fatal }}
                                </div>
                                <div class="col-lg-2 kt-font-bold kt-font-danger">
                                    {{
                                        overAllTotalScoreRow[0]
                                            .with_fatal_per == 0
                                            ? "NaN"
                                            : overAllTotalScoreRow[0]
                                                .with_fatal_per
                                    }}
                                    %
                                </div>
                                <div class="col-lg-2 kt-font-bold">
                                    {{
                                        overAllTotalScoreRow[0]
                                            .without_fatal_per == 0
                                            ? "NaN"
                                            : overAllTotalScoreRow[0]
                                                .without_fatal_per
                                    }}
                                    %
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-1">
                    <button type="button" class="btn btn-success" id="MySubmitButton" @click="submitMe(1)">
                        Final Submit</button>&nbsp;&nbsp;&nbsp;
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-warning" id="MySaveButton" @click="submitMe(0)">
                        Save</button>&nbsp;&nbsp;&nbsp;
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger">Cancel</button>
                </div>
                <div class="col-md-6">
                    <p align="right">
                        <a :href="'/audited_list/' + qmSheetId" class="btn btn-warning">Back to List</a>
                    </p>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
import vSelect from "vue-select";
Vue.component("v-select", vSelect);

export default {
    props: ["qmSheetId", "todayDate", "auditorName", "auditorId"],
    data() {
        return {
            ahtTimes: [{ aht: "", callId: "" }],
            submit_type: 0,
            secondTime: "",
            secondTimeData: "",
            call_file: "",
            search_call_id: "",
            agentData: [
                {
                    agent_namee: "",
                    email: "",
                    audit_count: 0,
                    thisWeekaudits: 0
                }
            ],
            sheet_basic_data: [
                {
                    client: null,
                    comm_instance_id: "",
                    agent_name: null,
                    qa_name: null,
                    tl_name: null,
                    language: null,
                    case_number: null,
                    relationship_number: null,
                    call_type: null,
                    call_sub_type: null,
                    campaign_name: null,
                    disposition: null,
                    customer_name: null,
                    customer_phone: null,
                    qm_sheet_version: null,
                    month: null,
                    case_id: null,
                    reference_no: null,
                    opened_date: null,
                    close_date: null,
                    case_origin: null,
                    case_reason: null,
                    solution: null,
                    user_name: null,
                    solution_based_category_c: null,
                    current_assigned_queue: null,
                    partner_name: null,
                    case_status: null,


                }
            ],
            parameters: [],
            subs: [],
            screenshot: [],
            image: "",
            type_b_scoring_option: [],
            overAllTotalScore: 0,
            overAllTotalScoreRow: [
                {
                    total_scorable: 0,
                    with_fatal: 0,
                    without_fatal: 0,
                    with_fatal_per: 0,
                    without_fatal_per: 0
                }
            ],
            submission_data: [
                {
                    company_id: null,
                    client_id: null,
                    partner_id: null,
                    qm_sheet_id: null,
                    process_id: null,
                    raw_data_id: null,
                    audited_by_id: null,
                    is_critical: null,
                    is_partial_marking: null,
                    overall_score: null,
                    partner_id: null,
                    qrc_2: null,
                    language_2: null,
                    second_level_drill: null,
                    audit_date: "",
                    case_id: null,
                    overall_summary: null,
                    feedback_to_agent: null,
                    disposition: null,
                    call_time: null,
                    close_date: null,
                    call_duration: null,
                    customer_name: null,
                    call_type: null,
                    customer_phone: null,
                    good_bad_call: 0,
                    good_bad_call_file: null,
                    call_sub_type: null,
                    delay1: null,
                    delay2: null,
                    holiday: null,
                    error_code: null,
                    vehicle_type: null,
                    new_error_code: null,
                    call_count: null,
                    recording_link: null,
                    sfdc_disposition: null,
                    consent_nonconcet_lead: null,
                    consent: null,
                    spdc_dispostion: null,
                    refrence_number: null,
                    hot_lead: null,
                    hotLead_reason: null,
                    intervention_in_call: null,
                    intervention_drop_down: null,
                    agent_intervenses_count: null,
                    agent_intervenes_reason: null,
                    info_given_by_sinior: null,
                    intervention_remarks: null,
                    audit_type: null,
                    solution_based_category_c: null,
                    scenerio: null,
                    case_origin_channel: null,
                    channel_master: null,
                    scenerio_code: null,
                    error_reason_type: null,
                    event_c: null,
                    customer_voice: null,
                    current_assigned_queue_when_closed_c: null,
                    solution: null,
                    current_assigned_queue: null,
                    sub_issues: null,
                    issues: null,
                    reference_no: null,
                    category: null,
                    user_name: null,

                    qrc_1: null,
                    bfl_process: null,
                    opened_date: null,
                    agent_name: null,
                    emp_id: null,
                    case_status: null,
                    case_type: null,
                    case_reason: null,
                    campaign_name: null,
                    customer_id: null,
                    brand_name: null,
                    order_stage: null,
                    sr_mgr: null,
                    fpr: null,
                    hrs_tat_bucket: null,
                    info_1: null,
                    info_2: null,
                    info_3: null,
                    info_4: null,
                    info_5: null,
                    error_code_reason: null,
                    case_reason_when_case_closed: null,
                    product_id: null,
                    gen_ai: null,
                    variable: null,
                    vendor_name: null,
                    hot_leads: null,
                    customer_stt_1: null,
                    customer_stt2: null,
                    customer_stt_3: null,
                    customer_stt_4: null,
                    customer_stt_5: null,
                    case_create_date: null,
                    case_dropdown: null,
                    caller_type: null,
                    scanerio: null,
                    scanerio_codes: null,
                    bfl_tl_name: null,
                    product_business_family: null,
                    case_number: null,
                    l2_ticket_disposition: null,
                    owner: null,
                    call_disposition: null,
                    bot_disposition_as_per_logic: null,
                    case_comment: null,
                    acpt_l2: null,
                    call_stage: null,
                    case_reason_if_incorrect: null,
                    empowerment_validation: null,
                    lan_incorrect_comment: null,
                    rca_other_detail: null,
                    rcc_remark: null,

                }
            ],
            my_alloted_call_list: [],
            n_client_id: 0,
            callTypeList: [],
            final_call_fatal_status: 0,
            total_non_scoring_parms: 0,
            is_rca_enabled: 0,
            is_type_2_rca_enabled: 0,
            rca_type: [],
            selected_rca_type: 0,
            rca_mode: [],
            selected_rca_mode: 0,
            rca1: [],
            selected_rca1: 0,
            rca2: [],
            selected_rca2: 0,
            rca3: [],
            selected_rca3: 0,
            rca_other_detail: null,
            rcc_remark: null,
            type_2_rca_mode: [],
            selected_type_2_rca_mode: 0,
            type_2_rca1: [],
            selected_type_2_rca1: 0,
            type_2_rca2: [],
            selected_type_2_rca2: 0,
            type_2_rca3: [],
            selected_type_2_rca3: 0,
            type_2_rca_other_detail: null,
            errors: [],
            audit_cycle: "",

        };
    },
    mounted() {
        var self = this;
        axios
            .get("/get_qm_sheet_details_for_audit/" + this.qmSheetId)
            .then(function (response) {
                var temp_first_data = response.data.data;
                console.log(temp_first_data);
                //my_alloted_call_list
                self.my_alloted_call_list =
                    temp_first_data.my_alloted_call_list;
                //my_alloted_call_list
                self.callTypeList = temp_first_data.callTypeList;
                self.n_client_id = temp_first_data.client_id;

                self.sheet_basic_data[0].client =
                    temp_first_data.sheet_details.client.name;
                self.sheet_basic_data[0].version =
                    temp_first_data.sheet_details.version;

                self.parameters = temp_first_data.simple_data;
                self.type_b_scoring_option =
                    temp_first_data.type_b_scoring_option;
                //prep for submission

                self.submission_data[0].company_id = temp_first_data.sheet_details.company_id;

                self.submission_data[0].client_id =
                    temp_first_data.sheet_details.client_id;
                self.submission_data[0].process_id =
                    temp_first_data.sheet_details.process_id;
                self.submission_data[0].audited_by_id = self.auditorId;
                self.submission_data[0].qm_sheet_id =
                    temp_first_data.sheet_details.id;
                self.submission_data[0].audit_date =
                    temp_first_data.audit_timestamp;

                // Tiwari code
                self.submission_data[0].delay1 =
                    temp_first_data.sk_client.delay1;
                self.submission_data[0].delay2 =
                    temp_first_data.sk_client.delay2;
                self.submission_data[0].holiday =
                    temp_first_data.sk_client.holiday;
                // Tiwari Code

                //prep for submission

                self.audit_cycle = temp_first_data.audit_cycle;

                self.is_rca_enabled =
                    temp_first_data.sheet_details.client.rca_enabled;
                self.is_type_2_rca_enabled =
                    temp_first_data.sheet_details.client.rca_two_enabled;

                self.rca_type = temp_first_data.rca_type;
                self.rca_mode = temp_first_data.rca_mode;
                self.type_2_rca_mode = temp_first_data.type_2_rca_mode;
            })
            .catch(function (error) {
                console.log(error);
            });
    },
    methods: {
        showpopup: function () {
            var self = this;
            var params = {
                process_id: this.submission_data[0].process_id
            };

            axios
                .get(
                    "/audit_count_agent/" +
                    this.sheet_basic_data[0].comm_instance_id.key
                )
                .then(function (response) {
                    var agent_audits_count = response.data.data;

                    // Check if audit_count is greater than 0 and client_id is 23
                    if (
                        agent_audits_count.audit_count > 0 &&
                        agent_audits_count.client_id == 23
                    ) {
                        // Show the data in a SweetAlert2 popup
                        swal.fire({
                            title: "Agent Data",
                            html: `
                        <p><strong>Agent Name:</strong> ${agent_audits_count.email
                                }</p>
                        <p><strong>Audit Count:</strong> ${agent_audits_count.audit_count
                                }</p>
                        <p><strong>This Week Audit</strong> ${agent_audits_count.thisWeekaudits
                                }</p>
                    `,
                            icon: "info",
                            confirmButtonText: "OK"
                        });

                        // Update agentData with response data
                        self.agentData = {
                            agent_namee: agent_audits_count.email,
                            email: agent_audits_count.email,
                            audit_count: agent_audits_count.audit_count,
                            audit_count: agent_audits_count.thisWeekaudits
                        };
                    }
                })
                .catch(function (error) {
                    swal.fire("Oop's!", "Failed to fetch agent data.", "error");
                });
        },

        getRawData: function () {
            // this.showpopup();
            document.getElementById("audit_component_form").reset();
            Object.keys(this.parameters).forEach(key_param => {
                Object.keys(this.parameters[key_param].subs).forEach(
                    key_sub_param => {
                        this.parameters[key_param].subs[
                            key_sub_param
                        ].selected_option_model = null;
                        this.parameters[key_param].subs[
                            key_sub_param
                        ].remark = null;
                        this.parameters[key_param].subs[
                            key_sub_param
                        ].selected_options = null;
                        this.parameters[key_param].subs[
                            key_sub_param
                        ].selected_reason = null;
                        this.parameters[key_param].subs[
                            key_sub_param
                        ].selected_reason_type = null;
                        this.parameters[key_param].subs[
                            key_sub_param
                        ].temp_weight = 0;
                        this.parameters[key_param].subs[
                            key_sub_param
                        ].screenshot = 0;
                    }
                );
            });
            this.submission_data[0].customer_phone = null;
            this.submission_data[0].customer_name = null;
            this.submission_data[0].call_type = null;
            this.submission_data[0].qrc_2 = null;

            this.submission_data[0].disposition = null;
            this.submission_data[0].call_time = null;
            this.submission_data[0].call_duration = null;
            this.submission_data[0].overall_summary = null;
            this.submission_data[0].feedback_to_agent = null;
            this.submission_data[0].case_id = null;
            this.submission_data[0].refrence_number = null;
            this.submission_data[0].error_code = null;
            this.submission_data[0].recording_link = null;
            this.submission_data[0].call_count = null;
            this.submission_data[0].hot_lead = null;
            this.submission_data[0].calhotLead_reasonl_count = null;
            this.submission_data[0].vehicle_type = null;
            this.submission_data[0].new_error_code = null;

            this.submission_data[0].language_2 = null;
            this.submission_data[0].second_level_drill = null;
            this.submission_data[0].good_bad_call = 0;

            var self = this;
            axios
                .get(
                    "/get_raw_data_for_audit/" +
                    this.sheet_basic_data[0].comm_instance_id.key
                )
                .then(function (response) {
                    var temp_data_b = response.data.data;
                    self.sheet_basic_data[0].agent_name =
                        temp_data_b.agent_name;
                    self.sheet_basic_data[0].tl_name = temp_data_b.tl;
                    self.sheet_basic_data[0].language = temp_data_b.language;
                    self.sheet_basic_data[0].case_number = temp_data_b.case_number;
                    self.sheet_basic_data[0].relationship_number = temp_data_b.relationship_number;
                    self.secondTime = Date.now();
                    self.sheet_basic_data[0].call_type = temp_data_b.call_type;
                    self.sheet_basic_data[0].call_sub_type = temp_data_b.call_sub_type;
                    self.sheet_basic_data[0].case_origin = temp_data_b.case_origin;
                    self.sheet_basic_data[0].case_reason = temp_data_b.case_reason;
                    self.sheet_basic_data[0].solution = temp_data_b.solution;
                    self.sheet_basic_data[0].user_name = temp_data_b.user_name;
                    self.sheet_basic_data[0].solution_based_category_c = temp_data_b.solution_based_category_c;
                    self.sheet_basic_data[0].partner_name = temp_data_b.partner_name;
                    self.sheet_basic_data[0].current_assigned_queue = temp_data_b.current_assigned_queue;
                    self.sheet_basic_data[0].case_status = temp_data_b.case_status;
                    self.sheet_basic_data[0].reference_no = temp_data_b.reference_no;
                    self.sheet_basic_data[0].opened_date = temp_data_b.opened_date;
                    self.sheet_basic_data[0].close_date = temp_data_b.close_date;
                    self.sheet_basic_data[0].case_id = temp_data_b.case_id;
                    self.sheet_basic_data[0].month = temp_data_b.month;
                    self.sheet_basic_data[0].disposition = temp_data_b.disposition;
                    self.sheet_basic_data[0].partner_name = temp_data_b.partner_detail.name;



                    self.submission_data[0].customer_phone = temp_data_b.phone_number;
                    self.submission_data[0].reference_no = temp_data_b.reference_no;
                    self.submission_data[0].solution_based_category_c = temp_data_b.solution_based_category_c;
                    self.submission_data[0].customer_name = temp_data_b.customer_name;
                    self.submission_data[0].call_type = temp_data_b.call_type;
                    self.submission_data[0].partner_id = temp_data_b.partner_id;
                    self.submission_data[0].raw_data_id = temp_data_b.id;
                    self.submission_data[0].disposition = temp_data_b.disposition;
                    self.submission_data[0].call_time = temp_data_b.call_time;
                    self.submission_data[0].call_duration = temp_data_b.call_duration;
                    self.submission_data[0].call_sub_type = temp_data_b.call_sub_type;
                    self.submission_data[0].campaign_name = temp_data_b.campaign_name;
                    self.submission_data[0].bfl_tl_name = temp_data_b.bfl_tl_name;
                    self.submission_data[0].close_date = temp_data_b.close_date;
                    self.submission_data[0].event_c = temp_data_b.event_c;
                    self.submission_data[0].product_business_family = temp_data_b.product_business_family;
                    self.submission_data[0].case_number = temp_data_b.case_number;
                    self.submission_data[0].user_name = temp_data_b.user_name;

                    self.submission_data[0].qrc_1 = temp_data_b.qrc_1;
                    self.submission_data[0].bfl_process = temp_data_b.bfl_process;
                    self.submission_data[0].opened_date = temp_data_b.opened_date;

                    self.submission_data[0].audit_type = temp_data_b.audit_type;
                    self.submission_data[0].agent_name = temp_data_b.agent_name;
                    self.submission_data[0].emp_id = temp_data_b.emp_id;
                    self.submission_data[0].month = temp_data_b.month;
                    self.submission_data[0].relationship_number = temp_data_b.relationship_number;

                    self.submission_data[0].auditor_name = temp_data_b.auditor_name;
                    self.submission_data[0].location = temp_data_b.location;

                    self.submission_data[0].call_id = temp_data_b.call_id;
                    self.submission_data[0].category = temp_data_b.category;

                    self.submission_data[0].case_status = temp_data_b.case_status;
                    self.submission_data[0].scenerio = temp_data_b.scenerio;
                    self.submission_data[0].case_origin_channel = temp_data_b.case_origin_channel;
                    self.submission_data[0].channel_master = temp_data_b.channel_master;
                    self.submission_data[0].case_origin = temp_data_b.case_origin;
                    self.submission_data[0].case_type = temp_data_b.case_type;

                    self.submission_data[0].case_reason = temp_data_b.case_reason;
                    self.submission_data[0].solution = temp_data_b.solution;
                    self.submission_data[0].current_assigned_queue = temp_data_b.current_assigned_queue;

                    self.submission_data[0].customer_id = temp_data_b.customer_id;
                    self.submission_data[0].brand_name = temp_data_b.brand_name;

                    self.submission_data[0].order_stage = temp_data_b.order_stage;
                    self.submission_data[0].sr_mgr = temp_data_b.sr_mgr;

                    self.submission_data[0].fpr = temp_data_b.fpr;
                    self.submission_data[0].hrs_tat_bucket = temp_data_b.hrs_tat_bucket;

                    self.submission_data[0].info_1 = temp_data_b.info_1;
                    self.submission_data[0].info_2 = temp_data_b.info_2;
                    self.submission_data[0].info_3 = temp_data_b.info_3;
                    self.submission_data[0].info_4 = temp_data_b.info_4;
                    self.submission_data[0].info_5 = temp_data_b.info_5;

                    self.submission_data[0].error_reason_type = temp_data_b.error_reason_type;
                    self.submission_data[0].error_code_reason = temp_data_b.error_code_reason;
                    self.submission_data[0].error_code = temp_data_b.error_code;
                    self.submission_data[0].case_create_date = temp_data_b.case_create_date;

                    self.submission_data[0].issues = temp_data_b.issues;
                    self.submission_data[0].sub_issues = temp_data_b.sub_issues;

                    self.submission_data[0].case_reason_when_case_closed = temp_data_b.case_reason_when_case_closed;

                    self.submission_data[0].product_id = temp_data_b.product_id;
                })
                .catch(function (error) {
                    swal.fire(
                        "Oop's!",
                        "Call not found, please check your call Id.",
                        "error"
                    );
                    self.sheet_basic_data[0].comm_instance_id = null;
                });
        },

        handleUpload(e, sb_id, pm_id, type) {
            console.log(Type);
            var reforfile = "file_" + sb_id + pm_id;

            //this.parameters[pm_id].subs[sb_id].type = null;
            const img = e.target.files[0];
            if (sb_id) {
                var reg = /(.*?)\.(jpg|bmp|jpeg|png|JPG|PNG|JPEG)$/;
                if (!img.name.match(reg)) {
                    alert(
                        "File Extension Not Supported.\n Only JPG & PNG Extension Format Images are Allowed"
                    );
                    this.$refs[reforfile][0].value = "";
                    return;
                }
                if (img.size / 1000 > 2048) {
                    alert("Image size must not exceed 2 MB");
                    this.$refs[reforfile][0].value = "";
                    return;
                } else {
                    this.createBase64(img, sb_id, pm_id, type);
                }
            }
        },

        removeImage(e, sb_id, pm_id) {
            console.log(e.target);
            //e.target.value = '';
            console.log(sb_id + pm_id);
            var reforfile = "file_" + sb_id + pm_id;
            console.log(this.$refs[reforfile][0].value);
            this.$refs[reforfile][0].value = "";
            //this.$refs.reforfile.value = '';
            //this.$refs['file_'+sb_id+pm_id].value = '';
            //this.parameters[pm_id].subs[sb_id].screenshot = '';
        },

        /*removeImage: function (e) {
          this.parameters[pm_id].subs[sb_id].screenshot = '';
        },*/

        createBase64(fileObj, sb_id, pm_id, type) {
            const reader = new FileReader();
            reader.onload = e => {
                this.parameters[pm_id].subs[sb_id].type = e.target.result;
            };
            reader.readAsDataURL(fileObj);
        },

        onScoreChange: function (event, sp_id, p_id) {
            var str = event.target.value;

            this.parameters[parseInt(p_id)].subs[parseInt(sp_id)][
                "score"
            ] = parseInt(str);
            this.calculateSummary();
        },

        onObservationChange: function (event) {
            //console.log(event.target.value);
            var str = event.target.value;
            var res = str.split("_");

            if (res[3] == 1 || this.n_client_id == 9) {
                document.getElementById("sp_id_" + res[1]).readOnly = false;
                document.getElementById(
                    "sp_id_" + res[1]
                ).max = this.parameters[parseInt(res[0])].subs[
                parseInt(res[1])
                ]["orignal_weight"];
            }

            this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                "all_reason_type"
            ] = "#";
            this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                "all_reasons"
            ] = "";
            //set failure type
            if (res[3] == 2 && res[4] == 1)
                this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                    "all_reason_type"
                ] = this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                    "all_reason_type_fail"
                    ];

            if (res[3] == 3 && res[4] == 1)
                this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                    "all_reason_type"
                ] = this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                    "all_reason_type_cric"
                    ];

            if (res[3] == 5 && res[4] == 1)
                this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                    "all_reason_type"
                ] = this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                    "all_reason_type_pwd"
                    ];

            if (res[3] == 6 && res[4] == 1)
                this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                    "all_reason_type"
                ] = this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                    "all_reason_type_partial_marking"
                    ];

            this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                "selected_reason_type"
            ] = null;
            this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                "selected_reason"
            ] = null;
            //set failure type

            if (res[3] == 6) {
                document.getElementById("sp_id_" + res[1]).readOnly = false;
                document.getElementById("sp_id_" + res[1]).type = "number";
                document.getElementById(
                    "sp_id_" + res[1]
                ).max = this.parameters[parseInt(res[0])].subs[
                parseInt(res[1])
                ]["orignal_weight"];
            }
            //show me alert box
            if (
                this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                "options"
                ][event.target.value]["alert_box"] != null
            ) {
                swal.fire(
                    "Alert!",
                    this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                        "options"
                    ][event.target.value]["alert_box"].details,
                    "warning"
                );
            }
            //show me alert box

            this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                "selected_options"
            ] = res[2];
            this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                "selected_observation"
            ] = res[3];

            if (
                this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                "is_non_scoring"
                ]
            ) {
                this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                    "score"
                ] = 0;

                this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                    "temp_weight"
                ] = 0;
            } else {
                if (isNaN(res[2]))
                    this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                        "score"
                    ] = 0;
                else
                    this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                        "score"
                    ] = Number(res[2]);

                if (res[2] == "Critical")
                    this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                        "is_fatal"
                    ] = 1;
                else
                    this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                        "is_fatal"
                    ] = 0;

                if (res[2] == "Partial Marking")
                    this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                        "is_partial_marking"
                    ] = 1;
                else
                    this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                        "is_partial_marking"
                    ] = 0;

                if (res[2] == "N/A")
                    this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                        "temp_weight"
                    ] = 0;
                else
                    this.parameters[parseInt(res[0])].subs[parseInt(res[1])][
                        "temp_weight"
                    ] = this.parameters[parseInt(res[0])].subs[
                    parseInt(res[1])
                    ]["orignal_weight"];
            }

            //calculate total tabe;
            this.calculateSummary();
        },
        calculateSummary: function () {
            var self = this;

            this.overAllTotalScoreRow[0].with_fatal = 0;
            this.overAllTotalScoreRow[0].without_fatal = 0;
            this.overAllTotalScoreRow[0].with_fatal_per = 0;
            this.overAllTotalScoreRow[0].without_fatal_per = 0;

            this.total_non_scoring_parms = 0;

            var temp_param_fatal_status = 0;

            this.final_call_fatal_status = 0;
            var total_scorable = 0;
            var total_with_fatal_score = 0;
            var total_without_fatal_score = 0;
            var param_is_fatal = 0;

            for (const key of Object.keys(this.parameters)) {
                //console.log(key, this.parameters[key]);
                var temp_total_para_score = 0;
                temp_param_fatal_status = 0;
                param_is_fatal = 0;

                this.parameters[key]["temp_total_weightage"] = 0;

                if (this.parameters[key]["is_non_scoring"] == 0)
                    this.total_non_scoring_parms =
                        this.total_non_scoring_parms + 1;

                for (const key_s of Object.keys(this.parameters[key].subs)) {
                    temp_total_para_score += this.parameters[key].subs[key_s][
                        "score"
                    ];

                    if (this.parameters[key].subs[key_s]["is_fatal"])
                        temp_param_fatal_status = 1;

                    this.parameters[key][
                        "temp_total_weightage"
                    ] += this.parameters[key].subs[key_s]["temp_weight"];
                    total_scorable =
                        total_scorable +
                        Number(this.parameters[key].subs[key_s]["temp_weight"]);

                    if (this.parameters[key].subs[key_s]["is_fatal"] == 1) {
                        this.final_call_fatal_status = 1;
                        param_is_fatal = 1;
                    }
                }

                if (temp_param_fatal_status) {
                    this.parameters[key].score = temp_total_para_score;
                    this.parameters[key].score_with_fatal = 0;
                    this.parameters[
                        key
                    ].score_without_fatal = temp_total_para_score;
                } else {
                    this.parameters[key].score = temp_total_para_score;
                    this.parameters[
                        key
                    ].score_with_fatal = temp_total_para_score;
                    this.parameters[
                        key
                    ].score_without_fatal = temp_total_para_score;
                }

                this.parameters[key]["is_fatal"] = param_is_fatal;

                total_with_fatal_score += this.parameters[key].score_with_fatal;
                total_without_fatal_score += this.parameters[key]
                    .score_without_fatal;
            }
            this.overAllTotalScoreRow[0].total_scorable = total_scorable;
            if (this.final_call_fatal_status == 1) {
                this.overAllTotalScoreRow[0].with_fatal = 0;
                this.overAllTotalScoreRow[0].without_fatal = total_without_fatal_score;

                this.overAllTotalScoreRow[0].with_fatal_per = 0;
                this.overAllTotalScoreRow[0].without_fatal_per = (
                    (this.overAllTotalScoreRow[0].without_fatal /
                        this.overAllTotalScoreRow[0].total_scorable) *
                    100
                ).toFixed(2);
            } else {
                this.overAllTotalScoreRow[0].with_fatal = total_with_fatal_score;
                this.overAllTotalScoreRow[0].without_fatal = total_without_fatal_score;

                this.overAllTotalScoreRow[0].with_fatal_per = (
                    (this.overAllTotalScoreRow[0].with_fatal /
                        this.overAllTotalScoreRow[0].total_scorable) *
                    100
                ).toFixed(2);
                this.overAllTotalScoreRow[0].without_fatal_per = (
                    (this.overAllTotalScoreRow[0].without_fatal /
                        this.overAllTotalScoreRow[0].total_scorable) *
                    100
                ).toFixed(2);
            }
            self.submission_data[0].is_critical = this.final_call_fatal_status;
            self.submission_data[0].overall_score = this.overAllTotalScoreRow[0].without_fatal_per;
            //self.submission_data[0].qm_sheet_id =
        },
        submitMe: function (submit_type) {
            var self = this;

            this.submit_type = submit_type;

            if (this.submit_type) {
                document.getElementById("MySubmitButton").className =
                    "btn btn-primary kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light";
                document.getElementById("MySubmitButton").disabled = true;
            } else {
                document.getElementById("MySaveButton").className =
                    "btn btn-primary kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light";
                document.getElementById("MySaveButton").disabled = true;
            }
            this.submission_data[0].selected_rca_type = this.selected_rca_type;

            this.submission_data[0].selected_rca_mode = this.selected_rca_mode;
            this.submission_data[0].selected_rca1 = this.selected_rca1;
            this.submission_data[0].selected_rca2 = this.selected_rca2;
            this.submission_data[0].selected_rca3 = this.selected_rca3;
            this.submission_data[0].rca_other_detail = this.rca_other_detail;

            this.submission_data[0].selected_type_2_rca_mode = this.selected_type_2_rca_mode;
            this.submission_data[0].selected_type_2_rca1 = this.selected_type_2_rca1;
            this.submission_data[0].selected_type_2_rca2 = this.selected_type_2_rca2;
            this.submission_data[0].selected_type_2_rca3 = this.selected_type_2_rca3;
            this.submission_data[0].type_2_rca_other_detail = this.type_2_rca_other_detail;

            const endTime = Date.now();
            const timeDifference = (endTime - self.secondTime) / 1000;
            this.submission_data[0].secondTimeData = Math.floor(timeDifference);

            var validation_errors = this.validate_me();
            if (validation_errors.length > 0) {
                if (this.submit_type) {
                    document.getElementById("MySubmitButton").className =
                        "btn btn-success";
                    document.getElementById("MySubmitButton").disabled = false;
                } else {
                    document.getElementById("MySaveButton").className =
                        "btn btn-warning";
                    document.getElementById("MySaveButton").disabled = false;
                }
                var x = "<strong>Validation!</strong><br/>";
                validation_errors.forEach(function (value) {
                    x += value + "</br/>";
                });

                swal.fire("Oop's!", x, "error");
                return null;
            }
            const AHTTimes = this.ahtTimes.filter(
                time => time.aht && time.callId
            );
            var params = {
                submit: this.submit_type,
                submission_data: this.submission_data,
                parameters: this.parameters,
                AHTTimes: AHTTimes
            };
            axios
                .post("/allocation/store_audit", params)
                .then(function (response) {
                    swal.fire(
                        "Success",
                        "Audit saved successfully.",
                        "success"
                    ).then(function (result) {
                        if (result.value) {
                            location.reload();
                        }
                    });
                    if (self.submit_type)
                        document.getElementById("MySubmitButton").className =
                            "btn btn-success";
                    else
                        document.getElementById("MySaveButton").className =
                            "btn btn-warning";
                })
                .catch(function (error) {
                    if (this.submit_type)
                        document.getElementById("MySubmitButton").className =
                            "btn btn-success";
                    else
                        document.getElementById("MySaveButton").className =
                            "btn btn-warning";

                    console.log(error);
                    alert(
                        "Error: Audit saved, but please inform Tech Support."
                    );
                });
        },
        validate_me: function () {
            this.errors = [];

            //    if (
            //         this.submission_data[0].qrc_2 == "" ||
            //         this.submission_data[0] .qrc_2 == null
            //     )
            //         this.errors.push("QRC required.");

            // if (
            //     this.submission_data[0].customer_phone == "" ||
            //     this.submission_data[0].customer_phone == null
            // )
            //     this.errors.push("Customer phone required.");

            // if (
            //     this.submission_data[0].call_time == "" ||
            //     this.submission_data[0].call_time == null
            // )
            //     this.errors.push("Call time required.");

            // if (
            //     this.submission_data[0].call_duration == "" ||
            //     this.submission_data[0].call_duration == null
            // )
            //     this.errors.push("Call duration required.");

            // if (
            //     this.submission_data[0].qrc_2 == "" ||
            //     this.submission_data[0].qrc_2 == null
            // )
            //     this.errors.push("QRC required.");

            // if (
            //     this.submission_data[0].disposition == "" ||
            //     this.submission_data[0].disposition == null
            // )
            //     this.errors.push("Disposition required.");

            // if (
            //     this.submission_data[0].campaign_name == "" ||
            //     this.submission_data[0].campaign_name == null
            // )
            //     this.errors.push("Campaign name required.");

            // if (
            //     this.submission_data[0].customer_name == "" ||
            //     this.submission_data[0].customer_name == null
            // )
            //     this.errors.push("Cusotmer name required.");

            if (
                this.submission_data[0].audit_date == "" ||
                this.submission_data[0].audit_date == null
            )
                this.errors.push("Audit date required.");

            // if (
            //     this.submission_data[0].case_id == "" ||
            //     this.submission_data[0].case_id == null
            // )
            //     this.errors.push("Case Id required.");

            // if (
            //     this.submission_data[0].language_2 == "" ||
            //     this.submission_data[0].language_2 == null
            // )
            //     this.errors.push("Language QA required.");

            // if (this.n_client_id == 14) {
            //     if (
            //         this.submission_data[0].error_code == "" ||
            //         this.submission_data[0].error_code == null
            //     )
            //         this.errors.push("Scenario Code required.");
            //     if (
            //         this.submission_data[0].vehicle_type == "" ||
            //         this.submission_data[0].vehicle_type == null
            //     )
            //         this.errors.push("Vehicle Type required.");
            //     if (
            //         this.submission_data[0].new_error_code == "" ||
            //         this.submission_data[0].new_error_code == null
            //     )
            //         this.errors.push("Error Code required.");
            // }
            if (
                this.submission_data[0].overall_summary == "" ||
                this.submission_data[0].overall_summary == null
            )
                this.errors.push("Overall summary QA required.");

            if (this.n_client_id == 23) {
                // const emailRegex = /[\w.-]+@[\w-]+\.[\w.-]+/; // removed ^$
                // const phoneRegex = /\b[6-9]\d{9}\b/; // removed ^$
                // const panRegex = /[A-Z]{5}[0-9]{4}[A-Z]{1}/; // removed ^$
                const emailRegex = /\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\b/;
                const phoneRegex = /\b[6-9]\d{9}\b/;
                const panRegex = /\b[A-Z]{5}[0-9]{4}[A-Z]\b/;

                const data = this.submission_data[0];

                Object.keys(data).forEach(field => {
                    const value = data[field]
                        ? data[field].toString().trim()
                        : "";

                    if (emailRegex.test(value)) {
                        this.errors.push(
                            `Field "${field}" contains an Email ID (not allowed).`
                        );
                    }
                    if (phoneRegex.test(value)) {
                        this.errors.push(
                            `Field "${field}" contains a Phone Number (not allowed).`
                        );
                    }
                    if (panRegex.test(value.toUpperCase())) {
                        this.errors.push(
                            `Field "${field}" contains a PAN Number (not allowed).`
                        );
                    }
                });

                Object.keys(this.parameters).forEach(key_param => {
                    Object.keys(this.parameters[key_param].subs).forEach(
                        key_sub_param => {
                            console.log(key_sub_param);
                            if (
                                this.parameters[key_param].subs[key_sub_param]
                                    .selected_option_model == "" ||
                                this.parameters[key_param].subs[key_sub_param]
                                    .selected_option_model == null
                            ) {
                                this.errors.push(
                                    this.parameters[key_param].subs[
                                        key_sub_param
                                    ].name + " observation required."
                                );
                            } else {
                                if (
                                    this.parameters[key_param].subs[
                                        key_sub_param
                                    ].is_non_scoring
                                ) {
                                    if (
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 1 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 5 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 8 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 14 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 7 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 15 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 16 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 17 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 18 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 19 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 20 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 21 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 22 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 23
                                    ) {
                                        if (
                                            this.parameters[key_param].subs[
                                                key_sub_param
                                            ].remark == null
                                        ) {
                                            this.errors.push(
                                                this.parameters[key_param].subs[
                                                    key_sub_param
                                                ].name + " remark required."
                                            );
                                        } else {
                                            if (this.n_client_id != 23) {
                                                if (
                                                    this.parameters[key_param]
                                                        .subs[key_sub_param]
                                                        .remark.length < 25
                                                )
                                                    this.errors.push(
                                                        this.parameters[
                                                            key_param
                                                        ].subs[key_sub_param]
                                                            .name +
                                                        " remark minimum 25 characters."
                                                    );
                                            }
                                        }
                                    }
                                } else {
                                    if (
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 2 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 3 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 5
                                    ) {
                                        if (
                                            this.parameters[key_param].subs[
                                                key_sub_param
                                            ].remark == null
                                        ) {
                                            this.errors.push(
                                                this.parameters[key_param].subs[
                                                    key_sub_param
                                                ].name + " remark required."
                                            );
                                        } else {
                                            if (this.n_client_id != 23) {
                                                if (
                                                    this.parameters[key_param]
                                                        .subs[key_sub_param]
                                                        .remark.length < 25
                                                )
                                                    this.errors.push(
                                                        this.parameters[
                                                            key_param
                                                        ].subs[key_sub_param]
                                                            .name +
                                                        " remark minimum 25 characters."
                                                    );
                                            }
                                        }

                                        if (
                                            this.parameters[key_param].subs[
                                                key_sub_param
                                            ].selected_reason_type == null ||
                                            this.parameters[key_param].subs[
                                                key_sub_param
                                            ].selected_reason == null
                                        )
                                            this.errors.push(
                                                this.parameters[key_param].subs[
                                                    key_sub_param
                                                ].name +
                                                " Failure reason required."
                                            );
                                    } else if (
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 6
                                    ) {
                                        // console.log(this.parameters[key_param].subs[key_sub_param].selected_options);
                                        // console.log(this.parameters[key_param].subs[key_sub_param]);
                                        if (
                                            this.parameters[key_param].subs[
                                                key_sub_param
                                            ].selected_options >
                                            this.parameters[key_param].subs[
                                                key_sub_param
                                            ].orignal_weight
                                        ) {
                                            this.errors.push(
                                                this.parameters[key_param].subs[
                                                    key_sub_param
                                                ].name +
                                                " Partial marking weightage should be less than original weight"
                                            );
                                        }
                                    }
                                    /* if(this.parameters[key_param].subs[key_sub_param].selected_observation==1)

                                    {

                                    if(this.parameters[key_param].subs[key_sub_param].remark==null)
                                        {this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" remark required.");}
                                        else
                                        {
                                            if(this.parameters[key_param].subs[key_sub_param].remark.length < 25)
                                                this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" remark minimum 25 characters.");
                                        }

                                    if(this.parameters[key_param].subs[key_sub_param].selected_reason_type==null||this.parameters[key_param].subs[key_sub_param].selected_reason==null)
                                        this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" Failure reason required.");
                                    } */
                                }
                            }
                        }
                    );
                });
            } else {
                Object.keys(this.parameters).forEach(key_param => {
                    Object.keys(this.parameters[key_param].subs).forEach(
                        key_sub_param => {
                            console.log(key_sub_param);
                            if (
                                this.parameters[key_param].subs[key_sub_param]
                                    .selected_option_model == "" ||
                                this.parameters[key_param].subs[key_sub_param]
                                    .selected_option_model == null
                            ) {
                                this.errors.push(
                                    this.parameters[key_param].subs[
                                        key_sub_param
                                    ].name + " observation required."
                                );
                            } else {
                                if (
                                    this.parameters[key_param].subs[
                                        key_sub_param
                                    ].is_non_scoring
                                ) {
                                    if (
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 1 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 5 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 8 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 14 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 7 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 15 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 16 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 17 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 18 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 19 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 20 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 21 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 22 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 23
                                    ) {
                                        if (
                                            this.parameters[key_param].subs[
                                                key_sub_param
                                            ].remark == null
                                        ) {
                                            this.errors.push(
                                                this.parameters[key_param].subs[
                                                    key_sub_param
                                                ].name + " remark required."
                                            );
                                        } else {
                                            if (this.n_client_id != 23) {
                                                if (
                                                    this.parameters[key_param]
                                                        .subs[key_sub_param]
                                                        .remark.length < 25
                                                )
                                                    this.errors.push(
                                                        this.parameters[
                                                            key_param
                                                        ].subs[key_sub_param]
                                                            .name +
                                                        " remark minimum 25 characters."
                                                    );
                                            }
                                        }
                                    }
                                } else {
                                    if (
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 2 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 3 ||
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 5
                                    ) {
                                        if (
                                            this.parameters[key_param].subs[
                                                key_sub_param
                                            ].remark == null
                                        ) {
                                            this.errors.push(
                                                this.parameters[key_param].subs[
                                                    key_sub_param
                                                ].name + " remark required."
                                            );
                                        } else {
                                            if (this.n_client_id != 23) {
                                                if (
                                                    this.parameters[key_param]
                                                        .subs[key_sub_param]
                                                        .remark.length < 25
                                                )
                                                    this.errors.push(
                                                        this.parameters[
                                                            key_param
                                                        ].subs[key_sub_param]
                                                            .name +
                                                        " remark minimum 25 characters."
                                                    );
                                            }
                                        }

                                        if (
                                            this.parameters[key_param].subs[
                                                key_sub_param
                                            ].selected_reason_type == null ||
                                            this.parameters[key_param].subs[
                                                key_sub_param
                                            ].selected_reason == null
                                        )
                                            this.errors.push(
                                                this.parameters[key_param].subs[
                                                    key_sub_param
                                                ].name +
                                                " Failure reason required."
                                            );
                                    } else if (
                                        this.parameters[key_param].subs[
                                            key_sub_param
                                        ].selected_observation == 6
                                    ) {
                                        // console.log(this.parameters[key_param].subs[key_sub_param].selected_options);
                                        // console.log(this.parameters[key_param].subs[key_sub_param]);
                                        if (
                                            this.parameters[key_param].subs[
                                                key_sub_param
                                            ].selected_options >
                                            this.parameters[key_param].subs[
                                                key_sub_param
                                            ].orignal_weight
                                        ) {
                                            this.errors.push(
                                                this.parameters[key_param].subs[
                                                    key_sub_param
                                                ].name +
                                                " Partial marking weightage should be less than original weight"
                                            );
                                        }
                                    }
                                    /* if(this.parameters[key_param].subs[key_sub_param].selected_observation==1)

                                    {

                                    if(this.parameters[key_param].subs[key_sub_param].remark==null)
                                        {this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" remark required.");}
                                        else
                                        {
                                            if(this.parameters[key_param].subs[key_sub_param].remark.length < 25)
                                                this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" remark minimum 25 characters.");
                                        }

                                    if(this.parameters[key_param].subs[key_sub_param].selected_reason_type==null||this.parameters[key_param].subs[key_sub_param].selected_reason==null)
                                        this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" Failure reason required.");
                                    } */
                                }
                            }
                        }
                    );
                });
            }

            return this.errors;
        },
        onReasonTypeChange: function (eve) {
            var str = event.target.value;
            var srt = str.split("_");

            var self = this;

            axios
                .get("/get_reasons_by_type/" + srt[2])
                .then(function (response) {
                    self.parameters[parseInt(srt[0])].subs[parseInt(srt[1])][
                        "all_reasons"
                    ] = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        on_rca_mode_change: function () {
            var self = this;
            axios
                .get("/get_rca1_by_rca_mode_id/" + this.selected_rca_mode)
                .then(function (response) {
                    self.rca1 = response.data.data;
                    self.rca2 = null;
                    self.rca3 = null;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        on_rca1_change: function () {
            var self = this;
            axios
                .get("/get_rca2_by_rca1_id/" + this.selected_rca1)
                .then(function (response) {
                    self.rca2 = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        on_rca2_change: function () {
            var self = this;
            axios
                .get("/get_rca3_by_rca2_id/" + this.selected_rca2)
                .then(function (response) {
                    self.rca3 = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        on_type_2_rca_mode_change: function () {
            var self = this;
            axios
                .get(
                    "/get_type_2_rca1_by_rca_mode_id/" +
                    this.selected_type_2_rca_mode
                )
                .then(function (response) {
                    self.type_2_rca1 = response.data.data;
                    self.type_2_rca2 = null;
                    self.type_2_rca3 = null;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        on_type_2_rca1_change: function () {
            var self = this;
            axios
                .get("/get_type_2_rca2_by_rca1_id/" + this.selected_type_2_rca1)
                .then(function (response) {
                    self.type_2_rca2 = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        on_type_2_rca2_change: function () {
            var self = this;
            axios
                .get("/get_type_2_rca3_by_rca2_id/" + this.selected_type_2_rca2)
                .then(function (response) {
                    self.type_2_rca3 = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getRawDataOnDateRange: function () {
            var self = this;
            var params = {
                date_range: document.getElementById(
                    "kt_dashboard_daterangepicker_date"
                ).innerHTML,
                call_id: this.search_call_id,
                process_id: this.submission_data[0].process_id
            };
            axios
                .post("/get_raw_data_on_data_range", params)
                .then(function (response) {
                    self.my_alloted_call_list = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        upload_call() {
            // code for progress bar
            document.getElementById("MySubmitButton").disabled = true;
            document.getElementById("MySaveButton").disabled = true;
            var i = 0;
            document.getElementById("myBar").style.display = "block";
            function move() {
                if (i == 0) {
                    i = 1;
                    var elem = document.getElementById("myBar");
                    var width = 10;
                    var id = setInterval(frame, 10);
                    function frame() {
                        if (width >= 100) {
                            clearInterval(id);
                            i = 0;
                        } else {
                            width++;
                            elem.style.width = width + "%";
                            elem.innerHTML = width + "%";
                        }
                    }
                }
            }
            // code for progress bar

            var self = this;
            let formData = new FormData();
            formData.append("call_file", this.call_file);
            axios
                .post("/allocation/upload_call", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                })
                .then(function (response) {
                    move();
                    document.getElementById("MySubmitButton").disabled = false;
                    document.getElementById("MySaveButton").disabled = false;

                    self.submission_data[0].good_bad_call_file =
                        response.data.data;
                    console.log(self.submission_data[0].good_bad_call_file);
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        handleCallUpload() {
            this.call_file = this.$refs.good_bad_call_file.files[0];
        },
        addAHT() {
            if (this.ahtTimes.length < 21) {
                this.ahtTimes.push({ aht: "", callId: "" });
            }
            console.log(this.ahtTimes);
        },
        removeAHT(index) {
            this.ahtTimes.splice(index, 1);
        }
    }
};
</script>

<style lang="scss">
@import "vue-select/src/scss/vue-select.scss";

.sp-details-top {
    cursor: pointer;
}

.flex-container {
    display: flex;
    align-items: center;
}

.sp-row {
    .row {
        margin-bottom: 15px;
    }
}

#seprator {
    margin: 2.5rem 0 0 0;
}

@media screen and (min-width: 480px) {
    .sp-row {
        .row {
            .col-md-2 {
                margin-top: 15px;
            }
        }
    }
}

#myProgress {
    width: 100%;
    background-color: #ddd;
}

#myBar {
    width: 10%;
    height: 30px;
    background-color: #04aa6d;
    text-align: center;
    display: none;
    line-height: 30px;
    color: white;
}
</style>
