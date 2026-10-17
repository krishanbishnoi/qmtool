@extends('layouts.app')
<link href="/month_picker/monthpicker.css" rel="stylesheet" type="text/css">
@section('sh-title')
@endsection

@section('sh-detail')
@endsection

@section('main')
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
                    'route' => 'update_lob_par',
                    'class' => 'kt-form',
                    'role' => 'form',
                    'data-toggle' => 'validator',
                    'enctype' => 'multipart/form-data',
                ]) !!}
                {{-- <input type="hidden" name="audit_id" value="{{$audit_id}}"> --}}
                <input type="hidden" name="raw_id" value="{{ $raw_data_id }}">

                <div class="kt-portlet__body">
                    <h5>Old Location: {{ $raw_detail->location }}</h5>
                    <br>
                    <h5>Old Lob: {{ $raw_detail->lob }}</h5>
                    <br>
                    <br>
                    @php
                        $Partners = \App\Partner::find($raw_detail->partner_id);
                    @endphp
                    <h5>
                        Old Location: {{ $Partners->name }}
                    </h5>

                    <div class="form-group col-md-6">
                        <label>Edit Lob*</label>
                        <input type="text" class="form-control" name="lob" value="{{ $raw_detail->lob }}"
                            required="required">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Edit Location</label>
                        <input type="text" class="form-control" name="location" value="{{ $raw_detail->location }}"
                            required="required">
                    </div>
                    <div class="form-group">
                        <label>Select Partner</label>
                        <select class="form-control" name="partner_id" id="partner_id" required>
                            <option value="">Select Partner</option>
                            @foreach ($partners as $name => $id)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-secondary">Cancel</button>
                    </div>
                </div>

                {!! Form::close() !!}
                <!--end::Form-->
            </div>

            <!--end::Portlet-->
        </div>
    </div>
@endsection

@section('js')
    @include('shared.form_js')
@endsection
