@extends('layouts.app_third')
<script src="jquery-3.5.1.js"></script>
@section('sh-title')
Randomizer Report
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

.random{
	width: 500px;
	padding-left:25px;
	margin-top:-10px;
	
}
.values{

	padding-left:15px;
	margin-top:10px;
	font-size: 15px;
	
}
.LblHeading{
  font-weight:600;
  margin-bottom:10px;
}
.table thead th{
  font-weight:600;
}
.rdoLbl{
  display:flex;
  align-items:baseline;
  margin-top:5px;
}

</style>

<div class="row">
  <div class="col-md-12">

    <!--begin::Portlet-->
    <div class="kt-portlet">
      <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
          <h3 class="kt-portlet__head-title">
           Search/Filter
       
        </div>
      </div>

      <!--begin::Form-->
     
    {!! Form::open(
                array(
                'route' => 'qa_allocation', 
                'class' => 'kt-form',
                'role'=>'form',
                'data-toggle'=>"validator",
                'enctype'=>'multipart/form-data')
                ) !!}
        
        <div class="kt-portlet__body">


                    <div class="row">
                                
                    <div class="col-md-4"> 
                    <h6 class="LblHeading">Select Sample for allocation*</h6>
                    <select class="form-control" name="sample_id"  required>
                    <option value="0">Select Sample</option>
                    @foreach($all_samples as $value)
                    <option value="{{$value->id}}">TLC IBC {{$value->created_at}} sample({{count($value->final_sample)}})</option>
                    @endforeach
                    </select>
                    </div>		
                </div>
                <br>
                <h6 class="LblHeading">Select Option For Calls Allocation To Auditors* </h6>
                  	<div class="row">
		                  <div  class="form-group col-md-4">
                     
                        <div class="d-flex align-items-center">
                          <label for="equally" class="rdoLbl">
                          <input type="radio" id="equally" name="materialExampleRadios" value = "equal" onclick ="hide()"> 
                          <div class="pl-4">Equally Distribution</div>
                </label>
                        </div>
			                </div>
							
							
                        <div class="form-group col-lg-4">
                        
                            <div class="d-flex align-items-center">
                              <label for="uneually" class="rdoLbl">
                                <input type="radio" id="uneually" name="materialExampleRadios" value ="unqual" onclick ="show()">
                                <div class="pl-4">Unequal Distribution</div>     
                </label>             
                            </div>
		                  </div>	

                </div>
              <div>

                <table class="table table-bordered table-hover table-checkable" style="width:50%">
               
                    <thead style="text-align: center">
                       
                       <tr style="background-color:#d0d6df;">
                           <th title="Field #1">#</th>
                           
                           <th title="Field #2">
                               Auditor's Name
                           </th>
                           <th>Allocation</th>
                       
                       </tr>
                    </thead>
                   <tbody style="text-align: center">
                   @foreach($qa as $row)
                    <tr>
                    <td>{{$loop->iteration}}</td>

                    <td>

                    
                    {{$row->name}}
                    </td>

                    <td>
                    <input type="hidden" name = "qa_id[]" value ="{{$row->id}}" >
                        <input type="number" class="form-control allocated" name = "allocated[]" min="0" disabled="disabled" >
                    </td>

                       </tr>
                       @endforeach
                   </tbody>
                </table>
              </div>
                    <div class="col-sm-2">
                    <br>    
                    <input type="submit" name="submit" style="width: 100px;" class="btn btn-outline-brand d-block" >
                    </div>				
                 
   
        
      </form>

      <!--end::Form-->
    </div>

    <!--end::Portlet-->
  </div>
</div>


	
@endsection



@section('css')
@include('shared.table_css')
@endsection
@section('js')
@include('shared.table_js')
<script type="text/javascript">
var start_date = '';
var end_date = '';
$(function() {
  $("#datepicker123").daterangepicker({
    opens: 'right'
  }, function(start, end, label) {
      start_date = start.format('YYYY-MM-DD');
      end_date = end.format('YYYY-MM-DD');
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});
</script>
@endsection
<script>
	function show(){

    
    var uneually = document.getElementById("uneually");
        var items = document.getElementsByClassName("allocated"); 
        
        //var items = document.getElementsByClassName(className);
    for (var i=0; i < items.length; i++) {
      items[i].disabled = false;
    }
       // allocated.disabled = uneually.checked ? false : true;
      
  }

  function hide(){

    
var uneually = document.getElementById("uneually");
    var items = document.getElementsByClassName("allocated"); 
    
    //var items = document.getElementsByClassName(className);
for (var i=0; i < items.length; i++) {
  items[i].disabled = true;
}
   // allocated.disabled = uneually.checked ? false : true;
  
}
  
</script> 