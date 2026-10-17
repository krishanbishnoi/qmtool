<template>
	<div>

	  	  <div class="form-group">
            <label>Select Client*</label>
            <select class="form-control" name="client_id" required  v-model="selected_client" @change="onClientSelectChange()" required="required">
            	<option v-for="(item,key) in all_client_list" :value="key">
                {{ item }}
              </option>
            </select>
          </div>
          <div class="form-group">
            <label>Select Process*</label>
            <select class="form-control" name="process_id" readonly v-model="selected_process" @change="onProcessSelectChange()" required="required">
            	<option v-for="(item,key) in all_client_process_list" :value="key">
                {{ item }}
              </option>
            </select>
        </div>

        <div class="form-group">
            <label>Select Qm Sheet*</label>
            <select class="form-control" name="qm_sheet_id" v-model="selected_sheet_id">
            	<option v-for="(item,key) in all_client_process_qm_sheet_list" :value="item.id">
                {{ item.name }} - v.{{item.version}}
              </option>
            </select>
        </div>

	</div>
</template>
<script>
import draggable from 'vuedraggable';
export default {
  props:['clientId','processId','qmSheetId'],
	data() {
	    return {
	    	selected_client:0,
	    	all_client_list:[],
	    	selected_process:0,
	    	all_client_process_list:[],
	    	selected_sheet_id:0,
	    	all_client_process_qm_sheet_list:[],
	    }
	},
	mounted() {
        var self = this;
        axios.get('/get_company_client_list').then(function (response){
          self.all_client_list = response.data.data;
        }).catch(function (error) {
            console.log(error);
        });

        if(this.clientId)
        {
          this.selected_client = this.clientId;
          this.selected_process = this.processId;
          this.selected_sheet_id = this.qmSheetId;
          this.onClientSelectChange();
          this.onProcessSelectChange();
        }
    },
    methods: {
    	onClientSelectChange: function()
    	{
          var self = this;
          // get qtls
          axios.get('/get_client_all_process/'+this.selected_client).then(function (response){
            self.all_client_process_list = response.data.data;
          }).catch(function (error) {
              console.log(error);
          });
    	},
    	onProcessSelectChange: function(){
    		var self = this;
          	// get qtls
	  		  var params = {
		      client_id:this.selected_client,
		      process_id:this.selected_process
		      };
		      
		     axios.post('/get_client_process_based_qm_sheet',params).then(function (response){
		     	self.all_client_process_qm_sheet_list = response.data.data;
		      }).catch(function (error) {
		          console.log(error);
		      });
          
    	}
    }
}
	
</script>
