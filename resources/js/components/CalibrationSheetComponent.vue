<template>
	<div>
									<!--begin::Portlet-->
									<div class="kt-portlet kt-portlet--mobile">
										<div class="kt-portlet__head" style="background-image: linear-gradient(to right, #845ec2, #906dc6, #9c7dc9, #a88ccd, #b39cd0);">
											<div class="kt-portlet__head-label">
												<h3 class="kt-portlet__head-title kt-font-light">
													Basic Calibration Details
												</h3>
											</div>
										</div>
										<div class="kt-portlet__body">
											<form class="kt-form kt-form--label-right">

												<div class="form-group row">
												<div class="col-lg-4">
													<label>Calibration:</label>
													<input type="text" class="form-control" :value="calibration_data.title">
												</div>
												<div class="col-lg-4">
													<label>Client:</label>
													<input type="text" class="form-control" v-model="sheet_basic_data[0].client" readonly="readonly">
												</div>
												<div class="col-lg-4">
													<label>Process:</label>
													<input type="text" class="form-control" :value="calibration_data.process" readonly="readonly">
												</div>
												</div>

												<div class="form-group row">
												<div class="col-lg-4">
													<label>Due Date:</label>
													<input type="text" class="form-control" :value="calibration_data.due_date">
												</div>
												<div class="col-lg-4">
													<label>Total Calibrator:</label>
													<input type="text" class="form-control" :value="calibration_data.total_calibrator" readonly="readonly">
												</div>
												<div class="col-lg-4">
													<label>Master Calibrator:</label>
													<input type="text" class="form-control" :value="calibration_data.master_calibrator" readonly="readonly">
												</div>
												</div>
												<div class="form-group row">
												<div class="col-lg-4">
													<label>Attachment:</label>
													<a :href="calibration_data.attachment" download class="kt-font-bold">
														Download <i class="fa flaticon-download"></i>
													</a>
												</div>
												</div>


											</form>
										</div>
									</div>
									<!--end::Portlet-->

									<div class="kt-portlet kt-portlet--mobile">
										<div class="kt-portlet__head" style="background-image: linear-gradient(to right, #845ec2, #906dc6, #9c7dc9, #a88ccd, #b39cd0);">
											<div class="kt-portlet__head-label">
												<h3 class="kt-portlet__head-title kt-font-light">
													Parameter Details
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
														<div class="col-md-2 kt-font-bolder">Scored</div>
														<div class="col-md-2 kt-font-bolder">Failure Type</div>
														<div class="col-md-2 kt-font-bolder">Failure Reason</div>
														<div class="col-md-2 kt-font-bolder">Remarks</div>	
													</div>
												</div>
											</div>

											<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg" id="seprator"></div>

											<div class="row flex-container" v-for="pm in parameters" style="border-bottom:1px solid #ccc; padding: 20px 0; height: 100%; ">
												<div class="col-md-2 kt-font-bolder kt-font-primary flex-item">{{pm.name}}</div>
												<div class="col-md-10 sp-row">
													<div class="row flex-container" v-for="sp in pm.subs">
														<div class="col-md-2 kt-font-bold">
															{{sp.name}} <i class="la la-info-circle kt-font-warning sp-details-top" :title="sp.details"></i>
														</div>
														<div class="col-md-2">
															<select class="form-control" v-model="sp.selected_option_model" @change="onObservationChange($event)">
																
																<option v-for="subs_opt in sp.options" :value="subs_opt.key">{{subs_opt.value}}</option>
															</select>
														</div>
														<div class="col-md-2">
															<input type="text" class="form-control" readonly v-model="sp.selected_options">
														</div>
														<div class="col-md-2">
															<select class="form-control" @change="onReasonTypeChange($event)" v-model="sp.selected_reason_type">
																
																<option v-for="(rtype) in sp.all_reason_type" :value="rtype.key">{{rtype.value}}</option>
															</select>
														</div>
														<div class="col-md-2">
															<select class="form-control" v-model="sp.selected_reason">
																
																<option v-for="(rtype,key) in sp.all_reasons" :value="key">{{rtype}}</option>
															</select>
														</div>
														<div class="col-md-2">
															<input type="text" class="form-control" v-model="sp.remark">
														</div>
													</div>
												</div>
												

											</div>

											<div class="row" style="margin-top: 15px;">
												<div class="col-lg-6">
													<label>Overall Call Summary:</label>
													<textarea class="form-control" v-model="submission_data[0].overall_summary"></textarea>
												</div>
											</div>
										</div>
									</div>

			<div class="row">
				<div class="col-lg-9">
				<div class="kt-portlet kt-portlet--mobile">
					<div class="kt-portlet__head" style="background-image: linear-gradient(to right, #ffc75f, #ffd361, #fedf65, #fcec6a, #f9f871);">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title kt-font-light">
								Score Result
							</h3>
						</div>
					</div>
					<div class="kt-portlet__body">
						<div class="row" style="border-bottom:1px solid #ccc;">
							<div class="col-lg-4 kt-font-bolder">&nbsp;</div>
							<div class="col-lg-4 kt-font-bolder">Scored</div>
							<div class="col-lg-4 kt-font-bolder">Scores%</div>
						</div>
						<div class="row" style="padding: 15px 0;">
							<div class="col-lg-2 kt-font-bolder">Behaviour</div>
							<div class="col-lg-2 kt-font-bolder">Scorable</div>
							<div class="col-lg-2 kt-font-bolder">With FATAL</div>
							<div class="col-lg-2 kt-font-bolder">Without FATAL</div>
							<div class="col-lg-2 kt-font-bolder">With FATAL</div>
							<div class="col-lg-2 kt-font-bolder">Without FATAL</div>
						</div>
						<div class="row" v-for="pm in parameters" style="border-bottom:1px solid #ccc; padding: 20px 0; height: 100%; " v-if="pm.is_non_scoring==0">
							
							<div class="col-lg-2 kt-font-bold kt-font-primary">{{pm.name}}</div>
							<div class="col-lg-2">{{pm.temp_total_weightage}}</div>
							<div class="col-lg-2 kt-font-danger">{{pm.score_with_fatal}}</div>
							<div class="col-lg-2">{{pm.score_without_fatal}}</div>
							<div class="col-lg-2 kt-font-danger">{{(pm.is_non_scoring)?0.00:((pm.score_with_fatal/pm.temp_total_weightage)*100).toFixed(2)}} %</div>
							<div class="col-lg-2">{{(pm.is_non_scoring)?0.00:((pm.score_without_fatal/pm.temp_total_weightage)*100).toFixed(2)}} %</div>
							
							
						</div>
						<div class="row" style="padding: 20px 0; height: 100%; ">
							<div class="col-lg-2 kt-font-bold kt-font-success">Over All</div>
							<div class="col-lg-2 kt-font-bold" >{{overAllTotalScoreRow[0].total_scorable}}</div>
							<div class="col-lg-2 kt-font-bold kt-font-danger">{{overAllTotalScoreRow[0].with_fatal}}</div>
							<div class="col-lg-2 kt-font-bold">{{overAllTotalScoreRow[0].without_fatal}}</div>
							<div class="col-lg-2 kt-font-bold kt-font-danger">{{overAllTotalScoreRow[0].with_fatal_per}} %</div>
							<div class="col-lg-2 kt-font-bold">{{overAllTotalScoreRow[0].without_fatal_per}} %</div>
						</div>
						
					</div>
				</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-2">
					<button type="button" class="btn btn-success" id="MySubmitButton" @click="submitMe()">Submit</button>&nbsp;&nbsp;&nbsp;
					<button type="button" class="btn btn-danger">Cancel</button>
				</div>
			</div>



		
	</div>
</template>
<script>
	import vSelect from 'vue-select';
 	Vue.component('v-select', vSelect);


	export default {
		props:['qmSheetId','todayDate','auditorName','auditorId','calibrationId','calibratorId'],
		data(){
		  	return{
		  		sheet_basic_data:[{client:null,
		  						   comm_instance_id:'',
		  						   agent_name:null,
		  						   qa_name:null,
		  						   tl_name:null,
		  						   language:null,
		  						   call_type:null,
		  						   call_sub_type:null,
		  						   campaign_name:null,
		  						   disposition:null,
		  						   customer_name:null,
		  						   customer_phone:null,
		  						   qm_sheet_version:null,
		  						   partner_name:null
		  						}],
				parameters:[],
				type_b_scoring_option:[],
				overAllTotalScore:0,
				overAllTotalScoreRow:[{total_scorable:0,with_fatal:0,without_fatal:0,with_fatal_per:0,without_fatal_per:0}],
				submission_data:[{company_id:null,
								  client_id:null,
								  partner_id:null,
								  qm_sheet_id:null,
								  process_id:null,
								  raw_data_id:null,
								  audited_by_id:null,
								  is_critical:null,
								  overall_score:null,
								  partner_id:null,
								  qrc_2:null,
								  language_2:null,
								  audit_date:'',
								  case_id:null,
								  overall_summary:null}],
			    my_alloted_call_list:[],
			    final_call_fatal_status:0,
			    total_non_scoring_parms:0,
			    calibration_data:[],
		  	}
		  },
		  mounted() {
		  	var self = this;

	        axios.get('/get_qm_sheet_details_for_calibration/'+this.qmSheetId+'/'+this.calibrationId).then(function (response){
	        	var temp_first_data = response.data.data;

	        	//console.log(temp_first_data);
	        	//my_alloted_call_list
	          	self.my_alloted_call_list = temp_first_data.my_alloted_call_list;
	          	//my_alloted_call_list
	        	
	          	self.sheet_basic_data[0].client = temp_first_data.sheet_details.client.name;
	          	self.sheet_basic_data[0].version = temp_first_data.sheet_details.version;

	          	self.parameters = temp_first_data.simple_data;
	          	self.type_b_scoring_option = temp_first_data.type_b_scoring_option;

	          	//prep for submission

	          	self.submission_data[0].company_id = temp_first_data.sheet_details.company_id;
	          	self.submission_data[0].client_id = temp_first_data.sheet_details.client_id;
	          	self.submission_data[0].process_id = temp_first_data.sheet_details.process_id;
	          	self.submission_data[0].audited_by_id = self.auditorId;
	          	self.submission_data[0].qm_sheet_id = temp_first_data.sheet_details.id;

	          	//prep for submission

	          	self.calibration_data = temp_first_data.calibration_data;


	          
	        }).catch(function (error) {
	            console.log(error);
	        });
		  },
		  methods:{
		  	getRawData: function(){

		  		var self = this;
		          axios.get('/get_raw_data_for_audit/'+this.sheet_basic_data[0].comm_instance_id.key).then(function (response){
		            var temp_data_b = response.data.data;
		            
		            self.sheet_basic_data[0].agent_name = temp_data_b.agent_name;
		            self.sheet_basic_data[0].tl_name = temp_data_b.tl;
		            self.sheet_basic_data[0].language = temp_data_b.language;
		            self.sheet_basic_data[0].call_type = temp_data_b.call_type;
		            self.sheet_basic_data[0].call_sub_type = temp_data_b.call_sub_type;
		            self.sheet_basic_data[0].disposition = temp_data_b.disposition;
		            self.sheet_basic_data[0].customer_name = temp_data_b.customer_name;
		            self.sheet_basic_data[0].customer_phone = temp_data_b.phone_number;
		            self.sheet_basic_data[0].partner_name = temp_data_b.partner_detail.name;
		            self.sheet_basic_data[0].campaign_name = temp_data_b.campaign_name;
		            self.submission_data[0].qrc_2 = temp_data_b.call_type;

		            self.submission_data[0].partner_id = temp_data_b.partner_id;
		            self.submission_data[0].raw_data_id = temp_data_b.id;
		          }).catch(function (error) {
		              swal.fire("Oop's!", "Call not found, please check your call Id.", "error");
		              self.sheet_basic_data[0].comm_instance_id=null;
		          });
		  	},
		  	onObservationChange: function(event){
		  		//console.log(event.target.value);
		  		var str = event.target.value;
		  		var res = str.split("_");
		  		
		  		this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['all_reason_type'] = "#";
		  		this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['all_reasons'] = '';
		  		//set failure type
		  		if(res[3]==2 && res[4]==1)
		  			this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['all_reason_type'] = this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['all_reason_type_fail'];

		  		if(res[3]==3 && res[4]==1)
		  			this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['all_reason_type'] = this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['all_reason_type_cric'];

		  		//set failure type

		  		//show me alert box
		  		if(this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['options'][event.target.value]['alert_box']!=null)
		  		{
		  			swal.fire("Alert!", this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['options'][event.target.value]['alert_box'].details, "warning");
		  		}
		  		//show me alert box

		  		this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['selected_options']=res[2];
				
		  		if(this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['is_non_scoring'])
		  		{
		  			this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['score'] = 0;

		  			this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['temp_weight'] = 0;
		  		}else
		  		{	
		  			if(isNaN(res[2]))
		  				this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['score']=0;
		  			else
		  				this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['score']=Number(res[2]);

		  			if(res[2]=='Critical')
		  				this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['is_fatal']=1;
		  			else
		  				this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['is_fatal']=0;

		  			if(res[2]=='N/A')
		  				this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['temp_weight']=0;
		  			else
		  				this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['temp_weight'] = this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['orignal_weight'];

		  		}

		  		//calculate total tabe;
		  		this.calculateSummary();
		  	},
		  	calculateSummary: function(){
		  			var self = this;

					this.overAllTotalScoreRow[0].with_fatal=0;
			    	this.overAllTotalScoreRow[0].without_fatal=0;
			    	this.overAllTotalScoreRow[0].with_fatal_per=0;
			    	this.overAllTotalScoreRow[0].without_fatal_per=0;

			    	this.total_non_scoring_parms=0;

			    	var temp_param_fatal_status = 0;

			    	this.final_call_fatal_status = 0;
			    	var total_scorable = 0;
			    	var total_with_fatal_score = 0;
			    	var total_without_fatal_score = 0;
			    	var param_is_fatal=0;

	  				for (const key of Object.keys(this.parameters)) {
					    //console.log(key, this.parameters[key]);
					    var temp_total_para_score = 0;
					    temp_param_fatal_status = 0;
					    param_is_fatal=0;

					    this.parameters[key]['temp_total_weightage'] = 0;

					    if(this.parameters[key]['is_non_scoring']==0)
					    	this.total_non_scoring_parms =this.total_non_scoring_parms+1;

					    for (const key_s of Object.keys(this.parameters[key].subs)) {

					    	temp_total_para_score += this.parameters[key].subs[key_s]['score'];

					    	if(this.parameters[key].subs[key_s]['is_fatal'])
					    		temp_param_fatal_status = 1;

					    		this.parameters[key]['temp_total_weightage'] += this.parameters[key].subs[key_s]['temp_weight'];
					    		total_scorable = total_scorable+Number(this.parameters[key].subs[key_s]['temp_weight']);

					    		if(this.parameters[(key)].subs[key_s]['is_fatal']==1)
					    			{
					    				this.final_call_fatal_status = 1;
					    				param_is_fatal=1;

					    			}
					    }
					    

					    if(temp_param_fatal_status)
					    	{
								this.parameters[key].score = temp_total_para_score;
						    	this.parameters[key].score_with_fatal = 0;
						    	this.parameters[key].score_without_fatal = temp_total_para_score;
						    }
					    else
					    	{	
								this.parameters[key].score = temp_total_para_score;
						    	this.parameters[key].score_with_fatal = temp_total_para_score;
						    	this.parameters[key].score_without_fatal = temp_total_para_score;
						    }

						    this.parameters[key]['is_fatal'] = param_is_fatal;

						    total_with_fatal_score += this.parameters[key].score_with_fatal;
						    total_without_fatal_score += this.parameters[key].score_without_fatal;
					}
						this.overAllTotalScoreRow[0].total_scorable = total_scorable;
						if(this.final_call_fatal_status==1)
						{								
							this.overAllTotalScoreRow[0].with_fatal = 0;
							this.overAllTotalScoreRow[0].without_fatal = total_without_fatal_score;

							this.overAllTotalScoreRow[0].with_fatal_per = 0;
					    	this.overAllTotalScoreRow[0].without_fatal_per = ((this.overAllTotalScoreRow[0].without_fatal/this.overAllTotalScoreRow[0].total_scorable)*100).toFixed(2);
					    }else
					    {
					    	this.overAllTotalScoreRow[0].with_fatal = total_with_fatal_score;
							this.overAllTotalScoreRow[0].without_fatal = total_without_fatal_score;

							this.overAllTotalScoreRow[0].with_fatal_per = ((this.overAllTotalScoreRow[0].with_fatal/this.overAllTotalScoreRow[0].total_scorable)*100).toFixed(2);
					    	this.overAllTotalScoreRow[0].without_fatal_per = ((this.overAllTotalScoreRow[0].without_fatal/this.overAllTotalScoreRow[0].total_scorable)*100).toFixed(2);
					    }
					    self.submission_data[0].is_critical = this.final_call_fatal_status;
					    self.submission_data[0].overall_score = this.overAllTotalScoreRow[0].without_fatal_per;
					    //self.submission_data[0].qm_sheet_id = 
		  		},
		  		submitMe: function(){
		  			
	  			document.getElementById("MySubmitButton").className = "btn btn-primary kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light";
  				document.getElementById("MySubmitButton").disabled = true;

			      var params = {
			      submission_data:this.submission_data,
			      parameters:this.parameters,
			      calibration_id:this.calibrationId,
			      calibrator_id:this.calibratorId
			      };
			      console.log(params);
			     axios.post('/store_calibration',params).then(function (response){
			     	swal.fire("Success", "Calibration saved successfully. Thank you for your efforts.", "success").then(function(result) {
		                if (result.value) {
		                    window.close();
		                }
		            });
		            document.getElementById("MySubmitButton").className = "btn btn-success";
			      }).catch(function (error) {
			      	document.getElementById("MySubmitButton").className = "btn btn-success";
			          console.log(error);
			          alert('Error: Audit saved, but please inform Tech Support.');
			      });


		  		},
				onReasonTypeChange: function(eve){
					
					var str = event.target.value;
			  		var srt = str.split("_");
			  		

					var self = this

					axios.get('/get_reasons_by_type/'+srt[2]).then(function (response){
      					self.parameters[parseInt(srt[0])].subs[parseInt(srt[1])]['all_reasons'] = response.data.data;
			        }).catch(function (error) {
			            console.log(error);
			        });
				}

		  }

	}
	
</script>
<style lang="scss">
@import "vue-select/src/scss/vue-select.scss";
.sp-details-top
{
	cursor: pointer;

}
.flex-container
{
	display: flex;
	align-items:center;

}
.sp-row
{
	.row
	{
		margin-bottom: 15px;
	}
}
#seprator
{
	margin: 2.5rem 0 0 0;
}
@media screen and (min-width: 480px) {
  .sp-row
  {
  	.row{
  		.col-md-2
  		{
  			margin-top: 15px;
  		}
  	}
  }
}
</style>