<template>
	<div>
<div class="form-group">
            <label>Select Client*</label>
            <select class="form-control" name="client_id" required v-model="selected_client" @change="onClientSelectChange()" required="required">
            	<option v-for="(item,key) in all_client_list" :value="key">
                {{ item }}
              </option>
            </select>
          </div>
          <div class="form-group">
            <label>Select Client's Partner*</label>
            <select class="form-control" name="partner_id" required v-model="selected_partner" @change="onPartnerSelectChange()" required="required">
            	<option v-for="(item,key) in all_partner_list" :value="key">
                {{ item }}
              </option>
            </select>
          </div>
          <!-- <div class="form-group">
            <label>Select Partner Location*</label>
            <select class="form-control" name="location_id" required v-model="selected_location" required="required">
              <option v-for="(item,key) in all_location_list" :value="key">
                {{ item }}
              </option>
            </select>
          </div> -->
          <div class="form-group">
            <label>Select Partner's Process*</label>
            <select class="form-control" name="process_id" required v-model="selected_process" required="required">
            	<option v-for="(item,key) in all_process_list" :value="key">
                {{ item }}
              </option>
            </select>
          </div>
          <div class="form-group">
            <label>Select Sheet*</label>
            <select class="form-control" name="sheet_id" required v-model="selected_sheet" required="required" @change="onSheetSelectChange()">
            	<option v-for="(item,key) in all_sheet_list" :value="item.id">
                {{ item.name }} - {{item.process.name}}
              </option>
            </select>
          </div>
	</div>
</template>
<script>

export default {
	data() {
	    return {
    	selected_client:0,
    	all_client_list:[],
    	selected_sheet:0,
    	all_sheet_list:[],
    	qtl_team_list:[],
    	selected_partner:0,
    	all_partner_list:[],
    	selected_process:0,
    	all_process_list:[],
      selected_location:0,
      all_location_list:[],


	    }
	},
	mounted() {
        var self = this;
        axios.get('/get_company_client_list').then(function (response){
          self.all_client_list = response.data.data;
        }).catch(function (error) {
            console.log(error);
        });
    },
    methods: {
    	onClientSelectChange: function()
    	{
  		    // get sheet
          var self = this;
          axios.get('/get_sheets_by_client/'+this.selected_client).then(function (response){
            self.all_sheet_list = response.data.data;
          }).catch(function (error) {
              console.log(error);
          });

          // get partners
          axios.get('/get_client_partner/'+this.selected_client).then(function (response){
            self.all_partner_list = response.data.data;
            
          }).catch(function (error) {
              console.log(error);
          });
    	},
    	onQtlSelectChange: function(){
          var self = this;
          axios.get('/get_qtl_team/'+this.selected_qtl).then(function (response){
            self.qtl_team_list = response.data.data;
          }).catch(function (error) {
              console.log(error);
          });
    	},
      log: function(evt) {
        window.console.log(this.selected_team_list);
      },
    	onPartnerSelectChange: function(){
    		var self = this;
          axios.get('/get_partners_process/'+this.selected_partner).then(function (response){
            self.all_process_list = response.data.data;
          }).catch(function (error) {
              console.log(error);
          });

          axios.get('/get_partners_location/'+this.selected_partner).then(function (response){
            self.all_location_list = response.data.data;
          }).catch(function (error) {
              console.log(error);
          });
    	},
    	
      onSheetSelectChange: function(){
        var self = this;
          axios.get('/get_qm_sheet_associated_qa/'+this.selected_sheet).then(function (response){
            self.associated_qa_list = response.data.data;
          }).catch(function (error) {
              console.log(error);
          });
      },

    }
}
	
</script>
<style>
	
</style>