@extends('layouts.app')

@section('sh-title')
Reason
@endsection

@section('sh-detail')
Master
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
											Filter
										</h3>
									</div>
								</div>

								<!--begin::Form-->
					{!! Form::open(
                  array(
                    'url' => '/rca2', 
                    'method'=>'get',
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator",
                    "enctype"=>'multipart/form-data')
                  ) !!}
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


									<div class="kt-portlet">
										<div class="kt-portlet__head">
											<div class="kt-portlet__head-label">
												<h3 class="kt-portlet__head-title">
													RCA Tree Details
												</h3>
											</div>
										</div>
										<div class="kt-portlet__body">
											<div id="kt_tree_2" class="tree-demo">
												<ul>
													@foreach($data as $ka=>$va)
													<li>
														{{$va->name}}
														<ul>
															<li>
																Action
																<ul>
																	<li data-jstree='{"icon" : "fa fa-edit kt-font-warning" }'><a href="{{url('edit_rca2_mode?id='.Crypt::encrypt($va->id).'&data=1')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit"></a></li>

																	<li data-jstree='{"icon" : "fa fa-trash kt-font-danger" }'><a href="{{url('delete_rca2_mode?id='.Crypt::encrypt($va->id).'&data=1')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete"></a></li>
																</ul>
															</li>
															@foreach($va->rcatwo1 as $kb=>$vb)
															<li>
																{{$vb->name}}
																<ul>
																	<li>
																		Action
																		<ul>
																			<li data-jstree='{"icon" : "fa fa-plus kt-font-success" }'><a href="{{ url('add_custom_rca2?id='.Crypt::encrypt($vb->id).'&data=1')}}" title="Create"></a></li>
																			<li data-jstree='{"icon" : "fa fa-edit kt-font-warning" }'><a href="{{url('edit_rca2_mode?id='.Crypt::encrypt($vb->id).'&data=2')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit"></a></li>
																			<li data-jstree='{"icon" : "fa fa-trash kt-font-danger" }'><a href="{{url('delete_rca2_mode?id='.Crypt::encrypt($vb->id).'&data=2')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete"></a></li>
																		</ul>
																	</li>
																	@foreach($vb->rcatwo2 as $kc=>$vc)
																	<li>
																		{{$vc->name}}
																		<ul>
																			<li>
																				Action
																				<ul>
																					<li data-jstree='{"icon" : "fa fa-plus kt-font-success" }'><a href="{{ url('add_custom_rca2?id='.Crypt::encrypt($vc->id).'&data=2')}}" title="Create"></a></li>
																					<li data-jstree='{"icon" : "fa fa-edit kt-font-warning" }'><a href="{{url('edit_rca2_mode?id='.Crypt::encrypt($vc->id).'&data=3')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit"></a></li>
																					<li data-jstree='{"icon" : "fa fa-trash kt-font-danger" }'><a href="{{url('delete_rca2_mode?id='.Crypt::encrypt($vc->id).'&data=3')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete"></a></li>
																				</ul>
																			</li>
																			@foreach($vc->rcatwo3 as $kd=>$vd)
																			<li>
																				<ul>
																					<li>
																						Action
																						<ul>
																							<li data-jstree='{"icon" : "fa fa-edit kt-font-warning" }'><a href="{{url('edit_rca2_mode?id='.Crypt::encrypt($vd->id).'&data=4')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit"></a></li>
																							<li data-jstree='{"icon" : "fa fa-trash kt-font-danger" }'><a href="{{url('delete_rca2_mode?id='.Crypt::encrypt($vd->id).'&data=4')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete"></a></li>
																						</ul>
																					</li>
																				</ul>
																				{{$vd->name}}
																			</li>
																			@endforeach
																		</ul>
																	</li>
																	@endforeach
																</ul>
															</li>
															@endforeach
														</ul>
													</li>
													@endforeach
												</ul>
											</div>
											<div class="alert alert-outline-primary kt-margin-t-10">
												Note! If you want to add RCA2 mode with it's Rca1,2 and 3 then please use RCA2 -> Create.
											</div>
										</div>
									</div>

									<!--end::Portlet-->

@endsection

@section('css')
	<link href="/assets/vendors/custom/jstree/jstree.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('js')
	<script src="/assets/vendors/custom/jstree/jstree.bundle.js" type="text/javascript"></script>
	<script src="/assets/app/custom/general/components/extended/treeview.js" type="text/javascript"></script>
@endsection