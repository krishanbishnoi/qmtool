@extends('layouts.app')
<link href="/month_picker/monthpicker.css" rel="stylesheet" type="text/css">
<style> 
.hide_1{
			display:none;
		}
</style>

@section('sh-title')
QA Report
@endsection

@section('sh-detail')
List QA Performance Matrix 
@endsection

@section('main')

<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head kt-portlet__head--lg">
		<div class="kt-portlet__head-label">
			<span class="kt-portlet__head-icon">
				<i class="kt-font-brand flaticon2-line-chart"></i>
			</span>
			<h3 class="kt-portlet__head-title">
				List
			</h3>
		</div>
		
	</div>

    {!! Form::open(
                array(
                'route' => 'qa_performance_matrix', 
                'class' => 'kt-form',
                'role'=>'form',
                'data-toggle'=>"validator",
                'enctype'=>'multipart/form-data')
                ) !!}
        
		<div class="kt-portlet__body">
            <div class = "row">
                <div class="form-group col-md-6">
                    <label>Month*</label>
                    <input type="text" class="form-control"  id="demo-1" name = "target_month" required="required">
                </div>
            </div>
            <div class="kt-form__actions">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
        <div class="kt-portlet__foot">
        </div>
      </form>

	<div class="kt-portlet__body">

		<?php 
			$rank = array();
			foreach($data as $row) {
				
				$audit_target = $row->qa_target;
				$audit_achievement = $row->audits;
				$audit_achievement_percent = 0;
				$audit_score = 0;
				$audit_bonus = 0;

				if($row->qa_target != 0) {
					$audit_achievement_percent = number_format(($row->audits/$row->qa_target) * 100, 2, '.', ',');
					$audit_score = number_format( (($row->audits/$row->qa_target )* 100*35)/100, 2, '.', ',');
					
					$audit_bonus = ($audit_score - 35) * 100/35;
					if($audit_bonus >= 5){
						$audit_bonus = 5;
					}
					else {
						$audit_bonus = 0;
					}
			
				}

				$qc_audits = $row->qc_audits;
				$qc_deviation = 0;
				$qc_score_score = 0;
				$qc_bonus_bonus = 0;

				if($row->audits != 0){
					$qc_deviation = number_format(($row->qc_audits/ $row->audits) * 100, 2, '.', ',');
					
					if($qc_deviation <= 5){
						$qc_score_score = 15;
						if($qc_deviation == 0) {
							$qc_bonus_bonus = 5;
						}else {
							$qc_bonus_bonus = 0;
						}
					}
					else{
						$qc_score_score = 0;
					}
				}

				$rebuttal_accept = $row->rebuttal_accept;
				$rebuttal_accept_percent = 0;
				$rebuttal_score = 0;
				$rebuttal_bonus = 0;

				if($row->audits != 0){
					$rebuttal_accept_percent = number_format(($row->rebuttal_accept/ $row->audits) * 100, 2, '.', ',');
					
					if($rebuttal_accept_percent <= 2){
						$rebuttal_score = 20;
						if($rebuttal_accept_percent == 0) {
							$rebuttal_bonus = 5;
						}else {
							$rebuttal_bonus = 0;
						}
					}
					else{
						$rebuttal_score = 0;
					}
				}

				$training_pkt = $row->training_pkt;
				$training_pkt_score = 0;
				$training_pkt_bonus = 0;

				
				if($training_pkt > 85){
					$training_pkt_score = 10;
					if($training_pkt == 100) {
						$training_pkt_bonus = 5;
					}else {
						$training_pkt_bonus = 0;
					}
				}
				else{
					$training_pkt_score = 0;
				}
				
				$calibration = 0;
				$calibration_score = 0;
				$calibration_bonus = 0;

				$rank[$row->id] = $audit_score + $audit_bonus + $qc_score_score + $qc_bonus_bonus + $rebuttal_score + $rebuttal_bonus + $training_pkt_score + $training_pkt_bonus + $calibration_score + $calibration_bonus;

			}
			
			arsort($rank);

			
		?>
			
		<!--begin: Datatable -->
		<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
			<thead>
				<tr>
					<th title="Field #1">#</th>
					<th title="Field #1">
						Name
					</th>

					<th title="Field #1">
						Audit Target score
					</th>
					<th title="Field #1">
						QC Deviation score
					</th>
					<th title="Field #1">
						Rebuttal score 
					</th>
					<th title="Field #2">
						Training PKT sore
					</th>

					<th title="Field #2">
						Internal call calibration Score
					</th>
					<th title="Field #2">
						Total Score
					</th>
					<th title="Field #2" class = "hide_1">
						Total Bonus
					</th>
					<th title="Field #2">
						Total Score with bonus
					</th>
					<th title="Field #2">
						Rank
					</th>
					<th title="Field #2">
						Audit Target 
					</th>
					<th title="Field #2">
						Audit Target Achievement
					</th>
					<th title="Field #2">
						Audit Target Achievement %
					</th>
					
					<th title="Field #2">
					Audit Target Bonus
					</th>
					<th title="Field #2">
						QC Deviation
					</th>
					
					<th title="Field #2">
						QC Deviation bonus
					</th>
					<th title="Field #2" class = "hide_1">
						Rebuttal Accepted
					</th>
					<th title="Field #2">
						Rebuttal Accepted %
					</th>
					
					<th title="Field #2">
						Rebuttal bonus
					</th>
					<th title="Field #2">
						Training PKT 
					</th>
					
					<th title="Field #2">
						Training PKT bonus
					</th>
					<th title="Field #2">
						Internal call calibration deviation
					</th>
					
					<th title="Field #2">
						Internal call calibration Bonus
					</th>
					
					

				</tr>
			</thead>
			<tbody>
				

				@foreach($data as $row)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td>
						{{$row->name}}
					</td>
					
					<?php 
						$audit_target = $row->qa_target;
						$audit_achievement = $row->audits;
						$audit_achievement_percent = 0;
						$audit_score = 0;
						$audit_bonus = 0;

						if($row->qa_target != 0) {
							$audit_achievement_percent = number_format(($row->audits/$row->qa_target) * 100, 2, '.', ',');
							$audit_score = number_format( (($row->audits/$row->qa_target )* 100*35)/100, 2, '.', ',');
							
							$audit_bonus = ($audit_score - 35) * 100/35;
							if($audit_bonus >= 5){
								$audit_bonus = 5;
							}
							else {
								$audit_bonus = 0;
							}
					
						}

						$qc_audits = $row->qc_audits;
						$qc_deviation = 0;
						$qc_score_score = 0;
						$qc_bonus_bonus = 0;

						if($row->audits != 0){
							$qc_deviation = number_format(($row->qc_audits/ $row->audits) * 100, 2, '.', ',');
							
							if($qc_deviation <= 5){
								$qc_score_score = 15;
								if($qc_deviation == 0) {
									$qc_bonus_bonus = 5;
								}else {
									$qc_bonus_bonus = 0;
								}
							}
							else{
								$qc_score_score = 0;
							}
						}

						$rebuttal_accept = $row->rebuttal_accept;
						$rebuttal_accept_percent = 0;
						$rebuttal_score = 0;
						$rebuttal_bonus = 0;

						if($row->audits != 0){
							$rebuttal_accept_percent = number_format(($row->rebuttal_accept/ $row->audits) * 100, 2, '.', ',');
							
							if($rebuttal_accept_percent <= 2){
								$rebuttal_score = 20;
								if($rebuttal_accept_percent == 0) {
									$rebuttal_bonus = 5;
								}else {
									$rebuttal_bonus = 0;
								}
							}
							else{
								$rebuttal_score = 0;
							}
						}

						$training_pkt = $row->training_pkt;
						$training_pkt_score = 0;
						$training_pkt_bonus = 0;

						
						if($training_pkt > 85){
							$training_pkt_score = 10;
							if($training_pkt == 100) {
								$training_pkt_bonus = 5;
							}else {
								$training_pkt_bonus = 0;
							}
						}
						else{
							$training_pkt_score = 0;
						}
						
						$calibration = 0;
						$calibration_score = 0;
						$calibration_bonus = 0;
						$total_score = $audit_score + $qc_score_score + $rebuttal_score + $training_pkt_score + $calibration_score;
						$total_bonus = $audit_bonus + $qc_bonus_bonus + $rebuttal_bonus + $training_pkt_bonus + $calibration_bonus;
						$total_score_with_bonus = $audit_score + $audit_bonus + $qc_score_score + $qc_bonus_bonus + $rebuttal_score + $rebuttal_bonus + $training_pkt_score + $training_pkt_bonus + $calibration_score + $calibration_bonus;
					?>
					<td>
						{{$audit_score}}
					</td>
					<td>
						{{ $qc_score_score }}
					</td>
					<td>
						{{$rebuttal_score}}
					</td>
					<td>
						{{ $training_pkt_score }}
					</td>
					<td>
						{{ $calibration_score }}
					</td>
					<td>
						{{ $total_score }}
					</td >
					<td class = "hide_1"> 
						{{$total_bonus}}
					</td>
					<td>
						{{$total_score_with_bonus}}
					</td>
					<td>
						@php($j = 1)
						@foreach($rank as $key=>$value)
							@if($key == $row->id) 
								{{ $j }}
							@endif
							@php($j++)
						@endforeach
					</td>
					
					<td>
						{{$audit_target}}
					</td>
					<td>
						{{$audit_achievement}}
					</td>
					<td>
						{{$audit_achievement_percent}}
					</td>
					
					<td>
						{{$audit_bonus}}
					</td>
					<td>
						{{ $qc_deviation }}
					</td>
					
					<td>
						{{ $qc_bonus_bonus }}
					</td>
					
                    <td class = "hide_1">
						{{$rebuttal_accept}}
					</td>
					<td>
						{{$rebuttal_accept_percent}}
					</td>
					
					<td>
						{{$rebuttal_bonus}}
					</td>
					<td>
						{{ $training_pkt }}
					</td>
					
					<td>
						{{ $training_pkt_bonus }}
					</td>

					<td>
						{{ $calibration }}
					</td>
					
					<td>
						{{ $calibration_bonus }}
					</td>
					
					
            	</tr>
            @endforeach
        </tbody>
    </table>

    <!--end: Datatable -->
</div>
</div>
@endsection
@section('css')
@include('shared.table_css')
@endsection
@section('js')
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="/month_picker/monthpicker.min.js"></script>
<script>
$('#demo-1').Monthpicker();

</script>
@include('shared.table_js')

@endsection