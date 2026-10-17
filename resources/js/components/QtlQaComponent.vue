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
            <label>Select QTL*</label>
            <select class="form-control" name="qtl_id" required v-model="selected_qtl" @change="onSelectChange()">
            	<option v-for="(item,key) in all_qtl_list" :value="key">
                {{ item }}
              </option>
            </select>
          </div>

          <div class="row">
    <div class="col-6">
      <h6>All QA List</h6>
      <draggable class="list-group" :list="all_qa_list" group="people" @change="log">
        <div
          class="list-group-item"
          v-for="(element, index) in all_qa_list"
          :key="element.id"
        >
          {{ element.name }}
        </div>
      </draggable>
      <span class="form-text text-muted">use drag and drop for creating your team.</span>
    </div>

    <div class="col-6">
      <h6>Your selected Team</h6>
      <draggable class="list-group" :list="list2" group="people" @change="log">
        <div
          class="list-group-item"
          v-for="(element, index) in list2"
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
        Qtl Team List
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
            <th title="Field #7">
              Actions
            </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item,itemObjKey) in my_team_list">
          <td>{{itemObjKey+1}}</td>
          <td>{{item.name}}</td>
          <td>{{item.email}}</td>
          <td nowrap>
                        <div style="display: flex;">
                          <button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete" @click="remove_from_team(item.id)">
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
  name: "two-lists",
  display: "Two Lists",
  order: 1,
  components: {
    draggable
  },
  data() {
    return {
      selected_qtl:0,
      all_qtl_list:[],
      my_team_list:[],
      all_qa_list:[],
      list2: [],
    };
  },
  mounted() {
        var self = this;
        axios.get('/get_company_qtl_list').then(function (response){
          self.all_qtl_list = response.data.data;
        }).catch(function (error) {
            console.log(error);
        });
        axios.get('/get_company_qa_list').then(function (response){
          self.all_qa_list = response.data.data;
        }).catch(function (error) {
            console.log(error);
        });
  },
  methods: {
    log: function(evt) {
      window.console.log(this.list2);
    },
    checkForm: function (e) {
      e.preventDefault();
      document.getElementById("MySubmitButton").className = "btn btn-primary kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light";
      document.getElementById("MySubmitButton").disabled = true;
      var params = {
      selected_qtl:this.selected_qtl,
      my_team:this.list2
      };
     axios.post('/allocation/store_qtl_qa',params).then(function (response){
      
      document.getElementById("MySubmitButton").className = "btn btn-primary";
      document.getElementById("MySubmitButton").disabled = false;
      window.location = "/allocation/qtl_qa";

      }).catch(function (error) {
          console.log(error);
      });
    },
    onSelectChange: function(e){
        var self = this;
        axios.get('/get_qtl_team_list/'+this.selected_qtl).then(function (response){
          self.my_team_list = response.data.data;
          console.log(self.my_team_list);
        }).catch(function (error) {
            console.log(error);
        });
    },
    remove_from_team: function(qa_id)
    {
      axios.get('/remove_qa_from_qtl_team/'+qa_id).then(function (response){
          window.location = "/allocation/qtl_qa";
        }).catch(function (error) {
            console.log(error);
        });
    }
  }
};
</script>
<style>
  
</style>