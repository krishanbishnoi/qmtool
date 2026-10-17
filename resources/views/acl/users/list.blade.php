@extends('layouts.app')

@section('sh-title')
ACL
@endsection

@section('sh-detail')
Users
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

									<!--begin: Search Form -->
									<form class="kt-form kt-form--fit kt-margin-b-20">
									
										<div class="row kt-margin-b-20">
											<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
												<label>Role:</label>
												<select class="form-control kt-input" data-col-index="2">
													<option value="">Role</option>
													@foreach($roles as $kk=>$vv)
													<option value="{{$vv}}">{{$vv}}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="kt-separator kt-separator--md kt-separator--dashed"></div>
										<div class="row">
											<div class="col-lg-12">
												<button class="btn btn-primary btn-brand--icon" id="kt_search">
													<span>
														<i class="la la-search"></i>
														<span>Search</span>
													</span>
												</button>
												&nbsp;&nbsp;
												<button class="btn btn-secondary btn-secondary--icon" id="kt_reset">
													<span>
														<i class="la la-close"></i>
														<span>Reset</span>
													</span>
												</button>
											</div>
										</div>
									</form>

									<!--begin: Datatable -->
									<div class="kt-separator kt-separator--border-dashed kt-separator--space-md"></div>
									<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_99">
			<thead>
				   <tr>  
						<th title="Field #1">#</th>
						<th title="Field #2">Name</th>
						<th title="Field #3">Role</th>
						<th title="Field #4">Email</th>
						<th title="Field #5">Phone</th>
						<th title="Field #5">Client</th>
						<th title="Field #6">Status</th>
						<th title="Field #7">Actions</th>
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
												@foreach($row->roles as $rrs)
					                            {{$rrs->display_name.","}}
					                            @endforeach
											</td>
											<td>{{$row->email}}</td>
											<td>{{$row->mobile}}</td>
											@php
												$client_name = \App\Client::find($row->parent_client);
												
											@endphp	

											<td>{{$client_name->name ?? 'NA'}}</td>
											<td>
												@if($row->status == 1)
													<a href="{{url('user/status/'.Crypt::encrypt($row->id).'/99')}}">
													<span class="kt-badge kt-badge--danger kt-badge--inline">Disable Now</span>
													</a>
												@else
													<a href="{{url('user/status/'.Crypt::encrypt($row->id).'/1')}}">
													<span class="kt-badge kt-badge--success kt-badge--inline">Enable Now</span>
													</a>
												@endif
											</td>
					<td nowrap>
                        <!-- <div style="display: flex;">
                        	{{Form::open([ 'method'  => 'delete', 'route' => [ 'user.destroy', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])}}
                        	<button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                        		<i class="la la-trash"></i>
                        	</button>
                        </form> -->
                        <a href="{{url('user/'.Crypt::encrypt($row->id).'/edit')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
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
@include('shared.table_css');
@endsection
@section('js')

<script src="/assets/app/custom/general/crud/datatables/search-options/advanced-search.js" type="text/javascript"></script>
{!! Html::script('assets/vendors/custom/datatables/datatables.bundle.js')!!}

<script>

"use strict";
var KTDatatablesBasicScrollable = function() {

	var initTable1 = function() {
		var table = $('#kt_table_1');

		// begin first table
		table.DataTable({
			scrollY: '50vh',
			scrollX: true,
			scrollCollapse: true,
			columnDefs: [],
			
			buttons: [
				{
					extend: 'excelHtml5',
					title: 'Users List'
				},
				{
					extend: 'pdfHtml5',
					title: 'Users List'
				},
				{
					extend: 'print',
					title: 'Users List'
				},
				{
					extend: 'copyHtml5',
					title: 'Users List'
				},
				{
					extend: 'csvHtml5',
					title: 'Users List'
				}
			],
		});
	};

	var initTable2 = function() {
		var table = $('#kt_table_99');

		// begin second table
		table.DataTable({
			scrollY: '50vh',
			scrollX: true,
			scrollCollapse: true,
			dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
			<'row'<'col-sm-12'tr>>
			<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
			
			buttons: [
				{
					extend: 'excelHtml5',
					title: 'Users List'
				},
				{
					extend: 'pdfHtml5',
					title: 'Users List'
				},
				{
					extend: 'print',
					title: 'Users List'
				},
				{
					extend: 'copyHtml5',
					title: 'Users List'
				},
				{
					extend: 'csvHtml5',
					title: 'Users List'
				}
			],
			bSort : false,
			columnDefs: [],
		});
	};

	return {

		//main function to initiate the module
		init: function() {
			initTable1();
			initTable2();
		},

	};

}();

jQuery(document).ready(function() {
	KTDatatablesBasicScrollable.init();
});
jQuery(document).ready(function() {
	KTDatatablesExtensionButtons.init();
});
</script>
@endsection