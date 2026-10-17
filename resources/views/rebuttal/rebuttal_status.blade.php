@extends('layouts.app')

@section('sh-title')
Rebuttal
@endsection

@section('sh-detail')
Audit Detail
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
						                    'url' => 'rebuttal/update_basic_audit_data', 
						                    'class' => 'kt-form kt-form--label-right',
						                    'role'=>'form',
						                    'data-toggle'=>"validator")
						                  ) !!}
						                  <input type="hidden" name="audit_id" value="{{Crypt::encrypt($audit_data->id)}}">
						                  <input type="hidden" name="rebuttal_id" value="{{Crypt::encrypt($rebuttal_data->id)}}">
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
													
													<input type="text" class="form-control" value="{{$audit_data->audit_date}}"/>
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
													<input type="text" class="form-control"  value="{{$raw_data->call_time}}" name="call_time">
												</div>
												<div class="col-lg-4">
													<label>Call Duration:</label>
													<input type="text" class="form-control" value="{{$raw_data->call_duration}}" name="call_duration">
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
													<textarea class="form-control" name="feedback" required>{{$audit_data->feedback}}</textarea>
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
															{{($svalue['remark'])?$svalue['remark']:'-'}} 
														</div>
														<div class="col-md-1">
															
															<qc-sub-parameter-updater audit-id="{{$audit_data->id}}"
																					  parameter-id="{{$value['id']}}"
																					  sub-parameter-id="{{$svalue['id']}}"
																					  rebuttal-id="{{$rebuttal_data->id}}">
																					  </qc-sub-parameter-updater>
														  	
														</div>
													</div>
													@endforeach
												</div>
											</div>
											@endforeach
											
										</div>
									</div>

									<!--begin::Portlet-->
									<div class="kt-portlet kt-portlet--mobile">
										<div class="kt-portlet__head" style="background-image: linear-gradient(to right, #845ec2, #906dc6, #9c7dc9, #a88ccd, #b39cd0);">
											<div class="kt-portlet__head-label">
												<h3 class="kt-portlet__head-title kt-font-light">
													Rebuttal Parameter
												</h3>
											</div>
										</div>
										<div class="kt-portlet__body">
											<div class="row">
												<div class="col-md-2 kt-font-bolder">Parameter</div>
												<div class="col-md-10 kt-font-bolder">
													<div class="row">
														<div class="col-md-2 kt-font-bolder">Sub Parameter</div>
														<div class="col-md-2 kt-font-bolder">Rebuttal Remark</div>
													</div>
												</div>
											</div>
											<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg" id="seprator"></div>

											{{-- <rebuttal-status rebuttal-id="{{$rebuttal_data->id}}"></rebuttal-status> --}}
											<form id="submitrebutaldata" method="post" enctype="multipart/form-data">
												@csrf
												<input type="hidden" name="audit_id" value="{{ $audit_data->id }}">
												<input type="hidden" name="rebuttal_id" value="{{ $rebuttal_data->id }}">
												<div class="row" style="padding: 20px 0; height: 100%; ">
													<div class="col-md-2 kt-font-bolder kt-font-primary flex-item">
														<input type="hidden" name="parameter" value="{{ $parameter_data->parameter }}">
														{{ $parameter_data->parameter }}</div>
													<div class="col-md-10">
														<div class="row">
															<div class="col-md-2 kt-font-bold">
																<input type="hidden" name="sub_parameter" value="{{ $sub_parameter_data->sub_parameter }}">
																{{ $sub_parameter_data->sub_parameter }}<i
																	class="la la-info-circle kt-font-warning sp-details-top"></i>
															</div>
															<div class="col-md-2 kt-font-bold">
																<input type="hidden" name="remark" value="{{ $rebuttal_data->remark }}">
																<p>{{ $rebuttal_data->remark }}</p>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label>Rebuttal Reply / Remark</label>
															<textarea name="reply_remark" class="form-control" minlength="30" required></textarea>
														</div>
													</div>
												</div>
												@if (Auth::user()->parent_client != 23)
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label>Artifact</label>
															<input type="file" id="file" name="file" ref="file" />
														</div>
													</div>
												</div>
												@endif
								
												@if (Auth::user()->parent_client == 23)
												<div class="row" style="padding: 20px 0; height: 100%; ">
													<div class="col-md-6">
														<input type="checkbox" id="bod_check" value = "bod" name = "bod_check" class = "mr-4"
															onchange="bod_hidding()">
														<label>Please check this for BOD</label>
								
														<div id = "bod_reason_div" style="display:none">
								
															<select name="bod_reasons" class="form-control mt-4" id="bod_reasons">
																<option value="" selected>Select Reason</option>
																@foreach ($bod_reasons as $reason)
																	<option value="{{ $reason->id }}">{{ $reason->reason_name }}</option>
																@endforeach
															</select>
															<div id="error_message" style="color: red; display: none; margin-top: 5px;"></div>
														</div>
													</div>
												</div>								
												@endif
								
												<div class="kt-portlet__oot">
								
													<div class="kt-form__actions">
														<button type="button" class="btn btn-success" id="btnAccept">Rebuttal Accepted</button>
														<button type="button" class="btn btn-danger" id="btnReject">Rebuttal Rejected</button>
														@if (Auth::user()->parent_client == 23)
														<button type="button" class="btn btn-success" id="bodbtn">BOD</button>
														@endif
													</div>
												</div>
											</form>
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



<script>
	$(document).ready(function() {
		$('#bod_check').change(function() {
			if (this.checked) {
				$('#bod_reason_div').show();
			} else {
				$('#bod_reason_div').hide();
			}
		});
	});
</script>

<script>
$(document).ready(function() {
	$('#btnAccept').click(function() {
		$('#submitrebutaldata').attr('data-status', '1');
		$('#submitrebutaldata').submit();
	});

	$('#btnReject').click(function() {
		$('#submitrebutaldata').attr('data-status', '2');
		$('#submitrebutaldata').submit();
	});
	$('#bodbtn').click(function() {
            $('#submitrebutaldata').attr('data-status', '1');
            $('#submitrebutaldata').submit();
        });
});

$(document).ready(function() {
	$("#submitrebutaldata").on("submit", function(e) {		
		e.preventDefault();
		if ($('#bod_check').is(':checked')) {
                // If checkbox is checked, validate that a reason is selected
                var selectedReason = $('#bod_reasons').val();

                if (!selectedReason || selectedReason === '') {
					showError('Please select a reason before submitting.');
                    return;
                }
            }
			hideError();
		let formData = new FormData(this);
		formData.append('reply_status', $(this).data('status'));

		$.ajax({
			url: "/reply_rebuttal",
			method: "POST",
			data: formData,
			processData: false,
			contentType: false,
			success: function(response) {
				if (response.status === 200) {
					window.location = "/raised_rebuttal_list";
				} else {
					console.log('not submit');
				}
			},
			error: function(xhr, status, error) {
				console.error(error);
			}
		});
	});
		function showError(message) {
            $('#error_message').text(message);
            $('#error_message').show();
        }

        function hideError() {
            $('#error_message').hide();
        }
});
</script>
@endsection