@extends('layouts.app')

@section('sh-title')
update training list
@endsection

@section('sh-detail')
update training list
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

			<div class="kt-separator kt-separator--border-dashed kt-separator--space-md"></div>
			<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_99">
			<thead>
				<tr>
						<th title="Field #1">#</th>
						<th title="Field #2">
							Date
						</th>
						<th title="Field #3">
							Feedback
						</th>
						<th title="Field #4">
							Trainee Email
						</th>
						
				</tr>
			</thead>
			<tbody>
				@foreach($data as $row)
				<tr>
					<td>{{$loop->iteration}}</td>
											<td>
												{{$row->training_date}}
											</td>
											<td>{{$row->feedback}}</td>
											
											<td>{{$row->trainiee_email}}</td>
											
            	</tr>
            @endforeach

        </tbody>
    </table>

</div>
</div>
@endsection