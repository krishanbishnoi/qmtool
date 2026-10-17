    <div id="dtBasicExample_wrapper" class="dataTables_wrapper dt-bootstrap4">     
    <div class="row">
      <div class="col-sm-12">
      <table class="table table-striped- table-bordered table-hover table-checkable" cellspacing="0" width="100%" role="grid" aria-describedby="dtBasicExample_info" style="width: 100%;" id="kt_table_2">
      <thead>
         <tr class="blue">
          <th>Agents</th>
           @foreach($final_data['month_list'] as $final)
          <th>{{ $final }}</th>
           @endforeach  
        </tr>       
      </thead>
      <tbody>
        <?php foreach($final_data['unique_agents'] as $key=>$agent){ ?>
          <tr>
          <td> <b> {{ $agent }}</b> </td>
          <?php foreach($final_data['month_list'] as $final) { ?>
          <td> {{ $final_data['param_data'][$key]['scored'][$final] }} %</td>
          <?php } ?> </tr> <?php } ?>         
      </tbody>
    </table>
  </div>
</div>
</div>
<script src="/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/crud/datatables/basic/scrollable.js" type="text/javascript"></script>
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
