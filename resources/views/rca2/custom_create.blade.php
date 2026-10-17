@extends('layouts.app')

@section('sh-title')
RCA Master
@endsection

@section('sh-detail')
Create New
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
								{!! Form::open(
				                  array(
				                    'url' => 'add_custom_rca2',
				                    'method' => 'post', 
				                    'class' => 'kt-form',
				                    'role'=>'form',
				                    'data-toggle'=>'validator')
				                  ) 
				                !!}
                  					<input type="hidden" name="company_id" value="{{Auth::User()->company_id}}">
                  					<input type="hidden" value="{{Crypt::encrypt($id)}}" name="rca_id">
                  					<input type="hidden" value="{{$type}}" name="type">

									<div class="kt-portlet__body">
										<div class="kt-form__section kt-form__section--first">
											<div id="kt_repeater_1">
												<div class="form-group  row" id="kt_repeater_1">
													<label class="col-lg-2 col-form-label">RCA</label>
													<div data-repeater-list="rca" class="col-lg-10">
														<div data-repeater-item class="form-group row align-items-center">
															<div class="col-md-7">
																<div class="kt-form__group--inline">
																	<div class="kt-form__label">
																		<label>Name:</label>
																	</div>
																	<div class="kt-form__control">
																		<input type="text" class="form-control" placeholder="Enter rca name" name="name">
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