@extends('layouts.app')
@section('sh-title')
Agent Down Time
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
                    'route' => 'agent_down_time_store', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator")
                  ) !!}
        <input type="hidden" name="company_id" value="{{Auth::User()->company_id}}">
            @csrf
        <div class="kt-portlet__body">

          <div class="form-group">
            <label>Down Time To*</label>
            <?php
                $givenDate = date("Y-m-d");
                $newDate = date("Y-m-d", strtotime($givenDate. '-2 days'));
            ?>
            <input type="date" max=<?php echo $givenDate ?> min=<?php echo $newDate ?> name="down_timeDate" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Down Time To*</label>
            <input type="time" name="down_timeTo" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Down Time From*</label>
            <input type="time" name="down_timeFrom" class="form-control" required>
          </div>
          <div class="form-group">
            <select class="form-control" name="reason_type" id="reason_type">
                <option value="#">Select Reason</option>
                <option value="Verient Issue">Verient Issue</option>
                <option value="Nice Issue">Tool (Nice) Issue</option>
                <option value="Network Issue">Network Issue</option>
                <option value="QAViews Issue">QAViews Issue</option>
                <option value="Power Breakup">Power Breakup</option>
                <option value="Others">Others</option>
            </select>
          </div>
          <div class="form-group" id="reason" style="display: none">
            <label>Reason</label>
            <textarea class="form-control" name="reason"></textarea>
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
    $('#reason_type').on('change',function(e){
        console.log(e);
        var reason =  e.target.value;
        if(reason === "Others"){
            $('#reason').show()
            $('#reason').prop('required', true)
        }else{
            $('#reason').hide()
        }

    });
</script>
@endsection