@extends('layouts.app')

@section('sh-title')
Report
@endsection

@section('sh-detail')
QC Report
@endsection

@section('main')



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
												<th>Call id</th>
												<th>Agent Name</th>
												<th>audit date</th>
												
												<th>location</th>
												<th>action</th>
												
											</tr>
										</thead>
										<tbody>
											@foreach($data as $vv)
											<tr>
												<td>{{$loop->iteration}}</td>
												<td>{{$vv['call_id']}}</td>
												<td>{{$vv['agent_name']}}</td>
												<td>{{$vv['audit_date']}}</td>
												
                                                <td>{{$vv['location']}}</td>
												<td><a href="/new_loc/{{$vv['id']}}/{{$vv['raw_data_id']}}/{{$vv['partner_id']}}/{{$vv['location']}}">Edit</a></td>
												
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
