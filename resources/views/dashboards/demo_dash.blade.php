@extends('layouts.app_third')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<style>




body,
html {
padding: 0;
margin: 0;
}

.label {
text-align:left;
width:100px;
padding-right:250px;
/*width:500px;*/
/*word-wrap: normal;
*/
}


.col-lg-6 {
-webkit-box-flex: 0;
-ms-flex: 0 0 50%;
flex: 0 0 50%;
max-width: 50%;
}

.col-lg-1,
.col-lg-10,
.col-lg-11,
.col-lg-12,
.col-lg-2,
.col-lg-3,
.col-lg-4,
.col-lg-5,
.col-lg-6,
.col-lg-7,
.col-lg-8,
.col-lg-9 {
float: left;
}


.row {
display: -webkit-box;
display: -ms-flexbox;
display: flex;
-ms-flex-wrap: wrap;
flex-wrap: wrap;
margin-right: -10px;
margin-left: -10px;
}

.col-lg-6 {
width: 50%;
}

.container-fluid {
padding-right: 15px;
padding-left: 15px;
margin-right: auto;
margin-left: auto;
}

.col-lg-12 {
width: 100%;
}

.whitebox {
background: white;
border-radius: 4px;
box-shadow: 0 0 10px rgba(0, 0, 0, .2);
padding: 20px;
margin-top: 10px;
}

.whitebox h1 {
font-size: 1.5em;
border-bottom: solid 1px #ddd;
line-height: 1.5em;
margin: 0;
margin-bottom: 20px;
padding-bottom: 10px;
}

.highcharts-figure,
.highcharts-data-table table {
min-width: 210px;
max-width: 800px;
margin: 1em auto;
}

#container {
height: 400px;
}

.highcharts-data-table table {
font-family: Verdana, sans-serif;
border-collapse: collapse;
border: 1px solid #EBEBEB;
margin: 10px auto;
text-align: center;

max-width: 500px;
}

.highcharts-data-table caption {
padding: 1em 0;
font-size: 1.2em;
color: #555;
}

.highcharts-data-table th {
font-weight: 600;
padding: 0.5em;
}

.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
padding: 0.5em;
}

.highcharts-data-table thead tr,
.highcharts-data-table tr:nth-child(even) {
background: #f8f8f8;
}

.highcharts-data-table tr:hover {
background: #f1f7ff;
}

/* table */
table {
border-collapse: collapse;
}
.table {
width: 100%;
max-width: 100%;
margin-bottom: 1rem;
}

.table th,
.table td {
padding: 0.75rem;
vertical-align: top;
border-top: 1px solid #eceeef;
}

.table thead th {
vertical-align: bottom;
border-bottom: 2px solid #eceeef;
}

.table tbody + tbody {
border-top: 2px solid #eceeef;
}

.table .table {
background-color: #fff;
}

.table-sm th,
.table-sm td {
padding: 0.3rem;
}

.table-bordered {
border: 1px solid #eceeef;
}

.table-bordered th,
.table-bordered td {
border: 1px solid #eceeef;
}

.table-bordered thead th,
.table-bordered thead td {
border-bottom-width: 2px;
}

.table-striped tbody tr:nth-of-type(odd) {
background-color: rgba(0, 0, 0, 0.05);
}

.table-hover tbody tr:hover {
background-color: rgba(0, 0, 0, 0.075);
}

.table-active,
.table-active > th,
.table-active > td {
background-color: rgba(0, 0, 0, 0.075);
}

.table-hover .table-active:hover {
background-color: rgba(0, 0, 0, 0.075);
}

.table-hover .table-active:hover > td,
.table-hover .table-active:hover > th {
background-color: rgba(0, 0, 0, 0.075);
}

.table-success,
.table-success > th,
.table-success > td {
background-color: #dff0d8;
}

.table-hover .table-success:hover {
background-color: #d0e9c6;
}

.table-hover .table-success:hover > td,
.table-hover .table-success:hover > th {
background-color: #d0e9c6;
}

.table-info,
.table-info > th,
.table-info > td {
background-color: #d9edf7;
}

.table-hover .table-info:hover {
background-color: #c4e3f3;
}

.table-hover .table-info:hover > td,
.table-hover .table-info:hover > th {
background-color: #c4e3f3;
}

.table-warning,
.table-warning > th,
.table-warning > td {
background-color: #fcf8e3;
}

.table-hover .table-warning:hover {
background-color: #faf2cc;
}

.table-hover .table-warning:hover > td,
.table-hover .table-warning:hover > th {
background-color: #faf2cc;
}

.table-danger,
.table-danger > th,
.table-danger > td {
background-color: #f2dede;
}

.table-hover .table-danger:hover {
background-color: #ebcccc;
}

.table-hover .table-danger:hover > td,
.table-hover .table-danger:hover > th {
background-color: #ebcccc;
}

.thead-inverse th {
color: #fff;
background-color: #292b2c;
}

.thead-default th {
color: #464a4c;
background-color: #eceeef;
}

.table-inverse {
color: #fff;
background-color: #292b2c;
}

.table-inverse th,
.table-inverse td,
.table-inverse thead th {
border-color: #fff;
}

.table-inverse.table-bordered {
border: 0;
}

.table-responsive {
display: block;
width: 100%;
overflow-x: auto;
-ms-overflow-style: -ms-autohiding-scrollbar;
}

.table-responsive.table-bordered {
border: 0;
}

.highcharts-figure, .highcharts-data-table table {
min-width: 360px; 
max-width: 800px;
margin: 1em auto;
}

.highcharts-data-table table {
font-family: Verdana, sans-serif;
border-collapse: collapse;
border: 1px solid #EBEBEB;
margin: 10px auto;
text-align: center;
width: 100%;
max-width: 500px;
}
.highcharts-data-table caption {
padding: 1em 0;
font-size: 1.2em;
color: #555;
}
.highcharts-data-table th {
font-weight: 600;
padding: 0.5em;
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
background: #f8f8f8;
}
.highcharts-data-table tr:hover {
background: #f1f7ff;
}

.whitebox {
border-radius: 10px;
background: white;
box-shadow: 0 0 10px rgba(0, 0, 0, .2);
padding: 20px;
margin-bottom: 15px;
}

table {
width: 100%;
}



#myProgress {
width: 100%;
background-color: #eaedf2;
border-bottom-left-radius: 12px;
border-top-right-radius: 12px;
border-bottom-right-radius: 12px;
border-top-left-radius: 12px;
margin-top: 15px;
}

#myBar {
height: 12px;
background-color: #4d79fe;
border-bottom-left-radius: 12px;
border-top-right-radius: 12px;
border-bottom-right-radius: 12px;
border-top-left-radius: 12px;
}

p,
span {
color: #a5abc3;
padding: 10px 0;
font-size: 13px;
}

h3 {
color: #6a7293;
}
hr{
border-top-color: #ffffff;
/* background: red; */
border-bottom-color: #fff;
}
body {
font-family: Arial, Helvetica, sans-serif;
background: #eceef3;
}
h1,
h2,
h3,
h4,
p {
padding: 0;
margin: 0;
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


@if(Auth::user()->hasRole('qtl'))
<a href="/qtl_dashboard/qtl_dashboard_new" class="btn btn-label-brand btn-bold">
QTL Dashboard
</a>
@elseif(Auth::user()->parent_client == 23)
<a href="/welcome_dashboard_new" class="btn btn-label-brand btn-bold">
    Welcome Dashboard
    </a>
@endif


</div>
</div> 
@endsection
@section('main')


<div class="kt-portlet kt-portlet--mobile">
<div class="kt-portlet__head">
<div class="kt-portlet__head-label">
<h3 class="kt-portlet__head-title">
Filter
</h3>
</div>
</div>
<div class="kt-portlet__body">
{{ Form::open(array('url' => 'test_html_new_get')) }}
@csrf
<div class="form-group row">
<div class="row">
<div class="col-sm-2">
<div>Select a Partner</div>
<select class="form-control" name="partner_id" required="required" id="partner_id" onchange="getLocation(this.value);">

<?php 
if(Auth::user()->hasRole('client') || Auth::user()->hasRole('process-owner')){
    ?>
<option value="all">All</option>

    <?php
} else {
    ?>
    <option value="0">Select Partner</option>
    
        <?php
}
?>

@foreach($all_partners as $partner)
<option value="{{$partner->id}}" >{{$partner->name }}</option>
@endforeach
    
</select>
</div>
<div class="col-sm-2">
<div>Select LOB</div>
<select class="form-control" name="lob" id="lob" required>
<option value="0">Select LOB</option> 
          
</select>
</div>
<div class="col-sm-2">
<div>Select a Location</div>
<select class="form-control" name="location_id" id="location" required>
<option value="0">Select a Location</option>                
</select>
</div>
<div class="col-sm-2">
<div>Select a Process</div>
<select class="form-control"  name="process_id" id="process" onchange="getCycle(this.value);" required>


</select>
</div>        
<div class="col-sm-2">
<div>Select Audit Cycle</div>
<select class="form-control" name="date"  id="audit_cycle" required="required" >
<option value="0">Select Audit Cycle</option>
<option value="2022-04-01,2023-03-31">Current Financial Year</option>

</select>
</div>    
<div class="col-sm-2">
<br>    
<input type="submit" onclick="return check_cycle()" style="width: 100px;" class="btn btn-outline-brand d-block" value="Search">
</div>        
</div>            
</div>
{{ Form::close() }}
</div>
</div>

@if($final_data)

<div id="div_container">
<!--begin:: Widgets/Stats-->
<div class="kt-portlet">
<div class="kt-portlet__body  kt-portlet__body--fit">
<div class="row row-no-padding row-col-separator-xl">
<div class="col-md-12 col-lg-6 col-xl-4">

                    <div class="kt-widget24">
<div class="kt-widget24__details">
  <div class="kt-widget24__info">
    <h4 class="kt-widget24__title">
      Coverage
    </h4>
    <span class="kt-widget24__desc" style="color:#3498DB">
      Target
    </span>
  </div>
  <span class="kt-widget24__stats kt-font-brand" style="color:#3498DB">
    <?php if($final_data['coverage']['target'] == 0){ echo "200";} else { echo $final_data['coverage']['target']; }?>
  </span>
</div>
<div class="progress progress--sm">
  <div class="progress-bar kt-bg-brand" role="progressbar" style= "width: {{  $final_data['coverage']['achived_per'] }}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" ></div>
</div>
<div class="kt-widget24__action">
  <span class="kt-widget24__change" style="color:black">
    Achieved
  </span>
  <span class="kt-widget24__number" style="color:black">
  <?php
    if($final_data['coverage']['target'] == 0){ 
        echo $final_data['coverage']['achived'] ." (100%)";
    } else {
        echo $final_data['coverage']['achived'] ." (". $final_data['coverage']['achived_per']. "%)";
    }
   ?>
  </span>
</div>
</div>
<!--end::Total Profit-->

</div>
<div class="col-md-12 col-lg-6 col-xl-4">

<!--begin::New Feedbacks-->
<div class="kt-widget24">
<div class="kt-widget24__details">
  <div class="kt-widget24__info">
    <h4 class="kt-widget24__title">
      Rebuttal
    </h4>
    <span class="kt-widget24__desc kt-font-warning">
      Raised
    </span>
  </div>
  <span class="kt-widget24__stats kt-font-warning">
  {{ $final_data['rebuttal']['raised'] }} - {{ $final_data['rebuttal']['rebuttal_per'] }}%
  </span>
</div>
<div class="progress progress--sm">
  <div class="progress-bar kt-bg-warning" role="progressbar"  aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style = "width: {{ $final_data['rebuttal']['accepted_per'] }}%;"></div>
</div>


<div class="kt-widget24__action">
    <span class="kt-widget24__change" style="color:black">
        Accepted || Rejected || WIP
    </span>
    <span class="kt-widget24__number" style="color:black">
        {{ $final_data['rebuttal']['accepted'] }} -
        {{ $final_data['rebuttal']['accepted_per'] }} % ||
        {{ $final_data['rebuttal']['rejected'] }} -
        {{ $final_data['rebuttal']['rejected_per'] }}% ||
        {{ $final_data['rebuttal']['wip'] }}
    </span>
</div>
@if(Auth::user()->parent_client == 23)
<div class="kt-widget24__action">
    <span class="kt-widget24__change" style="color:black">
         Unique Rebuttals || Feedback Shared
    </span>
    <span class="kt-widget24__number" style="color:black">
       {{$final_data['rebuttal']['unque_rebuttal']}}||{{$final_data['coverage']['feedback_shared']}}
    </span>

</div>
@endif


</div>
<!--end::New Feedbacks-->
</div>



<div class="col-md-12 col-lg-6 col-xl-3">

<!--begin::New Orders-->
<div class="kt-widget24">
<div class="kt-widget24__details">
  <div class="kt-widget24__info">
    <h4 class="kt-widget24__title">
      FATAL
    </h4>
    <span class="kt-widget24__desc kt-font-danger">
      Parameter Count
    </span>
  </div>
  <span class="kt-widget24__stats kt-font-danger">
  {{ $final_data['fatal_first_row_block']['total_fatal_count_sub_parameter'] }}
  </span>
</div>
<div class="progress progress--sm">
  <div class="progress-bar kt-bg-danger" style = "width: {{$final_data['fatal_first_row_block']['total_fatal_audit_per'] }}%;" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<div class="kt-widget24__action">
  <span class="kt-widget24__change" style="color:black">
    Fatal Call
  </span>
  <span class="kt-widget24__number" style="color:black">
  {{ $final_data['fatal_first_row_block']['total_fatal_audits'] }} ({{ $final_data['fatal_first_row_block']['total_fatal_audit_per'] }}%)
  </span>
</div>
</div>

<!--end::New Orders-->
</div>

</div>
</div>
</div>  
    </div>  




<div class="row">
<div class="col-lg-12">

<div class="kt-portlet kt-portlet--mobile">
<div class="kt-portlet__head">
<div class="kt-portlet__head-label">
<h3 class="kt-portlet__head-title">
Process Statistics 
</h3>
</div>
</div>
<div class="kt-portlet__body">
<div class="row" style="text-align: center;">

<div class="col-md-3 popupbox">
<h6 class="kt-font-bolder kt-font-brand">DPU</h6>
<span class="form-text text-muted">Defect Per Unit</span>
<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
<label>{{ $final_data['process_stats']['dpu'] }}</label>
</div>
<div class="col-md-3 popupbox">
<h6 class="kt-font-bolder kt-font-brand">DPO</h6>
<span class="form-text text-muted">Defect Per Opportunity</span>
<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
<label>{{ $final_data['process_stats']['dpo'] }}</label>
</div>
<div class="col-md-3 popupbox">
<h6 class="kt-font-bolder kt-font-brand">DPMO</h6>
<span class="form-text text-muted">Defect Per Million Opportunities</span>
<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
<label>{{ $final_data['process_stats']['dpmo'] }}</label>
</div>
<div class="col-md-3 popupbox">
<h6 class="kt-font-bolder kt-font-brand">PPM</h6>
<span class="form-text text-muted">Parts Per Million</span>
<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
<label>{{ $final_data['process_stats']['ppm'] }}</label>
</div>


<!-- <div class="tooltipbox">

dummy test 

</div> -->

</div>
</div>
</div>


</div>

</div>

<div class="kt-portlet kt-portlet--mobile">
<div class="kt-portlet__head">
<div class="kt-portlet__head-label">
<h3 class="kt-portlet__head-title">
Process Score
</h3>
</div>
</div>
<div class="kt-portlet__body">
<div class="row">
<div class="col-md-6">
<div class="row">
<div class="col-md-6">
<h5 align="center" style="color:#4169E1">With Fatal</h5>
<div id="with_fatel"></div>
</div>
<div class="col-md-6">
<h5 align="center" style="color:grey">Without Fatal</h5>
<div id="without_fatel"></div>
</div>
</div>
</div>
<div class="col-md-6">
<h5>Parameter Wise Defect</h5>
<table class="table table-striped- table-bordered table-hover table-checkable" style="margin-top: 35px;">
    <thead>
        <tr>
            <th title="Field #1">#</th>
            <th title="Field #2">
                Parameter
            </th>
            <th title="Field #3" align="center">
                Fatal Count
            </th>
            <th title="Field #3" align="center">
                Fail Count
            </th>
            <th title="Field #4" align="center">
                Non - Compliance
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($final_data['pwfcs'] as $item)
        <tr>
            <td>{{$item['counter']}}</td>
            <td>{{$item['parameter']}}</td>
            <td align="center">{{$item['fatal_count']}}</td>
            <td align="center">{{$item['fail_count']}}</td>
            <?php $ncom=(100- $item['fatal_score']); ?>

         <td align="center" <?php if($ncom > 50) { ?> style="color:red;" <?php } ?> <?php if($ncom > 30 && $ncom <= 50) { ?> style="color:orange;" <?php } ?> <?php if($ncom <= 30) { ?> style="color:black;" <?php } ?> >{{$ncom}} %</td>
        
        </tr>
        @endforeach
    </tbody>
</table>
</div>
</div>
</div>
</div>




<div class="row">
<div class="col-lg-12">
<div class="kt-portlet kt-portlet--mobile">
<div class="kt-portlet__head">
<div class="kt-portlet__head-label">
   <h3 class="kt-portlet__head-title">
       Parameter Wise Compliance
   </h3>
</div>
</div>
<div class="kt-portlet__body">
<div id="paramerer_wise_compilance"> </div>
                  
</div>
</div>
</div>
</div>

<div class="row">

<div class="col-lg-12">
<div class="kt-portlet kt-portlet--mobile">
<div class="kt-portlet__head">
<div class="kt-portlet__head-label">
   <h3 class="kt-portlet__head-title">
    Sub Parameter Wise Compliance
   </h3>
</div>
</div>
<div class="kt-portlet__body">
    <div class = "row"> 
        <div class="square" style=" height: 15px;width: 15px;background-color: rgb(65, 105, 225);"></div>
        <h5 class="kt-portlet__head-title pl-2 pr-2">
        
            Fatal Parameter
        </h5>

        <div class="square" style=" height: 15px;width: 15px;background-color: grey;"></div>
        <h5 class="kt-portlet__head-title pl-2 pr-2">
        
            Non Fatal Parameter
        </h5>
    </div>
</div>
<?php $m=0;
$sub_parameter = array();

?>
@foreach($final_data['spws'] as $spws)
<?php 
/* print_r($spws['sp_p_score']);
die; */
?>
<?php array_push($sub_parameter, 'container_'. $m); ?>
<div class="kt-portlet__body">
    <div id="container_<?php echo $m; ?>"></div>
</div>

<script type="application/javascript">
color = {
        <?php 
            $i = 0;
            foreach($spws['temp_sps_list'] as $value){
                echo "'".$value."':'".$spws['temp_sps_color'][$i]."',";
                $i++;
            } 
                     
        ?>
        
    }       
            Highcharts.chart('container_<?php echo $m; ?>', {

            chart: {
                type: 'bar',
                height: 250
            },
            credits: {
                    enabled: false
            },

            title: {
                text: "<?php echo $spws['parameter']; ?>"
            },
            
            tooltip: {
                    valueSuffix: ' %',
                    outside: true,
                    useHTML: true,
                    backgroundColor: "rgba(246, 246, 246, 1)",
                    borderColor: "#bbbbbb",
                    borderWidth: 1.5,
                    style: {
                        opacity: 10,
                        background: "rgba(246, 246, 246, 1)"
                    }                        
            },
            categories: {
                    enabled: 'true'                        
            },
            legend: {
                    enabled:false,
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -40,
                    y: 80,
                    floating: true,
                    borderWidth: 1,
                                          
                    shadow: false,                        
                    itemHoverStyle: {
                        color: 'grey'
                    }                       
            },                 

            xAxis: {
                categories:<?php echo json_encode($spws['temp_sps_list'],true); ?>,
                myString:<?php echo json_encode($spws['temp_sps_detail'],true); ?>,
                myColor:<?php echo json_encode($spws['temp_sps_color'],true); ?>,
                labels: {
                    useHTML:true,
                        formatter:function(){

                          //  return '<div style="color:'+this.axis.options.myColor+'" title="'+this.axis.options.myString+'" class="label">'+this.value+'</div>';
                            return `<div style="color: ${color[this.value]}" title="${this.axis.options.myString}" class="label">${this.value}</div>`
                        }
                },
            },

            yAxis: {
                    min: 0,
                    max: 100,
                    title: {
                        text: 'Score',
                        align: 'high'
                    },
                    labels: {
                        overflow: 'justify'
                    }
            }, 

            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:.0f}%'
                    },

                },
                
                series: {
		            colorByPoint: true
		        }
                
            },
            colors: [<?php foreach ($spws['temp_sps_color'] as $key => $value) {
                    	?>
                    	'<?=$value ?>',
                    	<?php
                    } ?>
            ],
            series: [{
                    name: 'Score',
                    data: <?php echo json_encode($spws['sp_p_score'],true); ?>
                    
                    }]

            });
            </script>
            <?php $m++; ?>

@endforeach
            

<div class="kt-portlet__body">
<div id="nonScoring"> </div>
                  
</div>


</div>
</div>

</div>



<div class="row">
<div class="col-lg-6" >
<div class="kt-portlet kt-portlet--mobile">
<div class="kt-portlet__head">
<div class="kt-portlet__head-label">
   <h3 class="kt-portlet__head-title">
       Agent Performance Quartile
   </h3>
   
</div>
</div>
<div class="kt-portlet__body" id="agent_performance">

</div>       
</div>
</div>
<div class="col-lg-6">
<div class="kt-portlet kt-portlet--mobile">
<div class="kt-portlet__head">
<div class="kt-portlet__head-label">
   <h3 class="kt-portlet__head-title">
       Call Type
   </h3>
   
</div>
</div>
<div class="kt-portlet__body" id="call_type_container">

</div>
</div>
</div>
</div>

<div class="kt-portlet kt-portlet--mobile whitebox">
<div class="kt-portlet__head">
<div class="kt-portlet__head-label">
<h3 class="kt-portlet__head-title">
Disposition Wise Compliance
</h3>
</div>
</div>
<div class="kt-portlet__body" id="disposition_wise_compliance">

</div>
</div>

<!-- <div class="kt-portlet kt-portlet--mobile whitebox">
<div class="kt-portlet__head">
<div class="kt-portlet__head-label">
<h3 class="kt-portlet__head-title">
Pareto Data
</h3>
</div>
</div>
<div class="kt-portlet__body" id="pareto_data">

</div>
</div> -->
<input type="hidden" id="user_id" value="{{Auth::user()->id}}" />
<form id="quartile_report" method="post" action="/dashboard/test_excel" target='_blank'>
<input type="hidden" id="partnerId" name="partner_id" value="<?php if(is_array($final_data) && array_key_exists('partner_id', $final_data) ) { echo $final_data['partner_id']; } ?>" />
<input type="hidden" name="lob" value="<?php if( is_array($final_data) && array_key_exists('lob', $final_data)) {  echo $final_data['lob'];} ?>" />
<input type="hidden" id="locationId" name="location_id" value="<?php if(is_array($final_data) && array_key_exists('location_id', $final_data)) { echo $final_data['location_id'];} ?>" />
<input type="hidden" id="processId" name="process_id" value="<?php if(is_array($final_data) && array_key_exists('process_id', $final_data)) { echo $final_data['process_id'];} ?>"/>
<input type="hidden" id="date" name="date" value="<?php if(is_array($final_data) && array_key_exists('date', $final_data)) { echo $final_data['date'];} ?>" />
<input type="hidden" id="range" name="range" />
</form>



<script type="application/javascript"> 
// Fatal Chart start 
var gaugeOptions = {
chart: {
type: 'solidgauge'
},

title: null,



exporting: {
enabled: false
},

tooltip: {
enabled: false
},

// the value axis
yAxis: {
stops: [
[0.1, '#4169E1'], // green
[0.5, '#4169E1'], // yellow
[0.9, '#4169E1'] // red
]

}


};

var gaugeOptionstwo = {
chart: {
type: 'solidgauge'
},

title: null,



exporting: {
enabled: false
},

tooltip: {
enabled: false
},

// the value axis
yAxis: {
stops: [
[0.1, '#AFEEEE'], // green
[0.5, '#AFEEEE'], // yellow
[0.9, '#AFEEEE'] // red
]

}


};

// Fatal Chart start 
Highcharts.chart('with_fatel', Highcharts.merge(gaugeOptions,{
chart: {
type: 'solidgauge',

height:280,
},

title: null,

pane: {
center: ['50%', '70%'],
size: '100%',
startAngle: -90,
endAngle: 90,
background: {
innerRadius: '60%',
outerRadius: '100%',
shape: 'arc'
}
},

tooltip: {
enabled: false
},

// the value axis
yAxis: {

lineWidth: 0,
minorTickInterval: null,
tickAmount: 2,
title: {
y: -0
},
labels: {
y: 0
}

},

plotOptions: {
solidgauge: {
dataLabels: {
y: 6,
borderWidth: 0,
useHTML: true
}
}
},
yAxis: {
min: 0,
max: 100,
title: {
text: ''
}
},

credits: {
enabled: false
},

series: [{
name: 'Speed',
data: [<?php echo $final_data['fatal_dialer_data']['with_fatal_score']; ?>],
dataLabels: {
format:
'<div style="text-align:center">' +
'<span style="font-size:25px">{y}%</span><br/>' +
'<span style="font-size:12px;opacity:0.4">Score</span>' +
'</div>'
},
tooltip: {
valueSuffix: ' Count'
}
}]
}));

Highcharts.chart('without_fatel', Highcharts.merge(gaugeOptionstwo,{
chart: {
type: 'solidgauge',
height:280,
},

title: null,

pane: {
center: ['50%', '70%'],
size: '100%',
startAngle: -90,
endAngle: 90,
background: {
innerRadius: '60%',
outerRadius: '100%',
shape: 'arc'
}
},

tooltip: {
enabled: false
},

// the value axis
yAxis: {

lineWidth: 0,
minorTickInterval: null,
tickAmount: 2,
title: {
y: -0
},
labels: {
y: 0
}
},

plotOptions: {
solidgauge: {
dataLabels: {
y: 6,
borderWidth: 0,
useHTML: true
}
}
},
yAxis: {
min: 0,
max: 100,
title: {
text: ''
}
},

credits: {
enabled: false
},

series: [{
name: 'Speed',
data: [<?php echo $final_data['fatal_dialer_data']['without_fatal_score']; ?>],
dataLabels: {
format:
'<div style="text-align:center">' +
'<span style="font-size:25px">{y}%</span><br/>' +
'<span style="font-size:12px;opacity:0.4">Score</span>' +
'</div>'
},
tooltip: {
valueSuffix: ' Score'
}
}]

}));



Highcharts.chart('nonScoring', {

chart: {
type: 'bar',
height: 250
},
credits: {
enabled: false
},

title: {
text: "<?php echo $final_data['non_scoring_params']['names']; ?>"
},

tooltip: {
valueSuffix: ' %',
outside: true,
useHTML: true,
backgroundColor: "rgba(246, 246, 246, 1)",
borderColor: "#bbbbbb",
borderWidth: 1.5,
style: {
opacity: 10,
background: "rgba(246, 246, 246, 1)"
}                        
},
categories: {
enabled: 'true'                        
},
legend: {
enabled:false,
layout: 'vertical',
align: 'right',
verticalAlign: 'top',
x: -40,
y: 80,
floating: true,
borderWidth: 1,                        
shadow: false,                        
itemHoverStyle: {
color: 'grey'
}                       
},                 

xAxis: {
categories:<?php echo json_encode($final_data['non_scoring_params']['list'],true); ?>       
},

yAxis: {
min: 0,
max: 100,
title: {
text: 'Score',
align: 'high'
},
labels: {
overflow: 'justify'
}
}, 

plotOptions: {
bar: {
dataLabels: {
enabled: true,
format: '{point.y:.0f}%'
}
}
},
series: [{
name: 'Score',
data: <?php echo json_encode($final_data['non_scoring_params']['score'],true); ?>
}]

});



Highcharts.chart('agent_performance', {

chart: {
plotBackgroundColor: null,
plotBorderWidth: null,
plotShadow: false,
type: 'pie'
},
title: {
text: ''
},
tooltip: {
pointFormat: '{series.name}: <b>{point.percentage:.0f} %</b> <br>Call Count:<b>{point.au_count}</b><br> Contribution:<b>{point.au_contri} %</b>'
},
plotOptions: {
pie: {
allowPointSelect: true,
cursor: 'pointer',
dataLabels: {
enabled: true,
format: '<b>{point.name}</b>: {point.percentage:.0f} % <br>Call Count:{point.au_count} <br> Contribution:{point.au_contri} %'
},
point: {
events: {
click: function() {     
var url = this.options.url;
var range = url.split('=');
               
$("#range").val(range[1]);                                     
$("#quartile_report").submit();                      
}
}
}
}        
},
series: [{
name: 'Agent %',
colorByPoint: true,
data: [{
name: '0 - 40 %',
y: <?php echo $final_data['quartile'][0]; ?>,
au_count:<?php echo $final_data['quartile_au_count'][0]; ?>,
au_contri:<?php echo $final_data['quartile_au_contri'][0]; ?>,
url: 'range=1',
sliced: true,
selected: true,
color:"#F24405"
}, {
name: '41 - 60 %',
y:  <?php echo $final_data['quartile'][1]; ?>,
au_count:<?php echo $final_data['quartile_au_count'][1]; ?>,
au_contri:<?php echo $final_data['quartile_au_contri'][1]; ?>,
url: 'range=2',
color:'#D7F205'
}, {
name: '61 - 80 %',
y:  <?php echo $final_data['quartile'][2]; ?>,
au_count:<?php echo $final_data['quartile_au_count'][2]; ?>,
au_contri:<?php echo $final_data['quartile_au_contri'][2]; ?>,
url: 'range=3',
color:'#7D07F2'
}, {
name: 'Greater then 80 %',
y:  <?php echo $final_data['quartile'][3]; ?>,
au_count:<?php echo $final_data['quartile_au_count'][3]; ?>,
au_contri:<?php echo $final_data['quartile_au_contri'][3]; ?>,
url: 'range=4',
color:"#20D91A"
}]
}]
});



Highcharts.chart('call_type_container', {

title: {
text: 'Call Type'
},
xAxis: {
categories: [<?php if($final_data['client_id'] == 9 || $final_data['client_id'] == 13 || $final_data['client_id'] == 17 || $final_data['client_id'] == 15) { foreach ($final_data['call_type'] as $key => $value) {
?>
'<?=$value ?>',
<?php
} } else { ?> 'Query','Request','Complaint' <?php } ?>]
},
plotOptions: {
spline: { // has to say spline here
dataLabels: {
enabled: true,
format: '{point.y:.0f} %'
},
enableMouseTracking: false
}
}, 
labels: {
items: [{
html: '',
style: {
left: '50px',
top: '18px',
// color: ( // theme
//     Highcharts.defaultOptions.title.style &&
//     Highcharts.defaultOptions.title.style.color
// ) || 'black'
}
}]
},
series: [{
type: 'column',
name: '<?php if($final_data['client_id'] == 9 || $final_data['client_id'] == 13|| $final_data['client_id'] == 15) { echo "Audit Count(Auditor Wise)"; } else { echo "Audit Count"; } ?>',
data: <?php echo json_encode($final_data['qrc']['audit_count'],true); ?>

}, {
type: 'column',
name: '<?php if($final_data['client_id'] == 9 || $final_data['client_id'] == 13|| $final_data['client_id'] == 15 || $final_data['client_id'] == 17)  { echo "Audit Count(Bam Wise)"; } else { echo "Fatal Count"; } ?>',
data:  <?php if($final_data['client_id'] == 9 || $final_data['client_id'] == 13|| $final_data['client_id'] == 15 || $final_data['client_id'] == 17) {
echo json_encode($final_data['qrc_bam']['audit_count'],true); } else { echo json_encode($final_data['qrc']['fatal_count'],true); } ?>
}, {
type: 'spline',
name: '<?php if($final_data['client_id'] == 9 || $final_data['client_id'] == 13 || $final_data['client_id'] == 15 || $final_data['client_id'] == 17) { echo "Score(Auditor Wise)"; } else { echo "Score"; } ?>',
data:  <?php echo json_encode($final_data['qrc']['score'],true); ?>,
marker: {
lineWidth: 2,
// lineColor: Highcharts.getOptions().colors[3],
fillColor: 'red',
format: '{point.y:.0f}%'
}
},
<?php if($final_data['client_id'] == 9 || $final_data['client_id'] == 13 || $final_data['client_id'] == 15 || $final_data['client_id'] == 17) { ?>
{
type: 'spline',
name: 'Score(Bam Wise)',
data:  <?php echo json_encode($final_data['qrc_bam']['score'],true); ?>,
marker: {
lineWidth: 2,
// lineColor: Highcharts.getOptions().colors[3],
fillColor: 'blue',
format: '{point.y:.0f}%'
}
} <?php } ?>]

});

Highcharts.chart('disposition_wise_compliance', {

chart: {
zoomType: 'xy'
},
title: {
text: ''
},
subtitle: {
text: ''
},
xAxis: [{
categories:<?php echo json_encode($final_data['disposition']['all_unique_despos'],true); ?>,
crosshair: true
}],
yAxis: [{ // Primary yAxis
min:0,
max:100,
labels: {
format: '{value} %',
style: {

}
},
title: {
text: 'Score',
style: {

}
}
}, { // Secondary yAxis
title: {
text: 'Count',
style: {

}
},
labels: {
format: '{value}',
style: {

}
},
opposite: true
}],
tooltip: {
shared: true
},
legend: {
layout: 'horizontal',
align: 'center',
x: 0,
verticalAlign: 'top',
y: 0,
floating: true,

},
series: [{
name: 'Audit Counts',
type: 'column',
yAxis: 1,
data: <?php echo json_encode($final_data['disposition']['all_unique_despos_counts'],true); ?>,
tooltip: {
valueSuffix: ' Count'
}

}, {
name: 'Score',
type: 'spline',
data: <?php echo json_encode($final_data['disposition']['all_unique_despos_score'],true); ?>,
tooltip: {
valueSuffix: '%'
}
}]
});


/* Highcharts.chart('pareto_data', {
chart: {
zoomType: 'xy'
},
title: {
text: ''
},
subtitle: {
text: ''
},
xAxis: [{
categories:<?php /* echo json_encode($final_data['pareto_data']['reasons'],true); */ ?>,
crosshair: true
}],
yAxis: [{ // Primary yAxis
min:0,
max:100,
labels: {
format: '{value} %',
style: {

}
},
title: {
text: '%',
style: {

}
}
}, { // Secondary yAxis
title: {
text: 'Count',
style: {

}
},
labels: {
format: '{value}',
style: {

}
},
opposite: true
}],
tooltip: {
shared: true
},
legend: {
layout: 'horizontal',
align: 'left',
x: 0,
verticalAlign: 'top',
y: 0,
floating: true,

},
series: [{
name: 'Reason Counts',
type: 'column',
yAxis: 1,
data: <?php /* echo json_encode($final_data['pareto_data']['count'],true); */ ?>,
tooltip: {
valueSuffix: ' Count'
}

}, {
name: 'Percentage',
type: 'spline',
data:<?php /* echo json_encode($final_data['pareto_data']['per'],true); */ ?>,
tooltip: {
valueSuffix: '%'
}
}]

}); */

</script>







@endif
@endsection
@section('js')
<script>


(function() {
    var val = "all";
    
    var base_url = window.location.origin;
    if(val != 0) {
        $.ajax({
        type: "GET",
        url: base_url + "/dashboard/get_partner_locations1/"+val,
        success: function(Data){
        $("#location").html(Data);
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
        $("#lob").html(Data);
        
        }
        
    });
    }

   
})();

function check_cycle(){
	var cycle = document.getElementById("process").value;
    var cycle_2 = document.getElementById("audit_cycle").value;
	if(cycle == "0"){
		document.getElementById("process_warning").style.display = "block";
		//document.getElementById("audit_cycle").value
		return false;
	} else if(cycle == "0"){
        document.getElementById("warning").style.display = "block";
		//document.getElementById("audit_cycle").value
		return false;
    }
	else {
		return true;
	}
	//console.log(cycle);
	
}


/*  var process = document.getElementById("process").options;

console.log(process); */
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
        $("#location").html(Data);
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
        $("#lob").html(Data);
        }
    });
    }
}
</script>
<?php if($final_data){

?>
<script type="application/javascript">
Highcharts.chart('nonScoring', {

chart: {
    type: 'bar',
    height: 250
},
credits: {
        enabled: false
},

title: {
    text: "<?php echo $final_data['non_scoring_params']['names']; ?>"
},

tooltip: {
        valueSuffix: ' %',
        outside: true,
        useHTML: true,
        backgroundColor: "rgba(246, 246, 246, 1)",
        borderColor: "#bbbbbb",
        borderWidth: 1.5,
        style: {
        opacity: 10,
        background: "rgba(246, 246, 246, 1)"
        }                        
},
categories: {
        enabled: 'true'                        
},
legend: {
        enabled:false,
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -40,
        y: 80,
        floating: true,
        borderWidth: 1,                        
        shadow: false,                        
        itemHoverStyle: {
            color: 'grey'
        }                       
},                 

xAxis: {
    categories:<?php echo json_encode($final_data['non_scoring_params']['list'],true); ?>       
},

yAxis: {
        min: 0,
        max: 100,
        title: {
            text: 'Score',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        }
}, 

plotOptions: {
    bar: {
        dataLabels: {
            enabled: true,
            format: '{point.y:.0f}%'
        }
    }
},
series: [{
        name: 'Score',
        data: <?php echo json_encode($final_data['non_scoring_params']['score'],true); ?>
    }]

});

Highcharts.chart('paramerer_wise_compilance', {

    chart: {
        type: 'spline'
    },

    title: {
        text: ''
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: <?php echo json_encode($final_data['parameter_list'],true); ?>
    },
    yAxis: {
        min:0,
        max:100,
        title: {
            text: '%'
        },
        labels: {
            format: '{value} %',
            style: {
                
            }
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true,
                format: '{point.y:.0f}%'
            },
            enableMouseTracking: false
        }
    },
    series: [{
                name: 'Compliance %',
                data: <?php echo json_encode($final_data['pws'],true); ?>

    }]

});
</script>

@if($final_data['non_scoring_params']['names'] == null)
<script type="application/javascript">
console.log("hiii");
var x = document.getElementById("nonScoring");
x.style.display = "none";
</script>
@endif
<?php 
}
?>
@endsection
@section('css')
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
