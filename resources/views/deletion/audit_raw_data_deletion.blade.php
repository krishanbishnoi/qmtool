@extends('layouts.app')

@section('sh-title')
Audit Data
@endsection

@section('sh-detail')
Audit data with raw data
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
												@if(Auth::user()->hasRole('client')||
												    Auth::user()->hasRole('partner-training-head') || Auth::user()->hasRole('partner-operation-head') || Auth::user()->hasRole('partner-quality-head') ||
								 					Auth::user()->hasRole('partner-admin')||
													 Auth::user()->hasRole('partner-qa'))
							 					@else
							 					<th>Auditor Name</th>
							 					@endif
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
												<th>
													@if (isset($labels->qm_sheet_id))
														{{ $labels->info_5 }}							
													@else 
														Agent Name
													@endif
													
												</th>
												<th>Emp. ID</th>
												<th>Doj</th>
												<th>LOB</th>
												<th>
														Language
													
												</th>
												<th>Case Id</th>
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
												<th>
													@if (isset($labels->qm_sheet_id))
														{{ $labels->info_12 }}							
													@else 
														Customer Phone
													@endif
												</th>
												<th>Refrence Number</th>
												<th>QRC</th>
												
												<th>With Fatal Score</th>
												<th>Without Fatal Score</th>
												<th>Brand Name</th>
												<th>Circle</th>
												<th>Raw Data id</th>
												<th>Audit id</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											@foreach($data as $kk=>$vv)
											<tr>
												<td>{{$loop->iteration}}</td>
												@if(Auth::user()->hasRole('client')||
												    Auth::user()->hasRole('partner-training-head') || Auth::user()->hasRole('partner-operation-head') || Auth::user()->hasRole('partner-quality-head') ||
								 					Auth::user()->hasRole('partner-admin')||
													 Auth::user()->hasRole('partner-qa'))
							 					@else
							 					<td>{{$vv['auditor']}}</td>
							 					@endif
												<td>{{$vv['audit_date']}}</td>
												<td>{{$vv['partner']}}</td>
												<td>{{$vv['location']}}</td>
												<td>{{$vv['temp_raw_data']->call_id}}</td>
												<td>{{$vv['temp_raw_data']->agent_name}}</td>
												<td>{{$vv['temp_raw_data']->emp_id}}</td>
												<td>{{$vv['temp_raw_data']->doj}}</td>
												<td>{{$vv['temp_raw_data']->lob}}</td>
												<td>{{$vv['language_2']}}</td>
												<td>{{$vv['case_id']}}</td>
												<td>{{$vv['temp_raw_data']->call_time}}</td>
												<td>{{$vv['temp_raw_data']->call_duration}}</td>
												
												<td>{{$vv['temp_raw_data']->campaign_name}}</td>
												<td>{{$vv['temp_raw_data']->customer_name}}</td>
												<td>{{$vv['temp_raw_data']->phone_number}}</td>
												<td>{{$vv['refrence_number']}}</td>
												<td>{{$vv['qrc_2']}}</td>
												
												<td>{{$vv['with_fatal_score_per']}}</td>
												<td>{{$vv['without_fatal_score']}}</td>
												<td>{{$vv['temp_raw_data']->brand_name}}</td>
												<td>{{$vv['temp_raw_data']->circle}}</td>

												<td>{{$vv['temp_raw_data']->id}}</td>
												<td>{{$vv['audit_id']}}</td>
												<td><a href="delete_audit/{{$vv['temp_raw_data']->id}}/{{$vv['audit_id']}}"><button class="btn btn-xs btn-danger">Delete</button></a></td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>

							@endsection

@section('js')
<script src="/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/crud/datatables/basic/scrollable.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
@endsection
@section('css')
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
