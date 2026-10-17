@extends('layouts.app')

@section('sh-title')
Monthly Trending Report
@endsection

@section('sh-detail')
<div id="report_title"></div>
@endsection
@section('main')
<style>
table.dataTable thead .sorting:after,
table.dataTable thead .sorting:before,
table.dataTable thead .sorting_asc:after,
table.dataTable thead .sorting_asc:before,
table.dataTable thead .sorting_asc_disabled:after,
table.dataTable thead .sorting_asc_disabled:before,
table.dataTable thead .sorting_desc:after,
table.dataTable thead .sorting_desc:before,
table.dataTable thead .sorting_desc_disabled:after,
table.dataTable thead .sorting_desc_disabled:before {
  bottom: .5em;
}
</style>
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
						<label>Select Report Type:</label>
						<select class="form-control" id="report_type" required="required">
							<option value="0">Select a report type</option>
							<option value="1">Parameter Wise</option>
							<option value="2">Sub Parameter Wise</option>
							<option value="3">Agent Wise</option>
							<!--<option value="4">Partner Wise</option>
							<option value="5">Circle Wise</option>-->
						</select>
					</div>
					<div class="col-sm-2">
						<label>Select a Partner:</label>
						<select class="form-control" id="partner" required="required" onchange="getLocation(this.value);">
							<option value="0">Select a Partner</option>
							@foreach($all_partners as $partner):
							<option value="{{$partner->id}}">{{$partner->name }}</option>
							@endforeach;
							<option value="all">ALL</option>
						</select>
					</div>
					<div class="col-sm-2">
						<label>Select LOB:</label>
						<select class="form-control" id="lob" required="required">
							<option value="0">Select Lob</option>							
						</select>
					</div>
					<div class="col-sm-2">
						<label>Select a Location:</label>
						<select class="form-control" id="location" required="required">
							<option value="0">Select a Location</option>							
						</select>
					</div>
					<div class="col-sm-2">
						<label>Select a Process:</label>
						<select class="form-control"  id="process" onchange="getCycle(this.value);" required="required">
							<option value="0">Select a Process</option>
							<option ></option>
						</select>
					</div>

					<div class="col-sm-2">
						<label>Select Audit Cycle:</label>
						<select class="form-control" name="audit_cycle"  id="audit_cycle" required="required" >
							<option value="0">Select Audit Cycle</option>
							<option ></option>
						</select>
					</div>

				
				</div>
			    </br>
			    <div class="row">
			    	
					<div class="col-md-2 col-md-offset-10">
						<button type="button" onclick="getReport();" style="width: 100%;" class="btn btn-outline-brand d-block"><i class="fa fa-search"></i> Search</button>
						<!--<button type="button" onclick="getExcel();" class="btn btn-outline-brand"><i class="fa fa-file"></i> Get Excel </button>-->
					</div>	
			    </div>			    
			</form>
			</div>
		</div>


		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Report :- <span class="text-danger">Monthly Trending Report</span>
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body" id="table_body">
				
			</div>
		</div>
	
</div>
<!--<form action="{{url('/').'/test_excel'}}"  id="getExcelReport" method="post">
	<input type="hidden" name="partner_id" id="partner_id" />
	<input type="hidden" name="location_id" id="location_id" />
	<input type="hidden" name="process_id" id="process_id" />
	<input type="hidden" name="date" id="date" />
	<input type="hidden" name="range" id="range" />
</form>
</form>-->
@endsection

@section('js')
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-daterangepicker.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/components/extended/blockui.js" type="text/javascript"></script>
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

$(document).ready(function () {
  $('#dtBasicExample').DataTable();
  $('.dataTables_length').addClass('bs-select');
});

function getReport() {
	KTApp.blockPage({
                overlayColor: '#000000',
                type: 'v2',
                state: 'primary',
                message: 'Processing...'
            	});
	var base_url = window.location.origin;
	var partner_id = document.getElementById("partner").value;
	var process_id = document.getElementById("process").value;
	var location_id = document.getElementById("location").value;
	var audit_cycle = document.getElementById("audit_cycle").value;
	
	var report_type = document.getElementById("report_type").value;
	var lob = document.getElementById("lob").value;
	if(report_type == 1) {
		$.ajax({
			type: "POST",
			url: base_url + "/report/mtr_data_parameter_wise",
			headers: {        
	            'X-CSRF-TOKEN':$('meta[name=csrf-token]').attr("content")        
	        },
			data:{ audit_cycle:audit_cycle, partner_id:partner_id, process_id:process_id, location_id:location_id,lob:lob },
			success: function(Data){
			  	KTApp.unblockPage();
		        $("#table_body").html(Data);
				$("#report_title").html("Parameter Wise");
		    }
		});


	}
	else if(report_type == 2) {
		$.ajax({
			type: "POST",
			url: base_url + "/report/mtr_data_sub_parameter_wise",
			headers: {        
	            'X-CSRF-TOKEN':$('meta[name=csrf-token]').attr("content")        
	        },
			data:{ audit_cycle:audit_cycle, partner_id:partner_id, process_id:process_id, location_id:location_id,lob:lob },
			success: function(Data){
			  	KTApp.unblockPage();
			  	$("#table_body").html(Data);
				  $("#report_title").html("Sub-Parameter Wise");
		    }
		});
	}
	else if(report_type == 3) {
		$.ajax({
			type: "POST",
			url: base_url + "/report/mtr_data_agent_wise",
			headers: {        
	            'X-CSRF-TOKEN':$('meta[name=csrf-token]').attr("content")        
	        },
			data:{ audit_cycle:audit_cycle, partner_id:partner_id, process_id:process_id, location_id:location_id,lob:lob },
			success: function(Data){
			  	KTApp.unblockPage();
		        $("#table_body").html(Data);
				$("#report_title").html("Agent Wise");
		    }
		});
	}
	else {
		alert("Invalid report type selection...");
		KTApp.unblockPage();
	}
}
</script>
@endsection
@section('css')
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@include('shared.table_css')
@endsection
