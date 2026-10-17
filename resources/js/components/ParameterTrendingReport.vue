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
					<div class="col-md-2">
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
					<div class="col-md-2">
						<select class="form-control" v-model="selected_elements[0].process_id" required="required">
							<option value="0">Select a Process</option>
							<option v-for="(v,k) in all_partner_process_list" :value="k">{{v}}</option>
						</select>
					</div>
					
					<div class="col-md-2">
						<button type="submit" class="btn btn-outline-brand"><i class="fa fa-search"></i> Search</button>
					</div>
					
				</div>
			</form>
			</div>
		</div>


		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Report
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<table class="table table-bordered table-hover" v-if="has_data">
						<thead>
							<tr class="kt-bg-brand kt-font-light">
								<th>Parameter</th>
								<th v-for="(clmn,k) in month_list">{{clmn}}</th>
							</tr>
						</thead>
						<tbody v-for="(item,kk) in param_data">
							<tr>
								<th scope="row">{{item.name}}</th>
								<td v-for="(desp,kkk) in month_list">{{item.data[desp].score_per}} %</td>
							</tr>
						</tbody>
				</table>
			</div>
		</div>
	
</div>
</template>
<script>
	export default {

		props:['clientId'],
		data() {
	    	return {
	    		selected_elements:[{partner_id:0,
	    							location_id:0,
	    							process_id:0,
	    							dates:0}],
				all_partners_list:[],
    			all_partner_location_list:[],
    			all_partner_process_list:[],
    			month_list:[],
    			param_data:[],
    			
    			has_data:false,
					}
				},
		mounted()
	    {
	    	// get all partners
	    	var self = this;
	        axios.get('/dashboard/get_loged_in_client_partners').then(function (response){
	          	self.all_partners_list = response.data.data;
	        }).catch(function (error) {
	            console.log(error);
	        });

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
            	
	    		// console.log(this.selected_elements);
	    		var self = this;
	    		var params = {
			      selected_elements:this.selected_elements,
		        };

			     // console.log(params);
			     axios.post('/report/get_parameter_trending_report_data',params).then(function (response){
			     	console.log(response.data.data);
			     	var tempt_response = response.data.data;
			     	self.month_list = tempt_response.month_list;
			     	self.param_data = tempt_response.param_data;

			     	self.has_data=true;
                	KTApp.unblockPage();
			      }).catch(function (error) {
			          console.log(error);
			          self.has_data=false;
			      });
	    	}
	    }
	}
	
</script>
<style>
	
</style>