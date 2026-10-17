@extends('layouts.app')

@section('sh-title')
QM - Sheet
@endsection

@section('sh-detail')
All Client
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
							Process
						</th>
						<th title="Field #2">
							Version
						</th>
						<th title="Field #2">
							Name
						</th>
						<th title="Field #2">
							Code
						</th>
						<th title="Field #2">
							Total Parameters
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
												{{$row->client->name}}
											</td>
											<td>
												{{$row->process->name}}
											</td>
											<td>
												{{$row->version}}
											</td>
											<td>
												{{$row->name}}
											</td>
											<td>
												{{$row->code}}
											</td>
											<td>{{$row->parameter->count()}}</td>
					<td nowrap>
                        <div style="display: flex;">
                        	{{Form::open([ 'method'  => 'delete', 'route' => [ 'qm_sheet.destroy', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])}}
                        	<button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete">
                        		<i class="la la-trash"></i>
                        	</button>
                        </form>
                        <a href="{{url('qm_sheet/'.Crypt::encrypt($row->id).'/edit')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
                        	<i class="la la-edit"></i>
                        </a>
                        <a href="{{url('qm_sheet/'.Crypt::encrypt($row->id).'/parameter')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Manage Parameters">
                        	<i class="la la-list"></i>
                        </a>
                        <a href="{{url('audit_sheet/'.Crypt::encrypt($row->id))}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Audit Sheet">
                        	<i class="la la-eye"></i>
                        </a>
                        <a href="{{url('qm_sheet/re_label/'.Crypt::encrypt($row->id))}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Re-label auditsheet inputs">
                        	<i class="la la-copy"></i>
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