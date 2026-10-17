@extends('layouts.app')

@section('sh-title')
Main Audited Pool
@endsection

@section('sh-detail')
Call
@endsection

@section('main')



<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
            Search / Filter
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        {{ Form::open(['url' => 'audit_cases_list', 'method' => 'post']) }}
            @csrf
            <div class="form-group row">        
                <div class="row">                
                    <div class="col-sm-6">
                        <select class="form-control" name="process" id="process" required>
                            <option value="">Select a Process</option>
                            @foreach ($process as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>        
                    
                    <div class="col-sm-2">
                        <input type="submit" style="width: 100px;" class="btn btn-outline-brand d-block" value="Search">
                    </div>                
                </div>                        
            </div>
        {{ Form::close() }}
    </div>
</div>

@if($data)
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
   
    <div class="kt-portlet__body">
        <form method="POST" action="{{ route('save_audit_cases') }}">
            @csrf
        
            <div class="form-group">
                <label for="existing_case"><b>Select Case</b></label>
                <select class="form-control" id="existing_case" name="existing_case" onchange="toggleCaseNameField()">
                    <option value="">-- Select Case --</option>
                    @foreach($cases as $case)
                        <option value="{{ $case->id }}">{{ $case->case_name }}</option>
                    @endforeach
                </select>
            </div>
        
            <div class="form-group" id="new_case_name_group" style="display: none;">
                <label for="new_case_name"><b>New Case Name</b></label>
                <input type="text" class="form-control" id="new_case_name" name="new_case_name" readonly>
            </div>
        
            <input type="hidden" name="process_id" value="{{ request('process') }}">
        
            <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                <thead>
                    <tr>
                        <th></th>
                        <th>Audit Date</th>
                        <th>Call ID</th>
                        <th>Agent Name</th>
                        <th>Score With FATAL</th>
                        <th>Score Without FATAL</th>
                        <th>Partner</th>
                        <th>Location</th>
                        <th>Case No.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                    <tr>
                        <td>
                            <input type="checkbox" name="audit_raw_data_ids[]" value="{{ $row->id }},{{ $row->raw_data->id }}">
                        </td>
                        <td>{{ $row->created_at }}</td>
                        <td>{{ $row->raw_data->call_id }}</td>
                        <td>{{ $row->raw_data->agent_name }}</td>
                        <td>{{ ($row->is_critical == 1) ? 0 : $row->overall_score . "%" }}</td>
                        <td>{{ $row->overall_score }} %</td>
                        <td>{{ $row->partner->name }}</td>
                        <td>{{ $row->raw_data->location }}</td>
                        <td>-</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <!--end: Datatable -->
    </div>
</div>
@endif


@endsection
@section('css')
@include('shared.table_css');
@endsection
@section('js')
@include('shared.table_js');
<script>
    function toggleCaseNameField() {
        var existingCaseSelect = document.getElementById('existing_case');
        var newCaseNameGroup = document.getElementById('new_case_name_group');
        var newCaseNameInput = document.getElementById('new_case_name');
    
        if (existingCaseSelect.value === "") {
            newCaseNameGroup.style.display = 'block';
            newCaseNameInput.value = generateNextCaseName();
        } else {
            newCaseNameGroup.style.display = 'none';
            newCaseNameInput.value = '';
        }
    }
    
    function generateNextCaseName() {
        var lastCaseName = "{{ $cases->last() ? $cases->last()->case_name : 'CASE0' }}";
        var lastNumber = parseInt(lastCaseName.slice(1));
        return 'CASE' + (lastNumber + 1);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('existing_case').value === "") {
            document.getElementById('new_case_name_group').style.display = 'block';
            document.getElementById('new_case_name').value = generateNextCaseName();
        }
    });
    </script>
@endsection