@extends('layouts.app')

@section('sh-title')
Calibration
@endsection

@section('sh-detail')
Edit
@endsection

@section('main')

    <!--begin::Portlet-->
    <div class="kt-portlet">
      <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
          <h3 class="kt-portlet__head-title">
            Details
          </h3>
        </div>
      </div>

      {!! Form::model($data,
                  array(
                  'method' => 'PATCH',
                    'url' =>'calibration/'.Crypt::encrypt($data->id),
                    'class' => 'kt-form',
                    'data-toggle'=>"validator")
                  ) !!}
        <input type="hidden" name="company_id" value="{{Auth::User()->company_id}}">

        <div class="kt-portlet__body">

        <div class="row">
        	<div class="col-md-6">

        	<div class="form-group">
            <label>Title*</label>
            <input type="text" name="title" class="form-control" value="{{$data->title}}" />
        	</div>
        	<div class="form-group">
				    <label>Due Date*</label>
				    <input type="text" class="form-control" id="kt_datepicker_1" readonly placeholder="Select date" name="due_date" value="{{$data->due_date}}" />
			   </div>

         <div class="form-group">
            <label>Client</label>
            <input type="text" class="form-control" readonly value="{{$data->client->name}}" />
         </div>
         <div class="form-group">
            <label>Process</label>
            <input type="text" class="form-control" readonly value="{{$data->process->name}}" />
         </div>
         <div class="form-group">
            <label>Qm Sheet</label>
            <input type="text" class="form-control" readonly value="{{$data->qm_sheet->name}} - V{{$data->qm_sheet->version}}" />
         </div>
        	

          <div class="form-group">
            <label>Attachments</label>
            <input type="file" name="attachment" class="form-control" />
         </div>

          <div class="form-group">
            <label>Master Clibrator*</label>
            <input type="email" name="master_calibrator" required="required" readonly class="form-control" placeholder="Enter email" value="{{$mc[0]->email}}" />
          </div>

          </div>
        </div>

        <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
          <h6>Add Calibrators</h6>
          <span class="form-text text-muted">Please enter same email id for registered user on this application.</span>
          <br/>
          <div id="kt_repeater_qm_sheet">
                        <div class="form-group  row" id="kt_repeater_qm_sheet">
                          <div data-repeater-list="calibrator" class="col-lg-10">

                            @foreach($data->calibrator as $kk=>$vv)
                              @if($vv->is_master==0)

                              <div data-repeater-item class="form-group row align-items-center">
                              <div class="col-md-6">
                                <div class="kt-form__group--inline">
                                  <div class="kt-form__label">
                                    <label>Enter Email Id:</label>
                                  </div>
                                  <div class="kt-form__control">
                                    <input type="email" name="old_email" class="form-control" placeholder="Enter email" value="{{$vv->email}}" readonly />
                                  </div>
                                </div>
                                <div class="d-md-none kt-margin-b-10"></div>
                              </div>
                              <div class="col-md-4">
                                <div data-repeater-delete="" class="btn-sm btn btn-danger btn-pill" @click="onCalibrationEditDelete({{$vv->id}})">
                                  <span>
                                    <i class="la la-trash-o"></i>
                                    <span>Delete</span>
                                  </span>
                                </div>
                              </div>
                            </div>

                              @endif
                            @endforeach

                            <div data-repeater-item class="form-group row align-items-center">
                              <div class="col-md-6">
                                <div class="kt-form__group--inline">
                                  <div class="kt-form__label">
                                    <label>Enter Email Id:</label>
                                  </div>
                                  <div class="kt-form__control">
                                  	<input type="email" name="email" class="form-control" placeholder="Enter email"/>
                                  </div>
                                </div>
                                <div class="d-md-none kt-margin-b-10"></div>
                              </div>
                              <div class="col-md-4">
                                <div data-repeater-delete="" class="btn-sm btn btn-danger btn-pill">
                                  <span>
                                    <i class="la la-trash-o"></i>
                                    <span>Delete</span>
                                  </span>
                                </div>
                              </div>
                            </div>


                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-lg-4">
                            <div data-repeater-create="" class="btn btn btn-sm btn-brand btn-pill">
                              <span>
                                <i class="la la-plus"></i>
                                <span>Add</span>
                              </span>
                            </div>
                          </div>
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

    </div>



@endsection
@section('js')
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
@include('shared.form_js')
<script type="text/javascript">
	function markCheckBox(ctrl) {$('input:checkbox.group-name').prop("checked", false);
$("[name='" + ctrl + "']").prop("checked", true);
}
</script>
@endsection