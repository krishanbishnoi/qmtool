@extends('layouts.app')

@section('sh-title')
Temp list of audits
@endsection

@section('sh-detail')
to be removed
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
						<th title="Field #2">
							Client
						</th>
						<th title="Field #2">
							Partner
						</th>
						<th title="Field #2">
							Call Id
						</th>
						<th title="Field #2">
							Score
						</th>
						<th title="Field #2">
							Audit Created Date
						</th>
						<th title="Field #2">
							Audit Date
						</th>
						<th title="Field #2">
							Raw Data DB ID
						</th>
						<th title="Field #2">
							Audit DB ID
						</th>
						
				</tr>
			</thead>
			<tbody>
				@foreach($data as $row)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td>{{$row->client->name}}</td>
					<td>{{$row->partner->name}}</td>
					<td>{{$row->raw_data->call_id}}</td>
					<td>{{$row->overall_score}}</td>
					<td>{{$row->created_at}}</td>
					<td>{{$row->audit_date}}</td>
					<td>{{$row->raw_data_id}}</td>
					<td>{{$row->id}}</td>
					
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