@extends('layouts.app_third')

<script src="jquery-3.5.1.js"></script>
@section('sh-title')
Randomizer Report
@endsection

@section('main')
<style>
table.dataTable thead .sorting:after,
table.dataTable thead .sorting:before,
table.dataTable thead .sorting_asc:after,
table.dataTable thead .sorting_asc:before,
table.dataTable thead .sorting_asc_disabled:after,
table.dataTable thead .sorting_asc_disabled:before,
table.dataTable thead .sorting_desc:after,
table.dataTable thead .sorting_desc:before,
table.dataTable thead .sorting_desc_disabled:after,
table.dataTable thead .sorting_desc_disabled:before {
  bottom: .5em;
}
</style>

	<div class="kt-portlet kt-portlet--mobile">
								<div class="kt-portlet__head">
									<div class="kt-portlet__head-label">
									<h3 class="kt-portlet__head-title">
									Report :- <span class="text-danger">Randomizer Report</span>
									</h3>
									</div>
								</div>
								<div class="kt-portlet__body">

									<!--begin: Datatable -->
									<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
										<thead>

											<tr>
												<th title="Field #1">#</th>
												<th title="Field #2">SR Number</th>
												<th title="Field #2">Agent ID</th>
												<th title="Field #3">Call ID</th>
												<th title="Field #4">Date of Interaction</th>
												<th title="Field #4">Call Duration</th>
												<th title="Field #2">Location</th>
												<th title="Field #2">Language</th>
												<th title="Field #5">Call Type</th>
												<th title="Field #5">Disposition</th>
												<th title="Field #6">Sub Disposition</th>
												<th title="Field #2">Campaign Name</th>
												<th title="Field #8">Hangup Details</th>
												<th title="Field #2">LOB</th>
												<th title="Field #2">TL</th>
												<th title="Field #2">DOJ</th>
												<th title="Field #7">Agent Name</th>
												<th title="Field #2">Customer Name</th>
												<th title="Field #2">QA System Registered Email</th>
												<th title="Field #2">Brand Name</th>
												<th title="Field #2">Circle</th>
												<th title="Field #8">Final Tagging</th>
												<th title="Field #2">Info 2</th>
												<th title="Field #2">Info 3</th>
												<th title="Field #2">Info 4</th>
												<th title="Field #2">Info 5</th>
											
											</tr>
										</thead>
										<tbody>
											@foreach($data as $kk=>$vv)
											<tr>
												<td>{{$loop->iteration}}</td>
												<td>{{$vv->phone_number}}</td>
												<td>{{$vv->agent_name}}</td>
												<td>{{$vv->call_id}}</td>
												<td>{{$vv->call_time}}</td>
												<td>{{$vv->call_duration}}</td>
												<td>{{$vv->location}}</td>
												<td>{{$vv->language}}</td>
												<td>{{$vv->call_type}}</td>
												<td>{{$vv->disposition}}</td>
												<td>{{$vv->call_sub_type}}</td>
												<td>{{$vv->campaign_name}}</td>
												<td>{{$vv->hangup_details}}</td>
												<td>{{$vv->lob}}</td>
												<td>{{$vv->tl}}</td>
												<td>{{$vv->doj}}</td>
												<td>{{$vv->emp_id}}</td>
												<td>{{$vv->customer_name}}</td>
												<td>{{$vv->emp_id}}</td>
												<td>{{$vv->brand_name}}</td>
												<td>{{$vv->circle}}</td>
												<td>{{$vv->info_1}}</td>
												<td>{{$vv->info_2}}</td>
												<td>{{$vv->info_3}}</td>
												<td>{{$vv->info_4}}</td>
												<td>{{$vv->info_5}}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
@endsection



@section('css')
@include('shared.table_css')
@endsection
@section('js')
@include('shared.table_js')
<script type="text/javascript">
var start_date = '';
var end_date = '';
$(function() {
  $("#datepicker123").daterangepicker({
    opens: 'right'
  }, function(start, end, label) {
      start_date = start.format('YYYY-MM-DD');
      end_date = end.format('YYYY-MM-DD');
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});
</script>
@endsection