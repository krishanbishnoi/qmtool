<html>
   
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
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
</head>

<body>
    <button name="btn" id = "btn" onclick="getImg();">download</button>

   
    <div class="whitebox">
       
        <table class="table">
            <tr>
                <td style="padding: 10px;" width="50%">
                    <table>
                        <tr>
                            <td>
                                <h3>Coverage</h3>
                                <p>Target</p>

                            </td>
                            <td align="right" style="vertical-align: top;">
                                <h2 style="color:#4d79fe">450</h2>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <div id="myProgress">
                                    <div id="myBar" style="width:10%"></div>
                                </div>
                                <span style="float:left">Achived</span>
                                <span style="float:right">75(17%)</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 0;"> <span style="padding: 0;display: inline-block;">&nbsp; </span>
                            </td>
                            <td style="padding: 0;" align="right"> <span style="display: inline-block;">&nbsp; </span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="padding: 10px;" width="50%">
                    <table>
                        <tr>
                            <td>
                                <h3>Rebuttal</h3>
                                <p>Raised</p>

                            </td>
                            <td align="right" style="vertical-align: top;">
                                <h2 style="color:#ffb50e">4-5%</h2>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <table>
                                    <tr>
                                        <td colspan="2">
                                            <div id="myProgress">
                                                <div id="myBar" style="width:10%; background-color: #ffb50e;"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0;"> <span
                                                style="padding: 0;display: inline-block;">Acce[ted ||
                                                Rejected </span> </td>
                                        <td style="padding: 0;" align="right"><span style="display: inline-block;">2 -
                                                3% ||
                                                2-3%</span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0;"> <span style="padding: 0;display: inline-block;">WIP
                                            </span>
                                        </td>
                                        <td style="padding: 0;" align="right"> <span style="display: inline-block;">0
                                            </span> </td>
                                    </tr>
                                </table>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <hr>
        <table class="table">
            <tr>
                <td style="padding: 10px;" width="50%">
                    <table>
                        <tr>
                            <td>
                                <h3>FATAL</h3>
                                <p>parameter Count</p>

                            </td>
                            <td align="right" style="vertical-align: top;">
                                <h2 style="color:#ff146f">450</h2>
                            </td>
                        </tr>   

                        <tr>
                            <td colspan="2">
                                <div id="myProgress">
                                    <div id="myBar" style="width:10%; background-color: #ff146f;"></div>
                                </div>
                                <span style="float:left">Fatal Call</span>
                                <span style="float:right">28(37%)</span>
                            </td>
                        </tr>
                       
                    </table>
                </td>

                <td style="padding: 10px;" width="50%">
                    &nbsp;
                </td>

            </tr>
        </table>
    </div>

    <div class="whitebox">
        <h3>Process Statistics</h3>
        <hr>
        <br>
        <table>
            <tr>
                <td align="center" width="25%">
                    <h4 style="color:#4d79fe; padding: 0;">DPU</h4>
                    <P>Defect Per Unit</P>
                </td>
                <td  align="center" width="25%">
                    <h4 style="color:#4d79fe">DPU</h4>
                    <P>Defect Per Unit</P>
                </td>
                <td  align="center" width="25%">
                    <h4 style="color:#4d79fe">DPU</h4>
                    <P>Defect Per Unit</P>
                </td>
                <td  align="center" width="25%">
                    <h4 style="color:#4d79fe">DPU</h4>
                    <P>Defect Per Unit</P>
                </td>
            </tr>
        </table>

        <hr>
        <br>
        <table>
            <tr>
                <td align="center" width="25%">
                   
                    <P>3.51</P>
                </td>
                <td  align="center" width="25%">
                   
                    <P>0.28</P>
                </td>
                <td  align="center" width="25%">
                     
                    <P>280000</P>
                </td>
                <td  align="center" width="25%">
                     
                    <P>373333.33</P>
                </td>
            </tr>
        </table>
    </div>
    <div class="container-fluid">
        <div class="whitebox">
            <h1>Process Score</h1>            
            <div class="row">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-6">
                            <div id="with_fatel"></div>
                        </div>
                        <div class="col-lg-6">
                            <div id="without_fatel"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <table colspan="0"  class="table table-striped- table-bordered table-hover table-checkable"
                        style="margin-top: 35px;" border="0">
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
                            <td align="center">{{(100 - $item['fatal_score'])}} %</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!--<div class="row whitebox">
            <div class="col-lg-12">
                <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__body">
                    <div class="row" style="text-align: center;">

                        <div class="col-md-3">
                            <h6 class="kt-font-bolder kt-font-brand">DPU</h6>
                            <span class="form-text text-muted">Defect Per Unit</span>
                            <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
                            <label>{{$final_data['process_stats']['dpu']}}</label>
                        </div>
                        <div class="col-md-3">
                            <h6 class="kt-font-bolder">DPO</h6>
                            <span class="form-text text-muted">Defect Per Opportunity</span>
                            <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
                            <label>{{$final_data['process_stats']['dpo']}}</label>
                        </div>
                        <div class="col-md-3">
                            <h6 class="kt-font-bolder kt-font-brand">DPMO</h6>
                            <span class="form-text text-muted">Defect Per Million Opportunities</span>
                            <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
                            <label>{{$final_data['process_stats']['dpmo']}}</label>
                        </div>
                        <div class="col-md-3 popupbox">
                            <h6 class="kt-font-bolder kt-font-brand">PPM</h6>
                            <span class="form-text text-muted">Parts Per Million</span>
                            <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
                            <label>{{$final_data['process_stats']['ppm']}}</label>
                        </div>
                   </div>
                </div>
                </div>
            </div>
        </div> -->
        <div class="row whitebox">
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

        <div class="whitebox" id = "whitebox">
            <h3>Sub Parameter Wise Compliance</h3>
            <figure class="highcharts-figure">
                <?php $m=0;
                $sub_parameter = array();
                ?>
                @foreach($final_data['spws'] as $spws)
                    <?php array_push($sub_parameter, 'container_'. $m); ?>
                <div class="col-md-12" id="container_<?php echo $m; ?>"></div>
                <script >
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
                    labels: {
                        useHTML:true,
                            formatter:function(){
                                return '<div title="'+this.axis.options.myString+'" class="label">'+this.value+'</div>';
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
                    columnrange: {
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.1f}%'
                        }
                    }
                },
                series: [{
                        name: 'Score',
                        data: <?php echo json_encode($spws['sp_p_score'],true); ?>
                    }]

                });
                </script>
                <?php $m++; ?>
                @endforeach

               
                <div class="col-md-12" id="nonScoring">

                </div>                
            </figure>
            <script >
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
                    columnrange: {
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.1f}%'
                        }
                    }
                },
                series: [{
                        name: 'Score',
                        data: <?php echo json_encode($final_data['non_scoring_params']['score'],true); ?>
                    }]

                });
                </script>
            <!-- <figure class="highcharts-figure">
                <div id="container4"></div>
                <p class="highcharts-description">
                    This chart shows how data labels can be added to the data series. This
                    can increase readability and comprehension for small datasets.
                </p>
            </figure> -->


        </div> 

        <div class="row whitebox">
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
								Call Type (QRC)
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

        <div class="kt-portlet kt-portlet--mobile whitebox">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Pareto Data
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body" id="pareto_data">

            </div>
        </div>

   </div>
    <script>
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Monthly Average Rainfall'
            },
            subtitle: {
                text: 'Source: WorldClimate.com'
            },
            xAxis: {
                categories: [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec'
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Rainfall (mm)'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Tokyo',
                data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

            }, {
                name: 'New York',
                data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]

            }, {
                name: 'London',
                data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2]

            }, {
                name: 'Berlin',
                data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1]

            }]
        });</script>

<script>
Highcharts.chart('paramerer_wise_compilance', {

    chart: {
        type: 'line'
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
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}%'
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



<script >
Highcharts.chart('container4', {

chart: {
    type: 'columnrange',
    inverted: true
},

accessibility: {
    description: 'Image description: A column range chart compares the monthly temperature variations throughout 2017 in Vik I Sogn, Norway. The chart is interactive and displays the temperature range for each month when hovering over the data. The temperature is measured in degrees Celsius on the X-axis and the months are plotted on the Y-axis. The lowest temperature is recorded in March at minus 10.2 Celsius. The lowest range of temperatures is found in December ranging from a low of minus 9 to a high of 8.6 Celsius. The highest temperature is found in July at 26.2 Celsius. July also has the highest range of temperatures from 6 to 26.2 Celsius. The broadest range of temperatures is found in May ranging from a low of minus 0.6 to a high of 23.1 Celsius.'
},

title: {
    text: ''
},

subtitle: {
    text: 'Observed in Vik i Sogn, Norway, 2017'
},

xAxis: {
    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
},

yAxis: {
    title: {
        text: 'Temperature ( °C )'
    }
},

tooltip: {
    valueSuffix: '°C'
},

plotOptions: {
    columnrange: {
        dataLabels: {
            enabled: true,
            format: '{y}°C'
        }
    }
},

legend: {
    enabled: false
},

series: [{
    name: 'Temperatures',
    data: [
        [-9.9, 10.3],
        [-8.6, 8.5],
        [-10.2, 11.8]
       
    ]
}]

});
</script>

<script >

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
        pointFormat: '{series.name}: <b>{point.percentage:.1f} %</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            },
            point: {
                events: {
                    click: function() {     
                        var userID = $("#user_id").val();
                        var partnerId = 0;
                        var locationId = 0;
                        if(userID == 44) {
                        	partnerId=1;
                        	locationId=2;
                        } else {
                        	partnerId = $("#partner_id").val();
                        	locationId = $("#location_id").val();
                        }
                        var date_range = $("#kt_daterangepicker_1").val();
                        var processId = $("#process_id").val();  
                        var lob = $("#lobs").val();
                        var string='&partner_id='+ partnerId +'&location_id='+locationId+'&process_id='+processId+'&date_range='+date_range+'&lob='+lob;
                        var url = this.options.url;
                        var range = url.split('=');
                        $("#partnerId").val(partnerId);
                        $("#locationId").val(locationId);
                        $("#date").val(date_range);
                        $("#lob_1").val(lob);
                        $("#processId").val(processId);
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
            url: 'range=1',
            sliced: true,
            selected: true,
            color:"#F24405"
        }, {
            name: '41 - 60 %',
            y:  <?php echo $final_data['quartile'][1]; ?>,
            url: 'range=2',
            color:'#D7F205'
        }, {
            name: '61 - 80 %',
            y:  <?php echo $final_data['quartile'][2]; ?>,
            url: 'range=3',
            color:'#7D07F2'
        }, {
            name: 'Greater then 80 %',
            y:  <?php echo $final_data['quartile'][3]; ?>,
            url: 'range=4',
            color:"#20D91A"
        }]
    }]
});

</script>

<script >
Highcharts.chart('call_type_container', {

    title: {
        text: 'Call Type (QRC)'
    },
    xAxis: {
        categories: ['Query', 'Request', 'Complaint']
    },
    plotOptions: {
            spline: { // has to say spline here
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.1f} %'
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
        name: 'Audit count',
        data: <?php echo json_encode($final_data['qrc']['audit_count'],true); ?>
            
    }, {
        type: 'column',
        name: 'Fatal count',
        data:  <?php echo json_encode($final_data['qrc']['fatal_count'],true); ?>
    }, {
        type: 'spline',
        name: 'Score',
        data:  <?php echo json_encode($final_data['qrc']['score'],true); ?>,
        marker: {
            lineWidth: 2,
            // lineColor: Highcharts.getOptions().colors[3],
            fillColor: 'red',
            format: '{point.y:.1f}%'
        }
    }]

});
</script>

<script>

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
</script>

<script>

Highcharts.chart('pareto_data', {
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
        categories:<?php echo json_encode($final_data['pareto_data']['reasons'],true); ?>,
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
        align: 'center',
        x: 0,
        verticalAlign: 'top',
        y: 0,
        floating: true,
        
    },
    series: [{
        name: 'Reason Counts',
        type: 'column',
        yAxis: 1,
        data: <?php echo json_encode($final_data['pareto_data']['count'],true); ?>,
        tooltip: {
            valueSuffix: ' Count'
        }

    }, {
        name: 'Percentage',
        type: 'spline',
        data:<?php echo json_encode($final_data['pareto_data']['per'],true); ?>,
        tooltip: {
            valueSuffix: '%'
        }
    }]
    
});
</script>

<script>

Highcharts.chart('with_fatel', {
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
        stops: [
            [0.1, '#fd3995'], // red
            [0.5, '#ffb822'], // yellow
            [0.9, '#34bfa3'] // gree
        ],
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
    
});

    

</script>

<script>

Highcharts.chart('without_fatel', {
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
        stops: [
            [0.1, '#fd3995'], // red
            [0.5, '#ffb822'], // yellow
            [0.9, '#34bfa3'] // gree
        ],
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
    
});
</script>

</body>

</html>

<script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
(function (H) {
    
        var chartnew = $('#paramerer_wise_compilance').highcharts();
        var render_width = 800;
        var render_height = render_width * chartnew.chartHeight / chartnew.chartWidth
        var svg = chartnew.getSVG({
            exporting: {
                sourceWidth: chartnew.chartWidth,
                sourceHeight: chartnew.chartHeight
            }
        });
        var canvasnew = document.createElement('canvas');
        canvasnew.height = render_height;
        canvasnew.width = render_width;
        var imagenew = new Image;
        imagenew.onload = function () {
         //  alert('ok');
            canvasnew.getContext('2d').drawImage(this, 0, 0, render_width, render_height);
            var data = canvasnew.toDataURL("pdf/image");
             downloadnew(data, 'paramerer_wise_compilance.png');
            //    alert(data);
            // var image = new Image();
            // image.src = data;
            //document.body.appendChild(imagenew);
          //  document.html.appendChild(image);
            // $('#p').html() = data;
            //download(data, filename + '.png');
        }
        imagenew.src = 'data:image/svg+xml;base64,' + window.btoa(svg);
      //  $("head").remove();
      //  $("div").remove();
      //  $("script").remove();
    }(Highcharts));
    function downloadnew(data, filename) {
       
        var b = document.createElement('b');
        b.download = filename;
        save_img_new(data, filename);
    }
    function save_img_new(data, filename) {
      //  alert('yes');
        $.post('/save_png', {data: data, filename: filename}, function (res) {
        });
    }

</script>

<script> 

(function (H) {
    var chartnew = $('#nonScoring').highcharts();
    var render_width = 800;
    var render_height = render_width * chartnew.chartHeight / chartnew.chartWidth
    var svg = chartnew.getSVG({
        exporting: {
            sourceWidth: chartnew.chartWidth,
            sourceHeight: chartnew.chartHeight
        }
    });
    var canvasnew = document.createElement('canvas');
    canvasnew.height = render_height;
    canvasnew.width = render_width;
    var imagenew = new Image;
    imagenew.onload = function () {
     //  alert('ok');
        canvasnew.getContext('2d').drawImage(this, 0, 0, render_width, render_height);
        var data = canvasnew.toDataURL("pdf/image");
         downloadnew(data, 'nonScoring.png');
        //    alert(data);
        // var image = new Image();
        // image.src = data;
        //document.body.appendChild(imagenew);
      //  document.html.appendChild(image);
        // $('#p').html() = data;
        //download(data, filename + '.png');
    }
    imagenew.src = 'data:image/svg+xml;base64,' + window.btoa(unescape(encodeURIComponent(svg)));
  //  $("head").remove();
  //  $("div").remove();
  //  $("script").remove();
}(Highcharts));
function downloadnew(data, filename) {
   
    var b = document.createElement('b');
    b.download = filename;
    save_img_new(data, filename);
}
function save_img_new(data, filename) {
  //  alert('yes');
    $.post('/save_png', {data: data, filename: filename}, function (res) {
    });
}
</script>

<script> 

    (function (H) {
        var chartnew = $('#agent_performance').highcharts();
        var render_width = 800;
        var render_height = render_width * chartnew.chartHeight / chartnew.chartWidth
        var svg = chartnew.getSVG({
            exporting: {
                sourceWidth: chartnew.chartWidth,
                sourceHeight: chartnew.chartHeight
            }
        });
        var canvasnew = document.createElement('canvas');
        canvasnew.height = render_height;
        canvasnew.width = render_width;
        var imagenew = new Image;
        imagenew.onload = function () {
         //  alert('ok');
            canvasnew.getContext('2d').drawImage(this, 0, 0, render_width, render_height);
            var data = canvasnew.toDataURL("pdf/image");
             downloadnew(data, 'agent_performance.png');
            //    alert(data);
            // var image = new Image();
            // image.src = data;
            //document.body.appendChild(imagenew);
          //  document.html.appendChild(image);
            // $('#p').html() = data;
            //download(data, filename + '.png');
        }
        imagenew.src = 'data:image/svg+xml;base64,' + window.btoa(svg);
      //  $("head").remove();
      //  $("div").remove();
      //  $("script").remove();
    }(Highcharts));
    function downloadnew(data, filename) {
       
        var b = document.createElement('b');
        b.download = filename;
        save_img_new(data, filename);
    }
    function save_img_new(data, filename) {
      //  alert('yes');
        $.post('/save_png', {data: data, filename: filename}, function (res) {
        });
    }
    </script>
    <script> 

    (function (H) {
        var chartnew = $('#with_fatel').highcharts();
        var render_width = 800;
        var render_height = render_width * chartnew.chartHeight / chartnew.chartWidth
        var svg = chartnew.getSVG({
            exporting: {
                sourceWidth: chartnew.chartWidth,
                sourceHeight: chartnew.chartHeight
            }
        });
        var canvasnew = document.createElement('canvas');
        canvasnew.height = render_height;
        canvasnew.width = render_width;
        var imagenew = new Image;
        imagenew.onload = function () {
         //  alert('ok');
            canvasnew.getContext('2d').drawImage(this, 0, 0, render_width, render_height);
            var data = canvasnew.toDataURL("pdf/image");
             downloadnew(data, 'with_fatel.png');
            //    alert(data);
            // var image = new Image();
            // image.src = data;
            //document.body.appendChild(imagenew);
          //  document.html.appendChild(image);
            // $('#p').html() = data;
            //download(data, filename + '.png');
        }
        imagenew.src = 'data:image/svg+xml;base64,' + window.btoa(svg);
      //  $("head").remove();
      //  $("div").remove();
      //  $("script").remove();
    }(Highcharts));
    function downloadnew(data, filename) {
       
        var b = document.createElement('b');
        b.download = filename;
        save_img_new(data, filename);
    }
    function save_img_new(data, filename) {
      //  alert('yes');
        $.post('/save_png', {data: data, filename: filename}, function (res) {
        });
    }
    </script>
    <script> 

    (function (H) {
        var chartnew = $('#without_fatel').highcharts();
        var render_width = 800;
        var render_height = render_width * chartnew.chartHeight / chartnew.chartWidth
        var svg = chartnew.getSVG({
            exporting: {
                sourceWidth: chartnew.chartWidth,
                sourceHeight: chartnew.chartHeight
            }
        });
        var canvasnew = document.createElement('canvas');
        canvasnew.height = render_height;
        canvasnew.width = render_width;
        var imagenew = new Image;
        imagenew.onload = function () {
         //  alert('ok');
            canvasnew.getContext('2d').drawImage(this, 0, 0, render_width, render_height);
            var data = canvasnew.toDataURL("pdf/image");
             downloadnew(data, 'without_fatel.png');
            //    alert(data);
            // var image = new Image();
            // image.src = data;
            //document.body.appendChild(imagenew);
          //  document.html.appendChild(image);
            // $('#p').html() = data;
            //download(data, filename + '.png');
        }
        imagenew.src = 'data:image/svg+xml;base64,' + window.btoa(svg);
      //  $("head").remove();
      //  $("div").remove();
      //  $("script").remove();
    }(Highcharts));
    function downloadnew(data, filename) {
       
        var b = document.createElement('b');
        b.download = filename;
        save_img_new(data, filename);
    }
    function save_img_new(data, filename) {
      //  alert('yes');
        $.post('/save_png', {data: data, filename: filename}, function (res) {
        });
    }
    </script>

    <script> 

    (function (H) {
        var chartnew = $('#call_type_container').highcharts();
        var render_width = 800;
        var render_height = render_width * chartnew.chartHeight / chartnew.chartWidth
        var svg = chartnew.getSVG({
            exporting: {
                sourceWidth: chartnew.chartWidth,
                sourceHeight: chartnew.chartHeight
            }
        });
        var canvasnew = document.createElement('canvas');
        canvasnew.height = render_height;
        canvasnew.width = render_width;
        var imagenew = new Image;
        imagenew.onload = function () {
         //  alert('ok');
            canvasnew.getContext('2d').drawImage(this, 0, 0, render_width, render_height);
            var data = canvasnew.toDataURL("pdf/image");
             downloadnew(data, 'call_type_container.png');
            //    alert(data);
            // var image = new Image();
            // image.src = data;
            //document.body.appendChild(imagenew);
          //  document.html.appendChild(image);
            // $('#p').html() = data;
            //download(data, filename + '.png');
        }
        imagenew.src = 'data:image/svg+xml;base64,' + window.btoa(svg);
      //  $("head").remove();
      //  $("div").remove();
      //  $("script").remove();
    }(Highcharts));
    function downloadnew(data, filename) {
       
        var b = document.createElement('b');
        b.download = filename;
        save_img_new(data, filename);
    }
    function save_img_new(data, filename) {
      //  alert('yes');
        $.post('/save_png', {data: data, filename: filename}, function (res) {
        });
    }
    </script>

    <script> 

    (function (H) {
        var chartnew = $('#disposition_wise_compliance').highcharts();
        var render_width = 800;
        var render_height = render_width * chartnew.chartHeight / chartnew.chartWidth
        var svg = chartnew.getSVG({
            exporting: {
                sourceWidth: chartnew.chartWidth,
                sourceHeight: chartnew.chartHeight
            }
        });
        var canvasnew = document.createElement('canvas');
        canvasnew.height = render_height;
        canvasnew.width = render_width;
        var imagenew = new Image;
        imagenew.onload = function () {
         //  alert('ok');
            canvasnew.getContext('2d').drawImage(this, 0, 0, render_width, render_height);
            var data = canvasnew.toDataURL("pdf/image");
             downloadnew(data, 'disposition_wise_compliance.png');
            //    alert(data);
            // var image = new Image();
            // image.src = data;
            //document.body.appendChild(imagenew);
          //  document.html.appendChild(image);
            // $('#p').html() = data;
            //download(data, filename + '.png');
        }
        imagenew.src = 'data:image/svg+xml;base64,' + window.btoa(unescape(encodeURIComponent(svg)));
      //  $("head").remove();
      //  $("div").remove();
      //  $("script").remove();
    }(Highcharts));
    function downloadnew(data, filename) {
       
        var b = document.createElement('b');
        b.download = filename;
        save_img_new(data, filename);
    }
    function save_img_new(data, filename) {
      //  alert('yes');
        $.post('/save_png', {data: data, filename: filename}, function (res) {
        });
    }
    </script>

    <script> 

        <?php for($i = 0; $i < sizeof($sub_parameter); $i++) {
           ?>
        
        (function (H) {
            
            var chartnew = $('#<?php echo $sub_parameter[$i]; ?>').highcharts();
            var render_width = 800;
            var render_height = render_width * chartnew.chartHeight / chartnew.chartWidth
            var svg = chartnew.getSVG({
                exporting: {
                    sourceWidth: chartnew.chartWidth,
                    sourceHeight: chartnew.chartHeight
                }
            });
            var canvasnew = document.createElement('canvas');
            canvasnew.height = render_height;
            canvasnew.width = render_width;
            var imagenew = new Image;
            
            imagenew.onload = function () {
              
                canvasnew.getContext('2d').drawImage(this, 0, 0, render_width, render_height);
                var data = canvasnew.toDataURL("pdf/image");
                
                downloadnew(data, '<?php echo $sub_parameter[$i] . 1; ?>.png');
                
            }
            imagenew.src = 'data:image/svg+xml;base64,' + window.btoa(unescape(encodeURIComponent(svg)));
        }(Highcharts));
        function downloadnew(data, filename) {
        
            var b = document.createElement('b');
            b.download = filename;
            save_img_new(data, filename);
        }
        function save_img_new(data, filename) {
        //  alert('yes');
            $.post('/save_png', {data: data, filename: filename}, function (res) {
            });
        }

        <?php } ?>
    </script>

    

    <script> 

    (function (H) {
        var chartnew = $('#pareto_data').highcharts();
        var render_width = 800;
        var render_height = render_width * chartnew.chartHeight / chartnew.chartWidth
        var svg = chartnew.getSVG({
            exporting: {
                sourceWidth: chartnew.chartWidth,
                sourceHeight: chartnew.chartHeight
            }
        });
        var canvasnew = document.createElement('canvas');
        canvasnew.height = render_height;
        canvasnew.width = render_width;
        var imagenew = new Image;
        imagenew.onload = function () {
         //  alert('ok');
            canvasnew.getContext('2d').drawImage(this, 0, 0, render_width, render_height);
            var data = canvasnew.toDataURL("pdf/image");
             downloadnew(data, 'pareto_data.png');
            //    alert(data);
            // var image = new Image();
            // image.src = data;
            //document.body.appendChild(imagenew);
          //  document.html.appendChild(image);
            // $('#p').html() = data;
            //download(data, filename + '.png');
        }
        imagenew.src = 'data:image/svg+xml;base64,' + window.btoa(unescape(encodeURIComponent(svg)));
      //  $("head").remove();
      //  $("div").remove();
      //  $("script").remove();
    }(Highcharts));
    function downloadnew(data, filename) {
       
        var b = document.createElement('b');
        b.download = filename;
        save_img_new(data, filename);
    }
    function save_img_new(data, filename) {
      //  alert('yes');
        $.post('/save_png', {data: data, filename: filename}, function (res) {
        });
    }
    </script>