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
</style>

	
	<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Search / Filter
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				
			{!! Form::open(
                array(
                'route' => 'randomizer_report', 
                'class' => 'kt-form',
                'role'=>'form',
                'data-toggle'=>"validator",
                'enctype'=>'multipart/form-data')
                ) !!}
           
				<div class="row">
				
                    <div class="form-group col-md-6"> 
                        <div class="">Select Date Range</div>
                        <input type='text' class="form-control w-100" name="date_range" id="datepicker123" required="required"/>
                    </div>		
                
					
                    <div class="form-group col-md-6">
					<div class="">Process</div>
					<input type="text" class="form-control" name="process" value="{{$all_process[0]->name}}" readonly="readonly">
                	</div>
								
													
				</div>
			    </br>
				<div class="col-md-12 ">
				<a onclick="show()"><button type="submit" class="btn btn-primary float-right" >Create new random sample</button></a>
				</div>
				    
			</form>
			</div>
	</div>
	<div class="kt-portlet kt-portlet--mobile" id ="requested">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Samples List 
			</h3><br>
			
		</div>
	</div>
	<div class="kt-portlet__body">

		
		<!--begin: Datatable -->
		<table class="table table-striped- table-bordered table-hover table-checkable">
			<thead style="text-align: center; background-color:#d0d6df" >
				<tr>
					<th>#</th>
					
					<th>Requested Date</th>
					<th>Process</th>
					<th>Total Randomizer Count
				</th>
				<th>Samples To Be Allocate
				</th>
					<th>Action</th>
					<th>QA  Allocation Report</th>
				
					
				</tr>
			</thead>
			<tbody style="text-align: center">
			@foreach($all_samples as $sample)
				<tr>
				{!! Form::open(
                array(
                'route' => 'final_output', 
                'class' => 'kt-form',
                'role'=>'form',
                'data-toggle'=>"validator",
                'enctype'=>'multipart/form-data')
                ) !!}
				<input type="hidden" value="{{$sample->id}}" name = "sample_id">
					<td>{{$index}}</td>
					<td>{{$sample->created_at}}</td>
					<td>{{$sample->name}}</td>
					
					<td style="text-align: center">
					<input type ="hidden" value ="{{count($sample->total_sample)}}" name = "total_count">{{count($sample->total_sample)}}
				</td>
				<td style="text-align: center">   
					@if(count($sample->final_sample) == 0)
					<input type="number" class="form-control" name = "required_count" min="0" >
					@else
					{{count($sample->final_sample)}}
					@endif
				</td>
					<td>	
						@if(count($sample->final_sample) == 0)
						<button type="submit" class="btn btn-primary">Assign Samples</button>
						
						@else
						<span style="color:grey"> Already Assign </span>
                        @endif	

					</td>
					<td>	
							
						@if($sample->auditor_qa_status == 1)
						<a href="{{url('view_samples/'.Crypt::encrypt($sample->id))}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View Samples">
                        	<i class="la la-eye"></i>
                        </a>
						
						@else
							@if(count($sample->final_sample) != 0)
							<span style="color:grey">Not Assign By TL</span>
							@endif	
                        @endif	
				

					
					</td>


				</form>
				</tr>

				<?php $index-- ?>
			@endforeach
			</tbody>	
		</table>
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
<script>
	function show(){
		document.getElementById("requested").style.display = "block";
	}
</script>
@endsection