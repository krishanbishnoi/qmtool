@extends('layouts.app')
<link href="/month_picker/monthpicker.css" rel="stylesheet" type="text/css">
@section('sh-title')

@endsection

@section('sh-detail')

@endsection

@section('main')
<div class="row">
  <div class="col-md-8">

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
                    'route' => 'update_loc_par_agent', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator",
                    'enctype'=>'multipart/form-data')
                  ) !!}

                 
        <input type="hidden" name="month" value="{{$month}}">
        <input type="hidden" name="process_id" value="{{$process_id}}">
        <input type="hidden" name="agent" value="{{$agent}}">
        <input type="hidden" name="location" value="{{$location}}">

        <div class="kt-portlet__body">

           <br>
          
          <div class="form-group">
          <label>Select new Partner*</label>
                    <select class="form-control" name="partner_id" id = "process_id" onchange="onChangeProcess(this.value);" required="required">
                    </select>
          </div>

          <div class="form-group">
          <label>Select new Location*</label>
                    <select class="form-control" name="location_id" id = "location_id" onchange="onChangeClient(this.value);" required="required">
                        
                    </select>
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
		
        console.log("hiii");
        $.ajax({
            type: "GET",
            url: "/target/get_partners/9/"+{{Auth::User()->company_id}},
            success: function(Data){
                
                $("#process_id").html(Data);
            }
        });

        $.ajax({
            type: "GET",
            url: "/target/get_location/9/"+{{Auth::User()->company_id}},
            success: function(Data){
                
                $("#location_id").html(Data);
            }
        });
})();


</script>



<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="/month_picker/monthpicker.min.js"></script>
<script>
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