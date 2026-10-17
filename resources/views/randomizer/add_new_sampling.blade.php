@extends('layouts.app_third')

<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script type='text/javascript' src='https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js'></script>
<script type='text/javascript' src='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js'></script>
<script type='text/javascript'></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" rel="stylesheet" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/js/ion.rangeSlider.min.js" rel="stylesheet" type="text/css">

<style type="text/css">


.irs--round .irs-bar {
   background-color: #00C2C0;
}

.irs--round .irs-handle {
  background-color: #00C2C0;
  border-color: #00C2C0;
  box-shadow: 0px 0px 0px 5px rgba(0, 194, 192, 0.2);
}

.irs--round .irs-handle.state_hover, 
.irs--round .irs-handle:hover {
   background-color: #00C2C0;
}

.irs--round .irs-handle {
  width: 16px;
  height: 16px;
  top: 29px
}

.irs--round .irs-from, 
.irs--round .irs-to, 
.irs--round .irs-single {
  background-color: transparent;
  color: #666666;
}

.irs--round .irs-from:before, 
.irs--round .irs-to:before, 
.irs--round .irs-single:before,
.irs--round .irs-min, 
.irs--round .irs-max {
  display: none;
}


</style>
@section('sh-title')
Sampling
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
                       
					{!! Form::open(
                array(
                'route' => 'save_sampling', 
                'class' => 'kt-form',
                'role'=>'form',
                'data-toggle'=>"validator",
                'enctype'=>'multipart/form-data')
                ) !!}
				<input type="hidden" name="sampling_id" value="{{$sampling_list->id}}">
						<div class="kt-portlet__body">
									<div class="row">
                                        <div class="col-sm-6">
										 	<div class="form-group">
													<label>Enter Name*</label>
													<input type="text" name="name" class="form-control" value="{{$sampling_list->name}}" required>
													</div>
										 </div>
										
<!-- 									
									<div class=" col-sm-6">
										<div class ="form-group">
										
												<label>Select Process*</label>
												<select class="form-control" name="process" id="process" required >
                                                <option value="0">Select a Process</option>
                                                @foreach($all_process as $process)
                                                <option value="{{$process->id}}" {{ ( $process->id == $selected_process_id) ? 'selected' : '' }}>{{$process->name }}</option>
                                                @endforeach;					
											</select>
											</div>
				                    </div> -->
									<div class=" col-sm-6">
										<div class ="form-group">
										
												<label>Process*</label>
												<input type="text" class="form-control" name="process" value="{{$all_process[0]->name}}" readonly="readonly">
											</div>
				                    </div>
								
		         
                  <div class="col-sm-6">
                  <div class ="form-group">
												<label>Enter FTR %*</label>
												<input type="number" class="form-control" name="ftr" value = "{{$sampling_list->ftr}}" min="0">
												</div>
									</div>

                  <div class="col-sm-6">
                  <div class ="form-group">
												<label>Enter NFTR %*</label>
												<input type="number" class="form-control" name="nftr"  value = "{{$sampling_list->nftr}}" min="0">
												</div>
									</div>
									
									
									<div class="col-sm-6">
									<div class ="form-group">
												<label>Top Volume Disposition*</label>
											
                 
                          <input type="text" class="form-control" name = "top_volume" value="{{$top_volume}}" readonly="readonly">
                                               					 
										
											</div>
									</div>

                  <div class="col-sm-6">
									<div class ="form-group">
												<label>Enter Top Volume Disposition in %*</label>
												<input type="number" class="form-control" name="top_volume"  value = "{{$sampling_list->top_volume}}" min="0">
												</div>
									</div> 
									<div class="col-sm-6">
										<div class ="form-group">
											<label>Enter VOC Range*</label>
											<select class="form-control" name="voc_type[]" multiple>
												<option value="low" <?php if(gettype(array_search("low",$selected_voc)) != "boolean"){echo "selected";}?>>0-2</option>
												<option value="medium" <?php if(gettype(array_search("medium",$selected_voc)) != "boolean"){echo "selected";}?>>3</option>
												<option value="high" <?php if(gettype(array_search("high",$selected_voc)) != "boolean"){echo "selected";}?>>4-5</option>

                        					</select>
										</div>
									</div>
									<!-- <div class="col-sm-6">
									<div class ="form-group">
												<label>High Volume VOC Type*</label>
												<input type="text" class="form-control"  name = "voc_type" value="{{$voc_type}}" readonly="readonly">
											</div>
									</div> -->
                                    <!-- <div class="col-sm-6">
									<div class ="form-group">
												<label>Enter High Volume VOC in %*</label>
												<input type="number" class="form-control" name="voc_score" value = "{{$sampling_list->voc_score}}" min="0">
												</div>
									</div> -->
									
                                   
									<div class="col-sm-6">
									<div class ="form-group">
												<label>Enter Random Samples %*</label>
												<input type="number" class="form-control" name="random_samples" value = "{{$sampling_list->random_samples}}" min="0">
												</div>
									</div>
									
									

									<div class="col-sm-6">
									<div class ="form-group">
												<label>Enter Low AHT In Secs*</label>
												<input type="number" class="form-control" name="low_aht" value = "{{$sampling_list->low_aht}}" min="0">
												</div>
									</div>
									<div class="col-sm-6">
									<div class ="form-group">
												<label>Enter High AHT In Secs*</label>
												<input type="number" class="form-control" name="high_aht" value = "{{$sampling_list->high_aht}}" min="0">
												</div>
									</div>
									
							
							<div class="col-md-12">
							<div class="kt-portlet__foot">
												<div class="kt-form__actions">
												
													<button type="submit"  class="btn btn-primary">Submit</button>
												
													
													<button type="reset" class="btn btn-secondary">Cancel</button>
												</div>
											</div>

											
							</div>
							</div>	
							
							</form>
                    </div>
                </div>
            
        </div>
 

@endsection
@section('css')
@include('shared.table_css')

@endsection
@section('js')
<script type = "application/javascript">


(function() {

var base_url = window.location.origin;
$.ajax({
  type: "GET",
  url: base_url + "/get_call_type",
  success: function(Data){
		var html = '';
	
		
		for(var i=0; i<Data.data1.length; i++){
			html+= '<option value="'+ Data.data1[i] +'">'+Data.data1[i] +'</option>';
		}
	// for(var i=0; i<Data.data2.length; i++){
	// 	html2+='<option value="'+ Data.data2[i] +'">'+Data.data2[i] +'</option>';
	// }
	// for(var i=0; i<Data.data3.length; i++){
	// 	html3+='<option value="'+ Data.data3[i] +'">'+Data.data3[i] +'</option>';
	// }

      $("#agents").html(html);
	

	 
	  console.log(html);
  }
});
})();



var $range = $(".js-range-slider"),
    $from = $(".from"),
    $to = $(".to"),
    range,
    min = $range.data('min'),
    max = $range.data('max'),
    from,
    to;

var updateValues = function () {
    $from.prop("value", from);
    $to.prop("value", to);
};

$range.ionRangeSlider({
    onChange: function (data) {
      from = data.from;
      to = data.to;
      updateValues();
    }
});

range = $range.data("ionRangeSlider");
var updateRange = function () {
    range.update({
        from: from,
        to: to
    });
};

$from.on("input", function () {
    from = +$(this).prop("value");
    if (from < min) {
        from = min;
    }
    if (from > to) {
        from = to;
    }
    updateValues();    
    updateRange();
});

$to.on("input", function () {
    to = +$(this).prop("value");
    if (to > max) {
        to = max;
    }
    if (to < from) {
        to = from;
    }
    updateValues();    
    updateRange();
});






</script>


@endsection