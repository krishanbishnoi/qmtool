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
              <label>Dump Date*</label>
              <input type="text" class="form-control" id="kt_datepicker_1" readonly placeholder="Select date" name="dump_date"/>
          </div>
          <div class="form-group">
            <label>Select Clint*</label>
            <select class="form-control" name="client_id" required v-model="selected_client" @change="onClientSelectChange()" required="required">
            	<option v-for="(item,key) in all_client_list" :value="key">
                {{ item }}
              </option>
            </select>
          </div>
          <div class="form-group">
            <label>Select Client's Partner*</label>
            <select class="form-control" name="partner_id" required v-model="selected_partner" required="required" @change="onPartnerSelectChange()">
              <option v-for="(item,key) in all_partner_list" :value="key">
                {{ item }}
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
          <div class="form-group">
            <label>Select QA*</label>
            <select class="form-control" name="qa_id" required v-model="selected_qa" required="required" @change="onQaSelectChange()">
              <option v-for="(item,key) in all_qa_list" :value="item.id">
                {{ item.name }}
              </option>
            </select>
          </div>

          <div class="row">
    <div class="col-6">
      <h6>Available Agents</h6>
      <draggable class="list-group" :list="agent_list" group="people" @change="log">
        <div
          class="list-group-item"
          v-for="(element, index) in agent_list"
          :key="element.id"
        >
          {{ element.agent_name }}
        </div>
      </draggable>
      <span class="form-text text-muted">use drag and drop for creating your team.</span>
    </div>

    <div class="col-6">
      <h6>Your selected Agents for QA</h6>
      <draggable class="list-group" :list="selected_team_list" group="people" @change="log" >
        <div
          class="list-group-item"
          v-for="(element, index) in selected_team_list"
          :key="element.agent_name"
        >
          {{ element.agent_name }}
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
        Allocated Agents to QA
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
              Employee Id
            </th>
            <th title="Field #2">
              Agent Name
            </th>
            <th title="Field #7">
              Actions
            </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item,itemObjKey) in all_qa_agents">
          <td>{{itemObjKey+1}}</td>
          <td>{{item.emp_id}}</td>
          <td>{{item.agent_name}}</td>
          <td nowrap>
                        <div style="display: flex;">
                          <button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Detach" @click="remove_from_team(item.agent_name)">
                            <i class="la la-trash"></i>
                          </button>
                    </div>

                </td>
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
        selected_partner:0,
        all_partner_list:[],
        all_qtl_list:[],
        selected_qtl:0,
        agent_list:[],
        selected_team_list:[],
        associated_qa_list:[],
        selected_qa:0,
        all_qa_list:[],
        dump_date:'',
        all_qa_agents:[]
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
          var self = this;
          // get qtls
          axios.get('/get_qtls_by_client/'+this.selected_client).then(function (response){
            self.all_qtl_list = response.data.data;
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
            self.all_qa_list = response.data.data;
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
      selected_qtl:this.selected_qtl,
      selected_qa:this.selected_qa,
      dump_date:document.getElementById("kt_datepicker_1").value,
      client_id:this.selected_client,
      partner_id:this.selected_partner,
      selected_agent_list:this.selected_team_list
      };
     axios.post('/allocation/update_qa_agent',params).then(function (response){
      document.getElementById("MySubmitButton").className = "btn btn-primary";
      document.getElementById("MySubmitButton").disabled = false;
      window.location = "/allocation/qa_agent";
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
      onPartnerSelectChange: function(){
        var self = this;
          var params = {
          dump_date:document.getElementById("kt_datepicker_1").value,
          client_id:this.selected_client,
          partner_id:this.selected_partner
          };
      axios.post('/allocation/get_available_agents_to_allocate',params).then(function (response){
        self.agent_list = response.data.data;
      }).catch(function (error) {
          console.log(error);
      });
      },
      onQaSelectChange: function(){
        var self = this;
      var params = {
        client_id:this.selected_client,
        partner_id:this.selected_partner,
        qa_id:this.selected_qa
      };
     axios.post('/get_alloted_partners_agent_by_qa',params).then(function (response){
        self.all_qa_agents = response.data.data;
      }).catch(function (error) {
          console.log(error);
      });

      },
      remove_from_team: function(agent_name){

        var self = this;
        var params = {
          dump_date:document.getElementById("kt_datepicker_1").value,
          client_id:this.selected_client,
          partner_id:this.selected_partner,
          qa_id:this.selected_qa,
          agent_name:agent_name
        };
       axios.post('/remove_agent_from_qa_team',params).then(function (response){
        //console.log(response.data);
        self.onQaSelectChange();
        self.onPartnerSelectChange();

          swal.fire({
                position: 'top-right',
                type: 'success',
                title: response.data.message,
                showConfirmButton: false,
                timer: 1500
            });

        }).catch(function (error) {
            console.log(error);
        });
      }
    }
}
	
</script>
<style>
	
</style>