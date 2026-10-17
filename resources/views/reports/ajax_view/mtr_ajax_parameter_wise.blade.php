    <div id="dtBasicExample_wrapper" class="dataTables_wrapper dt-bootstrap4">     
    <div class="row">
      <div class="col-sm-12">
      <table id="kt_table_2" class="table table-striped- table-bordered table-hover table-checkable" cellspacing="0" width="100%" role="grid" aria-describedby="dtBasicExample_info" style="width: 100%;">
      <thead>
         <tr class="header blue">
          <th class="th-sm sorting_asc" style="width: 144.2px;">Parameter</th>
           @foreach($final_data['month_list'] as $final)
          <th class="th-sm sorting">{{ $final }}</th>
           @endforeach  
        </tr>       
      </thead>
      <tbody>

       <?php $s=0; foreach($final_data['param_data'] as $data): ?>      
        <tr <?php if($s%2 == 0) { ?> class="even" <?php } else { ?> classs="odd" <?php } ?>>
          <td>{{ $data['name'] }}</td>
           @foreach($final_data['month_list'] as $final)
              <td><?php if($data['name'] != "Overall With Fatal") { echo $data['data'][$final]['score_per']; } else { if($data['data'][$final]['count'] != 0) { echo $m=round($data['data'][$final]['scored']/$data['data'][$final]['count']); } else { echo 0; }  } ?> %</td>
          @endforeach 
        </tr>
        <?php $s++; endforeach; ?>        
      </tbody>
    </table>
  </div>
</div>
</div>
<script src="/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/crud/datatables/basic/scrollable.js" type="text/javascript"></script>
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />

