@extends('layouts.app')

@section('sh-title')
    User
@endsection

@section('sh-detail')
    Create New
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

                {!! Form::open([
                    'route' => 'user.store',
                    'class' => 'kt-form',
                    'role' => 'form',
                    'autocomplete' => 'off',
                    'data-toggle' => 'validator',
                ]) !!}
                <input type="hidden" name="company_id" value="{{ Auth::User()->company_id }}">

                <div class="kt-portlet__body">

                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Name*</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-lg-6">
                            <label>Primary Email (as username)*</label>
                            <input type="text" autocomplete="off" name="email" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Mobile No.*</label>
                            <input type="text" name="mobile" class="form-control" required>
                        </div>
                        <div class="col-lg-6">
                            <label>Password*</label>
                            <input type="password" autocomplete="off" name="password" class="form-control" required>
                            <small>Your password must be more than 8 characters long, should contain at-least 1 Uppercase, 1
                                Lowercase, 1 Numeric and 1 special character.</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Confirm Password*</label>
                            <input type="password" autocomplete="off" name="password_confirmation" class="form-control"
                                required>
                        </div>
                        <div class="col-lg-6">
                            <label>Roles*</label>
                            <select class="form-control m-select2" id="kt_select2_1" name="role_id[]" multiple="multiple">
                                @foreach ($roles as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <br>
                            <label style="display:none" id="select_sme_lable">Select SME</label>
                            <select class="form-control m-select2" id="selected_sme_list" name="selected_sme"
                                style="display:none">
                                <option value="">Select SME</option>
                                @foreach ($sme_data as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-lg-6">
                            <label  style="display:none" id ='qa_target' >QA Target*</label>
                            <input  style="display:none"  type="text" autocomplete="off" name="target" id ='qa_target' class="form-control"
                                >

                        </div>

                        </div>



                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label>Qd Id*</label>
                                <input type="text" autocomplete="off" name="qd_id" class="form-control" required>
                            </div>
                            <div class="col-lg-6">
                                <label>Date Of Joining*</label>
                                <input type="date" autocomplete="off" name="doj" class="form-control" required>
                            </div>
                        </div>
                        @if (Auth::user()->id == 333)
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>Vendor</label>
                                    <select class="form-control" name="vendor">
                                        <option value="">Please Select Vendor</option>
                                        <option value="Essencea">Essencea</option>
                                        <option value="Qconneqt">Qconneqt</option>
                                        <option value="Cogent">Cogent</option>
                                        <option value="Inhouse">Inhouse</option>
                                    </select>
                                </div>
                            </div>
                        @endif
                        <h6>Masters Details</h6>
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label>Process</label>
                                {{ Form::select('selected_process[]', $process_data, null, ['class' => 'form-control m-select2', 'id' => 'kt_select2_2', 'multiple' => 'multiple']) }}
                            </div>
                            <div class="col-lg-6">
                                <label>Region</label>
                                {{ Form::select('selected_region[]', $region_data, null, ['class' => 'form-control m-select2', 'id' => 'kt_select2_3', 'multiple' => 'multiple']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label>Language</label>
                                {{ Form::select('selected_language[]', $language_data, null, ['class' => 'form-control m-select2', 'id' => 'kt_select2_4', 'multiple' => 'multiple']) }}
                            </div>
                            <div class="col-lg-6">
                                <input type="checkbox" id="sme_select" name="sme" value="1">
                                <label for="sme">SME</label><br>
                            </div>
                        </div>
                        <h6>Cluster Hierarchy</h6>
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label>Select Parent Client</label>
                                <select id="client_id" onchange="getFilter(this.value)" name="parent_client_id"
                                    class="form-control">

                                </select>

                            </div>
                            <div class="col-lg-6">
                                <label>Select Partner</label>
                                <div class="form-group form-box form-group">

                                    <select class="js-example-basic-multiple form-control" id="partner_id" name="partner[]"
                                        multiple="true">


                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label>Select Location</label>
                                <div class="form-group form-box form-group">

                                    <select class="js-example-basic-multiple form-control" id="location_id"
                                        name="location[]" multiple="true">


                                    </select>

                                </div>


                            </div>
                            <div class="col-lg-6">
                                <label>Select Process</label>

                                <div class="form-group form-box form-group">

                                    <select class="js-example-basic-multiple form-control" id="process_id"
                                        name="process[]" multiple="true">


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
                        $("#location_id").html(Data);
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
                        $("#client_id").html(Data);
                    }
                });
            });

            $(document).ready(function() {
                $('#kt_select2_1').on("change", function() {
                    var selectedRole = $(this).val();
                    // console.log(selectedRole);
                    if (selectedRole == '17') {
                        $('#selected_sme_list ').show();
                    } else {
                        $('#selected_sme_list ').hide();
                    }
                });
            });


            $(document).ready(function() {
                $('#kt_select2_1').on("change", function() {
                    var selectedRole = $(this).val();
                    // console.log(selectedRole);
                    if (selectedRole == '5') {
                        $('#qa_target ').show();
                    } else {
                        $('#qa_target ').hide();
                    }
                });
            });
        </script>
    @endsection
