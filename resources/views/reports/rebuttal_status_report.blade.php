@extends('layouts.app')

@section('sh-title')
    Report
@endsection

@section('sh-detail')
    All Audit Rebuttals Data
@endsection

@section('main')
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
                <div class="form-group row">
                    @if (Auth::user()->hasRole('process-owner'))
                        <div class="col-lg-3">
                            {{ Form::select('client_id', $client_list, $client_id, ['class' => 'form-control']) }}
                        </div>
                    @endif
                    <div class="col-lg-3">
                        <label>Select Start Date:</label>
                        <input type="text" readonly class="form-control" id="kt_datepicker_1" name="start_date"
                            required="required">
                    </div>
                    <div class="col-lg-3">
                        <label>Select End Date:</label>
                        <input type="text" readonly class="form-control" id="kt_datepicker_2" name="end_date"
                            required="required">
                    </div>
                    <div class="col-lg-3">
                        <button type="submit" class="btn btn-outline-brand"><i class="fa fa-search"></i> Get Data</button>
                    </div>
                </div>
            </form>
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
                <thead>
                    <tr align="center">
                        <th colspan="27">Basic Data</th>
                        <th colspan="5">Rebuttal</th>
                        <th colspan="5">Re-rebuttal</th>
						@if (Auth::user()->parent_client == 23)
						<th colspan="3">Final Status</th>
						@else
						<th colspan="2">Final Status</th>
                 		@endif                      
                        <th colspan="3">Revised Score (if Accepted)</th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Audit Date</th>
                        <th>Auditor</th>
                        <th>Partner</th>
                        <th>Location</th>
                        <th>Call Id</th>
                        <th>Agent Name</th>
                        <th>Emp. Id</th>
                        <th>DOJ</th>
                        <th>LOB</th>
                        <th>Language</th>
                        <th>Case Id</th>
                        <th>Call Time</th>
                        <th>Call Duration</th>
                        <th>Call Type</th>
                        <th>Call Sub Type</th>
                        <th>Call Disposition</th>
                        <th>Campaign Name</th>
                        @if($client_id != 23 || Auth::user()->parent_client != 23)
                        <th>Customer Name</th>
                        <th>Customer Phone</th>
                        @endif
                        <th>QRC</th>
                        <th>Overall Summary</th>
                        <th>With Fatal Score</th>
                        <th>Without Fatal Score</th>
                        <th>Parameter</th>
                        <th>Sub Parameter</th>
                        <th>Failure Reason</th>
                        <th>Auditor Remarks</th>
                        <th>Rebuttal Date</th>
                        <th>Partner Remarks</th>
                        @if (Auth::user()->parent_client == 14)
                            <th>Revert Date (ATL)</th>
                        @else
                            <th>Revert Date</th>
                        @endif
                        <th>Revert Remarks</th>
                        <th>Status</th>
                        <th>Re-rebuttal Date</th>
                        <th>Partner Remarks</th>
                        @if (Auth::user()->parent_client == 14)
                            <th>Revert Date(QTL)</th>
                        @else
                            <th>Revert Date</th>
                        @endif
                        <th>Revert Remarks</th>
                        <th>Status</th>
                        @if (Auth::user()->parent_client == 23)
                            <th>Final Status (Accepted/Rejected)</th>
                            <th>BOD</th>
                        @else
                            <th>Final Status (Accepted/Rejected)</th>
                        @endif
                        {{-- <th>Final Status (Accepted/Rejected/BOD)</th> --}}
                        <th>Score Revised (Y/N)</th>
                        <th>Score with FATAL</th>
                        <th>Score without FATAL</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $kk => $vv)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $vv['audit_date'] }}</td>
                            <td>{{ $vv['auditor'] }}</td>
                            <td>{{ $vv['partner'] }}</td>
                            <td>{{ $vv['location'] }}</td>
                            <td>{{ $vv['call_id'] }}</td>
                            <td>{{ $vv['agent_name'] }}</td>
                            <td>{{ $vv['emp_id'] }}</td>
                            <td>{{ $vv['doj'] }}</td>
                            <td>{{ $vv['lob'] }}</td>
                            <td>{{ $vv['language'] }}</td>
                            <td>{{ $vv['case_id'] }}</td>
                            <td>{{ $vv['call_time'] }}</td>
                            <td>{{ $vv['call_duration'] }}</td>
                            <td>{{ $vv['call_type'] }}</td>
                            <td>{{ $vv['call_sub_type'] }}</td>
                            <td>{{ $vv['disposition'] }}</td>
                            <td>{{ $vv['campaign_name'] }}</td>
                            @if($client_id != 23 || Auth::user()->parent_client != 23)
                            <td>{{ $vv['customer_name'] }}</td>
                            <td>{{ $vv['phone_number'] }}</td>
                            @endif
                            <td>{{ $vv['qrc'] }}</td>
                            <td>{{ $vv['overall_summary'] }}</td>
                            <td>{{ $vv['with_fatal_score'] }}</td>
                            <td>{{ $vv['without_fatal_score'] }}</td>
                            <td>{{ $vv['parameter'] }}</td>
                            <td>{{ $vv['sub_parameter'] }}</td>
                            <td>{{ $vv['failure_reason'] }}</td>
                            <td>{{ $vv['auditor_remark'] }}</td>
                            <td>{{ $vv['rebuttal_date'] }}</td>
                            <td>{{ $vv['partner_remark'] }}</td>
                            <td>{{ $vv['revert_date'] }}</td>
                            <td>{{ $vv['revert_remark'] }}</td>
                            @if (Auth::user()->parent_client == 23)
                            <td>
                            <?php 

                                if($vv['bod_status'] != 'not'){
                                    echo "BOD";
                                } else {
                                    echo $vv['status'];
                                }
                            ?>
                            </td>
                            @else
							<td>{{ $vv['final_status'] }}</td>
                            @endif
                            
                            <td>{{ $vv['re_rebuttal_date'] }}</td>
                            <td>{{ $vv['re_partner_remark'] }}</td>
                            <td>{{ $vv['re_revert_date'] }}</td>
                            <td>{{ $vv['re_revert_remark'] }}</td>
                            {{-- <td>{{ $vv['re_status'] }}</td>        --}}
                            
                            @if (Auth::user()->parent_client == 23)
                                @if (($vv['re_bod_status']) != 'not')
                                        <td>BOD</td>
                                @else
                                <td>{{ $vv['re_status'] }}</td>                                    
                                @endif					
                          
                            @else
                            <td>{{ $vv['re_status'] }}</td>
                            @endif
                            

                            @if (Auth::user()->parent_client == 23)
                                @if (($vv['re_bod_status']) != 'not' || $vv['bod_status'] != 'not'  )
                                        <td>BOD</td>
                                @else
                                <td>{{ $vv['final_status'] }}</td>                                    
                                @endif							
                                @if ($vv['re_bod_status'] != 'not' )
                                <td>{{ $vv['re_bod_status'] }}</td> 
								@elseif($vv['bod_status'] != 'not')
                                <td>{{ $vv['bod_status'] }}</td> 
                                @else
								<td>-</td> 
                                @endif
							@else
							<td>{{ $vv['final_status'] }}</td>
                            @endif
                            <td>{{ $vv['score_revised'] }}</td>
                            <td>{{ $vv['score_with_fatal'] }}</td>
                            <td>{{ $vv['score_without_fatal'] }}</td>
                        </tr>
                    @endforeach
            </table>
        </div>
    </div>
@endsection
@section('js')
    <script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>

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
                            title: 'Rebuttal Report'
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'Rebuttal Report'
                        },
                        {
                            extend: 'print',
                            title: 'Rebuttal Report'
                        },
                        {
                            extend: 'copyHtml5',
                            title: 'Rebuttal Report'
                        },
                        {
                            extend: 'csvHtml5',
                            title: 'Rebuttal Report'
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
                            title: 'Rebuttal Report'
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'Rebuttal Report'
                        },
                        {
                            extend: 'print',
                            title: 'Rebuttal Report'
                        },
                        {
                            extend: 'copyHtml5',
                            title: 'Rebuttal Report'
                        },
                        {
                            extend: 'csvHtml5',
                            title: 'Rebuttal Report'
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
