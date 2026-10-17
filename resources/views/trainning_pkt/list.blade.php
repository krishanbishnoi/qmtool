@extends('layouts.app')

@section('sh-title')
Training PKT
@endsection

@section('sh-detail')
List Training PKT
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
		
	</div>
	<div class="kt-portlet__body">

		<!--begin: Datatable -->
		<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
			<thead>
				<tr>
						<th title="Field #1">#</th>
						<th title="Field #2">
							Name
						</th>
						<th title="Field #2">
							Month
						</th>
						
						<th title="Field #2">
							Count of Test
						</th>
						<th title="Field #2">
							Test Attendant
						</th>
						<th title="Field #2">
							Overall obtain Marks
						</th>
						<th title="Field #2">
							Out of Total Marks
						</th>
						<th title="Field #2">
							Avg. Score
						</th>
						
						
						
				</tr>
			</thead>
			<tbody>
				@foreach($data as $row)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td>
						{{$row->name}}
					</td>
					<td>
						{{$row->pkt_month}}
					</td>
					
					<td>
						{{$row->count_of_test}}
					</td>
					<td>
						{{$row->test_attendent}}
					</td>
					<td>
						{{$row->overall_marks_obtain}}
					</td>
					<td>
						{{$row->out_of_total_marks}}
					</td>
					<td>
						{{$row->avg_score}}
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
@endsection