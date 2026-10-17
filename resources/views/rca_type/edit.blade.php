@extends('layouts.app')

@section('sh-title')
RCA Type
@endsection

@section('sh-detail')
Edit
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
                    'url' =>'rca_type/'.Crypt::encrypt($data->id),
                    'class' => 'kt-form',
                    'data-toggle'=>"validator")
                  ) !!}
        <input type="hidden" name="company_id" value="{{Auth::User()->company_id}}">

        <div class="kt-portlet__body">
          <div class="form-group">
            <label>Client:</label>
              {{ Form::select('client_id',$client_list,$data->client_id,['class'=>'form-control','placeholder'=>'Select a client']) }}
          </div>
          <div class="form-group">
            <label>Process:</label>
              {{ Form::select('process_id',$process_list,$data->process_id,['class'=>'form-control','placeholder'=>'Select a process']) }}
          </div>
          <div class="form-group">
            <label>Name*</label>
            <input type="text" name="name" class="form-control" required value="{{$data->name}}">
          </div>
          <div class="form-group">
            <label>Details</label>
            <textarea class="form-control" name="details">{{$data->details}}</textarea>
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
@endsection