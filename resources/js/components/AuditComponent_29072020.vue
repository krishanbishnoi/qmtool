<template>
	<div>
		<form class="kt-form kt-form--label-right" id="audit_component_form">
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
				
				<div class="form-group row">
					<div class="col-lg-4">
						<a href="#" class="btn btn-primary" id="kt_dashboard_daterangepicker" data-toggle="kt-tooltip" title="Select raw data daterange" data-placement="left">
							<span class="kt-subheader__btn-daterange-title" id="kt_dashboard_daterangepicker_title">Today</span>&nbsp;
							<span class="kt-subheader__btn-daterange-date" id="kt_dashboard_daterangepicker_date">Aug 16</span>
							<i class="flaticon2-calendar-1"></i>
						</a>
						<button type="button" class="btn btn-success btn-elevate btn-icon" @click="getRawDataOnDateRange"><i class="la la-search"></i></button>
						<br/><small>By default, calls list is based on current audit Cycle {{audit_cycle}}</small>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-lg-4">
						<label>Communication Instance ID(Call ID):</label>

						<!-- <select class="form-control m-select2" id="kt_select2_1" name="param" v-model="sheet_basic_data[0].comm_instance_id" v-on:input="something = $event.target.value">
							<option value="">Select a call</option>
							<option v-for="(value,key) in my_alloted_call_list" v-bind:value="key">
							    {{ value }}
							  </option>
						</select> -->

						<v-select label="value" :options="my_alloted_call_list" v-model="sheet_basic_data[0].comm_instance_id" @input="getRawData()"></v-select>
					</div>
					<div class="col-lg-4">
						<label>Client:</label>
						<input type="text" class="form-control" v-model="sheet_basic_data[0].client" readonly="readonly">
					</div>
					<div class="col-lg-4">
						<label>Partner:</label>
						<input type="text" class="form-control" v-model="sheet_basic_data[0].partner_name" readonly="readonly">
					</div>
				</div>

				<div class="form-group row">
					<div class="col-lg-4">
						<label>Audit Date:</label>
						<!-- <input type="date" class="form-control" placeholder="yyyy-mm-dd" v-model="submission_data[0].audit_date" required="required" /> -->
						<input type="text" class="form-control" readonly :value="submission_data[0].audit_date" />
					</div>
					<div class="col-lg-4">
						<label>Agent Name:</label>
						<input type="text" class="form-control" :value="sheet_basic_data[0].agent_name" readonly="readonly">
					</div>
					<div class="col-lg-4">
						<label>TL Name:</label>
						<input type="text" class="form-control" :value="sheet_basic_data[0].tl_name" readonly="readonly">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-lg-4">
						<label>QA / QTL Name:</label>
						<input type="text" class="form-control" :value="auditorName" readonly="readonly">
					</div>
					<div class="col-lg-4">
						<label>Campaign Name:</label>
						<input type="text" class="form-control" v-model="submission_data[0].campaign_name">
					</div>
					<div class="col-lg-4">
						<label>Call Sub Type:</label>
						<input type="text" class="form-control" v-model="submission_data[0].call_sub_type" >
					</div>
					
				</div>
				<div class="form-group row">
					
					<div class="col-lg-4">
						<label>Disposition:</label>
						<input type="text" class="form-control" v-model="submission_data[0].disposition" required="response">
					</div>
					<div class="col-lg-4">
						<label>Customer Name:</label>
						<input type="text" class="form-control" v-model="submission_data[0].customer_name">
					</div>
					<div class="col-lg-4">
						<label>Cusotmer contact number:</label>
						<input type="text" class="form-control" v-model="submission_data[0].customer_phone">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-lg-4">
						<label>QM-Sheet Version:</label>
						<input type="text" class="form-control" :value="sheet_basic_data[0].version" readonly="readonly">
					</div>
					<div class="col-lg-4">
						<label>QRC 1:</label>
						<input type="text" class="form-control" :value="sheet_basic_data[0].call_type" readonly="readonly">
					</div>
					<div class="col-lg-4">
						<label>QRC for QA:</label>
						<!-- <input type="text" class="form-control" v-model="submission_data[0].qrc_2"> -->
						<!--<select class="form-control" v-model="submission_data[0].qrc_2" required="required">
							<option value="">Select one!</option>
							<option value="Query">Query</option>
							<option value="Request">Request</option>
							<option value="Complaint">Complaint</option>
						</select>-->
						<div v-if="n_client_id == 9">	
						<select class="form-control" v-model="submission_data[0].qrc_2" required="required">
							<option value="">Select one!</option>
							<option v-for="(v,k) in callTypeList" :value="v">{{v}}</option>
						</select>
						</div>
					   <div v-else>	
					   	<select class="form-control" v-model="submission_data[0].qrc_2" required="required">
							<option value="">Select one!</option>
							<option value="Query">Query</option>
							<option value="Request">Request</option>
							<option value="Complaint">Complaint</option>
						</select>
					   </div>
						
					</div>
				</div>

				<div class="form-group row">
					<div class="col-lg-4">
						<label>Language 1:</label>
						<input type="text" class="form-control" :value="sheet_basic_data[0].language" readonly="readonly">
					</div>
					<div class="col-lg-4">
						<label>Language for QA:</label>
					<input type="text" class="form-control" v-model="submission_data[0].language_2">
					</div>
					<div class="col-lg-4">
						<label>Case ID:</label>
					<input type="text" class="form-control" v-model="submission_data[0].case_id">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-lg-4">
						<label>Call Time:</label>
						<input type="text" class="form-control" v-model="submission_data[0].call_time" required="required">
					</div>
					<div class="col-lg-4">
						<label>Call Duration:</label>
						<input type="text" class="form-control" v-model="submission_data[0].call_duration" required="required">
					</div>
					<div class="col-lg-4">
						<label>Refrence No.:</label>
						<input type="text" class="form-control" v-model="submission_data[0].refrence_number">
					</div>
				</div>

				<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
				<div class="row" v-if="is_rca_enabled">
					<div class="col-md-2">
						<label>RCA Type:</label>
						<select class="form-control" v-model="selected_rca_type">
							<option v-for="(vv,kk) in rca_type" :value="kk">{{vv}}</option>
						</select>
					</div>
					<div class="col-md-2">
						<label>RCA Mode:</label>
						<select class="form-control" v-model="selected_rca_mode" @change="on_rca_mode_change">
							<option v-for="(vv,kk) in rca_mode" :value="kk">{{vv}}</option>
						</select>
					</div>
					<div class="col-md-2">
						<label>RCA 1:</label>
						<select class="form-control" v-model="selected_rca1" @change="on_rca1_change">
							<option v-for="(vv,kk) in rca1" :value="kk">{{vv}}</option>
						</select>
					</div>
					<div class="col-md-2">
						<label>RCA 2:</label>
						<select class="form-control" v-model="selected_rca2" @change="on_rca2_change">
							<option v-for="(vv,kk) in rca2" :value="kk">{{vv}}</option>
						</select>
					</div>
					<div class="col-md-2">
						<label>RCA 3:</label>
						<select class="form-control" v-model="selected_rca3">
							<option v-for="(vv,kk) in rca3" :value="kk">{{vv}}</option>
						</select>
					</div>
					<div class="col-md-2">
						<label>Other Detail:</label>
						<input type="text" name="" class="form-control" v-model="rca_other_detail">
					</div>
				</div>

				<div v-if="is_type_2_rca_enabled">
					<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
					<div class="row">
						<div class="col-md-2">
							<label>Type II RCA Mode:</label>
							<select class="form-control" v-model="selected_type_2_rca_mode" @change="on_type_2_rca_mode_change">
								<option v-for="(vv,kk) in type_2_rca_mode" :value="kk">{{vv}}</option>
							</select>
						</div>
						<div class="col-md-2">
							<label>Type II RCA 1:</label>
							<select class="form-control" v-model="selected_type_2_rca1" @change="on_type_2_rca1_change">
								<option v-for="(vv,kk) in type_2_rca1" :value="kk">{{vv}}</option>
							</select>
						</div>
						<div class="col-md-2">
							<label>Type II RCA 2:</label>
							<select class="form-control" v-model="selected_type_2_rca2" @change="on_type_2_rca2_change">
								<option v-for="(vv,kk) in type_2_rca2" :value="kk">{{vv}}</option>
							</select>
						</div>
						<div class="col-md-2">
							<label>Type II RCA 3:</label>
							<select class="form-control" v-model="selected_type_2_rca3">
								<option v-for="(vv,kk) in type_2_rca3" :value="kk">{{vv}}</option>
							</select>
						</div>
						<div class="col-md-2">
							<label>Type II RCA Other Detail:</label>
							<input type="text" name="" class="form-control" v-model="type_2_rca_other_detail">
						</div>
					</div>
				</div>
				
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
								<select class="form-control" v-model="sp.selected_option_model" @change="onObservationChange($event)" required="required">
									
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
					<div class="col-lg-4">
						<label>Overall Call Summary:</label>
						<textarea class="form-control" v-model="submission_data[0].overall_summary"></textarea>
					</div>
					<div class="col-lg-4">
						<label>Feedback to Agent:</label>
						<textarea class="form-control" v-model="submission_data[0].feedback_to_agent"></textarea>
					</div>
					<div class="col-lg-2">
						<label>Good or Bad Call ?:</label>
						<select class="form-control" v-model="submission_data[0].good_bad_call">
							<option value="0">Select one!</option>
							<option value="1">Good Call</option>
							<option value="2">Bad Call</option>
						</select>
					</div>
					<div class="col-lg-2">
						<label>Upload Call:</label>
						<input type="file" name="call_file" class="form-control">
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
										<div class="col-md-1">
											<button type="button" class="btn btn-success" id="MySubmitButton" @click="submitMe(1)">Final Submit</button>&nbsp;&nbsp;&nbsp;
										</div>
										<div class="col-md-2">
											<button type="button" class="btn btn-warning" id="MySaveButton" @click="submitMe(0)">Save</button>&nbsp;&nbsp;&nbsp;
										</div>
										<div class="col-md-2">
											<button type="button" class="btn btn-danger">Cancel</button>
										</div>
										<div class="col-md-6">
											<p align="right"><a :href="'/audited_list/'+qmSheetId" class="btn btn-warning">Back to List</a></p>
										</div>
									</div>
	
	</form>

	</div>
</template>
<script>
	import vSelect from 'vue-select';
 	Vue.component('v-select', vSelect);


	export default {
		props:['qmSheetId','todayDate','auditorName','auditorId'],
		data(){
		  	return{
		  		submit_type:0,
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
								  overall_summary:null,
								  feedback_to_agent:null,
								  disposition:null,
								  call_time:null,
								  call_duration:null,
								  customer_name:null,
		  						  customer_phone:null,
		  						  good_bad_call:0,
		  						  good_bad_call_file:null,
		  						  call_sub_type:null,
		  						  delay1:null,
		  						  delay2:null,
		  						  holiday:null,
		  						  refrence_number:null}],
			    my_alloted_call_list:[],
			    n_client_id:0,
			    callTypeList:[],
			    final_call_fatal_status:0,
			    total_non_scoring_parms:0,
			    is_rca_enabled:0,
			    is_type_2_rca_enabled:0,
			    rca_type:[],
			    selected_rca_type:0,
			    rca_mode:[],
			    selected_rca_mode:0,
			    rca1:[],
			    selected_rca1:0,
			    rca2:[],
			    selected_rca2:0,
			    rca3:[],
			    selected_rca3:0,
			    rca_other_detail:null,
			    type_2_rca_mode:[],
			    selected_type_2_rca_mode:0,
			    type_2_rca1:[],
			    selected_type_2_rca1:0,
			    type_2_rca2:[],
			    selected_type_2_rca2:0,
			    type_2_rca3:[],
			    selected_type_2_rca3:0,
			    type_2_rca_other_detail:null,
			    errors:[],
			    audit_cycle:'',
		  	}
		  },
		  mounted() {
		  	var self = this;
	        axios.get('/get_qm_sheet_details_for_audit/'+this.qmSheetId).then(function (response){
	        	var temp_first_data = response.data.data;
	        	console.log(temp_first_data);
	        	//my_alloted_call_list
	          	self.my_alloted_call_list = temp_first_data.my_alloted_call_list;
	          	//my_alloted_call_list
	          	self.callTypeList=temp_first_data.callTypeList;
	          	self.n_client_id=temp_first_data.client_id;
	        	
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
	          	self.submission_data[0].audit_date = temp_first_data.audit_timestamp;

	          	// Tiwari code
				self.submission_data[0].delay1 = temp_first_data.sk_client.delay1;
				self.submission_data[0].delay2 = temp_first_data.sk_client.delay2;
				self.submission_data[0].holiday = temp_first_data.sk_client.holiday;
				// Tiwari Code

	          	//prep for submission

	          	self.audit_cycle = temp_first_data.audit_cycle;

	          	self.is_rca_enabled = temp_first_data.sheet_details.client.rca_enabled;
	          	self.is_type_2_rca_enabled = temp_first_data.sheet_details.client.rca_two_enabled;

	          	self.rca_type = temp_first_data.rca_type;
	          	self.rca_mode = temp_first_data.rca_mode;
	          	self.type_2_rca_mode = temp_first_data.type_2_rca_mode;

	          
	        }).catch(function (error) {
	            console.log(error);
	        });
		  },
		  methods:{
		  	getRawData: function(){
		  		document.getElementById("audit_component_form").reset();
		  		Object.keys(this.parameters).forEach(key_param => {
						  Object.keys(this.parameters[key_param].subs).forEach(key_sub_param => {
						  	this.parameters[key_param].subs[key_sub_param].selected_option_model=null;
						  	this.parameters[key_param].subs[key_sub_param].remark=null;
						  	this.parameters[key_param].subs[key_sub_param].selected_options=null;
						  	this.parameters[key_param].subs[key_sub_param].selected_reason=null;
						  	this.parameters[key_param].subs[key_sub_param].selected_reason_type=null;
						  	this.parameters[key_param].subs[key_sub_param].temp_weight=0;
						  })
				});
		            this.submission_data[0].customer_phone =null;
		            this.submission_data[0].customer_name =null;
		            this.submission_data[0].qrc_2 =null;

		            this.submission_data[0].disposition =null;
  					this.submission_data[0].call_time =null;
  					this.submission_data[0].call_duration =null;
  					this.submission_data[0].overall_summary =null;
  					this.submission_data[0].feedback_to_agent =null;
  					this.submission_data[0].case_id =null;
  					this.submission_data[0].refrence_number =null;
  					this.submission_data[0].language_2 =null;
  					this.submission_data[0].good_bad_call =0;

		  		var self = this;
		          axios.get('/get_raw_data_for_audit/'+this.sheet_basic_data[0].comm_instance_id.key).then(function (response){
		            var temp_data_b = response.data.data;
		            self.sheet_basic_data[0].agent_name = temp_data_b.agent_name;
		            self.sheet_basic_data[0].tl_name = temp_data_b.tl;
		            self.sheet_basic_data[0].language = temp_data_b.language;
		            self.sheet_basic_data[0].call_type = temp_data_b.call_type;
		            self.sheet_basic_data[0].partner_name = temp_data_b.partner_detail.name;

		            self.submission_data[0].customer_phone = temp_data_b.phone_number;
		            self.submission_data[0].customer_name = temp_data_b.customer_name;
		            self.submission_data[0].qrc_2 = temp_data_b.call_type;
		            self.submission_data[0].partner_id = temp_data_b.partner_id;
		            self.submission_data[0].raw_data_id = temp_data_b.id;

		            self.submission_data[0].disposition = temp_data_b.disposition;
  					self.submission_data[0].call_time = temp_data_b.call_time;
  					self.submission_data[0].call_duration = temp_data_b.call_duration;
  					self.submission_data[0].call_sub_type = temp_data_b.call_sub_type;
  					self.submission_data[0].campaign_name = temp_data_b.campaign_name;
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

		  		if(res[3]==5 && res[4]==1)
		  			this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['all_reason_type'] = this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['all_reason_type_pwd'];

		  		this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['selected_reason_type']=null;
		  		this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['selected_reason']=null;
		  		//set failure type

		  		//show me alert box
		  		if(this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['options'][event.target.value]['alert_box']!=null)
		  		{
		  			swal.fire("Alert!", this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['options'][event.target.value]['alert_box'].details, "warning");
		  		}
		  		//show me alert box

		  		this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['selected_options']=res[2];
		  		this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['selected_observation'] = res[3];
				
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
		  		submitMe: function(submit_type){
		  		var self = this;

	  			this.submit_type = submit_type;
		  			
		  		if(this.submit_type)
	  			{
	  				document.getElementById("MySubmitButton").className = "btn btn-primary kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light";
	  				document.getElementById("MySubmitButton").disabled = true;
	  			}else{
	  				document.getElementById("MySaveButton").className = "btn btn-primary kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light";
	  				document.getElementById("MySaveButton").disabled = true;
	  			}
  				this.submission_data[0].selected_rca_type = this.selected_rca_type;

  				this.submission_data[0].selected_rca_mode = this.selected_rca_mode;
  				this.submission_data[0].selected_rca1 = this.selected_rca1;
  				this.submission_data[0].selected_rca2 = this.selected_rca2;
  				this.submission_data[0].selected_rca3 = this.selected_rca3;
  				this.submission_data[0].rca_other_detail = this.rca_other_detail;

  				this.submission_data[0].selected_type_2_rca_mode = this.selected_type_2_rca_mode;
  				this.submission_data[0].selected_type_2_rca1 = this.selected_type_2_rca1;
  				this.submission_data[0].selected_type_2_rca2 = this.selected_type_2_rca2;
  				this.submission_data[0].selected_type_2_rca3 = this.selected_type_2_rca3;
  				this.submission_data[0].type_2_rca_other_detail = this.type_2_rca_other_detail;

  				var validation_errors = this.validate_me();
  				if(validation_errors.length > 0)
				{
					if(this.submit_type)
					{
						document.getElementById("MySubmitButton").className = "btn btn-success";
						document.getElementById("MySubmitButton").disabled = false;
					}else
					{
						document.getElementById("MySaveButton").className = "btn btn-warning";
						document.getElementById("MySaveButton").disabled = false;
					}
					var x='<strong>Validation!</strong><br/>';
					validation_errors.forEach(function(value){
						x+= value+'</br/>';
					});
					
					swal.fire("Oop's!",x, "error");
					return null;
				}

			     var params = {
			      submit:this.submit_type,
			      submission_data:this.submission_data,
			      parameters:this.parameters
			      };
			     axios.post('/allocation/store_audit',params).then(function (response){
			     	swal.fire("Success", "Audit saved successfully.", "success").then(function(result) {
		                if (result.value) {
		                    location.reload();
		                }
		            });
		            if(self.submit_type)
		            	document.getElementById("MySubmitButton").className = "btn btn-success";
		        	else
		        		document.getElementById("MySaveButton").className = "btn btn-warning";
			      }).catch(function (error) {
			      	if(this.submit_type)
			      		document.getElementById("MySubmitButton").className = "btn btn-success";
			      	else
			      		document.getElementById("MySaveButton").className = "btn btn-warning";


			          console.log(error);
			          alert('Error: Audit saved, but please inform Tech Support.');
			      });
		  		},
		  		validate_me: function(){
		  				this.errors = [];
		  				
		  				if(this.submission_data[0].qrc_2==''||this.submission_data[0].qrc_2==null)
		  					this.errors.push("QRC required.");

		  				if(this.submission_data[0].customer_phone==''||this.submission_data[0].customer_phone==null)
		  					this.errors.push("Customer phone required.");

		  				if(this.submission_data[0].call_time==''||this.submission_data[0].call_time==null)
		  					this.errors.push("Call time required.");

		  				if(this.submission_data[0].call_duration==''||this.submission_data[0].call_duration==null)
		  					this.errors.push("Call duration required.");

		  				if(this.submission_data[0].qrc_2==''||this.submission_data[0].qrc_2==null)
		  					this.errors.push("QRC required.");

		  				if(this.submission_data[0].disposition==''||this.submission_data[0].disposition==null)
		  					this.errors.push("Disposition required.");

		  				if(this.submission_data[0].campaign_name==''||this.submission_data[0].campaign_name==null)
		  					this.errors.push("Campaign name required.");

		  				if(this.submission_data[0].customer_name==''||this.submission_data[0].customer_name==null)
		  					this.errors.push("Cusotmer name required.");

		  				if(this.submission_data[0].audit_date==''||this.submission_data[0].audit_date==null)
		  					this.errors.push("Audit date required.");

		  				if(this.submission_data[0].case_id==''||this.submission_data[0].case_id==null)
		  					this.errors.push("Case Id required.");

		  				if(this.submission_data[0].language_2==''||this.submission_data[0].language_2==null)
		  					this.errors.push("Language QA required.");

		  				if(this.submission_data[0].overall_summary==''||this.submission_data[0].overall_summary==null)
		  					this.errors.push("Overall summary QA required.");


		  				Object.keys(this.parameters).forEach(key_param => {
						  Object.keys(this.parameters[key_param].subs).forEach(key_sub_param => {
						  	if(this.parameters[key_param].subs[key_sub_param].selected_option_model==''||this.parameters[key_param].subs[key_sub_param].selected_option_model==null)
					  		{
					  			this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" observation required.");
					  		}else
					  		{
					  			if(this.parameters[key_param].subs[key_sub_param].is_non_scoring)
					  			{
					  				if(this.parameters[key_param].subs[key_sub_param].selected_observation==1||
					  				   this.parameters[key_param].subs[key_sub_param].selected_observation==5||
					  				   this.parameters[key_param].subs[key_sub_param].selected_observation==8||
					  				   this.parameters[key_param].subs[key_sub_param].selected_observation==14)
					  				{

						  				if(this.parameters[key_param].subs[key_sub_param].remark==null)
		  								{this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" remark required.");}
			  							else
			  							{
			  								if(this.parameters[key_param].subs[key_sub_param].remark.length < 25)
			  									this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" remark minimum 25 characters.");
			  							}
					  				}
					  			}else
					  			{
					  				if(this.parameters[key_param].subs[key_sub_param].selected_observation==2||
					  				   this.parameters[key_param].subs[key_sub_param].selected_observation==3)
					  				{

				  					if(this.parameters[key_param].subs[key_sub_param].remark==null)
		  								{this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" remark required.");}
			  							else
			  							{
			  								if(this.parameters[key_param].subs[key_sub_param].remark.length < 25)
			  									this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" remark minimum 25 characters.");
			  							}

				  					if(this.parameters[key_param].subs[key_sub_param].selected_reason_type==null||this.parameters[key_param].subs[key_sub_param].selected_reason==null)
				  						this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" Failure reason required.");
					  				}
					  			}
					  		}



						  });
						});

		  				return this.errors;
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
				},
				on_rca_mode_change: function(){
					var self=this;
					axios.get('/get_rca1_by_rca_mode_id/'+this.selected_rca_mode).then(function (response){
	        			self.rca1 = response.data.data;
	        			self.rca2 = null;
	        			self.rca3 = null;
	        			
			        }).catch(function (error) {
			            console.log(error);
			        });
				},
				on_rca1_change: function(){
					var self=this;
					axios.get('/get_rca2_by_rca1_id/'+this.selected_rca1).then(function (response){
	        			self.rca2 = response.data.data;
			        }).catch(function (error) {
			            console.log(error);
			        });
				},
				on_rca2_change: function(){
					var self=this;
					axios.get('/get_rca3_by_rca2_id/'+this.selected_rca2).then(function (response){
	        			self.rca3 = response.data.data;
			        }).catch(function (error) {
			            console.log(error);
			        });
				},
				on_type_2_rca_mode_change: function(){
					var self=this;
					axios.get('/get_type_2_rca1_by_rca_mode_id/'+this.selected_type_2_rca_mode).then(function (response){
	        			self.type_2_rca1 = response.data.data;
	        			self.type_2_rca2 = null;
	        			self.type_2_rca3 = null;
	        			
			        }).catch(function (error) {
			            console.log(error);
			        });
				},
				on_type_2_rca1_change: function(){
					var self=this;
					axios.get('/get_type_2_rca2_by_rca1_id/'+this.selected_type_2_rca1).then(function (response){
	        			self.type_2_rca2 = response.data.data;
			        }).catch(function (error) {
			            console.log(error);
			        });
				},
				on_type_2_rca2_change: function(){
					var self=this;
					axios.get('/get_type_2_rca3_by_rca2_id/'+this.selected_type_2_rca2).then(function (response){
	        			self.type_2_rca3 = response.data.data;
			        }).catch(function (error) {
			            console.log(error);
			        });
				},
				getRawDataOnDateRange: function(){
					
					var self=this;
					var params = {
				      date_range:document.getElementById("kt_dashboard_daterangepicker_date").innerHTML,
				      process_id: this.submission_data[0].process_id,
				      };
			     	axios.post('/get_raw_data_on_data_range',params).then(function (response){
	        			self.my_alloted_call_list = response.data.data;
			        }).catch(function (error) {
			            console.log(error);
			        });
				},

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