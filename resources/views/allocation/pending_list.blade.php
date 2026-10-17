@extends('layouts.app')

@section('sh-title')
Pending Call List
@endsection

@section('sh-detail')
Pending Call List
@endsection

@section('main')

<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head kt-portlet__head--lg">
		<div class="kt-portlet__head-label">
			<span class="kt-portlet__head-icon">
				<i class="kt-font-brand flaticon2-line-chart"></i>
			</span>
			<h3 class="kt-portlet__head-title">
				List
			</h3>
		</div>
		<!-- <div class="kt-portlet__head-toolbar">
			<div class="kt-portlet__head-wrapper">
				<div class="kt-portlet__head-actions">
					<a href="{{url('skill/create')}}" class="btn btn-brand btn-elevate btn-icon-sm">
						<i class="la la-plus"></i>
						New Record
					</a>
				</div>
			</div>
		</div> -->
	</div>
	<div class="kt-portlet__body">

		
		<form method="post" action="{{url('allocation/reassign_search')}}" id="form">
			@csrf
			<input type="hidden" name="batch_id" value = "{{$batch_id}}">
			<div class="row">
				<div class="col-2 mb-2">
					<input class="form-control" type="text" name="search" id = "search" placeholder="Search">
				</div>
				<div class="col-1 mb-2">
					<button class="btn btn-md btn-primary form-control" type> Search</button>
					
				
				</div>
			</div>
		</form>

		<form method="post" action="{{url('allocation/reassign_multiple')}}" id="form">
			@csrf
			<input type="hidden" name="batch_id" value = "{{$batch_id}}">
			<hr>	
		
		<br>
		
		<!--begin: Datatable -->
		<table class="table table-striped- table-bordered table-hover table-checkable" >
			<thead>
				<tr>
				<th><input type="checkbox" onclick="secall()" id="check_0" name="raw_data[0]" value="1"><label for="check_0">All</label></th>
						<th title="Field #1">#</th>
						
						<th title="Field #4">
							Call Id
						</th>
						<th title="Field #4">
							Audit Date
						</th>
						<th title="Field #5">
							Allocated QA
						</th>						
						
				</tr>
			</thead>
			<tbody>
				@foreach($data as $row)
				<tr>
				<td><input type="checkbox" class="checkSec" id="check_<?=$row->id; ?>"  name="raw_data[<?=$row->id; ?>]" value="1" ><label for="check_<?php echo $row->id; ?>"></label></td>
					<td>{{$loop->iteration}}</td>
					<td>{{$row->call_id}} </td>					
					<td>{{$row->dump_date}} </td>	
					<td>
						@foreach($allQa as $qa)
						<?php if($qa->user_id == $row->qa_id) { echo $qa->getUser->name; } ?> 
						@endforeach  
					</td>			
					
            	</tr>
            @endforeach

        </tbody>
	</table>
	
	
	<div class="row">
			
		<div class="col-2 mb-2">
			<select name="qa_id"  class="form-control">
				<option value="0">Allocate to QA</option>
				@foreach($allQa as $qa)
				<option value="{{$qa->user_id}}"> {{ $qa->getUser->name }}</option>
				@endforeach 
			</select>
		</div>
		<div class="col-2 mb-2">
			<button class="btn btn-md btn-primary form-control" type="submit"> Allocate</button>
		</div>
	</div>

				
				<!-- <input class="form-control" type="submit" name="search" id = "search">
				-->
			
			
	</form>
    <!--end: Datatable -->
</div>
</div>
@endsection
@section('css')
@include('shared.table_css');
@endsection
@section('js')
@include('shared.table_js');
<script>

	 function secall() {

      if(document.getElementById("check_0").checked == true) {
        $(".checkSec").prop("checked",true);
      } else {
        $(".checkSec").prop("checked",false);
      }
    }
</script>

@endsection