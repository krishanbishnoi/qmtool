@extends('layouts.app')
<link href="/month_picker/monthpicker.css" rel="stylesheet" type="text/css">
@section('sh-title')
QA Target
@endsection

@section('sh-detail')
Edit QA Target
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
                'route' => 'update_qa_target', 
                'class' => 'kt-form',
                'role'=>'form',
                'data-toggle'=>"validator",
                'enctype'=>'multipart/form-data')
                ) !!}
        
        <div class="kt-portlet__body">
        
            <input type="hidden" name = "target_id" value = "{{$data[0]->id}}">
            <div class = "row">
                <div class="form-group col-md-6">
                    <label>Name*</label>
                    <input type=" text" class="form-control" disabled value="{{$data[0]->name}}">
                </div>

                <div class="form-group col-md-6">
                    <label>Email*</label>
                    <input type=" text" class="form-control" disabled value="{{$data[0]->qa_email}}">
                </div>
            
            </div>

            <div class = "row">
                <div class="form-group col-md-6">
                    <label>Month*</label>
                    <input type="text" class="form-control"  value = "{{$data[0]->target_month}}" name = "target_month" required="required">
                </div>

                <div class="form-group col-md-6">
                    <label>Target*</label>
                    <input type="number" class="form-control" value = "{{$data[0]->qa_target}}" name = "target" required="required">
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
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="/month_picker/monthpicker.min.js"></script>
<script>
$('#demo-1').Monthpicker();

</script>

<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
@endsection