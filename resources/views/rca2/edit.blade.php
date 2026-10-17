@extends('layouts.app')

@section('sh-title')
RCA2 Master
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
								{!! Form::open(
				                  array(
				                  	'method' => 'PATCH',
				                    'url' =>'edit_rca2_mode/'.Crypt::encrypt($data->id),
				                    'class' => 'kt-form',
				                    'data-toggle'=>"validator",				                    'role'=>'form')
				                  ) 
				                !!}
				                	<input type="hidden" name="type" value="{{ $type }}">
			                  		<input type="hidden" name="company_id" value="{{Crypt::encrypt($data->company_id)}}">
			                  		<input type="hidden" name="client_id" value="{{Crypt::encrypt($data->client_id)}}">
			                  		<input type="hidden" name="process_id" value="{{Crypt::encrypt($data->process_id)}}">
									<div class="kt-portlet__body">
										<div class="kt-form__section kt-form__section--first">
											<div class="form-group row">
												<label class="col-lg-2 col-form-label">Name:</label>
												<div class="col-lg-4">
													<input value="{{$data->name}}" type="text" name="name" class="form-control"/>
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