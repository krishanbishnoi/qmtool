@extends('layouts.app')

@section('sh-title')
Report
@endsection

@section('sh-detail')
QC Report
@endsection

@section('main')
<div class="kt-portlet kt-portlet--mobile">
{!! Form::open(
                  array(
                    'route' => 'change_location_agent', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator",
                    'enctype'=>'multipart/form-data')
                  ) !!}
      

        <div class="kt-portlet__body">
                    
           
<br><br>
          <div class="row">

            <div class="col-6">
            <div class="form-group">
          <label>Select Process*</label>
                    <select class="form-control" name="process_id" id = "process_id" onchange="onChangeProcess(this.value);" required="required">
                    </select>
          </div>

          <div class="form-group">
          <label>Select Location*</label>
                    <select class="form-control" name="location_id" id = "location_id" onchange="onChangeClient(this.value);" required="required">
                        
                    </select>
          </div>
            </div>
            <div class="col-6">
            <div class="form-group">
                    <label>Agent*</label>
                    <input type="text" class="form-control" name= "agent" >
          </div>
          <div class="form-group">
                    <label>Month*</label>
                    <input type="text" class="form-control" name= "month" placeholder="yyyy-mm">
          </div>
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

      @if($after_post == 1)
      <a href="/new_loc_agent/{{$month}}/{{$process_id}}/{{$agent}}/{{$location}}">Change All location or Parner bulk</a>           
      @endif
      </div>
<div class="kt-portlet kt-portlet--mobile">
								<div class="kt-portlet__head">
									<div class="kt-portlet__head-label">
										<h3 class="kt-portlet__head-title">
											Details
										</h3>
									</div>
								</div>
								<div class="kt-portlet__body">

									<!--begin: Datatable -->
									<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_2">
										<thead>

											<tr>
												<th>#</th>
												<th>Call id</th>
												<th>Agent Name</th>
												<th>audit date</th>
												
												<th>location</th>
												<th>action</th>
												
											</tr>
										</thead>
										<tbody>
											@foreach($data as $vv)
											<tr>
												<td>{{$loop->iteration}}</td>
												<td>{{$vv['call_id']}}</td>
												<td>{{$vv['agent_name']}}</td>
												<td>{{$vv['audit_date']}}</td>
												
                                                <td>{{$vv['location']}}</td>
												<td><a href="/new_loc/{{$vv['id']}}/{{$vv['raw_data_id']}}/{{$vv['partner_id']}}/{{$vv['location']}}">Edit</a></td>
												
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>

							@endsection

@section('js')
<script src="/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/crud/datatables/basic/scrollable.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>

<script>
(function() {
	
    var base_url = window.location.origin;
    
    console.log("hiii");
    $.ajax({
        type: "GET",
        url: "/target/get_process/"+{{Auth::User()->company_id}},
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


@endsection
@section('css')
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
