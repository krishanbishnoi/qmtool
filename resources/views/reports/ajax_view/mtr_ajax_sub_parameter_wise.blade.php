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
            <?php foreach($final_data['param_data'] as $param_data): 
            
            foreach($param_data['sub_parameter_detail'] as $sub_parameter_detail):  ?>

            <?php if($sub_parameter_detail['fatal'] == 1){
              $color = "rgb(65, 105, 225)";
            }else {
              $color = "black";
            }
            ?>
            <tr>
            <td style="color:{{$color}}">{{ $sub_parameter_detail['name'] }}</td>
            <?php foreach($final_data['month_list'] as $final): ?>
            <td style="color:{{$color}}">{{ $sub_parameter_detail['scored'][$final] }} %</td>
            
            <?php endforeach; ?> </tr>
            <?php endforeach;  ?>
            <tr class="lightred">
            <td><b>{{ $param_data['parameter_name'] }}</b></td>
             <?php foreach($final_data['month_list'] as $final): ?>
            <td>{{ $param_data['scored'][$final] }} %</td>
           
           <?php endforeach; ?>  </tr>
            <?php endforeach;  ?>
            <tr class="yellow">
              <td><b>Overall With Fatal</b></td>
            <?php foreach($final_data['month_list'] as $final): ?>
              <td> {{ $final_data['overall_with_fatal'][$final] }} %</td>
            <?php endforeach; ?>
            </tr>
            <tr class="pink">
              <td><b>Overall Without Fatal</b></td>
             <?php foreach($final_data['month_list'] as $final): ?>
              <td> {{ $final_data['overall_without_fatal'][$final] }} %</td>
            <?php endforeach; ?>
            </tr>
      </tbody>      

    </table>
  </div>
</div>
</div>
<script src="/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/crud/datatables/basic/scrollable.js" type="text/javascript"></script>
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />

