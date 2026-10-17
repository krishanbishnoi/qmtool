@extends('layouts.app')

@section('sh-title')
QA Target
@endsection

@section('sh-detail')
Set QA Target
@endsection

@section('main')
<div class="row">
  <div class="col-md-12">

    <!--begin::Portlet-->
    <div class="kt-portlet">
      <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
          <h3 class="kt-portlet__head-title">
            Details
          </h3>
        </div>
      </div>

      <!--begin::Form-->
      
    {!! Form::open(
                array(
                'route' => 'save_qa_target', 
                'class' => 'kt-form',
                'role'=>'form',
                'data-toggle'=>"validator",
                'enctype'=>'multipart/form-data')
                ) !!}
        
        <div class="kt-portlet__body">
        
            <div class = "row">
                <div class="form-group col-md-6">
                    <label>Select Client*</label>
                    <select class="form-control" name="client_id" id = "client_id" onchange="onChangeClient(this.value);" required="required">
                        
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label>Select Process*</label>
                    <select class="form-control" name="process_id" id = "process_id" onchange="onChangeProcess(this.value);" required="required">
                    </select>
                </div>
            
            </div>

            <div class = "row">
                <div class="form-group col-md-6">
                    <label>Select Partner*</label>
                    <select class="form-control" name="partner_id" id = "partner_id" onchange="onChangePartner(this.value);" required="required">
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label>Select Audit Cycle*</label>
                    <select class="form-control" name="audit_cycle_id" id = "audit_cycle_id" required="required">
                    </select>
                </div>
            
            </div>
            
            <div class = "row">
                <div class="form-group col-md-6">
                    <label>Select LOB*</label>
                    <select class="form-control" name="lob" id = "lob" required="required">
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label>Select Brand*</label>
                    <select class="form-control" name="brand" id = "brand" required="required">
                    </select>
                </div>
            </div>
            

            
            <div class = "row">
                <div class="form-group col-md-6">
                    <label>Select Circle*</label>
                    <select class="form-control" name="circle" id = "circle" required="required">
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label>Enter Target*</label>
                    <input type="number" class="form-control" name = "target" required="required">
                </div>
            </div>
           
          
          
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

    $.ajax({
        type: "GET",
        url: "/target/get_audit_cycle/"+client_id+"/"+val,
        success: function(Data){
            
            $("#audit_cycle_id").html(Data);
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

<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
@endsection