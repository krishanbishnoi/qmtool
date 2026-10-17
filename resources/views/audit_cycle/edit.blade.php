@extends('layouts.app')

@section('sh-title')
Audit Cycle
@endsection

@section('sh-detail')
Edit Audit Cycle
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
                
                   {!! Form::model($data,
                  array(
                  'method' => 'PATCH',
                    'url' =>'audit_cycle/'.Crypt::encrypt($data->id),
                    'class' => 'kt-form',
                    'data-toggle'=>"validator")
                  ) !!}
                  <div class="kt-portlet__body">
                      <div class="form-group">
                          <label>Client Name:</label>
                          {{ Form::select('client_id',$all_clients,$data->client_id,['class'=>'form-control','required'=>'required','placeholder'=>"Select something"]) }}
                      </div>
                      <div class="form-group">
                          <label>Process Name:</label>
                          {{ Form::select('process_id',$all_process,$data->process_id,['class'=>'form-control','required'=>'required','placeholder'=>"Select something"]) }}
                      </div>
                      <div class="form-group">
                          <label>QmSheet Name:</label>
                          {{ Form::select('qmsheet_id',$all_qmsheet,$data->qmsheet_id,['class'=>'form-control','required'=>'required','placeholder'=>"Select something"]) }}
                      </div>
                      <div class="form-group">
                          <label>Name:</label>
                          <input type="text" class="form-control" name="name" value="{{ $data->name }}">
                      </div>
                      <div class="form-group">
                          <label>Start Date:</label>
                          <input type="text" class="form-control" id="kt_datepicker_1" name="start_date" readonly value="{{ $data->start_date }}">
                      </div>
                      <div class="form-group">
                          <label>End Date:</label>
                          <input type="text" class="form-control" id="kt_datepicker_2" readonly name="end_date" value="{{ $data->end_date }}">
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
<script src="/assets/app/custom/general/crud/forms/widgets/summernote.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
@endsection