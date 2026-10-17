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
                    'route' => 'rca.store', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator",
                    "enctype"=>'multipart/form-data')
                  ) !!}
                  <input type="hidden" name="company_id" value="{{Auth::User()->company_id}}">

									<div class="kt-portlet__body">
										<div class="kt-form__section kt-form__section--first">

												<div class="form-group row">
													<label class="col-lg-2 col-form-label">Client:</label>
													<div class="col-lg-4">
														{{ Form::select('client_id',$client_list,null,['class'=>'form-control','placeholder'=>'Select a client']) }}
													</div>
												</div>
												<div class="form-group row">
													<label class="col-lg-2 col-form-label">Process:</label>
													<div class="col-lg-4">
														{{ Form::select('process_id',$process_list,null,['class'=>'form-control','placeholder'=>'Select a process']) }}
													</div>
												</div>
											<div class="form-group row">
												<label class="col-lg-2 col-form-label">RCA data file:</label>
												<div class="col-lg-4">
													<input type="file" name="rca_data_file" class="form-control"/>
												</div>
											</div>
											<a href="/rca_data.xlsx" download="download">Click to download example RCA data file format, please same structure before upload.</a>

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