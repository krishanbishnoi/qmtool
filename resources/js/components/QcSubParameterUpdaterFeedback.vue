<style scoped>
	.full {
	  width: 100%;
	  height: auto;
	}
	img {
	  width: 150px;
	  border-radius: 2px;
	  box-shadow: 1px 1px 3px 1px rgba(0, 0, 0, 0.5);
	  transition: width 1s;
	}
	img:hover {
	  cursor: pointer;
	}
	
	</style>
	<template>
		<div>
			<button @click="get_my_data()" type="button" class="btn btn-outline-brand btn-sm" data-toggle="modal" :data-target="'#kt_modal_'+subParameterId" title="Update Scoring"><i class="fa flaticon-edit-1"></i></button>
	
			<!--begin::Modal-->
								<div class="modal fade" :id="'kt_modal_'+subParameterId" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="exampleModalLabel">Update Score</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												</button>
											</div>
											<div class="modal-body">
												<form>
	
													<div class="form-group row">
														<div class="col-lg-6">
															<label>Parameter:</label>
															<input type="email" class="form-control" readonly :value="basic_data.p_name">
														</div>
														<div class="col-lg-6">
															<label class="">Sub Parameter <i class="la la-info-circle kt-font-warning sp-details-top" :title="basic_data.s_detail"></i>:</label>
															<input type="email" class="form-control" readonly :value="basic_data.s_name">
														</div>
													</div>
													<div class="form-group row">
														<div class="col-lg-6">
															<label>Observation:</label>
															<select class="form-control" @change="observation_change" v-model="basic_data.selected_observation">
																<option v-for="(vv,kk) in scoring_opts" :value="kk">{{vv['value']}}</option>
															</select>
														</div>
														<div class="col-lg-6">
															<label class="">Scored:</label>
															<input type="text" class="form-control" readonly :value="basic_data.score_view">
														</div>
													</div>
													<div class="form-group row">
														<div class="col-lg-6">
															<label>Reason Type:</label>
															<select class="form-control" v-model="basic_data.selected_reason_type_id" @change="onReasonTypeChange">
																<option v-for="(vv,kk) in reason_type" :value="vv.key">{{vv.value}}</option>
															</select>
														</div>
														<div class="col-lg-6">
															<label>Reasons:</label>
															<select class="form-control" v-model="basic_data.selected_reason_id">
																<option v-for="(vv,kk) in reasons_master" :value="kk">{{vv}}</option>
															</select>
														</div>
													</div>
													<div class="form-group row">
														<div class="col-lg-6">
															<label>Remark:</label>
															<textarea class="form-control" v-model="basic_data.remarks"></textarea>
														</div>
														
														<div class="col-lg-6" v-if='basic_data.screenshot'>
															<label>Screenshot:</label>
															<a class="lightbox" href="#screenshot">
															<img :class="{ full: fullWidthImage }" @click="fullWidthImage = !fullWidthImage" :src="getAvatar(basic_data.screenshot)"></a>
														</div>
	
														<div class="col-lg-6">
															<label>Feedback for QA:</label>
															<textarea class="form-control" v-model="basic_data.qc_to_qa_feedback"></textarea>
														</div>
	
														<div class="col-lg-6">
														<label>Add Artifact:</label>
															
															<input type="file"  title="Select" class="screenshot_to_qa form-control" :ref="screenshot_to_qa"   accept="image/jpeg, image/png"  @change="handleUpload($event,'screenshot_to_qa')">
																	
														</div>
	
													</div>
	 
												</form>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
												<button type="button" :class="btnClass" :disabled="btnDisabled" id="onSpUpdate" @click="onSpUpdate">Update</button>
											</div>
										</div>
									</div>
								</div>
								<!--end::Modal-->
		</div>
	</template>
	<script>
	
	
	export default {
	  props:['auditId','parameterId','subParameterId','rebuttalId'],
		data() {
			return {
				basic_data:[],
				scoring_opts:[],
				reason_type_master:[],
				reasons_master:[],
				reason_type:[],
				btnClass:'',
				btnDisabled:false,
				screenshot_to_qa:null,
				fullWidthImage: false,
				
			}
		},
		mounted() {
			this.get_my_data(); 
			this.btnClass = 'btn btn-primary';
		},
		methods: {
	
			getAvatar(avatar){
			return "/storage/"+avatar;
			  },
	
			get_my_data: function(){
			  var self=this;
			  var params = {
			  audit_id:this.auditId,
			  parameter_id:this.parameterId,
			  sub_parameter_id:this.subParameterId
			  };
			 axios.post('/qc/get_details_for_update_audit_sub_parameter',params).then(function (response){
	
				  // console.log(response.data.data);
				 var temp_data = response.data.data;
				 self.scoring_opts = temp_data.scoring_opts;
				 self.basic_data = temp_data.intro_data;
				 self.reason_type  = temp_data.intro_data.reason_type;
				 self.reason_type_master = temp_data.reason_type_master;
				 self.reasons_master = temp_data.reasons_master;
				 console.log(self.basic_data);
	
			  }).catch(function (error) {
				  console.log(error);
			  });
			},
	
			handleUpload(e,type) {
	
				var reforfile = 'screenshot_to_qa';
				console.log(Type);
				//this.parameters[pm_id].subs[sb_id].type = null;
				const img = e.target.files[0];
				if (type) {
				var reg = /(.*?)\.(jpg|bmp|jpeg|png|JPG|PNG|JPEG)$/;
				if (!img.name.match(reg)) {
					
						alert('File Extension Not Supported.\n Only JPG & PNG Extension Format Images are Allowed');
						this.$refs[reforfile][0].value = '';
						return ;
	
				}
				if (img.size / 1000 > 2048) {
	
					alert('Image size must not exceed 2 MB');
					this.$refs[reforfile][0].value = '';
						return ;
				}
				else{
					this.createBase64(img,type)
				}
	
				} 
	
			},
	
			createBase64(fileObj, type) {
				const reader = new FileReader()
				reader.onload = e => {
					this.parameters[pm_id].subs[sb_id].type = e.target.result
				}
				reader.readAsDataURL(fileObj)
			},
			observation_change: function(event){
				var str = event.target.value;
				  var res = str.split("_");
				//show me alert box
				if(str=='')
					return false;
	
				  if(this.scoring_opts[event.target.value]['alert_box']!=null)
				  {
					  swal.fire("Alert!", this.scoring_opts[event.target.value]['alert_box'].details, "warning");
				  }
				  //show me alert box
				  this.basic_data.score_view = res[0];
				  this.basic_data.selected_option = res[1];
	
				  switch(Number(res[1]))
				  {
					  case 1:{
						  this.basic_data.scored=Number(res[0]);
						  this.basic_data.after_audit_weight = this.basic_data.weight;
						  this.basic_data.is_critical=0;
						  this.reason_type=[];
						  this.basic_data.selected_reason_type_id=0;
						  this.basic_data.selected_reason_id=0;
						  break;
					  }
					  case 2:{
						  this.basic_data.scored=0;
						  this.basic_data.after_audit_weight = this.basic_data.weight;
						  this.basic_data.is_critical=0;
						  this.reason_type=this.reason_type_master[2];
						  this.basic_data.selected_reason_type_id=0;
						  this.basic_data.selected_reason_id=0;
						  break;
					  }
					  case 3:{
						  this.basic_data.scored=0;
						  this.basic_data.after_audit_weight = this.basic_data.weight;
						  this.basic_data.is_critical=1;
						  this.reason_type=this.reason_type_master[3];
						  this.basic_data.selected_reason_type_id=0;
						  this.basic_data.selected_reason_id=0;
						  break;
					  }
					  case 4:{
						  this.basic_data.scored=0;
						  this.basic_data.after_audit_weight = 0;
						  this.basic_data.is_critical=0;
						  this.reason_type=[];
						  // this.basic_data.selected_reason_type_id=0;
						  // this.basic_data.selected_reason_id=0;
						  break;
					  }
					  case 5:{
						  this.basic_data.scored=Number(res[0]);
						  this.basic_data.after_audit_weight = this.basic_data.weight;
						  this.basic_data.is_critical=0;
						  this.reason_type=this.reason_type_master[5];
						  this.basic_data.selected_reason_type_id=0;
						  this.basic_data.selected_reason_id=0;
						  break;
					  }
	
				  }
	
	
			},
			onSpUpdate: function(){
			  
			  this.btnClass = "btn btn-primary kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light";
			  this.btnDisabled=true;
	
			  var self=this;
			  var params = {
				  audit_id:this.auditId,
				  parameter_id:this.parameterId,
				  sub_parameter_id:this.subParameterId,
				  basic_data: this.basic_data,
				  rebuttal_id: this.rebuttalId,
			  };
			 axios.post('/qc/update_sp_data',params).then(function (response){
					 console.log(response.data);
					 self.btnClass = "btn btn-primary";
					  self.btnDisabled=false;
					  swal.fire("Success", "Sub parameter updated successfully.", "success").then(function(result) {
							if (result.value) {
								location.reload();
							}
						});
			  }).catch(function (error) {
					self.btnClass = "btn btn-primary";
					  self.btnDisabled=false;
					  var errors  = error.response;
					if(errors.status==422)
					{
					  alert("Error: "+errors.data.message);
					}else
					{
					  alert("Error: "+errors.data.message);  
					}
			  });
			},
			onReasonTypeChange: function(){
				var self=this;
				axios.get('/get_reasons_by_type/'+this.basic_data.selected_reason_type_id).then(function (response){
					  self.reasons_master = response.data.data;
				}).catch(function (error) {
					console.log(error);
				});
			}
		}
	}
		
	</script>
	<style>
		
	</style>