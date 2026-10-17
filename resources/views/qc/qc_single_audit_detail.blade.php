@extends('layouts.app')

@section('sh-title')
Audit
@endsection

@section('sh-detail')
Detail
@endsection

@section('main')
<!--begin::Portlet-->

@if($audit_data->process_id == 217 || $audit_data->process_id == 218 )
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head" style="background-image: linear-gradient(to right, #845ec2, #906dc6, #9c7dc9, #a88ccd, #b39cd0);">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title kt-font-light">
                Basic Details
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <!-- <form class="kt-form kt-form--label-right"> -->
        {!! Form::open([
        'url' => 'qc/update_basic_audit_data',
        'class' => 'kt-form kt-form--label-right',
        'role' => 'form',
        'enctype' => 'multipart/form-data',
        'data-toggle' => 'validator',
        ]) !!}
        <input type="hidden" name="audit_id" value="{{ Crypt::encrypt($audit_data->id) }}">
        <div class="form-group row">
            <div class="col-lg-4">
                <label>Communication Instance ID(Call ID):</label>
                <input type="text" name="call_id" class="form-control" value="{{ $raw_data->call_id ?? '-' }}">
            </div>
            <div class="col-lg-4">
                <label>Client:</label>
                <input type="text" class="form-control" readonly="readonly" value="{{ $raw_data->client->name }}">
            </div>
            <div class="col-lg-4">
                <label>Partner:</label>
                <input type="text" class="form-control" readonly="readonly" value="{{ $raw_data->partner->name }}">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-4">
                <label>Audit Date:</label>
                <input type="text" class="form-control" id="kt_datetimepicker_audit_component" readonly placeholder="Select date & time" required="required" value="{{ $audit_data->audit_date }}" name="audit_date" />
            </div>
            <div class="col-lg-4">
                <label>SR Month</label>
                <input type="text" class="form-control" name="month" value="{{ $raw_data->month }}">
            </div>
            <div class="col-lg-4">
                <label>CaseNumber</label>
                <input type="text" class="form-control" name="case_number" value="{{ $raw_data->case_number }}">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-4">
                <label>Case ID:</label>
                <input type="text" class="form-control" name="case_id" value="{{ $raw_data->case_id }}">
            </div>
            <div class="col-lg-4">
                <label>LAN No</label>
                <input type="text" class="form-control" name="reference_no" value="{{ $raw_data->reference_no }}">
            </div>
            <div class="col-lg-4">
                <label>Opened Date:</label>
                <input type="text" class="form-control" value="{{ $raw_data->opened_date }}" readonly="readonly" />
            </div>
            <div class="col-lg-4">
                <label>Close Date:</label>
                <input type="text" class="form-control" value="{{ $raw_data->close_date }}" />
            </div>


            <div class="col-lg-4">
                <label>Acceptance Status</label>
                <select name="revisit_compliance" class="form-control">
                    <option value="">-- Select an option --</option>
                    <option value="Yes" {{ strtolower($audit_data->scenerio ?? '') === 'yes' ? 'selected' : '' }}>
                        Yes</option>
                    <option value="No" {{ strtolower($audit_data->scenerio ?? '') === 'no' ? 'selected' : '' }}>
                        No</option>

                </select>
            </div>

            <div class="col-lg-4">
                <label>Recovery Required</label>
                <select name="revisit_compliance" class="form-control">
                    <option value="">-- Select an option --</option>
                    <option value="Yes" {{ $audit_data->scenerio_code ?? '' === 'Yes' ? 'selected' : '' }}>
                        Yes</option>
                    <option value="NO" {{ $audit_data->scenerio_code ?? '' === 'NO' ? 'selected' : '' }}>
                    <option value="Dupliacte" {{ $audit_data->scenerio_code ?? '' === 'Dupliacte' ? 'selected' : '' }}>
                        No</option>

                </select>
            </div>


            <div class="col-lg-4">
                <label>Recovery Required</label>
                <input type="text" class="form-control" value="{{ $raw_data->close_date }}" />
            </div>


        </div>
        <div class="form-group row">
            <div class="col-lg-4">
                <label>Case Origin</label>
                <input type="text" class="form-control" name="case_origin" value="{{ $raw_data->case_origin }}">
            </div>
            <div class="col-lg-4">
                <label>Call Type:</label>
                <input type="text" class="form-control" name="call_type" value="{{ $raw_data->call_type }}">
            </div>
            <div class="col-lg-4">
                <label>Call Sub Type:</label>
                <input type="text" class="form-control" name="call_sub_type" value="{{ $raw_data->call_sub_type }}">
            </div>



        </div>
        <div class="form-group row">
            <div class="col-lg-4">
                <label>Case Reason</label>
                <input type="text" class="form-control" value="{{ $raw_data->case_reason }}" name="case_reason">
            </div>
            <div class="col-lg-4">
                <label>Solution/Disposition</label>
                <input type="text" class="form-control" value="{{ $raw_data->solution }}" name="solution">
            </div>
            <div class="col-lg-4">
                <label>Username</label>
                <input type="text" class="form-control" value="{{ $raw_data->user_name }}" name="user_name">
            </div>


        </div>
        <div class="form-group row">
            <div class="col-lg-4">
                <label>Current Assigned Queue:</label>
                <input type="text" class="form-control" value="{{ $raw_data->current_assigned_queue }}" name="current_assigned_queue">
            </div>
            <div class="col-lg-4">
                <label>Status</label>
                <input type="text" class="form-control" value="{{ $raw_data->case_status }}" name="case_status">
            </div>
            <div class="col-lg-4">
                <label>Customer_Redressal_Decision</label>
                <input type="text" class="form-control" value="{{ $raw_data->solution_based_category_c }}" name="solution_based_category_c">
            </div>

        </div>


        <div class="form-group row">
            <div class="col-lg-4">
                <label>Predictive RBI complaint</label>
                <input type="text" class="form-control" value="{{ $raw_data->event_c }}" name="event_c">
            </div>

            <div class="col-lg-4">
                <label>Customer voc</label>
                <input type="text" class="form-control" value="{{ $audit_data->customer_voice }}" name="customer_voice">
            </div>

            <div class="col-lg-4">
                <label>Predictive sentiment</label>
                <input type="text" class="form-control" value="{{ $raw_data->solution }}" name="solution">
            </div>


        </div>

        <div class="form-group row">
            <div class="col-lg-4">
                <label>Error Type</label>
                <input type="text" class="form-control" value="{{ $raw_data->error_reason_type }}" name="error_reason_type">
            </div>

            <div class="col-lg-4">
                <label>Overall Call Summary*:</label>
                <textarea class="form-control" name="overall_summary" required>{{ $audit_data->overall_summary }}</textarea>
            </div>
            <div class="col-lg-4">
                <label>Corrective Actions*:</label>
                <textarea class="form-control" name="issues">{{ $raw_data->issues }}</textarea>
            </div>
            <div class="col-lg-4">
                <label>Preventive Action:</label>
                <textarea class="form-control" name="sub_issues">{{ $raw_data->sub_issues }}</textarea>
            </div>

            <div class="col-lg-4">
                <label>recording link</label>
                <textarea class="form-control" name="recording_link">{{ $audit_data->recording_link }}</textarea>
            </div>
        </div>






        <div class="form-group row">
            <div class="col-lg-4">
                <label>Call File:</label>
                <audio controls>
                    <source src="{{ $url }}" type="audio/ogg">
                    <source src="{{ $url }}" type="audio/mpeg">
                    Your browser does not support the audio tag.
                </audio>
            </div>
            <div class="col-lg-4">
                <label>Upload Call file:</label>
                <input type="file" name="good_bad_call_file">
            </div>

        </div>

        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <button type="submit" class="btn btn-success">Update Basic Data</button>
                <button type="reset" class="btn btn-secondary">Cancel</button>
            </div>
        </div>

        </form>
    </div>
</div>

@else
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head" style="background-image: linear-gradient(to right, #845ec2, #906dc6, #9c7dc9, #a88ccd, #b39cd0);">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title kt-font-light">
                Basic Details
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <!-- <form class="kt-form kt-form--label-right"> -->
        {!! Form::open([
        'url' => 'qc/update_basic_audit_data',
        'class' => 'kt-form kt-form--label-right',
        'role' => 'form',
        'enctype' => 'multipart/form-data',
        'data-toggle' => 'validator',
        ]) !!}
        <input type="hidden" name="audit_id" value="{{ Crypt::encrypt($audit_data->id) }}">
        <div class="form-group row">
            <div class="col-lg-4">
                <label>Communication Instance ID(Call ID):</label>
                <input type="text" name="call_id" class="form-control" value="{{ $raw_data->call_id ?? '-' }}">
            </div>
            <div class="col-lg-4">
                <label>Client:</label>
                <input type="text" class="form-control" readonly="readonly" value="{{ $raw_data->client->name }}">
            </div>
            <div class="col-lg-4">
                <label>Partner:</label>
                <input type="text" class="form-control" readonly="readonly" value="{{ $raw_data->partner->name }}">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-4">
                <label>Audit Date:</label>
                <input type="text" class="form-control" id="kt_datetimepicker_audit_component" readonly placeholder="Select date & time" required="required" value="{{ $audit_data->audit_date }}" name="audit_date" />
            </div>
            <div class="col-lg-4">
                <label>Agent Name:</label>
                <input type="text" class="form-control" readonly="readonly" value="{{ $raw_data->agent_name }}">
            </div>
            <div class="col-lg-4">
                <label>TL Name:</label>
                <input type="text" class="form-control" readonly="readonly" value="{{ $raw_data->tl }}">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-4">
                <label>QA / QTL Name:</label>
                <input type="text" class="form-control" readonly="readonly" value="{{ $audit_data->qa_qtl_detail->name }}">
            </div>
            <div class="col-lg-4">
                <label>Call Type:</label>
                <input type="text" class="form-control" readonly="readonly" value="{{ $raw_data->call_type }}">
            </div>
            <div class="col-lg-4">
                <label>Call Sub Type:</label>
                <input type="text" class="form-control" readonly="readonly" value="{{ $raw_data->call_sub_type }}">
            </div>

        </div>
        <div class="form-group row">
            <div class="col-lg-4">
                <label>Disposition:</label>
                <input type="text" class="form-control" value="{{ $raw_data->disposition }}" name="disposition">
            </div>
            <div class="col-lg-4">
                <label>Customer Name*:</label>
                <input type="text" class="form-control" name="customer_name" value="{{ $raw_data->customer_name }}" required>
            </div>
            <div class="col-lg-4">
                <label>Cusotmer contact number*:</label>
                <input type="text" class="form-control" name="phone_number" value="{{ optional($raw_data)->phone_number ?? '-' }}" required>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-4">
                <label>QRC 1:</label>
                <input type="text" class="form-control" readonly="readonly" value="{{ $raw_data->qrc_1 }}">
            </div>
            <div class="col-lg-4">
                <label>QRC 2*:</label>
                {{ Form::select('qrc_2', ['Query' => 'Query', 'Request' => 'Request', 'Complaint' => 'Complaint'], $audit_data->qrc_2, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
            <div class="col-lg-4">
                <label>Language 1:</label>
                <input type="text" class="form-control" readonly="readonly" value="{{ $raw_data->language_1 }}">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-4">
                <label>Call Time:</label>
                <input type="text" class="form-control" value="{{ $raw_data->call_time }}" name="call_time">
            </div>
            <div class="col-lg-4">
                <label>Call Duration:</label>
                <input type="text" class="form-control" value="{{ $raw_data->call_duration }}" name="call_duration">
            </div>
            <div class="col-lg-4">
                <label>Refrence Number:</label>
                <input type="text" class="form-control" value="{{ $audit_data->refrence_number }}" name="refrence_number">
            </div>
        </div>


        @if($audit_data->process_id == 262 || $audit_data->process_id == 272)

        <div class="form-group row">

            {{-- Impact --}}
            <div class="col-lg-4">
                <label>Impact</label>
                <select class="form-control" name="l2_ticket_disposition">
                    <option value="">Select one!</option>
                    <option value="BFL" {{ $audit_data->l2_ticket_disposition == 'BFL' ? 'selected' : '' }}>BFL</option>
                    <option value="Customer" {{ $audit_data->l2_ticket_disposition == 'Customer' ? 'selected' : '' }}>Customer</option>
                    <option value="Both" {{ $audit_data->l2_ticket_disposition == 'Both' ? 'selected' : '' }}>Both</option>
                    <option value="NA" {{ $audit_data->l2_ticket_disposition == 'NA' ? 'selected' : '' }}>NA</option>
                </select>
            </div>

            {{-- Case Reason Status --}}
            <div class="col-lg-4">
                <label>Case Reason Status</label>
                <select class="form-control" name="case_dropdown" id="case_dropdown">
                    <option value="">Select one!</option>
                    <option value="Correct" {{ $audit_data->case_dropdown == 'Correct' ? 'selected' : '' }}>Correct</option>
                    <option value="Incorrect" {{ $audit_data->case_dropdown == 'Incorrect' ? 'selected' : '' }}>Incorrect</option>
                    <option value="NA" {{ $audit_data->case_dropdown == 'NA' ? 'selected' : '' }}>NA</option>
                </select>
            </div>

            {{-- Incorrect Reason --}}
            <div class="col-lg-4" id="incorrect_reason_div"
                style="{{ $audit_data->case_dropdown == 'Incorrect' ? '' : 'display:none;' }}">
                <label>If Incorrect (Reasons)</label>

                <select class="form-control" name="case_reason_if_incorrect">

                    <option value="">Select one!</option>

                    @if($audit_data->process_id == 262)

                    @php
                    $caseReasons = [
                    "Excess EMI refund",
                    "Excess amount paid or deducted",
                    "Waiver of penal/overdue charges",
                    "Refund of bounce charges charged by customer bank",
                    "Refund of bounce charges charged by BFL",
                    "Advance EMI Payment Related",
                    "Bounce/ LPP charges Information requested",
                    "LAN details not provided by the customer",
                    "Bounce memo requested",
                    "EMI Clearance status-CIP Period",
                    "Customer wants to change the Bank Account",
                    "Payment by customer",
                    "Refund Status confirmation",
                    "Mandate verification charges information",
                    "ECS Return Charges related information",
                    "Loan Close or cancellation during CIP",
                    "EMI Payment Receipt confirmation",
                    "EMI Deduction Status",
                    "Mandate cancellation request",
                    "Payment towards foreclosure",
                    "Charges against loan clarification Requested",
                    "ME EDC - Non Enterprise Merchant",
                    "Refund other fees / charges",
                    "Part payment the loan",
                    "Mandate swapping process information given",
                    "Duplicate Case",
                    "E- Mandate registration",
                    "Overdue Payment confirmation",
                    "EMI amount not deducted",
                    "Foreclose the loan",
                    "Excess EMI refund - loan closed/cancelled in CIP",
                    "Broken period interest refund clarification",
                    "Transaction Failed",
                    "Outstanding payment details required",
                    "Payment Failure",
                    "EMI cleared from bank A/c but SoA not updated",
                    "Excess amount knockoff against EMI",
                    "Excess cross knockoff against EMI Amount",
                    "Foreclosure charges waiver request",
                    "General enquiry",
                    "Forward To Other Queue",
                    "Card unblock request",
                    "Excess amount cross knockoff against EMI Amount by Debt Management Services",
                    "Disbursement payment not received",
                    "EMI Debited twice by Customer Bank",
                    "Flexi Loan Drawdown bank change",
                    "Ecomm Convenience Fee related",
                    "Differential Interest Related",
                    "Excess knock off against EMI and Penal/OD",
                    "Excess EMI amount knockoff reversal",
                    "New loan application status information",
                    "No Dues Certificate (NDC) requested",
                    "Change EMI requested after part pre payment",
                    "Change tenure requested after part pre payment",
                    "EMI Payment status update request",
                    "Refund interest due to disbursement delay",
                    "Repayment Schedule requested",
                    "Customer requested for reduction in loan tenure",
                    "Statement of Account (SOA) requested",
                    "Loan related information requested",
                    "Prime - Other Bajaj Prime membership query",
                    "Prime -Unable to redeem Bajaj Coins",
                    "Query not specified",
                    "CD General Enquiry",
                    "EMI amount details",
                    "AMC charges clarification",
                    "Flexi Draw down related",
                    "New Loan Related",
                    "Request Received from Unregistered Email id",
                    "ME - Junk/Test Email",
                    "Refund request - Stale/Return chq",
                    "UPI- Request to revoke Auto-pay",
                    "NOC Queries",
                    "EMI Card Related",
                    "Insurance product details requested",
                    "Excess cross knockoff against Penal / OD",
                    "Loan features /details",
                    "REMI Convenience Fee related",
                    "EMI / Loan Related Query",
                    "Insignificant Email ? Action not Required",
                    "Foreclosure Letter requested",
                    "Refund of mandate verification charges charged by customer bank",
                    "TDS Refund/Process information requested",
                    "TDS Refund Request",
                    "Advance EMI Receipt clarification",
                    "EMI Charges clarification",
                    "Settlement removal request",
                    "Demog Email ID change or correction Req",
                    "Interest Certificate requested"
                    ];
                    @endphp

                    @else

                    @php
                    $caseReasons = [
                    'Account Information Clarification',
                    'Demographic Details Updated',
                    'DPD reported in credit bureau',
                    'Duplicate Case',
                    'Enquiry Reported in Bureau',
                    'Insignificant Email – Action not Required',
                    'Insignificant Email ? Action not Required',
                    'Loan details to be updated in bureau',
                    'Loan Status to be updated in bureau',
                    'Loan taken by customer not reflecting in bureau',
                    'Settlement removal request',
                    'Settlement tagging removal & CIBIL correction',
                    'Write off removal request'
                    ];
                    @endphp

                    @endif

                    @foreach($caseReasons as $reason)
                    <option value="{{ $reason }}"
                        {{ $audit_data->case_reason_if_incorrect == $reason ? 'selected' : '' }}>
                        {{ $reason }}
                    </option>
                    @endforeach

                </select>
            </div>

        </div>

        <div class="form-group row">

            <div class="col-lg-4">
                <label>Corrective Action Taken</label>
                <select class="form-control" name="sfdc_disposition">
                    <option value="">Select one!</option>
                    <option value="NO" {{ $audit_data->sfdc_disposition=='NO'?'selected':'' }}>NO</option>
                    <option value="Yes" {{ $audit_data->sfdc_disposition=='Yes'?'selected':'' }}>Yes</option>
                    <option value="NA" {{ $audit_data->sfdc_disposition=='NA'?'selected':'' }}>NA</option>
                </select>
            </div>

            <div class="col-lg-4">
                <label>Service Recovery</label>
                <select class="form-control" name="hot_leads">
                    <option value="">Select one!</option>
                    <option value="NO" {{ $audit_data->hot_leads=='NO'?'selected':'' }}>NO</option>
                    <option value="Yes" {{ $audit_data->hot_leads=='Yes'?'selected':'' }}>Yes</option>
                    <option value="NA" {{ $audit_data->hot_leads=='NA'?'selected':'' }}>NA</option>
                </select>
            </div>

            <div class="col-lg-4">
                <label>Outcall Status</label>
                <select class="form-control" name="caller_type">
                    <option value="">Select one!</option>
                    <option value="Connected" {{ $audit_data->caller_type=='Connected'?'selected':'' }}>Connected</option>
                    <option value="Not Connected" {{ $audit_data->caller_type=='Not Connected'?'selected':'' }}>Not Connected</option>
                    <option value="Not Attempted" {{ $audit_data->caller_type=='Not Attempted'?'selected':'' }}>Not Attempted</option>
                    <option value="NA" {{ $audit_data->caller_type=='NA'?'selected':'' }}>NA</option>
                </select>
            </div>

        </div>

        <div class="form-group row">

            <div class="col-lg-4">
                <label>UUID Captured in Comments</label>
                <select class="form-control" name="scanerio">
                    <option value="">Select one!</option>
                    <option value="NO" {{ $audit_data->scanerio=='NO'?'selected':'' }}>NO</option>
                    <option value="Yes" {{ $audit_data->scanerio=='Yes'?'selected':'' }}>Yes</option>
                    <option value="NA" {{ $audit_data->scanerio=='NA'?'selected':'' }}>NA</option>
                </select>
            </div>

            <div class="col-lg-4">
                <label>Allegation Category</label>
                <select class="form-control" name="scanerio_codes">
                    <option value="">Select one!</option>
                    <option value="Accepted" {{ $audit_data->scanerio_codes=='Accepted'?'selected':'' }}>Accepted</option>
                    <option value="Not Accepted" {{ $audit_data->scanerio_codes=='Not Accepted'?'selected':'' }}>Not Accepted</option>
                    <option value="NA" {{ $audit_data->scanerio_codes=='NA'?'selected':'' }}>NA</option>
                </select>
            </div>

            @if($audit_data->process_id == 262)
            <div class="col-lg-4">
                <label>Transaction Execution</label>
                <select class="form-control" name="call_disposition">
                    <option value="">Select one!</option>
                    <option value="Correct" {{ $audit_data->call_disposition=='Correct'?'selected':'' }}>Correct</option>
                    <option value="In-Correct" {{ $audit_data->call_disposition=='In-Correct'?'selected':'' }}>In-Correct</option>
                    <option value="NA" {{ $audit_data->call_disposition=='NA'?'selected':'' }}>NA</option>
                </select>
            </div>
            @endif

        </div>

        @endif


        <div class="form-group row">
            <div class="col-lg-4">
                <label>Language 2*:</label>
                <input type="text" class="form-control" name="language_2" required value="{{ $audit_data->language_2 }}">
            </div>
            <div class="col-lg-4">
                <label>Is Call Fatal:</label>
                @if ($audit_data->is_critical)
                <input type="text" class="form-control is-invalid" id="inputWarning1" readonly="readonly" value="YES" />
                @else
                <input type="text" class="form-control is-valid" id="inputSuccess1" readonly="readonly" value="NO" />
                @endif
            </div>
            <div class="col-lg-4">
                <label>Overall Score:</label>
                @if ($audit_data->is_critical)
                <input type="text" class="form-control is-invalid" id="inputWarning1" readonly="readonly" value="{{ $audit_data->overall_score }}" />
                @else
                <input type="text" class="form-control is-valid" id="inputSuccess1" readonly="readonly" value="{{ $audit_data->overall_score }}" />
                @endif

            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-4">
                <label>Case ID*:</label>
                <input type="text" class="form-control" name="case_id" value="{{ $audit_data->case_id }}" required>
            </div>
            <div class="col-lg-4">
                <label>Overall Call Summary*:</label>
                <textarea class="form-control" name="overall_summary" required>{{ $audit_data->overall_summary }}</textarea>
            </div>
            <div class="col-lg-4">
                <label>Feedback to Agent*:</label>
                <textarea class="form-control" name="feedback">{{ $audit_data->feedback }}</textarea>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-4">
                <label>Call File:</label>
                <audio controls>
                    <source src="{{ $url }}" type="audio/ogg">
                    <source src="{{ $url }}" type="audio/mpeg">
                    Your browser does not support the audio tag.
                </audio>
            </div>
            <div class="col-lg-4">
                <label>Upload Call file:</label>
                <input type="file" name="good_bad_call_file">
            </div>
            <div class="col-lg-4">
                <label>Scanerio Code:</label>
                <input type="text" class="form-control" value="{{ $audit_data->error_code }}" name="error_code">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-4">
                <label>Vehicle Type:</label>
                <input type="text" class="form-control" value="{{ $audit_data->vehicle_type }}" name="vehicle_type">
            </div>
            <div class="col-lg-4">
                <label>Error Code:</label>
                <input type="text" class="form-control" value="{{ $audit_data->new_error_code }}" name="new_error_code">
            </div>
            <div class="col-lg-4">
                <label>Customer_Redressal_Decision:</label>
                <input type="text" class="form-control" value="{{ $audit_data->raw_data->solution_based_category_c }}" name="solution_based_category_c">
            </div>
        </div>
        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <button type="submit" class="btn btn-success">Update Basic Data</button>
                <button type="reset" class="btn btn-secondary">Cancel</button>
            </div>
        </div>

        </form>
    </div>
</div>

@endif



<!--end::Portlet-->

<!--begin::Portlet-->
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head" style="background-image: linear-gradient(to right, #845ec2, #906dc6, #9c7dc9, #a88ccd, #b39cd0);">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title kt-font-light">
                Audit Observation Details
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">

        <div class="row">
            <div class="col-md-2 kt-font-bolder">Behavior</div>
            <div class="col-md-10 kt-font-bolder">
                <div class="row">
                    <div class="col-md-2 kt-font-bolder">Parameter</div>
                    <div class="col-md-2 kt-font-bolder">Observation</div>
                    <div class="col-md-1 kt-font-bolder">Scored</div>
                    <div class="col-md-2 kt-font-bolder">Failure Type</div>
                    <div class="col-md-2 kt-font-bolder">Failure Reason</div>
                    <div class="col-md-2 kt-font-bolder">Remarks</div>
                    <div class="col-md-1 kt-font-bolder">Update</div>
                </div>
            </div>
        </div>
        <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg" id="seprator"></div>
        @foreach ($final_data as $key => $value)
        <div class="row" style="border-bottom:1px solid #ccc; padding: 20px 0; height: 100%; ">
            <div class="col-md-2 kt-font-bolder kt-font-primary flex-item">{{ $value['name'] }}</div>
            <div class="col-md-10 sp-row">
                @if(isset($value['sp']) && count($value['sp']) > 0)
                @foreach ($value['sp'] as $skey => $svalue)
                <div class="row flex-container">

                    <div class="col-md-2 kt-font-bold">
                        {{ $svalue['name'] }} <i class="la la-info-circle kt-font-warning sp-details-top" title="{{ $svalue['detail'] }}"></i>
                    </div>
                    <div class="col-md-2">
                        {{ $svalue['selected_option'] }}
                    </div>
                    <div class="col-md-1">
                        {{ $svalue['scored'] }}
                    </div>
                    <div class="col-md-2">
                        {{ $svalue['reason_type'] ? $svalue['reason_type'] : '-' }}
                    </div>
                    <div class="col-md-2">
                        {{ $svalue['reason'] ? $svalue['reason'] : '-' }}
                    </div>
                    <div class="col-md-2">
                        <p style="width: 100%; display: block; overflow-wrap: break-word;">
                            {{ $svalue['remark'] ? $svalue['remark'] : '-' }}
                        </p>
                    </div>
                    <div class="col-md-1">

                        <qc-sub-parameter-updater audit-id="{{ $audit_data->id }}" parameter-id="{{ $value['id'] }}" sub-parameter-id="{{ $svalue['id'] }}" rebuttal-id="0">
                        </qc-sub-parameter-updater>

                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
        @endforeach

    </div>
</div>

@if($audit_data->client_id == 23 )


<div class="row">

    <div class="col-md-12">
        <!--begin::Portlet-->
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head" style="background-image: linear-gradient(to right, #845ec2, #906dc6, #9c7dc9, #a88ccd, #b39cd0);">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title kt-font-light">
                        Update QC Status
                    </h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {!! Form::open([
                    'url' => 'qc/update_aht',
                    'class' => 'kt-form',
                    'role' => 'form',
                    'accept-charset' => 'UTF-8',
                    'enctype' => 'multipart/form-data',
                    'data-toggle' => 'validator',
                    ]) !!}
                    <input type="hidden" name="audit_id" value="{{ Crypt::encrypt($audit_data->id) }}">

                    <div class="kt-portlet__body">
                        <div class="col-12">
                            @foreach($aht_data as $index => $aht)
                            <div class="mb-3 d-flex align-items-center">
                                <input type="hidden" name="ahts[{{ $index }}][id]" value="{{ $aht->id }}" />
                                <input type="text" name="ahts[{{ $index }}][aht_time]" value="{{ $aht->aht_time }}" class="form-control mr-2" placeholder="HH:MM:SS" />
                                <input type="text" name="ahts[{{ $index }}][call_id]" value="{{ $aht->call_id }}" class="form-control mx-2" placeholder="Call ID" />
                                <button type="button" class="btn btn-danger" onclick="removeAHT(this)">
                                    Remove
                                </button>
                            </div>
                            @endforeach

                            <!-- Container for dynamically added AHT entries -->
                            <div id="new-aht-container"></div>

                            <!-- Button to add new AHT entries -->
                            <button type="button" id="add-aht-btn" class="btn btn-primary mt-3">
                                Add AHT
                            </button>
                        </div>
                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary">Update AHT</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endif

<div class="row">

    <div class="col-md-12">
        <!--begin::Portlet-->
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head" style="background-image: linear-gradient(to right, #845ec2, #906dc6, #9c7dc9, #a88ccd, #b39cd0);">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title kt-font-light">
                        Update QC Status
                    </h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {!! Form::open([
                    'url' => 'qc/update_qc_status',
                    'class' => 'kt-form',
                    'role' => 'form',
                    'accept-charset' => 'UTF-8',
                    'enctype' => 'multipart/form-data',
                    'data-toggle' => 'validator',
                    ]) !!}
                    <input type="hidden" name="audit_id" value="{{ Crypt::encrypt($audit_data->id) }}">

                    <div class="kt-portlet__body">

                        <div class="form-group">
                            <label>Status*</label>
                            {{ Form::select('qc_status', [1 => 'Pass with edit', 'Pass', 'Failed'], $audit_data->qc_status, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Select one!']) }}
                        </div>
                        <div class="form-group">
                            <label>Feedback Comment</label>
                            <textarea class="form-control" name="qc_comment">{{ $audit_data->qc_comment }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Feedback to QA</label>
                            <textarea class="form-control" required name="qa_feedback">{{ $audit_data->qc_to_qa_feedback }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Artifact</label>
                            <input type="file" class="form-control" name="qc_feedback_file">

                        </div>

                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary">Update Status</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                        </div>
                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('css')
<style type="text/css">
    .sp-details-top {
        cursor: pointer;

    }

    .flex-container {
        display: flex;
        align-items: center;

    }

    .sp-row .row {
        margin-bottom: 15px;

    }

    #seprator {
        margin: 2.5rem 0 0 0;
    }

    @media screen and (min-width: 480px) {
        .sp-row .row .col-md-2 {
            margin-top: 15px;
        }
    }
</style>
@endsection
@section('js')
@include('shared.form_js')
<script type="text/javascript">
    window.scrollTo(0, document.body.scrollHeight);
</script>
<script>
    let ahtIndex = {{ count($aht_data) }};
    document.getElementById('add-aht-btn').addEventListener('click', function() {
        let container = document.getElementById('new-aht-container');

        let newAHT = `
                <div class="mb-3 d-flex align-items-center">
                    <input type="hidden" name="ahts[${ahtIndex}][id]" value="" />
                    <input
                        type="text"
                        name="ahts[${ahtIndex}][aht_time]"
                        class="form-control mr-2"
                        placeholder="HH:MM:SS"
                    />
                    <input
                        type="text"
                        name="ahts[${ahtIndex}][call_id]"
                        class="form-control mx-2"
                        placeholder="Call ID"
                    />
                    <button type="button" class="btn btn-danger" onclick="removeAHT(this)">
                        Remove
                    </button>
                </div>
            `;

        container.insertAdjacentHTML('beforeend', newAHT);
        ahtIndex++;
    });

    function removeAHT(element) {
        element.parentElement.remove();
    }
</script>
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datetimepicker.js" type="text/javascript"></script>
@endsection