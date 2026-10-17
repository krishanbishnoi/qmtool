@extends('layouts.app')

@section('sh-title')
Audit
@endsection

@section('sh-detail')
Done
@endsection

@section('main')

<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head kt-portlet__head--lg">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Filter:- Audit Date
			</h3>
		</div>
	</div>
	<div class="kt-portlet__body">
		<form>
			<div class="form-group row">
				<div class="col-lg-3">
					<input type="text" name="start_date" class="form-control" id="kt_datepicker_1" readonly placeholder="Select start audit date" />
				</div>
				<div class="col-lg-3">
					<input type="text" name="end_date" class="form-control" id="kt_datepicker_2" readonly placeholder="Select end audit date" />
				</div>

				@if (Auth::user()->parent_client == 23)


				<div class="col-lg-3">
					<select name="process_id" class="form-control">
						<option value="all">All Processes</option>

						@foreach($processes as $process)
						<option value="{{$process->id}}"
							{{request('process_id')==$process->id?'selected':''}}>
							{{$process->name}}
						</option>
						@endforeach
					</select>
				</div>
				@endif
				<div class="col-lg-3">
					<button type="submit" class="btn btn-brand">Submit</button>
				</div>
			</div>
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
				List
			</h3>
		</div>
	</div>
	<div class="kt-portlet__body">

		<!--begin: Datatable -->
		@php
		$dynamicProcesses = [243,244,245,246,247,248,249,250,251,252,255,256,257,258,259,264,267,274,275,276,277,278,279,280,281,286];
		$auditoNameProcess = [224,225,226,227,228,229,230,231,232,233,234,235,236,237,238,239,240,241,242,243,244,245,246,247,248,249,250,251,252,253,254,255,256,257,258,259,260,261,262,263,264,265,266,267,268,269,270,271,272,274,275,276,277,278,279,280,281,282,283,286];
		@endphp

		@if(in_array($selectedProcess, $dynamicProcesses))
		<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_2">
			<thead>
				<tr>
					<th title="Field #1">#</th>
					<th title="Field #8">
						Action
					</th>

					<th title="Field #4">
						Call Id
					</th>

					<th title="Field #6">
						Audit Date
					</th>

					@if(in_array($selectedProcess, $auditoNameProcess))
					<th title="Field #4">Audited By</th>
					@endif


					@foreach($requiredFields as $field)
					@if(isset($fieldMap[$field]))
					<th>{{ $field }}</th>
					@endif
					@endforeach

					<th title="Field #6">
						Rebuttal Raised by
					</th>
					<th title="Field #7">
						Audit Status
					</th>
					<th title="Field #7">
						Score
					</th>
					<th title="Field #7">
						Rebuttal Seen Status
					</th>
					<th title="Field #7">
						Rebuttal Accepted
					</th>
					<th title="Field #7">
						Rebuttal Rejected
					</th>
					<th title="Field #7">
						Feedback Status
					</th>


				</tr>
			</thead>
			<tbody>
				@foreach($data as $row)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td nowrap>
						<a href="{{url('partner/single_audit_detail/'.Crypt::encrypt($row->audit->id))}}" target="_blank" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Detail View">
							<i class="la la-edit"></i>
						</a>
					</td>

					<td>
						{{$row->call_id ?? ''}}
					</td>

					<td>
						{{date_time_to_user($row->audit->audit_date ?? '')}}
					</td>
					@if(in_array($selectedProcess, $auditoNameProcess))

					<td>
						{{$row->audit->qa_qtl_detail->name ?? ''}}
					</td>
					@endif
					@foreach($requiredFields as $field)
					@if(isset($fieldMap[$field]))
					<td>{{ $row->dynamic_fields[$fieldMap[$field]] ?? '' }}</td>
					@endif
					@endforeach

					<td>{{$row->audit->rebuttal_tat ?? ''}}</td>
					<td>
						@if($row->audit->is_critical)
						<span class="text-danger">Fatal</span>
						@else
						<span class="text-success">Non-Fatal</span>
						@endif
					</td>
					<td>
						{{round($row->audit->overall_score ?? '')}}%
					</td>
					<td>
						{{audit_rebuttal_status($row->audit->rebuttal_status)}} || {{$row->audit->audit_rebuttal->count()}}
					</td>
					<td>
						{{$row->audit->audit_rebuttal_accepted->count()}}
					</td>
					<td>
						{{$row->audit->audit_rebuttal_rejected->count()}}
					</td>
					<td>
						@if($row->audit->feedback_shared_status != '')
						{{return_feedback_status($row->audit->feedback_shared_status)}}
						@endif

					</td>

				</tr>
				@endforeach

			</tbody>
		</table>
		@elseif($selectedProcess == 'all')


		<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_2">
			<thead>
				<tr>
					<th title="Field #1">#</th>
					<th title="Field #8">
						Action
					</th>
					<th title="Field #2">
						Employee Id
					</th>
					<th title="Field #3">
						Agent Name
					</th>
					@if(in_array($selectedProcess, $auditoNameProcess))
					<th title="Field #4">Audited By</th>
					@endif
					<th title="Field #3">
						User Name
					</th>
					<th title="Field #4">
						Call Id
					</th>
					<th title="Field #5">
						LOB
					</th>
					<th title="Field #6">
						Audit Date
					</th>
					<th title="Field #6">
						Rebuttal Raised by
					</th>
					<th title="Field #7">
						Audit Status
					</th>
					<th title="Field #7">
						Score
					</th>
					<th title="Field #7">
						Rebuttal Seen Status
					</th>
					<th title="Field #7">
						Rebuttal Accepted
					</th>
					<th title="Field #7">
						Rebuttal Rejected
					</th>
					<th title="Field #7">
						Feedback Status
					</th>


				</tr>
			</thead>
			<tbody>
				@foreach($data as $row)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td nowrap>
						<a href="{{url('partner/single_audit_detail/'.Crypt::encrypt($row->audit->id))}}" target="_blank" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Detail View">
							<i class="la la-edit"></i>
						</a>
					</td>
					<td>
						{{$row->emp_id?? ''}}
					</td>
					<td>
						{{$row->agent_name?? ''}}
					</td>
					@if(in_array($selectedProcess, $auditoNameProcess))
					<td>
						{{$row->audit->qa_qtl_detail->name ?? ''}}
					</td>
					@endif
					<td>
						{{$row->user_name?? ''}}
					</td>
					<td>
						{{$row->call_id ?? ''}}
					</td>
					<td>
						{{$row->lob ?? 'NA'}}
					</td>
					<td>
						{{date_time_to_user($row->audit->audit_date ?? '')}}
					</td>
					<td>{{$row->audit->rebuttal_tat ?? ''}}</td>
					<td>
						@if($row->audit->is_critical)
						<span class="text-danger">Fatal</span>
						@else
						<span class="text-success">Non-Fatal</span>
						@endif
					</td>
					<td>
						{{round($row->audit->overall_score ?? '')}}%
					</td>
					<td>
						{{audit_rebuttal_status($row->audit->rebuttal_status)}} || {{$row->audit->audit_rebuttal->count()}}
					</td>
					<td>
						{{$row->audit->audit_rebuttal_accepted->count()}}
					</td>
					<td>
						{{$row->audit->audit_rebuttal_rejected->count()}}
					</td>
					<td>
						@if($row->audit->feedback_shared_status != '')
						{{return_feedback_status($row->audit->feedback_shared_status)}}
						@endif

					</td>

				</tr>
				@endforeach

			</tbody>
		</table>
		@else

		<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_2">
			<thead>
				<tr>
					<th title="Field #1">#</th>
					<th title="Field #8">
						Action
					</th>
					<th title="Field #2">
						Employee Id
					</th>
					<th title="Field #3">
						Agent Name
					</th>

					@if(in_array($selectedProcess, $auditoNameProcess))
					<th title="Field #4">Audited By</th>
					@endif
					<th title="Field #3">
						User Name
					</th>
					<th title="Field #4">
						Call Id
					</th>
					<th title="Field #5">
						LOB
					</th>
					<th title="Field #6">
						Audit Date
					</th>
					<th title="Field #6">
						Rebuttal Raised by
					</th>
					<th title="Field #7">
						Audit Status
					</th>
					<th title="Field #7">
						Score
					</th>
					<th title="Field #7">
						Rebuttal Seen Status
					</th>
					<th title="Field #7">
						Rebuttal Accepted
					</th>
					<th title="Field #7">
						Rebuttal Rejected
					</th>
					<th title="Field #7">
						Feedback Status
					</th>


				</tr>
			</thead>
			<tbody>
				@foreach($data as $row)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td nowrap>
						<a href="{{url('partner/single_audit_detail/'.Crypt::encrypt($row->audit->id))}}" target="_blank" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Detail View">
							<i class="la la-edit"></i>
						</a>
					</td>
					<td>
						{{$row->emp_id?? ''}}
					</td>
					<td>
						{{$row->agent_name?? ''}}
					</td>
					@if(in_array($selectedProcess, $auditoNameProcess))
					<td>
						{{$row->audit->qa_qtl_detail->name ?? ''}}
					</td>
					@endif
					<td>
						{{$row->user_name?? ''}}
					</td>
					<td>
						{{$row->call_id ?? ''}}
					</td>
					<td>
						{{$row->lob ?? 'NA'}}
					</td>
					<td>
						{{date_time_to_user($row->audit->audit_date ?? '')}}
					</td>
					<td>{{$row->audit->rebuttal_tat ?? ''}}</td>
					<td>
						@if($row->audit->is_critical)
						<span class="text-danger">Fatal</span>
						@else
						<span class="text-success">Non-Fatal</span>
						@endif
					</td>
					<td>
						{{round($row->audit->overall_score ?? '')}}%
					</td>
					<td>
						{{audit_rebuttal_status($row->audit->rebuttal_status)}} || {{$row->audit->audit_rebuttal->count()}}
					</td>
					<td>
						{{$row->audit->audit_rebuttal_accepted->count()}}
					</td>
					<td>
						{{$row->audit->audit_rebuttal_rejected->count()}}
					</td>
					<td>
						@if($row->audit->feedback_shared_status != '')
						{{return_feedback_status($row->audit->feedback_shared_status)}}
						@endif

					</td>

				</tr>
				@endforeach

			</tbody>
		</table>
		@endif

		<!--end: Datatable -->
	</div>
</div>
@endsection
@section('css')
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('js')
{!! Html::script('assets/vendors/custom/datatables/datatables.bundle.js')!!}
<script src="/assets/app/custom/general/crud/datatables/basic/scrollable.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
@endsection