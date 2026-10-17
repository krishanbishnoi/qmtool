<div id="dtBasicExample_wrapper" class="dataTables_wrapper dt-bootstrap4">
   <div class="row">
      <div class="col-sm-12">
         <table class="table table-striped- table-bordered table-hover table-checkable" cellspacing="0" width="100%" role="grid" aria-describedby="dtBasicExample_info" style="width: 100%;">
            <thead>
               <tr>
                  <th width="50%" style="background:#5e79ff; color:white;"><b>Rebuttal Summary</b></th>
                  <th style="background:#5e79ff; color:white;"><b>Total</b></th>
                  <th style="background:#5e79ff; color:white;"><b>Percentage</b></th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td>Raised</td>
                  <td>{{$final_data['raised']}}</td>
                  <td>{{$final_data['raised_per']}}%</td>
               </tr>
               <tr>
                  <td>Accepted</td>
                  <td>{{$final_data['accepted']}}</td>
                  <td>{{$final_data['accepted_per']}}%</td>
               </tr>
               <tr>
                  <td>Rejected</td>
                  <td>{{$final_data['rejected']}}</td>
                  <td>{{$final_data['rejected_per']}}%</td>
               </tr>
               <tr>
                  <td>WIP</td>
                  <td>{{$final_data['wip']}}</td>
                  <td>{{$final_data['wip_per']}}%</td>
               </tr>
            </tbody>
         </table>
      </div>

   </div>   
        
     
         <div class="kt-portlet__head" style="margin-left: -25px; margin-bottom: 25px;">
            <div class="kt-portlet__head-label">
               <h3 class="kt-portlet__head-title">
                  Report :- <span class="text-danger">Overall Rebuttal Partner Bucket</span>
               </h3>
            </div>
         </div>
      <div class="col-sm-12">
         <table class="table table-striped- table-bordered table-hover table-checkable" cellspacing="0" width="100%" role="grid" aria-describedby="dtBasicExample_info" style="width: 100%;">
            <thead>
               <tr>
                  <th width="50%" style="background:#5e79ff; color:white;"><b>Partner Bucket</b></th>
                  <th style="background:#5e79ff; color:white;"><b>Total</b></th>
                  <th style="background:#5e79ff; color:white;"><b>Percentage</b></th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td>Seen</td>
                  <td>{{$partner_bucket['Seen']}}</td>
                  <td>{{$partner_bucket['Seen_per']}} %</td>
               </tr>
               <tr>
                  <td>Un-seen</td>
                  <td>{{ $partner_bucket['Un-seen']}}</td>
                  <td>{{$partner_bucket['Un-seen_per']}} %</td>
               </tr>
               <tr>
                  <td>Rebuttal Raised (Call Count)</td>
                  <td>{{$partner_bucket['Raised']}}</td>
                  <td>{{$partner_bucket['Raised_per']}} %</td>
               </tr>
               <tr>
                  <td>Re-Rebuttal Raised</td>
                  <td>{{$partner_bucket['Re-Rebuttal Raised']}}</td>
                  <td>{{$partner_bucket['Re-Rebuttal-Raised-per']}} %</td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
 
<script src="/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/crud/datatables/basic/scrollable.js" type="text/javascript"></script>
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />