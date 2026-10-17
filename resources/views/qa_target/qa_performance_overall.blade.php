@extends('layouts.app')

@section('sh-title')
    Report
@endsection

@section('sh-detail')
    Agent Feedback Report
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
            {{ Form::open(['url' => 'qa_performance_search', 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}
            @csrf
              <div class="kt-portlet__body">
                <div class="form-group">
                    <label>QA Target xlsx(PKT)*</label>
                    <input type="file" name="file" class="form-control" required/>
                    <small><a href="/Upload_pkt_score.xlsx" download="download">Click here to donwload sample file</a></small>
                </div>
              </div>
              <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="reset" class="btn btn-secondary">Cancel</button>
                </div>
              </div>
            {{ Form::close() }}
        </div>
    </div>

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Report: <span class="text-danger"></span>
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped table-bordered table-hover table-checkable" id="kt_table_2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Auditor Name</th>
                        <th>Active Status</th>
                        <th>Current Month Mini Target</th>
                        <th>Current Month  Maxm Target</th>
                        <th>Audit Count</th>
                        <th>Achieved %</th>
                        <th>QC Count</th>
                        <th>QC %</th>
                        <th>Fatal Count</th>
                        <th>Total Rebuttal Count</th>
                        <th>Accpted Rebuttal Count</th>
                        <th>Rebuttal Accept per</th>
                        <th>PKT Scores</th>
                        <th>Incentive Applicable</th>
                        <th>Incentive </th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($auditor_data))
                        @foreach ($auditor_data as $auditor_id => $data)
                            @php
                                $auditor = \App\User::find($auditor_id);
                                $audit_count = $data['audit_counts'] ?? 0;
                                $qc_count = $data['qc_audit_count'] ?? 0;                               
                                $qc_percentage = $data['qc_audits_per'];
                                $fatal_count = $data['fatal_audits'] ?? 0;
                                $rebuttal_count = $data['rebuttal_counts'] ?? 0;

                                $aplicable_for_insentive = '';

                                $doj = new DateTime($auditor->created_at);
                                $today = new DateTime(); 

                                $interval = $doj->diff($today);
                                $daysDifference = $interval->days;
                                $current_month_taget = $auditor->qa_target > 0 ? $auditor->qa_target : 1000; // Minimum Target
                                $max_target = $auditor->max_target > 0 ? $auditor->max_target : 1300; 
                                $cappedAuditCount = min($audit_count, $max_target);

                                // Calculate extra audits eligible for incentive
                                $extraAudits = max(0, $cappedAuditCount - $current_month_taget);
                                $total_incentive_amount  =  $auditor->incentive_amount > 0 ? $auditor->incentive_amount : 1500;
                                // Calculate the incentive amount
                                $incentiveAmountPerAudit =  $extraAudits  > 0 ?   $total_incentive_amount /  $extraAudits : 0;
                                $total_rs = $incentiveAmountPerAudit * $extraAudits;


                                if ($daysDifference > 90 && 
                                    $cappedAuditCount > $current_month_taget && 
                                    $data['rebuttal_counts_per'] > 95 && 
                                    $qc_percentage > 95 && 
                                    $auditor->pkt_scores_4 > 85) {
                                    $aplicable_for_insentive = "Applicable";
                                    $incentiveToBePaid = $total_rs;
                                } else {
                                    $aplicable_for_insentive = "Not Applicable";
                                    $incentiveToBePaid = 0;
                                }

                                // $startDate = new DateTime(date('Y-m-01'));
                                // $endDate = new DateTime(Carbon\Carbon::now()->endOfMonth()->toDateString());
                                // $totalWorkingDays = 0;

                                // while ($startDate <= $endDate) {
                                // if ($startDate->format('N') < 6) {
                                //     $totalWorkingDays++;
                                // }
                                // $startDate->modify('+1 day');
                                // }
                                
                                 //     $update_data = Target::find($request->target_id);
                            //     $check_cycle = AuditCycle::whereDate('end_date', '<=', date("Y-m-d"))->where('id', $update_data->audit_cycle_id)->count();
                            // $startDate = AuditCycle::where('client_id',14)->where('qmsheet_id',$qc_audits_qm_sheet_id)->get();
                            // dd($startDate);

                              

                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $auditor->name ?? 'N/A' }}</td>
                                <td>{{ $auditor->status == 1 ? 'Active' : 'Inactive' }}</td>
                                <td>{{$current_month_taget > 0 ?  $current_month_taget : 'Not Set Target for this Process'}}</td>
                                <td>{{$max_target > 0 ?  $max_target : 'Not Set Target for this Process'}}</td>
                                <td>{{ $audit_count }}</td>
                                <td>{{$current_month_taget > 0 ?  round(($audit_count/$current_month_taget)*100,2) : 'Not Set Target for this Process'}}%</td>
                                <td>{{ $qc_count }}</td>
                                <td>{{ $qc_percentage }}%</td>
                                <td>{{ $fatal_count }}</td>
                                <td>{{ $rebuttal_count }}</td>
                                <td>{{$data['accept_rebuttal'] }}</td>
                                <td>{{$data['rebuttal_counts_per'] }}%</td>
                                <td>{{ $auditor->pkt_scores_4 ?? 'N/A'  }}</td>
                                <td>{{ $aplicable_for_insentive  }}</td>
                                <td>{{ $incentiveToBePaid }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10" class="text-center">No data available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#kt_table_2').DataTable({
                scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
                      <'row'<'col-sm-12'tr>>
                      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
                buttons: [{
                        extend: 'excelHtml5',
                        title: 'Agent Feedback Report'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Agent Feedback Report'
                    },
                    {
                        extend: 'print',
                        title: 'Agent Feedback Report'
                    },
                    {
                        extend: 'copyHtml5',
                        title: 'Agent Feedback Report'
                    },
                    {
                        extend: 'csvHtml5',
                        title: 'Agent Feedback Report'
                    }
                ],
                bSort: false,
                columnDefs: []
            });
        });
    </script>
    <script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js"></script>
    <script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-daterangepicker.js"></script>
    {!! Html::script('assets/vendors/custom/datatables/datatables.bundle.js') !!}
@endsection

@section('css')
    <link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
