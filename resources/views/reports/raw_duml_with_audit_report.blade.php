@extends('layouts.app')

@section('sh-title')
Report
@endsection

@section('sh-detail')
Raw dump with Audit data
@endsection

@section('main')
<div class="row">
    <div class="col-md-12">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Filter
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <form>
                    <raw-dump-filter-component></raw-dump-filter-component>
                    <button type="Submit" class="btn btn-success">Submit</button>
                </form>
                <a onclick="show()"><button class="btn btn-primary float-right"> Show Requested Reports</button></a>
                <p style="color:red">{{ $msg }}</p>
            </div>
        </div>
    </div>
</div>
<div class="kt-portlet kt-portlet--mobile" id="requested" style="display:none">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Requested Reports
            </h3><br>

        </div>
    </div>
    <div class="kt-portlet__body">
        <p> Reports requested in morning will be available at 2PM.</p>
        <p> Reports requested after 1PM will be availabe next day morning.</p>

        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable">
            <thead>
                <tr>
                    <th>#</th>

                    <th>Requested Date</th>
                    <th>Process</th>
                    <th>Date Range</th>
                    <th>Download</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($old_reports as $report)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $report->created_at }}</td>
                    <td>{{ $report->name }}</td>
                    <td>{{ $report->filter_start_date }} to {{ $report->filter_end_date }}</td>
                    <td>
                        @if ($report->file_location)
                        <?php
                        $path_name = str_replace('https://qmtool.s3.ap-south-1.amazonaws.com/', '', $report->file_location);
                        $url = Storage::disk('s3')->temporaryUrl(
                            $path_name,
                            now()->addMinutes(3000), //Minutes for which the signature will stay valid
                        );
                        ?>
                        <a href="{{ $url }}">Download Report</a>
                        @else
                        Pending
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Details
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">

        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_2">
            @if ($processs == 145 || $processs == 156 || $processs == 171 || $processs == 200 || $processs == 204 ||$processs == 216 || $processs == 217 ||$processs == 218)

            @if ($processs == 171)
            <thead>
                <tr>
                    <th>Sr. No</th>
                    <th>Audit Date</th>
                    <th>Auditor Name</th>
                    <th>Location</th>
                    <th>Call Id/CaseNumber</th>
                    <th>Case Origin</th>
                    <th>Current Assigned Queue</th>
                    <th>STATUS</th>
                    <th>Date/Time Opened</th>
                    <th>Current Assigned Queue Datetime</th>
                    <th>Date/Time Closed</th>
                    <th>Last Modified By: Full Name</th>
                    <th>Type </th>
                    <th>Relationship Number</th>
                    <th>Case Reason</th>
                    <th>Solution: Case Disposition Name</th>
                    <th>Agent Name</th>
                    <th>Agent ID</th>
                    <th>LOB</th>
                    <th>DOJ</th>
                    <th>Call Duration</th>
                    <th>Refrence No</th>
                    <th>Customer Name</th>



                    @foreach ($repeater_param_data as $kk => $vv)
                    @foreach ($vv as $kkb => $vvb)
                    <th>{{ $vvb['name'] }}</th>
                    <th>Scored</th>
                    <th>Scorable</th>
                    <th>Reason Type</th>
                    <th>Reason</th>
                    <th>Remark</th>
                    @endforeach
                    @endforeach

                    <th>Total Scored</th>
                    <th>Total Scorable</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $kk => $vv)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $vv['audit_date'] }}</td>
                    <td>{{ $vv['auditor'] }}</td>
                    <td>{{ $vv['location'] }}</td>
                    <td>{{ $vv['call_id'] }}</td>
                    <td>{{ $vv['case_origin'] ?? 'NA' }}</td>
                    <td>{{ $vv['current_assigned_queue'] ?? 'NA' }}</td>
                    <td>{{ $vv['case_status'] ?? 'NA' }}</td>
                    <td>{{ $vv['temp_raw_data']->opened_date ?? 'NA' }}</td>
                    <td>{{ $vv['case_create_date'] ?? 'NA' }}</td>
                    <td>{{ $vv['close_date'] ?? 'NA' }}</td>
                    <td>{{ $vv['queue_name_c'] ?? 'NA' }}</td>
                    <td>{{ $vv['call_type'] ?? 'NA' }}</td>
                    <td>{{ $vv['relationship_number'] ?? 'NA' }}</td>
                    <td>{{ $vv['case_reason'] ?? 'NA' }}</td>
                    <td>{{ $vv['solution'] ?? 'NA' }}</td>
                    <td>{{ $vv['agent_name'] }}</td>
                    <td>{{ $vv['temp_raw_data']->agent_id ?? 'NA' }}</td>
                    <td>{{ $vv['temp_raw_data']->lob }}</td>
                    <td>{{ $vv['temp_raw_data']->doj }}</td>
                    <td>{{ $vv['temp_raw_data']->call_duration }}</td>
                    <td>{{ $vv['refrence_number'] }}</td>
                    <td>{{ $vv['temp_raw_data']->customer_name }}</td>



                    <?php
                    $total_scored = 0;
                    $total_scorable = 0;
                    $is_critical = 0; ?>
                    @foreach ($vv['audit'] as $kk => $vv)
                    @foreach ($vv as $kkb => $vvb)
                    <?php if ($vvb['observation'] == 'Critical') {
                        $is_critical = 1;
                    } ?>
                    <td>{{ $vvb['observation'] }}</td>
                    <td>{{ $vvb['scored'] }}</td>
                    <td>{{ $vvb['scorable'] }}</td>
                    <td>
                        {{ $vvb['reason_type'] == 0 ? '' : $vvb['reason_type'] }}
                    </td>

                    <?php
                    $string = str_replace(' ', '-', $vvb['reason']); // Replaces all spaces with hyphens.
                    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
                    $string = preg_replace('/-+/', ' ', $string);
                    ?>

                    <td>{{ strip_tags($string) }}</td>



                    <?php
                    $string = str_replace(' ', '-', $vvb['remark']); // Replaces all spaces with hyphens.
                    $string = preg_replace('/[^,"":().A-Za-z0-9-\/-]/', '', $string);
                    $string = preg_replace('/-+/', ' ', $string);
                    ?>

                    <td>{{ strip_tags($string) }}</td>



                    <?php
                    $total_scored += $vvb['scored'];
                    $total_scorable += $vvb['scorable']; ?>
                    @endforeach
                    @endforeach
                    <td><?php if ($is_critical == 1) {
                            echo 0;
                        } else {
                            if ($total_scored >= $total_scorable) {
                                echo $total_scorable;
                            } else {
                                echo $total_scored;
                            }
                        } ?></td>
                    <td><?php if ($total_scorable > 100) {
                            echo 100;
                        } else {
                            echo $total_scorable;
                        } ?></td>
                </tr>
                @endforeach
            </tbody>




            @elseif($processs == 200)
            <thead>
                <tr>
                    <th>Sr. No</th>
                    <th>Audit Date</th>
                    <th>Auditor Name</th>
                    <th>Location</th>
                    <th>Call Id/CaseNumber</th>
                    <th>EMP ID</th>
                    <th>Audit Type</th>
                    <th>Call Start Date & Time</th>
                    <th>Call Duration</th>
                    <th>Name of FA</th>
                    <th>TL Name</th>
                    <th>Tenure</th>
                    <th>Call Type </th>
                    <th>Ops Manager</th>
                    <th>Language</th>
                    <th>Disposition (Selected by agent)</th>
                    <th>Disposition (Actual)</th>
                    <th>BFL Process</th>
                    <th>Customer ID</th>
                    <th>campaign_name</th>
                    <th>overall_summary</th>
                    <th>agent_feedback</th>
                    <th>Good/Bad Call</th>
                    <th>Recording Link</th>

                    @foreach ($repeater_param_data as $kk => $vv)
                    @foreach ($vv as $kkb => $vvb)
                    <th>{{ $vvb['name'] }}</th>
                    <th>Scored</th>
                    <th>Scorable</th>
                    <th>Reason Type</th>
                    <th>Reason</th>
                    <th>Remark</th>
                    @endforeach
                    @endforeach
                    <th>Total Scored</th>
                    <th>Total Scorable</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $kk => $vv)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $vv['audit_date'] }}</td>
                    <td>{{ $vv['auditor'] }}</td>
                    <td>{{ $vv['location'] }}</td>
                    <td>{{ $vv['call_id'] }}</td>
                    <td>{{ $vv['temp_raw_data']->emp_id ?? 'NA' }}</td>
                    <td>{{ $vv['audit_type'] ?? 'NA' }}</td>
                    <td>{{ $vv['temp_raw_data']->call_time }}</td>
                    <td>{{ $vv['temp_raw_data']->call_duration }}</td>
                    <td>{{ $vv['temp_raw_data']->sr_mgr }}</td>
                    <td>{{ $vv['temp_raw_data']->tl }}</td>
                    <td>{{ $vv['temp_raw_data']->month }}</td>
                    <td>{{ $vv['temp_raw_data']->call_type }}</td>
                    <td>{{ $vv['temp_raw_data']->user_name }}</td>
                    <td>{{ $vv['language_2'] }}</td>
                    <td>{{ $vv['temp_raw_data']->case_reason }}</td>
                    <td>{{ $vv['temp_raw_data']->disposition }}</td>
                    <td>{{ $vv['temp_raw_data']->bfl_process }}</td>
                    <td>{{ $vv['temp_raw_data']->customer_id }}</td>
                    <td>{{ $vv['temp_raw_data']->campaign_name }}</td>
                    <td>{{ $vv['overall_summary'] }}</td>
                    <td>{{ $vv['agent_feedback'] }}</td>
                    <td>
                        @if ($vv['good_bad_call'] == 1)
                        Good Call
                        @elseif ($vv['good_bad_call'] == 2)
                        Bad Call
                        @elseif ($vv['good_bad_call'] == 3)
                        Average Call
                        @else
                        {{-- Show nothing --}}
                        @endif
                    </td>
                    <td>{{ $vv['recording_link'] ?? 'NA' }}</td>
                    <?php
                    $total_scored = 0;
                    $total_scorable = 0;
                    $is_critical = 0; ?>
                    @foreach ($vv['audit'] as $kk => $vv)
                    @foreach ($vv as $kkb => $vvb)
                    <?php if ($vvb['observation'] == 'Critical') {
                        $is_critical = 1;
                    } ?>
                    <td>{{ $vvb['observation'] }}</td>
                    <td>{{ $vvb['scored'] }}</td>
                    <td>{{ $vvb['scorable'] }}</td>
                    <td>
                        {{ $vvb['reason_type'] == 0 ? '' : $vvb['reason_type'] }}
                    </td>

                    <?php
                    $string = str_replace(' ', '-', $vvb['reason']); // Replaces all spaces with hyphens.
                    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
                    $string = preg_replace('/-+/', ' ', $string);
                    ?>

                    <td>{{ strip_tags($string) }}</td>



                    <?php
                    $string = str_replace(' ', '-', $vvb['remark']); // Replaces all spaces with hyphens.
                    $string = preg_replace('/[^,"":().A-Za-z0-9-\/-]/', '', $string);
                    $string = preg_replace('/-+/', ' ', $string);
                    ?>

                    <td>{{ strip_tags($string) }}</td>



                    <?php
                    $total_scored += $vvb['scored'];
                    $total_scorable += $vvb['scorable']; ?>
                    @endforeach
                    @endforeach
                    <td><?php if ($is_critical == 1) {
                            echo 0;
                        } else {
                            if ($total_scored >= $total_scorable) {
                                echo $total_scorable;
                            } else {
                                echo $total_scored;
                            }
                        } ?></td>
                    <td><?php if ($total_scorable > 100) {
                            echo 100;
                        } else {
                            echo $total_scorable;
                        } ?></td>
                </tr>
                @endforeach
            </tbody>

            {{-- @elseif($processs == 216 || $processs == 217) --}}
            @elseif($processs == 216 )
            <thead>
                <tr>
                    <th>Sr. No</th>
                    <th>Audit Date</th>
                    <th>Auditor Name</th>
                    <th>Location</th>
                    <th>Branch Location</th>
                    <th>Call Id</th>
                    <th>B2C ID</th>
                    <th>Agent Emp Id:</th>
                    <th>Agent Name</th>
                    <th>Field Executive Name</th>
                    <th>Field Rejection Date and time</th>
                    <th>Field Remarks</th>
                    <th>Field Sub-remarks</th>
                    <th>Call Date and Time</th>
                    <th>Call Duration</th>
                    <th>Dialer Disposition:</th>
                    <th>Sub Disposition</th>
                    <th>LOB:</th>
                    <th>ACPT </th>
                    <th>ACPT L2 </th>
                    <th>ACPT Remarks</th>
                    <th>Agent Opportunity</th>
                    <th>Customer VOC</th>
                    <th>Login Possibility</th>
                    <th>Recording Link:</th>
                    <th>overall_summary</th>
                    <th>agent_feedback</th>
                    <th>Good/Bad Call</th>
                    <th>error Type</th>



                    @foreach ($repeater_param_data as $kk => $vv)
                    @foreach ($vv as $kkb => $vvb)
                    <th>{{ $vvb['name'] }}</th>
                    <th>Scored</th>
                    <th>Scorable</th>
                    <th>Reason Type</th>
                    <th>Reason</th>
                    <th>Remark</th>
                    @endforeach
                    @endforeach
                    <th>Total Scored</th>
                    <th>Total Scorable</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $kk => $vv)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $vv['audit_date'] }}</td>
                    <td>{{ $vv['auditor'] }}</td>
                    <td>{{ $vv['location'] }}</td>
                    <td>{{ $vv['temp_raw_data']->campaign_name }}</td>
                    <td>{{ $vv['call_id'] }}</td>
                    <td>{{ $vv['temp_raw_data']->customer_id }}</td>
                    <td>{{ $vv['temp_raw_data']->emp_id ?? 'NA' }}</td>
                    <td>{{ $vv['temp_raw_data']->agent_name ?? 'NA' }}</td>
                    <td>{{ $vv['temp_raw_data']->vendor_name ?? 'NA' }}</td>
                    <td>{{ $vv['temp_raw_data']->case_create_date ?? 'NA' }}</td>
                    <td>{{ $vv['temp_raw_data']->internal_case_reason_c ?? 'NA' }}</td>
                    <td>{{ $vv['temp_raw_data']->solution_based_category_c ?? 'NA' }}</td>
                    <td>{{ $vv['temp_raw_data']->call_time }}</td>
                    <td>{{ $vv['temp_raw_data']->call_duration }}</td>
                    <td>{{ $vv['temp_raw_data']->disposition }}</td>
                    <td>{{ $vv['sub_disposition'] }}</td>
                    <td>{{ $vv['temp_raw_data']->lob }}</td>
                    <td>{{ $vv['acpt'] }}</td>
                    <td>{{ $vv['acpt_l2']  ?? 'NA' }}</td>
                    <td>{{ $vv['temp_raw_data']->call_type }}</td>
                    <td>{{ $vv['focused_audit_remark'] }}</td>
                    <td>{{ $vv['case_comment'] }}</td>
                    <td>{{ $vv['call_stage'] }}</td>
                    <td>{{ $vv['recording_link'] ?? 'NA' }}</td>
                    <td>{{ $vv['overall_summary'] }}</td>
                    <td>{{ $vv['agent_feedback'] }}</td>
                    <td>
                        @if ($vv['good_bad_call'] == 1)
                        Good Call
                        @elseif ($vv['good_bad_call'] == 2)
                        Bad Call
                        @elseif ($vv['good_bad_call'] == 3)
                        Average Call
                        @else
                        {{-- Show nothing --}}
                        @endif
                    </td>
                    <td>{{ $vv['temp_raw_data']->call_type }}</td>
                    <?php
                    $total_scored = 0;
                    $total_scorable = 0;
                    $is_critical = 0; ?>
                    @foreach ($vv['audit'] as $kk => $vv)
                    @foreach ($vv as $kkb => $vvb)
                    <?php if ($vvb['observation'] == 'Critical') {
                        $is_critical = 1;
                    } ?>
                    <td>{{ $vvb['observation'] }}</td>
                    <td>{{ $vvb['scored'] }}</td>
                    <td>{{ $vvb['scorable'] }}</td>
                    <td>
                        {{ $vvb['reason_type'] == 0 ? '' : $vvb['reason_type'] }}
                    </td>

                    <?php
                    $string = str_replace(' ', '-', $vvb['reason']); // Replaces all spaces with hyphens.
                    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
                    $string = preg_replace('/-+/', ' ', $string);
                    ?>

                    <td>{{ strip_tags($string) }}</td>



                    <?php
                    $string = str_replace(' ', '-', $vvb['remark']); // Replaces all spaces with hyphens.
                    $string = preg_replace('/[^,"":().A-Za-z0-9-\/-]/', '', $string);
                    $string = preg_replace('/-+/', ' ', $string);
                    ?>

                    <td>{{ strip_tags($string) }}</td>



                    <?php
                    $total_scored += $vvb['scored'];
                    $total_scorable += $vvb['scorable']; ?>
                    @endforeach
                    @endforeach
                    <td><?php if ($is_critical == 1) {
                            echo 0;
                        } else {
                            if ($total_scored >= $total_scorable) {
                                echo $total_scorable;
                            } else {
                                echo $total_scored;
                            }
                        } ?></td>
                    <td><?php if ($total_scorable > 100) {
                            echo 100;
                        } else {
                            echo $total_scorable;
                        } ?></td>
                </tr>
                @endforeach
            </tbody>

            @elseif ($processs == 217 || $processs == 218)

            <thead>
                <tr>
                    <th>Sr. No</th>
                    <th>Audit Date</th>
                    <th>Auditor Name</th>
                    <th>Location</th>
                    <th>Call Id/CaseNumber</th>
                    <th>Case Id</th>
                    <th>SR Month</th>
                    <th>LAN No</th>
                    <th>Solution/Disposition</th>
                    <th>Date/Time Opened</th>
                    <th>Date/Time Closed</th>
                    <th>Case Origin</th>
                    <th>Type</th>
                    <th>Sub Type</th>
                    <th>Case Reason</th>
                    <th>User Name</th>
                    <th>Customer_Redressal_Decision</th>
                    <th>Current Assigned Queue</th>
                    <th>Partner Name</th>
                    <th>Status</th>
                    <th>Audit Type</th>
                    <th>Acceptance Status</th>
                    <th>Recovery Required</th>
                    <th>Corrective Action</th>
                    <th>Preventive Action</th>
                    <th>Overall Summary</th>
                    <th>Good/Bad Call</th>
                    <th>error Type</th>
                    <th>Customer Voc</th>
                    <th>Predictive sentiment</th>
                    <th>Predictive RBI Complaint</th>
                    <!-- <th>Overall Summary</th>
                    <th>Agent feedback</th> -->

                    @foreach ($repeater_param_data as $group)
                    @foreach ($group as $param)
                    <th>{{ $param['name'] }}</th>
                    <th>Scored</th>
                    <th>Scorable</th>
                    <th>Reason Type</th>
                    <th>Reason</th>
                    <th>Remark</th>
                    @endforeach
                    @endforeach

                    <th>Total Scored</th>
                    <th>Total Scorable</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($data as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['audit_date'] }}</td>
                    <td>{{ $row['auditor'] }}</td>
                    <td>{{ $row['location'] }}</td>
                    <td>{{ $row['call_id'] }}</td>
                    <td>{{ $row['temp_raw_data']->case_id ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->month ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->reference_no ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->disposition ?? 'NA'  }}</td>
                    <td>{{ $row['temp_raw_data']->opened_date ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->close_date ?? 'NA' }}</td>
                    <td>{{ $row['case_origin'] ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->call_type ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->call_sub_type ?? 'NA' }}</td>
                    <td>{{ $row['case_reason'] ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->user_name ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->solution_based_category_c ?? 'NA' }}</td>
                    <td>{{ $row['current_assigned_queue'] ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->partner_name ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_status ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->audit_type ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->scenerio ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->scenerio_code ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->issues ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->sub_issues ?? 'NA' }}</td>
                    <td>{{ $row['overall_summary'] ?? 'NA' }}</td>
                    <td>
                        @if ($row['good_bad_call'] == 1)
                        Good Call
                        @elseif ($row['good_bad_call'] == 2)
                        Bad Call
                        @elseif ($row['good_bad_call'] == 3)
                        Average Call
                        @else
                        {{-- Show nothing --}}
                        @endif
                    </td>
                    <td>{{ $row['temp_raw_data']->error_reason_type ?? 'NA' }}</td>
                    <td>{{ $row['customer_voice'] ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->solution ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->event_c ?? 'NA' }}</td>
                    <!-- <td>-</td>
                    <td>-</td> -->

                    @php
                    $total_scored = 0;
                    $total_scorable = 0;
                    $is_critical = 0;
                    @endphp

                    @foreach ($row['audit'] as $auditGroup)
                    @foreach ($auditGroup as $audit)
                    @php
                    if ($audit['observation'] == 'Critical') {
                    $is_critical = 1;
                    }

                    $reason = preg_replace('/[^A-Za-z0-9 ]/', '', $audit['reason']);
                    $remark = preg_replace('/[^,"":().A-Za-z0-9\/ -]/', '', $audit['remark']);

                    $total_scored += $audit['scored'];
                    $total_scorable += $audit['scorable'];
                    @endphp

                    <td>{{ $audit['observation'] }}</td>
                    <td>{{ $audit['scored'] }}</td>
                    <td>{{ $audit['scorable'] }}</td>
                    <td>{{ $audit['reason_type'] ?: '' }}</td>
                    <td>{{ $reason }}</td>
                    <td>{{ $remark }}</td>
                    @endforeach
                    @endforeach

                    <td>
                        {{ $is_critical ? 0 : min($total_scored, $total_scorable) }}
                    </td>
                    <td>
                        {{ $total_scorable > 100 ? 100 : $total_scorable }}
                    </td>

                </tr>
                @endforeach
            </tbody>


            @else
            <thead>
                <tr>
                    <th>Sr. No</th>
                    <th>Audit Date</th>
                    {{-- <th>Batch Id</th>
                            <th>Company Id</th> --}}
                    <th>Auditor Name</th>
                    <th>Agent Name</th>
                    <th>Agent Feedback</th>
                    {{-- <th>Agent ID</th> --}}
                    <th>Emp Id</th>
                    <th>Language</th>
                    <th>Call Time</th>
                    <th>Call Duration</th>
                    <th>lob</th>
                    <th>ACPT</th>
                    <th>Recording Link:</th>
                    <th>SLA Manager</th>
                    <th>Event C</th>
                    <th>Case Id</th>
                    <th>Overall Summary</th>
                    <th>Location</th>
                    <th>Partner Name</th>
                    <th>Case Create Date</th>
                    <th>Disbursment Date</th>
                    <th>Difference</th>
                    <th>Close Date</th>
                    <th>Call Id</th>
                    <th>Relationship Number</th>
                    <th>Type C</th>
                    <th>Sub Type</th>
                    <th>Category</th>
                    <th>Solution Based Category C</th>
                    <th>Internal Case Reason C</th>
                    <th>Disposition</th>
                    <th>SUB-Disposition</th>
                    <th>Queue Name C</th>
                    <th>Month</th>
                    <th>Week</th>
                    <th>Hrs Tat Bucket</th>
                    <th>User Name</th>
                    <th>Case Origin</th>
                    <th>Product Id</th>
                    <th>Product Business Family</th>
                    <th>Fpr</th>
                    <th>Case Origin Channel</th>
                    <th>Channel Master</th>
                    <th>OWNER</th>
                    <th>CURRENT_ASSIGNED_QUEUE_WHEN_CLOSED__C</th>
                    <th>VERTICAL</th>
                    <th>CASE_REASON_WHEN_CASE_CLOSED</th>
                    <th>STATUS</th>
                    <th>Customer Name</th>
                    <th>bot_disposition_as_per_logic</th>
                    <th> Compaign Name</th>
                    <th> Focused Audit Type</th>
                    <th> Focused Audit Type Text If ANY</th>

                    @foreach ($repeater_param_data as $kk => $vv)
                    @foreach ($vv as $kkb => $vvb)
                    <th>{{ $vvb['name'] }}</th>
                    <th>Scored</th>
                    <th>Scorable</th>
                    <th>Reason Type</th>
                    <th>Reason</th>
                    <th>Remark</th>
                    @endforeach
                    @endforeach

                    <th>Total Scored</th>
                    <th>Total Scorable</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $kk => $vv)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $vv['audit_date'] }}</td>
                    <td>{{ $vv['auditor'] }}</td>
                    <td>{{ $vv['agent_name'] }}</td>
                    @if (Auth::user()->parent_client == 23)
                    <td>{{ $vv['feedback_to_agent'] }}</td>
                    @endif
                    {{-- <td>{{ $vv['temp_raw_data']->agent_id ?? 'NA' }}</td> --}}
                    <td>{{ $vv['temp_raw_data']->emp_id ?? 'NA' }}</td>
                    <td>{{ $vv['language_2'] }}</td>
                    <td>{{ $vv['temp_raw_data']->call_time }}</td>
                    <td>{{ $vv['temp_raw_data']->call_duration }}</td>
                    <td>{{ $vv['temp_raw_data']->lob }}</td>
                    <td>{{ $vv['acpt'] }}</td>
                    <td>{{ $vv['recording_link'] ?? 'NA' }}</td>
                    <td>{{ $vv['sla_manager'] }}</td>
                    <td>{{ $vv['event_c'] }}</td>
                    <td>{{ $vv['case_id_raw'] }}</td>
                    <td>{{ $vv['overall_summary'] }}</td>
                    <td>{{ $vv['location'] }}</td>
                    <td>{{ $vv['partner_name'] }}</td>
                    <td>{{ $vv['case_create_date'] }}</td>
                    <td>{{ $vv['disbursment_date'] }}</td>
                    <td>{{ $vv['diff'] }}</td>
                    <td>{{ $vv['close_date'] }}</td>
                    <td>{{ $vv['call_id'] }}</td>
                    <td>{{ $vv['relationship_number'] }}</td>
                    <td>{{ $vv['type_c'] }}</td>
                    <td>{{ $vv['sub_type'] }}</td>
                    <td>{{ $vv['category'] }}</td>
                    <td>{{ $vv['solution_based_category_c'] }}</td>
                    <td>{{ $vv['internal_case_reason_c'] }}</td>
                    <td>{{ $vv['disposition'] }}</td>
                    <td>{{ $vv['sub_disposition'] }}</td>
                    <td>{{ $vv['queue_name_c'] }}</td>
                    <td>{{ $vv['month'] }}</td>
                    <td>{{ $vv['week'] }}</td>
                    <td>{{ $vv['hrs_tat_bucket'] }}</td>
                    <td>{{ $vv['user_name'] }}</td>
                    <td>{{ $vv['case_origin'] }}</td>
                    <td>{{ $vv['product_id'] }}</td>
                    <td>{{ $vv['product_business_family'] }}</td>
                    <td>{{ $vv['fpr'] }}</td>
                    <td>{{ $vv['case_origin_channel'] }}</td>
                    <td>{{ $vv['channel_master'] }}</td>
                    <td>{{ $vv['owner'] }}</td>
                    <td>{{ $vv['current_assigned_queue'] }}</td>
                    <td>{{ $vv['vertical'] }}</td>
                    <td>{{ $vv['case_reason_when_case_closed'] }}</td>
                    <td>{{ $vv['case_status'] }}</td>
                    <td>{{ $vv['temp_raw_data']->customer_name }}</td>
                    <td>{{ $vv['bot_disposition_as_per_logic'] }}</td>
                    <td>{{ $vv['temp_raw_data']->campaign_name }}</td>
                    <td>{{ $vv['focused_audit_type'] }}</td>
                    <td>{{ $vv['focused_audit_remark'] }}</td>

                    <?php
                    $total_scored = 0;
                    $total_scorable = 0;
                    $is_critical = 0; ?>
                    @foreach ($vv['audit'] as $kk => $vv)
                    @foreach ($vv as $kkb => $vvb)
                    <?php if ($vvb['observation'] == 'Critical') {
                        $is_critical = 1;
                    } ?>
                    <td>{{ $vvb['observation'] }}</td>
                    <td>{{ $vvb['scored'] }}</td>
                    <td>{{ $vvb['scorable'] }}</td>
                    <td>
                        {{ $vvb['reason_type'] == 0 ? '' : $vvb['reason_type'] }}
                    </td>

                    <?php
                    $string = str_replace(' ', '-', $vvb['reason']); // Replaces all spaces with hyphens.
                    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
                    $string = preg_replace('/-+/', ' ', $string);
                    ?>

                    <td>{{ strip_tags($string) }}</td>



                    <?php
                    $string = str_replace(' ', '-', $vvb['remark']); // Replaces all spaces with hyphens.
                    $string = preg_replace('/[^,"":().A-Za-z0-9-\/-]/', '', $string);
                    $string = preg_replace('/-+/', ' ', $string);
                    ?>

                    <td>{{ strip_tags($string) }}</td>



                    <?php
                    $total_scored += $vvb['scored'];
                    $total_scorable += $vvb['scorable']; ?>
                    @endforeach
                    @endforeach
                    <td><?php if ($is_critical == 1) {
                            echo 0;
                        } else {
                            if ($total_scored >= $total_scorable) {
                                echo $total_scorable;
                            } else {
                                echo $total_scored;
                            }
                        } ?></td>
                    <td><?php if ($total_scorable > 100) {
                            echo 100;
                        } else {
                            echo $total_scorable;
                        } ?></td>
                </tr>
                @endforeach
            </tbody>
            @endif

            <!-- RCC sheets -->

            @elseif ($processs == 224|| $processs == 225|| $processs == 226 || $processs == 227 || $processs == 228 || $processs == 229 || $processs == 230 || $processs == 232 || $processs == 233 || $processs == 234 || $processs == 235 || $processs == 236 || $processs == 237 || $processs == 238 || $processs == 239 || $processs == 240 || $processs == 241 || $processs == 242 || $processs == 243 || $processs == 244 || $processs == 245 || $processs == 246 || $processs == 247 || $processs == 248 || $processs == 249 || $processs == 250 ||$processs == 251 || $processs == 252 || $processs == 253 || $processs == 254 || $processs == 255 ||$processs == 256 ||$processs == 257 || $processs == 258 || $processs == 259 || $processs == 260 || $processs == 261 || $processs == 262 ||$processs == 263 ||$processs == 264 || $processs == 265 || $processs == 266 || $processs == 267 || $processs == 268 || $processs == 269 || $processs == 270 || $processs == 271 || $processs == 272 || $processs == 273|| $processs == 274|| $processs == 275|| $processs == 276 || $processs == 277 || $processs == 278 || $processs == 279 || $processs == 280 || $processs == 281 || $processs == 282 ||$processs == 283 || $processs == 284 || $processs == 285  )



            @if($processs == 224 || $processs == 225 || $processs == 226 || $processs == 227 || $processs == 228 || $processs == 229 || $processs == 230)


            <thead>
                <tr>
                    <th>Sr. No</th>
                    <th>Audit Date</th>
                    <th>Auditor Name</th>
                    <!-- <th>Location</th> -->
                    <th>Call Id/CaseNumber</th>
                    <th>Case Audit date</th>
                    <th>Emp Id</th>
                    <th>QC Username</th>
                    <th>Auditor ID</th>
                    <th>Advisior Name</th>
                    <th>Case Type</th>
                    <th>TL Name</th>
                    <th>Status</th>
                    <th>Agent Name</th>
                    <th>LOB</th>
                    <th>Case Reason</th>
                    <th>Agent processing date</th>
                    <th>System Name</th>
                    <th>Case Number</th>
                    <th>Case Origin</th>
                    <th>Relationship Number</th>
                    <th>Overdue Waiver Amount</th>
                    <th>Waiver Status</th>
                    <th>Waiver Amount</th>
                    <th>Other charges</th>

                    <th>CBC</th>
                    <th>LPP</th>
                    <th>Call duration</th>
                    <th>Email</th>
                    <th>Language</th>
                    <th>Vertical</th>
                    <th>Tenure</th>
                    <th>Category (ABCU)</th>
                    <th>Overall Summary</th>
                    <th>Agent feedback</th>
                    <th>Good/Bad Call</th>



                    @foreach ($repeater_param_data as $group)
                    @foreach ($group as $param)
                    <th>{{ $param['name'] }}</th>
                    <th>Scored</th>
                    <th>Scorable</th>
                    <th>Reason Type</th>
                    <th>Reason</th>
                    <th>Remark</th>
                    @endforeach
                    @endforeach

                    <th>Total Scored</th>
                    <th>Total Scorable</th>

                    @foreach ($parameter_names as $param_id => $param_name)
                    <th>Parameter Name: {{ $param_name }}</th>
                    <th>Fatal/ Or Not</th>
                    <th>Scorable</th>
                    <th>Scored</th>
                    @endforeach

                </tr>
            </thead>

            <tbody>
                @foreach ($data as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['audit_date'] }}</td>
                    <td>{{ $row['auditor'] }}</td>
                    <td>{{ $row['call_id'] }}</td>
                    <td>{{ $row['temp_raw_data']->case_create_date ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->emp_id ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->user_name ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->auditor_id ?? 'NA'  }}</td>
                    <td>{{ $row['temp_raw_data']->auditor_name ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_type ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->bfl_tl_name ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_status ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->agent_name ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->lob ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_reason ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_create_date ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->audit_type ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_number ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_origin ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->relationship_number ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->overdue_wavier_amount ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->wavier_status ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->wavier_amount ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->other_charges ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->cbc ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->lpp ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->call_duration ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->email ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->language ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->vertical ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->diff ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->audit_type ?? 'NA' }}</td>
                    <td>{{ $row['overall_summary'] ?? 'NA' }}</td>

                    <td>{{ $row['agent_feedback'] ?? 'NA' }}</td>
                    <td>
                        @if ($row['good_bad_call'] == 1)
                        Good Call
                        @elseif ($row['good_bad_call'] == 2)
                        Bad Call
                        @elseif ($row['good_bad_call'] == 3)
                        Average Call
                        @else
                        {{-- Show nothing --}}
                        @endif
                    </td>



                    @php
                    $total_scored = 0;
                    $total_scorable = 0;
                    $is_critical = 0;
                    $row_parameter_result = $row['parameter_result'] ?? []; // ✅ this block's loop var is $row
                    @endphp


                    @foreach ($row['audit'] as $auditGroup)
                    @foreach ($auditGroup as $audit)
                    @php
                    if ($audit['observation'] == 'Critical') {
                    $is_critical = 1;
                    }

                    $reason = preg_replace('/[^A-Za-z0-9 ]/', '', $audit['reason']);
                    $remark = preg_replace('/[^,"":().A-Za-z0-9\/ -]/', '', $audit['remark']);

                    $total_scored += $audit['scored'];
                    $total_scorable += $audit['scorable'];
                    @endphp

                    <td>{{ $audit['observation'] }}</td>
                    <td>{{ $audit['scored'] }}</td>
                    <td>{{ $audit['scorable'] }}</td>
                    <td>{{ $audit['reason_type'] ?: '' }}</td>
                    <td>{{ $reason }}</td>
                    <td>{{ $remark }}</td>
                    @endforeach
                    @endforeach

                    <td>
                        {{ $is_critical ? 0 : min($total_scored, $total_scorable) }}
                    </td>
                    <td>
                        {{ $total_scorable > 100 ? 100 : $total_scorable }}
                    </td>
                    @foreach ($parameter_names as $param_id => $param_name)
                    <td>{{ $param_name }}</td>
                    <td>{{ ($row_parameter_result[$param_id]['is_critical'] ?? 0) == 1 ? 'Fatal' : 'Not Fatal' }}</td>
                    <td>{{ $row_parameter_result[$param_id]['scorable'] ?? 0 }}</td>
                    <td>{{ $row_parameter_result[$param_id]['scored'] ?? 0 }}</td>
                    @endforeach





                </tr>
                @endforeach
            </tbody>


            <!-- Manual Sheets -->

            @elseif($processs == 243 || $processs == 244 || $processs == 245 || $processs == 246 || $processs == 247 || $processs == 248 || $processs == 249 || $processs == 250 ||$processs == 251 || $processs == 252 || $processs == 253 || $processs == 254 || $processs == 255 ||$processs == 256 ||$processs == 257 || $processs == 258 || $processs == 259 || $processs == 264 || $processs == 267 || $processs == 274|| $processs == 275|| $processs == 276 || $processs == 277 || $processs == 278 || $processs == 279 || $processs == 280 || $processs == 281 ||  $processs == 285)

            <thead>
                <tr>
                    <th>Sr. No</th>
                    <th>Audit Date</th>
                    <th>Auditor Name</th>
                    <th>Call Id/CaseNumber</th>
                    @foreach($dynamicHeaders as $field)
                    <th>{{ $field->field_name }}</th>
                    @endforeach
                    <th>Overall Summary</th>
                    <th>Agent feedback</th>
                    <th>Good/Bad Call</th>






                    @foreach ($repeater_param_data as $group)
                    @foreach ($group as $param)
                    <th>{{ $param['name'] }}</th>
                    <th>Scored</th>
                    <th>Scorable</th>
                    <th>Reason Type</th>
                    <th>Reason</th>
                    <th>Remark</th>
                    @endforeach
                    @endforeach

                    <th>Total Scored</th>
                    <th>Total Scorable</th>

                    @foreach ($parameter_names as $param_id => $param_name)
                    <th>Parameter Name: {{ $param_name }}</th>
                    <th>Fatal/ Or Not</th>
                    <th>Scorable</th>
                    <th>Scored</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach ($data as $row)

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['audit_date'] }}</td>
                    <td>{{ $row['auditor'] }}</td>
                    <td>{{ $row['call_id'] }}</td>
                    @foreach($dynamicHeaders as $header)
                    <td>
                        {{ $row['dynamic_fields'][$header->id] ?? 'NA' }}
                    </td>
                    @endforeach

                    <td>{{ $row['overall_summary'] ?? 'NA' }}</td>
                    <td>{{ $row['agent_feedback'] ?? 'NA' }}</td>
                    <td>
                        @if ($row['good_bad_call'] == 1)
                        Good Call
                        @elseif ($row['good_bad_call'] == 2)
                        Bad Call
                        @elseif ($row['good_bad_call'] == 3)
                        Average Call
                        @else
                        {{-- Show nothing --}}
                        @endif
                    </td>



                    @php
                    $total_scored = 0;
                    $total_scorable = 0;
                    $is_critical = 0;
                    $row_parameter_result = $row['parameter_result'] ?? [];
                    @endphp

                    @foreach ($row['audit'] as $auditGroup)
                    @foreach ($auditGroup as $audit)
                    @php
                    if ($audit['observation'] == 'Critical') {
                    $is_critical = 1;
                    }

                    $reason = preg_replace('/[^A-Za-z0-9 ]/', '', $audit['reason']);
                    $remark = preg_replace('/[^,"":().A-Za-z0-9\/ -]/', '', $audit['remark']);

                    $total_scored += $audit['scored'];
                    $total_scorable += $audit['scorable'];
                    @endphp

                    <td>{{ $audit['observation'] }}</td>
                    <td>{{ $audit['scored'] }}</td>
                    <td>{{ $audit['scorable'] }}</td>
                    <td>{{ $audit['reason_type'] ?: '' }}</td>
                    <td>{{ $reason }}</td>
                    <td>{{ $remark }}</td>
                    @endforeach
                    @endforeach

                    <td>
                        {{ $is_critical ? 0 : min($total_scored, $total_scorable) }}
                    </td>
                    <td>
                        {{ $total_scorable > 100 ? 100 : $total_scorable }}
                    </td>
                    @foreach ($parameter_names as $param_id => $param_name)
                    <td>{{ $param_name }}</td>
                    <td>{{ ($row_parameter_result[$param_id]['is_critical'] ?? 0) == 1 ? 'Fatal' : 'Not Fatal' }}</td>
                    <td>{{ $row_parameter_result[$param_id]['scorable'] ?? 0 }}</td>
                    <td>{{ $row_parameter_result[$param_id]['scored'] ?? 0 }}</td>
                    @endforeach

                </tr>
                @endforeach
            </tbody>

            @elseif($processs == 262 || $processs == 272 || $processs == 282 || $processs == 284 || $processs == 268|| $processs == 269 || $processs == 270)

            <thead>
                <tr>
                    <th>Sr. No</th>

                    <th>Audit Date</th>
                    <th>Auditor Name</th>

                    <th>Call Id/CaseNumber</th>
                    <th>Current Assigned Queue</th>
                    <th>Case origin</th>
                    <th>Case Number</th>
                    <th>Relationship Number</th>
                    <th>Complaint</th>
                    <th>Customer Redressal Decision</th>
                    <th>Case ID</th>
                    <th>Case Opened date</th>
                    <th>Case Closing Date</th>
                    <th>Type</th>
                    <th>Sub Type</th>
                    <th>Case Reason</th>
                    <th>Case Disposition Name</th>
                    <th>Customer Query</th>
                    <th>Case Owner</th>
                    <th>Status</th>
                    <th>Case reason Status</th>
                    <th>Case reasons</th>
                    <th>Outcall Status </th>

                    @if($processs == 282)
                    <th>Delay in SR Closure</th>
                    <th>Amount</th>

                    @else
                    <th>UUID Captured in Comments</th>
                    <th>Allegation category</th>

                    @endif

                    <th>Impact </th>
                    <th>Transaction Exectution</th>
                    <th>Empowerment Validation</th>
                    <th>Empowerment Validation-2</th>
                    <th>Comments Updated</th>
                    <th>Response Template Selection</th>
                    <th>Correct LAN</th>
                    <th>Correct LAN comment (If incorrect)</th>
                    <th>Corrective action taken</th>
                    <th>service recovery </th>
                    <th>Overall Summary</th>
                    <th>Agent feedback</th>
                    <th>Good/Bad Call</th>


                    @foreach ($repeater_param_data as $group)
                    @foreach ($group as $param)
                    <th>{{ $param['name'] }}</th>
                    <th>Scored</th>
                    <th>Scorable</th>
                    <th>Reason Type</th>
                    <th>Reason</th>
                    <th>Remark</th>
                    @endforeach
                    @endforeach

                    <th>Total Scored</th>
                    <th>Total Scorable</th>

                    @foreach ($parameter_names as $param_id => $param_name)
                    <th>Parameter Name: {{ $param_name }}</th>
                    <th>Fatal/ Or Not</th>
                    <th>Scorable</th>
                    <th>Scored</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach ($data as $row)

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['audit_date'] }}</td>
                    <td>{{ $row['auditor'] }}</td>
                    <td>{{ $row['call_id'] }}</td>
                    <td>{{ $row['temp_raw_data']->current_assigned_queue ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_origin ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_number ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->relationship_number ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->error_code_reason ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->error_code ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_id ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_create_date ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->close_date ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->call_type ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->call_sub_type ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_reason ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->disposition ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->scenerio ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_origin_channel ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_status ?? 'NA' }}</td>
                    <td>{{ $row['case_dropdown'] ?? 'NA' }}</td>
                    <td>{{ $row['case_reason_if_incorrect'] ?? 'NA' }}</td>
                    <td>{{ $row['caller_type'] ?? 'NA' }}</td>
                    <td>{{ $row['scenario'] ?? 'NA' }}</td>
                    <td>{{ $row['scanerio_codes'] ?? 'NA' }}</td>
                    <td>{{ $row['l2_ticket_disposition'] ?? 'NA' }}</td>
                    <td>{{ $row['call_disposition'] ?? 'NA' }}</td>
                    <td>{{ $row['bot_disposition_as_per_logic'] ?? 'NA' }}</td>
                    <td>{{ $row['empowerment_validation'] ?? 'NA' }}</td>
                    <td>{{ $row['case_comment'] ?? 'NA' }}</td>
                    <td>{{ $row['acpt_l2'] ?? 'NA' }}</td>
                    <td>{{ $row['call_stage'] ?? 'NA' }}</td>
                    <td>{{ $row['lan_incorrect_comment'] ?? 'NA' }}</td>
                    <td>{{ $row['sfdc_disposition'] ?? 'NA' }}</td>
                    <td>{{ $row['hot_leads'] ?? 'NA' }}</td>
                    <td>{{ $row['overall_summary'] ?? 'NA' }}</td>
                    <td>{{ $row['agent_feedback'] ?? 'NA' }}</td>
                    <td>
                        @if ($row['good_bad_call'] == 1)
                        Good Call
                        @elseif ($row['good_bad_call'] == 2)
                        Bad Call
                        @elseif ($row['good_bad_call'] == 3)
                        Average Call
                        @else
                        {{-- Show nothing --}}
                        @endif
                    </td>




                    @php
                    $total_scored = 0;
                    $total_scorable = 0;
                    $is_critical = 0;
                    $row_parameter_result = $row['parameter_result'] ?? [];
                    @endphp

                    @foreach ($row['audit'] as $auditGroup)
                    @foreach ($auditGroup as $audit)
                    @php
                    if ($audit['observation'] == 'Critical') {
                    $is_critical = 1;
                    }

                    $reason = preg_replace('/[^A-Za-z0-9 ]/', '', $audit['reason']);
                    $remark = preg_replace('/[^,"":().A-Za-z0-9\/ -]/', '', $audit['remark']);

                    $total_scored += $audit['scored'];
                    $total_scorable += $audit['scorable'];
                    @endphp

                    <td>{{ $audit['observation'] }}</td>
                    <td>{{ $audit['scored'] }}</td>
                    <td>{{ $audit['scorable'] }}</td>
                    <td>{{ $audit['reason_type'] ?: '' }}</td>
                    <td>{{ $reason }}</td>
                    <td>{{ $remark }}</td>
                    @endforeach
                    @endforeach

                    <td>
                        {{ $is_critical ? 0 : min($total_scored, $total_scorable) }}
                    </td>
                    <td>
                        {{ $total_scorable > 100 ? 100 : $total_scorable }}
                    </td>
                    @foreach ($parameter_names as $param_id => $param_name)
                    <td>{{ $param_name }}</td>
                    <td>{{ ($row_parameter_result[$param_id]['is_critical'] ?? 0) == 1 ? 'Fatal' : 'Not Fatal' }}</td>
                    <td>{{ $row_parameter_result[$param_id]['scorable'] ?? 0 }}</td>
                    <td>{{ $row_parameter_result[$param_id]['scored'] ?? 0 }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>








            @elseif($processs == 261 || $processs == 263)

            <thead>
                <tr>
                    <th>Sr. No</th>

                    <th>Audit Date</th>
                    <th>Auditor Name</th>

                    <th>Call Id/CaseNumber</th>
                    <th>Call Date</th>
                    <th>Call Duration</th>
                    <th>Case Date</th>
                    <th>Date of QC</th>
                    <th>EMP ID</th>
                    <th>Agent Name</th>
                    <th>TL Name</th>
                    <th>Platform</th>
                    <th>Source</th>
                    <th>Case Reason</th>
                    <th>Disposition</th>
                    <th>Event</th>
                    <th>Reason for Closure</th>
                    <th>Appropriate/Inappropriate / QC Status </th>
                    <th>Observation</th>
                    <th>Corrective Action</th>
                    <th>Corrective action Status (Yes/No)</th>
                    <th>Preventive Action </th>
                    <th>QC Done BY </th>
                    <th>API No.</th>
                    <th>Case Reason Tagged</th>
                    <th>Case Number</th>

                    <th>Overall Summary</th>
                    <th>Agent feedback</th>
                    <th>Good/Bad Call</th>


                    @foreach ($repeater_param_data as $group)
                    @foreach ($group as $param)
                    <th>{{ $param['name'] }}</th>
                    <th>Scored</th>
                    <th>Scorable</th>
                    <th>Reason Type</th>
                    <th>Reason</th>
                    <th>Remark</th>
                    @endforeach
                    @endforeach

                    <th>Total Scored</th>
                    <th>Total Scorable</th>

                    @foreach ($parameter_names as $param_id => $param_name)
                    <th>Parameter Name: {{ $param_name }}</th>
                    <th>Fatal/ Or Not</th>
                    <th>Scorable</th>
                    <th>Scored</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach ($data as $row)

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['audit_date'] }}</td>
                    <td>{{ $row['auditor'] }}</td>
                    <td>{{ $row['call_id'] }}</td>
                    <td>{{ $row['temp_raw_data']->call_time ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->call_duration ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_create_date ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->close_date ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->emp_id ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->agent_name ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->bfl_tl_name ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->product_business_family ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->scenerio ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_reason ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->disposition ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->event_c ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_reason_when_case_closed ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->issues ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->sub_issues ?? 'NA' }}</td>
                    <td>{{ $row['sfdc_disposition'] ?? 'NA' }}</td>
                    <td>{{ $row['hot_leads'] ?? 'NA' }}</td>
                    <td>{{ $row['variable'] ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->vendor_name ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->reference_no ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_origin_channel ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_number ?? 'NA' }}</td>

                    <td>{{ $row['overall_summary'] ?? 'NA' }}</td>
                    <td>{{ $row['agent_feedback'] ?? 'NA' }}</td>
                    <td>
                        @if ($row['good_bad_call'] == 1)
                        Good Call
                        @elseif ($row['good_bad_call'] == 2)
                        Bad Call
                        @elseif ($row['good_bad_call'] == 3)
                        Average Call
                        @else
                        {{-- Show nothing --}}
                        @endif
                    </td>




                    @php
                    $total_scored = 0;
                    $total_scorable = 0;
                    $is_critical = 0;
                    $row_parameter_result = $row['parameter_result'] ?? [];
                    @endphp

                    @foreach ($row['audit'] as $auditGroup)
                    @foreach ($auditGroup as $audit)
                    @php
                    if ($audit['observation'] == 'Critical') {
                    $is_critical = 1;
                    }

                    $reason = preg_replace('/[^A-Za-z0-9 ]/', '', $audit['reason']);
                    $remark = preg_replace('/[^,"":().A-Za-z0-9\/ -]/', '', $audit['remark']);

                    $total_scored += $audit['scored'];
                    $total_scorable += $audit['scorable'];
                    @endphp

                    <td>{{ $audit['observation'] }}</td>
                    <td>{{ $audit['scored'] }}</td>
                    <td>{{ $audit['scorable'] }}</td>
                    <td>{{ $audit['reason_type'] ?: '' }}</td>
                    <td>{{ $reason }}</td>
                    <td>{{ $remark }}</td>
                    @endforeach
                    @endforeach

                    <td>
                        {{ $is_critical ? 0 : min($total_scored, $total_scorable) }}
                    </td>
                    <td>
                        {{ $total_scorable > 100 ? 100 : $total_scorable }}
                    </td>
                    @foreach ($parameter_names as $param_id => $param_name)
                    <td>{{ $param_name }}</td>
                    <td>{{ ($row_parameter_result[$param_id]['is_critical'] ?? 0) == 1 ? 'Fatal' : 'Not Fatal' }}</td>
                    <td>{{ $row_parameter_result[$param_id]['scorable'] ?? 0 }}</td>
                    <td>{{ $row_parameter_result[$param_id]['scored'] ?? 0 }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>

            @elseif($processs == 283 || $processs == 271)

            <thead>
                <tr>
                    <th>Sr. No</th>
                    <th>Audit Date</th>
                    <th>Auditor Name</th>
                    <th>Location</th>
                    <th>Call Id/CaseNumber</th>
                    <th>Employee ID</th>
                    <th>Agent Name</th>
                    <th>Auditor Name</th>
                    @if($processs == 271)
                    <th>FD reference number</th>
                    @else
                    <th>Loan A/c Number</th>
                    @endif

                    <th>Case Owner</th>
                    <th>Case Orgin</th>
                    <th>Case Type</th>
                    <th>Type</th>
                    <th>Sub Type</th>
                    <th>Case Reason</th>
                    <th>Solution</th>
                    <th>Action Date</th>
                    <th>Monitor Date</th>
                    @if($processs == 271)
                    <th>Audit Type</th>
                    <th>Corrective action taken</th>
                    <th>Corrective action required </th>
                    <th>service recovery </th>
                    @endif
                    <th>Overall Summary</th>
                    <th>Agent feedback</th>
                    <th>Good/Bad Call</th>

                    @foreach ($repeater_param_data as $group)
                    @foreach ($group as $param)
                    <th>{{ $param['name'] }}</th>
                    <th>Scored</th>
                    <th>Scorable</th>
                    <th>Reason Type</th>
                    <th>Reason</th>
                    <th>Remark</th>
                    @endforeach
                    @endforeach

                    <th>Total Scored</th>
                    <th>Total Scorable</th>


                    @foreach ($parameter_names as $param_id => $param_name)
                    <th>Parameter Name: {{ $param_name }}</th>
                    <th>Fatal/ Or Not</th>
                    <th>Scorable</th>
                    <th>Scored</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach ($data as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['audit_date'] }}</td>
                    <td>{{ $row['auditor'] }}</td>
                    <td>{{ $row['location'] }}</td>
                    <td>{{ $row['call_id'] }}</td>
                    <td>{{ $row['temp_raw_data']->emp_id ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->agent_name ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->auditor_name ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->reference_no ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_origin_channel ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_origin ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_type ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->call_type ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->call_sub_type ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_reason ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->solution ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_create_date ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->close_date ?? 'NA' }}</td>

                    @if($processs == 271)
                     <td>{{ $row['temp_raw_data']->audit_type ?? 'NA' }}</td>
                    <td>{{ $row['sfdc_disposition'] ?? 'NA' }}</td>
                    <td>{{ $row['gen_ai'] ?? 'NA' }}</td>
                    <td>{{ $row['hot_leads'] ?? 'NA' }}</td>

                    @endif


                    <td>{{ $row['overall_summary'] ?? 'NA' }}</td>
                    <td>{{ $row['agent_feedback'] ?? 'NA' }}</td>
                    <td>
                        @if ($row['good_bad_call'] == 1)
                        Good Call
                        @elseif ($row['good_bad_call'] == 2)
                        Bad Call
                        @elseif ($row['good_bad_call'] == 3)
                        Average Call
                        @else
                        {{-- Show nothing --}}
                        @endif
                    </td>






                    @php
                    $total_scored = 0;
                    $total_scorable = 0;
                    $is_critical = 0;
                    $row_parameter_result = $row['parameter_result'] ?? [];
                    @endphp

                    @foreach ($row['audit'] as $auditGroup)
                    @foreach ($auditGroup as $audit)
                    @php
                    if ($audit['observation'] == 'Critical') {
                    $is_critical = 1;
                    }

                    $reason = preg_replace('/[^A-Za-z0-9 ]/', '', $audit['reason']);
                    $remark = preg_replace('/[^,"":().A-Za-z0-9\/ -]/', '', $audit['remark']);

                    $total_scored += $audit['scored'];
                    $total_scorable += $audit['scorable'];
                    @endphp

                    <td>{{ $audit['observation'] }}</td>
                    <td>{{ $audit['scored'] }}</td>
                    <td>{{ $audit['scorable'] }}</td>
                    <td>{{ $audit['reason_type'] ?: '' }}</td>
                    <td>{{ $reason }}</td>
                    <td>{{ $remark }}</td>
                    @endforeach
                    @endforeach

                    <td>
                        {{ $is_critical ? 0 : min($total_scored, $total_scorable) }}
                    </td>
                    <td>
                        {{ $total_scorable > 100 ? 100 : $total_scorable }}
                    </td>

                    @foreach ($parameter_names as $param_id => $param_name)
                    <td>{{ $param_name }}</td>
                    <td>{{ ($row_parameter_result[$param_id]['is_critical'] ?? 0) == 1 ? 'Fatal' : 'Not Fatal' }}</td>
                    <td>{{ $row_parameter_result[$param_id]['scorable'] ?? 0 }}</td>
                    <td>{{ $row_parameter_result[$param_id]['scored'] ?? 0 }}</td>
                    @endforeach

                </tr>
                @endforeach
            </tbody>




            @else


            <thead>
                <tr>
                    <th>Sr. No</th>
                    <th>Audit Date</th>
                    <th>Auditor Name</th>
                    <th>Location</th>
                    <th>Call Id/CaseNumber</th>
                    <th>Unit</th>
                    <th>Process</th>
                    <th>Action Date</th>
                    <th>Audit Type</th>
                    <th>Agent Name</th>
                    <th>Employee ID</th>
                    <th>Loan A/c Number</th>
                    <th>Case Status</th>
                    <th>Case Type</th>
                    <th>Type</th>
                    <th>Sub Type</th>
                    <th>Case Reason</th>
                    <th>Solution</th>
                    <th>Associate Name</th>
                    <th>Customer ID</th>
                    <th>EXISTING_CUSTOMER</th>
                    <th>GENDER</th>
                    <th>PAYMENT_MODE</th>
                    <th>IMPS_ACCOUNT_VALIDATION_STATUS</th>
                    <th>POS</th>
                    <th>BUCKET</th>
                    <th>Sales_one_Status</th>
                    <th>Sales_one_Status_new</th>
                    <th>INSURANCE_Status</th>
                    <th>Cust_Annual_Income</th>
                    <th>Document_Name1</th>
                    <th>Document_Name2</th>
                    <th>QC Status</th>
                    <th>QC Status Remark</th>
                    <th>Remark2</th>
                    <th>Document_Name3</th>
                    <th>Remark3</th>
                    <th>INDEX_ID</th>
                    <th>Checkpoints List</th>
                    <!-- // Manual entry -->
                    <th>Corrective action taken</th>
                    <th>Corrective action required </th>
                    <th>Preventive Action Taken </th>
                    <th>service recovery </th>
                    <th>Customer Type </th>
                    <th>SFDC / DEAL ID NO </th>
                    <th>Application No </th>
                    <th>FD Number </th>
                    <th>Channel</th>
                    <th>Renewal</th>
                    <th>Renewal Channel</th>
                    <th>APPLICANT_NAME</th>
                    <th>APPLICANT_NAME ON OVD</th>
                    <th>FD amount</th>
                    <th>Booking Date</th>
                    <th>Overall Summary</th>
                    <th>Agent feedback</th>
                    <th>Good/Bad Call</th>







                    @foreach ($repeater_param_data as $group)
                    @foreach ($group as $param)
                    <th>{{ $param['name'] }}</th>
                    <th>Scored</th>
                    <th>Scorable</th>
                    <th>Reason Type</th>
                    <th>Reason</th>
                    <th>Remark</th>
                    @endforeach
                    @endforeach

                    <th>Total Scored</th>
                    <th>Total Scorable</th>


                    @foreach ($parameter_names as $param_id => $param_name)
                    <th>Parameter Name: {{ $param_name }}</th>
                    <th>Fatal/ Or Not</th>
                    <th>Scorable</th>
                    <th>Scored</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach ($data as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['audit_date'] }}</td>
                    <td>{{ $row['auditor'] }}</td>
                    <td>{{ $row['location'] }}</td>
                    <td>{{ $row['call_id'] }}</td>
                    <td>{{ $row['temp_raw_data']->qrc_1 ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->bfl_process ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->opened_date ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->audit_type ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->agent_name ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->emp_id ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->reference_no ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_status ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_type ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->call_type ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->call_sub_type ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_reason ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->solution ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->campaign_name ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->customer_id ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->customer_name ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->brand_name ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->order_stage ?? 'NA'  }}</td>
                    <td>{{ $row['temp_raw_data']->sr_mgr ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->fpr ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->hrs_tat_bucket ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->info_1 ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->info_2 ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->info_3 ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->info_4 ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->info_5 ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->error_code ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->error_reason_type ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->error_code_reason ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->issues ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->sub_issues ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_reason_when_case_closed ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->product_id ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->solution_based_category_c ?? 'NA' }}</td>

                    <td>{{ $row['sfdc_disposition'] ?? 'NA' }}</td>
                    <td>{{ $row['gen_ai'] ?? 'NA' }}</td>
                    <td>{{ $row['variable'] ?? 'NA' }}</td>
                    <td>{{ $row['hot_leads'] ?? 'NA' }}</td>
                    <td>{{ $row['consent_nonconcet_lead'] ?? 'NA' }}</td>
                    <td>{{ $row['customer_stt_1'] ?? 'NA' }}</td>
                    <td>{{ $row['customer_stt2'] ?? 'NA' }}</td>
                    <td>{{ $row['customer_stt_3'] ?? 'NA' }}</td>
                    <td>{{ $row['customer_stt_4'] ?? 'NA' }}</td>
                    <td>{{ $row['customer_stt_5'] ?? 'NA' }}</td>
                    <td>{{ $row['intervention_in_call'] ?? 'NA' }}</td>
                    <td>{{ $row['intervention_drop_down'] ?? 'NA' }}</td>
                    <td>{{ $row['agent_intervenes_reason'] ?? 'NA' }}</td>
                    <td>{{ $row['info_given_by_sinior'] ?? 'NA' }}</td>
                    <td>{{ $row['temp_raw_data']->case_create_date ?? 'NA' }}</td>
                    <td>-</td>
                    <td>-</td>
                    <td>
                        @if ($row['good_bad_call'] == 1)
                        Good Call
                        @elseif ($row['good_bad_call'] == 2)
                        Bad Call
                        @elseif ($row['good_bad_call'] == 3)
                        Average Call
                        @else
                        {{-- Show nothing --}}
                        @endif
                    </td>




                    @php
                    $total_scored = 0;
                    $total_scorable = 0;
                    $is_critical = 0;
                    $row_parameter_result = $row['parameter_result'] ?? [];
                    @endphp

                    @foreach ($row['audit'] as $auditGroup)
                    @foreach ($auditGroup as $audit)
                    @php
                    if ($audit['observation'] == 'Critical') {
                    $is_critical = 1;
                    }

                    $reason = preg_replace('/[^A-Za-z0-9 ]/', '', $audit['reason']);
                    $remark = preg_replace('/[^,"":().A-Za-z0-9\/ -]/', '', $audit['remark']);

                    $total_scored += $audit['scored'];
                    $total_scorable += $audit['scorable'];
                    @endphp

                    <td>{{ $audit['observation'] }}</td>
                    <td>{{ $audit['scored'] }}</td>
                    <td>{{ $audit['scorable'] }}</td>
                    <td>{{ $audit['reason_type'] ?: '' }}</td>
                    <td>{{ $reason }}</td>
                    <td>{{ $remark }}</td>
                    @endforeach
                    @endforeach

                    <td>
                        {{ $is_critical ? 0 : min($total_scored, $total_scorable) }}
                    </td>
                    <td>
                        {{ $total_scorable > 100 ? 100 : $total_scorable }}
                    </td>

                    @foreach ($parameter_names as $param_id => $param_name)
                    <td>{{ $param_name }}</td>
                    <td>{{ ($row_parameter_result[$param_id]['is_critical'] ?? 0) == 1 ? 'Fatal' : 'Not Fatal' }}</td>
                    <td>{{ $row_parameter_result[$param_id]['scorable'] ?? 0 }}</td>
                    <td>{{ $row_parameter_result[$param_id]['scored'] ?? 0 }}</td>
                    @endforeach

                </tr>
                @endforeach
            </tbody>

            @endif

            @else
            <thead>
                <tr>
                    <th>#</th>
                    @if (Auth::user()->parent_client == 23)
                    @if ($processs == 191 || $processs == 208 || $processs == 286)
                    <th>Auditor Name</th>
                    @else
                    @if (Auth::user()->hasRole('partner-training-head') ||
                    Auth::user()->hasRole('partner-operation-head') ||
                    Auth::user()->hasRole('partner-quality-head') ||
                    Auth::user()->hasRole('partner-admin') ||
                    Auth::user()->hasRole('partner-qa'))
                    {{-- <td>{{$vv['auditor']}}</td> --}}

                    @else
                    <th>Auditor Name</th>
                    @endif
                    @endif
                    @else
                    @if (Auth::user()->hasRole('client') ||
                    Auth::user()->hasRole('partner-training-head') ||
                    Auth::user()->hasRole('partner-operation-head') ||
                    Auth::user()->hasRole('partner-quality-head') ||
                    Auth::user()->hasRole('partner-admin') ||
                    Auth::user()->hasRole('partner-qa'))
                    {{-- <td>{{$vv['auditor']}}</td> --}}
                    @else
                    <th>Auditor Name</th>
                    @endif
                    @endif



                    {{-- @if (Auth::user()->hasRole('client') || Auth::user()->hasRole('partner-training-head') || Auth::user()->hasRole('partner-operation-head') || Auth::user()->hasRole('partner-quality-head') || Auth::user()->hasRole('partner-admin'))
                            {{-- <th>Auditor Name</th> --}}
                    {{-- @else
                            <th>Auditor Name</th>
                        @endif --}}

                    <th>
                        @if (isset($labels->qm_sheet_id))
                        {{ $labels->info_4 }}
                        @else
                        Audit Date
                        @endif
                    </th>
                    <th>

                        @if (isset($labels->qm_sheet_id))
                        {{ $labels->info_3 }}
                        @else
                        Partner
                        @endif

                    </th>


                    <th>
                        Location

                    </th>
                    <th>Call Id</th>
                    @if (Auth::user()->parent_client != 23)

                    <th>Call File</th>
                    @endif
                    @if (Auth::user()->id == 3549 || Auth::user()->id == 3531 || Auth::user()->id == 4225)
                    <th>Call File</th>
                    @endif
                    <th>
                        @if (isset($labels->qm_sheet_id))
                        {{ $labels->info_5 }}
                        @else
                        Agent Name
                        @endif

                    </th>

                    @if (Auth::user()->parent_client == 14 || Auth::user()->parent_client == 23)
                    <th>
                        Agent Feedback
                    </th>
                    @endif

                    <th>Emp. ID</th>
                    <th>Doj</th>
                    <th>LOB</th>
                    <th>
                        Language

                    </th>
                    <th>Case Id</th>
                    @if (Auth::user()->hasRole('client') ||
                    Auth::user()->hasRole('partner-training-head') ||
                    Auth::user()->hasRole('partner-operation-head') ||
                    Auth::user()->hasRole('partner-quality-head') ||
                    Auth::user()->hasRole('partner-admin') ||
                    Auth::user()->hasRole('partner-qa'))
                    @else
                    <th>Auditor's Total Spend Time</th>
                    @endif

                    <th>
                        @if (isset($labels->qm_sheet_id))
                        {{ $labels->info_19 }}
                        @else
                        Call Time
                        @endif

                    </th>
                    <th>
                        @if (isset($labels->qm_sheet_id))
                        {{ $labels->info_20 }}
                        @else
                        Call Duration
                        @endif

                    </th>

                    @if ($processs == 109)
                    <th>ACPT CSAT </th>
                    @else
                    <th>Call Type</th>
                    @endif
                    <th>
                        @if (isset($labels->qm_sheet_id))
                        {{ $labels->info_9 }}
                        @else
                        Call Sub Type
                        @endif
                    </th>
                    <th>
                        @if (isset($labels->qm_sheet_id))
                        {{ $labels->info_10 }}
                        @else
                        Disposition
                        @endif
                    </th>
                    <th>
                        @if (isset($labels->qm_sheet_id))
                        {{ $labels->info_8 }}
                        @else
                        Compaign Name
                        @endif
                    </th>



                    <th>
                        @if (isset($labels->qm_sheet_id))
                        {{ $labels->info_11 }}
                        @else
                        Customer Name
                        @endif
                    </th>
                    @if ($client_id != 23)
                    <th>
                        @if (isset($labels->qm_sheet_id))
                        {{ $labels->info_12 }}
                        @else
                        Customer Phone
                        @endif
                    </th>
                    @endif
                    <!-- <th>Audit Date</th>
                                                <th>Partner</th>
                                                <th>Location</th>
                                                <th>Call Id</th>
                                                <th>Agent Name</th>
                                                <th>Emp. ID</th>
                                                <th>Doj</th>
                                                <th>LOB</th>
                                                <th>Language</th>
                                                <th>Case Id</th>
                                                <th>Call Time</th>
                                                <th>Call Duration</th>
                                                <th>Call Type</th>
                                                <th>Call Sub Type</th>
                                                <th>Disposition</th>
                                                <th>Compaign Name</th>
                                                <th>Customer Name</th>
                                                <th>Customer Phone</th> -->

                    @if ($processs == 191 || $processs == 208 ||  $processs == 286)
                    <th>Unique Number</th>
                    @else
                    <th>Reference Number</th>
                    @endif
                    <th>QRC</th>
                    <th>Overall Summary</th>
                    <th>With Fatal Score</th>
                    <th>Without Fatal Score</th>
                    <th>Brand Name</th>
                    <th>Circle</th>
                    <th>Info 1</th>
                    <th>Info 2</th>
                    <th>Info 3</th>
                    <th>Info 4</th>
                    <th>Info 5</th>
                    <th>Good/Bad Call</th>
                    <th>Consented/Non consented leads</th>
                    <th>SFDC Disposition</th>
                    <th>Focused Audit Sub type</th>
                    @if (Auth::user()->id == 3549 || Auth::user()->id == 3531 || Auth::user()->id == 4225)
                    <th>Customer Id For Bot</th>
                    @endif

                    @if (Auth::user()->parent_client == 23 && $processs == 138)
                    <th>case_number</th>
                    <th>case_origin</th>
                    <th>current_assigned_queue</th>
                    <th>sub_type</th>
                    <th>partner_emp_id</th>
                    <th>partner_agent_name </th>
                    <th>partner_agent_tenure</th>
                    <th>case_reason</th>
                    <th>solution</th>
                    <th>opened_date</th>
                    <th>close_date</th>
                    <th>fpr</th>
                    <th>sr_mgr</th>
                    <th>relationship_number</th>
                    @endif




                    @if (
                    (Auth::user()->parent_client == 23 && $processs == 88) ||
                    $processs == 120 ||
                    $processs == 110 ||
                    $processs == 101 ||
                    $processs == 155 ||
                    $processs == 158 ||
                    $processs == 173)
                    @php
                    for ($i = 1; $i < 21; $i++) { echo '<th>AHT ' . $i . '</th>' ; echo '<th>Mobile No ' . $i . '</th>' ; } @endphp @endif @if ( (Auth::user()->parent_client == 23 && $processs == 215 || $processs == 220))
                        @php
                        for ($i = 1; $i < 21; $i++) { echo '<th>bot_status ' . $i . '</th>' ; echo '<th>confidenceScore ' . $i . '</th>' ; echo '<th>words ' . $i . '</th>' ; } @endphp @endif @if (Auth::user()->parent_client == 14)
                            <th>
                                Scanerio Code
                            </th>
                            <th>
                                Vehicle Type
                            </th>
                            <th>
                                Error Code
                            </th>
                            @endif
                            @if (Auth::user()->parent_client == 23 && $processs == 109)
                            <th>First Level Drill Down</th>
                            <th>Second Level Drill Down</th>
                            <th>Third Level Drill Down</th>
                            <th>ACPT</th>
                            @endif

                            @if (
                            (Auth::user()->parent_client == 23 && $processs == 160) ||
                            $processs == 175 ||
                            $processs == 180 ||
                            $processs == 181 ||
                            $processs == 183 ||
                            $processs == 185 ||
                            $processs == 192 ||
                            $processs == 193 ||
                            $processs == 210 || $processs == 211 || $processs == 215 ||$processs == 220)
                            <th>Confidence Score</th>
                            <th>Customer STT 1</th>
                            <th>Customer STT 2</th>
                            <th>Customer STT 3</th>
                            <th>Customer STT 4</th>
                            <th>Customer STT 5</th>
                            <th>Sent to BOT or Not</th>
                            <th>Call Disconnected By</th>
                            <th>Disconnected Status</th>
                            <th>Gen Ai</th>
                            <th>variable</th>
                            <th>call_stage</th>
                            @if ($processs == 181)
                            <th>bot_disposition_as_per_logic</th>
                            @else
                            <th>Disposition as per agent logic</th>
                            @endif
                            @endif

                            @if (Auth::user()->parent_client == 23)
                            <th>Recording LInk</th>
                            <th>Call Count</th>
                            <th>Hot Leads</th>
                            <th>Hot Leads Reason</th>
                            <th>Gender Identification</th>
                            <th>BOT Identification on Gender</th>
                            <th>Customer Voice</th>
                            @endif


                            @if ($processs == 173)
                            <th>Intervention in call</th>
                            <th>Drop down</th>
                            <th>Agent intervenes count</th>
                            <th>Agent intervenes Reason</th>
                            <th>Information given by senior</th>
                            <th>Remark</th>
                            @endif
                            @if ($processs == 191 || $processs == 208 ||  $processs == 286)
                            <th>Call Type</th>
                            @endif


                            @foreach ($repeater_param_data as $kk => $vv)
                            @foreach ($vv as $kkb => $vvb)
                            <th>{{ $vvb['name'] }}</th>
                            <th>Scored</th>
                            <th>Scorable</th>
                            <th>Reason Type</th>
                            <th>Reason</th>
                            <th>Remark</th>
                            @endforeach
                            @endforeach

                            <th>Total Scored</th>
                            <th>Total Scorable</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $kk => $vv)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    @if (Auth::user()->parent_client == 23)
                    @if ($processs == 191 || $processs == 208 ||  $processs == 286)
                    <td>{{ $vv['auditor'] }}</td>
                    @else
                    @if (Auth::user()->hasRole('partner-training-head') ||
                    Auth::user()->hasRole('partner-operation-head') ||
                    Auth::user()->hasRole('partner-quality-head') ||
                    Auth::user()->hasRole('partner-admin') ||
                    Auth::user()->hasRole('partner-qa'))
                    {{-- <td>{{$vv['auditor']}}</td> --}}
                    @else
                    <td>{{ $vv['auditor'] }}</td>
                    @endif
                    @endif
                    @else
                    @if (Auth::user()->hasRole('client') ||
                    Auth::user()->hasRole('partner-training-head') ||
                    Auth::user()->hasRole('partner-operation-head') ||
                    Auth::user()->hasRole('partner-quality-head') ||
                    Auth::user()->hasRole('partner-admin') ||
                    Auth::user()->hasRole('partner-qa'))
                    {{-- <td>{{$vv['auditor']}}</td> --}}
                    @else
                    <td>{{ $vv['auditor'] }}</td>
                    @endif
                    @endif


                    <td>{{ $vv['audit_date'] }}</td>
                    <td>{{ $vv['partner'] }}</td>

                    <td>{{ $vv['location'] }}</td>
                    <td>{{ $vv['temp_raw_data']->call_id }}</td>
                    @if (Auth::user()->parent_client != 23 )
                    <td>{{ $vv['good_bad_call_file'] }}</td>
                    @endif
                    @if (Auth::user()->id == 3549 || Auth::user()->id == 3531 || Auth::user()->id == 4225)
                    <td>{{ $vv['good_bad_call_file'] }}</td>
                    @endif
                    <td>{{ $vv['temp_raw_data']->agent_name }}</td>
                    @if (Auth::user()->parent_client == 14 || Auth::user()->parent_client == 23)
                    <td>{{ $vv['agent_feedback'] }}</td>
                    @endif
                    <td>{{ $vv['temp_raw_data']->emp_id }}</td>
                    <td>{{ $vv['temp_raw_data']->doj }}</td>
                    <td>{{ $vv['temp_raw_data']->lob }}</td>
                    <td>{{ $vv['language_2'] }}</td>

                    <td>{{ $vv['case_id'] }}</td>
                    @if (Auth::user()->hasRole('client') ||
                    Auth::user()->hasRole('partner-training-head') ||
                    Auth::user()->hasRole('partner-operation-head') ||
                    Auth::user()->hasRole('partner-quality-head') ||
                    Auth::user()->hasRole('partner-admin') ||
                    Auth::user()->hasRole('partner-qa'))
                    @else
                    <td>
                        <?php

                        $secs1 = $vv['auditor_time_spend'];
                        $secs2 = $vv['auditor_sheet_time_secs'];

                        $sec = 0;

                        if ($secs1 > $secs2) {
                            $sec = $secs1;
                        } else {
                            $sec = $secs2;
                        }

                        $h = intval($sec / 3600);

                        $sec = $sec - $h * 3600;
                        $m = intval($sec / 60);
                        $s = $sec - $m * 60;

                        // $h = intval($vv['auditor_time_spend'] / 3600);
                        // $vv['auditor_time_spend'] = $vv['auditor_time_spend'] - $h * 3600;
                        // $m = intval($vv['auditor_time_spend'] / 60);
                        // $s = $vv['auditor_time_spend'] - $m * 60;

                        ?>
                        @if (!isset($h))
                        {{ $h }} Hours, {{ $m }} Minutes,
                        {{ $s }}
                        Secs.
                        @else
                        {{ $m }} Minutes, {{ $s }} Secs.
                        @endif

                    </td>
                    @endif
                    <td>{{ $vv['temp_raw_data']->call_time }}</td>
                    <td>{{ $vv['temp_raw_data']->call_duration }}</td>



                    <td>{{ $vv['temp_raw_data']->call_type }}</td>
                    <td>{{ $vv['temp_raw_data']->call_sub_type }}</td>
                    <td>{{ $vv['temp_raw_data']->disposition }}</td>
                    <td>{{ $vv['temp_raw_data']->campaign_name }}</td>


                    <td>{{ $vv['temp_raw_data']->customer_name }}</td>
                    @if ($client_id != 23)
                    <td>{{ $vv['temp_raw_data']->phone_number }}</td>
                    @endif
                    <td>{{ $vv['refrence_number'] }}</td>
                    <td>{{ $vv['qrc_2'] }}</td>
                    <td>{{ $vv['overall_summary'] }}</td>
                    <td>{{ $vv['with_fatal_score_per'] }}</td>
                    <td>{{ $vv['without_fatal_score'] }}</td>
                    <td>{{ $vv['temp_raw_data']->brand_name }}</td>
                    <td>{{ $vv['temp_raw_data']->circle }}</td>
                    <td>{{ $vv['temp_raw_data']->info_1 }}</td>
                    <td>{{ $vv['temp_raw_data']->info_2 }}</td>
                    <td>{{ $vv['temp_raw_data']->info_3 }}</td>
                    <td>{{ $vv['temp_raw_data']->info_4 }}</td>
                    <td>{{ $vv['temp_raw_data']->info_5 }}</td>
                    <td>
                        @if ($vv['good_bad_call'] == 1)
                        Good Call
                        @elseif ($vv['good_bad_call'] == 2)
                        Bad Call
                        @elseif ($vv['good_bad_call'] == 3)
                        Average Call
                        @else
                        {{-- Show nothing --}}
                        @endif
                    </td>
                    <td>{{ $vv['consent_nonconcet_lead'] }}</td>
                    <td>{{ $vv['sfdc_disposition'] }}</td>
                    <td>{{ $vv['temp_raw_data']->sub_issues }}</td>
                    @if (Auth::user()->id == 3549 || Auth::user()->id == 3531 || Auth::user()->id == 4225)
                    <td>{{ $vv['location_name'] }}</td>
                    @endif




                    @if (Auth::user()->parent_client == 23 && $processs == 138)
                    <td>{{ $vv['temp_raw_data']->case_number }}</td>
                    <td>{{ $vv['temp_raw_data']->case_origin }}</td>
                    <td>{{ $vv['temp_raw_data']->current_assigned_queue }}</td>
                    <td>{{ $vv['temp_raw_data']->sub_type }}</td>
                    <td>{{ $vv['temp_raw_data']->partner_emp_id }}</td>
                    <td>{{ $vv['temp_raw_data']->partner_agent_name }} </td>
                    <td>{{ $vv['temp_raw_data']->partner_agent_tenure }}</td>
                    <td>{{ $vv['temp_raw_data']->case_reason }}</td>
                    <td>{{ $vv['temp_raw_data']->solution }}</td>
                    <td>{{ $vv['temp_raw_data']->opened_date }}</td>
                    <td>{{ $vv['temp_raw_data']->close_date }}</td>
                    <td>{{ $vv['temp_raw_data']->fpr }}</td>
                    <td>{{ $vv['temp_raw_data']->sr_mgr }}</td>
                    <td>{{ $vv['temp_raw_data']->relationship_number }}</td>
                    @endif




                    @if (
                    (Auth::user()->parent_client == 23 && $processs == 88) ||
                    $processs == 120 ||
                    $processs == 110 ||
                    $processs == 101 ||
                    $processs == 155 ||
                    $processs == 158 ||
                    $processs == 173)
                    @php
                    for ($i = 0; $i < 20; $i++) { $ath=$callTime='-' ; if (isset($vv['AHt'][$i])) { $ath=$vv['AHt'][$i]; $callTime=$vv['Callstime'][$i]; } echo '<td>' . $ath . '</td>' ; echo '<td>' . $callTime . '</td>' ; } @endphp @endif @if ( (Auth::user()->parent_client == 23 && $processs == 215 || $processs == 220))
                        @php
                        for ($i = 0; $i < 20; $i++) { $bot_status=$confscore=$words='-' ; if (isset($vv['conf_score'][$i])) { $bot_status=$vv['botStatus'][$i]; $confscore=$vv['conf_score'][$i]; $words=$vv['Wordss'][$i]; } echo '<td>' . $bot_status . '</td>' ; echo '<td>' . $confscore . '</td>' ; echo '<td>' . $words . '</td>' ; } @endphp @endif @if (Auth::user()->parent_client == 14)
                            <td>{{ $vv['error_code'] }}</td>
                            <td>{{ $vv['vehicle_type'] }}</td>
                            <td>{{ $vv['new_error_code'] }}</td>
                            @endif

                            @if (Auth::user()->parent_client == 23 && $processs == 109)
                            <td>{{ $vv['first_level_drill'] }}</td>
                            <td>{{ $vv['second_level_drill'] }}</td>
                            <td>{{ $vv['thirdlevel_drill'] }}</td>
                            <td>{{ $vv['acpt'] }}</td>
                            @endif


                            @if (
                            (Auth::user()->parent_client == 23 && $processs == 160) ||
                            $processs == 175 ||
                            $processs == 180 ||
                            $processs == 181 ||
                            $processs == 183 ||
                            $processs == 185 ||
                            $processs == 192 ||
                            $processs == 193 ||
                            $processs == 210 || $processs == 211 ||$processs == 215 || $processs == 220)
                            <td>{{ $vv['confidence_score'] }}</td>
                            <td>{{ $vv['customer_stt_1'] }}</td>
                            <td>{{ $vv['customer_stt2'] }}</td>
                            <td>{{ $vv['customer_stt_3'] }}</td>
                            <td>{{ $vv['customer_stt_4'] }}</td>
                            <td>{{ $vv['customer_stt_5'] }}</td>
                            <td>{{ $vv['sent_to_bot_or_not'] }}</td>
                            <td>{{ $vv['call_disconnected_by'] }}</td>
                            <td>{{ $vv['disconnected_status'] }}</td>
                            <td>{{ $vv['gen_ai'] }}</td>
                            <td>{{ $vv['variable'] }}</td>
                            <td>{{ $vv['call_stage'] }}</td>

                            @if ($processs == 183)
                            <td>{{ $vv['bot_disposition_as_per_logic'] }}</td>
                            @else
                            <td>{{ $vv['bot_disposition_as_per_logic'] }}</td>
                            @endif
                            @endif


                            @if (Auth::user()->parent_client == 23)
                            <td>{{ $vv['recording_link'] ?? 'NA' }}</td>
                            <td>{{ $vv['call_count'] ?? 'NA' }}</td>
                            <td>{{ $vv['hot_leads'] ?? 'NA' }}</td>
                            <td>{{ $vv['hot_leads_reason'] ?? 'NA' }}</td>
                            <td>{{ $vv['gender_identification'] ?? 'NA' }}</td>
                            <td>{{ $vv['bot_identification_on_gender'] ?? 'NA' }}</td>
                            <td>{{ $vv['customer_voice'] ?? 'NA' }}</td>
                            @endif

                            @if ($processs == 173)
                            <td>{{ $vv['intervention_in_call'] ?? 'NA' }}</td>
                            <td>{{ $vv['intervention_drop_down'] ?? 'NA' }}</td>
                            <td>{{ $vv['agent_intervenses_count'] ?? 'NA' }}</td>
                            <td>{{ $vv['agent_intervenes_reason'] ?? 'NA' }}</td>
                            <td>{{ $vv['info_given_by_sinior'] ?? 'NA' }}</td>
                            <td>{{ $vv['intervention_remarks'] ?? 'NA' }}</td>
                            @endif

                            @if ($processs == 191 || $processs == 208 ||  $processs == 286)
                            <td>{{ $vv['audit_type'] }}</td>
                            @endif

                            @php($total_scored = 0)
                            @php($total_scorable = 0)
                            @php($is_critical = 0)
                            @foreach ($vv['audit'] as $kk => $vv)
                            @foreach ($vv as $kkb => $vvb)
                            <?php if ($vvb['observation'] == 'Critical') {
                                $is_critical = 1;
                            } ?>
                            <td>{{ $vvb['observation'] }}</td>
                            <td>{{ $vvb['scored'] }}</td>
                            <td>{{ $vvb['scorable'] }}</td>
                            <td>
                                {{ $vvb['reason_type'] == 0 ? '' : $vvb['reason_type'] }}
                            </td>

                            <?php
                            $string = str_replace(' ', '-', $vvb['reason']); // Replaces all spaces with hyphens.
                            $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
                            $string = preg_replace('/-+/', ' ', $string);
                            ?>

                            <td>{{ strip_tags($string) }}</td>



                            <?php
                            $string = str_replace(' ', '-', $vvb['remark']); // Replaces all spaces with hyphens.
                            $string = preg_replace('/[^,"":().A-Za-z0-9-\/-]/', '', $string);
                            $string = preg_replace('/-+/', ' ', $string);
                            ?>

                            {{-- <td>{{ strip_tags($string) }}</td> --}}
                            <td>{{ $vvb['remark'] }}</td>



                            @php($total_scored += $vvb['scored'])
                            @php($total_scorable += $vvb['scorable'])
                            @endforeach
                            @endforeach
                            <td><?php if ($is_critical == 1) {
                                    echo 0;
                                } else {
                                    if ($total_scored >= $total_scorable) {
                                        echo $total_scorable;
                                    } else {
                                        echo $total_scored;
                                    }
                                } ?></td>
                            <td><?php if ($total_scorable > 100) {
                                    echo 100;
                                } else {
                                    echo $total_scorable;
                                } ?></td>
                </tr>
                @endforeach
            </tbody>
            @endif
        </table>
    </div>
</div>
@endsection

@section('js')
{!! Html::script('assets/vendors/custom/datatables/datatables.bundle.js') !!}

<script>
    "use strict";
    var KTDatatablesBasicScrollable = function() {

        var initTable1 = function() {
            var table = $('#kt_table_1');

            // begin first table
            table.DataTable({
                scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                columnDefs: [],

                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Raw Dump With Audit Report'
                }, {
                    extend: 'pdfHtml5',
                    title: 'Raw Dump With Audit Report'
                }, {
                    extend: 'print',
                    title: 'Raw Dump With Audit Report'
                }, {
                    extend: 'copyHtml5',
                    title: 'Raw Dump With Audit Report'
                }, {
                    extend: 'csvHtml5',
                    title: 'Raw Dump With Audit Report'
                }],
            });
        };

        var initTable2 = function() {
            var table = $('#kt_table_2');

            // begin second table
            table.DataTable({
                scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
			<'row'<'col-sm-12'tr>>
			<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,

                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Raw Dump With Audit Report'
                }, {
                    extend: 'pdfHtml5',
                    title: 'Raw Dump With Audit Report'
                }, {
                    extend: 'print',
                    title: 'Raw Dump With Audit Report'
                }, {
                    extend: 'copyHtml5',
                    title: 'Raw Dump With Audit Report'
                }, {
                    extend: 'csvHtml5',
                    title: 'Raw Dump With Audit Report'
                }],
                bSort: false,
                columnDefs: [],
            });
        };

        return {

            //main function to initiate the module
            init: function() {
                initTable1();
                initTable2();
            },

        };

    }();

    jQuery(document).ready(function() {
        KTDatatablesBasicScrollable.init();
    });
    jQuery(document).ready(function() {
        KTDatatablesExtensionButtons.init();
    });
</script>
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
@endsection
@section('css')
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
<script>
    function show() {
        document.getElementById("requested").style.display = "block";
    }
</script>