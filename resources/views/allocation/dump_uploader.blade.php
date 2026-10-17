@extends('layouts.app')

@section('sh-title')
    Raw Data Uploader
@endsection

@section('sh-detail')
    Process action dump
@endsection

@section('main')
    <div class="row">
        <div class="col-md-8">

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
                    'route' => 'upload_raw_data_dump',
                    'class' => 'kt-form',
                    'role' => 'form',
                    'data-toggle' => 'validator',
                    'enctype' => 'multipart/form-data',
                ]) !!}
                <input type="hidden" name="company_id" value="{{ Auth::User()->company_id }}">

                <div class="kt-portlet__body">

                    <div class="form-group">
                        <label>Dump Date*</label>
                        <input type="text" class="form-control" id="kt_datepicker_1" readonly placeholder="Select date"
                            name="dump_date" required />
                    </div>

                    <parents-uploader></parents-uploader>

                    <div class="form-group">
                        <label>Raw data xlsx*</label>
                        <input type="file" name="raw_data_file" class="form-control" required />
                    </div>
                    <span class="form-text text-muted">Max file size:- 50MB. File format:- .xlsx only</span>
                    <a href="/qm_tool_sample_raw_data_sheet.xlsx" download="download">Sample Raw Data Uploader Sheet.</a>




                    @if (Auth::user()->parent_client == 23)
                        <span class="form-text text-muted">Max file size:- 50MB. File format:- .xlsx only</span>
                        <a href="/Bajaj-Cancellation-sheet-sampleRawDump.xlsx" download="download">Sample Raw Data Uploader for Cancellation Sheet
                            Sheet./ Wavier Sheet</a>
                    @endif

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
    <script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
@endsection
