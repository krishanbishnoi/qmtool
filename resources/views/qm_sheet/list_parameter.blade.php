@extends('layouts.app')

@section('sh-title')
{{$qm_sheet_data->name}}
@endsection

@section('sh-detail')
Parameters
@endsection

@section('sh-toolbar')
							<div class="kt-subheader__toolbar">
								<div class="kt-subheader__wrapper">

								<a href="/qm_sheet/{{Crypt::encrypt($qm_sheet_data->id)}}/add_parameter" class="btn btn-label-success btn-bold">
									Create New Parameter
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
							Parameter
						</th>
						<th title="Field #2">
							Sub Parameter - Weightage
						</th>
						<th title="Field #2">
							Weightage
						</th>
						<th title="Field #2">
							Type
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
												{{$row->parameter}}
											</td>
											<td>
												<ol>
												<?php $total_weightage=0; ?>
												@foreach($row->qm_sheet_sub_parameter as $ksp=>$vsp)
												<li>{{$vsp->sub_parameter}} - <strong>{{$vsp->weight}}</strong></li>
												<?php $total_weightage += $vsp->weight;?>
												@endforeach
												</ul>
											</td>
											<td>
												{{$total_weightage}}
											</td>
											<td>
												{{($row->is_non_scoring)?"Non-Scoring":"Scoring"}}
											</td>
											
					<td nowrap>
                        <div style="display: flex;">
                        	{{Form::open([ 'method'  => 'delete', 'route' => [ 'delete_parameter', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])}}
                        	<button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                        		<i class="la la-trash"></i>
                        	</button>
                        </form>
                        <a href="{{url('parameter/'.Crypt::encrypt($row->id).'/edit')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
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
@include('shared.table_js');
@endsection