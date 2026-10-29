@extends('layouts.app')

@section('sh-title')
Calibration
@endsection

@section('sh-detail')
Result
@endsection
@section('main')

<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head kt-portlet__head--lg">
		<div class="kt-portlet__head-label">
			<span class="kt-portlet__head-icon">
				<i class="kt-font-brand flaticon-information"></i>
			</span>
			<h3 class="kt-portlet__head-title">
				Basic Information
			</h3>
		</div>
	</div>
	<div class="kt-portlet__body">
		<form class="kt-form kt-form--label-right">

												<div class="form-group row">
												<div class="col-lg-4">
													<label>Calibration:</label>
													<input type="text" class="form-control" value="{{$calibration->title}}" />
												</div>
												<div class="col-lg-4">
													<label>Client:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$calibration->client->name}}" >
												</div>
												<div class="col-lg-4">
													<label>Process:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$calibration->process->name}}" >
												</div>
												</div>

												<div class="form-group row">
												<div class="col-lg-4">
													<label>Due Date:</label>
													<input type="text" class="form-control" value="{{$calibration->due_date}}" >
												</div>
												<div class="col-lg-4">
													<label>Total Calibrator:</label>
													<input type="text" class="form-control"  readonly="readonly" value="{{$calibration->calibrator->count()}}" >
												</div>
												<div class="col-lg-4">
													<label>Attachment:</label><br/>
													<a href='{{Storage::url("company/_".$calibration->company_id."/calibration/".$calibration->attachment)}}'  download class="kt-font-bold">
														Download <i class="fa flaticon-download"></i>
													</a>
												</div>
												</div>
												

												<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
												<h6>Calibration Submission Status</h6>
												<table class="table table-bordered table-hover">
														<thead>
															<tr>
																<th>#</th>
																<th>Calibrator</th>
																<th>Status</th>
																<th>Submitted At</th>
															</tr>
														</thead>
														<tbody>
															@foreach($calibration->calibrator as $kk=>$vv)
															<tr>
																<th scope="row">{{$loop->iteration}}</th>
																<td>
																	{{$vv->email}}
																	@if($vv->is_master)
																	<span class="form-text text-muted kt-font-brand">(Master Calibrator)</span>
																	@endif
																</td>
																@if($vv->status)
																	<td class="kt-font-success">Submited</td>
																	<td>{{$vv->updated_at}}</td>
																@else
																	@if($calibration->due_date >= date('Y-m-d'))
																	<td class="kt-font-warning">Pending</td>
																	@else
																	<td class="kt-font-danger">Absentee</td>
																	@endif
																	<td>-</td>
																@endif
																
															</tr>
															@endforeach
														</tbody>
													</table>


											</form>
	</div>
</div>

<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head kt-portlet__head--lg">
		<div class="kt-portlet__head-label">
			<span class="kt-portlet__head-icon">
				<i class="kt-font-brand flaticon2-line-chart"></i>
			</span>
			<h3 class="kt-portlet__head-title">
				Result  (Scored || Variance)
			</h3>
		</div>
	</div>
	<div class="kt-portlet__body">
		<table class="table table-striped- table-bordered table-hover table-checkable kt-font-bolder" id="kt_table_2">
			<thead>
				<tr>
					<th class="kt-bg-brand kt-font-light">Parameter / Sub - Parameter</th>
					@foreach($calibrator as $key=>$value)
					<th class="kt-bg-brand kt-font-light">
						{{$value->email}}
					</th>
					@endforeach
				</tr>
			</thead>
			<tbody>
				@foreach($all_params as $kk=>$vv)
					@foreach($vv->qm_sheet_sub_parameter as $kkb=>$vvb)
						<tr>
						<td>{{$vvb->sub_parameter}}</td>
						@foreach($all_calibrator as $keyc=>$valuec)
							<td>{{$valuec['data'][$vv->id]['subp'][$vvb->id]['score']}} <span class="kt-font-brand abcd">||</span> {{$valuec['data'][$vv->id]['subp'][$vvb->id]['score_vary'] ?? ''}}</td>
						@endforeach
						</tr>
					@endforeach
					<tr>
						<td class="kt-bg-danger kt-font-light">{{$vv->parameter}}</td>
						@foreach($all_calibrator as $keyc=>$valuec)
							<td class="kt-bg-danger kt-font-light">{{$valuec['data'][$vv->id]['with_fatal_score']}} <span class="kt-font-brand">||</span> {{$valuec['data'][$vv->id]['with_fatal_score_vary']  ?? ''}}</td>
						@endforeach
					</tr>
				@endforeach
				<tr>
					<td class="kt-bg-success kt-font-light">Overall With Fatal Score</td>
					@foreach($all_calibrator as $keyc=>$valuec)
							<td class="kt-bg-success kt-font-light">{{$valuec['with_fatal_score'] ?? ''}} <span class="kt-font-brand kgb">||</span> {{$valuec['with_fatal_score_vary'] ?? ''}}</td>
						@endforeach
				</tr>
				<tr>
					<td class="kt-bg-warning kt-font-light">Overall Without Fatal Score</td>
					@foreach($all_calibrator as $keyc=>$valuec)
							<td class="kt-bg-warning kt-font-light">{{$valuec['without_fatal_score']}} <span class="kt-font-brand">||</span> {{$valuec['without_fatal_score_vary'] ?? ''}}</td>
						@endforeach
				</tr>
				<tr>
					<td class="kt-bg-dark kt-font-light">Overall Summary</td>
					@foreach($calibrator as $key=>$value)
							<td class="kt-bg-dark kt-font-light">{{$value->overall_summary}}</td>
						@endforeach
				</tr>
			</tbody>
		</table>
	</div>
</div>




@endsection
@section('css')
@include('shared.table_css');
@endsection
@section('js')
@include('shared.table_js');
@endsection