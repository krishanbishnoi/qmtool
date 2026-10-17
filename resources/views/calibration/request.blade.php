@extends('layouts.app')

@section('sh-title')
Calibration
@endsection

@section('sh-detail')
my Requests
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
							Title
						</th>
						<th title="Field #2">
							Due Date
						</th>
						<th title="Field #2">
							Client
						</th>
						<th title="Field #2">
							Process
						</th>
						<th title="Field #2">
							Qm Sheet
						</th>
						<th title="Field #2">
							Calibrators
						</th>
						<th title="Field #7">
							Actions
						</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $row)
				<tr>
					<td>{{$loop->iteration}}</td>
											<td>
												{{$row->calibration->title}}
											</td>
											<td>
												{{$row->calibration->due_date}}
											</td>
											<td>
												{{$row->calibration->client->name}}
											</td>
											<td>
												{{$row->calibration->process->name}}
											</td>
											<td>
												{{$row->calibration->qm_sheet->name}} - V{{$row->calibration->qm_sheet->version}}
											</td>
											
											<td class="kt-font-bold">
												<span class="kt-font-danger">{{$row->calibration->calibrator->where('status',1)->count()}}</span> / <span class="kt-font-success">{{$row->calibration->calibrator->count()}}</span>
											</td>

					<td nowrap>
                        <div style="display: flex;">

                        @if($row->status==0)
                        <a href="{{url('calibrate/'.Crypt::encrypt($row->id))}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Show Detail">
                        	<i class="la la-list-alt"></i>
                        </a>	
                        @endif
                        <a href="{{url('calibration/'.Crypt::encrypt($row->calibration_id).'/result')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Show Detail">
                        	<i class="la la-eye"></i>
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