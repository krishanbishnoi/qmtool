@extends('layouts.app')

@section('sh-title')
{{$client_data->name}}
@endsection

@section('sh-detail')
Partners
@endsection

@section('sh-toolbar')
							<div class="kt-subheader__toolbar">
								<div class="kt-subheader__wrapper">

								<a href="/client/{{Crypt::encrypt($client_data->id)}}/create/partner" class="btn btn-label-success btn-bold">
									Create New Partner
								</a>
								
								</div>
							</div> 
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
							Name
						</th>
						<th title="Field #2">
							Admin Detail
						</th>
						<th title="Field #2">
							Location
						</th>
						<th title="Field #7">
							Contact Email
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
											<td>
												{{$row->name}}
											</td>
											<td>
												{{$row->admin_user->name}}
											</td>
											<td>
												@foreach($row->partner_location as $kkk=>$vvv)
													<p>{{$vvv->location_detail->name}}</p>
												@endforeach
											</td>
											<td>{{$row->contact_email}}</td>
					<td nowrap>
                        <div style="display: flex;">
                        	{{Form::open([ 'method'  => 'delete', 'route' => [ 'partner.destroy', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])}}
                        	<button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                        		<i class="la la-trash"></i>
                        	</button>
                        </form>
                        <a href="{{url('partner/'.Crypt::encrypt($row->id).'/edit')}}"  class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                        	<i class="la la-edit"></i>
                        </a>
                        <a href="{{url('partner/'.Crypt::encrypt($row->id).'/add/spocs')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Add Partner Spocs">
                        	<i class="la la-user-plus"></i>
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