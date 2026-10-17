@extends('layouts.app')

@section('sh-title')
Language
@endsection

@section('sh-detail')
Master
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
        'route' => 'search_reason_type', 
        'class' => 'kt-form',
        'role'=>'form',
        'data-toggle'=>"validator",
        'enctype'=>'multipart/form-data')
        ) !!}

<div class="kt-portlet__body">
    <div class = "row">
        <div class="form-group col-md-6">
            <select class="form-control" name="reason_type" id="reason_type">
                <option value="#">Select Reason</option>
                <option value="Verient Issue">Verient Issue</option>
                <option value="Nice Issue">Tool (Nice) Issue</option>
                <option value="Network Issue">Network Issue</option>
                <option value="QAViews Issue">QAViews Issue</option>
                <option value="Power Breakup">Power Breakup</option>
                <option value="Others">Others</option>
            </select>
        </div>
    </div>
    <div class="kt-form__actions">
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="{{url('check/down_time')}}" class="btn btn-primary">Reset</a>
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
						<th title="Field #2">Name</th>
                        <th>Date</th>
						<th title="Field #2">Down Time To</th>
						<th title="Field #2">Down Time From</th>
                        <th>Reason Type</th>
                        <th>Reason</th>
                        <th>Status</th>
						<th title="Field #7">Actions</th>
				</tr>
			</thead>
            @if(isset($data))
            <tbody>
				@foreach($data as $row)
				<tr>
					<td>{{$loop->iteration}}</td>
                    <td>{{ $row->auditor->name }}</td>
					<td>{{$row->down_time_date}}</td>
					<td>{{ date('h:i:s A', strtotime($row->down_time_to))}}</td>
					<td>{{date('h:i:s A', strtotime($row->down_time_from))}}</td>
					<td>{{$row->reason_type}}</td>
					<td>{{$row->reason ?? '-'}}</td>
                    <td> {{$row->status == 1 ? 'Approved' : ($row->status == 2 ? 'Rejected' : 'Pending')}}</td>
					<td nowrap>
                        <a href="{{url('down_time/status/1/'.Crypt::encrypt($row->id))}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Approved">
                        	<i class="la la-check-circle"></i>
                        </a>
                        <a href="{{url('down_time/status/2/'.Crypt::encrypt($row->id))}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Reject">
                        	<i class="la la-times-circle"></i>
                        </a>
                        <div style="display: flex;">
                        	{{Form::open([ 'method'  => 'delete', 'url' => url('/delete_down_time/' . Crypt::encrypt($row->id)),'onsubmit'=>"delete_confirm()"])}}
                        	<button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                        		<i class="la la-trash"></i>
                        	</button>
                        </form>
                        </div>
                </td>
            	</tr>
            @endforeach
        </tbody>
        @else
        <tbody>
            @foreach($downTime as $row)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ $row->auditor->name }}</td>
                <td>{{$row->down_time_date}}</td>
                <td>{{ date('h:i:s A', strtotime($row->down_time_to))}}</td>
                <td>{{date('h:i:s A', strtotime($row->down_time_from))}}</td>
                <td>{{$row->reason_type}}</td>
                <td>{{$row->reason ?? '-'}}</td>
                <td> {{$row->status == 1 ? 'Approved' : ($row->status == 2 ? 'Rejected' : 'Pending')}}</td>
                <td nowrap>
                    <a href="{{url('down_time/status/1/'.Crypt::encrypt($row->id))}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Approved">
                        <i class="la la-check-circle"></i>
                    </a>
                    <a href="{{url('down_time/status/2/'.Crypt::encrypt($row->id))}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Reject">
                        <i class="la la-times-circle"></i>
                    </a>
                    <div style="display: flex;">
                        {{Form::open([ 'method'  => 'delete', 'url' => url('/delete_down_time/' . Crypt::encrypt($row->id)),'onsubmit'=>"delete_confirm()"])}}
                        <button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                            <i class="la la-trash"></i>
                        </button>
                    </form>
                    </div>

            </td>
            </tr>
        @endforeach

    </tbody>
        @endif
		
    </table>

    <!--end: Datatable -->
</div>
</div>
@endsection
@section('css')
@include('shared.table_css');
@endsection
@section('js')
@include('shared.table_js');
@endsection