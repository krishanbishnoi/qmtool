@extends('layouts.app')

@section('sh-title')
Target
@endsection

@section('sh-detail')
List target
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
							Client
						</th>
						<th title="Field #2">
							Process
						</th>
						<th title="Field #2">
							Audit Cycle
						</th>
						<th title="Field #2">
							Partner
						</th>
						<th title="Field #2">
							Lob
						</th>
						<th title="Field #2">
							Brand
						</th>
                        <th title="Field #2">
							Circle
						</th>
                        <th title="Field #2">
							Target
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
						{{$row->client_name}}
					</td>
					<td>
						{{$row->process_name}}
					</td>
					<td>
						{{$row->cycle_name}}
					</td>
                    <td>
                        {{$row->process_name}}
                    </td>
					<td>
                        {{$row->lob}}
					</td>
					<td>
                        {{$row->brand_name}}
					</td>
					<td>
                        {{$row->circle_name}}
					</td>
                    <td>
                        {{$row->target}}
					</td>
                   
					<td nowrap>
                        <!-- <div style="display: flex;">
                        	{{Form::open([ 'method'  => 'delete', 'route' => [ 'reason.destroy', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])}}
                        	<button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                        		<i class="la la-trash"></i>
                        	</button>
                        </form> -->
                        <a href="{{url('target/'.Crypt::encrypt($row->id).'/edit')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
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
@include('shared.table_css')
@endsection
@section('js')
@include('shared.table_js')
@endsection