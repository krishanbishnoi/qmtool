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
						<input type='text' class="form-control" id="kt_daterangepicker_1" required="required"/>
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
								<th>Sub Parameter</th>
								<th v-for="(clmn,k) in despositions">{{clmn}}</th>
								<th>Overall</th>
							</tr>
							<tr>
								<th scope="row">Audit Count || NA Count</th>
								<th v-for="(desp,kkk) in audit_count">{{ desp }} || {{ na_count[kkk] }}</th>
								<th scope="row">{{ total_audit_count }}</th>
							</tr>	
							<tr>
								<th scope="row">Audit Score</th>
								<th v-for="(desp,kkk) in audit_score">{{ desp }} %</th>
								<th scope="row">{{ total_audit_score }} %</th>
							</tr>	
						</thead>
						<tbody v-for="(item,kk) in report_data">
							<tr v-for="(items,kkk) in item.data">
								<th scope="row">{{items.name}}</th>
								<td v-for="(desp,kkk) in despositions">{{items.data[desp].score}} %</td>
								<td>{{items.data['total']}} %</td>
							</tr>
							<tr class="kt-bg-danger kt-font-light">
								<th scope="row">{{item.name}}</th>
								<th v-for="(desp,kkk) in despositions">{{item.sum[desp]}} %</th>
								<th scope="row">{{item.sum['total']}} %</th>
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
    			report_data:[],
    			despositions:[],
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

            	this.selected_elements[0].dates  = document.getElementById("kt_daterangepicker_1").value;
	    		// console.log(this.selected_elements);
	    		var self=this;
	    		var params = {
			      selected_elements:this.selected_elements,
			      };
			      // console.log(params);
			     axios.post('/report/get_client_agent_wise_report_data',params).then(function (response){
			     	console.log(response.data.data);
			     	var tempt_response = response.data.data;
			     	self.report_data = tempt_response.data;
			     	self.despositions = tempt_response.despositions;
			     	self.audit_count = tempt_response.audit_count;
			     	self.audit_score = tempt_response.audit_score;
			     	self.na_count = tempt_response.na_count;
			     	self.total_audit_count = tempt_response.total_audit_count;
			     	self.total_audit_score = tempt_response.total_audit_score;
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