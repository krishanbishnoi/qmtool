@extends('layouts.app')

@section('sh-title')
Audit List
@endsection

@section('sh-detail')
For QC
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
		<form autocomplete="off">
		<div class="row">
			<div class="col-md-3">
				<label>First Select Client:</label>
				<select class="form-control kt-input" name="client_id" required="required">
					<option value="">Select one!</option>
					@foreach($client_list as $kk=>$vv)
					<option value="{{Crypt::encrypt($kk)}}">{{$vv}}</option>
					@endforeach
				</select>
			</div>
			<div class="col-md-3">
				<label class="">Select Start Audit Date:</label>
				<input type="text" class="form-control"  id="kt_datepicker_1" name="start_date" required="required">
			</div>
			<div class="col-md-3">
				<label class="">Select End Audit Date:</label>
				<input type="text" class="form-control"  id="kt_datepicker_2" name="end_date" required="required">
			</div>
			<div class="col-md-1">
				<label class="">&nbsp;</label>
				<button type="Submit" class="btn btn-success form-control">Submit</button>
			</div>
		</div>
		</form>
		

									

									<!--begin: Search Form -->
									<div style="display:none">
									<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
									<form class="kt-form kt-form--fit kt-margin-b-20">
										<h6>Filter</h6>
										<div class="row kt-margin-b-20">
											<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
												<label>Partner:</label>
												{{ Form::select(null,$unique_partner,null,['class'=>'form-control kt-input','data-col-index'=>'2']) }}
											</div>
											<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
												<label>Location:</label>
												{{ Form::select(null,$unique_location,null,['class'=>'form-control kt-input','data-col-index'=>'3']) }}
											</div>
											<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
												<label>Agent:</label>
												{{ Form::select(null,$unique_agent,null,['class'=>'form-control kt-input','data-col-index'=>'6']) }}
											</div>
											<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
												<label>QA:</label>
												{{ Form::select(null,$unique_qa,null,['class'=>'form-control kt-input','data-col-index'=>'7']) }}
											</div>
										</div>
										<div class="kt-separator kt-separator--md kt-separator--dashed"></div>
										<div class="row">
											<div class="col-lg-12">
												<button class="btn btn-primary btn-brand--icon" id="kt_search">
													<span>
														<i class="la la-search"></i>
														<span>Search</span>
													</span>
												</button>
												&nbsp;&nbsp;
												<button class="btn btn-secondary btn-secondary--icon" id="kt_reset">
													<span>
														<i class="la la-close"></i>
														<span>Reset</span>
													</span>
												</button>
											</div>
										</div>
									</form>
									</div>
									<!--begin: Datatable -->
									<div class="kt-separator kt-separator--border-dashed kt-separator--space-md"></div>
									<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_99">
										<thead>
											<tr>
												<th>#</th>
												<th>Audit Date</th>
												<th>QC Last Date</th>
												<th>Partner</th>
												<th>Location</th>
												<th>Phone No.</th>
												<th>Call Id</th>
												<th>Agent</th>
												<th>QA</th>
												<th>Score</th>
												<th>QC</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											@foreach($audits as $kk=>$vv)
											<tr>
												<td>{{$loop->iteration}}</td>
												<td>{{$vv->audit_date}}</td>
												<td>{{$vv->qc_tat}}</td>
												<td>{{$vv->partner->name}}</td>
												<td>{{$vv->raw_data->location_data->name}}</td>
												<td>{{$vv->raw_data->phone_number}}</td>
												<td>{{$vv->raw_data->call_id}}</td>
												<td>{{$vv->raw_data->agent_name}}</td>
												<td>{{$vv->qa_qtl_detail->name}}</td>
												<td>{{$vv->with_fatal_score_per}}</td>
												<td>{{return_qc_status($vv->qc_status)}}</td>
												</td>
												<td nowrap>
							                        <a href="{{url('qc_qa/single_audit_detail/'.Crypt::encrypt($vv->id))}}" target="_blank" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Detail View">
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
@include('shared.table_js')
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/crud/datatables/search-options/advanced-search.js" type="text/javascript"></script>
@endsection