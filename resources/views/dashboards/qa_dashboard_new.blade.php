@extends('layouts.app_third')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script src="https://cdn.amcharts.com/lib/4/plugins/forceDirected.js"></script>



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
<style>
    #chartdiv {
        width: 100%;
        height: 500px;
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


    @if (Auth::user()->parent_client == 23)
    <div class="kt-portlet">
        <div class="kt-portlet__body  kt-portlet__body--fit">
            <div class="row row-no-padding row-col-separator-xl">

                <div class="col-md-12 col-lg-6 col-xl-6 card">
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">Today My Score</h4>
                                <span class="kt-widget24__desc" style="color:black">Target</span>
                            </div>
                            <span class="kt-widget24__stats kt-font-brand" style="color:black">40</span>
                        </div>
                        <div class="progress progress--sm">
                            <div class="progress-bar kt-bg-brand" id="progress_bar_today" role="progressbar"
                                style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="kt-widget24__action">
                            <span class="kt-widget24__change" style="color:black">Achieved</span>
                            <span class="kt-widget24__number" id="today_achieve" style="color:black"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="kt-widget24 col-md-6 col-lg-6 col-xl-6">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <span style="color:black">Achieved %</span>
                                </div>
                                <span id="achived_per_today" style="color:black"></span>
                            </div>
                            <div class="progress progress--sm">
                                <div class="progress-bar kt-bg-warning" id="progress_bar_today_acivedper" role="progressbar"
                                    style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <div class="kt-widget24 col-md-6 col-lg-6 col-xl-6">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <span style="color:black">Not Achieved %</span>
                                </div>
                                <span style="color:red" id="not_achieved_today_per"></span>
                            </div>
                            <div class="progress progress--sm">
                                <div id="progress_bar_today_not_acivedper" class="progress-bar kt-bg-danger"
                                    role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-lg-6 col-xl-6 card">
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">Overall Month Score</h4>
                                <span class="kt-widget24__desc" style="color:black">Target</span>
                            </div>
                            <span class="kt-widget24__stats kt-font-warning" style="color:black">936</span>
                        </div>
                        <div class="progress progress--sm">
                            <div id="progress_bar_cycle" class="progress-bar kt-bg-brand" role="progressbar"
                                style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="kt-widget24__action">
                            <span class="kt-widget24__change" style="color:black">Achieved</span>
                            <span class="kt-widget24__number" id="overall_month_achieve" style="color:black"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="kt-widget24 col-md-6 col-lg-6 col-xl-6">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <span style="color:black">Achieved %</span>
                                </div>
                                <span id="achived_per_cycle" style="color:black"></span>
                            </div>
                            <div class="progress progress--sm">
                                <div id="progress_bar_cycle_acivedper" class="progress-bar kt-bg-warning" role="progressbar"
                                    style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>

                        <div class="kt-widget24 col-md-6 col-lg-6 col-xl-6">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <span style="color:black">Not Achieved %</span>
                                </div>
                                <span style="color:red" id="not_achieved_cycle_per"></span>
                            </div>
                            <div class="progress progress--sm">
                                <div id="progress_bar_cycle_not_acivedper" class="progress-bar kt-bg-danger"
                                    role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif




    <div class="kt-portlet">
        <div class="kt-portlet__body  kt-portlet__body--fit">
            <div class="row row-no-padding row-col-separator-xl">

                <div class="col-md-12 col-lg-6 col-xl-6 card">

                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title ">My Score</h4>
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
                                    :style="'width': '70';" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                </div>
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
                                    :style="'width': '70';" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <div class="kt-widget24__action">
                                <span class="kt-widget24__change" style="color:black">Count</span>
                                <span class="kt-widget24__number" id = "fatal_count_my_team_one"
                                    style="color:black"></span>
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
                        <div id="qa_process_chart" style="width: 100%; height: 500px;"></div>
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
                        <div id="trend_chart" style="width: 100%; height: 500px;"></div>
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
                        <div id="auditor_performance" style="width: 100%; height: 500px;"></div>
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
                        <div id="agent_performance" style="width: 100%; height: 500px;"></div>
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

                        <div id="pareto_data" style="width: 100%; height: 500px;"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {

            var base_url = window.location.origin;
            $.ajax({
                url: base_url + "/qa_dashboard/qa_dashboard_targets",
                type: 'GET',
                success: function(response) {

                    var data = response.data;

                  
                    $('#today_achieve').text(data.todays_score);                   
                    $('#overall_month_achieve').text(data.current_cycle_audits);                  
                    $('#progress_bar_today').css('width', data.achievedPerToday + '%');                   
                    $('#achived_per_today').text(data.achievedPerToday + '%');                  
                    $('#progress_bar_cycle').css('width', data.achievedPerCurrentCycle + '%');                  
                    $('#achived_per_cycle').text(data.achievedPerCurrentCycle + '%');                   
                    $('#not_achieved_today_per').text(data.not_achieved_today_per + '%');
                    $('#not_achieved_cycle_per').text(data.not_achieved_cycle_per + '%');

                    $('#progress_bar_today_acivedper').css('width', data.achievedPerToday + '%');    
                    $('#progress_bar_cycle_acivedper').css('width', data.achievedPerCurrentCycle + '%');    
                    $('#progress_bar_today_not_acivedper').css('width', data.not_achieved_today_per + '%');    
                    $('#progress_bar_cycle_not_acivedper').css('width', data.not_achieved_cycle_per + '%');    
                },

                error: function(error) {
                    console.log('Error fetching data: ', error);
                }

            });
        });


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

    call_all_api("2022-06-01","2022-06-30");
    
    
})();



function process_wise_performance(data){
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

am4core.addLicense("ch-custom-attribution");

var chart = am4core.create('qa_process_chart', am4charts.XYChart)
chart.colors.step = 2;

var title = chart.titles.create();
    title.text = "Process Wise Performance";
   title.fontSize = 20;
    title.fontFamily = "Arial";
    title.marginBottom = 10;

chart.legend = new am4charts.Legend()
chart.legend.position = 'top'
chart.legend.paddingBottom = 20
chart.legend.labels.template.maxWidth = 95

var xAxis = chart.xAxes.push(new am4charts.CategoryAxis())
xAxis.dataFields.category = 'category'
xAxis.renderer.cellStartLocation = 0.1
xAxis.renderer.cellEndLocation = 0.9
xAxis.renderer.grid.template.location = 0;

var yAxis = chart.yAxes.push(new am4charts.ValueAxis());
yAxis.min = 0;

function createSeries(value, name) {
    var series = chart.series.push(new am4charts.ColumnSeries())
    series.dataFields.valueY = value
    series.dataFields.categoryX = 'category'
    series.name = name

    series.events.on("hidden", arrangeColumns);
    series.events.on("shown", arrangeColumns);

    var bullet = series.bullets.push(new am4charts.LabelBullet())
    bullet.interactionsEnabled = false
    bullet.dy = 30;
    bullet.label.text = '{valueY}'
    bullet.label.fill = am4core.color('#ffffff')

    return series;
}

chart.data = data; 


createSeries('audit_done', 'Audit done');
createSeries('fatal', 'Fatal');
createSeries('rebuttal_raised', 'Rebuttal raised');
function arrangeColumns() {

var series = chart.series.getIndex(0);

var w = 1 - xAxis.renderer.cellStartLocation - (1 - xAxis.renderer.cellEndLocation);
if (series.dataItems.length > 1) {
    var x0 = xAxis.getX(series.dataItems.getIndex(0), "categoryX");
    var x1 = xAxis.getX(series.dataItems.getIndex(1), "categoryX");
    var delta = ((x1 - x0) / chart.series.length) * w;
    if (am4core.isNumber(delta)) {
        var middle = chart.series.length / 2;

        var newIndex = 0;
        chart.series.each(function(series) {
            if (!series.isHidden && !series.isHiding) {
                series.dummyData = newIndex;
                newIndex++;
            }
            else {
                series.dummyData = chart.series.indexOf(series);
            }
        })
        var visibleCount = newIndex;
        var newMiddle = visibleCount / 2;

        chart.series.each(function(series) {
            var trueIndex = chart.series.indexOf(series);
            var newIndex = series.dummyData;

            var dx = (newIndex - trueIndex + middle - newMiddle) * delta

            series.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
            series.bulletsContainer.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
        })
    }
}
}

}); 
} 


function trends_for_auditors(data){

am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end
am4core.addLicense("ch-custom-attribution");
// Create chart instance
var chart = am4core.create("trend_chart", am4charts.XYChart);

var title = chart.titles.create();
    title.text = "Score Trend";
   title.fontSize = 20;
    title.fontFamily = "Arial";
    title.marginBottom = 10;
// Enable chart cursor
chart.cursor = new am4charts.XYCursor();
chart.cursor.lineX.disabled = true;
chart.cursor.lineY.disabled = true;

// Enable scrollbar
chart.scrollbarX = new am4core.Scrollbar();

// Add data
chart.data = data;

// Create axes
var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
dateAxis.renderer.grid.template.location = 0.5;
dateAxis.dateFormatter.inputDateFormat = "yyyy-MM-dd";
dateAxis.renderer.minGridDistance = 40;
dateAxis.tooltipDateFormat = "MMM, yyyy";
dateAxis.dateFormats.setKey("day", "dd");

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

// Create series
var series = chart.series.push(new am4charts.LineSeries());
series.tooltipText = "{date}\n[bold font-size: 17px]value: {valueY}[/]";
series.dataFields.valueY = "value";
series.dataFields.dateX = "date";
series.strokeDasharray = 3;
series.strokeWidth = 2
series.strokeOpacity = 0.3;
series.strokeDasharray = "3,3"

var bullet = series.bullets.push(new am4charts.CircleBullet());
bullet.strokeWidth = 2;
bullet.stroke = am4core.color("#fff");
bullet.setStateOnChildren = true;
bullet.propertyFields.fillOpacity = "opacity";
bullet.propertyFields.strokeOpacity = "opacity";

var hoverState = bullet.states.create("hover");
hoverState.properties.scale = 1.7;

function createTrendLine(data) {
  var trend = chart.series.push(new am4charts.LineSeries());
  trend.dataFields.valueY = "value";
  trend.dataFields.dateX = "date";
  trend.strokeWidth = 2
  trend.stroke = trend.fill = am4core.color("#c00");
  trend.data = data;

  var bullet = trend.bullets.push(new am4charts.CircleBullet());
  bullet.tooltipText = "{date}\n[bold font-size: 17px]value: {valueY}[/]";
  bullet.strokeWidth = 2;
  bullet.stroke = am4core.color("#fff")
  bullet.circle.fill = trend.stroke;

  var hoverState = bullet.states.create("hover");
  hoverState.properties.scale = 1.7;

  return trend;
};

// createTrendLine([
//   { "date": "2012-01-02", "value": 10 },
//   { "date": "2012-01-11", "value": 19 }
// ]);

// var lastTrend = createTrendLine([
//   { "date": "2012-01-17", "value": 16 },
//   { "date": "2012-01-22", "value": 10 }
// ]);

// Initial zoom once chart is ready
// lastTrend.events.once("datavalidated", function(){
//   series.xAxis.zoomToDates(new Date(2012, 0, 2), new Date(2012, 0, 13));
// });

}); // end am4core.ready()

}


function auditors_performance(data){

    am4core.ready(function() {

    am4core.useTheme(am4themes_animated);
// Themes end
am4core.addLicense("ch-custom-attribution");
var chart = am4core.create("auditor_performance", am4charts.XYChart);


var title = chart.titles.create();
    title.text = "Auditors Performance";
   title.fontSize = 20;
    title.fontFamily = "Arial";
    title.marginBottom = 10;

// Set cell size in pixels
var cellSize = 20;
chart.events.on("datavalidated", function(ev) {

  // Get objects of interest
  var chart = ev.target;
  var categoryAxis = chart.yAxes.getIndex(0);

  // Calculate how we need to adjust chart height
  var adjustHeight = chart.data.length * cellSize - categoryAxis.pixelHeight;

  // get current chart height
  var targetHeight = chart.pixelHeight + adjustHeight;

  // Set it on chart's container
  chart.svgContainer.htmlElement.style.height = targetHeight + "px";
});
chart.padding(40, 40, 40, 40);

var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.dataFields.category = "names";
categoryAxis.renderer.minGridDistance = 1;
categoryAxis.renderer.inversed = true;
categoryAxis.renderer.grid.template.disabled = true;

var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
valueAxis.min = 0;

var series = chart.series.push(new am4charts.ColumnSeries());
series.dataFields.categoryY = "names";
series.dataFields.valueX = "counts";
series.tooltipText = "{valueX.value} "
series.columns.template.strokeOpacity = 0;
series.columns.template.column.cornerRadiusBottomRight = 5;
series.columns.template.column.cornerRadiusTopRight = 5;

var labelBullet = series.bullets.push(new am4charts.LabelBullet())
labelBullet.label.horizontalCenter = "left";
labelBullet.label.dx = 10;
labelBullet.label.text = "{values.valueX.workingValue.formatNumber('#')}";
labelBullet.locationX = 1;

// as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
series.columns.template.adapter.add("fill", function(fill, target){
    if(target.dataItem.dataContext.current == 1){
        

        return chart.colors.getIndex(9);
    } else {
        return chart.colors.getIndex(1);
    }
});

// categoryAxis.sortBySeries = series;
chart.data = data;


}); 
}


function agent_performance(data){

    am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("agent_performance", am4charts.XYChart);
var title = chart.titles.create();
    title.text = "Agent Performance";
   title.fontSize = 20;
    title.fontFamily = "Arial";
    title.marginBottom = 10;
chart.scrollbarX = new am4core.Scrollbar();

// Add data
chart.data = data;

// Create axes
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "names";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 30;
categoryAxis.renderer.labels.template.horizontalCenter = "right";
categoryAxis.renderer.labels.template.verticalCenter = "middle";
categoryAxis.renderer.labels.template.rotation = 270;
categoryAxis.tooltip.disabled = true;
categoryAxis.renderer.minHeight = 110;

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.renderer.minWidth = 50;

// Create series
var series = chart.series.push(new am4charts.ColumnSeries());
series.sequencedInterpolation = true;
series.dataFields.valueY = "counts";
series.dataFields.categoryX = "names";
series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
series.columns.template.strokeWidth = 0;

series.tooltip.pointerOrientation = "vertical";

series.columns.template.column.cornerRadiusTopLeft = 10;
series.columns.template.column.cornerRadiusTopRight = 10;
series.columns.template.column.fillOpacity = 0.8;

// on hover, make corner radiuses bigger
var hoverState = series.columns.template.column.states.create("hover");
hoverState.properties.cornerRadiusTopLeft = 0;
hoverState.properties.cornerRadiusTopRight = 0;
hoverState.properties.fillOpacity = 1;

series.columns.template.adapter.add("fill", function(fill, target) {
  return chart.colors.getIndex(target.dataItem.index);
});

// Cursor
chart.cursor = new am4charts.XYCursor();

series.columns.template.events.on("hit", onClickAgents, this);

});
}


function pareto_for_agent_wise(pareto_data,agent_name){
    
    
    am4core.ready(function() {

    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart = am4core.create("pareto_data", am4charts.XYChart);
    chart.scrollbarX = new am4core.Scrollbar();
    chart.exporting.menu = new am4core.ExportMenu();

    var title = chart.titles.create();
    title.text = "Reason wise Pareto ("+agent_name+")";
    title.fontSize = 20;
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
    return chart.colors.getIndex(target.dataItem.index *2);
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

// Pareto chart ends here

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

function onClickAgents(e){
        var dp = e.target.dataItem.dataContext;
    
        var base_url = window.location.origin;
    $.ajax({
		type: "GET",
		url: base_url + "/qa_dashboard/qa_dashboard_pareto_agent_wise/"+start_date+"/"+end_date+"/"+dp.names,
		success: function(Data){
            
            pareto_for_agent_wise(Data.data, dp.names);
		}
	});
       // alert("hii",ev.target);
      }

function get_my_score(startDate,endDate){
    var base_url = window.location.origin;
    $.ajax({
        type: "GET",
        url: base_url + "/qa_dashboard/my_score/"+startDate+"/"+endDate,
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



function get_qa_process_chart_data(startDate,endDate){
    var base_url = window.location.origin;
    $.ajax({
		type: "GET",
		url: base_url + "/qa_dashboard/qa_dashboard_process_wise_performance_data/"+startDate+"/"+endDate,
		success: function(Data){
            process_wise_performance(Data.data);
		
        }
	});

}

function trend_chart_data(startDate,endDate){
    var base_url = window.location.origin;
    $.ajax({
		type: "GET",
		url: base_url + "/qa_dashboard/qa_dashboard_trend_chart_data/"+startDate+"/"+endDate,
		success: function(Data){
            trends_for_auditors(Data.data);
		
        }
	});

}

function performance_for_auditors(startDate,endDate){
    var base_url = window.location.origin;
    $.ajax({
		type: "GET",
		url: base_url + "/qa_dashboard/qa_dashboard_performance_for_auditors/"+startDate+"/"+endDate,
		success: function(Data){
   
            auditors_performance(Data.data);
        }
	});

}

function performance_for_agents(startDate,endDate){
    var base_url = window.location.origin;
    $.ajax({
		type: "GET",
		url: base_url + "/qa_dashboard/qa_dashboard_performance_for_agents/"+startDate+"/"+endDate,
		success: function(Data){
   
            agent_performance(Data.data);
        }
	});

}


function get_overall_score(startDate,endDate){
	var base_url = window.location.origin;
    $.ajax({
		type: "GET",
		url: base_url + "/qa_dashboard/overall_score/"+startDate+"/"+endDate,
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

function qa_dashboard_pareto_agent_wise(startDate,endDate){
	var base_url = window.location.origin;
    $.ajax({
		type: "GET",
		url: base_url + "/qa_dashboard/qa_dashboard_pareto_agent_wise/"+startDate+"/"+endDate+"/all",
		success: function(Data){
            
            pareto_for_agent_wise(Data.data, "All Agents");
		}
	});
}

// qa_dashboard_pareto_rebuttal_data(startDate,endDate,process_id);


function call_all_api(startDate,endDate){
    start_date = startDate;
    end_date = endDate;
    get_my_score(startDate,endDate);
    get_overall_score(startDate,endDate);
    performance_for_agents(startDate,endDate);
    get_qa_process_chart_data(startDate,endDate);
    performance_for_auditors(startDate,endDate);
    trend_chart_data(startDate,endDate);
    qa_dashboard_pareto_agent_wise(startDate,endDate);
}



</script>
@endsection
@section('css')
    <link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
