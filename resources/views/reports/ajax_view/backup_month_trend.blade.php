<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
						<thead>
							<tr class="kt-bg-brand kt-font-light">
						      <th class="th-sm">Parameter</th>
						      @foreach($final_data['month_list'] as $final)
                                <th class="th-sm">{{ $final }}</th>
						      @endforeach						      
						    </tr>						
						</thead>
						<tbody>
						  @foreach($final_data['param_data'] as $data)
						  <tr>	
						      <th scope="row">{{ $data['name'] }}</th>
						      @foreach($final_data['month_list'] as $final)
                              <td>{{ $data['data'][$final]['score_per'] }} %</td>
						      @endforeach	
						  </tr>
						   @endforeach		
						</tbody>
						
</table>