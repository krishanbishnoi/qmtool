@extends('layouts.app')

@section('sh-title')
Audit Alert Box
@endsection

@section('sh-detail')
Create New
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
                    'route' => 'audit_alert_box.store', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator")
                  ) !!}
        <input type="hidden" name="company_id" value="{{Auth::User()->company_id}}">

        <div class="kt-portlet__body">

          <div class="form-group">
            <label>Name*</label>
            <input type="text" name="name" class="form-control" required>
          </div>

          <div class="form-group">
                      <label>Message*</label>
                      <div class="col-lg-12 col-md-12 col-sm-12">
                        <textarea class="summernote" id="kt_summernote_1" name="details"></textarea>
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
  $('#kt_summernote_1').summernote({
   callbacks: {
       onKeydown: function(e) {
           var forbiddenKeys = [190, 191, 192, 219, 220, 221, 222]; // Forbidden keys
           if (forbiddenKeys.indexOf(e.keyCode) !== -1) {
               e.preventDefault();
               return false;
           }
       },
       onPaste: function(e) {
           // Strip HTML tags from pasted content
           e.preventDefault();
           return false;
           // var text = (e.originalEvent || e).clipboardData.getData('text/plain');
           // document.execCommand('insertText', false, text);
       },
       onDrop: function(e) {
           // Strip HTML tags from dropped content
           e.preventDefault();
           var text = e.originalEvent.dataTransfer.getData('text/plain');
           document.execCommand('insertText', false, text);
       }
   }
});
</script>
<script src="/assets/app/custom/general/crud/forms/widgets/summernote.js" type="text/javascript"></script>
@endsection