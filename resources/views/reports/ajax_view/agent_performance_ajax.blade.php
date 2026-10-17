<table id="kt_table_2" class="table table-striped- table-bordered table-hover table-checkable">
						<thead>
							<tr class="blue">
								<th>Emp Id</th>
								<th>Name</th>
							    <th>Audit Count</th>
							    <th>Bucket</th>
							    <th>Score</th>
							</tr>							
						</thead>
						<tbody>
						  <?php foreach($new_ar as $ar): ?>
						  
                            <tr> 
                            	<td>{{ $ar['emp_id'] }}</td>
                            	<td>{{ $ar['name'] }}</td>
                            	<td>{{ $ar['audit_count'] }}</td>
                            	<td>{{ $ar['bucket'] }}</td>
                            	<td>{{ $ar['score'] }}%</td>
                            </tr>                           
						  <?php  endforeach;		?>				
						</tbody>
						
</table>
<script src="/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/crud/datatables/basic/scrollable.js" type="text/javascript"></script>
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
