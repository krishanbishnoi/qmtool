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
				<form @submit="submitFilter">

				<div class="row">
					<!-- <div class="col-md-2">
						<select class="form-control" @change="onPartnerChange" v-model="selected_elements[0].partner_id" required="required">
							<option value="0">Select a Partner</option>
							<option v-for="(v,k) in all_partners_list" :value="k">{{v}}</option>
						</select>
					</div>
					<div class="col-md-2">
						<select class="form-control" v-model="selected_elements[0].location_id" required="required">
							<option value="0">Select a Location</option>
							<option v-for="(v,k) in all_partner_location_list" :value="k">{{v}}</option>
						</select>
					</div>
					-->
					<!-- <div class="col-md-2">
						<select class="form-control" v-model="selected_elements[0].process_id" required="required">
							<option value="0">Select a Process</option>
							<option v-for="(v,k) in all_partner_process_list" :value="k">{{v}}</option>
						</select>
					</div>  -->
					<div class="col-md-2">
						<input type='text' class="form-control" id="kt_daterangepicker_1" required="required"/>
					</div>
					<div class="col-md-2">
						<button type="submit" class="btn btn-outline-brand"><i class="fa fa-search"></i> Search</button>
					</div>
					
				</div>

			</form>
			</div>
		</div>


		<!--begin:: Widgets/Stats-->
							<div class="kt-portlet">
								<div class="kt-portlet__body  kt-portlet__body--fit">
									<div class="row row-no-padding row-col-separator-xl">
										<div class="col-md-12 col-lg-6 col-xl-3">

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
														{{coverage.target}}
													</span>
												</div>
												<div class="progress progress--sm">
													<div class="progress-bar kt-bg-brand" role="progressbar" :style="'width: '+coverage.achived_per+'%;'" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
												</div>
												<div class="kt-widget24__action">
													<span class="kt-widget24__change">
														Achived
													</span>
													<span class="kt-widget24__number">
														{{coverage.achived}} ({{coverage.achived_per}} %)
													</span>
												</div>
											</div>
											<!--end::Total Profit-->
											
										</div>
										<div class="col-md-12 col-lg-6 col-xl-3">

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
														{{rebuttal.raised}} - {{rebuttal.rebuttal_per}}%
													</span>
												</div>
												<div class="progress progress--sm">
													<div class="progress-bar kt-bg-warning" role="progressbar" :style="'width: '+rebuttal.accepted_per+'%'" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
												</div>
												<div class="kt-widget24__action">
													<span class="kt-widget24__change">
														Accepted
													</span>
													<span class="kt-widget24__number">
														{{rebuttal.accepted}} - {{rebuttal.accepted_per}}%
													</span>
												</div>
												<div class="kt-widget24__action">
													<span class="kt-widget24__change">
														WIP
													</span>
													<span class="kt-widget24__number">
														{{rebuttal.wip}}
													</span>
												</div>
											</div>
											<!--end::New Feedbacks-->
										</div>

										<div class="col-md-12 col-lg-6 col-xl-3" v-if="showQcBlock">
											<!--begin::New Orders-->
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
													<span class="kt-widget24__stats kt-font-success">
														0
													</span>
												</div>
												<div class="progress progress--sm">
													<div class="progress-bar kt-bg-success" role="progressbar" style="width: 0%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
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

											<!--end::New Orders-->
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
														{{fatal_first_row_block.total_fatal_count_sub_parameter}}
													</span>
												</div>
												<div class="progress progress--sm">
													<div class="progress-bar kt-bg-danger" role="progressbar" :style="'width: '+fatal_first_row_block.total_fatal_audit_per+'%;'" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
												</div>
												<div class="kt-widget24__action">
													<span class="kt-widget24__change">
														Fatal Call
													</span>
													<span class="kt-widget24__number">
														{{fatal_first_row_block.total_fatal_audits}} ({{fatal_first_row_block.total_fatal_audit_per}}%)
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
								<highcharts :options="chartOptionsWithFatal" ref="process_score_with_fatal"></highcharts>
							</div>
							<div class="col-md-6">
								<h5 align="center">Without Fatal</h5>
								<highcharts :options="chartOptionsWithoutFatal" ref="process_score_without_fatal"></highcharts>
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
				<tr v-for="(item,kk) in pwfcs">
					<td>{{item.counter}}</td>
					<td>{{item.parameter}}</td>
					<td align="center">{{item.fatal_count}}</td>
					<td align="center">{{item.fail_count}}</td>
					<td align="center">{{(100 - item.fatal_score)}} %</td>
				</tr>
				

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

					<div class="col-md-3">
						<h6 class="kt-font-bolder kt-font-brand">DPU</h6>
						<span class="form-text text-muted">Defect Per Unit</span>
						<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
						<label>{{process_stats.dpu}}</label>
					</div>
					<div class="col-md-3">
						<h6 class="kt-font-bolder kt-font-brand">DPO</h6>
						<span class="form-text text-muted">Defect Per Opportunity</span>
						<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
						<label>{{process_stats.dpo}}</label>
					</div>
					<div class="col-md-3">
						<h6 class="kt-font-bolder kt-font-brand">DPMO</h6>
						<span class="form-text text-muted">Defect Per Million Opportunities</span>
						<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
						<label>{{process_stats.dpmo}}</label>
					</div>
					<div class="col-md-3">
						<h6 class="kt-font-bolder kt-font-brand">PPM</h6>
						<span class="form-text text-muted">Parts Per Million</span>
						<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
						<label>{{process_stats.ppm}}</label>
					</div>
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
						<highcharts :options="chartOptionsCoverage" ref="pws_line_chart"></highcharts>
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

							<div class="col-md-4" v-for="(item,kk) in spws">
								<horiz-bar-chart :param-name="item.parameter" 
											     :sps-list="item.temp_sps_list"
											     :sps-detail="item.temp_sps_detail"
											     :sps-score="item.sp_p_score"></horiz-bar-chart>
							</div>

							<div class="col-md-4">
							<highcharts :options="chartOptionsNonScoreSubParamater" ref="non_scoring_param_chart"></highcharts>
							 </div>

						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-6">
				
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
						<highcharts :options="chartOptionsQrc" ref="qrc_chart"></highcharts>
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
						<highcharts :options="chartOptionsDisposition" ref="dispostion_chart"></highcharts>
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
						<highcharts :options="chartOptionsParato" ref="pareto_chart"></highcharts>
					</div>
				</div>


		
	</div>
</template>
<script>
	Vue.component('horiz-bar-chart', require('./horizontalBarComponent').default);
	export default {

		props:['clientId','showQcBlock'],
		data() {
	    	return {
	    		selected_elements:[{partner_id:0,
	    							location_id:0,
	    							process_id:0,
	    							dates:0}],

	    		chartOptionsCoverage:{
	    			chart: {
        type: 'line'
    },

    title: {
        text: ''
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: []
    },
    yAxis: {
    	min:0,
    	max:100,
        title: {
            text: '%'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}%'
            },
            enableMouseTracking: false
        }
    },
    series: [{}]
	    			
	    		},

    		chartOptionsQuartile:{
    			chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: ''
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f} %</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f}'
            }
        }
    },
    series: [{
        name: 'Agent %',
        colorByPoint: true,
        data: [{
            name: '0 - 40 %',
            y: 0,
            sliced: true,
            selected: true,
            color:"#F24405"
        }, {
            name: '41 - 60 %',
            y: 0,
            color:'#D7F205'
        }, {
            name: '61 - 80 %',
            y: 0,
            color:'#7D07F2'
        }, {
            name: 'Greater then 80 %',
            y: 0,
            color:"#20D91A"
        }]
    }]
    		},
    		chartOptionsQrc:{
    title: {
        text: ''
    },
    xAxis: {
        categories: ['Query', 'Request', 'Complaint']
    },
    plotOptions: {
            spline: { // has to say spline here
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.1f} %'
                },
                enableMouseTracking: false
            }
        }, 
    labels: {
        items: [{
            html: '',
            style: {
                left: '50px',
                top: '18px',
                // color: ( // theme
                //     Highcharts.defaultOptions.title.style &&
                //     Highcharts.defaultOptions.title.style.color
                // ) || 'black'
            }
        }]
    },
    series: [{
        type: 'column',
        name: 'Audit count',
        data: []
    }, {
        type: 'column',
        name: 'Fatal count',
        data: []
    }, {
        type: 'spline',
        name: 'Score',
        data: [],
        marker: {
            lineWidth: 2,
            // lineColor: Highcharts.getOptions().colors[3],
            fillColor: 'red',
            format: '{point.y:.1f}%'
        }
    }]
},
    		chartOptionsDisposition:{
    			 chart: {
        zoomType: 'xy'
    },
    title: {
        text: ''
    },
    subtitle: {
        text: ''
    },
    xAxis: [{
        categories: [],
        crosshair: true
    }],
    yAxis: [{ // Primary yAxis
    	min:0,
    	max:100,
        labels: {
            format: '{value} %',
            style: {
                
            }
        },
        title: {
            text: 'Score',
            style: {
                
            }
        }
    }, { // Secondary yAxis
        title: {
            text: 'Count',
            style: {
                
            }
        },
        labels: {
            format: '{value}',
            style: {
                
            }
        },
        opposite: true
    }],
    tooltip: {
        shared: true
    },
    legend: {
        layout: 'horizontal',
        align: 'center',
        x: 0,
        verticalAlign: 'top',
        y: 0,
        floating: true,
        
    },
    series: [{
        name: 'Audit Counts',
        type: 'column',
        yAxis: 1,
        data: [],
        tooltip: {
            valueSuffix: ' Count'
        }

    }, {
        name: 'Score',
        type: 'spline',
        data: [],
        tooltip: {
            valueSuffix: '%'
        }
    }]
    		},
    		chartOptionsParato:{
    			 chart: {
        zoomType: 'xy'
    },
    title: {
        text: ''
    },
    subtitle: {
        text: ''
    },
    xAxis: [{
        categories: [],
        crosshair: true
    }],
    yAxis: [{ // Primary yAxis
    	min:0,
    	max:100,
        labels: {
            format: '{value} %',
            style: {
                
            }
        },
        title: {
            text: '%',
            style: {
                
            }
        }
    }, { // Secondary yAxis
        title: {
            text: 'Count',
            style: {
                
            }
        },
        labels: {
            format: '{value}',
            style: {
                
            }
        },
        opposite: true
    }],
    tooltip: {
        shared: true
    },
    legend: {
        layout: 'horizontal',
        align: 'center',
        x: 0,
        verticalAlign: 'top',
        y: 0,
        floating: true,
        
    },
    series: [{
        name: '%',
        type: 'column',
        yAxis: 1,
        data: [],
        tooltip: {
            valueSuffix: ' Count'
        }

    }, {
        name: 'Count',
        type: 'spline',
        data: [],
        tooltip: {
            valueSuffix: '%'
        }
    }]
    		},
    		chartOptionsWithFatal:{
    			chart: {
        type: 'solidgauge',
        height:280,
    },

    title: null,

    pane: {
        center: ['50%', '70%'],
        size: '100%',
        startAngle: -90,
        endAngle: 90,
        background: {
            innerRadius: '60%',
            outerRadius: '100%',
            shape: 'arc'
        }
    },

    tooltip: {
        enabled: false
    },

    // the value axis
    yAxis: {
        stops: [
            [0.1, '#fd3995'], // red
            [0.5, '#ffb822'], // yellow
            [0.9, '#34bfa3'] // gree
        ],
        lineWidth: 0,
        minorTickInterval: null,
        tickAmount: 2,
        title: {
            y: -0
        },
        labels: {
            y: 0
        }
    },

    plotOptions: {
        solidgauge: {
            dataLabels: {
                y: 6,
                borderWidth: 0,
                useHTML: true
            }
        }
    },
    yAxis: {
        min: 0,
        max: 100,
        title: {
            text: ''
        }
    },

    credits: {
        enabled: false
    },

    series: [{
        name: 'Speed',
        data: [0],
        dataLabels: {
            format:
                '<div style="text-align:center">' +
                '<span style="font-size:25px">{y}%</span><br/>' +
                '<span style="font-size:12px;opacity:0.4">Score</span>' +
                '</div>'
        },
        tooltip: {
            valueSuffix: ' Count'
        }
    }]
    		},  
	    		chartOptionsNonScoreSubParamater:{
    			chart: {
    				height: 250,
        type: 'bar'
    },
    title: {
        text: ''
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: '',
        title: {
            text: null
        }
    },
    yAxis: {
        min: 0,
        max: 100,
        title: {
            text: 'Count',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        }
    },
    tooltip: {
        valueSuffix: ' %'
    },
    plotOptions: {
        bar: {
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}%'

            }
        }
    },
    legend: {
        enabled:false,
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -40,
        y: 80,
        floating: true,
        borderWidth: 1,
        
        shadow: true
    },
    credits: {
        enabled: false
    },
    series: [{
        name: 'Count',
        data: []
    }]
    		},
    		chartOptionsWithoutFatal:{
    			chart: {
        type: 'solidgauge',
        height:280,
    },

    title: null,

    pane: {
        center: ['50%', '70%'],
        size: '100%',
        startAngle: -90,
        endAngle: 90,
        background: {
            innerRadius: '60%',
            outerRadius: '100%',
            shape: 'arc'
        }
    },

    tooltip: {
        enabled: false
    },

    // the value axis
    yAxis: {
        stops: [
            [0.1, '#fd3995'], // red
            [0.5, '#ffb822'], // yellow
            [0.9, '#34bfa3'] // gree
        ],
        lineWidth: 0,
        minorTickInterval: null,
        tickAmount: 2,
        title: {
            y: -0
        },
        labels: {
            y: 0
        }
    },

    plotOptions: {
        solidgauge: {
            dataLabels: {
                y: 6,
                borderWidth: 0,
                useHTML: true
            }
        }
    },
    yAxis: {
        min: 0,
        max: 100,
        title: {
            text: ''
        }
    },

    credits: {
        enabled: false
    },

    series: [{
        name: 'Speed',
        data: [0],
        dataLabels: {
            format:
                '<div style="text-align:center">' +
                '<span style="font-size:25px">{y}%</span><br/>' +
                '<span style="font-size:12px;opacity:0.4">Score</span>' +
                '</div>'
        },
        tooltip: {
            valueSuffix: ' Score'
        }
    }]
    		},
    		
    		all_partners_list:[],
    		all_partner_location_list:[],
    		all_partner_process_list:[],
    		rebuttal:{raised:0,accepted:0,accepted_per:0,rebuttal_per:0,rejected:0,wip:0},
	    	fatal_dialer_data:{with_fatal_score:0,without_fatal_score:0},
	    	fatal_first_row_block:{total_fatal_count_sub_parameter:0,total_audits:0,total_fatal_audits:0,total_fatal_audit_per:0},
	    	pwfcs:[],
	    	parameter_list:[],
	    	pws:[],
	    	disposition:[],
	    	spws:[],
	    	reason_type:[],
	    	coverage:{taregt:0,achived:0,achived_per:0},
	    	qrc:[],
	    	quartile:[],
	    	non_scoring_param_data:[{names:'',list:[],score:[]}],
	    	process_stats:[],
	    	pareto_data:[],
	    	}
	    },
	    mounted()
	    {
	    	console.log(this.showQcBlock);
	    	// get all partners
	    	var self = this;
	        /* axios.get('/dashboard/get_loged_in_client_partners').then(function (response){
	          	self.all_partners_list = response.data.data;
	        }).catch(function (error) {
	            console.log(error);
	        }); */
			/* axios.get('/dashboard/get_qtl_process/').then(function (response){
		          	self.all_partner_process_list = response.data.data;
		        }).catch(function (error) {
		            console.log(error);
		        }); */

	    },
	    methods:{
	    	onPartnerChange: function(event){
	    		// get all partner process
		    	var self = this;
		        axios.get('/dashboard/get_partner_process/'+event.target.value).then(function (response){
		          	self.all_partner_process_list = response.data.data;
		        }).catch(function (error) {
		            console.log(error);
		        });
		        axios.get('/dashboard/get_partner_locations/'+event.target.value).then(function (response){
		          	self.all_partner_location_list = response.data.data;
		        }).catch(function (error) {
		            console.log(error);
		        });
	    	},
	    	submitFilter: function(e){
	    		e.preventDefault();
	    		KTApp.blockPage({
                overlayColor: '#000000',
                type: 'v2',
                state: 'primary',
                message: 'Processing...'
            	});

	    		this.selected_elements[0].dates  = document.getElementById("kt_daterangepicker_1").value;
	    		// console.log(this.selected_elements);
	    		var self=this;
	    		var params = {
			      selected_elements:this.selected_elements,
			      };
			      // console.log(params);
			     axios.post('/dashboard/get_qa_dashboard_data',params).then(function (response){
			     	
					KTApp.unblockPage();
					 //console.log(response.data.data);
			     	var tempt_response = response.data.data;
	        		self.rebuttal = tempt_response.rebuttal;
	        		self.fatal_dialer_data = tempt_response.fatal_dialer_data;
	        		self.fatal_first_row_block = tempt_response.fatal_first_row_block;
	        		self.pwfcs = tempt_response.pwfcs;
	        		self.parameter_list = tempt_response.parameter_list;
	        		self.pws = tempt_response.pws;
	        		self.disposition = tempt_response.disposition;
	        		self.spws = tempt_response.spws;
	        		self.reason_type = tempt_response.reason_type;
	        		self.coverage = tempt_response.coverage;
	        		self.qrc = tempt_response.qrc;
	        		self.quartile = tempt_response.quartile,
	        		self.non_scoring_param_data = tempt_response.non_scoring_params;
	        		self.process_stats=tempt_response.process_stats;
	        		self.pareto_data=tempt_response.pareto_data;
	        		
					
	        		self.update_all_blocks();
                	

			      }).catch(function (error) {
			          console.log(error);
			      });
	    	},
	    	update_all_blocks:function(){
	    		this.$refs.process_score_with_fatal.chart.series[0].data[0].update(this.fatal_dialer_data.with_fatal_score);
	    		this.$refs.process_score_without_fatal.chart.series[0].data[0].update(this.fatal_dialer_data.without_fatal_score);
	    		
	    		this.$refs.pws_line_chart.chart.xAxis[0].update({categories: this.parameter_list});
	    		this.$refs.pws_line_chart.chart.series[0].update({name: 'Compliance %',data: this.pws});

	    		this.$refs.dispostion_chart.chart.xAxis[0].update({categories: this.disposition.all_unique_despos})
	    		this.$refs.dispostion_chart.chart.series[0].update({name: 'Audit Counts',type: 'column',yAxis: 1,data:this.disposition.all_unique_despos_counts,tooltip: { valueSuffix: ' Count' }});
	    		this.$refs.dispostion_chart.chart.series[1].update({name: 'Score',type: 'spline',tooltip: { valueSuffix: '%' },data:this.disposition.all_unique_despos_score});
	    		
	    		this.$refs.pareto_chart.chart.xAxis[0].update({categories: this.pareto_data.reasons});
	    		this.$refs.pareto_chart.chart.series[0].update({name: 'Reason Counts',type: 'column',yAxis: 1,data:this.pareto_data.count,tooltip: { valueSuffix: ' Count' }});
	    		this.$refs.pareto_chart.chart.series[1].update({name: 'Percentage',type: 'spline',tooltip: { valueSuffix: '%' },data:this.pareto_data.per});
	    		
	    		this.$refs.qrc_chart.chart.series[0].update({data:this.qrc.audit_count});
	    		this.$refs.qrc_chart.chart.series[1].update({data:this.qrc.fatal_count});
	    		this.$refs.qrc_chart.chart.series[2].update({data:this.qrc.score});

	    		this.$refs.quartile_chart.chart.series[0].data[0].update({y:this.quartile[0]});
	    		this.$refs.quartile_chart.chart.series[0].data[1].update({y:this.quartile[1]});
	    		this.$refs.quartile_chart.chart.series[0].data[2].update({y:this.quartile[2]});
	    		this.$refs.quartile_chart.chart.series[0].data[3].update({y:this.quartile[3]});

	    		this.$refs.non_scoring_param_chart.chart.setTitle({text:this.non_scoring_param_data.names});
	    		this.$refs.non_scoring_param_chart.chart.xAxis[0].update({categories: this.non_scoring_param_data.list});
	    		this.$refs.non_scoring_param_chart.chart.series[0].update({name: 'Score',data: this.non_scoring_param_data.score});
				
	    	},

	    },

		}
</script>
<style>
	
</style>