@extends('layouts.app')

@section('sh-title')
Target
@endsection

@section('sh-detail')
Edit Target
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
                'route' => 'update_target', 
                'class' => 'kt-form',
                'role'=>'form',
                'data-toggle'=>"validator",
                'enctype'=>'multipart/form-data')
                ) !!}
        
        <div class="kt-portlet__body">
        
            <input type="hidden" name = "target_id" value = "{{$data[0]->id}}">
            <div class = "row">
                <div class="form-group col-md-6">
                    <label>Client*</label>
                    <input type=" text" class="form-control" disabled value="{{$data[0]->client_name}}">
                </div>

                <div class="form-group col-md-6">
                    <label>Process*</label>
                    <input type=" text" class="form-control" disabled value="{{$data[0]->process_name}}">
                </div>
            
            </div>

            <div class = "row">
                <div class="form-group col-md-6">
                    <label>Partner*</label>
                    <input type=" text" class="form-control" disabled value="{{$data[0]->partner_name}}">
                </div>

                <div class="form-group col-md-6">
                    <label>Audit Cycle*</label>
                    <input type=" text" class="form-control" disabled value="{{$data[0]->cycle_name}}">
                </div>
            
            </div>
            
            <div class = "row">
                <div class="form-group col-md-6">
                    <label>LOB*</label>
                    <input type=" text" class="form-control" disabled value="{{$data[0]->lob}}">
                </div>

                <div class="form-group col-md-6">
                    <label>Brand*</label>
                    <input type=" text" class="form-control" disabled value="{{$data[0]->id}}">
                </div>
            </div>
            

            
            <div class = "row">
                <div class="form-group col-md-6">
                    <label>Circle*</label>
                    <input type="text" class="form-control" disabled value="{{$data[0]->circle_name}}">
                </div>

                <div class="form-group col-md-6">
                    <label>Target*</label>
                    <input type="number" class="form-control" value = "{{$data[0]->target}}" name = "target" required="required">
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

<script>



</script>

<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
@endsection