@extends('layouts.app')

@section('sh-title')
Role
@endsection

@section('sh-detail')
Create New
@endsection

@section('main')
<div class="row">
  <div class="col-md-6">

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
                    'route' => 'role.store', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator")
                  ) !!}


        <div class="kt-portlet__body">

          <div class="form-group">
            <label>Company*</label>
            <input type="text" class="form-control" readonly value="{{Auth::User()->company->company_name}}" />
            <input type="hidden" name="company_id" value="{{Auth::User()->company_id}}">
          </div>
          <div class="form-group">
            <label>Name*</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Display Name*</label>
            <input type="text" name="display_name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Description*</label>
            <textarea class="form-control" name="description"></textarea>
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