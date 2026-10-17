@extends('layouts.app')

@section('sh-title')
    {{ $qm_sheet_data->name }}
@endsection

@section('sh-detail')
    Parameters
@endsection
{{--
@section('sh-toolbar')
							<div class="kt-subheader__toolbar">
								<div class="kt-subheader__wrapper">

								<a href="/qm_sheet/{{Crypt::encrypt($qm_sheet_data->id)}}/add_parameter" class="btn btn-label-success btn-bold">
									Create New Parameter
								</a>

								</div>
							</div>
@endsection --}}

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
            <form action="{{ route('add_parameter_type') }}" method="post">
                @csrf
                <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">

                    <thead>
                        <tr>
                            <th title="Field #1">#</th>
                            <th title="Field #2">
                                Parameter
                            </th>
                            <th title="Field #2">
                                Sub Parameter - Weightage
                            </th>
                            <th title="Field #2">
                                Weightage
                            </th>

                        </tr>
                    </thead>
                    <tbody>
{{--
                        @foreach ($data as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->parameter }}</td>
                                <td>
                                    <ol>

                                        @foreach ($row->qm_sheet_sub_parameter as $ksp => $vsp)
                                            <li>{{ $vsp->sub_parameter }} - <strong>{{ $vsp->weight }}</strong></li>

                                            <input type="radio" id="" name="parameter_types[{{ $vsp->id }}]"
                                                value="Process" {{ $existingType === 'Process' ? 'checked' : '' }}>Process
                                            <input type="radio" id="" name="parameter_types[{{ $vsp->id }}]"
                                                value="System" {{ $existingType === 'System' ? 'checked' : '' }}>System
                                            <input type="radio" id=""
                                                name="parameter_types[{{ $vsp->id }}]" value="Communication"
                                                {{ $existingType === 'Communication' ? 'checked' : '' }}>Communication


                                        @endforeach
                                    </ol>
                                </td>
                                <td>{{ $total_weightage }}</td>
                                <td nowrap></td>
                            </tr>
                        @endforeach --}}

                        @foreach ($data as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->parameter }}</td>
                                <td>
                                    <ol>
                                        <?php $total_weightage = 0; ?>
                                        @foreach ($row->qm_sheet_sub_parameter as $ksp => $vsp)
                                            <li>{{ $vsp->sub_parameter }} - <strong>{{ $vsp->weight }}</strong></li>
                                            <?php
                                            $existingType = $existingParameterTypes
                                                ->where('parameter_ids', $vsp->id)
                                                ->pluck('parameter_type')
                                                ->first();
                                            ?>
                                            <input type="radio" id=""
                                                name="parameter_types[{{ $vsp->id }}]" value="Process"
                                                {{ $existingType === 'Process' ? 'checked' : '' }}>Process
                                            <input type="radio" id=""
                                                name="parameter_types[{{ $vsp->id }}]" value="System"
                                                {{ $existingType === 'System' ? 'checked' : '' }}>System
                                            <input type="radio" id=""
                                                name="parameter_types[{{ $vsp->id }}]" value="Communication"
                                                {{ $existingType === 'Communication' ? 'checked' : '' }}>Communication

                                            <?php $total_weightage += $vsp->weight; ?>
                                        @endforeach
                                    </ol>
                                </td>
                                <td>{{ $total_weightage }}</td>
                                <td nowrap></td>
                            </tr>
                        @endforeach





                    </tbody>
                </table>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
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
@endsection
