@extends('layouts.app')

@section('sh-title')
Reason Master
@endsection

@section('sh-detail')
Edit
@endsection

@section('main')

<!--begin::Portlet-->
							<div class="kt-portlet">
								<div class="kt-portlet__head">
									<div class="kt-portlet__head-label">
										<span class="kt-portlet__head-icon kt-hidden">
											<i class="la la-gear"></i>
										</span>
										<h3 class="kt-portlet__head-title">
											Detail
										</h3>
									</div>
								</div>

								<!--begin::Form-->
								{!! Form::model($data,
                  array(
                  'method' => 'PATCH',
                    'url' =>'reason/'.Crypt::encrypt($data->id),
                    'class' => 'kt-form',
                    'data-toggle'=>"validator")
                  ) !!}
                  <input type="hidden" name="company_id" value="{{AUth::User()->company_id}}">
									<div class="kt-portlet__body">
										<div class="kt-form__section kt-form__section--first">

											<div class="form-group row">
												<label class="col-lg-2 col-form-label">Client:</label>
												<div class="col-lg-4">
													{{ Form::select('client_id',$client_list,$data->client_id,['class'=>'form-control','placeholder'=>'Select a client']) }}
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-2 col-form-label">Process:</label>
												<div class="col-lg-4">
													{{ Form::select('process_id',$process_list,$data->process_id,['class'=>'form-control','placeholder'=>'Select a process']) }}
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-2 col-form-label">Reason Type:</label>
												<div class="col-lg-4">
													<input type="text" name="name" class="form-control" value="{{$data->name}}">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-2 col-form-label">Label:</label>
												<div class="col-lg-4">
													<input type="text" name="label" class="form-control" value="{{$data->label}}">
													<span class="form-text text-muted">It is for Qm-Sheet identification like People - P1 - SP1, Process - P1 - SP1, System - P1 - SP1</span>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-2 col-form-label">Details:</label>
												<div class="col-lg-4">
													<textarea class="form-control" name="details">{{$data->details}}</textarea>
												</div>
											</div>
										
									
											<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
											<div id="kt_repeater_qm_sheet">
												<div class="form-group  row" id="kt_repeater_qm_sheet">
													<label class="col-lg-2 col-form-label">Reasons:</label>

													<div data-repeater-list="reasons" class="col-lg-10">

														@foreach($data->reasons as $kk=>$vv)
														<div data-repeater-item class="form-group row align-items-center">
															<input type="hidden" name="row_id" value="{{$vv->id}}">
															<div class="col-md-7">
																<div class="kt-form__group--inline">
																	<div class="kt-form__label">
																		<label>Name:</label>
																	</div>
																	<div class="kt-form__control">
																		<input type="text" class="form-control" placeholder="Enter reasons of above type" name="reason" value="{{$vv->name}}">
																	</div>
																</div>
																<div class="d-md-none kt-margin-b-10"></div>
															</div>
															<div class="col-md-4">
																<div data-repeater-delete="" class="btn-sm btn btn-danger btn-pill" @click="onReasonsDelete({{$vv->id}})">
																	<span>
																		<i class="la la-trash-o"></i>
																		<span>Delete</span>
																	</span>
																</div>
															</div>
														</div>
														@endforeach


														<div data-repeater-item class="form-group row align-items-center">
															<input type="hidden" name="row_id">
															<div class="col-md-7">
																<div class="kt-form__group--inline">
																	<div class="kt-form__label">
																		<label>Name:</label>
																	</div>
																	<div class="kt-form__control">
																		<input type="text" class="form-control" placeholder="Enter reasons of above type" name="reason">
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
													<label class="col-lg-2 col-form-label"></label>
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
									</div>
									<div class="kt-portlet__foot">
										<div class="kt-form__actions">
											<div class="row">
												<div class="col-lg-2"></div>
												<div class="col-lg-2">
													<button type="Submit" class="btn btn-success">Submit</button>
													<button type="reset" class="btn btn-secondary">Cancel</button>
												</div>
											</div>
										</div>
									</div>
								</form>

								<!--end::Form-->
							</div>

							<!--end::Portlet-->

@endsection
@section('js')
@include('shared.form_js')
@endsection