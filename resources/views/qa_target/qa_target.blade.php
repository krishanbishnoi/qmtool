@extends('layouts.app')

@section('sh-title')
Upload QA Target
@endsection

@section('sh-detail')
QA Target Uploader
@endsection

@section('main')
<div class="row">
  <div class="col-md-8">

    <!--begin::Portlet-->
    <div class="kt-portlet">
      <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
          <h3 class="kt-portlet__head-title">
            Upload Excel File
          </h3>
        </div>
      </div>

      <!--begin::Form-->
      
        {!! Form::open(
                  array(
                    'route' => 'upload_qa_target_month',
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator",
                    'enctype'=>'multipart/form-data')
                  ) !!}


        <div class="kt-portlet__body">


          <div class="form-group">
              <label>QA Target xlsx*</label>
              <input type="file" name="file" class="form-control" required/>
              <small><a href="/qa_target_sample_moth.xlsx" download="download">Click here to donwload sample file. (* Date format:- yyyy-mm-dd)</a></small>
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
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
@endsection