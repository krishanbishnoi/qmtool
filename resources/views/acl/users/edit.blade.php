@extends('layouts.app')

@section('sh-title')
    User
@endsection

@section('sh-detail')
    Edit
@endsection

@section('main')
    <link rel="stylesheet" href="{{ asset('c_dash_css/select2.min.css') }}">
    <script type="text/javascript" src="{{ asset('cdn/select2.min.js') }}"></script>
    <div class="row">
        <div class="col-md-12">

            <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Details
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->

                {!! Form::model($data, [
                    'method' => 'PATCH',
                    'url' => 'user/' . Crypt::encrypt($data->id),
                    'class' => 'kt-form',
                    'data-toggle' => 'validator',
                ]) !!}


                <div class="kt-portlet__body">

                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Name*</label>
                            <input type="text" name="name" class="form-control" required value="{{ $data->name }}">
                        </div>
                        <div class="col-lg-6">
                            <label>Primary Email (as username)*</label>
                            <input type="text" name="email" class="form-control" required value="{{ $data->email }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Mobile No.*</label>
                            <input type="text" name="mobile" class="form-control" required value="{{ $data->mobile }}">
                        </div>
                        <div class="col-lg-6">
                            <label>Roles*</label>
                            {{ Form::select('role_id[]', $roles, $rdata, ['class' => 'form-control m-select2', 'id' => 'kt_select2_1', 'multiple' => 'multiple', 'required' => 'required']) }}
                        </div>


                        @if($rdata[0] == 5)
                        <div class="col-lg-6">
                            <label>Qd Target*</label>
                            <input type="text" autocomplete="off" name="target" class="form-control" required
                                value="{{ $data->qa_target  }}">
                        </div>
                        @endif



                        @if ($rdata[0] == 17)
                            <div class="col-lg-6">
                                <br>
                                <select class="form-control m-select2" id="selected_sme_list" name="selected_sme">
                                    @if ($name)
                                        <option selected="selected">{{ $name }}</option>
                                    @else
                                        <option selected="selected">Select SME</option>
                                    @endif
                                    @foreach ($sme_data as $k => $v)
                                        <option value="{{ $k }}">{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else

                       
                            <div class="col-lg-6">
                                <br>
                                <select class="form-control m-select2" id="selected_sme_list" name="selected_sme"
                                    style="display: none">
                                    @if ($name)
                                        <option selected="selected">{{ $name }}</option>
                                    @else
                                        <option selected="selected">Select SME</option>
                                    @endif
                                    @foreach ($sme_data as $k => $v)
                                        <option value="{{ $k }}">{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                       

                    </div>

                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Qd Id*</label>
                            <input type="text" autocomplete="off" name="qd_id" class="form-control" required
                                value="{{ $data->qd_id }}">
                        </div>
                        <div class="col-lg-6">
                            <label>Date Of Joining*</label>
                            <input type="date" autocomplete="off" name="doj" class="form-control" required
                                value="{{ $data->doj }}">
                        </div>
                    </div>
                    @if (Auth::user()->id == 333)
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label>Vendor</label>
                                <select class="form-control" name="vendor">
                                    <option value="">Please Select Vendor</option>
                                    <option <?php if ($data->vendor == 'Essencea') {
                                        echo 'selected';
                                    } ?> value="Essencea">Essencea</option>
                                    <option <?php if ($data->vendor == 'Qconneqt') {
                                        echo 'selected';
                                    } ?> value="Qconneqt">Qconneqt</option>
                                    <option <?php if ($data->vendor == 'Cogent') {
                                        echo 'selected';
                                    } ?> value="Cogent">Cogent</option>
                                    <option <?php if ($data->vendor == 'Inhouse') {
                                        echo 'selected';
                                    } ?> value="Inhouse">Inhouse</option>
                                </select>
                            </div>
                        </div>
                    @endif
                    <h6>Masters Details</h6>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Process</label>
                            {{ Form::select('selected_process[]', $process_data, $my_selected_processes, ['class' => 'form-control m-select2', 'id' => 'kt_select2_2', 'multiple' => 'multiple']) }}
                        </div>
                        {{-- <div class="col-lg-6">
                            <label>Region</label>
                            {{ Form::select('selected_region[]', $region_data, $my_selected_regions, ['class' => 'form-control m-select2', 'id' => 'kt_select2_3', 'multiple' => 'multiple']) }}
                        </div> --}}
                        <div class="col-lg-6">
                            <label>Region</label>
                            {{ Form::select(
                                'selected_region[]',
                                $region_data,
                                $my_selected_regions,
                                ['class' => 'form-control m-select2', 'id' => 'kt_select2_3', 'multiple' => 'multiple']
                            ) }}
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Language</label>
                            {{ Form::select('selected_language[]', $language_data, $my_selected_languages, ['class' => 'form-control m-select2', 'id' => 'kt_select2_4', 'multiple' => 'multiple']) }}
                        </div>
                        <div class="col-lg-6">
                          <input type="checkbox" id="sme_select" name="sme" value="1"  placeholder="SME" {{ $data->is_sme ? 'checked' : '' }}>
                           {{-- <label for="sme"></label><br> --}}
                           <h4>SME</h4>
                         </div>


                    </div>
                    <h6>Cluster Hierarchy</h6>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Select Parent Client</label>
                            <select class="js-example-basic-multiple form-control" id="client_id" name="parent_client_id"
                                onchange="getFilter(this.value)">
                                @foreach ($client_list as $value)
                                    <option value="{{ $value->id }}" <?php if ($allocated_client->parent_client == $value->id) {
                                        echo 'selected';
                                    } ?>>{{ $value->name }}

                                    </option>
                                @endforeach

                            </select>

                        </div>
                        <div class="col-lg-6">
                            <label>Select Partner</label>
                            <div class="form-group form-box form-group">

                                {{ Form::select('partner[]', $partner_data, $my_selected_partners, ['class' => 'form-control m-select2', 'id' => 'kt_select2_5', 'multiple' => 'multiple']) }}

                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Select Location</label>
                            <div class="form-group form-box form-group">

                                {{ Form::select('location[]', $location_data, $my_selected_location, ['class' => 'form-control m-select2', 'id' => 'kt_select2_10', 'multiple' => 'multiple']) }}

                            </div>

                           


                        </div>
                        
                        <div class="col-lg-6">
                            <label>Select Process</label>

                            <div class="form-group form-box form-group">

                                {{ Form::select('process[]', $process_data, $my_selected_process, ['class' => 'form-control m-select2', 'id' => 'kt_select2_7', 'multiple' => 'multiple']) }}


                                </select>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-secondary">Cancel</button>
                    </div>
                </div>
                </form>

                <!--end::Form-->
            </div>

            <!--end::Portlet-->
        </div>
    </div>

@endsection
@section('js')
    @include('shared.form_js')
    <script type="text/javascript">
        function getFilter(client_id) {
            var base_url = window.location.origin;
            $.ajax({
                type: "GET",
                url: base_url + "/getDataPartner/" + client_id,
                success: function(Data) {
                    $("#partner_id").html(Data);
                }
            });
            $.ajax({
                type: "GET",
                url: base_url + "/getDataLocation/" + client_id,
                success: function(Data) {
                    // $("#location_id").html(Data);
                }
            });
            $.ajax({
                type: "GET",
                url: base_url + "/getDataProcess/" + client_id,
                success: function(Data) {
                    $("#process_id").html(Data);
                }
            });
        }


        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({});

            var base_url = window.location.origin;
            $.ajax({
                type: "GET",
                url: base_url + "/getClientList/0",
                success: function(Data) {

                    // $("#client_id").html(Data);


                }
            });
        });

        $(document).ready(function() {
            $('#kt_select2_1').on("change", function() {
                var selectedRole = $(this).val();
                if (selectedRole == '17') {
                    $('#selected_sme_list').show();
                } else {
                    $('#selected_sme_list').hide();
                }
            });
        });
    </script>
@endsection
