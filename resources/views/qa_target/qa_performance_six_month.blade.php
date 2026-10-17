@extends('layouts.app')

@section('sh-title')
    Temp Audited Pool
@endsection

@section('sh-detail')
    Call
@endsection

@section('main')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-line-chart"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    My Score For this Month
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                <thead>
                    <tr>
                        <th>Auditor</th>
                        <th>DOJ</th>
                        <th>Active/Inactive</th>
                        @foreach ($months as $month)
                            <th>{{ $month }}</th>
                        @endforeach
                        <th>Total Audits</th>
                        <th>Avg</th>
                    </tr>
                  
                </thead>
             
                <tbody>
                    @php
                         $total_audit_count_row = 0;
                    @endphp
                    @foreach ($auditData as $data)
                        <tr>
                            @php
                            $auditor_name = \App\User::find( $data->auditor_id);
                            $total_audit_count_row = $data->month_1 +  $data->month_2 +  $data->month_3 +  $data->month_4 +  $data->month_5 +  $data->month_6;
                        @endphp
                           <td>{{ $auditor_name->name }}</td>
                           <td>{{ $auditor_name->doj }}</td>
                           <td>{{ $auditor_name->status  == 1 ? 'Active' : 'Inactive' }}</td>
                            <td>{{ $data->month_1 }}</td>
                            <td>{{ $data->month_2 }}</td>
                            <td>{{ $data->month_3 }}</td>
                            <td>{{ $data->month_4 }}</td>
                            <td>{{ $data->month_5 }}</td>
                            <td>{{ $data->month_6 }}</td>
                            <td>{{ $total_audit_count_row}}</td>
                            <td>{{ round($total_audit_count_row/6,2)}}</td>
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
    @include('shared.table_js')
@endsection
