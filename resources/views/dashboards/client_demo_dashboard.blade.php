@extends('layouts.app_third_new')
<style>
#cover-spin {
    position:fixed;
    width:100%;
    left:0;right:0;top:0;bottom:0;
    background-color: rgba(255,255,255,0.7);
    z-index:9999;
    display:none;
}

@-webkit-keyframes spin {
	from {-webkit-transform:rotate(0deg);}
	to {-webkit-transform:rotate(360deg);}
}

@keyframes spin {
	from {transform:rotate(0deg);}
	to {transform:rotate(360deg);}
}

#cover-spin::after {
    content:'';
    display:block;
    position:absolute;
    left:48%;top:40%;
    width:40px;height:40px;
    border-style:solid;
    border-color:black;
    border-top-color:transparent;
    border-width: 4px;
    border-radius:50%;
    -webkit-animation: spin .8s linear infinite;
    animation: spin .8s linear infinite;
}
</style>
@section('sh-title')
Dashboard
@endsection

@section('sh-detail')
Partner - Location - Process Wise
@endsection
@section('sh-toolbar')
<style>
	.plus,.minus{position: relative;display:block;}
	.icon-change.plus:after,
	.icon-change.minus:after{color: rgb(65, 105, 225);position: absolute;margin-left: 7px;top: 1px;font-weight: bold;}
	.plus:after{content: '+';}
	.minus:after{content: '\2212';top: 1px !important;}
</style>
<div class="kt-subheader__toolbar">
<div class="kt-subheader__wrapper">
@if(Auth::user())
	@if(Auth::user()->hasRole('qtl'))
		<a href="/qtl_dashboard/qtl_dashboard_new" class="btn btn-label-brand btn-bold">QTL Dashboard</a>
	@endif
	<a href="/test_html_new_get" class="btn btn-label-brand btn-bold">Detail Dashboard</a>
@endif
<a href="/test_html_new_get" class="btn btn-label-brand btn-bold">Detail Dashboard</a>
</div>
</div> 
@endsection
@section('main')
<!-- Loading Spinner -->
<div id="cover-spin"></div>

<!-- Dashboard Card -->
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">Dashboard</h3>
        </div>
    </div>

    <div class="kt-portlet__body">
        {{-- Your content goes here --}}
        <p>Welcome to your dashboard!</p>
    </div>
</div>


<div class="kt-portlet kt-portlet--mobile">



<div class="kt-portlet__body">
{{ Form::open(array('url' => 'moneyviewdashboard')) }}
@csrf

<div class="row">

<!--  <div class="col-sm-2">
<div>Select a Process</div>
<select class="form-control"  name="process_id" id="process" onchange="getCycle(this.value);" required>
<option value="0">Select a Process</option>

</select>
</div>-->				

 <div class="col-md-3 mb-3 ">
                        <input type="text" readonly class="form-control" id="kt_datepicker_1" name="start_date"
                            placeholder="Start Date" required="required">
                    </div>
                    <div class="col-md-3 mb-3">
                        <input type="text" readonly class="form-control" id="kt_datepicker_2" name="end_date"
                            placeholder="End Date" required="required">
                    </div>
	
<div class="col-sm-2">
<br>    
<input type="submit" onclick="return check_cycle()" style="width: 100px;" class="btn btn-outline-brand d-block" value="Search">
</div>				
</div>						

{{ Form::close() }}

<!-- <form @submit="get_lob_wise_data">

<div class="row">

<div class="col-md-2">
<button type="submit" class="btn btn-outline-brand"><i class="fa fa-search"></i> Search</button>
</div>

</div>

</form> -->


</div>
</div>



<!--begin:: Widgets/Stats-->
<div class="kt-portlet">
<div class="kt-portlet__body  kt-portlet__body--fit">
<div class="row row-no-padding row-col-separator-xl">
<div class="col-md-12 col-lg-6 col-xl-6">

<!--begin::Total Profit-->
<div class="kt-widget24">
<div class="kt-widget24__details">
<div class="kt-widget24__info">
<h4 class="kt-widget24__title">
All LOB Coverage
</h4>
<span class="kt-widget24__desc">
Target
</span>
</div>
<span class="kt-widget24__stats kt-font-brand">
{{ $final_data['coverage']['target']}}

</span>
</div>
<div class="progress progress--sm">
<div class="progress-bar kt-bg-brand" role="progressbar" style="width: {{$final_data['coverage']['achived_per']}}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<div class="kt-widget24__action">
<span class="kt-widget24__change">
Achieved
</span>
<span class="kt-widget24__number">
{{$final_data['coverage']['achived']}} ({{$final_data['coverage']['achived_per']}} %)
</span>
</div>
</div>
<!--end::Total Profit-->
</div>
<div class="col-md-12 col-lg-6 col-xl-6">

	<div class="kt-widget24">
		<div class="kt-widget24__details">
			<div class="kt-widget24__info">
				<h4 class="kt-widget24__title">
				Rebuttal
				</h4>
				<span class="kt-widget24__desc">Raised</span>
			</div>
			<span class="kt-widget24__stats kt-font-warning">
				{{$final_data['rebuttal']['raised']}} - {{$final_data['rebuttal']['rebuttal_per']}}%
			</span>
		</div>
		<div class="progress progress--sm">
			<div class="progress-bar kt-bg-warning" role="progressbar" style="width: {{$final_data['rebuttal']['rebuttal_per']}}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
		<div class="kt-widget24__action">
			<span class="kt-widget24__change">
			Accepted || Rejected || WIP 
			</span>
			<span class="kt-widget24__number">
			{{$final_data['rebuttal']['accepted']}} - {{$final_data['rebuttal']['accepted_per']}}% || {{$final_data['rebuttal']['rejected']}} - {{$final_data['rebuttal']['rejected_per']}}% ||  {{$final_data['rebuttal']['wip']}} - {{$final_data['rebuttal']['wip_per']}}% 
			</span>
		</div>
		@if(Auth::user())
			@if(Auth::user()->parent_client == 23)
			<div class="kt-widget24__action">
				<span class="kt-widget24__change">
				Unique Rebuttals || Feedback Shared 
				</span>
				<span class="kt-widget24__number">
					{{$final_data['rebuttal']['unque_rebuttal']}} || {{$final_data['coverage']['feedback_shared']}} 
				</span>
			</div>
			@endif
		@endif
	</div>
</div>



</div>
</div>
</div>

<!--end:: Widgets/Stats-->
<div class="row">
<div class="col-xl-6">

<!--begin:: Widgets/Personal Income-->
<div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--height-fluid">
<div class="kt-portlet__head kt-portlet__space-x">
<div class="kt-portlet__head-label">
<h3 class="kt-portlet__head-title kt-font-light">
LOB Wise Score
</h3>
</div>

</div>
<div class="kt-portlet__body">
<div class="kt-widget27">
<div class="kt-widget27__visual">
<img src="/assets/media//bg/bg-4.jpg" alt="" style="height: 80px;">
</div>
<div class="kt-widget27__container kt-portlet__space-x" style="margin:0;">

<ul class="nav nav-pills nav-fill" role="tablist">
<?php $active = 0;
$first_process = 0?>
@foreach($final_data['pws'] as $key => $value)
<?php if($active == 0) $first_process = $key; ?>
<li class="nav-item">
<a class="nav-link <?php if($active == 0) echo " active"; ?>" data-toggle="pill" href="#kt_personal_income_quater_{{$key}}" onclick="return qrc_dynamic({{$key}});" >Demo</a>
</li>
<?php $active ++;?>
@endforeach
</ul>

<div class="tab-content">
<?php $active2 = 0;?>
@foreach($final_data['pws'] as $key => $value)

<div  id="kt_personal_income_quater_{{$key}}" class="tab-pane <?php if($active2 == 0) echo " active"; ?>">
<div class="kt-widget11">
<div class="table-responsive">

<!--begin::Table-->
<table class="table">

<!--begin::Thead-->
<thead>
<tr>
	<td>Head</td>
	<td class="kt-align-right">Value</td>
</tr>
</thead>

<!--end::Thead-->

<!--begin::Tbody-->
<tbody>
<tr>
	<td>
		<a href="#" class="kt-widget11__title">Audit Count</a>
	</td>
	<td class="kt-align-right">{{$value['data']['audit_count']}}</td>
</tr>
<tr>
	<td>
		<a href="#" class="kt-widget11__title" style="color: rgb(65, 105, 225);">Main Score (With fatal)</a>
	</td>
	<td class="kt-align-right kt-font-brand kt-font-bold" style="color: rgb(65, 105, 225);">{{$value['data']['scored_with_fatal']}}%</td>
</tr>
<tr>
	<td>
		<a href="#" class="kt-widget11__title">Score Excl. Fatal Computation</a>
	</td>
	<td class="kt-align-right">{{$value['data']['score']}}%</td>
</tr>
<!-- New added by shailendra pms ticket - 945 -->
<tr>
	<td>
		<a href="#" class="kt-widget11__title" style="color:#ffb822">Total Rebuttal Raised</a>
	</td>
	<td class="kt-align-right kt-font-warning">{{$value['data']['raised_process']}} - {{$value['data']['rebuttal_per']}}%</td>
</tr>
<tr>
	<td>
		<a href="#" class="kt-widget11__title" >Rebuttal Accepted</a>
	</td>
	<td class="kt-align-right kt-font-brand kt-font-bold" >{{$value['data']['accepted_process']}} - {{$value['data']['accepted_per']}}%</td>
</tr>
<tr>
	<td>
		<a href="#" class="kt-widget11__title">Rebuttal Rejected</a>
	</td>
	<td class="kt-align-right">{{$value['data']['rejected_process']}} - {{$value['data']['rejected_per']}}%</td>
</tr>
<!-- New added by shailendra pms ticket - 945 -->
</tbody>

<!--end::Tbody-->
</table>

<!--end::Table-->
</div>
</div>
</div>
<?php $active2 ++;?>
@endforeach


</div>
</div>
</div>
</div>
</div>

<!--end:: Widgets/Personal Income-->
</div>



<div class="col-xl-6">
<!--begin:: Widgets/Personal Income-->
<div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--height-fluid">
<div class="kt-portlet__head kt-portlet__space-x">
<div class="kt-portlet__head-label">
<h3 class="kt-portlet__head-title kt-font-light">
Call Type : As Per System
</h3>
</div>

</div>
<div class="kt-portlet__body">
<div class="kt-widget27">
<div class="kt-widget27__visual">
<img src="/assets/media//misc/bg-2.jpg" alt="" style="height: 80px;">
</div>
<div class="kt-widget27__container kt-portlet__space-x" style="margin:0;">
<ul class="nav nav-pills nav-fill" role="tablist">
<?php $s=0; $count =1?>


	@foreach($final_data['call_type'] as $ct)
	<li class="nav-item">
	<?php if($client_id == 1) {
		if($count <= 2){
			?>
		<a <?php if($s == 0) { ?> class="nav-link active" <?php } else { ?> class="nav-link" <?php } ?> data-toggle="pill" <?php if($s == 0) { ?> href="#kt_personal_income_quater_qrc_q" <?php } if($s == 1) { ?> href="#kt_personal_income_quater_qrc_r" <?php } if($s == 2) { ?> href="#kt_personal_income_quater_qrc_c"  <?php }  ?> >{{ $ct }}</a>
			<?php
		}
	} else{
		?>
		<a <?php if($s == 0) { ?> class="nav-link active" <?php } else { ?> class="nav-link" <?php } ?> data-toggle="pill" <?php if($s == 0) { ?> href="#kt_personal_income_quater_qrc_q" <?php } if($s == 1) { ?> href="#kt_personal_income_quater_qrc_r" <?php } if($s == 2) { ?> href="#kt_personal_income_quater_qrc_c"  <?php }  ?> >{{ $ct }}</a>
		<?php
	}

	?>
	
	</li>
	<?php
		$count++;
		$s++; 
	
	?>
	@endforeach


</ul>
<div class="tab-content">
<?php $s=0; ?>
@foreach($final_data['call_type'] as $ct)
<div <?php if($s == 0) { ?> id="kt_personal_income_quater_qrc_q" <?php } if($s == 1) { ?> id="kt_personal_income_quater_qrc_r" <?php } if($s == 2) { ?> id="kt_personal_income_quater_qrc_c" <?php } if($s == 0) { ?> class="tab-pane active" <?php } else { ?> class="tab-pane fade" <?php } ?>>
<div class="kt-widget11">
<div class="table-responsive">

<!--begin::Table-->
<table class="table">

<!--begin::Thead-->
<thead>
<tr>
	<td>Head</td>
	<td class="kt-align-right">Count</td>
</tr>
</thead>

<!--end::Thead-->

<!--begin::Tbody-->
<tbody>
<tr>
	<td>
		<a href="#" class="kt-widget11__title">Audit Count</a>
	</td>
	<td class="kt-align-right kt-font-brand kt-font-bold" id = "<?php if($s == 0) { echo 'query_count'; } if($s==1) { echo 'request_count'; } if($s==2) { echo 'complaint_count'; } ?>"><?php if($s==0) { echo $final_data['qrc']['query_count']; } if($s==1) { echo $final_data['qrc']['request_count']; } if($s==2) { echo $final_data['qrc']['complaint_count']; } ?></td>
</tr>
<tr>
	<td>
		<a href="#" class="kt-widget11__title kt-font-danger">FATAL Count</a>
	</td>
	<td class="kt-align-right kt-font-brand kt-font-bold kt-font-danger" id = "<?php if($s == 0) { echo 'query_fatal_count'; } if($s==1) { echo 'request_fatal_count'; } if($s==2) { echo 'complaint_fatal_count'; } ?>">
		<?php if($s==0) { echo $final_data['qrc']['query_fatal_count']; } if($s==1) { echo $final_data['qrc']['request_fatal_count']; } if($s==2) { echo $final_data['qrc']['complaint_fatal_count']; } ?>
	</td>
</tr>
<!-- New added by shailendra pms ticket - 945 -->
<tr>
	<td>
		<a href="#" class="kt-widget11__title" style="color:#ffb822">Total Rebuttal Raised</a>
	</td>
	<td class="kt-align-right kt-font-brand kt-font-bold kt-font-warning" style="color:#ffb822" id = "<?php if($s == 0) { echo 'query_rebuttal_per'; } if($s==1) { echo 'request_rebuttal_per'; } if($s==2) { echo 'complain_rebuttal_per'; } ?>"><?php if($s==0) { echo $final_data['qrc']['query_raised_process']." - ". $final_data['qrc']['query_rebuttal_per']. "%"; } if($s==1) { echo $final_data['qrc']['request_rebuttal_per']; } if($s==2) { echo $final_data['qrc']['complain_rebuttal_per']; } ?></td>
</tr>
<tr>
	<td>
		<a href="#" class="kt-widget11__title">Rebuttal Accepted</a>
	</td>
	<td class="kt-align-right kt-font-brand kt-font-bold" id = "<?php if($s == 0) { echo 'query_accepted_per'; } if($s==1) { echo 'request_accepted_per'; } if($s==2) { echo 'complain_accepted_per'; } ?>">
		<?php if($s==0) { echo $final_data['qrc']['query_accepted_per']; } if($s==1) { echo $final_data['qrc']['request_accepted_per']; } if($s==2) { echo $final_data['qrc']['complain_accepted_per']; } ?>
	</td>
</tr>
<tr>
	<td>
		<a href="#" class="kt-widget11__title">Rebuttal Rejected</a>
	</td>
	<td class="kt-align-right kt-font-brand kt-font-bold" id = "<?php if($s == 0) { echo 'query_rejected_per'; } if($s==1) { echo 'request_rejected_per'; } if($s==2) { echo 'complain_rejected_per'; } ?>">
		<?php if($s==0) { echo $final_data['qrc']['query_rejected_per']; } if($s==1) { echo $final_data['qrc']['request_rejected_per']; } if($s==2) { echo $final_data['qrc']['complain_rejected_per']; } ?></td>
</tr>
<!-- New added by shailendra pms ticket - 945 -->
</tbody>

<!--end::Tbody-->
</table>

<!--end::Table-->
</div>
</div>
</div>
<?php $s++; ?>
@endforeach

<!--<div id="kt_personal_income_quater_qrc_r" class="tab-pane fade">
<div class="kt-widget11">
<div class="table-responsive">


<table class="table">


<thead>
<tr>
	<td>Application</td>
	<td class="kt-align-right">Total</td>
</tr>
</thead>



<tbody>
<tr>
	<td>
		<a href="#" class="kt-widget11__title">Audit Count</a>
	</td>
	<td class="kt-align-right kt-font-brand kt-font-bold" id = "request_count">{{$final_data['qrc']['request_count']}}</td>
</tr>
<tr>
	<td>
		<a href="#" class="kt-widget11__title kt-font-danger">FATAL Count</a>
	</td>
	<td class="kt-align-right kt-font-brand kt-font-bold kt-font-danger" id = "request_fatal_count">{{$final_data['qrc']['request_fatal_count']}}</td>
</tr>
</tbody>


</table>

</div>
</div>
</div>
<div id="kt_personal_income_quater_qrc_c" class="tab-pane fade">
<div class="kt-widget11">
<div class="table-responsive">


<table class="table">

<thead>
<tr>
	<td>Head</td>
	<td class="kt-align-right">Count</td>
</tr>
</thead>

<tbody>
<tr>
	<td>
		<a href="#" class="kt-widget11__title">Audit Count</a>
	</td>
	<td class="kt-align-right kt-font-brand kt-font-bold" id = "complaint_count">{{$final_data['qrc']['complaint_count']}}</td>
</tr>
<tr>
	<td>
		<a href="#" class="kt-widget11__title kt-font-danger">FATAL Count</a>
	</td>
	<td class="kt-align-right kt-font-brand kt-font-bold kt-font-danger" id = "complaint_fatal_count">{{$final_data['qrc']['complaint_fatal_count']}}</td>
</tr>
</tbody>


</table>

</div>
</div>
</div>-->

</div>
</div>
</div>
</div>
</div>

<!--end:: Widgets/Personal Income-->
</div>
</div>

<div class="row">
<div class="col-xl-12">
<div class="kt-portlet kt-portlet--mobile">
<div class="kt-portlet__head kt-portlet__head--lg">
<div class="kt-portlet__head-label">
<span class="kt-portlet__head-icon">
<i class="kt-font-brand flaticon2-line-chart"></i>
</span>
<h3 class="kt-portlet__head-title">
Partner & Location Wise Report
</h3>
</div>
<div class="kt-portlet__head-toolbar">
<div class="kt-portlet__head-wrapper">
<div class="kt-portlet__head-actions">
</div>
</div>
</div>
</div>
<div class="kt-portlet__body">

<!--begin: Datatable -->
<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
<thead>
<tr>

<th>Partner</th>
<th>Audit Count</th>
<th style="color: rgb(65, 105, 225);">With Fatal</th>
<th>Without Fatal</th>
</tr>
</thead>
<tbody>
<?php $m=0; ?>
@foreach($final_data['plr'] as $p)
@if($p['audit_count'] != 0)
<tr>
	<td><a href="javascript:getTable(<?=$m; ?>);" class="icon-change plus" onclick="myFunction(this)" >Demo</a></td>
	<td>{{$p['audit_count']}}</td>
	<td style="color: rgb(65, 105, 225);">{{$p['with_fatal']}} %</td>
	<td>{{$p['without_fatal']}} %</td>
</tr>
<tr>	
	<td id="pro_<?=$m; ?>" style="display: none;">
		<table>
			<thead>
				<tr>
					<th>Process</th>
					<th>Location</th>
					<th>Audit Count</th>
					<th style="color: rgb(65, 105, 225);">With Fatal</th>
					<th>Without Fatal</th>
				</tr>
			</thead>
			<tbody>
				@foreach($p['process_data'] as $pro)
					
					<tr>
						<td>{{'demo'}}</td>
						<td>{{$pro['location']}}</td>
						<td>{{$pro['audit_count']}}</td>
						<td style="color: rgb(65, 105, 225);">{{$pro['with_fatal']}} %</td>
						<td>{{$pro['without_fatal']}} %</td>
					</tr>
					
				@endforeach
			</tbody>
		</table>
	</td>
</tr>
@endif	
<?php $m++; ?>

@endforeach

</tbody>
</table>

<!--end: Datatable -->
</div>
</div>

</div>
</div>




@endsection
@section('js')
<script>
function myFunction(x) {
  	x.classList.toggle("minus");
}

function getTable(id) {
	$("#pro_"+id).toggle();
}
(function() {
/* $('#cover-spin').show(0); */
var base_url = window.location.origin;
$.ajax({
type: "GET",
url: base_url + "/get_qrc_lob_wise_welcome_dashboard/{{$first_process}}/{{$month_first_data}}/{{$today}}",
success: function(Data){


$("#query_count").html(Data.qrc.query_count);
$("#query_fatal_count").html(Data.qrc.query_fatal_count);
$("#query_rebuttal_per").html(Data.qrc.query_raised_process + " - " + Data.qrc.query_rebuttal_per + "%");
$("#query_accepted_per").html(Data.qrc.query_accepted_process + " - " + Data.qrc.query_accepted_per + "%");
$("#query_rejected_per").html(Data.qrc.query_rejected_process + " - " + Data.qrc.query_rejected_per + "%");

<?php 
	if($client_id==1){
		?>
		$("#request_count").html(Data.qrc.request_count+Data.qrc.complaint_count);
		$("#request_fatal_count").html(Data.qrc.request_fatal_count+Data.qrc.complaint_fatal_count);
		$("#request_rebuttal_per").html((Data.qrc.request_raised_process + Data.qrc.complain_raised_process) + " - " + ( Data.qrc.request_rebuttal_per + Data.qrc.complain_rebuttal_per) + "%");
		$("#request_accepted_per").html((Data.qrc.request_accepted_process+Data.qrc.complain_accepted_process) + " - " + (Data.qrc.request_accepted_per+Data.qrc.complain_accepted_per) + "%");
		$("#request_rejected_per").html((Data.qrc.request_rejected_process+Data.qrc.complain_rejected_process) + " - " + (Data.qrc.request_rejected_per+Data.qrc.complain_rejected_per) + "%");
<?php
	}else{
		?>
		$("#request_count").html(Data.qrc.request_count);
		$("#request_fatal_count").html(Data.qrc.request_fatal_count);
		$("#request_rebuttal_per").html(Data.qrc.request_raised_process + " - " + Data.qrc.request_rebuttal_per + "%");
		$("#request_accepted_per").html(Data.qrc.request_accepted_process + " - " + Data.qrc.request_accepted_per + "%");
		$("#request_rejected_per").html(Data.qrc.request_rejected_process + " - " + Data.qrc.request_rejected_per + "%");


		$("#complaint_count").html(Data.qrc.complaint_count);
		$("#complaint_fatal_count").html(Data.qrc.complaint_fatal_count);
		$("#complain_rebuttal_per").html(Data.qrc.complain_raised_process + " - " + Data.qrc.complain_rebuttal_per + "%");
		$("#complain_accepted_per").html(Data.qrc.complain_accepted_process + " - " + Data.qrc.complain_accepted_per + "%");
		$("#complain_rejected_per").html(Data.qrc.complain_rejected_process + " - " + Data.qrc.complain_rejected_per + "%");

		<?php
	}
?>

/* $('#cover-spin').hide(); */
//$("#kt_table_1").html(Data.html);
}
});
})();

function qrc_dynamic(val){
/* $('#cover-spin').show(0); */

var base_url = window.location.origin;
$.ajax({
type: "GET",
url: base_url + "/get_qrc_lob_wise_welcome_dashboard/"+val+"/{{$month_first_data}}/{{$today}}",
success: function(Data){


console.log(Data.qrc.query_count);

$("#query_count").html(Data.qrc.query_count);
$("#query_fatal_count").html(Data.qrc.query_fatal_count);
$("#query_rebuttal_per").html(Data.qrc.query_raised_process + " - " + Data.qrc.query_rebuttal_per + "%");
$("#query_accepted_per").html(Data.qrc.query_accepted_process + " - " + Data.qrc.query_accepted_per + "%");
$("#query_rejected_per").html(Data.qrc.query_rejected_process + " - " + Data.qrc.query_rejected_per + "%");

<?php 
	if($client_id==1){
		?>
		$("#request_count").html(Data.qrc.request_count+Data.qrc.complaint_count);
		$("#request_fatal_count").html(Data.qrc.request_fatal_count+Data.qrc.complaint_fatal_count);
		$("#request_rebuttal_per").html((Data.qrc.request_raised_process + Data.qrc.complain_raised_process) + " - " + ( Data.qrc.request_rebuttal_per + Data.qrc.complain_rebuttal_per) + "%");
		$("#request_accepted_per").html((Data.qrc.request_accepted_process+Data.qrc.complain_accepted_process) + " - " + (Data.qrc.request_accepted_per+Data.qrc.complain_accepted_per) + "%");
		$("#request_rejected_per").html((Data.qrc.request_rejected_process+Data.qrc.complain_rejected_process) + " - " + (Data.qrc.request_rejected_per+Data.qrc.complain_rejected_per) + "%");
<?php
	}else{
		?>
		$("#request_count").html(Data.qrc.request_count);
		$("#request_fatal_count").html(Data.qrc.request_fatal_count);
		$("#request_rebuttal_per").html(Data.qrc.request_raised_process + " - " + Data.qrc.request_rebuttal_per + "%");
		$("#request_accepted_per").html(Data.qrc.request_accepted_process + " - " + Data.qrc.request_accepted_per + "%");
		$("#request_rejected_per").html(Data.qrc.request_rejected_process + " - " + Data.qrc.request_rejected_per + "%");


		$("#complaint_count").html(Data.qrc.complaint_count);
		$("#complaint_fatal_count").html(Data.qrc.complaint_fatal_count);
		$("#complain_rebuttal_per").html(Data.qrc.complain_raised_process + " - " + Data.qrc.complain_rebuttal_per + "%");
		$("#complain_accepted_per").html(Data.qrc.complain_accepted_process + " - " + Data.qrc.complain_accepted_per + "%");
		$("#complain_rejected_per").html(Data.qrc.complain_rejected_process + " - " + Data.qrc.complain_rejected_per + "%");

		<?php
	}
?>
//$("#kt_table_1").html(Data.html);
/* $('#cover-spin').hide(); */
}
});


return true;
}

function getCycle(val) {

var base_url = window.location.origin;
if(val != 0) {
$.ajax({
type: "GET",
url: base_url + "/dashboard/get_partner_audit_cycle/"+val,
success: function(Data){
$("#audit_cycle").html(Data);
}
});


}
}
function getLocation(val) {

var base_url = window.location.origin;
if(val != 0) {
$.ajax({
type: "GET",
url: base_url + "/dashboard/get_partner_locations1/"+val,
success: function(Data){
$("#location").html(Data+'<option value="%">All</option>');
}
});

$.ajax({
type: "GET",
url: base_url + "/dashboard/get_partner_process1/"+val,
success: function(Data){
$("#process").html(Data);
}
});

$.ajax({
type: "GET",
url: base_url + "/dashboard/get_partner_lob/"+val,
success: function(Data){
$("#lob").html(Data+'<option value="%">All</option>');
}
});
}
}

function check_cycle(){
	var cycle = document.getElementById("audit_cycle").value;
	if(cycle == "blank"){
		document.getElementById("warning").style.display = "block";
		//document.getElementById("audit_cycle").value
		return false;
	}
	else {
		return true;
	}
	//console.log(cycle);
	
}
</script>
 <script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-daterangepicker.js" type="text/javascript">
@endsection
@section('css')
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
