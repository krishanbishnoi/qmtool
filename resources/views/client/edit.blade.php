@extends('layouts.app')

@section('sh-title')
Client
@endsection

@section('sh-detail')
Edit
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
      
        {!! Form::model($data,
                  array(
                  'method' => 'PATCH',
                    'url' =>'client/'.Crypt::encrypt($data->id),
                    'class' => 'kt-form',
                    'data-toggle'=>"validator",
                    'enctype'=>"multipart/form-data")
                  ) !!}
        <input type="hidden" name="company_id" value="{{AUth::User()->company_id}}">

        <div class="kt-portlet__body">

          <div class="form-group row">
              <div class="col-lg-6">
                <label>Name*</label>
                <input type="text" name="name" class="form-control" required value="{{$data->name}}">
              </div>
              <div class="col-lg-6">
                <label>Business Type*</label>
                <input type="text" name="business_type" class="form-control" required value="{{$data->business_type}}">
              </div>
          </div>

          <div class="form-group row">
            <div class="col-lg-6">
            <label>Logo</label>
            <input type="file" name="logo" class="form-control">
            </div>
            <div class="col-lg-6">
            <label>Details</label>
            <textarea class="form-control" name="details">{{$data->details}}</textarea>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-6">
            <label>QC latency time (in Days)</label>
            <input type="number" name="qc_time" class="form-control" value="{{$data->qc_time}}">
            </div>
            <div class="col-lg-6">
            <label>Rebuttal latency time (in Days)</label>
            <input type="number" name="rebuttal_time" class="form-control" value="{{$data->rebuttal_time}}">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-6">
              <label>Re-Rebuttal latency time (in Days)</label>
              <input type="number" name="re_rebuttal_time" class="form-control" value="{{$data->re_rebuttal_time}}">
            </div>
            <div class="col-lg-6">
            <label>Holiday Type*</label>
              <select name="holiday" class="form-control">
                <option value="1" @if($data->holiday==1) selected @endif>Only Sunday</option>
                <option value="2" @if($data->holiday==2) selected @endif >Saturday and Sunday</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
          <div class="col-lg-6">
              <label>&nbsp;</label><br/>
              <label class="kt-checkbox kt-checkbox--success">
                @if($data->rca_enabled)
                <input type="checkbox" name="rca_enabled" value="1" checked /> RCA Enabled
                @else
                <input type="checkbox" name="rca_enabled" value="1"/> RCA Enabled
                @endif
                <span></span>
              </label>
            </div>
            <div class="col-lg-6">
              <label>&nbsp;</label><br/>
              <label class="kt-checkbox kt-checkbox--success">
                @if($data->rca_two_enabled)
                <input type="checkbox" name="rca_two_enabled" value="1" checked /> RCA 2 Enabled
                @else
                <input type="checkbox" name="rca_two_enabled" value="1"/> RCA 2 Enabled
                @endif
                <span></span>
              </label>
            </div>
          </div>
          <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
          <h6>Users Details</h6>
          <div class="form-group row">
            <div class="col-lg-6">
            <label>Process Owner*</label>
            {{ Form::select('process_owner_id',$all_process_owner,$data->process_owner_id,['class'=>'form-control','required'=>'required']) }}
            </div>
            <div class="col-lg-6">
            <label>QTL*</label>
            {{ Form::select('qtl_id[]',$all_qtl,$selected_qtls,['class'=>'form-control m-select2','id'=>'kt_select2_1','multiple'=>'multiple']) }}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-6">
            <label>Admin Users*</label>
            {{ Form::select('client_admin[]',$all_client_users,$attached_client_admin,['class'=>'form-control m-select2','id'=>'kt_select2_2','multiple'=>'multiple']) }}
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
<script src="/assets/app/custom/general/crud/forms/widgets/summernote.js" type="text/javascript"></script>
@endsection