@extends('layouts.app')

@section('sh-title')
Raw Data Purging
@endsection

@section('sh-detail')
log status
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
	</div>
	<div class="kt-portlet__body">

		<!--begin: Datatable -->
		<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
			<thead>
				<tr>
					<th title="Field #1">#</th>
					<th>Timestamp</th>
					<th>Raw dump from date</th>
					<th>Raw dump to date</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>1</td>
					<td>2019-12-08 11:00:00</td>
					<td>2019-09-01</td>
					<td>2019-09-30</td>
					<td>Deleted</td>
				</tr>
				<tr>
					<td>2</td>
					<td>2019-12-08 11:00:00</td>
					<td>2019-10-01</td>
					<td>2019-10-31</td>
					<td>Deleted</td>
				</tr>
				<tr>
					<td>3</td>
					<td>2019-12-08 11:00:00</td>
					<td>2019-11-01</td>
					<td>2019-11-30</td>
					<td>Deleted</td>
				</tr>
			</tbody>
		</table>
		<!--end: Datatable -->
	</div>
</div>
@endsection
@section('css')
@include('shared.table_css');
@endsection
@section('js')
@include('shared.table_js');
@endsection