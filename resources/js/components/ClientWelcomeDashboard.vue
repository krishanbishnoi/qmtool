<template>
	<div>
		
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Search / Filter
					</h3>
				</div>
			</div>


			<div class="kt-portlet__body">
				<form @submit="submitAuditCycle">

				<div class="row">
					<div class="col-md-4">
					   <div >						
						<select class="form-control" id="audit_cycle" v-model="selected_elements[0].audit_cycle" required="required">
							<option value="0" selected>Select a Audit Cycle</option>
							<option v-for="(v,k) in audit_cycle" :value="k+' '+v">{{k}} {{v}}</option>
						</select>
					   </div>
					   						
					</div>


					<div class="col-md-2">
						<button type="submit" class="btn btn-outline-brand"><i class="fa fa-search"></i> Search</button>
					</div>
					
				</div>

			</form>

			<!-- <form @submit="get_lob_wise_data">

				<div class="row">
					
					<div class="col-md-2">
						<button type="submit" class="btn btn-outline-brand"><i class="fa fa-search"></i> Search</button>
					</div>
					
				</div>

			</form> -->

			
			</div>
		</div>

		
		
							<!--begin:: Widgets/Stats-->
							<div class="kt-portlet">
								<div class="kt-portlet__body  kt-portlet__body--fit">
									<div class="row row-no-padding row-col-separator-xl">
										<div class="col-md-12 col-lg-6 col-xl-6">

											<!--begin::Total Profit-->
											<div class="kt-widget24">
												<div class="kt-widget24__details">
													<div class="kt-widget24__info">
														<h4 class="kt-widget24__title">
															All LOB Coverage
														</h4>
														<span class="kt-widget24__desc">
															Target
														</span>
													</div>
													<span class="kt-widget24__stats kt-font-brand">
														{{coverage.target}}
													</span>
												</div>
												<div class="progress progress--sm">
													<div class="progress-bar kt-bg-brand" role="progressbar" :style="'width: '+coverage.achived_per+'%;'" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
												</div>
												<div class="kt-widget24__action">
													<span class="kt-widget24__change">
														Achieved
													</span>
													<span class="kt-widget24__number">
														{{coverage.achived}} ({{coverage.achived_per}} %)
													</span>
												</div>
											</div>
											<!--end::Total Profit-->
										</div>
										<div class="col-md-12 col-lg-6 col-xl-6">

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
														{{rebuttal.raised}} - {{rebuttal.rebuttal_per}}%
													</span>
												</div>
												<div class="progress progress--sm">
													<div class="progress-bar kt-bg-warning" role="progressbar" :style="'width: '+rebuttal.accepted_per+'%'" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
												</div>
												<div class="kt-widget24__action">
													<span class="kt-widget24__change">
														Accepted || Rejected
													</span>
													<span class="kt-widget24__number">
														{{rebuttal.accepted}} - {{rebuttal.accepted_per}}% || {{rebuttal.rejected}} - {{rebuttal.accepted_per}}%
													</span>
												</div>
											</div>
										</div>
										<!--<div class="col-md-12 col-lg-6 col-xl-4">

											//begin::New Orders
											<div class="kt-widget24">
												<div class="kt-widget24__details">
													<div class="kt-widget24__info">
														<h4 class="kt-widget24__title">
															QC
														</h4>
														<span class="kt-widget24__desc">
															Total
														</span>
													</div>
													<span class="kt-widget24__stats kt-font-danger">
														0
													</span>
												</div>
												<div class="progress progress--sm">
													<div class="progress-bar kt-bg-danger" role="progressbar" style="width: 0%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
												</div>
												<div class="kt-widget24__action">
													<span class="kt-widget24__change">
														Fail
													</span>
													<span class="kt-widget24__number">
														0 (0%)
													</span>
												</div>
											</div>

											//end::New Orders
										</div> -->
									
									</div>
								</div>
							</div>

							<!--end:: Widgets/Stats-->
		<div class="row">
			<div class="col-xl-6">

									<!--begin:: Widgets/Personal Income-->
									<div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--height-fluid">
										<div class="kt-portlet__head kt-portlet__space-x">
											<div class="kt-portlet__head-label">
												<h3 class="kt-portlet__head-title kt-font-light">
													LOB Wise Score
												</h3>
											</div>
											 
										</div>
										<div class="kt-portlet__body">
											<div class="kt-widget27">
												<div class="kt-widget27__visual">
													<img src="/assets/media//bg/bg-4.jpg" alt="" style="height: 80px;">
												</div>
												<div class="kt-widget27__container kt-portlet__space-x" style="margin:0;">
													<ul class="nav nav-pills nav-fill" role="tablist">

														<li class="nav-item" v-for="(item,key) in pws">
															<a :class="{'nav-link':true,'active':item.class}" data-toggle="pill" :href="'#kt_personal_income_quater_'+key">{{item.name}}</a>
														</li>
														
													</ul>
													<div class="tab-content">
															
														<div v-for="(item,key) in pws" :id="'kt_personal_income_quater_'+key" :class="{'tab-pane':true,'active':item.class}">
															<div class="kt-widget11">
																<div class="table-responsive">

																	<!--begin::Table-->
																	<table class="table">

																		<!--begin::Thead-->
																		<thead>
																			<tr>
																				<td>Head</td>
																				<td class="kt-align-right">Value</td>
																			</tr>
																		</thead>

																		<!--end::Thead-->

																		<!--begin::Tbody-->
																		<tbody>
																			<tr>
																				<td>
																					<a href="#" class="kt-widget11__title">Audit Count</a>
																				</td>
																				<td class="kt-align-right kt-font-brand kt-font-bold">{{item.data.audit_count}}</td>
																			</tr>
																			<tr>
																				<td>
																					<a href="#" class="kt-widget11__title">Score</a>
																				</td>
																				<td class="kt-align-right kt-font-brand kt-font-bold">{{item.data.score}}%</td>
																			</tr>
																			<tr>
																				<td>
																					<a href="#" class="kt-widget11__title kt-font-danger">Score(With Fatal)</a>
																				</td>
																				<td class="kt-align-right kt-font-brand kt-font-bold kt-font-danger">{{item.data.scored_with_fatal}}%</td>
																			</tr>
																		</tbody>

																		<!--end::Tbody-->
																	</table>

																	<!--end::Table-->
																</div>
															</div>
														</div>
													
														
													
													</div>
												</div>
											</div>
										</div>
									</div>

									<!--end:: Widgets/Personal Income-->
								</div>



								<div class="col-xl-6">
									<!--begin:: Widgets/Personal Income-->
									<div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--height-fluid">
										<div class="kt-portlet__head kt-portlet__space-x">
											<div class="kt-portlet__head-label">
												<h3 class="kt-portlet__head-title kt-font-light">
													QRC Score
												</h3>
											</div>
											
										</div>
										<div class="kt-portlet__body">
											<div class="kt-widget27">
												<div class="kt-widget27__visual">
													<img src="/assets/media//misc/bg-2.jpg" alt="" style="height: 80px;">
												</div>
												<div class="kt-widget27__container kt-portlet__space-x" style="margin:0;">
													<ul class="nav nav-pills nav-fill" role="tablist">
														<li class="nav-item">
															<a class="nav-link active" data-toggle="pill" href="#kt_personal_income_quater_qrc_q">Query</a>
														</li>
														<li class="nav-item">
															<a class="nav-link" data-toggle="pill" href="#kt_personal_income_quater_qrc_r">Request</a>
														</li>
														<li class="nav-item">
															<a class="nav-link" data-toggle="pill" href="#kt_personal_income_quater_qrc_c">Complaint</a>
														</li>
													</ul>
													<div class="tab-content">
														<div id="kt_personal_income_quater_qrc_q" class="tab-pane active">
															<div class="kt-widget11">
																<div class="table-responsive">

																	<!--begin::Table-->
																	<table class="table">

																		<!--begin::Thead-->
																		<thead>
																			<tr>
																				<td>Head</td>
																				<td class="kt-align-right">Count</td>
																			</tr>
																		</thead>

																		<!--end::Thead-->

																		<!--begin::Tbody-->
																		<tbody>
																			<tr>
																				<td>
																					<a href="#" class="kt-widget11__title">Audit Count</a>
																				</td>
																				<td class="kt-align-right kt-font-brand kt-font-bold">{{qrc.query_count}}</td>
																			</tr>
																			<tr>
																				<td>
																					<a href="#" class="kt-widget11__title kt-font-danger">FATAL Count</a>
																				</td>
																				<td class="kt-align-right kt-font-brand kt-font-bold kt-font-danger">{{qrc.query_fatal_count}}</td>
																			</tr>
																		</tbody>

																		<!--end::Tbody-->
																	</table>

																	<!--end::Table-->
																</div>
															</div>
														</div>
														<div id="kt_personal_income_quater_qrc_r" class="tab-pane fade">
															<div class="kt-widget11">
																<div class="table-responsive">

																	<!--begin::Table-->
																	<table class="table">

																		<!--begin::Thead-->
																		<thead>
																			<tr>
																				<td>Application</td>
																				<td class="kt-align-right">Total</td>
																			</tr>
																		</thead>

																		<!--end::Thead-->

																		<!--begin::Tbody-->
																		<tbody>
																			<tr>
																				<td>
																					<a href="#" class="kt-widget11__title">Audit Count</a>
																				</td>
																				<td class="kt-align-right kt-font-brand kt-font-bold">{{qrc.request_count}}</td>
																			</tr>
																			<tr>
																				<td>
																					<a href="#" class="kt-widget11__title">FATAL Count</a>
																				</td>
																				<td class="kt-align-right kt-font-brand kt-font-bold">{{qrc.request_fatal_count}}</td>
																			</tr>
																		</tbody>

																		<!--end::Tbody-->
																	</table>

																	<!--end::Table-->
																</div>
															</div>
														</div>
														<div id="kt_personal_income_quater_qrc_c" class="tab-pane fade">
															<div class="kt-widget11">
																<div class="table-responsive">

																	<!--begin::Table-->
																	<table class="table">

																		<!--begin::Thead-->
																		<thead>
																			<tr>
																				<td>Head</td>
																				<td class="kt-align-right">Count</td>
																			</tr>
																		</thead>

																		<!--end::Thead-->

																		<!--begin::Tbody-->
																		<tbody>
																			<tr>
																				<td>
																					<a href="#" class="kt-widget11__title">Audit Count</a>
																				</td>
																				<td class="kt-align-right kt-font-brand kt-font-bold">{{qrc.complaint_count}}</td>
																			</tr>
																			<tr>
																				<td>
																					<a href="#" class="kt-widget11__title">FATAL Count</a>
																				</td>
																				<td class="kt-align-right kt-font-brand kt-font-bold">{{qrc.complaint_fatal_count}}</td>
																			</tr>
																		</tbody>

																		<!--end::Tbody-->
																	</table>

																	<!--end::Table-->
																</div>
															</div>
														</div>
													
													</div>
												</div>
											</div>
										</div>
									</div>

									<!--end:: Widgets/Personal Income-->
								</div>
		</div>

		<div class="row">
			<div class="col-xl-12">
				<div class="kt-portlet kt-portlet--mobile">
								<div class="kt-portlet__head kt-portlet__head--lg">
									<div class="kt-portlet__head-label">
										<span class="kt-portlet__head-icon">
											<i class="kt-font-brand flaticon2-line-chart"></i>
										</span>
										<h3 class="kt-portlet__head-title">
											Partner & Location Wise Report
										</h3>
									</div>
									<div class="kt-portlet__head-toolbar">
										<div class="kt-portlet__head-wrapper">
											<div class="kt-portlet__head-actions">
											</div>
										</div>
									</div>
								</div>
								<div class="kt-portlet__body">

									<!--begin: Datatable -->
									<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
										<thead>
											<tr>
												<th>#</th>
												<th>Partner</th>
												<th>Location</th>
												<th>Audit Count</th>
												<th class="kt-font-danger">With Fatal</th>
												<th>Without Fatal</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="(item,key) in plr" :id="'kt_personal_income_quater_'+key">
												<td>{{item.count}}</td>
												<td>{{item.partner}}</td>
												<td>{{item.location}}</td>
												<td>{{item.audits_count}}</td>
												<td class="kt-font-danger">{{item.with_fatal}} %</td>
												<td>{{item.without_fatal}} %</td>
												
												
											</tr>
										</tbody>
									</table>

									<!--end: Datatable -->
								</div>
							</div>

			</div>
		</div>

	</div>
</template>
<script>

	export default {
		
		data() {
	    	return {
	    		rebuttal:{raised:0,accepted:0,accepted_per:0,rebuttal_per:0},
	    		qrc:[],
	    		pws:[],
				plr:[],
				selected_elements: [{audit_cycle:''}
									],
				audit_cycle:[],
	    		coverage:{taregt:0,achived:0,achived_per:0},
	    		chartOptionsCoverage:{
	    			chart: {
        type: 'column'
    },
    title: {
        text: 'Coverage'
    },
    subtitle: {
        text: 'Process Wise (last 4 weeks)'
    },
    xAxis: {
        categories: [
            'Inbound - Call',
            'Outbound - Call',
            'Email',
            'Chat'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Scored'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'With FATAL',
        data: [49.9, 71.5, 106.4],
        color: '#fd3995',


    }, {
        name: 'Without FATAL',
        data: [83.6, 78.8, 98.5],
        color: '#34bfa3',

    }, {
        name: 'Overall Score %',
        data: [48.9, 38.8, 39.3],
        color: '#5867dd',

    }]
	    		},
	    		chartOptionsQc:{
	    			chart: {
        type: 'column'
    },
    title: {
        text: 'QC'
    },
    subtitle: {
        text: 'Process Wise (last 4 weeks)'
    },
    xAxis: {
        categories: [
            'Inbound - Call',
            'Outbound - Call',
            'Email',
            'Chat'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Scored'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'With FATAL',
        data: [49.9, 71.5, 106.4],
        color: '#DB257D',


    }, {
        name: 'Without FATAL',
        data: [83.6, 78.8, 98.5],
        color: '#02BF74',

    }, {
        name: 'Overall Score %',
        data: [48.9, 38.8, 39.3],
        color: '#785DF0',

    }]
	    		},
	    		chartOptionsRebuttle:{

	    			chart: {
        type: 'column'
    },
    title: {
        text: 'Rebuttle'
    },
    subtitle: {
        text: 'Process Wise (last 4 weeks)'
    },
    xAxis: {
        categories: [
            'Inbound - Call',
            'Outbound - Call',
            'Email',
            'Chat'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Scored'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Raised',
        data: [49.9, 71.5, 106.4],
        color: '#DB257D',


    }, {
        name: 'Accepted',
        data: [83.6, 78.8, 98.5],
        color: '#02BF74',

    }, {
        name: 'Rejected',
        data: [48.9, 38.8, 39.3],
        color: '#785DF0',

    }]
	    		
	    		},
	    		chartOptionsAudirScore:{


	    			chart: {
        type: 'column'
    },
    title: {
        text: 'Score'
    },
    subtitle: {
        text: 'Process Wise (last 4 weeks)'
    },
    xAxis: {
        categories: [
            'Inbound - Call',
            'Outbound - Call',
            'Email',
            'Chat'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Scored'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Raised',
        data: [49.9, 71.5, 106.4],
        color: '#DB257D',


    }, {
        name: 'Accepted',
        data: [83.6, 78.8, 98.5],
        color: '#02BF74',

    }, {
        name: 'Rejected',
        data: [48.9, 38.8, 39.3],
        color: '#785DF0',

    }]
	    		},
    		}
		},
		mounted() {
			var self = this;
	        axios.get('/dashboard/get_client_welcome_data').then(function (response){
	        	var tempt_response = response.data.data;
	        	self.rebuttal = tempt_response.rebuttal;
	        	self.qrc = tempt_response.qrc;
	        	self.pws = tempt_response.pws;
	        	self.plr = tempt_response.plr;
	        	self.coverage = tempt_response.coverage;
				self.audit_cycle = tempt_response.audit_cycle;

				console.log(self.pws);
				
	        }).catch(function (error) {
	            console.log(error);
	        });

		},
		methods: {
			submitAuditCycle: function(e){
	    		e.preventDefault();
	    		KTApp.blockPage({
                overlayColor: '#000000',
                type: 'v2',
                state: 'primary',
                message: 'Processing...'
            	});

	    		//this.cycle_date = document.getElementById("audit_cycle").value;
	    		// console.log(this.selected_elements);
	    		var self = this;
	    		var params = {
			      selected_elements:this.selected_elements,
				  };
				  
				//  console.log(JSON.stringify(params));
			      
			     axios.post('/dashboard/get_client_welcome_data_cycle',params).then(function (response){
			     	var tempt_response = response.data.data;
					self.rebuttal = tempt_response.rebuttal;
					self.qrc = tempt_response.qrc;
					self.pws = tempt_response.pws;
					self.plr = tempt_response.plr;
					self.coverage = tempt_response.coverage;
					self.audit_cycle = tempt_response.audit_cycle;

					  
					KTApp.unblockPage();
					
			      }).catch(function (error) {
			          console.log(error.response);
			      });
	    	},
			get_client_data: function() {
				console.log("call function");
			},
			get_lob_wise_data: function(e){
	    		e.preventDefault();
	    		KTApp.blockPage({
                overlayColor: '#000000',
                type: 'v2',
                state: 'primary',
                message: 'Processing...'
            	});

	    		var self = this;
	    		var params = {
			     // selected_elements:this.selected_elements,
					process_id: 5,
				};
				  
				//  console.log(JSON.stringify(params));
			      
			    axios.post('/dashboard/get_client_welcome_data_lob_wise',params).then(function (response){
			     	var tempt_response = response.data.data;
					self.rebuttal = tempt_response.rebuttal;
					self.qrc = tempt_response.qrc;
					self.pws = tempt_response.pws;
					self.plr = tempt_response.plr;
					self.coverage = tempt_response.coverage;
					self.audit_cycle = tempt_response.audit_cycle;
					KTApp.unblockPage();
					
			    }).catch(function (error) {
			          console.log(error.response);
			    });
	    	},
		},

	}
</script>


<style>
	
</style>