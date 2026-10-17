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
                <div class="kt-portlet__body" v-if="
                    submission_data[0].process_id == 181 ||
                    submission_data[0].process_id == 183 ||
                    submission_data[0].process_id == 192 ||
                    submission_data[0].process_id == 193 ||
                    submission_data[0].process_id == 210 || submission_data[0].process_id == 211
                ">
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Communication Instance ID(Call ID):</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_id" />
                        </div>
                        <div class="col-lg-4">
                            <label>Client:</label>
                            <input type="text" class="form-control" v-model="sheet_basic_data[0].client"
                                readonly="readonly" />
                        </div>

                        <div class="col-md-4">
                            <label> </label>
                            <input type="text" class="form-control" value="Internal" disabled />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Audit Date:</label>
                            <!-- <input type="date" class="form-control" placeholder="yyyy-mm-dd" v-model="submission_data[0].audit_date" required="required" /> -->
                            <input type="text" class="form-control" readonly :value="submission_data[0].audit_date" />
                        </div>

                        <div class="col-md-4">
                            <label>TL Name</label>
                            <input type="text" class="form-control" value="sumith96@crank.com" disabled />
                        </div>
                        <!-- <div class="col-md-4">
                            <label>TL Name</label>
                            <v-select
                                :options="qtloption"
                                v-model="submission_data[0].tl_name"
                                label="label"
                                :reduce="option => option.value"
                                placeholder=""
                            ></v-select>
                        </div> -->
                        <div class="col-lg-4">
                            <label>QA / QTL Name:</label>
                            <input type="text" class="form-control" :value="auditorName" readonly="readonly" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Campaign Name:</label>
                            <input type="text" class="form-control" v-model="campaign_name" />
                        </div>

                        <div class="col-lg-4">
                            <label>Call Sub Type:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_sub_type"
                                required="response" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4" v-if="submission_data[0].process_id == 181">
                            <label>LOB:</label>
                            <div>
                                <select class="form-control" v-model="submission_data[0].lob" required="required">
                                    <option value="">Select one!</option>
                                    <option value="BFL_RTA_APNT_HINDI">BFL_RTA_APNT_HINDI</option>
                                    <option value="BFL_RTA_PL_BENGALI">BFL_RTA_PL_BENGALI</option>
                                    <option value="BFL_RTA_PL_CUG_HINDI">BFL_RTA_PL_CUG_HINDI</option>
                                    <option value="BFL_RTA_PL_HINDI">BFL_RTA_PL_HINDI</option>
                                    <option value="BFL_RTA_PL_KANNADA">BFL_RTA_PL_KANNADA</option>
                                    <option value="BFL_RTA_PL_MALAYALAM">BFL_RTA_PL_MALAYALAM</option>
                                    <option value="BFL_RTA_PL_TAMIL">BFL_RTA_PL_TAMIL</option>
                                    <option value="BFL_RTA_PL_TELUGU">BFL_RTA_PL_TELUGU</option>
                                    <option value="BFL_RTA_RPL_HINDI">BFL_RTA_RPL_HINDI</option>
                                    <option value="BFL_RTA_RPL_KANNADA">BFL_RTA_RPL_KANNADA</option>
                                    <option value="BFL_RTA_RPL_TAMIL">BFL_RTA_RPL_TAMIL</option>
                                    <option value="BFL_RTA_RPL_TELUGU">BFL_RTA_RPL_TELUGU</option>
                                    <option value="EMICARD_VOICE_BOT">EMICARD_VOICE_BOT</option>
                                    <option value="PL_PPL">PL_PPL</option>
                                    <option value="PLCS_BENGALI_NEWJ">PLCS_BENGALI_NEWJ</option>
                                    <option value="PLCS_Malayalam_NEWJ">PLCS_Malayalam_NEWJ</option>
                                    <option value="plcs_new_journey">plcs_new_journey</option>
                                    <option value="PLCS_Telugu_NEWJ">PLCS_Telugu_NEWJ</option>
                                    <option value="PLSL_v2">PLSL_v2</option>
                                    <option value="SALPL">SALPL</option>
                                    <option value="SERVICE_PL_BOT">SERVICE_PL_BOT</option>
                                    <option value="SERVICE_VOICE">SERVICE_VOICE</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4" v-else-if="submission_data[0].process_id == 193">
                            <label>LOB:</label>
                            <div>
                                <select class="form-control" v-model="submission_data[0].lob" required="required">
                                    <option value="">Select one!</option>
                                    <option value="Service_Voice">Service_Voice</option>
                                    <option value="EMICARD_VOICE_BOT">EMICARD_VOICE_BOT</option>
                                    <option value="SERVICE_VOICE_English">SERVICE_VOICE_English</option>
                                    <option value="EMICARD_VOICE_BOT_English">EMICARD_VOICE_BOT_English</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4"
                            v-else-if="submission_data[0].process_id == 210 || submission_data[0].process_id == 211">
                            <label>LOB:</label>
                            <div>
                                <select class="form-control" v-model="submission_data[0].lob" required>
                                    <option value="">Select one!</option>

                                    <option value="BFL_RTA_APNT_BENGALI">BFL_RTA_APNT_BENGALI</option>
                                    <option value="BFL_RTA_APNT_CUG_HINDI">BFL_RTA_APNT_CUG_HINDI</option>
                                    <option value="BFL_RTA_APNT_HINDI">BFL_RTA_APNT_HINDI</option>
                                    <option value="BFL_RTA_APNT_KANNADA">BFL_RTA_APNT_KANNADA</option>
                                    <option value="BFL_RTA_APNT_TAMIL">BFL_RTA_APNT_TAMIL</option>
                                    <option value="BFL_RTA_APNT_TELUGU">BFL_RTA_APNT_TELUGU</option>
                                    <option value="BFL_RTA_APNT_UAT_HINDI">BFL_RTA_APNT_UAT_HINDI</option>

                                    <option value="BFL_RTA_LC_CUG_HINDI">BFL_RTA_LC_CUG_HINDI</option>
                                    <option value="BFL_RTA_ONE_IVR">BFL_RTA_ONE_IVR</option>

                                    <option value="BFL_RTA_PL_BENGALI">BFL_RTA_PL_BENGALI</option>
                                    <option value="BFL_RTA_PL_CUG_HINDI">BFL_RTA_PL_CUG_HINDI</option>
                                    <option value="BFL_RTA_PL_CUG_KANNADA">BFL_RTA_PL_CUG_KANNADA</option>
                                    <option value="BFL_RTA_PL_CUG_MALAYALAM">BFL_RTA_PL_CUG_MALAYALAM</option>
                                    <option value="BFL_RTA_PL_CUG_TAMIL">BFL_RTA_PL_CUG_TAMIL</option>
                                    <option value="BFL_RTA_PL_CUG_TELUGU">BFL_RTA_PL_CUG_TELUGU</option>

                                    <option value="BFL_RTA_PL_HINDI">BFL_RTA_PL_HINDI</option>
                                    <option value="BFL_RTA_PL_KANNADA">BFL_RTA_PL_KANNADA</option>
                                    <option value="BFL_RTA_PL_MALAYALAM">BFL_RTA_PL_MALAYALAM</option>
                                    <option value="BFL_RTA_PL_MARATHI">BFL_RTA_PL_MARATHI</option>
                                    <option value="BFL_RTA_PL_TAMIL">BFL_RTA_PL_TAMIL</option>
                                    <option value="BFL_RTA_PL_TELUGU">BFL_RTA_PL_TELUGU</option>

                                    <option value="BFL_RTA_PPL_CUG_HINDI">BFL_RTA_PPL_CUG_HINDI</option>
                                    <option value="BFL_RTA_PPL_HINDI">BFL_RTA_PPL_HINDI</option>
                                    <option value="BFL_RTA_PPL_KANNADA">BFL_RTA_PPL_KANNADA</option>

                                    <option value="BFL_RTA_RPL_HINDI">BFL_RTA_RPL_HINDI</option>
                                    <option value="BFL_RTA_RPL_KANNADA">BFL_RTA_RPL_KANNADA</option>
                                    <option value="BFL_RTA_RPL_TAMIL">BFL_RTA_RPL_TAMIL</option>
                                    <option value="BFL_RTA_RPL_TELUGU">BFL_RTA_RPL_TELUGU</option>

                                    <option value="BFL_RTA_SPL_CUG_HINDI">BFL_RTA_SPL_CUG_HINDI</option>
                                    <option value="BFL_RTA_SPL_HINDI">BFL_RTA_SPL_HINDI</option>

                                    <option value="PL_PPL">PL_PPL</option>

                                    <option value="PLCS_BENGALI_NEWJ">PLCS_BENGALI_NEWJ</option>
                                    <option value="PLCS_English_NEWJ">PLCS_English_NEWJ</option>
                                    <option value="PLCS_Kannada_NEWJ">PLCS_Kannada_NEWJ</option>
                                    <option value="PLCS_Malayalam_NEWJ">PLCS_Malayalam_NEWJ</option>
                                    <option value="PLCS_Marathi_NEWJ">PLCS_Marathi_NEWJ</option>
                                    <option value="plcs_new_journey">plcs_new_journey</option>
                                    <option value="PLCS_Tamil_NEWJ">PLCS_Tamil_NEWJ</option>
                                    <option value="PLCS_Telugu_NEWJ">PLCS_Telugu_NEWJ</option>

                                    <option value="PLSL_v2">PLSL_v2</option>
                                    <option value="SALPL">SALPL</option>
                                    <option value="BFL_RTA_LC_HINDI">BFL_RTA_LC_HINDI</option>
                                    <option value="BFL_RTA_RLC_HINDI">BFL_RTA_RLC_HINDI</option>
                                    <option value="BFL_RTA_LC_TELUGU">BFL_RTA_LC_TELUGU</option>
                                    <option value="BFL_RTA_LC_BENGALI">BFL_RTA_LC_BENGALI</option>
                                    <option value="BFL_RTA_LC_MALAYALAM">BFL_RTA_LC_MALAYALAM</option>
                                    <option value="BFL_RTA_LC_KANNADA">BFL_RTA_LC_KANNADA</option>
                                    <option value="BFL_RTA_LC_TAMIL">BFL_RTA_LC_TAMIL</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4" v-else>
                            <label>LOB:</label>
                            <div>
                                <select class="form-control" v-model="submission_data[0].lob" required="required">
                                    <option value="">Select one!</option>
                                    <option value="BFL_RTA_APNT_HINDI">BFL_RTA_APNT_HINDI</option>
                                    <option value="BFL_RTA_PL_BENGALI">BFL_RTA_PL_BENGALI</option>
                                    <option value="BFL_RTA_PL_CUG_HINDI">BFL_RTA_PL_CUG_HINDI</option>
                                    <option value="BFL_RTA_PL_CUG_KANNADA">BFL_RTA_PL_CUG_KANNADA</option>
                                    <option value="BFL_RTA_PL_CUG_MALAYALAM">BFL_RTA_PL_CUG_MALAYALAM</option>
                                    <option value="BFL_RTA_PL_CUG_TAMIL">BFL_RTA_PL_CUG_TAMIL</option>
                                    <option value="BFL_RTA_PL_CUG_TELUGU">BFL_RTA_PL_CUG_TELUGU</option>
                                    <option value="BFL_RTA_PL_HINDI">BFL_RTA_PL_HINDI</option>
                                    <option value="BFL_RTA_PL_KANNADA">BFL_RTA_PL_KANNADA</option>
                                    <option value="BFL_RTA_PL_MALAYALAM">BFL_RTA_PL_MALAYALAM</option>
                                    <option value="BFL_RTA_PL_MARATHI">BFL_RTA_PL_MARATHI</option>
                                    <option value="BFL_RTA_PL_TAMIL">BFL_RTA_PL_TAMIL</option>
                                    <option value="BFL_RTA_PL_TELUGU">BFL_RTA_PL_TELUGU</option>
                                    <option value="BFL_RTA_PPL_CUG_HINDI">BFL_RTA_PPL_CUG_HINDI</option>
                                    <option value="BFL_RTA_RPL_HINDI">BFL_RTA_RPL_HINDI</option>
                                    <option value="BFL_RTA_RPL_KANNADA">BFL_RTA_RPL_KANNADA</option>
                                    <option value="BFL_RTA_RPL_TAMIL">BFL_RTA_RPL_TAMIL</option>
                                    <option value="BFL_RTA_RPL_TELUGU">BFL_RTA_RPL_TELUGU</option>
                                    <option value="BFL_RTA_SPL_CUG_HINDI">BFL_RTA_SPL_CUG_HINDI</option>
                                    <option value="BFL_RTA_SPL_HINDI">BFL_RTA_SPL_HINDI</option>
                                    <option value="EMICARD_VOICE_BOT">EMICARD_VOICE_BOT</option>
                                    <option value="PL_PPL">PL_PPL</option>
                                    <option value="PLCS_BENGALI_NEWJ">PLCS_BENGALI_NEWJ</option>
                                    <option value="PLCS_English_NEWJ">PLCS_English_NEWJ</option>
                                    <option value="PLCS_Kannada_NEWJ">PLCS_Kannada_NEWJ</option>
                                    <option value="PLCS_Malayalam_NEWJ">PLCS_Malayalam_NEWJ</option>
                                    <option value="PLCS_Marathi_NEWJ">PLCS_Marathi_NEWJ</option>
                                    <option value="PLCS_Tamil_NEWJ">PLCS_Tamil_NEWJ</option>
                                    <option value="PLCS_Telugu_NEWJ">PLCS_Telugu_NEWJ</option>
                                    <option value="PLSL_v2">PLSL_v2</option>
                                    <option value="SALPL">SALPL</option>
                                    <option value="SERVICE_PL_BOT">SERVICE_PL_BOT</option>
                                    <option value="SERVICE_VOICE">SERVICE_VOICE</option>
                                    <option value="plcs_new_journey">plcs_new_journey</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <label>Customer ID:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].location" />
                        </div>

                        <div class="col-lg-4" v-if="submission_data[0].process_id == 181">
                            <label>Disposition:</label>
                            <div>
                                <select class="form-control" v-model="submission_data[0].disposition"
                                    required="required">
                                    <option value="">Select one!</option>
                                    <option value="Customer Disconnected">Customer Disconnected</option>
                                    <option value="RPC Success">RPC Success</option>
                                    <option value="No Requirement">No Requirement</option>
                                    <option value="RPC Failed">RPC Failed</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Call Back">Call Back</option>
                                    <option value="CT SALE">CT_SALE</option>
                                    <option value="CT Negotiation">CT_Negotiation</option>
                                    <option value="Not Eligible">Not Eligible</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4" v-else-if="submission_data[0].process_id == 193">
                            <label>Disposition:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].disposition"
                                required="response" />
                        </div>

                        <div class="col-lg-4"
                            v-else-if="submission_data[0].process_id == 210 || submission_data[0].process_id == 211">
                            <label>Disposition:</label>
                            <div>
                                <select class="form-control" v-model="submission_data[0].disposition" required>
                                    <option value="">Select one!</option>

                                    <option value="Call Back">Call Back</option>
                                    <option value="CT_Negotiation">CT_Negotiation</option>
                                    <option value="CT_Negotiation_DC">CT_Negotiation_DC</option>
                                    <option value="CT_SALE">CT_SALE</option>
                                    <option value="CT_Sale_DC">CT_Sale_DC</option>
                                    <option value="Customer Disconnected">Customer Disconnected</option>
                                    <option value="No Requirement">No Requirement</option>
                                    <option value="Not Eligible">Not Eligible</option>
                                    <option value="RPC Failed">RPC Failed</option>
                                    <option value="RPC Success">RPC Success</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Same day appointment">Same day appointment</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4" v-else>
                            <label>Disposition:</label>
                            <div>
                                <select class="form-control" v-model="submission_data[0].disposition"
                                    required="required">
                                    <option value="">Select one!</option>
                                    <option value="Customer Disconnected">Customer Disconnected</option>
                                    <option value="RPC Success">RPC Success</option>
                                    <option value="No Requirement">No Requirement</option>
                                    <option value="RPC Failed">RPC Failed</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Call Back">Call Back</option>
                                    <option value="CT SALE">CT_SALE</option>
                                    <option value="CT Negotiation">CT_Negotiation</option>
                                    <option value="Not Eligible">Not Eligible</option>
                                    <option value="CT_Sale_DC">CT_Sale_DC
                                    </option>
                                    <option value="CT_Negotiation_DC">CT_Negotiation_DC</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="form-group row"> -->

                    <!-- <div class="col-lg-4">
                            <label>QM-Sheet Version:</label>
                            <input
                                type="text"
                                class="form-control"
                                :value="sheet_basic_data[0].version"
                                readonly="readonly"
                            />
                        </div> -->

                    <!-- <div class="col-lg-4">
                            <label>QRC for QA:</label>
                            <div>
                                <select
                                    class="form-control"
                                    v-model="submission_data[0].qrc_2"
                                    required="required"
                                >
                                    <option value="">Select one!</option>
                                    <option value="Query">Query</option>
                                    <option value="Request">Request</option>
                                    <option value="Complaint">Complaint</option>
                                </select>
                            </div>
                        </div> -->
                    <!-- </div> -->

                    <div class="form-group row">
                        <!-- <div class="col-lg-4">
                            <label>Language 1:</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="submission_data[0].language"
                            />
                        </div> -->

                        <div class="col-lg-4">
                            <label>Campaign Language:</label>
                            <select class="form-control" v-model="submission_data[0].language_2">
                                <option value="">-- Select Language --</option>
                                <option value="Assamese">Assamese</option>
                                <option value="Bengali">Bengali</option>
                                <option value="English">English</option>
                                <option value="Gujrati">Gujrati</option>
                                <option value="Hindi">Hindi</option>
                                <option value="Kannada">Kannada</option>
                                <option value="Malayalam">Malayalam</option>
                                <option value="Marathi">Marathi</option>
                                <option value="Odia">Odia</option>
                                <option value="Punjabi">Punjabi</option>
                                <option value="Tamil">Tamil</option>
                                <option value="Telugu">Telugu</option>
                            </select>
                        </div>

                        <!-- <div class="col-lg-4">
                            <label>Language for QA:</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="submission_data[0].language_2"
                            />
                        </div> -->
                        <div class="col-lg-4" v-if="submission_data[0].process_id == 181">
                            <label>bot_disposition_as_per_logic:</label>
                            <div>
                                <select class="form-control" v-model="submission_data[0]
                                        .bot_disposition_as_per_logic
                                    " required="required">
                                    <option value="">Select one!</option>
                                    <option value="Customer Disconnected">Customer Disconnected</option>
                                    <option value="RPC Success">RPC Success</option>
                                    <option value="No Requirement">No Requirement</option>
                                    <option value="RPC Failed">RPC Failed</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Call Back">Call Back</option>
                                    <option value="CT SALE">CT_SALE</option>
                                    <option value="CT Negotiation">CT_Negotiation</option>
                                    <option value="Not Eligible">Not Eligible</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4" v-else>
                            <label>Disposition as per agent logic:</label>
                            <div>
                                <select class="form-control" v-model="submission_data[0]
                                        .bot_disposition_as_per_logic
                                    " required="required">
                                    <option value="">Select one!</option>
                                    <option value="BOT Disconnected">BOT Disconnected</option>
                                    <option value="Call Back">Call Back</option>
                                    <option value="Customer Deceased">Customer Deceased</option>
                                    <option value="Customer Disconnected">Customer Disconnected</option>
                                    <option value="Customer Not Available">Customer Not Available</option>
                                    <option value="DNC">DNC</option>
                                    <option value="Follow up">Follow up</option>
                                    <option value="High Charges">High Charges</option>
                                    <option value="High ROI">High ROI</option>
                                    <option value="Interested">Interested</option>
                                    <option value="Interested Follow up">Interested Follow up</option>
                                    <option value="No Requirement">No Requirement</option>
                                    <option value="Not Eligible">Not Eligible</option>
                                    <option value="Voice Mail">Voice Mail</option>
                                    <option value="Required Higher Loan Amount">Required Higher Loan Amount</option>
                                    <option value="Required Less Amount">Required Less Amount</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Wrong Number">Wrong Number</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Call Time:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_time"
                                required="required" />
                        </div>

                        <div class="col-lg-4">
                            <label>Call Duration:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_duration"
                                required="required" />
                        </div>
                        <div class="col-lg-4">
                            <label>Recording Link:</label>
                            <textarea class="form-control" v-model="submission_data[0].recording_link"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <!-- <div class="col-lg-4">
                            <label>Gender Identification</label>
                            <select
                                class="form-control"
                                v-model="
                                    submission_data[0].gender_identifivation
                                "
                                required="required"
                            >
                                <option value="">Select one!</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="NA">NA</option>
                            </select>
                        </div> -->
                        <!-- <div class="col-lg-4">
                            <label>BOT Identification on Gender</label>
                            <select
                                class="form-control"
                                v-model="
                                    submission_data[0].gender_identifivation_bot
                                "
                                required="required"
                            >
                                <option value="">Select one!</option>
                                <option value="Pass">Pass</option>
                                <option value="Fail">Fail</option>
                                <option value="NA">NA</option>
                            </select>
                        </div> -->

                        <!-- <div class="col-lg-4">
                            <label>Customer Voice</label>
                            <select
                                class="form-control"
                                v-model="submission_data[0].customer_voice"
                                required="required"
                            >
                                <option value="">Select one!</option>
                                <option value="Low">LOW</option>
                                <option value="Medium">MEDIUM</option>
                                <option value="High">HIGH</option>
                                <option value="NA">NA</option>
                            </select>
                        </div> -->
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label>Confidence Score:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].confidence_score" />
                        </div>
                        <div class="col-lg-3">
                            <label>Customer STT 1:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].customer_stt_1" />
                        </div>
                        <div class="col-lg-3">
                            <label>Customer STT 2:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].customer_stt2" />
                        </div>

                        <div class="col-lg-3">
                            <label>Customer STT 3:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].customer_stt_3" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label>Customer STT 4:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].customer_stt_4" />
                        </div>
                        <div class="col-lg-3">
                            <label>Customer STT 5:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].customer_stt_5" />
                        </div>

                        <div class="col-lg-3">
                            <label>Sent to BOT or Not</label>
                            <select class="form-control" v-model="submission_data[0].sent_to_bot_or_not"
                                required="required">
                                <option value="">Select one!</option>
                                <option value="yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label>Call Disconnected By</label>
                            <select class="form-control" v-model="submission_data[0].call_disconnected_by
                                " required="required">
                                <option value="">Select one!</option>
                                <option value="Customer">Customer</option>
                                <option value="BOT">BOT</option>
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label>Disconnected Status</label>
                            <select class="form-control" v-model="submission_data[0].disconnected_status"
                                required="required">
                                <option value="">Select one!</option>
                                <option value="Valid">Valid</option>
                                <option value="Invalid">Invalid</option>
                                <option value="NA">NA</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Gen AI:</label>
                            <select class="form-control" v-model="submission_data[0].gen_ai">
                                <option value="">-- Select Language --</option>
                                <option value="Microsoft">Microsoft</option>
                                <option value="Navana">Navana</option>
                                <option value="Microsoft & Navana">Microsoft and Navana</option>
                                <option value="Navana & Microsoft">Navana and Microsoft</option>
                                <option value="NA">NA</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Variable</label>
                            <select class="form-control" v-model="submission_data[0].variable">
                                <option value="">-- Select Language --</option>
                                <option value="H1">H1</option>
                                <option value="H2">H2</option>
                                <option value="H3">H3</option>
                                <option value="H4">H4</option>
                                <option value="H5">H5</option>
                                <option value="Super Hot Lead">Super Hot Lead</option>
                                <option value="Appointment">Appointment</option>
                                <option value="Consent">Consent</option>
                                <option value="Consent with Appointment">Consent with Appointment</option>
                                <option value="NA">NA</option>
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label>Call Stage</label>
                            <select class="form-control" v-model="submission_data[0].call_stage" required="required">
                                <option value="">Select one!</option>
                                <option value="Opening">Opening</option>
                                <option value="RPC Success">RPC Success</option>
                                <option value="RPC Failed">RPC Failed</option>
                                <option value="Amount Pitched">Amount Pitched</option>
                                <option value="Commercial Pitch">Commercial Pitch</option>
                                <option value="Sale">Sale</option>
                                <option value="No Requirement">No Requirement</option>
                                <option value="Closing">Closing</option>
                                <option value="NA">NA</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="kt-portlet__body" v-else-if="
                    submission_data[0].process_id == 184 ||
                    submission_data[0].process_id == 185 || submission_data[0].process_id == 212
                ">
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Communication Instance ID(Call ID):</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_id" />
                        </div>
                        <div class="col-lg-4">
                            <label>Client:</label>
                            <input type="text" class="form-control" v-model="sheet_basic_data[0].client"
                                readonly="readonly" />
                        </div>

                        <!-- <div class="col-md-4">
                            <label>Partner</label>
                            <v-select
                                :options="partnersoption"
                                v-model="submission_data[0].partner_id"
                                label="label"
                                :reduce="option => option.value"
                                placeholder=""
                            ></v-select>
                        </div> -->
                        <div class="col-md-4">
                            <label>Partner </label>
                            <input type="text" class="form-control" value="Internal" disabled />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Audit Date:</label>
                            <!-- <input type="date" class="form-control" placeholder="yyyy-mm-dd" v-model="submission_data[0].audit_date" required="required" /> -->
                            <input type="text" class="form-control" readonly :value="submission_data[0].audit_date" />
                        </div>

                        <div class="col-md-4">
                            <label>TL Name</label>
                            <v-select :options="qtloption" v-model="submission_data[0].tl_name" label="label"
                                :reduce="option => option.value" placeholder=""></v-select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>QA / QTL Name:</label>
                            <input type="text" class="form-control" :value="auditorName" readonly="readonly" />
                        </div>
                        <div class="col-lg-4">
                            <label>Campaign Name:</label>
                            <input type="text" class="form-control" v-model="campaign_name" />
                        </div>

                        <div class="col-lg-4">
                            <label>Call Sub Type:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_sub_type"
                                required="response" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>LOB:</label>
                            <div>
                                <input type="text" class="form-control" v-model="submission_data[0].lob" />
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <label>Location:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].location" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Disposition:</label>
                            <div>
                                <select class="form-control" v-model="submission_data[0].disposition"
                                    required="required">
                                    <option value="">Select one!</option>
                                    <option value="RTP1">RTP1</option>
                                    <option value="Call Disconnected in 10s">Call Disconnected in 10s</option>
                                    <option value="Customer Disconnected 1">Customer Disconnected 1</option>
                                    <option value="RPC Success1">RPC Success1</option>
                                    <option value="PTP Tomorrow">PTP Tomorrow (PTP date =
                                        Tomorrow)</option>
                                    <option value="PTP Today">PTP Today (PTP date = Today)</option>
                                    <option value="Wrong customer 1">Wrong customer 1</option>
                                    <option value="Already Paid">Already Paid</option>
                                    <option value="Call Disconnected less 10s">Call Disconnected less 10s</option>
                                    <option value="Customer1 Disconnected">Customer1 Disconnected</option>
                                    <option value="Third Party">Third Party</option>
                                    <option value="Wrong number">Wrong number</option>
                                    <option value="Money not debited">Money not debited</option>
                                    <option value="Wrong customer">Wrong customer</option>
                                    <option value="Claims Paid">Claims Paid</option>
                                    <option value="PTP Call back">PTP Call back (PTP date = not
                                        today/tomorrow)</option>
                                    <option value="Medical">Medical</option>
                                    <option value="PTP today Payment assistance">PTP today Payment assistance</option>
                                    <option value="Escalation">Escalation</option>
                                    <option value="Callback1">Callback1</option>
                                    <option value="Medical Issue">Medical Issue</option>
                                    <option value="PTP Partial Pay">PTP Partial Pay</option>
                                    <option value="Charges waiver">Charges waiver</option>
                                    <option value="Dispute">Dispute</option>
                                    <option value="Death in Family">Death in Family</option>
                                    <option value="Threat">Threat</option>
                                    <option value="Death">Death</option>
                                    <option value="Want Charges Wavier">Want Charges Wavier</option>
                                    <option value="Customer Threat">Customer Threat</option>
                                    <option value="Money not deducted">Money not deducted</option>
                                    <option value="Death1">Death1</option>
                                    <option value="Customer Death">Customer Death</option>
                                    <option value="Want Settlement">Want Settlement</option>
                                    <option value="Settlement">Settlement</option>
                                    <option value="EMI Paid">EMI Paid</option>
                                    <option value="Fully Paid">Fully Paid</option>
                                    <option value="EMI Paid - Penal PTP">EMI Paid - Penal PTP</option>
                                    <option value="EMI Paid - Penal RTP">EMI Paid - Penal RTP</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <label>QM-Sheet Version:</label>
                            <input type="text" class="form-control" :value="sheet_basic_data[0].version"
                                readonly="readonly" />
                        </div>

                        <div class="col-lg-4">
                            <label>QRC for QA:</label>
                            <div>
                                <select class="form-control" v-model="submission_data[0].qrc_2" required="required">
                                    <option value="">Select one!</option>
                                    <option value="Query">Query</option>
                                    <option value="Request">Request</option>
                                    <option value="Complaint">Complaint</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Language 1:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].language" />
                        </div>
                        <div class="col-lg-4">
                            <label>Language for QA:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].language_2" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Call Time:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_time"
                                required="required" />
                        </div>

                        <div class="col-lg-4">
                            <label>Call Duration:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_duration"
                                required="required" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label>Confidence Score:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].confidence_score" />
                        </div>
                        <div class="col-lg-3">
                            <label>Customer STT 1:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].customer_stt_1" />
                        </div>
                        <div class="col-lg-3">
                            <label>Customer STT 2:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].customer_stt2" />
                        </div>

                        <div class="col-lg-3">
                            <label>Customer STT 3:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].customer_stt_3" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label>Customer STT 4:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].customer_stt_4" />
                        </div>
                        <div class="col-lg-3">
                            <label>Customer STT 5:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].customer_stt_5" />
                        </div>

                        <div class="col-lg-3">
                            <label>Sent to BOT or Not</label>
                            <select class="form-control" v-model="submission_data[0].sent_to_bot_or_not"
                                required="required">
                                <option value="">Select one!</option>
                                <option value="yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label>Call Disconnected By</label>
                            <select class="form-control" v-model="submission_data[0].call_disconnected_by
                                " required="required">
                                <option value="">Select one!</option>
                                <option value="Customer">Customer</option>
                                <option value="BOT">BOT</option>
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label>Disconnected Status</label>
                            <select class="form-control" v-model="submission_data[0].disconnected_status"
                                required="required">
                                <option value="">Select one!</option>
                                <option value="Valid">Valid</option>
                                <option value="Invalid">Invalid</option>
                                <option value="NA">NA</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Gen AI:</label>
                            <select class="form-control" v-model="submission_data[0].gen_ai">
                                <option value="">-- Select Language --</option>
                                <option value="Microsoft">Microsoft</option>
                                <option value="Navana">Navana</option>
                                <option value="Microsoft & Navana">Microsoft and Navana</option>
                                <option value="Navana & Microsoft">Navana and Microsoft</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Variable</label>
                            <select class="form-control" v-model="submission_data[0].variable">
                                <option value="">-- Select Language --</option>
                                <option value="H1">H1</option>
                                <option value="H2">H2</option>
                                <option value="H3">H3</option>
                                <option value="H4">H4</option>
                                <option value="No Lead">No Lead</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="kt-portlet__body" v-else-if="submission_data[0].process_id == 215 || submission_data[0].process_id == 220">
                     <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Communication Instance ID(Call ID):</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_id" />
                        </div>
                        <div class="col-lg-4">
                            <label>Client:</label>
                            <input type="text" class="form-control" v-model="sheet_basic_data[0].client"
                                readonly="readonly" />
                        </div>

                        <div class="col-md-4">
                            <label> </label>
                            <input type="text" class="form-control" value="Internal" disabled />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Audit Date:</label>
                            <!-- <input type="date" class="form-control" placeholder="yyyy-mm-dd" v-model="submission_data[0].audit_date" required="required" /> -->
                            <input type="text" class="form-control" readonly :value="submission_data[0].audit_date" />
                        </div>

                        <div class="col-md-4">
                            <label>TL Name</label>
                            <input type="text" class="form-control" value="sumith96@crank.com" disabled />
                        </div>
                        <!-- <div class="col-md-4">
                            <label>TL Name</label>
                            <v-select
                                :options="qtloption"
                                v-model="submission_data[0].tl_name"
                                label="label"
                                :reduce="option => option.value"
                                placeholder=""
                            ></v-select>
                        </div> -->
                        <div class="col-lg-4">
                            <label>QA / QTL Name:</label>
                            <input type="text" class="form-control" :value="auditorName" readonly="readonly" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Campaign Name:</label>
                            <input type="text" class="form-control" v-model="campaign_name" />
                        </div>

                        <div class="col-lg-4">
                            <label>Call Sub Type:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_sub_type"
                                required="response" />
                        </div>
                    </div>
                    <div class="form-group row">
                       
                        

                        <div class="col-lg-4" >
                            <label>LOB:</label>
                            <div>
                                <select class="form-control" v-model="submission_data[0].lob" required="required">
                                    <option value="">Select one!</option>
                                    <option value="BFL_RTA_APNT_HINDI">BFL_RTA_APNT_HINDI</option>
                                    <option value="BFL_RTA_PL_BENGALI">BFL_RTA_PL_BENGALI</option>
                                    <option value="BFL_RTA_PL_CUG_HINDI">BFL_RTA_PL_CUG_HINDI</option>
                                    <option value="BFL_RTA_PL_CUG_KANNADA">BFL_RTA_PL_CUG_KANNADA</option>
                                    <option value="BFL_RTA_PL_CUG_MALAYALAM">BFL_RTA_PL_CUG_MALAYALAM</option>
                                    <option value="BFL_RTA_PL_CUG_TAMIL">BFL_RTA_PL_CUG_TAMIL</option>
                                    <option value="BFL_RTA_PL_CUG_TELUGU">BFL_RTA_PL_CUG_TELUGU</option>
                                    <option value="BFL_RTA_PL_HINDI">BFL_RTA_PL_HINDI</option>
                                    <option value="BFL_RTA_PL_KANNADA">BFL_RTA_PL_KANNADA</option>
                                    <option value="BFL_RTA_PL_MALAYALAM">BFL_RTA_PL_MALAYALAM</option>
                                    <option value="BFL_RTA_PL_MARATHI">BFL_RTA_PL_MARATHI</option>
                                    <option value="BFL_RTA_PL_TAMIL">BFL_RTA_PL_TAMIL</option>
                                    <option value="BFL_RTA_PL_TELUGU">BFL_RTA_PL_TELUGU</option>
                                    <option value="BFL_RTA_PPL_CUG_HINDI">BFL_RTA_PPL_CUG_HINDI</option>
                                    <option value="BFL_RTA_RPL_HINDI">BFL_RTA_RPL_HINDI</option>
                                    <option value="BFL_RTA_RPL_KANNADA">BFL_RTA_RPL_KANNADA</option>
                                    <option value="BFL_RTA_RPL_TAMIL">BFL_RTA_RPL_TAMIL</option>
                                    <option value="BFL_RTA_RPL_TELUGU">BFL_RTA_RPL_TELUGU</option>
                                    <option value="BFL_RTA_SPL_CUG_HINDI">BFL_RTA_SPL_CUG_HINDI</option>
                                    <option value="BFL_RTA_SPL_HINDI">BFL_RTA_SPL_HINDI</option>
                                    <option value="EMICARD_VOICE_BOT">EMICARD_VOICE_BOT</option>
                                    <option value="PL_PPL">PL_PPL</option>
                                    <option value="PLCS_BENGALI_NEWJ">PLCS_BENGALI_NEWJ</option>
                                    <option value="PLCS_English_NEWJ">PLCS_English_NEWJ</option>
                                    <option value="PLCS_Kannada_NEWJ">PLCS_Kannada_NEWJ</option>
                                    <option value="PLCS_Malayalam_NEWJ">PLCS_Malayalam_NEWJ</option>
                                    <option value="PLCS_Marathi_NEWJ">PLCS_Marathi_NEWJ</option>
                                    <option value="PLCS_Tamil_NEWJ">PLCS_Tamil_NEWJ</option>
                                    <option value="PLCS_Telugu_NEWJ">PLCS_Telugu_NEWJ</option>
                                    <option value="PLSL_v2">PLSL_v2</option>
                                    <option value="SALPL">SALPL</option>
                                    <option value="SERVICE_PL_BOT">SERVICE_PL_BOT</option>
                                    <option value="SERVICE_VOICE">SERVICE_VOICE</option>
                                    <option value="plcs_new_journey">plcs_new_journey</option>
                                    <option value="BFL_RTA_LC_HINDI">BFL_RTA_LC_HINDI</option>
                                    <option value="BFL_RTA_RLC_HINDI">BFL_RTA_RLC_HINDI</option>
                                    <option value="BFL_RTA_LC_TELUGU">BFL_RTA_LC_TELUGU</option>
                                    <option value="BFL_RTA_LC_BENGALI">BFL_RTA_LC_BENGALI</option>
                                    <option value="BFL_RTA_LC_MALAYALAM">BFL_RTA_LC_MALAYALAM</option>
                                    <option value="BFL_RTA_LC_KANNADA">BFL_RTA_LC_KANNADA</option>
                                    <option value="BFL_RTA_LC_TAMIL">BFL_RTA_LC_TAMIL</option>
                                    <option value="BFL_RTA_APNT_TAMIL">BFL_RTA_APNT_TAMIL</option>
                                    <option value="BFL_RTA_APNT_TELUGU">BFL_RTA_APNT_TELUGU</option>
                                    <option value="BFL_RTA_APNT_BENGALI">BFL_RTA_APNT_BENGALI</option>
                                    <option value="BFL_RTA_APNT_KANNADA">BFL_RTA_APNT_KANNADA</option>
                                    <option value="BFL_RTA_LC_CUG_TAMIL">BFL_RTA_LC_CUG_TAMIL</option>
                                    <option value="BFL_RTA_LC_CUG_HINDI">BFL_RTA_LC_CUG_HINDI</option>
                                    <option value="BFL_RTA_LC_CUG_BENGALI">BFL_RTA_LC_CUG_BENGALI</option>
                                    <option value="BFL_RTA_LC_CUG_KANNADA">BFL_RTA_LC_CUG_KANNADA</option>
                                    <option value="BFL_RTA_LC_CUG_MARATHI">BFL_RTA_LC_CUG_MARATHI</option>
                                    <option value="BFL_RTA_LC_CUG_MALAYALAM">BFL_RTA_LC_CUG_MALAYALAM</option>
                                    <option value="BFL_RTA_PL_CUG_TELUGU">BFL_RTA_PL_CUG_TELUGU</option>
                                    <option value="BFL_RTA_LC_TELUGU">BFL_RTA_LC_TELUGU</option>
                                    <option value="BFL_RTA_FLEXI_CUG_HINDI">BFL_RTA_FLEXI_CUG_HINDI</option>
                                    <option value="BFL_RTA_FLEXI_CUG_ENGLISH">BFL_RTA_FLEXI_CUG_ENGLISH</option>
                                    <option value="BFL_RTA_FLEXI_ENGLISH">BFL_RTA_FLEXI_ENGLISH</option>
                                    <option value="BFL_RTA_FLEXI_HINDI">BFL_RTA_FLEXI_HINDI</option>
                                    <option value="BFL_RTA_ONE_IVR">BFL_RTA_ONE_IVR</option>
                                   
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <label>Customer ID:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].location" />
                        </div>

                        <div class="col-lg-4" v-if="submission_data[0].process_id == 181">
                            <label>Disposition:</label>
                            <div>
                                <select class="form-control" v-model="submission_data[0].disposition"
                                    required="required">
                                    <option value="">Select one!</option>
                                    <option value="Customer Disconnected">Customer Disconnected</option>
                                    <option value="RPC Success">RPC Success</option>
                                    <option value="No Requirement">No Requirement</option>
                                    <option value="RPC Failed">RPC Failed</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Call Back">Call Back</option>
                                    <option value="CT SALE">CT_SALE</option>
                                    <option value="CT Negotiation">CT_Negotiation</option>
                                    <option value="Not Eligible">Not Eligible</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4" v-else-if="submission_data[0].process_id == 193">
                            <label>Disposition:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].disposition"
                                required="response" />
                        </div>

                        <div class="col-lg-4"
                            v-else-if="submission_data[0].process_id == 210 || submission_data[0].process_id == 211">
                            <label>Disposition:</label>
                            <div>
                                <select class="form-control" v-model="submission_data[0].disposition" required>
                                    <option value="">Select one!</option>

                                    <option value="Call Back">Call Back</option>
                                    <option value="CT_Negotiation">CT_Negotiation</option>
                                    <option value="CT_Negotiation_DC">CT_Negotiation_DC</option>
                                    <option value="CT_SALE">CT_SALE</option>
                                    <option value="CT_Sale_DC">CT_Sale_DC</option>
                                    <option value="Customer Disconnected">Customer Disconnected</option>
                                    <option value="No Requirement">No Requirement</option>
                                    <option value="Not Eligible">Not Eligible</option>
                                    <option value="RPC Failed">RPC Failed</option>
                                    <option value="RPC Success">RPC Success</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Same day appointment">Same day appointment</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4" v-else>
                            <label>Disposition:</label>
                            <div>
                                <select class="form-control" v-model="submission_data[0].disposition"
                                    required="required">
                                    <option value="">Select one!</option>
                                    <option value="Customer Disconnected">Customer Disconnected</option>
                                    <option value="RPC Success">RPC Success</option>
                                    <option value="No Requirement">No Requirement</option>
                                    <option value="RPC Failed">RPC Failed</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Call Back">Call Back</option>
                                    <option value="CT SALE">CT_SALE</option>
                                    <option value="CT Negotiation">CT_Negotiation</option>
                                    <option value="Not Eligible">Not Eligible</option>
                                    <option value="CT_Sale_DC">CT_Sale_DC</option>
                                    <option value="CT_Negotiation_DC">CT_Negotiation_DC</option>
                                    <option value="Same day appointment">Same day appointment</option>

                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="form-group row"> -->

                    <!-- <div class="col-lg-4">
                            <label>QM-Sheet Version:</label>
                            <input
                                type="text"
                                class="form-control"
                                :value="sheet_basic_data[0].version"
                                readonly="readonly"
                            />
                        </div> -->

                    <!-- <div class="col-lg-4">
                            <label>QRC for QA:</label>
                            <div>
                                <select
                                    class="form-control"
                                    v-model="submission_data[0].qrc_2"
                                    required="required"
                                >
                                    <option value="">Select one!</option>
                                    <option value="Query">Query</option>
                                    <option value="Request">Request</option>
                                    <option value="Complaint">Complaint</option>
                                </select>
                            </div>
                        </div> -->
                    <!-- </div> -->

                    <div class="form-group row">
                        <!-- <div class="col-lg-4">
                            <label>Language 1:</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="submission_data[0].language"
                            />
                        </div> -->

                        <div class="col-lg-4">
                            <label>Campaign Language:</label>
                            <select class="form-control" v-model="submission_data[0].language_2">
                                <option value="">-- Select Language --</option>
                                <option value="Assamese">Assamese</option>
                                <option value="Bengali">Bengali</option>
                                <option value="English">English</option>
                                <option value="Gujrati">Gujrati</option>
                                <option value="Hindi">Hindi</option>
                                <option value="Kannada">Kannada</option>
                                <option value="Malayalam">Malayalam</option>
                                <option value="Marathi">Marathi</option>
                                <option value="Odia">Odia</option>
                                <option value="Punjabi">Punjabi</option>
                                <option value="Tamil">Tamil</option>
                                <option value="Telugu">Telugu</option>
                            </select>
                        </div>

                        <!-- <div class="col-lg-4">
                            <label>Language for QA:</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="submission_data[0].language_2"
                            />
                        </div> -->
                      
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Call Time:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_time"
                                required="required" />
                        </div>

                        <div class="col-lg-4">
                            <label>Call Duration:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_duration"
                                required="required" />
                        </div>
                        <div class="col-lg-4">
                            <label>Recording Link:</label>
                            <textarea class="form-control" v-model="submission_data[0].recording_link"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <!-- <div class="col-lg-4">
                            <label>Gender Identification</label>
                            <select
                                class="form-control"
                                v-model="
                                    submission_data[0].gender_identifivation
                                "
                                required="required"
                            >
                                <option value="">Select one!</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="NA">NA</option>
                            </select>
                        </div> -->
                        <!-- <div class="col-lg-4">
                            <label>BOT Identification on Gender</label>
                            <select
                                class="form-control"
                                v-model="
                                    submission_data[0].gender_identifivation_bot
                                "
                                required="required"
                            >
                                <option value="">Select one!</option>
                                <option value="Pass">Pass</option>
                                <option value="Fail">Fail</option>
                                <option value="NA">NA</option>
                            </select>
                        </div> -->

                        <!-- <div class="col-lg-4">
                            <label>Customer Voice</label>
                            <select
                                class="form-control"
                                v-model="submission_data[0].customer_voice"
                                required="required"
                            >
                                <option value="">Select one!</option>
                                <option value="Low">LOW</option>
                                <option value="Medium">MEDIUM</option>
                                <option value="High">HIGH</option>
                                <option value="NA">NA</option>
                            </select>
                        </div> -->
                    </div>

                   
                  

                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label>Call Disconnected By</label>
                            <select class="form-control" v-model="submission_data[0].call_disconnected_by
                                " required="required">
                                <option value="">Select one!</option>
                                <option value="Customer">Customer</option>
                                <option value="BOT">BOT</option>
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label>Disconnected Status</label>
                            <select class="form-control" v-model="submission_data[0].disconnected_status"
                                required="required">
                                <option value="">Select one!</option>
                                <option value="Valid">Valid</option>
                                <option value="Invalid">Invalid</option>
                                <option value="NA">NA</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Gen AI:</label>
                            <select class="form-control" v-model="submission_data[0].gen_ai">
                                <option value="">-- Select Language --</option>
                                <option value="Microsoft">Microsoft</option>
                                <option value="Navana">Navana</option>
                                <option value="Microsoft & Navana">Microsoft and Navana</option>
                                <option value="Navana & Microsoft">Navana and Microsoft</option>
                                <option value="NA">NA</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Variable</label>
                            <select class="form-control" v-model="submission_data[0].variable">
                                <option value="">-- Select Language --</option>
                                <option value="H1">H1</option>
                                <option value="H2">H2</option>
                                <option value="H3">H3</option>
                                <option value="H4">H4</option>
                                <option value="H5">H5</option>
                                <option value="Super Hot Lead">Super Hot Lead</option>
                                <option value="Appointment">Appointment</option>
                                <option value="Consent">Consent</option>
                                <option value="Consent with Appointment">Consent with Appointment</option>
                                <option value="NA">NA</option>
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label>Call Stage</label>
                            <select class="form-control" v-model="submission_data[0].call_stage" required="required">
                                <option value="">Select one!</option>
                                <option value="Opening">Opening</option>
                                <option value="RPC Success">RPC Success</option>
                                <option value="RPC Failed">RPC Failed</option>
                                <option value="Amount Pitched">Amount Pitched</option>
                                <option value="Commercial Pitch">Commercial Pitch</option>
                                <option value="Sale">Sale</option>
                                <option value="No Requirement">No Requirement</option>
                                <option value="Closing">Closing</option>
                                <option value="Self Intro">Self Intro</option>
                                <option value="RPC">RPC</option>
                                <option value="Offer">Offer</option>
                                <option value="Commercial">Commercial</option>
                                <option value="CIBIL">CIBIL</option>
                                <option value="Charges">Charges</option>
                                <option value="Appointment">Appointment</option>
                                <option value="Closure">Closure</option>
                                <option value="NA">NA</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="kt-portlet__body" v-else-if="submission_data[0].process_id == 222 || submission_data[0].process_id == 223">
                     <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Communication Instance ID(SR ID):</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_id" />
                        </div>
                        <div class="col-lg-4">
                            <label>Client:</label>
                            <input type="text" class="form-control" v-model="sheet_basic_data[0].client"
                                readonly="readonly" />
                        </div>

                        <div class="col-md-4">
                            <label>Partner </label>
                            <input type="text" class="form-control" value="Internal" disabled />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Audit Date:</label>
                       
                            <input type="text" class="form-control" readonly :value="submission_data[0].audit_date" />
                        </div>

                        <div class="col-md-4">
                            <label>TL Name</label>
                            <input type="text" class="form-control" value="sumith96@crank.com" disabled />
                        </div>
                       
                        <div class="col-lg-4">
                            <label>QA / QTL Name:</label>
                            <input type="text" class="form-control" :value="auditorName" readonly="readonly" />
                        </div>
                    </div>
                    <div class="form-group row">
                         <div class="col-lg-4">
                            <label>SR Month</label>
                            <input type="text" class="form-control" v-model="submission_data[0].month"  />
                        </div>
                        <div class="col-lg-4">
                            <label>Service Recovery</label>
                           
                            <select class="form-control" v-model="submission_data[0].call_stage"
                                required="required">
                                <option value="">Select one!</option>
                                <option value="Required">Required</option>
                                <option value="Not Required">Not Required</option>
                                
                            </select>
                        </div>
                    </div>
                    
                   

                   
                </div>

                <div class="kt-portlet__body" v-else>
                    <div class="form-group row"></div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Communication Instance ID(Call ID):</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_id" />
                        </div>
                        <div class="col-lg-4">
                            <label>Client:</label>
                            <input type="text" class="form-control" v-model="sheet_basic_data[0].client"
                                readonly="readonly" />
                        </div>
                        <!-- <div class="col-lg-4">
                            <label>Partner:</label>

                            <select
                                class="form-control"
                                v-model="submission_data[0].partner_id"
                            >
                               <option v-for="[id, name] in Object.entries(partner_lists)" :key="id" :value="id"> {{ name }} </option>
                            </select>
                        </div> -->

                        <div class="col-md-4">
                            <label>Partner</label>
                            <v-select :options="partnersoption" v-model="submission_data[0].partner_id" label="label"
                                :reduce="option => option.value" placeholder=""></v-select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Audit Date:</label>
                            <!-- <input type="date" class="form-control" placeholder="yyyy-mm-dd" v-model="submission_data[0].audit_date" required="required" /> -->
                            <input type="text" class="form-control" readonly :value="submission_data[0].audit_date" />
                        </div>
                        <div class="col-lg-4">
                            <label>Agent Name:</label>
                            <input type="text" class="form-control" v-model="agent_name" />
                        </div>

                        <div class="col-md-4">
                            <label>TL Name</label>
                            <v-select :options="qtloption" v-model="submission_data[0].tl_name" label="label"
                                :reduce="option => option.value" placeholder=""></v-select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>QA / QTL Name:</label>
                            <input type="text" class="form-control" :value="auditorName" readonly="readonly" />
                        </div>
                        <div class="col-lg-4">
                            <label>Campaign Name:</label>
                            <input type="text" class="form-control" v-model="campaign_name" />
                        </div>

                        <div class="col-lg-4">
                            <label>Call Sub Type:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_sub_type"
                                required="response" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Emp Id:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].emp_id"
                                required="response" />
                        </div>
                        <div class="col-lg-4">
                            <label>LOB:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].lob" />
                        </div>
                        <div class="col-lg-4">
                            <label>Location:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].location" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>hangup_details</label>
                            <input type="text" class="form-control" v-model="submission_data[0].hangup_details"
                                required="response" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Disposition:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].disposition"
                                required="response" />
                        </div>
                        <div class="col-lg-4">
                            <label>Customer Name:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].customer_name" />
                        </div>
                        <div class="col-lg-4">
                            <label>Customer contact number:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].customer_phone" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>QM-Sheet Version:</label>
                            <input type="text" class="form-control" :value="sheet_basic_data[0].version"
                                readonly="readonly" />
                        </div>

                        <div class="col-lg-4">
                            <label>QRC 1:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_type"
                                required="required" />
                        </div>
                        <div class="col-lg-4">
                            <label>QRC for QA:</label>
                            <div>
                                <select class="form-control" v-model="submission_data[0].qrc_2" required="required">
                                    <option value="">Select one!</option>
                                    <option value="Query">Query</option>
                                    <option value="Request">Request</option>
                                    <option value="Complaint">Complaint</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Language 1:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].language" />
                        </div>
                        <div class="col-lg-4">
                            <label>Language for QA:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].language_2" />
                        </div>
                        <div class="col-lg-4">
                            <label>Case ID:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].case_id" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Call Time:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_time"
                                required="required" />
                        </div>

                        <div class="col-lg-4">
                            <label>Call Duration:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_duration"
                                required="required" />
                        </div>

                        <div class="col-lg-4">
                            <label>Refrence No.:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].refrence_number" />
                        </div>
                    </div>

                    <div v-if="n_client_id == 23">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Call Count:</label>
                                <input type="text" class="form-control" v-model="submission_data[0].call_count" />
                            </div>
                            <div class="col-lg-4">
                                <label>Recording Link:</label>
                                <textarea class="form-control" v-model="submission_data[0].recording_link"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Gender Identification</label>
                            <select class="form-control" v-model="submission_data[0].gender_identifivation
                                " required="required">
                                <option value="">Select one!</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="NA">NA</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>BOT Identification on Gender</label>
                            <select class="form-control" v-model="submission_data[0].gender_identifivation_bot
                                " required="required">
                                <option value="">Select one!</option>
                                <option value="Pass">Pass</option>
                                <option value="Fail">Fail</option>
                                <option value="NA">NA</option>
                            </select>
                        </div>

                        <div class="col-lg-4">
                            <label>Customer Voice</label>
                            <select class="form-control" v-model="submission_data[0].customer_voice"
                                required="required">
                                <option value="">Select one!</option>
                                <option value="Low">LOW</option>
                                <option value="Medium">MEDIUM</option>
                                <option value="High">HIGH</option>
                                <option value="NA">NA</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label>Confidence Score:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].confidence_score" />
                        </div>
                        <div class="col-lg-3">
                            <label>Customer STT 1:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].customer_stt_1" />
                        </div>
                        <div class="col-lg-3">
                            <label>Customer STT 2:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].customer_stt2" />
                        </div>

                        <div class="col-lg-3">
                            <label>Customer STT 3:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].customer_stt_3" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label>Customer STT 4:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].customer_stt_4" />
                        </div>
                        <div class="col-lg-3">
                            <label>Customer STT 5:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].customer_stt_5" />
                        </div>

                        <div class="col-lg-3">
                            <label>Sent to BOT or Not</label>
                            <select class="form-control" v-model="submission_data[0].sent_to_bot_or_not"
                                required="required">
                                <option value="">Select one!</option>
                                <option value="yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label>Call Disconnected By</label>
                            <select class="form-control" v-model="submission_data[0].call_disconnected_by
                                " required="required">
                                <option value="">Select one!</option>
                                <option value="Customer">Customer</option>
                                <option value="BOT">BOT</option>
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label>Disconnected Status</label>
                            <select class="form-control" v-model="submission_data[0].disconnected_status"
                                required="required">
                                <option value="">Select one!</option>
                                <option value="Valid">Valid</option>
                                <option value="Invalid">Invalid</option>
                                <option value="NA">NA</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row" v-if="
                        submission_data[0].process_id == 88 ||
                        submission_data[0].process_id == 155
                    ">
                        <div class="col-lg-4">
                            <label>HOT Leads</label>
                            <select class="form-control" v-model="submission_data[0].hot_lead">
                                <option value="">Select one!</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Hot Lead Reasons:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].hotLead_reason" />
                        </div>
                    </div>
                    <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
                    <div class="row" v-if="is_rca_enabled">
                        <div class="col-md-2">
                            <label>RCA Type:</label>
                            <select class="form-control" v-model="selected_rca_type">
                                <option v-for="(vv, kk) in rca_type" :value="kk">
                                    {{ vv }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>RCA Mode:</label>
                            <select class="form-control" v-model="selected_rca_mode" @change="on_rca_mode_change">
                                <option v-for="(vv, kk) in rca_mode" :value="kk">
                                    {{ vv }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>RCA 1:</label>
                            <select class="form-control" v-model="selected_rca1" @change="on_rca1_change">
                                <option v-for="(vv, kk) in rca1" :value="kk">{{
                                    vv
                                    }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>RCA 2:</label>
                            <select class="form-control" v-model="selected_rca2" @change="on_rca2_change">
                                <option v-for="(vv, kk) in rca2" :value="kk">{{
                                    vv
                                    }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>RCA 3:</label>
                            <select class="form-control" v-model="selected_rca3">
                                <option v-for="(vv, kk) in rca3" :value="kk">{{
                                    vv
                                    }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Other Detail:</label>
                            <input type="text" name="" class="form-control" v-model="rca_other_detail" />
                        </div>
                    </div>

                    <div v-if="is_type_2_rca_enabled">
                        <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
                        <div class="row">
                            <div class="col-md-2">
                                <label>Type II RCA Mode:</label>
                                <select class="form-control" v-model="selected_type_2_rca_mode"
                                    @change="on_type_2_rca_mode_change">
                                    <option v-for="(vv, kk) in type_2_rca_mode" :value="kk">
                                        {{ vv }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Type II RCA 1:</label>
                                <select class="form-control" v-model="selected_type_2_rca1"
                                    @change="on_type_2_rca1_change">
                                    <option v-for="(vv, kk) in type_2_rca1" :value="kk">
                                        {{ vv }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Type II RCA 2:</label>
                                <select class="form-control" v-model="selected_type_2_rca2"
                                    @change="on_type_2_rca2_change">
                                    <option v-for="(vv, kk) in type_2_rca2" :value="kk">
                                        {{ vv }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Type II RCA 3:</label>
                                <select class="form-control" v-model="selected_type_2_rca3">
                                    <option v-for="(vv, kk) in type_2_rca3" :value="kk">
                                        {{ vv }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Type II RCA Other Detail:</label>
                                <input type="text" name="" class="form-control" v-model="type_2_rca_other_detail" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--end::Portlet-->

            <!--AHT-->

            <div v-if="submission_data[0].process_id == 215 || submission_data[0].process_id == 220" class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__head"
                    style="background-image: linear-gradient(to right, rgb(132, 94, 194), rgb(179, 156, 208));">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title kt-font-light">
                            BOT Classification
                        </h3>
                    </div>
                </div>

                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="col-12">

                           

                            <!-- Dynamic Rows -->
                            <div v-for="(item, index) in botEntries" :key="index"
                                class="mb-3 d-flex align-items-center">
                                <!-- Dropdown -->
                                <select v-model="item.botStatus" class="form-control mr-2">
                                    <option disabled value="">NA</option>
                                    <option value="NA">NA</option>
                                    <option value="not_sent">Not Sent to BOT</option>
                                    <option value="Incorrect STT">Incorrect STT</option>
                                    <option value="Blank/Empty STT">Blank/Empty STT</option>
                                </select>

                                <!-- Words -->
                                <input type="text" v-model="item.words" class="form-control mx-2" placeholder="Words" />

                                <!-- Confidence Score -->
                                <input type="text" v-model="item.confidenceScore" class="form-control mx-2"
                                    placeholder="Confidence Score" />

                                <!-- Remove Button -->
                                <button @click="removeEntry(index)" class="btn btn-danger">
                                    Remove
                                </button>
                            </div>

                            <!-- Add Button -->
                            <button @click="addEntry" :disabled="botEntries.length >= 20" class="btn btn-primary mt-3">
                                Add Row
                            </button>

                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__head" style="
           background-image: linear-gradient(to right, rgb(132, 94, 194), rgb(144, 109, 198), rgb(156, 125, 201), rgb(168, 140, 205), rgb(179, 156, 208));
          ">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title kt-font-light">
                            AHT For UPST
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="col-12">
                            <div v-for="(time, index) in ahtTimes" :key="index" class="mb-3 d-flex align-items-center">
                                <input type="text" v-model="time.aht" class="form-control mr-2"
                                    placeholder="HH:MM:SS" />
                                <input type="text" v-model="time.callId" class="form-control mx-2"
                                    placeholder="Call ID" />
                                <p @click="removeAHT(index)" class="btn btn-danger">
                                    Remove
                                </p>
                            </div>
                            <p @click="addAHT" :disabled="ahtTimes.length >= 20" class="btn btn-primary mt-3">
                                Add AHT
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!--AHT-->

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
                                <!-- <div class="col-md-2 kt-font-bolder text-center">
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
                                <!-- <div class="col-md-1" style="position:relative;">
            				<label for="choose-file" class="custom-file-upload" id="choose-file-label" style="background:linear-gradient(to right, rgb(132, 94, 194), rgb(144, 109, 198), rgb(156, 125, 201), rgb(168, 140, 205), rgb(179, 156, 208));color:#fff; padding: 8px;border: 1px solid #e3e3e3; border-radius: 5px; border: 1px solid #ccc; display: inline-block;padding: 6px 12px;cursor: pointer;width: 85px;overflow: hidden;   text-overflow: ellipsis;">
   									Add Image
							</label>
							<input name="uploadDocument" type="file" id="choose-file" onclick="myFunction()"  accept=".jpg,.jpeg,.pdf,doc,docx,application/msword,.png" style="display: none;" />
         					</div>	 -->
                                <!-- <div class="col-md-2" style="position: relative">
                                    <label v-for="subs_opt in sp.options">
                                        <div class="col-md-1" style="position: relative"
                                            v-if="subs_opt.value == 'Critical'">
                                            <input type="file" title="Select" class="screenshot"
                                                :ref="'file_' + i + index" v-if="
                                                    subs_opt.value == 'Critical'
                                                " accept="image/jpeg, image/png" name="screenshot" @change="
                                                    handleUpload(
                                                        $event,
                                                        i,
                                                        index,
                                                        'screenshot'
                                                    )
                                                    " />
                                        </div>

                                        <label>
                                            <a v-if="
                                                subs_opt.value == 'Critical'
                                            " @click="
                                                    removeImage(
                                                        $event,
                                                        i,
                                                        index
                                                    )
                                                    ">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </label>
                                    </label>
                                </div> -->
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
                            </select>
                        </div>
                        <!-- <div class="col-lg-2">
                            <label>Upload Call:</label>
                            <input type="file" name="good_bad_call_file" class="form-control" ref="good_bad_call_file"
                                multiple v-on:change="handleCallUpload()" />
                            <div id="myProgress">
                                <div id="myBar">10%</div>
                            </div>
                        </div>
                        <div class="col-lg-1">
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
                <!-- <div class="col-md-2">
                    <button
                        type="button"
                        class="btn btn-warning"
                        id="MySaveButton"
                        @click="submitMe(0)"
                    >
                        Save</button
                    >&nbsp;&nbsp;&nbsp;
                </div> -->
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
            botEntries: [
            {
                botStatus: "",
                words: "",
                confidenceScore: ""
            }
        ],
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
                    call_type: null,
                    call_sub_type: null,
                    campaign_name: null,
                    disposition: null,
                    customer_name: null,
                    customer_phone: null,
                    qm_sheet_version: null,
                    partner_name: null
                }
            ],
            parameters: [],
            subs: [],
            screenshot: [],
            partner_lists: [],
            qtl_lists: [],
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

                    qrc_2: null,
                    language_2: null,
                    second_level_drill: null,
                    audit_date: "",
                    case_id: null,
                    overall_summary: null,
                    feedback_to_agent: null,
                    disposition: null,
                    call_time: null,
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
                    refrence_number: null,
                    hot_lead: null,
                    hotLead_reason: null,
                    customer_voice: null,
                    gender_identifivation_bot: null,
                    gender_identifivation: null,
                    call_id: null,
                    partner_id: null,
                    agent_name: null,
                    language: null,
                    tl_name: null,
                    emp_id: null,
                    lob: null,
                    location: null,
                    hangup_details: null,
                    confidence_score: null,
                    customer_stt_1: null,
                    customer_stt2: null,
                    customer_stt_3: null,
                    customer_stt_4: null,
                    customer_stt_5: null,
                    sent_to_bot_or_not: null,
                    disconnected_status: null,
                    call_stage: null,
                    month: null,
                    gen_ai: null,
                    variable: null,
                    call_disconnected_by: null,
                    bot_disposition_as_per_logic: null
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
            type_2_rca_mode: [],
            selected_type_2_rca_mode: 0,
            type_2_rca1: [],
            selected_type_2_rca1: 0,
            type_2_rca2: [],
            selected_type_2_rca2: 0,
            type_2_rca3: [],
            selected_type_2_rca3: 0,
            type_2_rca_other_detail: null,
            campaign_name: null,
            agent_name: null,
            errors: [],
            audit_cycle: ""
        };
    },

    computed: {
        partnersoption() {
            if (!this.partner_lists) return [];
            return Object.keys(this.partner_lists).map(key => ({
                label: this.partner_lists[key],
                value: key
            }));
        },
        qtloption() {
            if (!this.qtl_lists) return [];
            return Object.keys(this.qtl_lists).map(key => ({
                label: this.qtl_lists[key],
                value: key
            }));
        }
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

                self.submission_data[0].company_id =
                    temp_first_data.sheet_details.company_id;
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

                self.partner_lists = temp_first_data.partners_list;
                self.qtl_lists = temp_first_data.tl_ids;
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
            this.showpopup();
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
                    self.secondTime = Date.now();
                    self.sheet_basic_data[0].call_type = temp_data_b.call_type;
                    self.sheet_basic_data[0].partner_name =
                        temp_data_b.partner_detail.name;

                    self.submission_data[0].customer_phone =
                        temp_data_b.phone_number;
                    self.submission_data[0].customer_name =
                        temp_data_b.customer_name;
                    // self.submission_data[0].call_type = temp_data_b.call_type;

                    self.submission_data[0].partner_id = temp_data_b.partner_id;
                    self.submission_data[0].raw_data_id = temp_data_b.id;

                    self.submission_data[0].disposition =
                        temp_data_b.disposition;
                    self.submission_data[0].call_time = temp_data_b.call_time;
                    self.submission_data[0].call_duration =
                        temp_data_b.call_duration;
                    self.submission_data[0].call_sub_type =
                        temp_data_b.call_sub_type;
                    self.submission_data[0].campaign_name =
                        temp_data_b.campaign_name;
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
            this.submission_data[0].campaign_name = this.campaign_name;
            this.submission_data[0].agent_name = this.agent_name;

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
            const BotTimes = this.botEntries.filter(
                item => item.confidenceScore && item.words && item.botStatus
            );
            var params = {
                submit: this.submit_type,
                submission_data: this.submission_data,
                parameters: this.parameters,
                AHTTimes: AHTTimes,
                BotTimes: BotTimes
            };
            axios
                .post("/allocation/store_audit_bot", params)
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

            // if (
            //     this.submission_data[0].qrc_2 == "" ||
            //     this.submission_data[0].qrc_2 == null
            // )
            //     this.errors.push("QRC required.");

            // if (
            //     this.submission_data[0].customer_voice == "" ||
            //     this.submission_data[0].customer_voice == null
            // )
            //     this.errors.push("customer_voice required.");

            // if (
            //     this.submission_data[0].gender_identifivation_bot == "" ||
            //     this.submission_data[0].gender_identifivation_bot == null
            // )
            //     this.errors.push("bot_identification_on_gender required.");

            // if (
            //     this.submission_data[0].gender_identifivation == "" ||
            //     this.submission_data[0].gender_identifivation == null
            // )
            //     this.errors.push("gender_identification required.");

            if (
                this.submission_data[0].call_id == "" ||
                this.submission_data[0].call_id == null
            )
                this.errors.push("call_id required.");

            // if (
            //     this.submission_data[0].partner_id == "" ||
            //     this.submission_data[0].partner_id == null
            // )
            //     this.errors.push("Partner Selection required.");

            // if (
            //     this.submission_data[0].agent_name == "" ||
            //     this.submission_data[0].agent_name == null
            // )
            //     this.errors.push("Agent Name required.");

            // if (
            //     this.submission_data[0].tl_name == "" ||
            //     this.submission_data[0].tl_name == null
            // )
            //     this.errors.push("TL Name Selection required.");

            // if (
            //     this.submission_data[0].language == "" ||
            //     this.submission_data[0].language == null
            // )
            //     this.errors.push("language required.");

            // if (
            //     this.submission_data[0].call_type == "" ||
            //     this.submission_data[0].call_type == null
            // )
            //     this.errors.push("QRC 1 required.");

            if (
                this.submission_data[0].call_time == "" ||
                this.submission_data[0].call_time == null
            )
                this.errors.push("Call time required.");

            if (
                this.submission_data[0].call_duration == "" ||
                this.submission_data[0].call_duration == null
            )
                this.errors.push("Call duration required.");

            // if (
            //     this.submission_data[0].qrc_2 == "" ||
            //     this.submission_data[0].qrc_2 == null
            // )
            //     this.errors.push("QRC required.");

            if (
                this.submission_data[0].disposition == "" ||
                this.submission_data[0].disposition == null
            )
                this.errors.push("Disposition required.");

            if (
                this.submission_data[0].campaign_name == "" ||
                this.submission_data[0].campaign_name == null
            )
                this.errors.push("Campaign name required.");

            if (this.submission_data[0].process_id == 210) {
                if (
                    this.submission_data[0].call_sub_type == "" ||
                    this.submission_data[0].call_sub_type == null
                )
                    this.errors.push("call_sub_type required.");

                if (
                    this.submission_data[0].bot_disposition_as_per_logic ==
                    "" ||
                    this.submission_data[0].bot_disposition_as_per_logic == null
                )
                    this.errors.push("disposition_as_per_logic required.");

                if (
                    this.submission_data[0].confidence_score == "" ||
                    this.submission_data[0].confidence_score == null
                )
                    this.errors.push("confidence_score required.");

                if (
                    this.submission_data[0].sent_to_bot_or_not == "" ||
                    this.submission_data[0].sent_to_bot_or_not == null
                )
                    this.errors.push("sent_to_bot_or_not required.");

                if (
                    this.submission_data[0].call_disconnected_by == "" ||
                    this.submission_data[0].call_disconnected_by == null
                )
                    this.errors.push("call_disconnected_by required.");

                if (
                    this.submission_data[0].disconnected_status == "" ||
                    this.submission_data[0].disconnected_status == null
                )
                    this.errors.push("disconnected_status required.");

                if (
                    this.submission_data[0].gen_ai == "" ||
                    this.submission_data[0].gen_ai == null
                )
                    this.errors.push("gen_ai required.");

                if (
                    this.submission_data[0].variable == "" ||
                    this.submission_data[0].variable == null
                )
                    this.errors.push("variable required.");

                if (
                    this.submission_data[0].call_stage == "" ||
                    this.submission_data[0].call_stage == null
                )
                    this.errors.push("call_stage required.");
            }

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

            if (
                this.submission_data[0].language_2 == "" ||
                this.submission_data[0].language_2 == null
            )
                this.errors.push("Language QA required.");

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
        },
        addEntry() {
        this.botEntries.push({
            botStatus: "",
            words: "",
            confidenceScore: ""
        });
        },
        removeEntry(index) {
            this.botEntries.splice(index, 1);
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
