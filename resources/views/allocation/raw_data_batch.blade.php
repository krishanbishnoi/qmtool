@extends('layouts.app')

@section('sh-title')
Raw Data Batch
@endsection

@section('sh-detail')
Raw Data Batch
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
							Process
						</th>
						<th title="Field #2">
							Uploader
						</th>
						<th title="Field #2">
							Create At
						</th>
						<!--<th title="Field #7">
							Download File
						</th>-->
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
												{{$row->client->name}}
											</td>
											<td>
												<?=($row->partner) ? $row->partner->name : '-' ?>
											</td>
											<td>
												{{$row->process->name}}
											</td>
											<td>
												{{$row->uploader->name}}
											</td>
											<td>
												{{$row->created_at}}
											</td>
											<!--<td>
												<a href="{{Storage::url('raw_data_dump_file/').$row->file_name}}">Download Sheet</a>
											</td>-->
											

					<td nowrap>
						
                        <div style="display: flex;">
                        	{{Form::open([ 'method'  => 'delete', 'route' => [ 'allocationdel.destroy', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])}}
                        	<a href="{{url('allocation/pendingList/'.Crypt::encrypt($row->id))}}" target="_blank" title="View Pending Audit">
												<i class="fa fa-eye"></i>	
													</a>
                        	<button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                        		<i class="la la-trash"></i>
                        	</button>

                        </form>

                        @if($row->visiblity)

													<a href="{{url('batch/status/'.Crypt::encrypt($row->id).'/0')}}">
													<span class="kt-badge kt-badge--danger kt-badge--inline">Disable Now</span>
													</a>
												@else
													<a href="{{url('batch/status/'.Crypt::encrypt($row->id).'/1')}}">
													<span class="kt-badge kt-badge--success kt-badge--inline">Enable Now</span>
													</a>
												@endif

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