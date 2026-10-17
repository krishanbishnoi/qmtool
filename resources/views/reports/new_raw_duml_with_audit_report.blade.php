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
				
				<div class="form-group row">
					<div class="col-lg-6">
						<label>Select Client:</label>
						<select class="form-control" name="client_id"
							    required="required" onchange="onClientChange();">
							<option>Select One!</option>							
						</select>
					</div>
					<div class="col-lg-6">
						<label class="">Select Partner:</label>
						<select class="form-control" name="partner_id"
							    required="required" >
							<option>Select One!</option>							
						</select>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-lg-6">
						<label>Select Location:</label>
						<select class="form-control" name="location_id"
							    required="required" >
							<option>Select One!</option>							
						</select>
					</div>
					<div class="col-lg-6">
						<label class="">Select Process:</label>
						<select class="form-control"  name="process_id" onchange="getCycle(this.value);"
							    required="required">
							<option>Select One!</option>							
						</select>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-lg-6">
						<label>Select Qm Sheet:</label>
						<select class="form-control" name="qm_sheet_id"
							    required="required">
							<option>Select One!</option>							
						</select>
					</div>
					<div class="col-lg-6">
						<label class="">Select Audit Cycle:</label>
						<select class="form-control" name="audit_cycle"  id="audit_cycle" required="required" >
							<option value="0">Select Audit Cycle</option>
							<option ></option>
						</select>
					</div>
				</div>
				
				
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
								 					Auth::user()->hasRole('partner-admin'))
							 					@else
							 					<th>Auditor Name</th>
							 					@endif
												<th>Audit Date</th>
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
												<th>Customer Phone</th>
												<th>Refrence Number</th>
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
												@foreach($repeater_param_data as $kk=>$vv)
													@foreach($vv as $kkb=>$vvb)
													<th>{{$vvb['name']}}</th>
													<th>Scored</th>
													<th>Scorable</th>
													<th>Reason Type</th>
													<th>Reason</th>
													<th>Remark</th>
													@endforeach
												@endforeach
											</tr>
										</thead>
										<tbody>
											@foreach($data as $kk=>$vv)
											<tr>
												<td>{{$loop->iteration}}</td>
												@if(Auth::user()->hasRole('client')||
												    Auth::user()->hasRole('partner-training-head') || Auth::user()->hasRole('partner-operation-head') || Auth::user()->hasRole('partner-quality-head') ||
								 					Auth::user()->hasRole('partner-admin'))
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
												<td>{{$vv['temp_raw_data']->call_type}}</td>
												<td>{{$vv['temp_raw_data']->call_sub_type}}</td>
												<td>{{$vv['temp_raw_data']->disposition}}</td>
												<td>{{$vv['temp_raw_data']->campaign_name}}</td>
												<td>{{$vv['temp_raw_data']->customer_name}}</td>
												<td>{{$vv['temp_raw_data']->phone_number}}</td>
												<td>{{$vv['refrence_number']}}</td>
												<td>{{$vv['qrc_2']}}</td>
												<td>{{$vv['overall_summary']}}</td>
												<td>{{$vv['with_fatal_score_per']}}</td>
												<td>{{$vv['without_fatal_score']}}</td>
												<td>{{$vv['temp_raw_data']->brand_name}}</td>
												<td>{{$vv['temp_raw_data']->circle}}</td>
												<td>{{$vv['temp_raw_data']->info_1}}</td>
												<td>{{$vv['temp_raw_data']->info_2}}</td>
												<td>{{$vv['temp_raw_data']->info_3}}</td>
												<td>{{$vv['temp_raw_data']->info_4}}</td>
												<td>{{$vv['temp_raw_data']->info_5}}</td>
												@foreach($vv['audit'] as $kk=>$vv)
													@foreach($vv as $kkb=>$vvb)
														<td>{{$vvb['observation']}}</td>
														<td>{{$vvb['scored']}}</td>
														<td>{{$vvb['scorable']}}</td>
														<td>{{$vvb['reason_type']}}</td>
														<td>{{$vvb['reason']}}</td>
														<td>{{$vvb['remark']}}</td>
													@endforeach
												@endforeach
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>

							@endsection

@section('js')
<script>
function getCycle(val) {

var base_url = window.location.origin;
if(val != 0) {
	$.ajax({
	type: "GET",
	url: base_url + "/dashboard/get_partner_audit_cycle/"+val,
	success: function(Data){
		$("#audit_cycle").html(Data);
	}
	});

	
}
}
</script>
<script src="/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/crud/datatables/basic/scrollable.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
@endsection
@section('css')
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
