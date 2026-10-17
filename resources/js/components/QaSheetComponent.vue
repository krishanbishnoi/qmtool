<template>
	<div>

		<div class="row">
  <div class="col-md-8">
    <!--begin::Portlet-->
    <div class="kt-portlet">
      <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
          <h3 class="kt-portlet__head-title">
            Details
          </h3>
        </div>
      </div>
        <form
  @submit="checkForm">
        <div class="kt-portlet__body">

          <div class="form-group">
            <label>Select Clint*</label>
            <select class="form-control" name="client_id" required v-model="selected_client" @change="onClientSelectChange()" required="required">
            	<option v-for="(item,key) in all_client_list" :value="key">
                {{ item }}
              </option>
            </select>
          </div>
          <div class="form-group">
            <label>Select Sheet*</label>
            <select class="form-control" name="sheet_id" required v-model="selected_sheet" required="required" @change="onSheetSelectChange()" multiple="true">
            	<option v-for="(item,key) in all_sheet_list" :value="item.id +'-'+ item.process.id" >
                {{ item.name }} - {{item.process.name}}
              </option>
            </select>
          </div>
          <div class="form-group">
            <label>Select Qtl*</label>
            <select class="form-control" name="qtl_id" required v-model="selected_qtl" @change="onQtlSelectChange()" required="required">
              <option v-for="(item,key) in all_qtl_list" :value="key">
                {{ item }}
              </option>
            </select>
          </div>

          <div class="row">
    <div class="col-6">
      <h6>All QA List</h6>
      <draggable class="list-group" :list="qtl_team_list" group="people" @change="log">
        <div
          class="list-group-item"
          v-for="(element, index) in qtl_team_list"
          :key="element.id"
        >
          {{ element.name }}
        </div>
      </draggable>
      <span class="form-text text-muted">use drag and drop for creating your team.</span>
    </div>

    <div class="col-6">
      <h6>Your selected Team</h6>
      <draggable class="list-group" :list="selected_team_list" group="people" @change="log">
        <div
          class="list-group-item"
          v-for="(element, index) in selected_team_list"
          :key="element.name"
        >
          {{ element.name }}
        </div>
      </draggable>
    </div>
  </div>

        
          
          
        </div>
        <div class="kt-portlet__foot">
          <div class="kt-form__actions">
            <button type="submit" class="btn btn-primary" id="MySubmitButton">Submit</button>
            <button type="reset" class="btn btn-secondary">Cancel</button>
          </div>
        </div>
      </form>
      <!--end::Form-->
    </div>
    <!--end::Portlet-->
  </div>
</div>

<div class="kt-portlet kt-portlet--mobile">
  <div class="kt-portlet__head kt-portlet__head--lg">
    <div class="kt-portlet__head-label">
      <span class="kt-portlet__head-icon">
        <i class="kt-font-brand flaticon2-line-chart"></i>
      </span>
      <h3 class="kt-portlet__head-title">
        QM Sheet Associated QA's
      </h3>
    </div>
  </div>
  <div class="kt-portlet__body">

    <!--begin: Datatable -->
    <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
      <thead>
        <tr>
            <th title="Field #1">#</th>
            <th title="Field #2">
              Name
            </th>
            <th title="Field #2">
              Email
            </th>
            
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item,itemObjKey) in associated_qa_list">
          <td>{{itemObjKey+1}}</td>
          <td>{{item.name}}</td>
          <td>{{item.email}}</td>
        </tr>
      </tbody>
    </table>
</div>
</div>

		
	</div>
</template>
<script>
import draggable from 'vuedraggable';
export default {
	data() {
	    return {
	    	selected_client:0,
	    	all_client_list:[],
        selected_sheet:0,
        all_sheet_list:[],
        all_qtl_list:[],
        selected_qtl:0,
        qtl_team_list:[],
        selected_team_list:[],
        associated_qa_list:[],

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
            console.log(self.all_sheet_list);
          }).catch(function (error) {
              console.log(error);
          });

          // get qtls
          axios.get('/get_qtls_by_client/'+this.selected_client).then(function (response){
            self.all_qtl_list = response.data.data;
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
    	checkForm: function(e){
        e.preventDefault();

      document.getElementById("MySubmitButton").className = "btn btn-primary kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light";
      document.getElementById("MySubmitButton").disabled = true;

      var params = {
      sheet_id:this.selected_sheet,
      my_team:this.selected_team_list
      };
     axios.post('/allocation/store_qa_sheet',params).then(function (response){
      
      document.getElementById("MySubmitButton").className = "btn btn-primary";
      document.getElementById("MySubmitButton").disabled = false;
      window.location = "/allocation/qa_sheet";

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
      }
    }
}
	
</script>
<style>
	
</style>