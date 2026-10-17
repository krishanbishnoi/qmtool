@extends('layouts.app_third')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<style>
    #cover-spin {
        position: fixed;
        width: 100%;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.7);
        z-index: 9999;
        display: none;
    }

    @-webkit-keyframes spin {
        from {
            -webkit-transform: rotate(0deg);
        }

        to {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    #cover-spin::after {
        content: '';
        display: block;
        position: absolute;
        left: 48%;
        top: 40%;
        width: 40px;
        height: 40px;
        border-style: solid;
        border-color: black;
        border-top-color: transparent;
        border-width: 4px;
        border-radius: 50%;
        -webkit-animation: spin .8s linear infinite;
        animation: spin .8s linear infinite;
    }
</style>
@section('sh-title')
    Cases Dashboard
@endsection


@section('sh-toolbar')
    <style>
        .plus,
        .minus {
            position: relative;
            display: block;
        }

        .icon-change.plus:after,
        .icon-change.minus:after {
            color: rgb(65, 105, 225);
            position: absolute;
            margin-left: 7px;
            top: 1px;
            font-weight: bold;
        }

        .plus:after {
            content: '+';
        }

        .minus:after {
            content: '\2212';
            top: 1px !important;
        }
    </style>
@endsection


@section('main')
    <!-- Main part start here -->
    <div id="cover-spin"></div>

    <div class="kt-portlet">
        <div class="kt-portlet__body  kt-portlet__body--fit">
            <div class="row row-no-padding row-col-separator-xl">

                <div class="col-md-12 col-lg-3 col-xl-3 card">
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title ">Total Cases</h4>
                            </div>
                            <span class="kt-widget24__stats kt-font-brand" style="color:black">{{$final_data['total_cases']}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-3 col-xl-3 card">
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">Total Audits </h4>
                            </div>
                            <span class="kt-widget24__stats kt-font-warning" style="color:black">{{$final_data['total_audits'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-3 col-xl-3 card">
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">Fatal Cases</h4>

                            </div>
                            <span class="kt-widget24__stats kt-font-warning" style="color:black">  {{$final_data['fatal_cases']}}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-lg-3 col-xl-3 card">
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">NoN Fatal cases</h4>
                            </div>
                            <span class="kt-widget24__stats kt-font-warning" style="color:black">{{ $final_data['Not_fatal'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            

        </div>
    </div>
    <div class="kt-portlet">
        <div class="kt-portlet__body  kt-portlet__body--fit">
            
            <div class="row row-no-padding row-col-separator-xl">

                <div class="col card">
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title ">Total Sub Parameter </h4>
                            </div>
                            <span class="kt-widget24__stats kt-font-brand" style="color:black">{{ $final_data['total_subP_opp_count'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="col card">
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title ">Totat Defect SubParameter</h4>
                            </div>
                            <span class="kt-widget24__stats kt-font-brand" style="color:black">{{ $final_data['failed_subp'] }}</span>
                        </div>
                    </div>
                </div>           
                
                <div class="col card">
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title ">Defect Parameters %</h4>
                            </div>
                            <span class="kt-widget24__stats kt-font-brand" style="color:black">{{ $final_data['failed_sub_count'] }}%</span>
                        </div>
                    </div>
                </div> 
             
            </div>

        </div>
    </div>



    <div class="kt-portlet">
        <div class="kt-portlet__body  kt-portlet__body--fit">
            <div class="row row-no-padding row-col-separator-xl">
                <div class="col-md-12 col-lg-12 col-xl-12">
                    <div class="kt-widget24">
                     <div id="container" style="width: 100%; height: 500px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="kt-portlet">
        <div class="kt-portlet__body  kt-portlet__body--fit">
            <div class="row row-no-padding row-col-separator-xl">
                <div class="col-md-12 col-lg-12 col-xl-12">
                    <div class="kt-widget24">
                     <div id="container-2" style="width: 100%; height: 500px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')

<script>
   
    document.addEventListener('DOMContentLoaded', function () {
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Defect Sub Parameter wise pareto'
            },
            xAxis: {
                categories: @json($highchartsData['subparameters'])
            },
            yAxis: [{
                min: 0,
                title: {
                    text: 'Counts'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (
                            Highcharts.defaultOptions.title.style &&
                            Highcharts.defaultOptions.title.style.color
                        ) || 'gray'
                    }
                }
            }, {
                title: {
                    text: 'Cumulative Percentage'
                },
                opposite: true,
                min: 0,
                max: 100,
                labels: {
                    format: '{value}%'
                }
            }],
            plotOptions: {
                column: {
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}'
                    }
                },
                spline: {
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}%'
                    }
                }
            },
            series: [{
                name: 'Audit Counts',
                data: @json($highchartsData['auditcounts']),
                yAxis: 0
            }, {
                name: 'Cumulative Percentage',
                data: @json($highchartsData['cumulatives']),
                type: 'spline',
                yAxis: 1
            }]
        });
    });

    
    document.addEventListener('DOMContentLoaded', function () {
        Highcharts.chart('container-2', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Agent Wise Failure Pareto'
            },
            xAxis: {
                categories: @json($agentWiseData['agents_name'])
            },
            yAxis: [{
                min: 0,
                title: {
                    text: 'Counts'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (
                            Highcharts.defaultOptions.title.style &&
                            Highcharts.defaultOptions.title.style.color
                        ) || 'gray'
                    }
                }
            }, {
                title: {
                    text: 'Cumulative Percentage'
                },
                opposite: true,
                min: 0,
                max: 100,
                labels: {
                    format: '{value}%'
                }
            }],
            plotOptions: {
                column: {
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}'
                    }
                },
                spline: {
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}%'
                    }
                }
            },
            series: [{
                name: 'Audit Counts',
                data: @json($agentWiseData['total_audits']),
                yAxis: 0
            }, {
                name: 'Cumulative Percentage',
                data: @json($agentWiseData['defect_percent']),
                type: 'spline',
                yAxis: 1
            }]
        });
    });
</script>
    

@endsection
@section('css')
    <link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
