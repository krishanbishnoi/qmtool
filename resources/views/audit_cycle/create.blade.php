@extends('layouts.app')

@section('sh-title')
Audit Cycle
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
                    'route' => 'audit_cycle.store', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator")
                  ) !!}
                  <div class="kt-portlet__body">
                      <div class="form-group">
                          <label>Client Name:</label>
                          {{ Form::select('client_id',$all_clients,null,['class'=>'form-control','id'=>'client_id','onchange' => 'fetchData()','required'=>'required','placeholder'=>"Select something"]) }}
                      </div>
                      <div class="form-group">
                          <label>Process Name:</label>
                          {{ Form::select('process_id',[],null,['class'=>'form-control','id'=>'process_id','required'=>'required','placeholder'=>"Select something"]) }}
                      </div>
                      <div class="form-group">
                          <label>QmSheet Name:</label>
                          {{ Form::select('qmsheet_id',$all_qmsheet,null,['class'=>'form-control','required'=>'required','placeholder'=>"Select something"]) }}
                      </div>
                      <div class="form-group">
                          <label>Name:</label>
                          <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                      </div>
                      <div class="form-group">
                          <label>Start Date:</label>
                          <input type="text" class="form-control" id="kt_datepicker_1"readonly name="start_date" value="{{ old('start_date') }}">
                      </div>
                      <div class="form-group">
                        <label>End Date:</label>
                          <input type="text" class="form-control" id="kt_datepicker_2" name="end_date" value="{{ old('end_date') }}">
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
<script>
  function fetchData() {
    var client_id = document.getElementById('client_id').value;
  
      fetch(`/qm_sheet_client_partner/${client_id}`, {
          method: 'GET',
          headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Content-Type': 'application/json',
              'Accept': 'application/json'
          }
      })
      .then(response => response.json())
      .then(data => {
          document.getElementById('process_id').innerHTML = JSON.stringify(data);
      })
      .catch(error => console.error('Error fetching data:', error));
  }
</script>
@endsection