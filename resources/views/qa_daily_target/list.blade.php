@extends('layouts.app')

@section('sh-title')
QA Target
@endsection

@section('sh-detail')
List QA Daily Target target
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
							Days 
						</th>
						<th title="Field #2">
							{{date('yy-m-d')}} 
						</th>
						<th title="Field #2">
							{{$tomorrow}}
						</th>
						<th title="Field #2">
							{{$day_after_tomorrow}}
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
						{{$row->name}}
					</td>
					
					<td>
						{{$row->days}}
					</td>
					<td>
						{{$row->today}}
					</td>
					<td>
						{{$row->tomorrow}}
					</td>
					<td>
						{{$row->day_after_tomorrow}}
					</td>
                   
                   
					<td nowrap>
                       
                        <!-- <a href="{{url('qa_daily_target/'.Crypt::encrypt($row->id).'/edit')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                        	<i class="la la-edit"></i>
                        </a> -->

						<a href="{{url('qa_daily_target/'.Crypt::encrypt($row->id).'/view_full')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
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
@include('shared.table_css')
@endsection
@section('js')
@include('shared.table_js')
@endsection