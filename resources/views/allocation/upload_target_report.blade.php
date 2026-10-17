@extends('layouts.app')

@section('sh-title')
Upload Report
@endsection

@section('sh-detail')
with call id
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
						<th >#</th>
						<th >Zone</th>
                        <th >Location</th>
                        <th >Brand</th>
                        <th >Circle</th>
                        <th >week_1_target</th>
                        <th >week_2_target</th>
                        <th >week_3_target</th>
                        <th >week_4_target</th>
                        <th >Reason</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $row)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td>{{$row->zone}}</td>
                    <td>{{$row->location}}</td>
                    <td>{{$row->brand}}</td>
                    <td>{{$row->circle}}</td>
                    <td>{{$row->week_1_target}}</td>
                    <td>{{$row->week_2_target}}</td>
                    <td>{{$row->week_3_target}}</td>
                    <td>{{$row->week_4_target}}</td>
                    <td>{{$row->reason}}</td>
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