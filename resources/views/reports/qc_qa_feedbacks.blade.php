@extends('layouts.app')

@section('sh-title', 'Report')

@section('sh-detail', 'QC Report Parameter wise')

@section('main')
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">Filter</h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <form method="post" action="{{ route('qc_qa_feedbacks') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <select class="form-control" id="clients" name="client" required>
                                    <option value="0">Select a Client</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input type="text" readonly class="form-control" id="kt_datepicker_1" name="start_date"
                                    placeholder="Start Date" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input type="text" readonly class="form-control" id="kt_datepicker_2" name="end_date"
                                    placeholder="End Date" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input type="submit" class="btn btn-outline-brand d-block w-100" value="Search">
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            <div class="kt-portlet">
                <div class="kt-portlet__body  kt-portlet__body--fit">
                    <div class="row row-no-padding row-col-separator-xl">

                        <div class="col-md-12 col-lg-3 col-xl-3 card">
                            <div class="kt-widget24">
                                <div class="kt-widget24__details">
                                    <div class="kt-widget24__info">
                                        <h4 class="kt-widget24__title ">Client Name</h4>
                                    </div>
                                    <span class="kt-widget24__stats kt-font-brand"
                                        style="color:black">{{ $client_name }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-3 col-xl-3 card">
                            <div class="kt-widget24">
                                <div class="kt-widget24__details">
                                    <div class="kt-widget24__info">
                                        <h4 class="kt-widget24__title">Total QC Feedback Shared</h4>

                                    </div>
                                    <span class="kt-widget24__stats kt-font-brand"
                                        style="color:black">{{ $total_shared_qc }} </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-3 col-xl-3 card">
                            <div class="kt-widget24">
                                <div class="kt-widget24__details">
                                    <div class="kt-widget24__info">
                                        <h4 class="kt-widget24__title"> Accepted Feedbacks By QA</h4>
                                    </div>
                                    <span class="kt-widget24__stats kt-font-brand"
                                        style="color:black">{{ $total_accepted_feedbacks }} </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-3 col-xl-3 card">
                            <div class="kt-widget24">
                                <div class="kt-widget24__details">
                                    <div class="kt-widget24__info">
                                        <h4 class="kt-widget24__title">Rejected Feedbacks By QA</h4>

                                    </div>
                                    <span class="kt-widget24__stats kt-font-brand"
                                        style="color:black">{{ $total_rejected_feedbacks }} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




            @if ($final_data)
                <div class="kt-portlet kt-portlet--mobile">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">Details</h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <!-- Summary Table -->
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>QC Date</th>
                                    <th>Audit Date</th>
                                    <th>Auditor Name</th>
                                    <th>QC Name</th>
                                    <th>Feedback Given by QC</th>
                                    <th>Previous Score</th>
                                    <th>After QC Score</th>
                                    <th>QC Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($final_data as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item['qc_date'] }}</td>
                                        <td>{{ $item['audit_date'] }}</td>

                                        @php
                                            $qc_name = App\User::where('id',$item['qc_id'])->value('email');

                                        @endphp
                                         <td>{{ $qc_name ?? 'NA' }}</td>


                                        <td>{{ $item['qa_name'] }}</td>
                                        <td>{{ $item['feedback_by_qc'] }}</td>
                                        <td>{{ $item['previous_score'] }}</td>
                                        <td>{{ $item['after_qc'] }}</td>
                                        <td>
                                            @if ($item['qc_reply'] == '1')
                                                Accept
                                            @elseif ($item['qc_reply'] == '0')
                                                Reject
                                            @else
                                                Pending
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>


            @endif
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js"></script>
    {!! Html::script('assets/vendors/custom/datatables/datatables.bundle.js') !!}
    <script>
        $(document).ready(function() {
            $.ajax({
                type: "GET",
                url: "{{ url('/report/get_rdr_client_list') }}",
                success: function(response) {
                    if (response.status == 200) {
                        var clientsDropdown = $('#clients');
                        clientsDropdown.empty().append('<option value="0">Select a Client</option>');
                        $.each(response.data, function(id, name) {
                            clientsDropdown.append('<option value="' + id + '">' + name +
                                '</option>');
                        });
                    } else {
                        console.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: " + status + " " + error);
                }
            });
        });

        var KTDatatablesBasicScrollable = function() {
            var initTable1 = function() {
                $('#kt_table_1').DataTable({
                    scrollX: true,
                    scrollCollapse: true,
                    dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
      <'row'<'col-sm-12'tr>>
      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,

                    buttons: [{
                            extend: 'excelHtml5',
                            title: 'QC Report'
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'QC Report'
                        },
                        {
                            extend: 'print',
                            title: 'QC Report'
                        },
                        {
                            extend: 'copyHtml5',
                            title: 'QC Report'
                        },
                        {
                            extend: 'csvHtml5',
                            title: 'QC Report'
                        }
                    ]
                });
            };

            return {
                init: function() {
                    initTable1();
                }
            };
        }();

        jQuery(document).ready(function() {
            KTDatatablesBasicScrollable.init();
        });
    </script>
@endsection

@section('css')
    <link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
