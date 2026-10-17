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
											<form class="kt-form kt-form--label-right">

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
													
													<input type="text" readonly class="form-control" value="{{$audit_data->audit_date}}"/>
												</div>
												<div class="col-lg-4">
													<label>Agent Name:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$raw_data->agent_name}}">
												</div>
												<div class="col-lg-4">
													<label>User Name:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$raw_data->user_name}}">
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
													<input type="text" class="form-control" readonly="readonly" value="{{$raw_data->disposition}}">
												</div>
												<div class="col-lg-4">
													<label>Customer Name:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$raw_data->customer_name}}">
												</div>
												<div class="col-lg-4">
													<label>Cusotmer contact number:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$raw_data->phone_number}}">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-lg-4">
													<label>QRC 1:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$raw_data->qrc_1}}">
												</div>
												<div class="col-lg-4">
													<label>QRC 2:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$audit_data->qrc_2}}">
												</div>
												<div class="col-lg-4">
													<label>Language 1:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$raw_data->language_1}}">
												</div>
											</div>

											<div class="form-group row">
												<div class="col-lg-4">
													<label>Language 2:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$audit_data->language_2}}">
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
													<input type="text" class="form-control is-invalid" id="inputWarning1" readonly="readonly" value="{{$audit_data->overall_score}}"/>
													@else
													<input type="text" class="form-control is-valid" id="inputSuccess1" readonly="readonly" value="{{$audit_data->overall_score}}"/>
													@endif
												</div>
											</div>

											<div class="form-group row">
												<div class="col-lg-4">
													<label>Call timestamp:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$raw_data->call_time}}">
												</div>
												<div class="col-lg-4">
													<label>Call duration:</label>
													<input type="text" class="form-control" readonly="readonly" value="{{$raw_data->call_duration}}">
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
														<div class="col-md-1 kt-font-bolder">Observation</div>
														<div class="col-md-1 kt-font-bolder">Scored</div>
														<div class="col-md-2 kt-font-bolder">Failure Type</div>
														<div class="col-md-2 kt-font-bolder">Failure Reason</div>
														<div class="col-md-2 kt-font-bolder">Remarks</div>	
														<div class="col-md-2 kt-font-bolder">Screenshot</div>
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
														<div class="col-md-1">
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
														<div class="col-md-2">
															@if($svalue['screenshot'])
															
																<img style="height:40px;width:40px;" src="/storage/{{$svalue['screenshot']}}">
															@else

															{{'-'}}
															@endif

														</div>
													</div>
													@endforeach
												</div>
											</div>
											@endforeach
											
										</div>
									</div>
									@if($audit_data->client_id == 23)
										<div class="kt-portlet kt-portlet--mobile">
											<div class="kt-portlet__head" style="background-color: #103264">
												<div class="kt-portlet__head-label">
													<h3 class="kt-portlet__head-title kt-font-light">
														Feedback To Agent
													</h3>
												</div>
											</div>
											<div class="kt-portlet__body">
												{!! Form::open(
												array(
													'route' => 'store_feedback', 
													'class' => 'kt-form',
													'role'=>'form',
													'data-toggle'=>"validator")
												) !!}
													<input type="hidden" name="raw_data_id" value="{{Crypt::encrypt($raw_data->id)}}">
													<input type="hidden" name="audit_id" value="{{Crypt::encrypt($audit_data->id)}}">
													<div class="row">
														<div class="col-md-6">
															<!-- <h6>Submit Feedback Comment</h6> -->

															<div class="form-group">
																<label>Date</label>
																<input type="datetime" name="feedback_date" class="form-control" readonly value="{{$audit_data->feedback_shared_date}}">
															</div>
															<!-- <div class="form-group">
																<label>Status</label>
																<select class="form-control" required name="feedback_status">
																	<option value="">Select One!</option>
																	<option value="1">Accepted</option>
																	<option value="2">Rejected</option>
																
																</select>
															</div> -->
															<div class="form-group">
																<label>Feedback Shared Status</label>
																<select class="form-control" required name="feedback">
																	<option value="">Select One!</option>
																	<option value="1" @if($audit_data->feedback_shared_status ==1) selected @endif >Feedback Shared</option>
																	{{-- <option value="0">Feedback Not Shared</option> --}}
																	<!-- <option value="3">Close</option>
																	<option value="4">Pending</option> -->
																</select>
															</div>
															<div class="form-group">
																<label>Feedback</label>
																<textarea class="form-control" name="feedback_remarks">{{$audit_data->feedback_comment}}</textarea>
															</div>
															<div class="kt-portlet__foot">
															<div class="kt-form__actions">
																<button type="submit" class="btn btn-primary" @if($audit_data->feedback_shared_status ==1) disabled @endif>Submit</button>
																<button type="reset" class="btn btn-secondary">Cancel</button>
															</div>
															</div>

														</div>
														<!-- <div class="col-md-6">
															<h6>Feedback Data</h6>
															@if($audit_data->feedback_status==0)
					
															<div class="form-group form-group-last">
																<div class="alert alert-secondary" role="alert">
																	<div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
																		<div class="alert-text">
																			Not Given.
																		</div>
																	</div>
																</div>
																@else
																
																<form class="kt-form kt-form--label-right">
																	<div class="kt-portlet__body">
																		<div class="form-group row">
																			<label for="example-text-input" class="col-2 col-form-label">Status</label>
																			<div class="col-10">
																				@if($audit_data->is_critical)
																				<input class="form-control text-danger" type="text" value="{{return_feedback_status($audit_data->feedback_status)}}" id="example-text-input">
																				@else
																				<input class="form-control text-success" type="text" value="{{return_feedback_status($audit_data->feedback_status)}}" id="example-text-input">
																				@endif
																			</div>
																		</div>
																		<div class="form-group row">
																			<label for="example-search-input" class="col-2 col-form-label">Feedback By Qa</label>
																			<div class="col-10">
																				<textarea class="form-control">{{$audit_data->feedback}}</textarea>
																			</div>
																		</div>
																		<div class="form-group row">
																			<label for="example-text-input" class="col-2 col-form-label">Dated</label>
																			<div class="col-10">
																				<input class="form-control" type="text" readonly id="example-text-input" value="{{$audit_data->feedback_date}}">
																			</div>
																		</div>
																		<div class="form-group row">
																			<label for="example-email-input" class="col-2 col-form-label">Feedback Comment </label>
																			<div class="col-10">
																				<input class="form-control" type="email" value="bootstrap@example.com" id="example-email-input">
																			</div>
																		</div>
																	</div>
																</form>
															@endif
														</div> -->
													</div>
												</form>
											
											</div>
										</div>
									@endif

									@if(strtotime($audit_data->rebuttal_tat) > strtotime(date('Y-m-d H:i:s')))
									<div class="row">

										<div class="col-md-12">
										<!--begin::Portlet-->
										<div class="kt-portlet kt-portlet--mobile">
											<div class="kt-portlet__head" style="background-image: linear-gradient(to right, #845ec2, #906dc6, #9c7dc9, #a88ccd, #b39cd0);">
												<div class="kt-portlet__head-label">
													<h3 class="kt-portlet__head-title kt-font-light">
														Raise Rebuttal
													</h3>
												</div>
											</div>
											<div class="kt-portlet__body">
												{!! Form::open(
												array(
													'route' => 'raise_rebuttal', 
													'class' => 'kt-form',
													'enctype'=>'multipart/form-data',
													'role'=>'form',
													'data-toggle'=>"validator")
												) !!}
												<input type="hidden" name="raw_data_id" value="{{Crypt::encrypt($raw_data->id)}}">
												<input type="hidden" name="audit_id" value="{{Crypt::encrypt($audit_data->id)}}">

												<div id="kt_repeater_1">
													<div class="form-group  row" id="kt_repeater_1">
														<div data-repeater-list="rebuttals" class="col-lg-10">

															<div data-repeater-item class="form-group row align-items-center">

																<div class="col-md-3">
																	<div class="kt-form__group-inline">
																		<div class="kt-form__control">
																			<select class="form-control" required="required" name="sp">
																				<option value="">Select Sub Parameter</option>
																				@foreach($all_sub_parameters as $k=>$v)
																				<option value="{{$v['key']}}">{{$v['value']}}</option>
																				@endforeach
																			</select>
																		</div>
																	</div>
																</div>
																<div class="col-md-5">
																	<div class="kt-form__group--inne">
																		<div class="kt-form__control">
																			<textarea required="required" class="form-control" placeholder="Remarks" name="remark"></textarea>
																		</div>
																	</div>
																</div>
																@if (Auth::user()->parent_client != 23)
																<div class="col-md-3">
																	<div class="kt-form__group--inne">
																		<div class="kt-form__control">
																			<input type="file" name="artifact">
																			<small>upload realted artifacts</small>
																		</div>
																	</div>
																</div>
																@endif
																<div class="col-md-1">
																	<div data-repeater-delete="" class="btn-sm btn btn-danger btn-pill">
																		<span>
																			<i class="la la-trash-o"></i>
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

												
											<div class="kt-form__actions">
												<div class="row">
													<div class="col-lg-2">
														<button type="Submit" class="btn btn-success">Submit</button>
													</div>
												</div>
											</div>
											
										

												</form>

												

											</div>
										</div>
										</div>
									</div>
									@endif

									<div class="row">
										<div class="col-md-12">
									<!--begin::Portlet-->
									<div class="kt-portlet kt-portlet--mobile">
										<div class="kt-portlet__head" style="background-image: linear-gradient(to right, #845ec2, #906dc6, #9c7dc9, #a88ccd, #b39cd0);">
											<div class="kt-portlet__head-label">
												<h3 class="kt-portlet__head-title kt-font-light">
													Previous Rebuttal Status
												</h3>
											</div>
										</div>
										<div class="kt-portlet__body">
											<!--begin: Datatable -->
		<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
			<thead>
				<tr>
						<th title="Field #1">#</th>
						<th title="Field #4">
							Status
						</th>
						@if($audit_data->client_id != 9)
						<th title="Field #4">
							Re-rebuttal
						</th>
						@endif
						<th title="Field #2">
							Parameter
						</th>
						<th title="Field #3">
							Sub Parameter
						</th>
						<th title="Field #3">
							Rebuttal Remark
						</th>
						<th title="Field #5">
							Raised At
						</th>
						<th title="Field #3">
							Reply Remark
						</th>
						<th title="Field #5">
							Replied At
						</th>
						<th title="Field #5">
							Artifact
						</th>
				</tr>
			</thead>
			<tbody>
				@foreach($rebuttal_data as $row)
				<tr>
					<td>{{$loop->iteration}}</td>
					@if($audit_data->client_id != 9)
					<td>{{rebuttal_status($row->status)}}</td>
					@endif
					<td>{{($row->re_rebuttal_id)?'Yes':'No'}}</td>
					<td>
						{{$row->parameter->parameter}}
					</td>
					<td>
						{{$row->sub_parameter->sub_parameter}}
					</td>
					<td>
						{{$row->remark}}
					</td>
					<td>{{$row->created_at}}</td>
					<td>
						{{$row->reply_remark}}
					</td>
					<td>
						@if($row->status>0)
						{{$row->updated_at}}
						@else
						-
						@endif
					</td>
					<td>
						@if($row->artifact)
							<a href="{{ Storage::url('company/_'.Auth::user()->company_id.'/rebuttals/'.$row->artifact) }}" target="_blank" >Download</a>
						@else
							N/A
						@endif

					</td>
				
            	</tr>
            @endforeach

        </tbody>
    </table>
										</div>
									</div>
									</div>
									</div>


	<!-- <div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head" style="background-image: linear-gradient(to right, #2c73d2, #007ecc, #0086be, #008bad, #008e9b);">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title kt-font-light">
					Feedback To Agent
				</h3>
			</div>
		</div>
		<div class="kt-portlet__body">
			{!! Form::open(
              array(
                'route' => 'store_feedback', 
                'class' => 'kt-form',
                'role'=>'form',
                'data-toggle'=>"validator")
              ) !!}
              <input type="hidden" name="raw_data_id" value="{{Crypt::encrypt($raw_data->id)}}">
              <input type="hidden" name="audit_id" value="{{Crypt::encrypt($audit_data->id)}}">
              <div class="row">
              	<div class="col-md-6">
              		<h6>Submit Feedback Comment</h6>

              <div class="form-group">
            <label>Date</label>
            <input type="text" name="feedback_date" class="form-control" readonly value="{{date('Y-m-d')}}">
          </div>
          <div class="form-group">
            <label>Status</label>
            <select class="form-control" required name="feedback_status">
            	<option value="">Select One!</option>
            	<option value="1">Accepted</option>
            	<option value="2">Rejected</option>
            	<option value="3">Close</option>
            	<option value="4">Pending</option>
            </select>
          </div>
          <div class="form-group">
            <label>Feedback</label>
            <textarea class="form-control" name="feedback"></textarea>
          </div>
        <div class="kt-portlet__foot">
          <div class="kt-form__actions">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-secondary">Cancel</button>
          </div>
        </div>

        </div>
        <div class="col-md-6">
        	<h6>Feedback Data</h6>
        	@if($audit_data->feedback_status==0)
        	
        	<div class="form-group form-group-last">
						<div class="alert alert-secondary" role="alert">
							<div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
							<div class="alert-text">
								Not Given.
							</div>
						</div>
					</div>
        	@else
        
			<form class="kt-form kt-form--label-right">
				<div class="kt-portlet__body">
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Status</label>
						<div class="col-10">
							@if($audit_data->is_critical)
							<input class="form-control text-danger" type="text" value="{{return_feedback_status($audit_data->feedback_status)}}" id="example-text-input">
							@else
							<input class="form-control text-success" type="text" value="{{return_feedback_status($audit_data->feedback_status)}}" id="example-text-input">
							@endif
						</div>
					</div>
					<div class="form-group row">
						<label for="example-search-input" class="col-2 col-form-label">Feedback By Qa</label>
						<div class="col-10">
							<textarea class="form-control">{{$audit_data->feedback}}</textarea>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Dated</label>
						<div class="col-10">
							<input class="form-control" type="text" readonly id="example-text-input" value="{{$audit_data->feedback_date}}">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-email-input" class="col-2 col-form-label">Feedback Comment </label>
						<div class="col-10">
							<input class="form-control" type="email" value="bootstrap@example.com" id="example-email-input">
						</div>
					</div>
				</div>
			</form>


        	@endif
        </div>
              </div>
          	</form>
			
		</div>
	</div> -->

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
@endsection