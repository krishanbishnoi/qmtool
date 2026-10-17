<template>
<div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Select Client:</label>
				<select class="form-control" name="client_id"
					    required="required" @change="onClientChange" 
					    v-model="selected_client">
					<option>Select One!</option>
					<option v-for="(vv,kk) in all_client_list" :value="kk">{{vv}}</option>
				</select>
			</div>
			<div class="col-lg-6">
				<label class="">Select Partner:</label>
				<select class="form-control" name="partner_id"
					    required="required" @change="onPartnerChange" 
					    v-model="selected_partner">
					<option>Select One!</option>
					<option v-for="(vv,kk) in all_partner_list" :value="kk">{{vv}}</option>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Select Location:</label>
				<select class="form-control" name="location_id"
					    required="required" v-model="selected_location">
					<option>Select One!</option>
					<option v-for="(vv,kk) in all_location_list" :value="kk">{{vv}}</option>
				</select>
			</div>
			<div class="col-lg-6">
				<label class="">Select Process:</label>
				<select class="form-control" name="process_id"
					    required="required" @change="onProcessChange" 
					    v-model="selected_process">
					<option>Select One!</option>
					<option v-for="(vv,kk) in all_process_list" :value="kk">{{vv}}</option>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Select Qm Sheet:</label>
				<select class="form-control" name="qm_sheet_id"
					    required="required">
					<option>Select One!</option>
					<option v-for="(vv,kk) in all_qmsheet_list" :value="kk">{{vv}}</option>
				</select>
			</div>
			<div class="col-lg-6">
				<label class="">Select Start Date:</label>
				<input type="text" class="form-control"  id="kt_datepicker_1" name="start_date" required="required">
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label class="">Select End Date:</label>
				<input type="text" class="form-control" id="kt_datepicker_2" name="end_date" required="required">
			</div>
		</div>
</div>	
</template>
<script>

export default {
  
	data() {
	    return {
	    	all_client_list:[],
	    	selected_client:null,
	    	all_partner_list:[],
	    	selected_partner:null,
	    	all_location_list:[],
	    	selected_location:null,
	    	all_process_list:[],
	    	selected_process:null,
	    	all_qmsheet_list:[]
	    }
	},
	mounted() {
        var self = this;
        axios.get('/report/get_rdr_client_list').then(function (response){
          self.all_client_list = response.data.data;
        }).catch(function (error) {
            console.log(error);
        });

    },
    methods: {
    	onClientChange: function()
    	{
          var self = this;
          axios.get('/report/get_rdr_client_partner_list/'+this.selected_client).then(function (response){
            self.all_partner_list = response.data.data;
          }).catch(function (error) {
              console.log(error);
          });
    	},
    	onPartnerChange: function(){
    		var self = this;

    		var sp_var=null;
    		if(this.selected_partner=='%')
    			sp_var = "All";
    		else
				sp_var = this.selected_partner;

          axios.get('/report/get_rdr_client_partner_location_list/'+sp_var+'/'+this.selected_client).then(function (response){
            self.all_location_list = response.data.data.partner_location_list;
            self.all_process_list = response.data.data.partner_process_list;
          }).catch(function (error) {
              console.log(error);
          });

    	},
    	onProcessChange: function(){
    		var self = this;
          axios.get('/report/get_rdr_client_process_qmsheeet_list/'+this.selected_client+'/'+this.selected_process).then(function (response){
            self.all_qmsheet_list = response.data.data;
          }).catch(function (error) {
              console.log(error);
          });

    	}
    	
    }
}
	
</script>
<style>
	
</style>