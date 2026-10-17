@extends('layouts.app')

@section('sh-title')
Report
@endsection

@section('sh-detail')
QC Report
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
												<th>Brand</th>
												<th>Language by QA</th>
												<th>Circle Name</th>
												<th>Call Id</th>
												<th>Auditor Name</th>
												<th>QC Name</th>
												<th>Participant Phone Number</th>
												<th>Agent User Department (Partner)</th>
												<th>Evaluation Creation Date</th>
												<th>Customer LOB (Pre/Post)</th>
												<th>Segment Date & Start Time</th>
												<th>Segment Duration</th>
												<th>Agent CRM ID</th>
												<th>Evaluation Score</th>
												<th>Reason for Call (CRM Sub Type)</th>
												<th>DFF 1</th>
												<th>Overall Remarks</th>
												<th>QC Date</th>
												<th>QC Date with time</th>
												<th>QC Defect Parameter Name</th>
												<th>QC Defect Count</th>
												@if (Auth::user()->hasRole('client'))
                                                <th>After Qc</th>
                                                @else
                                                <th>Before Qc With Fatal</th>
                                                <th>After Qc  With Fatal</th>
                                                {{-- <th>Before Qc  Without Fatal</th>
                                                <th>After Qc  Without Fatal</th> --}}
                                                @endif
												<th>Variance % (with fatal)</th>
												{{-- <th>Variance % (without fatal)</th> --}}
												<th>QC Remarks (After made changes in any parameter)</th>
												<th>QC to QA feedback</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
											@foreach($data as $kk=>$vv)
											<tr>
												<td>{{$loop->iteration}}</td>
												<td>{{$vv['brand']}}</td>
												<td>{{$vv['language_2']}}</td>
												<td>{{$vv['circle']}}</td>
												<td>{{$vv['call_id']}}</td>
												<td>{{$vv['auditor']}}</td>

												@php
													  $qc_name = App\User::where('id',$vv['qc_id'])->value('email');
												@endphp
												 <td>{{ $qc_name ?? 'NA' }}</td>

												<td>{{$vv['customer_phone']}}</td>
												<td>{{$vv['partner']}}</td>
												<td>{{$vv['evaluation_date']}}</td>
												<td>{{$vv['lob']}}</td>
												<td>{{$vv['call_time']}}</td>
												<td>{{$vv['call_duration']}}</td>
												<td>{{$vv['agent_id']}}</td>
												<td>{{$vv['evaluation_score']}}</td>
												<td>{{$vv['call_sub_type']}}</td>
												<td>{{$vv['dff_1']}}</td>
												<td>{{$vv['overall_remark']}}</td>
												<td>{{$vv['qc_date']}}</td>
												<td>{{$vv['updated_at']}}</td>
												<td>{{$vv['qc_deffect_parameter']}}</td>
												<td>{{$vv['qc_deffect_parameter_count']}}</td>
												@if (Auth::user()->hasRole('client'))
                                                <td>{{$vv['qc_revised_score_without_fatal']}}</td>
                                                @else
                                                {{-- <td>{{$vv['with_fatal_score_per'] }}</td> --}}
                                                <td>{{$vv['with_fatal_score_real'] }}</td>
                                                <td>{{$vv['with_fatal_score_per'] }}</td>
                                                {{-- <td>{{$vv['without_fatal_score_real'] }}</td>
                                                <td>{{$vv['qc_revised_score_without_fatal']}}</td> --}}
                                                @endif
												<td>{{$vv['variance']}}</td>
												{{-- <td>{{$vv['variance_without_fatal']}}</td> --}}
												<td>{{$vv['qc_remark']}}</td>
												<td>{{$vv['qc_to_qa_feedback']}}</td>
												<td>{{$vv['status']}}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>

							@endsection

@section('js')

<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
{!! Html::script('assets/vendors/custom/datatables/datatables.bundle.js')!!}

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
			
			buttons: [
				{
					extend: 'excelHtml5',
					title: 'QC Report'
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
			
			buttons: [
				{
					extend: 'excelHtml5',
					title: 'QC Report'
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
			bSort : false,
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
