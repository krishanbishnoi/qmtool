@extends('layouts.app')

@section('sh-title')
Client
@endsection

@section('sh-detail')
Create New
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
                    'route' => 'client.store', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator",
                    'enctype'=>"multipart/form-data")
                ) !!}

        <input type="hidden" name="company_id" value="{{Auth::User()->company_id}}">
        
        <div class="kt-portlet__body">

          <div class="form-group row">
              <div class="col-lg-6">
                <label>Name*</label>
                <input type="text" name="name" class="form-control" required>
              </div>
              <div class="col-lg-6">
                <label>Business Type*</label>
                <input type="text" name="business_type" class="form-control" required>
              </div>
          </div>

          <div class="form-group row">
            <div class="col-lg-6">
            <label>Logo</label>
            <input type="file" name="logo" class="form-control">
            </div>
            <div class="col-lg-6">
            <label>Details</label>
            <textarea class="form-control" name="details"></textarea>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-lg-6">
            <label>QC latency time (in Days)</label>
            <input type="number" name="qc_time" class="form-control">
            </div>
            <div class="col-lg-6">
            <label>Rebuttal latency time (in Days)</label>
            <input type="number" name="rebuttal_time" class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-6">
            <label>Re-Rebuttal latency time (in Days)</label>
            <input type="number" name="re_rebuttal_time" class="form-control">
            </div>
          </div>

          
          <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
          <h6>User Details</h6>

          <div class="form-group row">
            <div class="col-lg-6">
            <label>Name*</label>
            <input type="text" name="user_name" class="form-control" required>
            </div>
            <div class="col-lg-6">
            <label>Primary Email (as username)*</label>
            <input type="text" name="email" class="form-control" required>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-lg-6">
            <label>Mobile No.*</label>
            <input type="text" name="mobile" class="form-control" required>
          </div>
          <div class="col-lg-6">
            <label>Password*</label>
            <input type="password" name="password" class="form-control" required>
          </div>
        </div>
          <div class="form-group row">
            <div class="col-lg-6">
            <label>Confirm Password*</label>
            <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <div class="col-lg-6">
            <label>Process Owner*</label>
            {{ Form::select('process_owner_id',$all_process_owner,null,['class'=>'form-control','required'=>'required']) }}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-6">
            <label>QTL*</label>
            {{ Form::select('qtl_id[]',$all_qtl,null,['class'=>'form-control m-select2','id'=>'kt_select2_1','multiple'=>'multiple']) }}
            </div>
            <div class="col-lg-6">
            <label>Holiday Type*</label>
              <select name="holiday" class="form-control">
                <option value="1">Only Sunday</option>
                <option value="2">Saturday and Sunday</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-6">
              <label>&nbsp;</label><br/>
              <label class="kt-checkbox kt-checkbox--success">
                <input type="checkbox" name="rca_enabled" value="1"/> RCA Enabled
                <span></span>
              </label>
            </div>
            <div class="col-lg-6">
              <label>&nbsp;</label><br/>
              <label class="kt-checkbox kt-checkbox--success">
                <input type="checkbox" name="rca_two_enabled" value="1"/> RCA 2 Enabled
                <span></span>
              </label>
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