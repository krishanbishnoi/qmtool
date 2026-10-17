@extends('layouts.app')

@section('sh-title')
Report
@endsection

@section('sh-detail')
Agent Feedback Report
@endsection

@section('main')
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Search / Filter
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        {{ Form::open(['url' => 'report/agent_feedback_report']) }}
        @csrf
        <div class="form-group row">
            @if (Auth::user()->hasRole('process-owner'))
            <div class="col-lg-3">
                {{ Form::select('client_id', $client_list, $client_id, ['class' => 'form-control']) }}
            </div>
            @endif
            <div class="row">
                <div class="col-sm-2">
                    <select class="form-control" name="partner" required="required" onchange="getLocation(this.value);">
                        <option value="0">Select a Partner</option>
                        @foreach ($all_partners as $partner)
                        :
                        <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                        @endforeach;
                        <?php
                        if (Auth::user()->hasRole('client') || Auth::user()->hasRole('process-owner')) {
                        ?>
                            <option value="all">All</option>

                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="form-control" name="lob" id="lob" required>
                        <option value="0">Select LOB</option>
                        <option value="all">All</option>

                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="form-control" name="location" id="location" required>
                        <option value="0">Select a Location</option>
                        <option value="all">All</option>

                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="form-control" name="process" id="process" onchange="getCycle(this.value);"
                        required>
                        <option value="0">Select a Process</option>
                        <option value="all">All</option>

                        <option></option>
                    </select>
                </div>

                <div class="col-md-3 mb-3 ">
                    <input type="text" readonly class="form-control" id="kt_datepicker_1" name="start_date"
                        placeholder="Start Date" required="required">
                </div>
                <div class="col-md-3 mb-3">
                    <input type="text" readonly class="form-control" id="kt_datepicker_2" name="end_date"
                        placeholder="End Date" required="required">
                </div>

                {{-- <div class="col-sm-2">
						<select class="form-control" name="date"  id="audit_cycle" required="required" >
							<option value="0">Select Audit Cycle</option>
							<option ></option>
						</select>
					</div>	 --}}
                <div class="col-sm-2">
                    <input type="submit" style="width: 100px;" class="btn btn-outline-brand d-block" value="Search">
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>

@if ($data1)
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Report :- <span class="text-danger">Agent Feedback Report</span>
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">

        <!--begin: Datatable -->
        <!-- <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_2">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Audit Date</th>
                            <th>Call ID</th>
                            <th>Partner</th>
                            <th>Process</th>
                            <th>Agent name</th>
                            <th>Location</th>
                            <th>LoB</th>
                            <th>Feedback Shared Status</th>
                            <th>Feedback Date</th>
                            <th>Feedback Remarks</th>
                            @if (Auth::user()->parent_client == 23)
                                <th>feedback ViEW DATE</th>
                                <th>Quality Score</th>
                                <th>TAT(days)</th>
                                <th>Within TAT</th>
                                <th>emp_id</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data1 as $vv)
                            @php
                                $auditDate = \Carbon\Carbon::parse($vv['audit_date']);
                                $feedbackViewDate  = \Carbon\Carbon::parse($vv['audit_view_date']);   
                             
                                // if ($auditDate->dayOfWeek === \Carbon\Carbon::FRIDAY) {
                                //     $feedbackViewDate = $auditDate->copy()->addDays(3);
                                // } elseif ($auditDate->dayOfWeek === \Carbon\Carbon::SATURDAY) {
                                //     $feedbackViewDate = $auditDate->copy()->addDays(2);
                                // } else {
                                //     $feedbackViewDate = $auditDate->copy()->addDay();
                                // }
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $vv['audit_date'] }}</td>
                                <td>{{ $vv['data']->call_id }}</td>
                                <td>{{ $vv['partner'] }}</td>
                                <td>{{ $vv['process'] }}</td>
                                <td>{{ $vv['data']->agent_name }}</td>
                                <td>{{ $vv['data']->location }}</td>
                                <td>{{ $vv['data']->lob }}</td>
                                <td>{{ $vv['feedback_shared_status'] == 1 ? 'Feedback Shared' : 'Feedback Not Shared' }}
                                </td>
                                <td>{{ $vv['feedback_shared_date'] ?? '-' }}</td>
                                <td>{{ $vv['feedback_remarks'] ?? '-' }}</td>
                                @if (Auth::user()->parent_client == 23)
                                    <td>{{ $feedbackViewDate->format('Y-m-d') }}</td>
                                    <td>{{ $vv['with_fatal_score_per'] ?? '-' }}</td>
                                    @php
                                        $feedbackSharedDate = $vv['feedback_shared_date']
                                            ? \Carbon\Carbon::parse($vv['feedback_shared_date'])
                                            : null;
                                        $diffInDays = $feedbackSharedDate
                                            ? $feedbackSharedDate->diffInDays($feedbackViewDate)
                                            : null;
                                        if (
                                            $feedbackSharedDate &&
                                            $feedbackSharedDate->diffInHours($feedbackViewDate) % 24 != 0
                                        ) {
                                            $diffInDays++;
                                        }

                                        $rebuttal_data = \App\Rebuttal::where('raw_data_id', $vv['data']->id)->get();

                                    @endphp
                                    <td>{{ $diffInDays }}</td>
                                    <td>
                                        @if ($feedbackSharedDate && $diffInDays !== null)
                                            @if ($rebuttal_data->count() > 0)
                                                Rebuttal Affected
                                            @elseif ($diffInDays <= 3)
                                                Within TAT
                                            @else
                                                Not in TAT
                                            @endif
                                        @else
                                            @if ($rebuttal_data->count() > 0)
                                                Rebuttal Affected
                                            @else
                                                No feedback date
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ $vv['data']->emp_id ?? '-' }}</td>


                                    {{-- @endif --}}
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table> -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_2">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Audit Date</th>
                    <th>Call ID</th>
                
                    <th>Partner</th>
                    <th>Process</th>
                    <th>Agent name</th>

                    <th>Location</th>
                    <th>LoB</th>
                    <th>Feedback Shared Status</th>
                    <th>Feedback Date</th>
                    <th>Feedback Remarks</th>
                    @if (Auth::user()->parent_client == 23)
                    <th>feedback ViEW DATE</th>
                    <th>Quality Score</th>
                    <th>TAT(days)</th>
                    <th>Within TAT</th>
                    <th>emp_id</th>
                    @endif
                    @if ($showDynamicFields)
                    @foreach ($requiredFields as $field)
                    <th>{{ ucwords($field) }}</th>
                    @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($data1 as $vv)
                @php
                $auditDate = \Carbon\Carbon::parse($vv['audit_date']);
                $feedbackViewDate = \Carbon\Carbon::parse($vv['audit_view_date']);
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $vv['audit_date'] }}</td>
                    <td>{{ $vv['data']->call_id }}</td>
                   
                    <td>{{ $vv['partner'] }}</td>
                    <td>{{ $vv['process'] }}</td>
                    <td>{{ $vv['data']->agent_name }}</td>
                    <td>{{ $vv['data']->location }}</td>
                    <td>{{ $vv['data']->lob }}</td>
                    <td>{{ $vv['feedback_shared_status'] == 1 ? 'Feedback Shared' : 'Feedback Not Shared' }}
                    </td>
                    <td>{{ $vv['feedback_shared_date'] ?? '-' }}</td>
                    <td>{{ $vv['feedback_remarks'] ?? '-' }}</td>
                    @if (Auth::user()->parent_client == 23)
                    <td>{{ $feedbackViewDate->format('Y-m-d') }}</td>
                    <td>{{ $vv['with_fatal_score_per'] ?? '-' }}</td>
                    @php
                    $feedbackSharedDate = $vv['feedback_shared_date']
                    ? \Carbon\Carbon::parse($vv['feedback_shared_date'])
                    : null;
                    $diffInDays = $feedbackSharedDate
                    ? $feedbackSharedDate->diffInDays($feedbackViewDate)
                    : null;
                    if (
                    $feedbackSharedDate &&
                    $feedbackSharedDate->diffInHours($feedbackViewDate) % 24 != 0
                    ) {
                    $diffInDays++;
                    }

                    $rebuttal_data = \App\Rebuttal::where('raw_data_id', $vv['data']->id)->get();

                    @endphp
                    <td>{{ $diffInDays }}</td>
                    <td>
                        @if ($feedbackSharedDate && $diffInDays !== null)
                        @if ($rebuttal_data->count() > 0)
                        Rebuttal Affected
                        @elseif ($diffInDays <= 3)
                            Within TAT
                            @else
                            Not in TAT
                            @endif
                            @else
                            @if ($rebuttal_data->count() > 0)
                            Rebuttal Affected
                            @else
                            No feedback date
                            @endif
                            @endif
                    </td>
                    <td>{{ $vv['data']->emp_id ?? '-' }}</td>
                    @endif

                    @if ($showDynamicFields)
                    @foreach ($requiredFields as $field)
                    <td>{{ $vv['dynamic_fields'][$field] ?? '-' }}</td>
                    @endforeach
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
@section('js')
<script>
    function getLocation(val) {

        var base_url = window.location.origin;
        if (val != 0) {
            $.ajax({
                type: "GET",
                url: base_url + "/dashboard/get_partner_locations1/" + val,
                success: function(Data) {
                    $("#location").html(Data);
                }
            });

            $.ajax({
                type: "GET",
                url: base_url + "/dashboard/get_partner_process1/" + val,
                success: function(Data) {
                    $("#process").html(Data);
                }
            });

            $.ajax({
                type: "GET",
                url: base_url + "/dashboard/get_partner_lob/" + val,
                success: function(Data) {
                    $("#lob").html(Data);
                }
            });
        }
    }

    function getCycle(val) {
        if (val == '%') {
            val = 'All';
        }
        var base_url = window.location.origin;
        if (val != 0) {
            $.ajax({
                type: "GET",
                url: base_url + "/dashboard/get_partner_audit_cycle/" + val,
                success: function(Data) {
                    console.error(Data);
                    $("#audit_cycle").html(Data);
                }
            });
        }
    }
</script>
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-daterangepicker.js" type="text/javascript">
</script>
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
                        title: 'Agent Feedback Report'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Agent Feedback Report'
                    },
                    {
                        extend: 'print',
                        title: 'Agent Feedback Report'
                    },
                    {
                        extend: 'copyHtml5',
                        title: 'Agent Feedback Report'
                    },
                    {
                        extend: 'csvHtml5',
                        title: 'Agent Feedback Report'
                    }
                ],
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
                        title: 'Agent Feedback Report'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Agent Feedback Report'
                    },
                    {
                        extend: 'print',
                        title: 'Agent Feedback Report'
                    },
                    {
                        extend: 'copyHtml5',
                        title: 'Agent Feedback Report'
                    },
                    {
                        extend: 'csvHtml5',
                        title: 'Agent Feedback Report'
                    }
                ],
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
@endsection
@section('css')
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection