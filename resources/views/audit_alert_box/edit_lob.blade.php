@extends('layouts.app')
<link href="/month_picker/monthpicker.css" rel="stylesheet" type="text/css">
@section('sh-title')

@endsection

@section('sh-detail')
Change Location
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
      
        {!! Form::open(
                  array(
                    'route' => 'edit_lob', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator",
                    'enctype'=>'multipart/form-data')
                  ) !!}
        
        

        <div class="kt-portlet__body">

           
          
          <div class="form-group">
          <label for="call_id">Call Id</label>
            <input type="text" name="call_id">
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