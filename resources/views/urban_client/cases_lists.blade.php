@extends('layouts.app')

@section('sh-title')
    Main Audited Pool
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
                    Cases List
                </h3>
            </div>
            <!-- <div class="kt-portlet__head-toolbar">
       <div class="kt-portlet__head-wrapper">
        <div class="kt-portlet__head-actions">
         <a href="{{ url('skill/create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
          <i class="la la-plus"></i>
          New Record
         </a>
        </div>
       </div>
      </div> -->
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Case Name</th>
                        <th>Case Created Date</th>
                        <th>Partner</th>
                        <th>Location</th>
                        <th>Overall Score With FATAL</th>
                        <th>Overall Score Without FATAL</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($caseData as $index => $case)                       
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $case['case_name'] }}</td>
                            <td>{{ $case['case_created_at'] }}</td>                           
                            <td>{{ $case['partners'] }}</td>
                            <td>{{ $case['locations'] }}</td>                   
                            <td>{{ $case['overall_score_with_fatal'] }}%</td>
                            <td>{{ $case['overall_score_without_fatal'] }}%</td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#audits-{{ $index }}">
                                    +
                                </button>
                            </td>
                        </tr>
                        <tr id="audits-{{ $index }}" class="collapse">
                            <td colspan="8">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Audit Date</th>
                                            <th>Call ID</th>
                                            <th>Agent Name</th>
                                            <th>Score With FATAL</th>
                                            <th>Score Without FATAL</th>
                                            <th>Partner</th>
                                            <th>Location</th>   
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($case['audits'] as $audit)   
                                    @php
                                        
                                        // dd($case['audits'] );
                                    @endphp
                                                                   
                                            <tr>
                                                <td>{{ $audit->created_at  ?? 'NA'  }} </td>
                                                <td>{{ $audit->raw_data->call_id    ?? 'NA'  }} </td>
                                                <td>{{ $audit->raw_data->agent_name  ?? 'NA'  }} </td>
                                                <td>{{ $audit->with_fatal_score_per   ?? 'NA' }} </td>
                                                <td>{{ $audit->overall_score  ?? 'NA'  }} </td>
                                                <td>{{ $audit->partner->name   ?? 'NA' }}  </td>
                                                <td>{{ $audit->raw_data->location    ?? 'NA' }} </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
    @include('shared.table_css');
@endsection
@section('js')
    @include('shared.table_js');
@endsection
