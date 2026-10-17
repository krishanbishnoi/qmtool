@extends('layouts.app')

@section('sh-title')
Audit
@endsection

@section('sh-detail')
Detail
@endsection

@section('main')
									<!--begin::Portlet-->
									<div class="kt-portlet kt-portlet--mobile">
										<div class="kt-portlet__head" style="background-image: linear-gradient(to right, #845ec2, #906dc6, #9c7dc9, #a88ccd, #b39cd0);">
											<div class="kt-portlet__head-label">
												<h3 class="kt-portlet__head-title kt-font-light">
													Basic Details
												</h3>
											</div>
										</div>
										<div class="kt-portlet__body">
											<!-- <form class="kt-form kt-form--label-right"> -->
										{!! Form::open(
						                  array(
						                    'url' => 'qc/update_basic_audit_data', 
						                    'class' => 'kt-form kt-form--label-right',
						                    'role'=>'form',
						                    'data-toggle'=>"validator")
						                  ) !!}
						                  <input type="hidden" name="audit_id" value="{{Crypt::encrypt($audit_data->id)}}">
											<div class="form-group row">
												<div class="col-lg-4">
													<label>Communication Instance ID(Call ID):</label>
													<input type="text" name="call_id" class="form-control" value="{{$raw_data->call_id}}">
												</div>
												<div class="col-lg-4">
													<label>Client:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$raw_data->client->name}}">
												</div>
												<div class="col-lg-4">
													<label>Partner:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$raw_data->partner->name}}">
												</div>
											</div>

											<div class="form-group row">
												<div class="col-lg-4">
													<label>Audit Date:</label>
													<input type="text" class="form-control" id="kt_datetimepicker_audit_component" readonly placeholder="Select date & time" required="required" value="{{$audit_data->audit_date}}" name="audit_date" />
												</div>
												<div class="col-lg-4">
													<label>Agent Name:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$raw_data->agent_name}}">
												</div>
												<div class="col-lg-4">
													<label>TL Name:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$raw_data->tl}}">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-lg-4">
													<label>QA / QTL Name:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$audit_data->qa_qtl_detail->name}}">
												</div>
												<div class="col-lg-4">
													<label>Call Type:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$raw_data->call_type}}">
												</div>
												<div class="col-lg-4">
													<label>Call Sub Type:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$raw_data->call_sub_type}}">
												</div>
												
											</div>
											<div class="form-group row">
												<div class="col-lg-4">
													<label>Disposition:</label>
													<input type="text" class="form-control" value="{{$raw_data->disposition}}" name="disposition">
												</div>
												<div class="col-lg-4">
													<label>Customer Name*:</label>
													<input type="text" class="form-control" name="customer_name"  value="{{$raw_data->customer_name}}" required>
												</div>
												<div class="col-lg-4">
													<label>Cusotmer contact number*:</label>
													<input type="text" class="form-control" name="phone_number"  value="{{$raw_data->phone_number}}" required>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-lg-4">
													<label>QRC 1:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$raw_data->qrc_1}}">
												</div>
												<div class="col-lg-4">
													<label>QRC 2*:</label>
													{{ Form::select('qrc_2',['Query'=>'Query','Request'=>'Request','Complaint'=>'Complaint'],$audit_data->qrc_2,['class'=>'form-control','required'=>'required']) }}
												</div>
												<div class="col-lg-4">
													<label>Language 1:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$raw_data->language_1}}">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-lg-4">
													<label>Call Time:</label>
													<input type="text" class="form-control" value="{{$raw_data->call_time}}" name="call_time">
												</div>
												<div class="col-lg-4">
													<label>Call Duration:</label>
													<input type="text" class="form-control" value="{{$raw_data->call_duration}}" name="call_duration">
												</div>
												<div class="col-lg-4">
													<label>Refrence Number:</label>
													<input type="text" class="form-control" value="{{$audit_data->refrence_number}}" name="refrence_number">
												</div>
											</div>

											<div class="form-group row">
												<div class="col-lg-4">
													<label>Language 2*:</label>
													<input type="text" class="form-control" name="language_2" required  value="{{$audit_data->language_2}}">
												</div>
												<div class="col-lg-4">
													<label>Is Call Fatal:</label>
													@if($audit_data->is_critical)
													<input type="text" class="form-control is-invalid" id="inputWarning1" readonly="readonly" value="YES"/>
													@else
													<input type="text" class="form-control is-valid" id="inputSuccess1" readonly="readonly" value="NO"/>
													@endif
												</div>
												<div class="col-lg-4">
													<label>Overall Score:</label>
													@if($audit_data->is_critical)
													<input type="text" class="form-control is-invalid" id="inputWarning1" readonly="readonly" value="{{$audit_data->overall_score}}" />
													@else
													<input type="text" class="form-control is-valid" id="inputSuccess1" readonly="readonly" value="{{$audit_data->overall_score}}"/>
													@endif
													
												</div>
											</div>
											<div class="form-group row">
												<div class="col-lg-4">
													<label>Case ID*:</label>
													<input type="text" class="form-control" name="case_id" value="{{$audit_data->case_id}}" required>
												</div>
												<div class="col-lg-4">
													<label>Overall Call Summary*:</label>
													<textarea class="form-control" name="overall_summary" required>{{$audit_data->overall_summary}}</textarea>
												</div>
												<div class="col-lg-4">
													<label>Feedback to Agent*:</label>
													<textarea class="form-control" name="feedback">{{$audit_data->feedback}}</textarea>
												</div>
											</div>

											<div class="kt-portlet__foot">
									          <div class="kt-form__actions">
									            <button type="submit" class="btn btn-success">Update Basic Data</button>
									            <button type="reset" class="btn btn-secondary">Cancel</button>
									          </div>
									        </div>

											</form>
										</div>
									</div>
									<!--end::Portlet-->

									<!--begin::Portlet-->
									<div class="kt-portlet kt-portlet--mobile">
										<div class="kt-portlet__head" style="background-image: linear-gradient(to right, #845ec2, #906dc6, #9c7dc9, #a88ccd, #b39cd0);">
											<div class="kt-portlet__head-label">
												<h3 class="kt-portlet__head-title kt-font-light">
													Audit Observation Details
												</h3>
											</div>
										</div>
										<div class="kt-portlet__body">

											<div class="row">
												<div class="col-md-2 kt-font-bolder">Behavior</div>
												<div class="col-md-10 kt-font-bolder">
													<div class="row">
														<div class="col-md-2 kt-font-bolder">Parameter</div>
														<div class="col-md-2 kt-font-bolder">Observation</div>
														<div class="col-md-1 kt-font-bolder">Scored</div>
														<div class="col-md-2 kt-font-bolder">Failure Type</div>
														<div class="col-md-2 kt-font-bolder">Failure Reason</div>
														<div class="col-md-2 kt-font-bolder">Remarks</div>	
														<div class="col-md-1 kt-font-bolder">Update</div>
													</div>
												</div>
											</div>
											<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg" id="seprator"></div>

											@foreach($final_data as $key=>$value)
											<div class="row" style="border-bottom:1px solid #ccc; padding: 20px 0; height: 100%; ">
												<div class="col-md-2 kt-font-bolder kt-font-primary flex-item" >{{$value['name']}}</div>
												<div class="col-md-10 sp-row">
													@foreach($value['sp'] as $skey=>$svalue)
													<div class="row flex-container">

														<div class="col-md-2 kt-font-bold">
															{{$svalue['name']}} <i class="la la-info-circle kt-font-warning sp-details-top" title="{{$svalue['detail']}}"></i>
														</div>
														<div class="col-md-2">
															{{$svalue['selected_option']}}
														</div>
														<div class="col-md-1">
															{{$svalue['scored']}}
														</div>
														<div class="col-md-2">
															{{($svalue['reason_type'])?$svalue['reason_type']:'-'}}
														</div>
														<div class="col-md-2">
															{{$svalue['reason']?$svalue['reason']:'-'}}
														</div>
														<div class="col-md-2">
															<p style="width: 100%; display: block; overflow-wrap: break-word;">
															{{($svalue['remark'])?$svalue['remark']:'-'}} 
															</p>
														</div>
														<div class="col-md-1">
															
															<qc-sub-parameter-updater audit-id="{{$audit_data->id}}"
																					  parameter-id="{{$value['id']}}"
																					  sub-parameter-id="{{$svalue['id']}}"
																					  rebuttal-id="0">
														    </qc-sub-parameter-updater>
														  	
														</div>
													</div>
													@endforeach
												</div>
											</div>
											@endforeach
											 
										</div>
									</div>

									<div class="row">

									<div class="col-md-12">
									<!--begin::Portlet-->
									<div class="kt-portlet kt-portlet--mobile">
										<div class="kt-portlet__head" style="background-image: linear-gradient(to right, #845ec2, #906dc6, #9c7dc9, #a88ccd, #b39cd0);">
											<div class="kt-portlet__head-label">
												<h3 class="kt-portlet__head-title kt-font-light">
													Update QC Status
												</h3>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
				{!! Form::open(
                  array(
                    'url' => 'qc/update_qc_status', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator")
                  ) !!}
        <input type="hidden" name="audit_id" value="{{Crypt::encrypt($audit_data->id)}}">

        <div class="kt-portlet__body">

          <div class="form-group">
            <label>Status*</label>
            {{ Form::select('qc_status',[1=>'Pass with edit','Pass','Failed'],$audit_data->qc_status,['class'=>'form-control','required'=>'required','placeholder'=>'Select one!']) }}
          </div>
          <div class="form-group">
            <label>Feedback Comment</label>
            <textarea class="form-control" name="qc_comment">{{$audit_data->qc_comment}}</textarea>
          </div>
          
        </div>
        <div class="kt-portlet__foot">
          <div class="kt-form__actions">
            <button type="submit" class="btn btn-primary">Update Status</button>
            <button type="reset" class="btn btn-secondary">Cancel</button>
          </div>
        </div>
      </form>

      </div>
										</div>
									</div>
									</div>
									</div>
								



@endsection
@section('css')
<style type="text/css">
.sp-details-top
{
	cursor: pointer;

}
.flex-container
{
	display: flex;
	align-items:center;

}
.sp-row .row
{
		margin-bottom: 15px;

}
#seprator
{
	margin: 2.5rem 0 0 0;
}
@media screen and (min-width: 480px) {
  .sp-row .row .col-md-2
  {
	margin-top: 15px;
  }
}
</style>
@endsection
@section('js')
@include('shared.form_js')
<script type="text/javascript">
	window.scrollTo(0,document.body.scrollHeight);
</script>
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datetimepicker.js" type="text/javascript"></script>
@endsection