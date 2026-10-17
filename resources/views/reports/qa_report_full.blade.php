@extends('layouts.app')
<link href="/month_picker/monthpicker.css" rel="stylesheet" type="text/css">
@section('sh-title')
QA Report
@endsection

@section('sh-detail')
List QA Performance Report 
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
		
	</div>

    {!! Form::open(
                array(
                'route' => 'qa_performance', 
                'class' => 'kt-form',
                'role'=>'form',
                'data-toggle'=>"validator",
                'enctype'=>'multipart/form-data')
                ) !!}
        
			<div class="kt-portlet__body">
				
				<div class="form-group row">
				
					<div class="col-lg-3">
					<input type='text' class="form-control" name="target_month" id="kt_daterangepicker_1" required="required"/>
					</div>
					
					<div class="col-lg-3">
					<button type="submit" class="btn btn-outline-brand"><i class="fa fa-search"></i> Get Data</button>
				</div>
				</div>	
				<!-- <div class="kt-form__actions">
					<button type="submit" class="btn btn-primary">Search</button>
				</div> -->
			</div>
        <div class="kt-portlet__foot">
        </div>
      </form>

	<div class="kt-portlet__body">

		@php($avg_time = array())
		@foreach($data as $row)
			
			@if($row->audits == 0)
				@php($avg_time[$row->id] =1254)
			@else
				@php($avg_time[$row->id] = $row->total_time / $row->audits)
			@endif
		@endforeach
		@php(asort($avg_time ))

		@php($avg_audit_day = array())
		@foreach($data as $row)
			
			@if($row->present_days == 0)
				@php($avg_audit_day[$row->id] =0)
			@else
				@php($avg_audit_day[$row->id] = $row->audits / $row->present_days)
			@endif
		@endforeach
		@php(arsort($avg_audit_day ))			

				
				
		<!--begin: Datatable -->
		<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
			<thead>
				<tr>
					<th title="Field #1">#</th>
					<th title="Field #2">
						Name
					</th>
					
					<th title="Field #2">
						Present Days
					</th>
					<th title="Field #2">
						Total Spent time 
					</th>
					<th title="Field #2">
						Total Audits
					</th>
					<th title="Field #2">
						Avg. Time/Audit
					</th>
					<th title="Field #2">
						Rank (Time/Audit)
					</th>
					
					<th title="Field #2">
						Rank(audits/present)
					</th>
					<th title="Field #2">
						Submited/day
					</th>
					<th title="Field #2">
						Average time Sepent/day
					</th>
					<th title="Field #2">
						Target
					</th >
					<th title="Field #2">
						Target Achieved
					</th>

				</tr>
			</thead>
			<tbody>
				

				@foreach($data as $row)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td>
						{{$row->name}}
					</td>
					
					<td>
						{{$row->present_days}}
					</td>
					<td>
						@if(gettype($row->total_time) == 'NULL')
							
						@elseif($row->total_time <= 60)
							{{$row->total_time}} Min.
						@else
							{{ (int)($row->total_time / 60) }} Hours, {{ $row->total_time % 60 }} Min.
						@endif

					</td>
					
                    <td>
						{{$row->audits}}
					</td>
					<td>
						@if($row->audits == 0)
							
						@else
						{{ (int)($row->total_time / $row->audits)}} Min.
						@endif
					</td>
					<td>
						@php($i = 1)
						@foreach($avg_time as $key=>$value)
							@if($key == $row->id) 
								{{ $i }}
							@endif
							@php($i++)
						@endforeach
						
					</td>
					
					<td>

						@php($j = 1)
						@foreach($avg_audit_day as $key=>$value)
							@if($key == $row->id) 
								{{ $j }}
							@endif
							@php($j++)
						@endforeach
					</td>
					
					<td>
					@if($row->present_days != 0)
							{{ (int) ($row->audits/ $row->present_days)}}
						@endif
					</td>
					<td>
						@if($row->present_days != 0)
							@if($row->total_time/$row->present_days < 60)
								{{ (int) ($row->total_time/$row->present_days) }} Min.
							@else 
								{{ (int)(($row->total_time/$row->present_days) / 60) }} Hours, {{ ($row->total_time/$row->present_days) % 60 }} Min.
							@endif
						@endif
					</td>

					<td>
						{{ $row->qa_target }} 
					</td>
					<td>
						@if($row->qa_target != 0)
							{{ number_format((float)($row->audits/$row->qa_target) * 100, 2, '.', '')  }}%
						@endif
					</td>
            	</tr>
            @endforeach
        </tbody>
    </table>

    <!--end: Datatable -->
</div>
</div>
@endsection
@section('css')
@include('shared.table_css')
@endsection
@section('js')
<script src="/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/crud/datatables/basic/scrollable.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-daterangepicker.js" type="text/javascript"></script>
@endsection
@section('css')
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
{!! Html::script('assets/app/custom/general/crud/datatables/extensions/buttons.js')!!}
@endsection