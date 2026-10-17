@extends('layouts.app')

@section('sh-title')
Report
@endsection

@section('sh-detail')
Parameter Compliance Report
@endsection

@section('main')


<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Filter
			</h3>
		</div>
	</div>
	<div class="kt-portlet__body">
		{{ Form::open(array('url' => 'report/new_parameter_compliance')) }}
			@csrf
			<div class="form-group row">
			@if(Auth::user()->hasRole('process-owner'))
				<div class="col-lg-3">
				{{ Form::select('client_id',$client_list,$client_id,['class'=>'form-control']) }}
				</div>
			@endif
			    <div class="row">
					<div class="col-sm-2">
						<select class="form-control" name="partner" required="required" onchange="getLocation(this.value);">
							<option value="0">Select a Partner</option>
							@foreach($all_partners as $partner):
							<option value="{{$partner->id}}">{{$partner->name }}</option>
							@endforeach;
							<?php 
							if(Auth::user()->hasRole('client') || Auth::user()->hasRole('process-owner')){
								?>
							<option value="all">All</option>

								<?php
							}
							?>
						</select>
					</div>
					<div class="col-sm-2">
						<select class="form-control" name="lob" id="lob" required>
							<option value="0">Select LOB</option>							
						</select>
					</div>
					<div class="col-sm-2">
						<select class="form-control" name="location" id="location" required>
							<option value="0">Select a Location</option>							
						</select>
					</div>
					<div class="col-sm-2">
						<select class="form-control"  name="process" id="process" onchange="getCycle(this.value);" required>
							<option value="0">Select a Process</option>
							<option ></option>
						</select>
					</div>				
					<div class="col-sm-2">
						<select class="form-control" name="date"  id="audit_cycle" required="required" >
							<option value="0">Select Audit Cycle</option>
							<option ></option>
						</select>
					</div>	
					<div class="col-sm-2">
						<input type="submit" style="width: 100px;" class="btn btn-outline-brand d-block" value="Search">
					</div>				
				</div>						
			</div>
		{{ Form::close() }}
	</div>
</div>

@if($data)
<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Report :- Score || Fatal Count
			</h3>
		</div>
	</div>
	<div class="kt-portlet__body">

		<!--begin: Datatable -->
		<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_2">
			<thead>
				<tr>
					<th>Sub Parameters</th>	
					@foreach($final_data['despositions'] as $vv)
					<th>{{$vv}}</th>				
					@endforeach
					<th>Overall</th>	
						
				</tr>			
			</thead>
			<tbody>				
				<tr class="lightred"> 
					<td>Audit Count || NA Count </td>					
					@foreach($final_data['audit_count'] as $key=>$audit_count)
					<td>{{$audit_count}} || {{ $final_data['na_counts'][$key] }}</td>
					@endforeach
					<td>{{ $final_data['audit_count_total'] }}</td>					
				</tr>
				<tr class="pink"> 
					<td>Audit Score</td>					
					@foreach($final_data['audit_score'] as $key=>$audit_score)
					<td>{{$audit_score}} %</td>
					@endforeach
					<td>{{ $final_data['audit_score_total'] }} %</td>					
				</tr>				
				@foreach($final_data['data'] as $vv)				
				  @foreach($vv['data'] as $subPara)
				  <?php if($subPara['fatal'] == 1){
					  $color = "rgb(65, 105, 225)";
				  }else {
					  $color = "black";
				  }
				  ?>
                  <tr>
                  	<td style="color:{{$color}}">{{$subPara['name']}}</td>
                  	<?php $sco=0; $scr=0; ?>
                  	@foreach($final_data['despositions'] as $v1)
                  	<?php  $sco+=$subPara['data'][$v1]['scored']; 
                  		$scr+=$subPara['data'][$v1]['scorable']; ?>
                    <td style="color:{{$color}}">{{$subPara['data'][$v1]['score']}} % || {{$subPara['data'][$v1]['fatal_count']}}</td>
                  	@endforeach
                  	<td style="color:{{$color}}"><?php //$subPara['data']['total']
                  	if($scr != 0) { echo round(($sco/$scr)*100); } else {echo 0;} ?> %</td>
                  </tr>
				  @endforeach
				  <tr class="greenblue">
                  	<td><b> {{$vv['name']}}</b></td>
                  	@foreach($final_data['despositions'] as $v1)
                    <td>{{$vv['sum'][$v1]}} %</td>
                  	@endforeach
                  	<td>{{$vv['sum']['total']}} %</td>
                  </tr>		
				@endforeach		
			</tbody>
		</table>
	</div>
</div>
@endif
@endsection
@section('js')
<script>
function getLocation(val) {

	var base_url = window.location.origin;
	if(val != 0) {
		$.ajax({
		  type: "GET",
		  url: base_url + "/dashboard/get_partner_locations1/"+val,
		  success: function(Data){
	          $("#location").html(Data);
	      }
		});

		$.ajax({
		  type: "GET",
		  url: base_url + "/dashboard/get_partner_process1/"+val,
		  success: function(Data){
	          $("#process").html(Data);
	      }
		});

		$.ajax({
		  type: "GET",
		  url: base_url + "/dashboard/get_partner_lob/"+val,
		  success: function(Data){
	          $("#lob").html(Data);
	      }
		});
    }
}

function getCycle(val) {

var base_url = window.location.origin;
if(val != 0) {
	$.ajax({
	type: "GET",
	url: base_url + "/dashboard/get_partner_audit_cycle/"+val,
	success: function(Data){
		$("#audit_cycle").html(Data);
	}
	});

	
}
}
</script>

<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-daterangepicker.js" type="text/javascript"></script>
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
					title: 'Parameter Compliance Report'
				},
				{
					extend: 'pdfHtml5',
					title: 'Parameter Compliance Report'
				},
				{
					extend: 'print',
					title: 'Parameter Compliance Report'
				},
				{
					extend: 'copyHtml5',
					title: 'Parameter Compliance Report'
				},
				{
					extend: 'csvHtml5',
					title: 'Parameter Compliance Report'
				}
			],
		});
	};

	var initTable2 = function() {
		var table = $('#kt_table_2');

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
					title: 'Parameter Compliance Report'
				},
				{
					extend: 'pdfHtml5',
					title: 'Parameter Compliance Report'
				},
				{
					extend: 'print',
					title: 'Parameter Compliance Report'
				},
				{
					extend: 'copyHtml5',
					title: 'Parameter Compliance Report'
				},
				{
					extend: 'csvHtml5',
					title: 'Parameter Compliance Report'
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
@section('css')
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
