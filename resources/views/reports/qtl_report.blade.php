@extends('layouts.app')
<link href="/month_picker/monthpicker.css" rel="stylesheet" type="text/css">
@section('sh-title')
QTL Report
@endsection

@section('sh-detail')
List QTL Report
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
                'route' => 'qtl_report', 
                'class' => 'kt-form',
                'role'=>'form',
                'data-toggle'=>"validator",
                'enctype'=>'multipart/form-data')
                ) !!}
        
        <div class="kt-portlet__body">
            <div class = "row">
                <div class="form-group col-md-6">
                    <label>Month*</label>
                    <input type="text" class="form-control"  id="demo-1" name = "target_month" required="required">
                </div>
            </div>
            <div class="kt-form__actions">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
        <div class="kt-portlet__foot">
        </div>
      </form>

	<div class="kt-portlet__body">

		@php($man_days = array())
		@foreach($data as $row)
			
			@if($row->present_days == 0)
				@php($man_days[$row->id] =1254)
			@else
				@php($man_days[$row->id] = $row->auditor_count / $row->present_days)
			@endif
		@endforeach
		@php(asort($man_days ))

		<!--begin: Datatable -->
		<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
			<thead>
				<tr>
						<th title="Field #1">#</th>
						<th title="Field #1">
							Name
						</th>
						<!-- <th title="Field #2">
							Email Id
						</th> -->
						<th title="Field #1">
							Month 
						</th>
						<th title="Field #1">
							Team Size
						</th>
						<th title="Field #1">
							Avg. Man days
						</th>
						<th title="Field #1">
							Team Target
						</th>
						
                        <th title="Field #1">
							Audit Completed
						</th>

						<th title="Field #1">
							Target achieved % 
						</th>
						
						<th title="Field #1">
							Audit per Agent
						</th>
						<th title="Field #1">
							Audit per Man Day
						</th>
						<th title="Field #1">
							Till Date 
						</th>
						<th title="Field #1">
							Rank 
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
                        {{$date}}
					</td>
					<td>
                        {{$row->auditor_count}}
					</td>
					<td>
						@if($row->auditor_count == 0)
                            @php($man_day = 0)
							{{ $man_day }}
                        @else 
							@php($man_day = $row->present_days)

                        {{ $man_day }}
                        @endif
                       
					</td>
                    <td>
                        {{$row->total_target}}
					</td>
					<td>
                        {{$row->total_sum}}
					</td>

					<td>
                        @if($row->total_target == 0)
                            0
                        @else 
                        {{ number_format((float)($row->total_sum/$row->total_target) * 100, 2, '.', '')  }}%
                        @endif
					</td>
					<td>
						@if($row->auditor_count != 0)
                        {{ number_format((float) ($row->total_sum / $row->auditor_count),  2, '.', '')}}
						@endif
					</td>
					<td>
						@if($row->present_days != 0)
                        {{ number_format((float) ($row->total_sum / $row->present_days),  2, '.', '')}}
						@endif
					</td>
					<td>
                    {{date("l jS \of F Y h:i:s A") }}
					</td>
					<td>
						@php($i = 1)
						@foreach($man_days as $key=>$value)
							@if($key == $row->id) 
								{{ $i }}
							@endif
							@php($i++)
						@endforeach

                   
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
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="/month_picker/monthpicker.min.js"></script>
<script>
$('#demo-1').Monthpicker();

</script>
@include('shared.table_js')
@endsection