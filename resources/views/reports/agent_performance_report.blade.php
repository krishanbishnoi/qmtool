@extends('layouts.app')

@section('sh-title')
Report
@endsection

@section('sh-detail')
Agent Performance Quartile Report
@endsection

@section('main')
 
<div>
	<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Search / Filter
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<form id="form_filters">

				<div class="row">
					<div class="col-sm-2">
						<select class="form-control" id="partner" required="required" onchange="getLocation(this.value);">
							<option value="0">Select a Partner</option>
							@foreach($all_partners as $partner):
							<option value="{{$partner->id}}">{{$partner->name }}</option>
							@endforeach;
							<option value="all">ALL</option>
						</select>
					</div>
					<div class="col-sm-2">
						<select class="form-control" id="lob" required="required">
							<option value="0">Select Lob</option>							
						</select>
					</div>
					<div class="col-sm-2">
						<select class="form-control" id="location" required="required">
							<option value="0">Select a Location</option>							
						</select>
					</div>
					<div class="col-sm-2">
						<select class="form-control"  id="process" onchange="getCycle(this.value);" required="required">
							<option value="0">Select a Process</option>
							<option ></option>
						</select>
					</div>
					<div class="col-sm-2">						
						<select class="form-control" id="range">
							<option value=""> Select Bucket </option>
							<option value="0"> All </option>
							<option value="1">0 to 40 % </option>
							<option value="2">41 to 60 %</option>
							<option value="3">61 to 80 %</option>
							<option value="4">Greater than 80 %</option>
					    </select>						
					</div>
					<div class="col-sm-2">
						<select class="form-control" name="audit_cycle"  id="audit_cycle" required="required" >
							<option value="0">Select Audit Cycle</option>
							<option ></option>
						</select>
					</div>				
				</div>				
				</br>	
				<!--<div class="row">
					<div class="col-md-2 col-md-offset-10">					
						<button type="button" onclick="getExcel();" style="width: 100%;" class="btn btn-outline-brand d-block"><i class="fa fa-file"></i> Get Excel </button>
					</div>
				</div>-->
				<div class="row">
					<div class="col-md-2 col-md-offset-10">	
						<button type="button" onclick="getReport();" style="width: 100%;" class="btn btn-outline-brand"><i class="fa fa-search"></i> Search</button>	
					</div>
				</div>	
			</form>
			</div>
		</div>


		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Report :- <span class="text-danger">Agent Performance Quartile Report</span>
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body" id="table_body">
				
			</div>
		</div>
	
</div>
<form action="{{url('/').'/test_excel'}}"  id="getExcelReport" method="post">
	<input type="hidden" name="partner_id" id="partner_id" />
	<input type="hidden" name="location_id" id="location_id" />
	<input type="hidden" name="process_id" id="process_id" />
	<input type="hidden" name="date" id="date" />
	<input type="hidden" name="range" id="range_data" />
</form>
</form>
@endsection

@section('js')
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-daterangepicker.js" type="text/javascript"></script>
@include('shared.table_js')
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
function getReport() {
	

	var base_url = window.location.origin;
	var range_value=document.getElementById("range").value;
	var date = document.getElementById("audit_cycle").value;
	var partner_id = document.getElementById("partner").value;
	var process_id = document.getElementById("process").value;
	var location_id = document.getElementById("location").value;
	var lob = document.getElementById("lob").value;
             KTApp.blockPage({
                overlayColor: '#000000',
                type: 'v2',
                state: 'primary',
                message: 'Processing...'
            	});
	$.ajax({
		  type: "GET",
		  url: base_url + "/dashboard/get_agent_report",
		  headers: {        
            'X-CSRF-TOKEN':$('meta[name=csrf-token]').attr("content")        
          },
		  data:{ range:range_value, date:date, partner_id:partner_id, process_id:process_id, location_id:location_id,lob:lob},
		  success: function(Data){
		  	KTApp.unblockPage();
	        $("#table_body").html(Data);
	      }
	});
}

function getExcel() {	

	var base_url = window.location.origin;
    
    var range_value=document.getElementById("range").value;
	$("#range_data").val(range_value);
	var date = document.getElementById("kt_daterangepicker_1").value;
	$("#date").val(date);
	var partner_id = document.getElementById("partner").value;
	$("#partner_id").val(partner_id);
	var process_id = document.getElementById("process").value;
	$("#process_id").val(process_id);
	var location_id = document.getElementById("location").value;
	$("#location_id").val(location_id);
    $('#getExcelReport').submit();
	/*$.ajax({
		  type: "GET",
		  url: base_url + "/test_excel",
		  headers: {        
            'X-CSRF-TOKEN':$('meta[name=csrf-token]').attr("content")        
          },
		  data:{ range:range_value, date:date, partner_id:partner_id, process_id:process_id, location_id:location_id },
		  success: function(Data){
		  		KTApp.unblockPage();
		  			        
	      }
	});*/
}
</script>
@endsection
@section('css')
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@include('shared.table_css')
@endsection
