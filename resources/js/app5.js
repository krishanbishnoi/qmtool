/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Highcharts from 'highcharts'
import HighchartsVue from 'highcharts-vue'
Vue.use(HighchartsVue, {
    highcharts: Highcharts
})
import highchartsMore from 'highcharts/highcharts-more';
import solidGauge from 'highcharts/modules/solid-gauge';


highchartsMore(Highcharts);
solidGauge(Highcharts);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('qtl-qa', require('./components/QtlQaComponent.vue').default);
Vue.component('qa-sheet', require('./components/QaSheetComponent.vue').default);
Vue.component('parents-uploader', require('./components/DumpUploaderParentsComponent.vue').default);
Vue.component('agent-allocation', require('./components/AgentAllocationComponent.vue').default);
Vue.component('audit', require('./components/AuditComponent.vue').default);

Vue.component('client-welcome-dashboard', require('./components/ClientWelcomeDashboard.vue').default);
Vue.component('client-partner-dashboard', require('./components/ClientPartnerDashboard.vue').default);
Vue.component('client-disposition-wise-report', require('./components/ClientDispositionWiseReport.vue').default);
Vue.component('client-agent-wise-report', require('./components/ClientAgentWiseReport.vue').default);
Vue.component('parameter-trending-report', require('./components/ParameterTrendingReport.vue').default);

Vue.component('rebuttal-status', require('./components/RebuttalStatus.vue').default);
Vue.component('clibration-create-process-master', require('./components/ClibrationCreateProcessMaster.vue').default);
Vue.component('calibration-sheet-component', require('./components/CalibrationSheetComponent.vue').default);

Vue.component('qc-sub-parameter-updater', require('./components/QcSubParameterUpdater.vue').default);
Vue.component('raw-dump-filter-component', require('./components/RawDumpFilterComponent.vue').default);

Vue.component('temp-audit-parameter-updater', require('./components/TempAuditParameterUpdate.vue').default);

Vue.component('qa-dashboard', require('./components/QaDashboard.vue').default);
window.Event = new class{
  constructor(){
    this.vue = new Vue();
  }
  fire(event,data=null){
    this.vue.$emit(event,data);
  }
  listen(event, callback){
    this.vue.$on(event,callback);
  }
 }
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = new Vue({
    el: '#app',
    data:{
      logged_in_user_notifications:[],
      selected_qtl:0,
      qc_user_audit_list_client:0,
    },
    computed: {
      // a computed getter
      logged_in_user_total_notifications: function () {
        return this.logged_in_user_notifications.length;
      }
    },
    created(){
          var self = this;
          axios.get('/logged_in_user_total_notifications').then(function (response){
          self.logged_in_user_notifications = response.data.data;
          
          }).catch(function (error) {
            console.log(error);
          });
    },
    methods:{
      redirect_me_noti: function(noti_id,url){
        var params = {
            noti_id:noti_id,
            };  
          axios.post('/mark_read_notification',params).then(function (response){
              console.log(response);
              window.location = "/"+url;
            }).catch(function (error) {
                console.log(error);
            });

          },
          onQmSheetSubParameterDelete: function(pid)
          {
            var self = this;
            axios.get('/delete_sub_parameter/'+pid).then(function (response){
            }).catch(function (error) {
              console.log(error);
            });
          },
          onCalibrationEditDelete: function(cid)
          {
            var self = this;
            axios.get('/delete_calibrator/'+cid).then(function (response){
            }).catch(function (error) {
              console.log(error);
            });
          },
          qc_user_audit_list_client_change: function(){
              if(this.qc_user_audit_list_client!=0)
              window.location = '/qc/audits/'+this.qc_user_audit_list_client;
          },
          onReasonsDelete: function(reason_id){
            var self = this;
            axios.get('/delete_reason_by_id/'+reason_id).then(function (response){
            }).catch(function (error) {
              console.log(error);
            });
          },
          onCircleDelete: function(circle_id){
            var self = this;
            axios.get('/delete_circle_by_id/'+circle_id).then(function (response){
            }).catch(function (error) {
              console.log(error);
            });
          }
      }
});