@extends('layouts.app')
<link href="/month_picker/monthpicker.css" rel="stylesheet" type="text/css">
@section('sh-title')
QA Report
@endsection

@section('sh-detail')
List QA Report
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
                'route' => 'qa_report', 
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

		<!--begin: Datatable -->
		<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
			<thead>
				<tr>
						<th title="Field #1">#</th>
						<th title="Field #2">
							Name
						</th>
						<th title="Field #2">
							Email Id
						</th>
						<th title="Field #2">
							Month 
						</th>
						<th title="Field #2">
							Target
						</th>
						
                        <th title="Field #2">
							Audit Completed
						</th>

						<th title="Field #2">
							Target achieved % 
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
						{{$row->qa_email}}
					</td>
					<td>
						{{$row->target_month}}
					</td>

                    <td>
						{{$row->qa_target}}
					</td>
					<td>
						{{$row->count}}
					</td>

					<td>
						{{ number_format((float)($row->count/$row->qa_target) * 100, 2, '.', '')  }}%
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