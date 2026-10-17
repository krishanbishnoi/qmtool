@extends('layouts.app')

@section('sh-title')
    Report
@endsection

@section('sh-detail')
    QC Report Parameter wise
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
                    <form method="post" action="{{ route('qc_report_parameters') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <select class="form-control" id="clients" name="client" required="required">
                                    <option value="0">Select a Client</option>
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

                            <div class="col-md-3 mb-3">
                                <input type="submit" style="width: 100px;" class="btn btn-outline-brand d-block"
                                    value="Search">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
                    <tr>
                        <th>#</th>
                        <th>Call Id</th>
                        <th>Audit Date</th>
                        <th>Audit id</th>
                        <th>Partner Name</th>
                        <th>Process Name</th>
                        <th>Auditor Name</th>
                        <th>Done BY QC </th>
                        <th>Overall Remarks BY QC</th>
                        <th>Overall Before QC</th>
                        <th>Overall After QC</th>
                        <th>QC Date</th>
                        <th> Defect Counts</th>
                        <th>QC Defect Sub Parameters Name</th>
                        <th>Before QC With Fatal SubPara Wise</th>
                        <th>After QC With Fatal Score</th>
                        <th>Fatal Check</th>
                        <th>QC Remarks (After made changes in any parameter)</th>
                        <th>Before Selected Observation</th>
                        <th>After Selected Observation</th>

                    </tr>
                </thead>
                <tbody>
                    @php
                        $previous_call_id = null;
                        $previous_audit_id = null;
                        $count = 1;
                        // $keys = $count;
                    @endphp
                    @foreach ($newData as $key => $qcData)
                        @php

                            $current_call_id = $qcData['call_id'];
                            $current_audit_id = $qcData['audit_id'];
                        @endphp
                        <tr>
                            @if ($current_call_id !== $previous_call_id)
                                <td>{{ $count }}</td>
                                @php
                                    $count++; // Increment count for the next call_id
                                @endphp
                            @else
                                <td>&nbsp;</td>
                            @endif


                            <td>
                                @if ($current_call_id !== $previous_call_id)
                                    {{ $current_call_id }}
                                @else
                                    &nbsp;
                                @endif
                            </td>
                            <td>
                                @if ($current_call_id !== $previous_call_id)
                                    {{ $qcData['audit_date'] }}
                                @else
                                    &nbsp;
                                @endif
                            </td>
                            <td>
                                @if ($current_call_id !== $previous_call_id)
                                    {{ $qcData['audit_id'] }}
                                @else
                                    &nbsp;
                                @endif
                            </td>

                            <td>
                                @if ($current_call_id !== $previous_call_id)
                                    {{ $qcData['partner_name'] }}
                                @else
                                    &nbsp;
                                @endif
                            </td>
                            <td>
                                @if ($current_call_id !== $previous_call_id)
                                    {{ $qcData['process_name'] }}
                                @else
                                    &nbsp;
                                @endif
                            </td>
                            <td>
                                @if ($current_call_id !== $previous_call_id)
                                    {{ $qcData['name'] }}
                                @else
                                    &nbsp;
                                @endif
                            </td>
                            <td>
                                @if ($current_call_id !== $previous_call_id)
                                    @php
                                    
                                        $qc_id = $qcData['qc_id'];
                                        $qc = isset($qc_id) ? App\User::select('email')->where('id', $qc_id)->first() : null;
                                    @endphp
                                    {{ $qc ? $qc->email : '-' }}
                                @else
                                    &nbsp;
                                @endif
                            </td>
                            
                            <td>
                                @if ($current_audit_id !== $previous_audit_id)
                                    {{ $qcData['qc_comment'] }}
                                @else
                                    &nbsp;
                                @endif
                            </td>
                            <td>
                                @if ($current_audit_id !== $previous_audit_id)
                                    {{ $qcData['Before_Overall_qc'] }}
                                @else
                                    &nbsp;
                                @endif
                            </td>
                            <td>
                                @if ($current_audit_id !== $previous_audit_id)
                                    {{ $qcData['qc_revised_score_with_fatal'] }}
                                @else
                                    &nbsp;
                                @endif
                            </td>


                            <td>
                                @if ($current_audit_id !== $previous_audit_id)
                                    {{ $qcData['qc_date'] }}
                                @else
                                    &nbsp;
                                @endif
                            </td>
                            <td>
                                @if ($current_audit_id !== $previous_audit_id)
                                    {{ $qcData['defectCount'] }}
                                @else
                                    &nbsp;
                                @endif
                            </td>
                            <td>{{ $qcData['sub_parameter'] }}</td>
                            <td>{{ $qcData['before_qc_with_fatal'] ?? '' }}</td>
                            <td>{{ $qcData['score'] ?? '' }}</td>
                            <td>
                                @if ($qcData['is_critical'] !== $qcData['before_is_critical'])
                                    <div>{{ $qcData['is_critical'] == 0 ? 'Not Fatal' : 'Fatal' }}</div>
                                @else
                                    <div>Not Impacted Fatal</div>
                                @endif
                            </td>

                            <td>{{ $qcData['qc_to_qa_feedback'] ?? '-' }}</td>
                            <td>{{ $qcData['before_selected'] ?? '-' }}</td>
                            <td>{{ $qcData['after_qc_selected'] ?? '-' }}</td>
                        </tr>
                        @php
                            $previous_call_id = $current_call_id;
                            $previous_audit_id = $current_audit_id;
                        @endphp
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
    {!! Html::script('assets/vendors/custom/datatables/datatables.bundle.js') !!}

    <script>
        $(document).ready(function() {

            $.ajax({
                type: "GET",
                url: "{{ url('/report/get_rdr_client_list') }}",
                success: function(response) {
                    if (response.status == 200) {
                        var clientsDropdown = $('#clients');
                        clientsDropdown.empty(); // Clear any existing options
                        clientsDropdown.append('<option value="0">Select a Client</option>');
                        $.each(response.data, function(id, name) {
                            clientsDropdown.append('<option value="' + id + '">' + name +
                                '</option>');
                        });
                    } else {
                        console.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: " + status + " " + error);
                }
            });

        });
    </script>
    <script>
        "use strict";
        var KTDatatablesBasicScrollable = function() {

            var initTable1 = function() {
                var table = $('#kt_table_1');

                // begin first table
                table.DataTable({
                    // scrollY: '50vh',
                    scrollX: true,
                    scrollCollapse: true,
                    columnDefs: [],

                    buttons: [{
                            extend: 'excelHtml5',
                            title: 'QC Report',
                            customize: function(xlsx) {
                                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                $('row c', sheet).attr('s', '2'); // Align text center for all cells
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'QC Report'
                        },
                        {
                            extend: 'print',
                            title: 'QC Report'
                        },
                        {
                            extend: 'copyHtml5',
                            title: 'QC Report'
                        },
                        {
                            extend: 'csvHtml5',
                            title: 'QC Report'
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
                            title: 'QC Report',
                            customize: function(xlsx) {
                                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                $('row c', sheet).attr('s', '2'); // Align text center for all cells

                                // Define Excel styles for center alignment
                                var styles = xlsx.xl['styles.xml'];
                                $('cellXfs xf', styles).attr('applyAlignment', 1);
                                $('cellXfs xf alignment', styles).attr('horizontal', 'center');
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'QC Report'
                        },
                        {
                            extend: 'print',
                            title: 'QC Report'
                        },
                        {
                            extend: 'copyHtml5',
                            title: 'QC Report'
                        },
                        {
                            extend: 'csvHtml5',
                            title: 'QC Report'
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
