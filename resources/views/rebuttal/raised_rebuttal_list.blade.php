@extends('layouts.app')

@section('sh-title')
Rebuttal
@endsection

@section('sh-detail')
Raised
@endsection

@section('main')

<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head kt-portlet__head--lg">
		<div class="kt-portlet__head-label">
			<span class="kt-portlet__head-icon">
				<i class="kt-font-brand flaticon2-line-chart"></i>
			</span>
			<h3 class="kt-portlet__head-title">
				List
			</h3>
		</div>
		<!-- <div class="kt-portlet__head-toolbar">
			<div class="kt-portlet__head-wrapper">
				<div class="kt-portlet__head-actions">
					<a href="{{url('skill/create')}}" class="btn btn-brand btn-elevate btn-icon-sm">
						<i class="la la-plus"></i>
						New Record
					</a>
				</div>
			</div>
		</div> -->
	</div>
	<div class="kt-portlet__body">

		<!--begin: Datatable -->
		<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
			<thead>
				<tr>
						<th title="Field #1">#</th>
						<th title="Field #1">Status</th>
						<th title="Field #2">
							Call Id
						</th>
						<th title="Field #2">
							Audit Date
						</th>
						<th title="Field #2">
							Auditor Name
						</th>
						<th title="Field #2">
							Process
						</th>
						<th title="Field #2">
							LOB
						</th>
						<th title="Field #3">
							Parameter
						</th>
						<th title="Field #3">
							Sub Parameter
						</th>
						<th title="Field #4">
							Remark
						</th>
						<th title="Field #4">
							Raised At
						</th>
						<th title="Field #4">
							Detail
						</th>
						
				</tr>
			</thead>
			<tbody>
				@foreach($data as $row)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td>
						@if ($row->re_rebuttal_id == null)
							Rebuttal
						@else
							Re-Rebuttal
						@endif
					</td>
					<td>{{$row->raw_data->call_id}}</td>
					<td>{{$row->audit_data->audit_date}}</td>
					@php
						$process = \App\Process::find($row->raw_data->process_id);
						$auditor_name = \App\User::find($row->audit_data->audited_by_id)
					@endphp
					<td>{{$auditor_name->name}}</td>
					
					<td>{{$process->name }}</td>
					<td>{{$row->raw_data->lob}}</td>
					<td>{{$row->parameter->parameter}}</td>
					<td>{{$row->sub_parameter->sub_parameter}}</td>
					<td>{{$row->remark}}</td>
					<td>{{$row->created_at}}</td>
					<td nowrap>
                        <div style="display: flex;">
                        <a href="{{url('rebuttal_status/'.Crypt::encrypt($row->id))}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Update Status">
                        	<i class="la la-edit"></i>
                        </a>
                    </div>

                </td>
            	</tr>
            @endforeach

        </tbody>
    </table>

    <!--end: Datatable -->
</div>
</div>
@endsection
@section('css')
@include('shared.table_css')
@endsection
@section('js')
{!! Html::script('assets/vendors/custom/datatables/datatables.bundle.js')!!}

<script>


"use strict";
var KTDatatablesExtensionButtons = function() {

	var initTable1 = function() {

		// begin first table
		var table = $('#kt_table_1').DataTable({
			responsive: true,
			// Pagination settings
			dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
			<'row'<'col-sm-12'tr>>
			<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,

			buttons: [
				{
					extend: 'excelHtml5',
					title: 'Raised Rebuttal List'
				},
				{
					extend: 'pdfHtml5',
					title: 'Raised Rebuttal List'
				},
				{
					extend: 'print',
					title: 'Raised Rebuttal List'
				},
				{
					extend: 'copyHtml5',
					title: 'Raised Rebuttal List'
				},
				{
					extend: 'csvHtml5',
					title: 'Raised Rebuttal List'
				}
			]

			
			
		});

	};

	var initTable2 = function() {

		// begin first table
		var table = $('#kt_table_2').DataTable({
			responsive: true,
			scrollY: '50vh',
			scrollX: true,
			scrollCollapse: true,
			buttons: [
				{
					extend: 'excelHtml5',
					title: 'Raised Rebuttal List'
				},
				{
					extend: 'pdfHtml5',
					title: 'Raised Rebuttal List'
				},
				{
					extend: 'print',
					title: 'Raised Rebuttal List'
				},
				{
					extend: 'copyHtml5',
					title: 'Raised Rebuttal List'
				},
				{
					extend: 'csvHtml5',
					title: 'Raised Rebuttal List'
				}
			],
			"order": [],
			"pageLength":100,
		});

		$('#export_print').on('click', function(e) {
			e.preventDefault();
			table.button(0).trigger();
		});

		$('#export_copy').on('click', function(e) {
			e.preventDefault();
			table.button(1).trigger();
		});

		$('#export_excel').on('click', function(e) {
			e.preventDefault();
			table.button(2).trigger();
		});

		$('#export_csv').on('click', function(e) {
			e.preventDefault();
			table.button(3).trigger();
		});

		$('#export_pdf').on('click', function(e) {
			e.preventDefault();
			table.button(4).trigger();
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
	KTDatatablesExtensionButtons.init();
});
</script>
@endsection