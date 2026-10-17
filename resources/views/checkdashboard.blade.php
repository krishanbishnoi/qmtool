<!DOCTYPE html>

<html lang="en">

<!-- begin::Head -->
<head>
	<meta charset="utf-8" />
	<title>QM Tool</title>
	<meta name="description" content="QuoteNow">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<!--begin::Page Vendors Styles(used by this page) -->
	
     <link rel="stylesheet" href="assets/vendors/custom/fullcalendar/fullcalendar.bundle.css">
	<!--RTL version:{!! Html::style('assets/vendors/custom/fullcalendar/fullcalendar.bundle.rtl.css') !!}-->

	<!--end::Page Vendors Styles -->

	<!--begin::Global Theme Styles(used by all pages) -->
	<link rel="stylesheet" href="assets/vendors/base/vendors.bundle.css">


	<!--RTL version:{!! Html::style('assets/vendors/base/vendors.bundle.rtl.css') !!}-->
	<link rel="stylesheet" href="assets/demo/default/base/style.bundle.css">
	
	<!--RTL version:{!! Html::style('assets/demo/default/base/style.bundle.rtl.css') !!}-->

	<!--end::Global Theme Styles -->

	<!--begin::Layout Skins(used by all pages) -->
	<link rel="stylesheet" href="assets/demo/default/skins/header/base/light.css">
	
	<!--RTL version:{!! Html::style('assets/demo/default/skins/header/base/light.rtl.css') !!}-->
	<link rel="stylesheet" href="assets/demo/default/skins/header/menu/light.css">
	

	<!--RTL version:{!! Html::style('assets/demo/default/skins/header/menu/light.rtl.css') !!}-->
	<link rel="stylesheet" href="assets/demo/default/skins/brand/dark.css">
	
	<!--RTL version:{!! Html::style('assets/demo/default/skins/brand/dark.rtl.css') !!}-->
	<link rel="stylesheet" href="assets/demo/default/skins/aside/dark.css">
	<!--RTL version:{!! Html::style('assets/demo/default/skins/aside/dark.rtl.css') !!}-->

	<!--end::Layout Skins -->
	<link rel="shortcut icon" href="{{url('assets/media/logos/logo_default_dark.png')}}" />
</head>

<!-- end::Head -->

<!-- begin::Body -->
<body>
		
<div>
		
		 
        <div id="div_container">
		<!--begin:: Widgets/Stats-->
							<div class="kt-portlet">
								<div class="kt-portlet__body  kt-portlet__body--fit">
									<div class="row row-no-padding row-col-separator-xl">
										<div class="col-md-12 col-lg-6 col-xl-4">

	
											<!--begin::Total Profit-->
											<div class="kt-widget24">
												<div class="kt-widget24__details">
													<div class="kt-widget24__info">
														<h4 class="kt-widget24__title">
															Coverage
														</h4>
														<span class="kt-widget24__desc">
															Target
														</span>
													</div>
													<span class="kt-widget24__stats kt-font-brand">
														<?php echo $final_data['coverage']['target']; ?>
													</span>
												</div>
												<div class="progress progress--sm">
													<div class="progress-bar kt-bg-brand" role="progressbar" style= "width: {{  $final_data['coverage']['achived_per'] }}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" ></div>
												</div>
												<div class="kt-widget24__action">
													<span class="kt-widget24__change">
														Achived
													</span>
													<span class="kt-widget24__number">
													<?php echo $final_data['coverage']['achived'] ." (". $final_data['coverage']['achived']. "%)"; ?>
													</span>
												</div>
											</div>
											<!--end::Total Profit-->
											
										</div>
										<div class="col-md-12 col-lg-6 col-xl-4">
 
											<!--begin::New Feedbacks-->
											<div class="kt-widget24">
												<div class="kt-widget24__details">
													<div class="kt-widget24__info">
														<h4 class="kt-widget24__title">
															Rebuttal
														</h4>
														<span class="kt-widget24__desc">
															Raised
														</span>
													</div>
													<span class="kt-widget24__stats kt-font-warning">
													{{ $final_data['rebuttal']['raised'] }} - {{ $final_data['rebuttal']['rebuttal_per'] }}%
													</span>
												</div>
												<div class="progress progress--sm">
													<div class="progress-bar kt-bg-warning" role="progressbar"  aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style = "width: {{ $final_data['rebuttal']['accepted_per'] }}%;"></div>
												</div>
												<div class="kt-widget24__action">
													<span class="kt-widget24__change">
														Accepted || Rejected
													</span>
													<span class="kt-widget24__number">
													{{ $final_data['rebuttal']['accepted'] }} - {{ $final_data['rebuttal']['accepted_per'] }} % || {{ $final_data['rebuttal']['rejected'] }} - {{ $final_data['rebuttal']['rejected_per'] }}%
													</span>
												</div>
												<div class="kt-widget24__action">
													<span class="kt-widget24__change">
														WIP
													</span>
													<span class="kt-widget24__number">
													{{ $final_data['rebuttal']['wip'] }} 
													</span>
												</div>
											</div>
											<!--end::New Feedbacks-->
										</div>

										

										<div class="col-md-12 col-lg-6 col-xl-3">

											<!--begin::New Orders-->
											<div class="kt-widget24">
												<div class="kt-widget24__details">
													<div class="kt-widget24__info">
														<h4 class="kt-widget24__title">
															FATAL
														</h4>
														<span class="kt-widget24__desc">
															Parameter Count
														</span>
													</div>
													<span class="kt-widget24__stats kt-font-danger">
													{{ $final_data['fatal_first_row_block']['total_fatal_count_sub_parameter'] }}
													</span>
												</div>
												<div class="progress progress--sm">
													<div class="progress-bar kt-bg-danger" style = "width: {{ $final_data['fatal_first_row_block']['total_fatal_audit_per'] }}%;" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
												</div>
												<div class="kt-widget24__action">
													<span class="kt-widget24__change">
														Fatal Call
													</span>
													<span class="kt-widget24__number">
													{{ $final_data['fatal_first_row_block']['total_fatal_audits'] }} ({{ $final_data['fatal_first_row_block']['total_fatal_audit_per'] }}%)
													</span>
												</div>
											</div>

											<!--end::New Orders-->
										</div>
									
									</div>
								</div>
							</div>
        
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Process Score
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-6">
								<h5 align="center">With Fatal</h5>
								<?php echo $request->process_fatal; ?>
							</div>
							<div class="col-md-6">
								<h5 align="center">Without Fatal</h5>
								<?php echo $request->process_without_fatal; ?>
							</div>
						</div>
					</div>
					<div class="col-md-6">

						<h5>Parameter Wise Defect</h5>
						<table class="table table-striped- table-bordered table-hover table-checkable" style="margin-top: 35px;">
			<thead>
				<tr>
						<th title="Field #1">#</th>
						<th title="Field #2">
							Parameter
						</th>
						<th title="Field #3" align="center">
							Fatal Count
						</th>
						<th title="Field #3" align="center">
							Fail Count
						</th>
						<th title="Field #4" align="center">
							Non - Compliance
						</th>
				</tr>
			</thead>
			<tbody>
				
					<!-- @php ($i = 1) -->
					@foreach($final_data['pwfcs'] as $value)

					

					<tr >
					<td>{{ $value['counter'] }}</td>
					<td>{{ $value['parameter'] }}</td>
					<td align="center"> {{ $value['fatal_count'] }}</td>
					<td align="center"> {{ $value['fail_count'] }}</td>
					<td align="center"> {{ 100 - $value['fatal_score'] }} %</td>
					</tr>
					<!-- @php ($i ++) -->
					@endforeach

			</tbody>
		</table>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
					
					<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Process Statistics 
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<div class="row" style="text-align: center;">

					<div class="col-md-3 popupbox">
						<h6 class="kt-font-bolder kt-font-brand">DPU</h6>
						<span class="form-text text-muted">Defect Per Unit</span>
						<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
						<label>{{ $final_data['process_stats']['dpu'] }}</label>
					</div>
					<div class="col-md-3 popupbox">
						<h6 class="kt-font-bolder kt-font-brand">DPO</h6>
						<span class="form-text text-muted">Defect Per Opportunity</span>
						<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
						<label>{{ $final_data['process_stats']['dpo'] }}</label>
					</div>
					<div class="col-md-3 popupbox">
						<h6 class="kt-font-bolder kt-font-brand">DPMO</h6>
						<span class="form-text text-muted">Defect Per Million Opportunities</span>
						<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
						<label>{{ $final_data['process_stats']['dpmo'] }}</label>
					</div>
					<div class="col-md-3 popupbox">
						<h6 class="kt-font-bolder kt-font-brand">PPM</h6>
						<span class="form-text text-muted">Parts Per Million</span>
						<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
						<label>{{ $final_data['process_stats']['ppm'] }}</label>
					</div>


					<!-- <div class="tooltipbox">

						dummy test 

					</div> -->

				</div>
			</div>
		</div>


			</div>
		</div>


		<div class="row">
			<div class="col-lg-12">
				<div class="kt-portlet kt-portlet--mobile">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">
								Parameter Wise Compliance
							</h3>
						</div>
					</div>
					<div class="kt-portlet__body">
						<?php echo $request->parameter_wise_compliance; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="kt-portlet kt-portlet--mobile">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">
								Sub Parameter Wise Compliance
							</h3>
						</div>
					</div>
					<div class="kt-portlet__body">
						<div class="row">
							<?php echo $request->sub_param_chart; ?>				

						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-6" id="contain">
				<div class="kt-portlet kt-portlet--mobile">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">
								Agent Performance Quartile
							</h3>
						</div>
					</div>
					<div class="kt-portlet__body">
						<div data-highcharts-chart="4" style="overflow: hidden;">
						 <?php echo $request->quartileChart; ?>
					</div>
					</div>				
				</div>
			</div>
			<div class="col-lg-6">
				<div class="kt-portlet kt-portlet--mobile">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">
								Call Type (QRC)
							</h3>
						</div>
					</div>
					<div class="kt-portlet__body">
						<?php  echo $request->qrc_chart_html; ?>
					</div>
				</div>
			</div>
		</div>
	<div class="kt-portlet kt-portlet--mobile">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">
								Disposition Wise Compliance
							</h3>
						</div>
					</div>
					<div class="kt-portlet__body">
						<?php echo $request->disposition_chart; ?>
					</div>
				</div>

				<div class="kt-portlet kt-portlet--mobile">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">
								Pareto Data
							</h3>
						</div>
					</div>
					<div class="kt-portlet__body">
						<?php echo $request->pareto_chart; ?>
					</div>
				</div>
				
			</div>
					
	</div>

	</body>

<!-- end::Body -->
</html>