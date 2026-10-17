<template>
	<div>
		<form action="">
		<div class="row" style="padding: 20px 0; height: 100%; ">
			<div class="col-md-2 kt-font-bolder kt-font-primary flex-item">{{master_data[0].parameter}}</div>
			<div class="col-md-10">
				<div class="row">
					<div class="col-md-2 kt-font-bold">
						{{master_data[0].sub_parameter}} <i class="la la-info-circle kt-font-warning sp-details-top" :title="master_data[0].details"></i>
					</div>
					<div class="col-md-2 kt-font-bold">
						<p>{{master_data[0].remark}}</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
		            <label>Rebuttal Reply / Remark</label>
		            <textarea class="form-control" v-model="master_data[0].reply_remark" minlength="30" required></textarea>
		          </div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
		            <label>Artifact</label>
		            
					<input type="file" id="file" ref="file" @change="handleFileUpload" /> 
				</div>
			</div>
		</div>

		<div class="kt-portlet__oot">
          <div class="kt-form__actions">
            <button type="submit" class="btn btn-success" @click="save_this(1)">Rebuttal Accepted</button>
            <button type="submit" class="btn btn-danger" @click="save_this(2)">Rebuttal Rejected</button>
          </div>
        </div>
	</form>
	</div>
</template>
<script>
	export default {
		props:['rebuttalId'],
	data() {
	    return {
	    	master_data:[{
	    		parameter:null,
	    		sub_parameter:null,
	    		remark:null,
	    		audit_id:0,
				file: '',
	    	}],
	    }
	},
	mounted() {
		var self = this;
	        axios.get('/get_para_and_sub_para_for_rebuttal_status/'+this.rebuttalId).then(function (response){
	        	var temp_data = response.data.data;

	        	self.master_data[0].parameter = temp_data.parameter;
	        	self.master_data[0].sub_parameter = temp_data.sub_parameter;
	        	self.master_data[0].audit_id  = temp_data.audit_id;
	        	self.master_data[0].remark = temp_data.remark;
				

	        }).catch(function (error) {
	            console.log(error);
	        });
	},
	methods: {
		call_my_fun: function(){
			window.location = "/raised_rebuttal_list";
		},
		handleFileUpload() {
            //console.log(this.$refs.file.files[0]);
            this.file = this.$refs.file.files[0];
        },
		onObservationChange: function(event){
				if(event.target.value=='')
					return null;

		  		//console.log(event.target.value);
		  		var temp_selection_ob = event.target.value;

		  		this.master_data[0].all_reason_type = null;
		  		this.master_data[0].all_reasons = null;
		  		this.master_data[0].selected_reason_type = '';
		  		this.master_data[0].selected_reasons = '';

		  		if(this.master_data[0].is_non_scoring==0)
		  		{//set failure type
		  				  		if(temp_selection_ob==2)
		  				  			this.master_data[0].all_reason_type  = this.master_data[0].all_reason_type_fail;
		  		
		  				  		if(temp_selection_ob==3)
		  				  			this.master_data[0].all_reason_type  = this.master_data[0].all_reason_type_cric;}

		  		this.master_data[0].scored = this.master_data[0].observation_options[temp_selection_ob]['label'];


		  		//show me alert box
		  		if(this.master_data[0].observation_options[temp_selection_ob]['alert_box']!=null)
		  		{
		  			swal.fire("Alert!", this.master_data[0].observation_options[temp_selection_ob]['alert_box'].details, "warning");
		  		}
		  		//show me alert box
		},
		onReasonTypeChange: function(eve){
					var str = event.target.value;
					var self = this;
					axios.get('/get_reasons_by_type/'+str).then(function (response){
      					self.master_data[0].all_reasons = response.data.data;
			        }).catch(function (error) {
			            console.log(error);
			        });
				},
				save_this: function function_name(argument) {
					console.log(this.master_data);
					
					let formData = new FormData();
            		formData.append('file', this.file);
					
					var params = {
						parameter:this.master_data[0].parameter,
						sub_parameter:this.master_data[0].sub_parameter,
						audit_id:this.master_data[0].audit_id,
						all_reasons:this.master_data[0].all_reasons,
						remark:this.master_data[0].remark,
						reply_remark:this.master_data[0].reply_remark,

						rebuttal_id:this.rebuttalId,
						reply_status:argument
			      	};



			     axios.post('/reply_rebuttal',formData,{
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },params: params}).then(function (response){

			     	swal.fire("Success", "Rebuttal replied successfully.", "success").then(function(result) {
		                if (result.value) {
		                    window.location = "/raised_rebuttal_list";
		                }
		            });
			      }).catch(function (error) {
			          console.log(error);
			      });


				}

	},
}
</script>
<style>
	
</style>