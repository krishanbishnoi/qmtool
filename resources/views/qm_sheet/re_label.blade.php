@extends('layouts.app')

@section('sh-title')
Re-Label
@endsection

@section('sh-detail')
Audit Sheet Inputs
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
                {!! Form::open(
                  array(
                    'url' => 're_label_update', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator",
                    'enctype'=>"multipart/form-data")
                ) !!}
                <input type="hidden" name="re_label_id" value="{{Crypt::encrypt($data->id)}}">
        <div class="kt-portlet__body">

          <div class="form-group row">
              <div class="col-lg-3">
                <label>Communication Instance ID(Call ID)*</label>
                <input type="text" name="info_1" value="{{$data->info_1}}" class="form-control" required>
              </div>
              <div class="col-lg-3">
                <label>Client*</label>
                <input type="text" name="info_2" value="{{$data->info_2}}" class="form-control" required>
              </div>
              <div class="col-lg-3">
                <label>Partner*</label>
                <input type="text" name="info_3" value="{{$data->info_3}}" class="form-control" required>
              </div>
              <div class="col-lg-3">
                <label>Audit Date*</label>
                <input type="text" name="info_4" value="{{$data->info_4}}" class="form-control" required>
              </div>
          </div>

          <div class="form-group row">
              <div class="col-lg-3">
                <label>Agent Name*</label>
                <input type="text" name="info_5" value="{{$data->info_5}}" class="form-control" required>
              </div>
              <div class="col-lg-3">
                <label>TL Name*</label>
                <input type="text" name="info_6" value="{{$data->info_6}}" class="form-control" required>
              </div>
              <div class="col-lg-3">
                <label>QA / QTL Name*</label>
                <input type="text" name="info_7" value="{{$data->info_7}}" class="form-control" required>
              </div>
              <div class="col-lg-3">
                <label>Campaign Name*</label>
                <input type="text" name="info_8" value="{{$data->info_8}}" class="form-control" required>
              </div>
          </div>
          <div class="form-group row">
              <div class="col-lg-3">
                <label>Call Sub Type*</label>
                <input type="text" name="info_9" value="{{$data->info_9}}" class="form-control" required>
              </div>
              <div class="col-lg-3">
                <label>Disposition*</label>
                <input type="text" name="info_11" value="{{$data->info_11}}" class="form-control" required>
              </div>
              <div class="col-lg-3">
                <label>Customer Name*</label>
                <input type="text" name="info_11" value="{{$data->info_11}}" class="form-control" required>
              </div>
              <div class="col-lg-3">
                <label>Cusotmer contact number*</label>
                <input type="text" name="info_12" value="{{$data->info_12}}" class="form-control" required>
              </div>
          </div>
          <div class="form-group row">
              <div class="col-lg-3">
                <label>QM-Sheet Version*</label>
                <input type="text" name="info_13" value="{{$data->info_13}}" class="form-control" required>
              </div>
              <div class="col-lg-3">
                <label>QRC 1*</label>
                <input type="text" name="info_14" value="{{$data->info_14}}" class="form-control" required>
              </div>
              <div class="col-lg-3">
                <label>QRC for QA*</label>
                <input type="text" name="info_15" value="{{$data->info_15}}" class="form-control" required>
              </div>
              <div class="col-lg-3">
                <label>Language 1*</label>
                <input type="text" name="info_16" value="{{$data->info_16}}" class="form-control" required>
              </div>
          </div>
          <div class="form-group row">
              <div class="col-lg-3">
                <label>Language for QA*</label>
                <input type="text" name="info_17" value="{{$data->info_17}}" class="form-control" required>
              </div>
              <div class="col-lg-3">
                <label>Case ID*</label>
                <input type="text" name="info_18" value="{{$data->info_18}}" class="form-control" required>
              </div>
              <div class="col-lg-3">
                <label>Call Time*</label>
                <input type="text" name="info_19" value="{{$data->info_19}}" class="form-control" required>
              </div>
              <div class="col-lg-3">
                <label>Call Duration*</label>
                <input type="text" name="info_20" value="{{$data->info_20}}" class="form-control" required>
              </div>
          </div>
          <div class="form-group row">
              <div class="col-lg-3">
                <label>Refrence No.*</label>
                <input type="text" name="info_21" value="{{$data->info_21}}" class="form-control" required>
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
@endsection