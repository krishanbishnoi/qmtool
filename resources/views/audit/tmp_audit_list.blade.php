@extends('layouts.app')

@section('sh-title')
Temp Audited Pool
@endsection

@section('sh-detail')
Call
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
						<th title="Field #7">
							Audit Date
						</th>
						<th title="Field #2">
							Call Id
						</th>
						<th title="Field #2">
							Agent Name
						</th>
						<th title="Field #2">
							Lob
						</th>
						<th title="Field #2">
							Location
						</th>
						<th title="Field #2">
							Campaign
						</th>
						
						<th title="Field #7">
							Score With FATAL
						</th>
						<th title="Field #7">
							Score Without FATAL
						</th>
						
						<th title="Field #7">
							Action
						</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $row)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td>{{$row->created_at}}</td>
					<td>{{$row->raw_data->call_id}}</td>
					<td>{{$row->raw_data->agent_name}}</td>
					<td>{{$row->raw_data->lob}}</td>
					<td>{{$row->raw_data->location}}</td>
					<td>{{$row->raw_data->campaign_name}}</td>
					<td>{{($row->is_critical==1)?0:$row->overall_score."%"}}</td>
					<td>{{$row->overall_score}} %</td>
					<td nowrap>
                        <a href="/temp_audit/edit/{{Crypt::encrypt($row->id)}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
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
@include('shared.table_css');
@endsection
@section('js')
@include('shared.table_js');
@endsection