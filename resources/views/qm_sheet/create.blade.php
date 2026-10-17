@extends('layouts.app')

@section('sh-title')
QM - Sheet
@endsection

@section('sh-detail')
Create New
@endsection

@section('main')
<div class="row">
  <div class="col-md-7">

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
                    'route' => 'qm_sheet.store', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator")
                  ) !!}
        <input type="hidden" name="company_id" value="{{Auth::User()->company_id}}">

        <div class="kt-portlet__body">

          <div class="form-group">
            <label>Client*</label>
            {{ Form::select('client_id',$all_client,null,['class'=>'form-control','onchange' => 'fetchData()','id'=>'client_id','required'=>'required','placeholder'=>"Select something"]) }}
          </div>
          <div class="form-group">
            <label>Process*</label>
            {{ Form::select('process_id',[],null,['class'=>'form-control','id'=>'process_id','required'=>'required','placeholder'=>"Select something"]) }}
          </div>
          <div class="form-group">
            <label>Sheet Name*</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Sheet Code*</label>
            <input type="text" name="code" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Sheet Verion*</label>
            <input type="number" name="version" class="form-control" required>
          </div>
          <!-- <div class="form-group">
            <label>Sheet Banner*</label>
            <input type="file" name="banner" class="form-control">
          </div> -->
          <div class="form-group">
            <label>Details</label>
            <textarea class="form-control" name="details"></textarea>
          </div>

          @if (Auth::user()->parent_client == 9 || Auth::user()->parent_client == 23 )
          <div class="row" style="padding: 20px 0; height: 100%;">
              <div class="col-md-6">
                  <input type="checkbox" id="copy_sheet" value = "qmsheets" name = "copy_sheet" class = "mr-4">
                  <label>Please check for copy parameter from existing sheets</label>

                  <div id = "sheet_list_div" style="display:none">
                      <select name="qm_sheet" class="form-control mt-4" id="qm_sheet">
                          <option value="" selected disabled>Select Sheet</option>
                          @foreach ($all_sheets as $name => $id)
                              <option value="{{ $id }}">{{ $name }}</option>
                          @endforeach
                      </select>
                      <div id="error_message" style="color: red; display: none; margin-top: 5px;"></div>
                  </div>
              </div>
          </div>
          @endif
          
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
  $(document).ready(function() {
      $('#copy_sheet').change(function() {
          if (this.checked) {
              $('#sheet_list_div').show();
          } else {
              $('#sheet_list_div').hide();
          }
      });
  });
</script>
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