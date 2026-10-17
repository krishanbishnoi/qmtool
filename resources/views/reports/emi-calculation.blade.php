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
                    <form method="post" action="{{ route('emi-cal') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-3 mb-3 ">
                                <input type="text" class="form-control" value = "{{$data['approvedLoanAmount'] ?? ''}}"  id="" name="emiAmount" placeholder="Enter Emi Amount" required="required">
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="form-control" id="irr" name="irr" required="required">
                                    <option value="0">Select IRR</option>
                                    @foreach($tenures as $tenure)
                                        <option value ="{{ $tenure }}" {{ $tenure == $selectedIrr ? 'selected' : '' }}>{{$tenure}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="form-control" id="tenure" name="tenure" required="required">
                                    <option value="0">Select Tenure</option>
                                    @foreach($longTenure as $tenure)
                                        <option  value="{{ $tenure }}" {{ $tenure == $selectedTenure ? 'selected' : '' }}>{{$tenure}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="form-control" id="healthInsurance" name="healthInsurance" required="required">
                                    <option value="0">Select Health Insurance</option>
                                    <option value="1" {{ $selectedHealthInsurance == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $selectedHealthInsurance == 0 ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="form-control" id="lifeInsurance" name="lifeInsurance" required="required">
                                    <option value="0" {{ $selectedLifeInsurance == 1 ? 'selected' : '' }}>Select Life Insurance</option>
                                    <option value="1" {{ $selectedLifeInsurance == 0 ? 'selected' : '' }}>Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input type="submit" style="width: 100px;" class="btn btn-outline-brand d-block" value="Search">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if(isset($data['approvedLoanAmount']))
    <div class="kt-portlet__body">

		<!--begin: Datatable -->
        <div class="kt-portlet kt-portlet--mobile">
		    <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
			    <thead>
				    <tr>
						<th>Approved Loan Amount</th>
						<th>Life Insurance</th>
						<th>Health Insurance</th>
						<th>Life Insurance Amount</th>
						<th>Health Insurance Amount</th>
						<th>Processing Fees</th>
						<th>Total Gross-up Amount</th>
						<th>Total Loan Amount</th>
						<th>IRR</th>
						<th>Tenure</th>
						<th>ROI per Annum</th>
						<th>ROI per Month</th>
						<th>EMI Amount</th>
						<th>Total Repayment Amount</th>
						<th>Total Interest Amount</th>
				    </tr>
			    </thead>
			    <tbody>
				<tr>
					<td>{{ $data['approvedLoanAmount'] }}</td>
					<td>{{ $data['lifeInsurance'] ? 'Yes':'No' }}</td>
					<td>{{ $data['healthInsurance'] ? 'Yes':'No' }}</td>
					<td>{{ $data['lifeInsurance'] }}</td>
					<td>{{ $data['healthInsurance'] }}</td>
					<td>{{ $data['processingFees'] }}</td>
					<td>{{ $data['grossUpAmount'] }}</td>
					<td>{{ $data['totalLoanAmount'] }}</td>
					<td>{{ $data['irr'] }}</td>
					<td>{{ $data['tenure'] }}</td>
					<td>{{ $data['roiPerAnnum'] }}</td>
					<td>{{ $data['roiPerMonth'] }}</td>
					<td>{{ $data['emiAmount'] }}</td>
					<td>{{ $data['repaymentAmount'] }}</td>
					<td>{{ $data['interestAmount'] }}</td>
            	</tr>
            </tbody>
        </table>
    </div>
    @endif
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
