@extends('layouts.app')

@section('sh-title')
Email
@endsection

@section('sh-detail')
List email
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
							Process
						</th>
						
						 <th title="Field #3">
							Client 
						</th> 
						<th title="Field #4">
							Email
						</th>
						<th title="Field #5">
							Email type
						</th>
						
						
						 <th title="Field #6">
							Action
						</th> 
				</tr>
			</thead>
			<tbody>
				@foreach($data as $row)
				<tr>
					<td>{{$loop->iteration}}</td>
					
					<td>
						{{$row->process_name}}
					</td>

					<td>
						{{$row->client_name}}
					</td>

					<td>
						{{$row->email}}
					</td>
					<td>
						{{$row->email_type}}
					</td>
                    <!-- <td>
                        {{$row->partner_name}}
                    </td> -->
					
					
					 <td nowrap>
                        <a href="{{$row->id}}/delete" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
						<i class="la la-trash"></i>
                        </a>
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