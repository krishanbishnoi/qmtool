@extends('layouts.app')
<link href="/month_picker/monthpicker.css" rel="stylesheet" type="text/css">
@section('sh-title')
Monthly Target Uploader
@endsection

@section('sh-detail')
Process action Monthly Target
@endsection

@section('main')
<div class="row">
  <div class="col-md-8">

    <!--begin::Portlet-->
    <div class="kt-portlet">
      <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
          <h3 class="kt-portlet__head-title">
            <p style="color:red">Please upload target in new format!</p>
         
          </h3>
        </div>
      </div>

      <!--begin::Form-->
      
        {!! Form::open(
                  array(
                    'route' => 'upload_month_target', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator",
                    'enctype'=>'multipart/form-data')
                  ) !!}
        <input type="hidden" name="company_id" value="{{Auth::User()->company_id}}">

        <div class="kt-portlet__body">

          

          <div class="form-group">
          <label>Select Client*</label>
                    <select class="form-control" name="client_id" id = "client_id" onchange="onChangeClient(this.value);" required="required">
                        
                    </select>
          </div>
          <div class="form-group">
          <label>Select Process*</label>
                    <select class="form-control" name="process_id" id = "process_id" onchange="onChangeProcess(this.value);" required="required">
                    </select>
          </div>

          <div class="form-group">
              <label>Audit Cycle*</label>
              
              <select class="form-control" name="dump_date"  id="audit_cycle"  required="required" >
                    <option value="0">Select Audit Cycle</option>
                    <option ></option>
                </select>
          </div>

          <div class="form-group">
          <label>Select Partner*</label>
                    <select class="form-control" name="partner_id" id = "partner_id" onchange="onChangePartner(this.value);" required="required">
                    </select>
          </div>

          <div class="form-group">
          <label>Select LOB*</label>
                    <select class="form-control" name="lob" id = "lob" required="required">
                    </select>
          </div>



            <!--  <parents-uploader></parents-uploader> -->

          <div class="form-group">
              <label>Target data xlsx*</label>
              <input type="file" name="raw_data_file" class="form-control" required/>
          </div>
          <span class="form-text text-muted">Max file size:- 50MB. File format:- .xlsx only</span>
          <a class="btn btn-danger" href="/month_target_upload_sample.xlsx" download="munth_target_upload_sample"> Download Sample here</a>

          
        </div>
        <div class="kt-portlet__foot">
          <div class="kt-form__actions">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-secondary">Cancel</button>
          </div>
        </div>
      </form>

      <!--end::Form-->
    </div>

    <!--end::Portlet-->
  </div>
</div>

@endsection
@section('js')
@include('shared.form_js')
<script>
function onChangeClient(val) {

	var base_url = window.location.origin;

    $.ajax({
		type: "GET",
		url: "/target/get_partners/"+val+"/"+{{Auth::User()->company_id}},
		success: function(Data){
			
			$("#partner_id").html(Data);
		}
	});
}

function onChangeProcess(val) {
    var client_id = document.getElementById("client_id").value;
    var base_url = window.location.origin;

    console.log(client_id);
    console.log(' '+val);
    $.ajax({
        type: "GET",
        url: "/target/get_audit_cycle/"+client_id+"/"+val,
        success: function(Data){
            
            $("#audit_cycle").html(Data);
        }
    });
}

function onChangePartner(val) {

    var base_url = window.location.origin;

    $.ajax({
        type: "GET",
        url: "/target/get_lob/"+val,
        success: function(Data){
            
            $("#lob").html(Data);
        }
    });
 
    $.ajax({
        type: "GET",
        url: "/target/get_brand/"+val,
        success: function(Data){
            
            $("#brand").html(Data);
        }
    });

    $.ajax({
        type: "GET",
        url: "/target/get_circle/"+val,
        success: function(Data){
            
            $("#circle").html(Data);
        }
    });

}

(function() {
	
		var base_url = window.location.origin;
		$.ajax({
			type: "GET",
			url: base_url + "/target/get_client/"+{{Auth::User()->company_id}}+"/" + {{Auth::User()->id}},
			success: function(Data){
				$("#client_id").html(Data);
			}
        });
        
        $.ajax({
		type: "GET",
		url: "/target/get_process/"+{{Auth::User()->company_id}},
		success: function(Data){
			
			$("#process_id").html(Data);
		}
    });
})();


</script>



<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="/month_picker/monthpicker.min.js"></script>
<script>
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

$('#demo-1').Monthpicker();
$('#startDate').Monthpicker({
        onSelect: function () {
            $('#endDate').Monthpicker('option', { minValue: $('#startDate').val() });
        }
    });
    $('#endDate').Monthpicker({
        onSelect: function () {
            $('#startDate').Monthpicker('option', { maxValue: $('#endDate').val() });
        }
    });
</script>
@endsection