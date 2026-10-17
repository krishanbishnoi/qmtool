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
    Dashboard
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

    <div class="kt-subheader__toolbar">
        <div class="kt-subheader__wrapper">
            <a href="/welcome_dashboard_new" class="btn btn-label-brand btn-bold">
                Welcome Dashboard
            </a>
            <a href="/test_html_new_get" class="btn btn-label-brand btn-bold">
                Detail Dashboard
            </a>
       </div>
    </div>
@endsection


@section('main')
    <!-- Main part start here -->
    <div id="cover-spin"></div>
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">Search / Filter</h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="row">
                <div class="col-sm-3">
                    <div class="pl-4">Select Date Range</div>
                    <input type='text' class="form-control ml-4" name="target_month" id="datepicker123"
                        required="required" />
                </div>
                <div class="col-sm-2">
                    <br>
                    <input type="submit" onclick="api_calling()" style="width: 100px;"
                        class="btn btn-outline-brand d-block" value="Search">
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet">
        <div class="kt-portlet__body  kt-portlet__body--fit">
            <div class="row row-no-padding row-col-separator-xl">

                <div class="col-md-12 col-lg-6 col-xl-6 card">

                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title ">My Team Score</h4>
                                <span class="kt-widget24__desc" style="color:black">Target</span>
                            </div>
                            <span class="kt-widget24__stats kt-font-brand" style="color:black">1000</span>
                        </div>
                        <div class="progress progress--sm">
                            <div class="progress-bar kt-bg-brand" role="progressbar" id="progress_bar_achieve"
                                style="width: 70%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="kt-widget24__action">
                            <span class="kt-widget24__change" style="color:black">Achieved</span>
                            <span class="kt-widget24__number" id = "my_team_achieve" style="color:black"></span>
                        </div>
                    </div>

                    <div class="row">

                        <div class="kt-widget24 col-md-6 col-lg-6 col-xl-6">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <span style="color:black">Rebuttal</span>
                                </div>
                                <span class="" id = "my_team_rebuttal" style="color:black"></span>
                            </div>
                            <div class="progress progress--sm">
                                <div id="progress_bar_rebuttal" class="progress-bar kt-bg-warning" role="progressbar"
                                    :style="'width': '70';" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="kt-widget24__action">
                                <span class="kt-widget24__change" style="color:black">Accepted | Rejected</span>
                                <span class="kt-widget24__number" id = "my_team_rebuttal_accepted"
                                    style="color:black"></span>
                            </div>


                        </div>

                        <div class="kt-widget24 col-md-6 col-lg-6 col-xl-6">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <span style="color:black">Fatal</span>
                                </div>
                                <span style="color:red" id="fatal_count_my_team"></span>
                            </div>
                            <div class="progress progress--sm">
                                <div id="progress_bar_fatal" class="progress-bar kt-bg-danger" role="progressbar"
                                    :style="'width': '70';" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="kt-widget24__action">
                                <span class="kt-widget24__change" style="color:black">Count</span>
                                <span class="kt-widget24__number" id = "fatal_count_my_team_one" style="color:black"></span>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="kt-widget24 col-md-6 col-lg-6 col-xl-6">
                            <div class="kt-widget24__details">
                                <div id="with_fatal_score_my_team"></div>
                            </div>
                            <div style="text-align: center; color:black"><span>With Fatal Score</span></div>
                        </div>
                        <div class="kt-widget24 col-md-6 col-lg-6 col-xl-6">
                            <div class="kt-widget24__details">
                                <div id="without_fatal_score_my_team"></div>

                            </div>
                            <div style="text-align: center; color:black;"><span>Without Fatal Score</span></div>

                        </div>
                    </div>

                </div>



                <div class="col-md-12 col-lg-6 col-xl-6 card">
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">Overall Score</h4>
                                <span class="kt-widget24__desc" style="color:black">Target</span>
                            </div>
                            <span class="kt-widget24__stats kt-font-warning" style="color:black">15000</span>
                        </div>
                        <div class="progress progress--sm">
                            <div id="progress_bar_achieve_overall" class="progress-bar kt-bg-brand" role="progressbar"
                                aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="kt-widget24__action">
                            <span class="kt-widget24__change" style="color:black">Achieveed </span>
                            <span class="kt-widget24__number" id="overall_team_achieve" style="color:black"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="kt-widget24 col-md-6 col-lg-6 col-xl-6">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <span style="color:black">Rebuttal</span>
                                </div>
                                <span class="" id = "overall_team_rebuttal" style="color:black"></span>
                            </div>
                            <div class="progress progress--sm">
                                <div id="progress_bar_rebuttal_overall" class="progress-bar kt-bg-warning"
                                    role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="kt-widget24__action">
                                <span class="kt-widget24__change" style="color:black">Accepted | Rejected</span>
                                <span class="kt-widget24__number" id = "overall_rebuttal" style="color:black"></span>
                            </div>
                        </div>

                        <div class="kt-widget24 col-md-6 col-lg-6 col-xl-6">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <span style="color:black">Fatal</span>
                                </div>
                                <span style="color:red" id="overall_fatal_count"></span>
                            </div>
                            <div class="progress progress--sm">
                                <div id="progress_bar_fatal_overall" class="progress-bar kt-bg-danger" role="progressbar"
                                    aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="kt-widget24__action">
                                <span class="kt-widget24__change" style="color:black">Count</span>
                                <span class="kt-widget24__number" id = "overall_fatal_count_one"
                                    style="color:black"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="kt-widget24 col-md-6 col-lg-6 col-xl-6">
                            <div class="kt-widget24__details">
                                <div id="with_fatal_score_overall"></div>
                            </div>
                            <div style="text-align: center; color:black"><span>With Fatal Score</span></div>
                        </div>
                        <div class="kt-widget24 col-md-6 col-lg-6 col-xl-6">
                            <div class="kt-widget24__details">
                                <div id="without_fatal_score_overall"></div>
                            </div>
                            <div style="text-align: center; color:black;"><span>Without Fatal Score</span></div>
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
                        <figure class="highcharts-figure">
                            <div id="qa_performance_piller_chart_container"></div>
                            <p class="highcharts-description">

                            </p>
                        </figure>

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
                        <div class="kt-widget24__info">
                            <figure class="highcharts-figure">
                                <div id="qc_deviation_chart"></div>
                                <p class="highcharts-description">

                                </p>
                            </figure>
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
                        <figure class="highcharts-figure">
                            <div id="qa_process_chart"></div>
                            <p class="highcharts-description">

                            </p>
                        </figure>

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

                        <div id="chartdiv" style="width: 100%; height: 500px;"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var start_date = '';
        var end_date = '';
        $(function() {
            $("#datepicker123").daterangepicker({
                opens: 'right'
            }, function(start, end, label) {
                start_date = start.format('YYYY-MM-DD');
                end_date = end.format('YYYY-MM-DD');
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
                    .format('YYYY-MM-DD'));
            });
        });


        function getCycle(val) {

            var base_url = window.location.origin;
            if (val != 0) {
                $.ajax({
                    type: "GET",
                    url: base_url + "/dashboard/get_partner_audit_cycle/" + val,
                    success: function(Data) {
                        $("#audit_cycle").html(Data);
                    }
                });


            }
        }


        function check_cycle() {
            var cycle = document.getElementById("audit_cycle").value;
            if (cycle == "blank") {
                document.getElementById("warning").style.display = "block";
                //document.getElementById("audit_cycle").value
                return false;
            } else {
                return true;
            }
            //console.log(cycle);

        }
    </script>

    <script type="application/javascript">
var auditor_name_list = [];
var audit_done = [];
var fatal = [];
var rebuttal_raised = [];
var sale_audits = [];
var non_sale_audits = [];


var qa_performance_piller_chart = Highcharts.chart('qa_performance_piller_chart_container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Auditor Performance'
    },
    subtitle: {
        text: ''
    },
    legend: {
        align: 'right',
        verticalAlign: 'middle',
        layout: 'vertical'
    },
    xAxis: {
        categories: auditor_name_list,
        labels: {
            x: -10
        }
    },
    yAxis: {
        allowDecimals: false,
        title: {
            text: 'Count'
        }
    },
    series: [{
        name: 'Audit',
        data: audit_done
    }, {
        name: 'Rebuttal',
        data: rebuttal_raised
    }, {
        name: 'Fatal',
        data: fatal
    }, {
        name: 'Sale Audits',
        data: sale_audits
    }, {
        name: 'Non-Sale Audits', 
        data: non_sale_audits
    }],
    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    align: 'center',
                    verticalAlign: 'bottom',
                    layout: 'horizontal'
                },
                yAxis: {
                    labels: {
                        align: 'left',
                        x: 0,
                        y: -5
                    },
                    title: {
                        text: null
                    }
                },
                subtitle: {
                    text: null
                },
                credits: {
                    enabled: false
                }
            }
        }]
    }
});

// End qa performance chart here

var qc_deviation_chart = Highcharts.chart('qc_deviation_chart', {

chart: {
    type: 'column'
},

title: {
    text: 'QC Performance'
},

subtitle: {
    text: ''
},

legend: {
    align: 'right',
    verticalAlign: 'middle',
    layout: 'vertical'
},

xAxis: {
    categories: auditor_name_list,
    labels: {
        x: -10
    }
},

yAxis: {
    allowDecimals: false,
    title: {
        text: 'Count'
    }
},

series: [
	{
    name: 'QC Count',
    data: audit_done
}],

responsive: {
    rules: [{
        condition: {
            maxWidth: 500
        },
        chartOptions: {
            legend: {
                align: 'center',
                verticalAlign: 'bottom',
                layout: 'horizontal'
            },
            yAxis: {
                labels: {
                    align: 'left',
                    x: 0,
                    y: -5
                },
                title: {
                    text: null
                }
            },
            subtitle: {
                text: null
            },
            credits: {
                enabled: false
            }
        }
    }]
}
});


var process_list = [];
var qa_process_chart = Highcharts.chart('qa_process_chart', {

chart: {
    type: 'column'
},

title: {
    text: 'Process wise performance'
},

subtitle: {
    text: ''
},

legend: {
    align: 'right',
    verticalAlign: 'middle',
    layout: 'vertical'
},

xAxis: {
    categories: auditor_name_list,
    labels: {
        x: -10
    }
},

yAxis: {
    allowDecimals: false,
    title: {
        text: 'Count'
    }
},

series: [
	{
    name: 'Audit',
    data: audit_done
}, {
    name: 'Rebuttal',
    data: rebuttal_raised
}, {
    name: 'Fatal',
    data: fatal
}],

responsive: {
    rules: [{
        condition: {
            maxWidth: 500
        },
        chartOptions: {
            legend: {
                align: 'center',
                verticalAlign: 'bottom',
                layout: 'horizontal'
            },
            yAxis: {
                labels: {
                    align: 'left',
                    x: 0,
                    y: -5
                },
                title: {
                    text: null
                }
            },
            subtitle: {
                text: null
            },
            credits: {
                enabled: false
            }
        }
    }]
}
});

// Guage chart start
function guageChart(chart_div_id, score_percentage){
    // Themes begin
    am4core.useTheme(am4themes_animated);
    //Remove Credits
    am4core.addLicense("ch-custom-attribution");
    var chart = am4core.create(chart_div_id, am4charts.GaugeChart);
    chart.innerRadius = -15;
    var axis = chart.xAxes.push(new am4charts.ValueAxis());
    axis.min = 0;
    axis.max = 100;
    axis.strictMinMax = true;
    var colorSet = new am4core.ColorSet();
    var gradient = new am4core.LinearGradient();
    gradient.stops.push({color:am4core.color("red")})
    gradient.stops.push({color:am4core.color("yellow")})
    gradient.stops.push({color:am4core.color("green")})
    axis.renderer.line.stroke = gradient;
    axis.renderer.line.strokeWidth = 15;
    axis.renderer.line.strokeOpacity = 1;
    axis.renderer.grid.template.disabled = true;
    var hand = chart.hands.push(new am4charts.ClockHand());
    hand.radius = am4core.percent(97);
    hand.showValue(score_percentage, am4core.ease.cubicOut);
}
// Guage Chart End here

// Pareto chart start here

function api_calling(){

    call_all_api(start_date,end_date);
}

(function () {

    call_all_api("2021-09-01","2021-09-31");
})();

function pareto_for_rebuttals(pareto_data){
    
    
    am4core.ready(function() {

    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart = am4core.create("chartdiv", am4charts.XYChart);
    chart.scrollbarX = new am4core.Scrollbar();
    chart.exporting.menu = new am4core.ExportMenu();

    var title = chart.titles.create();
    title.text = "Rebuttal Defects";
    title.fontSize = 16;
    title.fontFamily = "Arial";
    title.marginBottom = 10;
    // Add data
    chart.data = pareto_data;

    prepareParetoData(chart);

    // Create axes
    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "country";
    /* categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.minGridDistance = 60;
    categoryAxis.tooltip.disabled = true; */

    // Configure axis label
    var label = categoryAxis.renderer.labels.template;
    label.truncate = true;
    label.maxWidth = 200;
    label.tooltipText = "{country}";

    categoryAxis.events.on("sizechanged", function(ev) {
    var axis = ev.target;
    var cellWidth = axis.pixelWidth / (axis.endIndex - axis.startIndex);
    if (cellWidth < axis.renderer.labels.template.maxWidth) {
        axis.renderer.labels.template.rotation = -45;
        axis.renderer.labels.template.horizontalCenter = "right";
        axis.renderer.labels.template.verticalCenter = "middle";
    }
    else {
        axis.renderer.labels.template.rotation = 0;
        axis.renderer.labels.template.horizontalCenter = "middle";
        axis.renderer.labels.template.verticalCenter = "top";
    }
    });

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.renderer.minWidth = 10;
    valueAxis.min = 0;
    valueAxis.cursorTooltipEnabled = false;

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.sequencedInterpolation = true;
    series.dataFields.valueY = "visits";
    series.dataFields.categoryX = "country";
    series.tooltipText = "[{categoryX}: bold]Rebuttal:{valueY}[/]";
    series.columns.template.strokeWidth = 0;

    series.tooltip.pointerOrientation = "vertical";

    series.columns.template.column.cornerRadiusTopLeft = 10;
    series.columns.template.column.cornerRadiusTopRight = 10;
    series.columns.template.column.fillOpacity = 0.8;
    series.columns.template.width = am4core.percent(50);

    // on hover, make corner radiuses bigger
    var hoverState = series.columns.template.column.states.create("hover");
    hoverState.properties.cornerRadiusTopLeft = 0;
    hoverState.properties.cornerRadiusTopRight = 0;
    hoverState.properties.fillOpacity = 1;

    series.columns.template.adapter.add("fill", function(fill, target) {

    /* return chart.colors.getIndex(target.dataItem.index); */
    return chart.colors.getIndex(0);
    })

    var paretoValueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    paretoValueAxis.renderer.opposite = true;
    paretoValueAxis.min = 0;
    paretoValueAxis.max = 100;
    paretoValueAxis.strictMinMax = true;
    paretoValueAxis.renderer.grid.template.disabled = true;
    paretoValueAxis.numberFormatter = new am4core.NumberFormatter();
    paretoValueAxis.numberFormatter.numberFormat = "''#'%'"
    paretoValueAxis.cursorTooltipEnabled = false;

    var paretoSeries = chart.series.push(new am4charts.LineSeries())
    paretoSeries.dataFields.valueY = "pareto";
    paretoSeries.dataFields.categoryX = "country";
    paretoSeries.yAxis = paretoValueAxis;
    paretoSeries.tooltipText = "Per: {valueY.formatNumber('#.0')}%[/]";
    paretoSeries.bullets.push(new am4charts.CircleBullet());
    paretoSeries.strokeWidth = 2;
    paretoSeries.stroke = new am4core.InterfaceColorSet().getFor("alternativeBackground");
    paretoSeries.strokeOpacity = 0.5;

    // Cursor
    chart.cursor = new am4charts.XYCursor();
    chart.cursor.behavior = "panX";

    }); 

}

function prepareParetoData(chart){
    var total = 0;

    for(var i = 0; i < chart.data.length; i++){
        var value = chart.data[i].visits;
        total += value;
    }

    var sum = 0;
    for(var i = 0; i < chart.data.length; i++){
        var value = chart.data[i].visits;
        sum += value;   
        chart.data[i].pareto = sum / total * 100;
    }    
}

// Pareto chart ends here
function get_my_team_score(startDate,endDate){
    var base_url = window.location.origin;
    $.ajax({
        type: "GET",
        url: base_url + "/qtl_dashboard/my_team_score/"+startDate+"/"+endDate,
        success: function(Data){
            $("#my_team_achieve").html(Data.data.audit_count);
            $("#progress_bar_achieve").css("width", ((Data.data.audit_count/10000)*100)+"%");
			$("#progress_bar_rebuttal").css("width", ((Data.data.rebuttal_count/Data.data.audit_count)*100)+"%");
            $("#progress_bar_fatal").css("width", ((Data.data.fatal_count/Data.data.audit_count)*100)+"%");

            $("#my_team_rebuttal").html(Data.data.rebuttal_count);
            
            $("#my_team_rebuttal_accepted").html(Data.data.rebuttal_accepted +" | "+ Data.data.rebuttal_rejected);
            $("#fatal_count_my_team").html(Data.data.fatal_count);
            $("#fatal_count_my_team_one").html(Data.data.fatal_count);
            guageChart("with_fatal_score_my_team", Data.data.with_fatal_score);
            guageChart("without_fatal_score_my_team", Data.data.without_fatal_score);
        }
    });
}


function get_qa_performance_chart_data(startDate,endDate){
    var base_url = window.location.origin;
    $.ajax({
		type: "GET",
		url: base_url + "/qtl_dashboard/qtl_dashboard_qa_performance_piller_chart_data/"+startDate+"/"+endDate,
		success: function(Data){
			qa_performance_piller_chart.series[0].setData(Data.data.audit_done);
            qa_performance_piller_chart.series[1].setData(Data.data.rebuttal_raised);
            qa_performance_piller_chart.series[2].setData(Data.data.fatal);
            qa_performance_piller_chart.series[3].setData(Data.data.sale_audits); 
            qa_performance_piller_chart.series[4].setData(Data.data.non_sale_audits); 
			qa_performance_piller_chart.xAxis[0].setCategories(Data.data.auditor_name_list);
		}
	});
}


function get_qa_process_chart_data(startDate,endDate){
    var base_url = window.location.origin;
    $.ajax({
		type: "GET",
		url: base_url + "/qtl_dashboard/qtl_dashboard_process_wise_performance_data/"+startDate+"/"+endDate,
		success: function(Data){
			qa_process_chart.series[0].setData(Data.data.audit_done);
            qa_process_chart.series[1].setData(Data.data.rebuttal_raised);
            qa_process_chart.series[2].setData(Data.data.fatal);
			qa_process_chart.xAxis[0].setCategories(Data.data.process_list);
            qtl_dashboard_pareto_rebuttal_data(startDate,endDate,Data.data.process_list_id[1]);
        }
	});

}

function get_qc_deviation_chart_data(startDate,endDate){
	var base_url = window.location.origin;
    $.ajax({
		type: "GET",
		url: base_url + "/qtl_dashboard/qtl_dashboard_qc_deviation_piller_chart_data/"+startDate+"/"+endDate,
		success: function(Data){
			qc_deviation_chart.xAxis[0].setCategories(Data.data.users);
			qc_deviation_chart.series[0].setData(Data.data.number);
		}
	});
}

function get_overall_score(startDate,endDate){
	var base_url = window.location.origin;
    $.ajax({
		type: "GET",
		url: base_url + "/qtl_dashboard/overall_score/"+startDate+"/"+endDate,
		success: function(Data){
            $("#overall_team_achieve").html(Data.data.audit_count);
			$("#overall_team_rebuttal").html(Data.data.rebuttal_count);
            $("#overall_rebuttal").html(Data.data.rebuttal_accepted +" | "+ Data.data.rebuttal_rejected);
            $("#overall_fatal_count").html(Data.data.fatal_count);
            $("#overall_fatal_count_one").html(Data.data.fatal_count);
            guageChart("with_fatal_score_overall", Data.data.with_fatal_score);
            guageChart("without_fatal_score_overall", Data.data.without_fatal_score);
		
            $("#progress_bar_achieve_overall").css("width", ((Data.data.audit_count/10000)*100)+"%");
			$("#progress_bar_rebuttal_overall").css("width", ((Data.data.rebuttal_count/Data.data.audit_count)*100)+"%");
            $("#progress_bar_fatal_overall").css("width", ((Data.data.fatal_count/Data.data.audit_count)*100)+"%");
        }
	});

}

/* function get_overall_score(startDate,endDate){
	var base_url = window.location.origin;
    $.ajax({
		type: "GET",
		url: base_url + "/qtl_dashboard/overall_score/"+startDate+"/"+endDate,
		success: function(Data){
            $("#overall_team_achieve").html(Data.data.audit_count);
			$("#overall_team_rebuttal").html(Data.data.rebuttal_count);
            $("#overall_rebuttal").html(Data.data.rebuttal_accepted +" | "+ Data.data.rebuttal_rejected);
            $("#overall_fatal_count").html(Data.data.fatal_count);
            $("#overall_fatal_count_one").html(Data.data.fatal_count);
            guageChart("with_fatal_score_overall", Data.data.with_fatal_score);
            guageChart("without_fatal_score_overall", Data.data.without_fatal_score);
		}
	});
} */

function qtl_dashboard_pareto_rebuttal_data(startDate,endDate,process_id){
	var base_url = window.location.origin;
    $.ajax({
		type: "GET",
		url: base_url + "/qtl_dashboard/qtl_dashboard_pareto_rebuttal_data/"+startDate+"/"+endDate+"/"+process_id,
		success: function(Data){
            
            pareto_for_rebuttals(Data.data);
		}
	});
}



function call_all_api(startDate,endDate){
    get_my_team_score(startDate,endDate);
    get_overall_score(startDate,endDate);
    get_qa_performance_chart_data(startDate,endDate);
    get_qa_process_chart_data(startDate,endDate);
    get_qc_deviation_chart_data(startDate,endDate);
    
}



</script>
@endsection
@section('css')
    <link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
