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
                <div class="kt-portlet__body" v-if="submission_data[0].process_id == 200">
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Unique Id.:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_id" />
                        </div>
                        <div class="col-lg-4">
                            <label>Client:</label>
                            <input type="text" class="form-control" v-model="sheet_basic_data[0].client"
                                readonly="readonly" />
                        </div>
                        <div class="col-md-4">
                            <label>Partner</label>
                            <input type="text" class="form-control" value="BFL Partner Audits" disabled />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Audit Date:</label>
                            <!-- <input type="date" class="form-control" placeholder="yyyy-mm-dd" v-model="submission_data[0].audit_date" required="required" /> -->
                            <input type="text" class="form-control" readonly :value="submission_data[0].audit_date" />
                        </div>
                        <div class="col-lg-4">
                            <label>QA Name</label>
                            <input type="text" class="form-control" :value="auditorName" readonly="readonly" />
                        </div>
                        <!-- <div class="col-lg-4">                          
                            <label> Location:</label>
                            <select
                                class="form-control"
                                v-model="submission_data[0].partner_location_id"
                                @change="updateLocationName"
                            >
                                <option value="">-- Select Location --</option>
                                <option value="58">Anantapur</option>
                                <option value="61">Bareilly</option>
                                <option value="20">Chennai</option>
                                <option value="31">Jamshedpur</option>
                                <option value="32">Kopargaon</option>
                                <option value="68">Krishnagiri</option>
                                <option value="59">Mangalore</option>
                                <option value="65">Nashik</option>
                                <option value="36">Pune</option>
                                <option value="40">Vadodara</option>
                            </select>
                        </div> -->

                        <div class="col-lg-4">
                            <label>Location:</label>
                            <input type="text" class="form-control" v-model="sheet_basic_data[0].location_name"
                                readonly="readonly" />
                        </div>

                        <div class="col-lg-4">
                            <label>Campaign Name:</label>
                            <input type="text" class="form-control" v-model="campaign_name" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <!-- <div class="col-lg-4">
                            <label>Agent Name:</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="agent_name"
                            />
                        </div> -->
                        <div class="col-lg-4">
                            <label>TL Name</label>
                            <input type="text" class="form-control" v-model="submission_data[0].tl"
                                required="response" />
                        </div>

                        <div class="col-lg-4">
                            <label>Call Type:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_type"
                                required="response" />
                        </div>

                        <div class="col-lg-4">
                            <label> Language:</label>
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
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Name of FA:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].sr_mgr" />
                        </div>
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
                        <div class="col-lg-4">
                            <label>Audit Type:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].audit_type"
                                required="response" />
                        </div>
                        <div class="col-lg-4">
                            <label>Emp Id:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].emp_id"
                                required="response" />
                        </div>
                        <div class="col-lg-4">
                            <label>Tenure:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].month"
                                required="response" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Ops Manager</label>
                            <input type="text" class="form-control" v-model="submission_data[0].user_name" />
                        </div>
                        <div class="col-lg-4">
                            <label>Disposition Actual:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].disposition"
                                required="response" />
                        </div>
                        <div class="col-lg-4">
                            <label>Disposition (Selected by agent)</label>
                            <input type="text" class="form-control" v-model="submission_data[0].case_reason" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>BFL Process</label>
                            <input type="text" class="form-control" v-model="submission_data[0].bfl_process" />
                        </div>
                        <div class="col-lg-4">
                            <label>Customer id:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].customer_id"
                                required="response" />
                        </div>
                        <div class="col-lg-4">
                            <label>Recording Link:</label>
                            <textarea class="form-control" v-model="submission_data[0].recording_link"></textarea>
                        </div>
                        <div class="col-lg-4">
                            <label>ACPT</label>
                            <select class="form-control" v-model="submission_data[0].acpt">
                                <option value="">-- Select --</option>
                                <option value="Agent">Agent</option>
                                <option value="Customer">Customer</option>
                                <option value="Process">Process</option>
                                <option value="Technology">Technology</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body" v-else-if="submission_data[0].process_id == 216">
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Unique Id.:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_id" />
                        </div>
                        <div class="col-lg-4">
                            <label>Client:</label>
                            <input type="text" class="form-control" v-model="sheet_basic_data[0].client"
                                readonly="readonly" />
                        </div>
                        <div class="col-md-4">
                            <label>Partner</label>
                            <input type="text" class="form-control" value="BFL Partner Audits" disabled />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Audit Date:</label>
                            <!-- <input type="date" class="form-control" placeholder="yyyy-mm-dd" v-model="submission_data[0].audit_date" required="required" /> -->
                            <input type="text" class="form-control" readonly :value="submission_data[0].audit_date" />
                        </div>
                        <div class="col-lg-4">
                            <label>Auditor Name</label>
                            <input type="text" class="form-control" :value="auditorName" readonly="readonly" />
                        </div>


                        <div class="col-lg-4">
                            <label>Location:</label>
                            <input type="text" class="form-control" v-model="sheet_basic_data[0].location_name"
                                readonly="readonly" />
                        </div>

                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>B2C ID</label>
                            <input type="text" class="form-control" v-model="submission_data[0].customer_id" />
                        </div>
                        <div class="col-lg-4">
                            <label>Branch Location</label>
                            <input type="text" class="form-control" v-model="campaign_name" />
                        </div>
                        <div class="col-lg-4">
                            <label>Agent Name:</label>
                            <input type="text" class="form-control" v-model="agent_name" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Agent Emp Id:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].emp_id"
                                required="response" />
                        </div>
                        <div class="col-lg-4">
                            <label>Field Executive Name</label>
                            <input type="text" class="form-control" v-model="submission_data[0].vendor_name"
                                required="response" />
                        </div>
                        <div class="col-lg-4">
                            <label>Field Rejection Date and time</label>
                            <input type="text" class="form-control" v-model="submission_data[0].case_create_date"
                                required="response" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Field Remarks</label>
                            <select class="form-control" v-model="submission_data[0].internal_case_reason_c">
                                <option value="">-- Select --</option>
                                <option value="Not Intrested">Not Intrested</option>
                                <option value="Not Eligible">Not Eligible</option>
                                <option value="No Requirement">No Requirement</option>
                                <option value="Follow-up">Follow-up</option>
                                <option value="Follow-up">Follow-up</option>
                                <option value="Submit to Credit">Submit to Credit</option>
                                <option value="Not Contactable">Not Contactable</option>
                                <option value="Call Back">Call Back</option>
                                <option value="OGL">OGL</option>
                                <option value="Already Sale">Already Sale</option>
                            </select>

                        </div>
                        <div class="col-lg-4">
                            <label>Field Sub-remarks</label>
                            <input type="text" class="form-control"
                                v-model="submission_data[0].solution_based_category_c" required="response" />
                        </div>
                        <div class="col-lg-4">
                            <label>Call Date and Time</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_time"
                                required="required" />
                        </div>


                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Call Duration:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_duration"
                                required="required" />
                        </div>

                        <div class="col-lg-4">
                            <label><strong>Dialer Disposition:</strong></label>
                            <select class="form-control" v-model="submission_data[0].disposition" required>
                                <option value="">-- Select Disposition --</option>

                                <option value="Sale/Same Day Sale/UPST Sale/Already Sale">
                                    Sale/Same Day Sale/UPST Sale/Already Sale
                                </option>
                                <option value="Availed with Competetor">Availed with Competetor</option>
                                <option value="Call Back">Call Back</option>
                                <option value="Call Disconnected">Call Disconnected</option>
                                <option value="Cibil norms not met">Cibil norms not met</option>
                                <option value="Connect">Connect</option>
                                <option value="Customer Death/Deceased">Customer Death/Deceased</option>
                                <option value="Customer Disconnected">Customer Disconnected</option>
                                <option value="Customer not available">Customer not available</option>
                                <option value="DNC">DNC</option>
                                <option value="E2E Hybrid Sale">E2E Hybrid Sale</option>
                                <option value="Follow Up">Follow Up</option>
                                <option value="Required higher loan amount">Required higher loan amount</option>
                                <option value="High Charges">High Charges</option>
                                <option value="High ROI">High ROI</option>
                                <option value="Interested">Interested</option>
                                <option value="Interested but OGL">Interested but OGL</option>
                                <option value="Interested Follow Up">Interested Follow Up</option>
                                <option value="Irritate Customer">Irritate Customer</option>
                                <option value="Lead Verified">Lead Verified</option>
                                <option value="Required lesser loan amount">Required lesser loan amount</option>
                                <option value="Manual">Manual</option>
                                <option value="Negative Area">Negative Area</option>
                                <option value="Negative Profile">Negative Profile</option>
                                <option value="No requirement">No requirement</option>
                                <option value="Non contactable">Non contactable</option>
                                <option value="Not Connected">Not Connected</option>
                                <option value="Not Eligible">Not Eligible</option>
                                <option value="Not Interested">Not Interested</option>
                                <option value="OGL">OGL</option>
                                <option value="Others">Others</option>
                                <option value="Over Obligation">Over Obligation</option>
                                <option value="Policy norms not met">Policy norms not met</option>
                                <option value="RPC not found">RPC not found</option>
                                <option value="Salary norms not met">Salary norms not met</option>
                                <option value="Self Employed">Self Employed</option>
                                <option value="Service Issue">Service Issue</option>
                                <option value="Voice issue">Voice issue</option>
                                <option value="Wrong Number">Wrong Number</option>

                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Sub Disposition</label>
                            <input type="text" class="form-control" v-model="submission_data[0].sfdc_disposition"
                                required="response" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>LOB:</label>
                            <div>
                                <select class="form-control" v-model="submission_data[0].lob" required="required">
                                    <option value="">Select one!</option>

                                    <option value="AHFO">AHFO</option>
                                    <option value="PLCS_Growth - T3 & T4 S&E">PLCS_Growth - T3 & T4 S&E</option>
                                    <option value="Commercial Finance">Commercial Finance</option>
                                    <option value="Fixed Deposit">Fixed Deposit</option>
                                    <option value="Gold loan">Gold loan</option>
                                    <option value="Insurance">Insurance</option>
                                    <option value="LAS">LAS</option>
                                    <option value="Life Insurance-BALIC">Life Insurance-BALIC</option>
                                    <option value="New Car Finance">New Car Finance</option>
                                    <option value="Payment QR">Payment QR</option>
                                    <option value="PL Emerging">PL Emerging</option>
                                    <option value="PLCS Prime T1">PLCS Prime T1</option>
                                    <option value="PLCS Prime T2">PLCS Prime T2</option>
                                    <option value="PLCS_Growth - T3 & T4 N&E">PLCS_Growth - T3 & T4 N&E</option>
                                    <option value="PLCS_Growth - T3 & T4 S&W">PLCS_Growth - T3 & T4 S&W</option>
                                    <option value="Professional Loan Dr.">Professional Loan Dr.</option>
                                    <option value="Professional Loan Sal">Professional Loan Sal</option>
                                    <option value="Remi">Remi</option>
                                    <option value="SALPL Growth">SALPL Growth</option>
                                    <option value="SALPL Prime">SALPL Prime</option>
                                    <option value="Tractor Finance">Tractor Finance</option>
                                    <option value="UCF">UCF</option>
                                    <option value="OMPL">OMPL</option>
                                    <option value="SME Flexi">SME Flexi</option>
                                    <option value="SME Secured">SME Secured</option>
                                    <option value="SME Unsecured">SME Unsecured</option>
                                    <option value="Rural-GIN PLTB">Rural-GIN PLTB</option>
                                    <option value="RTL-SE">RTL-SE</option>
                                    <option value="RTL-NW">RTL-NW</option>
                                    <option value="RSL">RSL</option>

                                </select>
                            </div>
                        </div>
                        <!-- <div class="col-lg-4">
                            <label>ACPT</label>
                            <select class="form-control" v-model="submission_data[0].acpt">
                                <option value="">-- Select --</option>
                                <option value="Agent">Agent</option>
                                <option value="Customer">Customer</option>
                                <option value="Process">Process</option>
                                <option value="Technology">Technology</option>
                            </select>
                        </div> -->


                        <div class="col-lg-4">
                            <label>ACPT (L1)</label>
                            <select class="form-control" v-model="submission_data[0].acpt">
                                <option value="">-- Select --</option>
                                <option value="Agent">Agent</option>
                                <option value="Customer">Customer</option>
                                <option value="Process">Process</option>
                                <option value="Technology">Technology</option>
                            </select>
                        </div>

                        <div class="col-lg-4" v-if="submission_data[0].acpt">
                            <label>ACPT (L2)</label>
                            <select class="form-control" v-model="submission_data[0].acpt_l2">
                                <option value="">-- Select --</option>
                                <option v-for="(item, index) in filteredL2Options" :key="index" :value="item">
                                    {{ item }}
                                </option>
                            </select>
                        </div>




                        <div class="col-lg-4">
                            <label>ACPT Remarks</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_type" />
                        </div>




                    </div>
                    <div class="form-group row">
                        <!-- <div class="col-lg-4">
                            <label>Agent Opportunity</label>
                            <input type="text" class="form-control" v-model="submission_data[0].focused_audit_text" />
                        </div> -->

                        <div class="col-lg-4">
                            <label>Login Possibility</label>
                            <select class="form-control" v-model="submission_data[0].call_stage" required="required">
                                <option value="">Select one!</option>
                                <option value="Yes">Yes</option>
                                <option value="NO">NO</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Recording Link:</label>
                            <textarea class="form-control" v-model="submission_data[0].recording_link"></textarea>
                        </div>

                    </div>




                </div>

                <div class="kt-portlet__body" v-else>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Unique Id.:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_id" />
                        </div>
                        <div class="col-lg-4">
                            <label>Client:</label>
                            <input type="text" class="form-control" v-model="sheet_basic_data[0].client"
                                readonly="readonly" />
                        </div>
                        <div class="col-md-4">
                            <label>Partner</label>
                            <input type="text" class="form-control" value="BFL Partner Audits" disabled />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Audit Date:</label>
                            <!-- <input type="date" class="form-control" placeholder="yyyy-mm-dd" v-model="submission_data[0].audit_date" required="required" /> -->
                            <input type="text" class="form-control" readonly :value="submission_data[0].audit_date" />
                        </div>
                        <div class="col-lg-4">
                            <label>QA / QTL Name:</label>
                            <input type="text" class="form-control" :value="auditorName" readonly="readonly" />
                        </div>
                        <div class="col-lg-4" v-if="submission_data[0].process_id != 204">
                            <label>Campaign Name:</label>
                            <input type="text" class="form-control" v-model="campaign_name" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Agent Name:</label>
                            <input type="text" class="form-control" v-model="agent_name" />
                        </div>

                        <div class="col-lg-4">
                            <label>Emp Id:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].emp_id"
                                required="response" />
                        </div>
                        <div class="col-lg-4" v-if="submission_data[0].process_id != 204">
                            <label>Call Sub Type:</label>
                            <input type="text" class="form-control" v-model="submission_data[0].call_sub_type"
                                required="response" />
                        </div>
                        <!-- <div class="col-lg-4">
                            <label>Customer Name:</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="submission_data[0].customer_name"
                            />
                        </div> -->
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label><strong>Disposition:</strong></label>
                            <select class="form-control" v-model="submission_data[0].disposition" required>
                                <option value="">-- Select Disposition --</option>
                                <option>Already Sale/ Sale/ UPST Sale/ Same Day
                                    Sale</option>
                                <option>Availed With Competitor</option>
                                <option>Language Barrier</option>
                                <option>BFL OGL</option>
                                <option>Call Back</option>
                                <option>Call Disconnected</option>
                                <option>CIBIL Norms not met</option>
                                <option>Customer Death / Deceased</option>
                                <option>Customer Disconnected</option>
                                <option>Customer Not Available</option>
                                <option>DNC</option>
                                <option>E2E Hybrid Sale</option>
                                <option>Follow Up</option>
                                <option>Required Higher Loan Amount</option>
                                <option>High Charges</option>
                                <option>High ROI</option>
                                <option>Interested</option>
                                <option>Interested But OGL</option>
                                <option>Interested Follow Up</option>
                                <option>Irritate Customer</option>
                                <option>Lead Verified</option>
                                <option>Required Less Loan Amount</option>
                                <option>Manual</option>
                                <option>Negative Area</option>
                                <option>Negative Profile</option>
                                <option>No Requirement</option>
                                <option>Non Contactable</option>
                                <option>Not Eligible</option>
                                <option>Not Interested</option>
                                <option>OGL</option>
                                <option>Others</option>
                                <option>Over Obligation</option>
                                <option>Policy Norms Not Met</option>
                                <option>RPC Not Found</option>
                                <option>Salary Norms Not Met</option>
                                <option>Self employed</option>
                                <option>Service Issue/Enquiry</option>
                                <option>Voice issue</option>
                                <option>Auto Wrap</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Sub Disposition</label>
                            <input type="text" class="form-control" v-model="submission_data[0].sfdc_disposition"
                                required="response" />
                        </div>
                        <div class="col-lg-4">
                            <label> Language:</label>
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

                        <div class="col-lg-4" v-if="submission_data[0].process_id != 204">
                            <label>Call Count for this case:</label>
                            <select class="form-control" v-model="submission_data[0].call_count">
                                <option value="">-- Select Call Count --</option>
                                <option v-for="n in 50" :key="n" :value="n">{{
                                    n
                                }}</option>
                            </select>
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
                            <label> Location:</label>
                            <select
                                class="form-control"
                                v-model="submission_data[0].partner_location_id"
                                @change="updateLocationName"
                            >
                                <option value="">-- Select Location --</option>
                                <option value="58">Anantapur</option>
                                <option value="61">Bareilly</option>
                                <option value="20">Chennai</option>
                                <option value="31">Jamshedpur</option>
                                <option value="32">Kopargaon</option>
                                <option value="68">Krishnagiri</option>
                                <option value="59">Mangalore</option>
                                <option value="65">Nashik</option>
                                <option value="36">Pune</option>
                                <option value="40">Vadodara</option>
                            </select>
                        </div> -->
                        <div class="col-lg-4">
                            <label>Location:</label>
                            <input type="text" class="form-control" v-model="sheet_basic_data[0].location_name"
                                readonly="readonly" />
                        </div>
                        <div class="col-lg-4" v-if="!sheet_basic_data[0].location_name">
                            <label> Location:</label>
                            <select class="form-control" v-model="submission_data[0].partner_location_id"
                                @change="updateLocationName">
                                <option value="">-- Select Location --</option>
                                <option value="58">Anantapur</option>
                                <option value="61">Bareilly</option>
                                <option value="20">Chennai</option>
                                <option value="31">Jamshedpur</option>
                                <option value="32">Kopargaon</option>
                                <option value="68">Krishnagiri</option>
                                <option value="59">Mangalore</option>
                                <option value="65">Nashik</option>
                                <option value="36">Pune</option>
                                <option value="40">Vadodara</option>
                            </select>
                        </div>

                        <div class="col-lg-4" v-if="
                            submission_data[0].process_id != 204 &&
                            submission_data[0].process_id != 191 &&
                            submission_data[0].process_id != 208 &&
                            submission_data[0].process_id != 205
                        ">
                            <label>Audit Type:</label>
                            <select class="form-control" v-model="submission_data[0].audit_type">
                                <option value="">-- Select --</option>
                                <option value="Focus Audit">Focus Audit</option>
                                <option value="BAU Audit">BAU Audit</option>
                                <option value="OJT">OJT</option>
                                <option value="Theme Specific">Theme Specific</option>
                            </select>
                        </div>
                        <div class="col-lg-4" v-if="submission_data[0].process_id != 204">
                            <label>Sale/ Non-sale:</label>
                            <select class="form-control" v-model="submission_data[0].call_type">
                                <option value="">-- Select --</option>
                                <option value="Sale">Sale</option>
                                <option value="Non-sale">Non-sale</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4" v-if="
                            submission_data[0].process_id != 191 ||
                            submission_data[0].process_id != 208 ||
                            submission_data[0].process_id != 205
                        ">
                            <label>Focused Audit Sub type:</label>
                            <select class="form-control" v-model="submission_data[0].focusd_sub_type">
                                <option value="">-- Select --</option>
                                <option value="Short Call">Short Call</option>
                                <option value="Script Adherence">Script Adherence</option>
                                <option value="Agent Call disconnection">Agent Call disconnection</option>
                                <option value="Follow up call">Follow up call</option>
                                <option value="Disposition Audit">Disposition Audit</option>
                                <option value="Wrong Number Audit">Wrong Number Audit</option>
                                <option value="DNC Audit">DNC Audit</option>
                                <option value="Other">Other</option>
                                <option value="NA">NA</option>
                            </select>
                        </div>
                        <div class="col-lg-4" v-if="
                            submission_data[0].process_id != 191 ||
                            submission_data[0].process_id != 208 ||
                            submission_data[0].process_id != 205
                        ">
                            <label>Focused Audit Sub type if any</label>
                            <input type="text" class="form-control" v-model="submission_data[0].focused_audit_text"
                                required="response" />
                        </div>

                        <div class="col-lg-4">
                            <label>LOB:</label>
                            <div>
                                <select class="form-control" v-model="submission_data[0].lob" required="required">
                                    <option value="">Select one!</option>
                                    <option value="AHFO">AHFO</option>
                                    <!-- <option value="Business">Business</option> -->
                                    <option value="PLCS_Growth - T3 & T4 S&E">PLCS_Growth - T3 & T4 S&E</option>
                                    <option value="Commercial Finance">Commercial Finance</option>
                                    <option value="Fixed Deposit">Fixed Deposit</option>
                                    <option value="Gold loan">Gold loan</option>
                                    <option value="Insurance">Insurance</option>
                                    <!-- <option
                                        value="Insurance Telebinding+ Distribution"
                                    >
                                        Insurance Telebinding+ Distribution
                                    </option> -->
                                    <option value="LAS">LAS</option>
                                    <option value="Life Insurance-BALIC">Life Insurance-BALIC</option>
                                    <option value="New Car Finance">New Car Finance</option>
                                    <option value="Payment QR">Payment QR</option>
                                    <option value="PL Emerging">PL Emerging</option>
                                    <!-- <option value="PLCS PLTB">PLCS PLTB</option> -->
                                    <option value="PLCS Prime T1">PLCS Prime T1</option>
                                    <!-- <option value="PLCS Prime T1 - Central"
                                        >PLCS Prime T1 - Central</option
                                    > -->
                                    <option value="PLCS Prime T2">PLCS Prime T2</option>
                                    <option value="PLCS_Growth - T3 & T4 N&E">
                                        PLCS_Growth - T3 & T4 N&E
                                    </option>
                                    <option value="PLCS_Growth - T3 & T4 S&W">
                                        PLCS_Growth - T3 & T4 S&W
                                    </option>
                                    <option value="Professional Loan Dr.">Professional Loan Dr.</option>
                                    <option value="Professional Loan Sal">Professional Loan Sal</option>
                                    <option value="Remi">Remi</option>
                                    <!-- <option value="RPL Emerging"
                                        >RPL Emerging</option
                                    >
                                    <option value="RPL Prime">RPL Prime</option> -->
                                    <!-- <option value="RPPL">RPPL</option>
                                    <option value="RSL">RSL</option> -->
                                    <!-- <option value="Rural GIN">Rural GIN</option> -->
                                    <option value="SALPL Growth">SALPL Growth</option>
                                    <option value="SALPL Prime">SALPL Prime</option>

                                    <option value="Tractor Finance">Tractor Finance</option>
                                    <option value="UCF">UCF</option>
                                    <option value="OMPL">OMPL</option>
                                    <!-- <option value="SME Loan">SME Loan</option> -->
                                    <option value="SME  Flexi">SME Flexi</option>
                                    <option value="SME Secured">SME Secured</option>
                                    <option value="SME Unsecured">SME Unsecured</option>
                                    <option value="Rural-GIN PLTB">Rural-GIN PLTB</option>
                                    <option value="RTL-SE">RTL-SE</option>
                                    <option value="RTL-NW">RTL-NW</option>
                                    <option value="RSL">RSL</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>ACPT</label>
                            <select class="form-control" v-model="submission_data[0].acpt">
                                <option value="">-- Select --</option>
                                <option value="Agent">Agent</option>
                                <option value="Customer">Customer</option>
                                <option value="Process">Process</option>
                                <option value="Technology">Technology</option>
                            </select>
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


                        <div class="col-lg-4" v-if="submission_data[0].process_id == 216">
                            <label>Customer VOC</label>
                            <textarea class="form-control" v-model="submission_data[0].case_comment"></textarea>

                            <!-- <input type="text" class="form-control" v-model="submission_data[0].case_comment" /> -->
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
                <div class="col-lg-12">
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
                            <div class="row" v-for="pm in parameters"
                                style="border-bottom: 1px solid #ccc; padding: 20px 0; height: 100%"
                                v-if="pm.is_non_scoring == 0">
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
                    location_id: null,
                    location_name: null,
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
                    location_id: null,
                    process_id: null,
                    raw_data_id: null,
                    audited_by_id: null,
                    is_critical: null,
                    is_partial_marking: null,
                    overall_score: null,
                    partner_location_id: null,
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
                    sr_mgr: null,
                    call_type: null,
                    tl: null,
                    focusd_sub_type: null,
                    audit_type: null,
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
                    sfdc_disposition: null,
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

                    acpt: null,
                    acpt_l2: "",
                    user_name: null,
                    bfl_process: null,
                    customer_id: null,
                    call_stage: null,
                    case_comment: null,
                    focused_audit_text: null,
                    solution_based_category_c: null,
                    internal_case_reason_c: null,
                    case_create_date: null,
                    vendor_name: null,
                    case_reason: null,
                    month: null,
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
                    call_disconnected_by: null,
                    sub_issues: null,

                    bot_disposition_as_per_logic: null
                }
            ],
            acptOptions: {
                Agent: [
                    'Consent not taken properly',
                    'Forceful consent observed',
                    'Mandatory details (DOB, address, PIN, income) not confirmed',
                    'Incomplete customer profiling done (Designation, company name)',
                    'IRR/ROI not informed',
                    'Charges (PF, Insurance) not informed',
                    'Appointment date and time not confirmed properly',
                    'AA not done / not pitched / ABB probing missing',
                    'CIBIL, bounce, overdue, and eligibility checks missed',
                    'OGL and location criteria not validated accurately',
                    'Follow-up / callback / NI cases wrongly tagged as sales',
                    'Not Eligible case tagged as Sale',
                    'Misselling/Malware practice observed'

                ],

                Customer: [
                    'Customer Not Interested',
                    'Customer Not Eligible',
                    'Customer RPC Done',
                    'Customer shared incomplete address',
                    'Customer not given final consent after multiple attempt',
                    'Customer not shared profiling details after multiple attempt by agents',
                    'Frequent customer mindset changes and drop-offs',
                    'Customer is in OGL',
                    'Customer belongs to negative area',
                    'Customer unwilling for AA / consent due to trust issues',
                    'Income, business proof, and vintage validation gaps',
                    'High ROI/IRR',
                    'Customer need high loan amount',
                    'Existing loan / obligation impacting eligibility',
                    'Technical dependency impacting customer verification (CIBIL / AA issues)',
                    'Customer denied for Insurance'

                ],

                Process: [
                    'Customer Interested however field team wrongly tagged as NE',
                    'Customer Interested however field team wrongly tagged as NI',
                    'AA not completed / technical dependency observed',
                    'CIBIL and eligibility criteria not met in multiple cases',
                    'Offer hold / eligibility changes post lead tagging',
                    'SFDC data visibility issues (offer, bounce, enquiry not reflecting)',
                    'ABB and banking validation gaps / norms not met',
                    'Customer demanding higher loan amount beyond eligibility',
                    'System limitations in checking bounce, transactions, and rejection history',
                    'Multiple ECS / EMI bounce issues not reflecting properly',
                    'Field-level issues (geo-tag, visit pending, rejection reason missing)',
                    'Approval dependency cases (BH / SM involvement required)',
                    'LAN changed by Field team due to Process Limitations',
                    'Non contact center case'

                ],

                Technology: [
                    'System limitations in checking bounce, transactions, and rejection history',
                    'Multiple ECS / EMI bounce issues not reflecting properly',
                    'Technical issues impacting application creation and process flow',
                    'AA not fetched due to technical issues',
                    'EMI/RTN not visible in system',
                    'CIBIL not fetched on call',
                    'Manual ABB dependency due to system gaps',
                    'Offer stuck on hold post lead tagging'
                ]
            },
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
        },
        filteredL2Options() {
            return this.acptOptions[this.submission_data[0].acpt] || [];
        }
    },
    watch: {
        'submission_data[0].acpt'(newVal) {
            this.submission_data[0].acpt_l2 = "";
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
                // ---
                self.sheet_basic_data[0].location_id =
                    temp_first_data.region.id;
                self.sheet_basic_data[0].location_name =
                    temp_first_data.region.name;

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

                self.submission_data[0].partner_location_id =
                    temp_first_data.region.id;

                self.submission_data[0].location = temp_first_data.region.name;

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
        updateLocationName(event) {
            const selectedText =
                event.target.options[event.target.selectedIndex].text;
            this.submission_data[0].location = selectedText;
            this.sheet_basic_data[0].location_name = selectedText;
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
            this.submission_data[0].focusd_sub_type = null;
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
            this.submission_data[0].audit_type = null;
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

                    // self.submission_data[0].partner_id = temp_data_b.partner_id;
                    self.submission_data[0].partner_id = 146;
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

                    if (!self.sheet_basic_data[0].location_name) {
                        // location empty → use region.id
                        self.submission_data[0].partner_location_id =
                            submission_data[0].partner_location_id;
                        self.submission_data[0].location = this.submission_data[0].location;
                    } else {
                        self.submission_data[0].partner_location_id =
                            temp_first_data.region.id;

                        self.submission_data[0].location =
                            temp_first_data.region.name;
                    }
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
                    //we have to add query here
                    total_scorable =
                        total_scorable +
                        Number(this.parameters[key].subs[key_s]["temp_weight"]);
                    //end here

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
            // this.submission_data[0].audit_type = this.audit_type;
            this.submission_data[0].agent_name = this.agent_name;
            this.submission_data[0].tl_name = 3549;
            this.submission_data[0].partner_id = 146;

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

            if (this.submission_data[0].process_id == 200) {
                if (
                    this.submission_data[0].call_id == "" ||
                    this.submission_data[0].call_id == null
                )
                    this.errors.push("Unique Id required.");

                if (
                    this.submission_data[0].audit_type == "" ||
                    this.submission_data[0].audit_type == null
                )
                    this.errors.push("audit_type Selection required.");

                if (
                    this.submission_data[0].tl == "" ||
                    this.submission_data[0].tl == null
                )
                    this.errors.push("tl Selection required.");

                if (
                    this.submission_data[0].disposition == "" ||
                    this.submission_data[0].disposition == null
                )
                    this.errors.push("Disposition Actual  required.");

                if (
                    this.submission_data[0].case_reason == "" ||
                    this.submission_data[0].case_reason == null
                )
                    this.errors.push(
                        "Disposition (Selected by agent)  required."
                    );

                if (
                    this.submission_data[0].recording_link == "" ||
                    this.submission_data[0].recording_link == null
                )
                    this.errors.push("recording_link required.");

                if (
                    this.submission_data[0].bfl_process == "" ||
                    this.submission_data[0].bfl_process == null
                )
                    this.errors.push("BFL Process required.");

                if (
                    this.submission_data[0].customer_id == "" ||
                    this.submission_data[0].customer_id == null
                )
                    this.errors.push("customer_id required.");

                if (
                    this.submission_data[0].acpt == "" ||
                    this.submission_data[0].acpt == null
                )
                    this.errors.push("acpt required.");

                if (
                    this.submission_data[0].call_type == "" ||
                    this.submission_data[0].call_type == null
                )
                    this.errors.push("call_type required.");

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

                if (
                    this.submission_data[0].campaign_name == "" ||
                    this.submission_data[0].campaign_name == null
                )
                    this.errors.push("Campaign name required.");

                if (
                    this.submission_data[0].emp_id == "" ||
                    this.submission_data[0].emp_id == null
                )
                    this.errors.push("emp_id required.");

                if (
                    this.submission_data[0].sr_mgr == "" ||
                    this.submission_data[0].sr_mgr == null
                )
                    this.errors.push("Name of FA required.");

                if (
                    this.submission_data[0].user_name == "" ||
                    this.submission_data[0].user_name == null
                )
                    this.errors.push("Ops Manager required.");

                if (
                    this.submission_data[0].language_2 == "" ||
                    this.submission_data[0].language_2 == null
                )
                    this.errors.push("Language QA required.");

                if (
                    this.submission_data[0].month == "" ||
                    this.submission_data[0].month == null
                )
                    this.errors.push("Tenure required.");

                if (
                    this.submission_data[0].overall_summary == "" ||
                    this.submission_data[0].overall_summary == null
                )
                    this.errors.push("Overall summary QA required.");
            }




            else {

                if (this.submission_data[0].process_id == 216) {
                    if (
                        this.submission_data[0].call_id == "" ||
                        this.submission_data[0].call_id == null
                    )
                        this.errors.push("call_id required.");

                    if (
                        this.submission_data[0].campaign_name == "" ||
                        this.submission_data[0].campaign_name == null
                    )
                        this.errors.push("Location required.");

                }
                else {
                    if (
                        this.submission_data[0].call_id == "" ||
                        this.submission_data[0].call_id == null
                    )
                        this.errors.push("call_id required.");

                    if (this.submission_data[0].process_id != 204) {
                        if (
                            this.submission_data[0].campaign_name == "" ||
                            this.submission_data[0].campaign_name == null
                        )
                            this.errors.push("Campaign name required.");


                        if (
                            this.submission_data[0].call_sub_type == "" ||
                            this.submission_data[0].call_sub_type == null
                        )
                            this.errors.push("call_sub_type required.");

                        if (
                            this.submission_data[0].call_count == "" ||
                            this.submission_data[0].call_count == null
                        )
                            this.errors.push("call_count required.");


                        if (
                            this.submission_data[0].call_type == "" ||
                            this.submission_data[0].call_type == null
                        )
                            this.errors.push("Sale/Not sale Selection required.");
                    }

                    if (
                        this.submission_data[0].process_id != 191 &&
                        this.submission_data[0].process_id != 208 &&
                        this.submission_data[0].process_id != 204 &&
                        this.submission_data[0].process_id != 205 &&
                        this.submission_data[0].process_id != 216
                    ) {
                        if (
                            this.submission_data[0].audit_type == "" ||
                            this.submission_data[0].audit_type == null
                        )
                            this.errors.push("audit_type Selection required.");
                    }

                    if (
                        this.submission_data[0].process_id != 191 &&
                        this.submission_data[0].process_id != 208 &&
                        this.submission_data[0].process_id != 205 && this.submission_data[0].process_id != 216
                    ) {
                        if (
                            this.submission_data[0].focusd_sub_type == "" ||
                            this.submission_data[0].focusd_sub_type == null
                        )
                            this.errors.push("focusd_sub_type required.");
                    }

                    if (
                        this.submission_data[0].agent_name == "" ||
                        this.submission_data[0].agent_name == null
                    )
                        this.errors.push("Agent Name required.");

                    if (
                        this.submission_data[0].emp_id == "" ||
                        this.submission_data[0].emp_id == null
                    )
                        this.errors.push("emp_id required.");

                    if (
                        this.submission_data[0].disposition == "" ||
                        this.submission_data[0].disposition == null
                    )
                        this.errors.push("Disposition Actual  required.");

                    if (
                        this.submission_data[0].sfdc_disposition == "" ||
                        this.submission_data[0].sfdc_disposition == null
                    )
                        this.errors.push("sfdc_disposition  required.");
                    if (this.submission_data[0].process_id != 216 || this.submission_data[0].process_id != 204) {

                        if (
                            this.submission_data[0].language_2 == "" ||
                            this.submission_data[0].language_2 == null
                        )
                            this.errors.push("Language QA required.");
                    }
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

                    if (
                        this.submission_data[0].recording_link == "" ||
                        this.submission_data[0].recording_link == null
                    )
                        this.errors.push("recording_link required.");

                    if (
                        this.submission_data[0].lob == "" ||
                        this.submission_data[0].lob == null
                    )
                        this.errors.push("lob  required.");

                    if (
                        this.submission_data[0].acpt == "" ||
                        this.submission_data[0].acpt == null
                    )
                        this.errors.push("acpt  required.");

                }

            }

            // const emailRegex = /[\w.-]+@[\w-]+\.[\w.-]+/; // removed ^$
            //  const phoneRegex = /\b[6-9]\d{9}\b/;   // removed ^$
            // const panRegex = /[A-Z]{5}[0-9]{4}[A-Z]{1}/; // removed ^$
            const emailRegex = /\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\b/;
            const phoneRegex = /\b[6-9]\d{9}\b/;
            const panRegex = /\b[A-Z]{5}[0-9]{4}[A-Z]\b/;

            const data = this.submission_data[0];
            Object.keys(data).forEach(field => {
                const value = data[field] ? data[field].toString().trim() : "";

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
                                this.parameters[key_param].subs[key_sub_param]
                                    .name + " observation required."
                            );
                        } else {
                            if (
                                this.parameters[key_param].subs[key_sub_param]
                                    .is_non_scoring
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
                                                this.parameters[key_param].subs[
                                                    key_sub_param
                                                ].remark.length < 25
                                            )
                                                this.errors.push(
                                                    this.parameters[key_param]
                                                        .subs[key_sub_param]
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
                                                this.parameters[key_param].subs[
                                                    key_sub_param
                                                ].remark.length < 25
                                            )
                                                this.errors.push(
                                                    this.parameters[key_param]
                                                        .subs[key_sub_param]
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
                                            ].name + " Failure reason required."
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
