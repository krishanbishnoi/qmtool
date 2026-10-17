<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="telephone=no" name="format-detection">
    <!-- <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script> -->
    <title>QM Tool</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            line-height: 22px;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;

        }

        .label,
        .highcharts-axis-labels {
            text-align: left;
            width: 338px;
            white-space: normal;
            /* width: 500px; */
            /* word-wrap: normal; */
        }
    </style>
</head>
<?php 
    $totalWeight = 100;
    $remaningWeight = $totalWeight - $data['fatal_dialer_data']['with_fatal_score'] ; 
    $withFatal = '{"type": "doughnut", "data": { "datasets": [{ "label": "foo", "data": ['.$data['fatal_dialer_data']['with_fatal_score'].', '.$remaningWeight.'], "backgroundColor": [ "rgba(255, 99, 132, 0.2)", "rgba(0, 0, 0, 0.1)" ], "textcolor":["#000555","#555555"], "borderWidth": 0, }] }, "options": { "rotation": Math.PI, "circumference": Math.PI, "cutoutPercentage": 75, "plugins": { "datalabels": { "display": false }, "doughnutlabel": { "labels": [ { "text": "With Fatel", "color": "#aaa", "font": { "size": "25" }, }, { "text": "'.$data['fatal_dialer_data']['with_fatal_score'].'%", "font": { "size": "40" }, }, ] } } } }';
    
    $remaningWeight = $totalWeight - $data['fatal_dialer_data']['without_fatal_score'];
    $withOutFatal = '{"type": "doughnut", "data": { "datasets": [{ "label": "foo", "data": ['.$data['fatal_dialer_data']['without_fatal_score'].', '.$remaningWeight.'], "backgroundColor": [ "rgba(255, 99, 132, 0.2)", "rgba(0, 0, 0, 0.1)" ], "textcolor":["#000555","#555555"], "borderWidth": 0, }] }, "options": { "rotation": Math.PI, "circumference": Math.PI, "cutoutPercentage": 75, "plugins": { "datalabels": { "display": false }, "doughnutlabel": { "labels": [ { "text": "Without Fatel", "color": "#aaa", "font": { "size": "25" }, }, { "text": "'.$data['fatal_dialer_data']['without_fatal_score'].'%", "font": { "size": "40" }, }, ] } } } }';
    

    $parameterWiseCompliance = "{type: 'line', data: { labels: [".json_encode($data['parameter_list'],true)."], datasets: [{ label: 'Parameter Wise Compliance',  backgroundColor: 'rgb(255, 99, 132)', borderColor: 'rgb(255, 99, 132)', data: [".json_encode($data['pws'],true)."], fill: false, }, ], },   options: { title: { display: true, text: 'Parameter Wise Compliance', }, },} ";

   $agentPerformance = "{type: 'pie', data: { datasets: [ { data: [".$data['quartile_au_count'][0].", ".$data['quartile_au_count'][1].", ".$data['quartile_au_count'][2].", ".$data['quartile_au_count'][3]."], backgroundColor: [ 'rgb(242,68,5)', 'rgb(215,242,5)', 'rgb(125,7,242)', 'rgb(32,217,26)', ], label: 'Dataset 1', }, ], labels: ['0 - 40 %', '41 - 60 %', '61 - 80 %', 'Greater then 80 %'], }, }
   ";

   $agwc = "{ type: 'bar', data: { labels: ['Y1', 'Y2', 'Y3'], datasets: [ { type: 'line', xAxisID: 'xLine', data: [90, 80, 160], backgroundColor: 'red', borderColor: 'red', borderWidth: 3, lineTension: 0.4, showLine: true, datalabels: { display: false, }, }, { data: [60, 70, 120], backgroundColor: '#f6c2db', borderColor: '#36a2eb', borderWidth: 0, }, ], }, options: { layout: { padding: { bottom: 20, top: 25, left: 10, right: 10 } }, scales: { x: { ticks: { color: '#fff' }, grid: { display: false, drawBorder: false }, }, xLine: { offset: false, ticks: { display: false }, grid: { display: false, drawBorder: false }, }, y: { ticks: { color: '#fff' }, grid: { display: false, drawBorder: false }, }, }, plugins: { datalabels: { anchor: 'center', align: 'center', color: '#0b3062', font: { weight: 'bold', }, }, title: { display: true, text: 'Chart with custom colors', color: '#fff', font: { size: 20 }, padding: { bottom: 20 }, }, legend: { display: false }, }, }, }";
//    dd($data['spws'][0]);
   $cse = "{ 'type': 'bar', 'data': { 'labels': [ '' ], 'datasets': [ { 'label': '".$data['spws'][0]['temp_sps_list'][0]."', 'backgroundColor': '".$data['spws'][0]['temp_sps_color'][0]."', 'borderColor': '".$data['spws'][0]['temp_sps_color'][0]."', 'borderWidth': 1, 'data': [".$data['spws'][0]['sp_p_score'][0]."] }, ] }, 'options': { 'responsive': true, 'legend': { 'position': 'top' }, 'title': { 'display': true, 'text': '".$data['spws'][0]['parameter']."' }, 'plugins': { 'roundedBars': true } } }";

   $inquisitive = "{ 'type': 'bar', 'data': { 'labels': [ '' ], 'datasets': [ { 'label': '".$data['spws'][1]['temp_sps_list'][0]."', 'backgroundColor': '".$data['spws'][1]['temp_sps_color'][0]."', 'borderColor': '".$data['spws'][1]['temp_sps_color'][0]."', 'borderWidth': 1, 'data': [".$data['spws'][1]['sp_p_score'][0]."] }, ] }, 'options': { 'responsive': true, 'legend': { 'position': 'top' }, 'title': { 'display': true, 'text': '".$data['spws'][1]['parameter']."' }, 'plugins': { 'roundedBars': true } } }";

   $chs = "{ 'type': 'bar', 'data': { 'labels': [ '' ], 'datasets': [ { 'label': '".$data['spws'][2]['temp_sps_list'][0]."', 'backgroundColor': '".$data['spws'][2]['temp_sps_color'][0]."', 'borderColor': '".$data['spws'][2]['temp_sps_color'][0]."', 'borderWidth': 1, 'data': [".$data['spws'][2]['sp_p_score'][0]."] }, { 'label': '".$data['spws'][2]['temp_sps_list'][1]."', 'backgroundColor': '".$data['spws'][2]['temp_sps_color'][1]."', 'borderColor': '".$data['spws'][2]['temp_sps_color'][1]."', 'borderWidth': 1, 'data': [".$data['spws'][2]['sp_p_score'][1]."] }, ] }, 'options': { 'responsive': true, 'legend': { 'position': 'top' }, 'title': { 'display': true, 'text': '".$data['spws'][2]['parameter']."' }, 'plugins': { 'roundedBars': true } } }";

   $ftr = "{ 'type': 'bar', 'data': { 'labels': [ '' ], 'datasets': [ { 'label': '".$data['spws'][3]['temp_sps_list'][0]."', 'backgroundColor': '".$data['spws'][3]['temp_sps_color'][0]."', 'borderColor': '".$data['spws'][3]['temp_sps_color'][0]."', 'borderWidth': 3, 'data': [".$data['spws'][3]['sp_p_score'][0]."] }, ] }, 'options': { 'responsive': true, 'legend': { 'position': 'top' }, 'title': { 'display': true, 'text': '".$data['spws'][3]['parameter']."' }, 'plugins': { 'roundedBars': true } } }";

   $systemDoc = "{ 'type': 'bar', 'data': { 'labels': [ '' ], 'datasets': [ { 'label': '".$data['spws'][4]['temp_sps_list'][0]."', 'backgroundColor': '".$data['spws'][4]['temp_sps_color'][0]."', 'borderColor': '".$data['spws'][4]['temp_sps_color'][0]."', 'borderWidth': 4, 'data': [".$data['spws'][4]['sp_p_score'][0]."] }, ] }, 'options': { 'responsive': true, 'legend': { 'position': 'top' }, 'title': { 'display': true, 'text': '".$data['spws'][4]['parameter']."' }, 'plugins': { 'roundedBars': true } } }";

   $agent = "{ 'type': 'bar', 'data': { 'labels': [ '' ], 'datasets': [ { 'label': '".$data['spws'][4]['temp_sps_list'][0]."', 'backgroundColor': '".$data['spws'][4]['temp_sps_color'][0]."', 'borderColor': '".$data['spws'][4]['temp_sps_color'][0]."', 'borderWidth': 4, 'data': [".$data['spws'][4]['sp_p_score'][0]."] }, ] }, 'options': { 'responsive': true, 'legend': { 'position': 'top' }, 'title': { 'display': true, 'text': '".$data['spws'][4]['parameter']."' }, 'plugins': { 'roundedBars': true } } }";


    $withFatalChart  = 'https://quickchart.io/chart?width=500&height=300&c='.urlencode($withFatal);
    $withOutFatalChart  = 'https://quickchart.io/chart?width=500&height=300&c='.urlencode($withOutFatal);
    $pwcChart  = 'https://quickchart.io/chart?width=500&height=300&c='.urlencode($parameterWiseCompliance);
    $agentPerformanceChart  = 'https://quickchart.io/chart?width=500&height=300&c='.urlencode($agentPerformance);
    $cseChart  = 'https://quickchart.io/chart?width=500&height=300&c='.urlencode($cse);
    $inquisitiveChart  = 'https://quickchart.io/chart?width=500&height=300&c='.urlencode($inquisitive);
    $chsChart  = 'https://quickchart.io/chart?width=500&height=300&c='.urlencode($chs);
    $ftrChart  = 'https://quickchart.io/chart?width=500&height=300&c='.urlencode($ftr);
    $systemDocChart  = 'https://quickchart.io/chart?width=500&height=300&c='.urlencode($systemDoc);
    $agentChart  = 'https://quickchart.io/chart?width=500&height=300&c='.urlencode($agent);
      

?>

<body
    style="width:100%;font-family:arial, 'helvetica neue', helvetica, sans-serif;padding:0;margin:0;background: #f2f3f8;">
    <table cellspacing="0" cellpadding="0" style="width: 100%;">
        <tr>
            <td style="padding:15px 15px;">
                <table style="width:100%;background: #fff;">
                    <tr>
                        <td style="width: 30%;vertical-align: top;padding-right: 10px;border-bottom: 1px solid #ebedf2;">
                            <table style="width: 100%;" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="padding: 13px;width: 60%;">
                                        <span
                                            style="color: #6c7293;font-weight: bold;font-size: 16px;display: block;">Coverage</span>
                                        <span style="color: #3498DB;font-size: 13px;display: block;">Target</span>
                                    </td>
                                    <td
                                        style="padding: 13px;font-size: 20px;color: #5d78ff;font-weight: bold;width: 40%;text-align: end;">
                                        <?php echo $data['coverage']['target']; ?></td>
                                </tr>
                                <tr>
                                    <td style="padding:0 13px;" colspan="2">
                                        <div style="height: 7px;background-color: #ebedf2;border-radius: 100px;">
                                            <div style="height: 100%; background-color: #5d78ff;border-radius: 100px; width: {{$data['coverage']['achived_per']}}%"
                                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 13px;font-size: 13px;width: 60%;">Achieved</td>
                                    <td style="padding: 13px;font-size: 13px;width: 40%;text-align: end;">
                                        <?php echo $data['coverage']['achived'] ." (". $data['coverage']['achived_per']. "%)"; ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 30%;vertical-align: top;padding-left: 10px;border-bottom: 1px solid #ebedf2;">
                            <table style="width: 100%;" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="padding: 13px;width: 60%;">
                                        <span
                                            style="color: #6c7293;font-weight: bold;font-size: 16px;display: block;">Rebuttal</span>
                                        <span style="color: #ffb822 ;font-size: 13px;display: block;">Raised</span>
                                    </td>
                                    <td
                                        style="padding: 13px;font-size: 20px;color: #ffb822 ;font-weight: bold;width: 40%;text-align: end;">
                                        {{ $data['rebuttal']['raised'] }} - {{ $data['rebuttal']['rebuttal_per'] }}%</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 13px;" colspan="2">
                                        <div style="height: 7px;background-color: #ebedf2;border-radius: 100px;">
                                            <div style="height: 100%; background-color: #ffb822;border-radius: 100px; width:{{ $data['rebuttal']['accepted'] }} - {{ $data['rebuttal']['accepted_per'] }} % || {{ $data['rebuttal']['rejected'] }} - {{ $data['rebuttal']['rejected_per'] }}%"
                                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 13px;font-size: 13px;width: 60%;">Accepted || Rejected</td>
                                    <td style="padding: 13px;font-size: 13px;width: 40%;text-align: end;">
                                        {{ $data['rebuttal']['accepted'] }} - {{ $data['rebuttal']['accepted_per'] }} % || {{ $data['rebuttal']['rejected'] }} - {{ $data['rebuttal']['rejected_per'] }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 13px;font-size: 13px;width: 60%;">WIP</td>
                                    <td style="padding: 13px;font-size: 13px;width: 40%;text-align: end;">
                                        {{ $data['rebuttal']['wip'] }} 
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 30%;vertical-align: top;padding-right: 10px;">
                            <table style="width: 100%;" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="padding: 13px;width: 60%;">
                                        <span
                                            style="color: #6c7293;font-weight: bold;font-size: 16px;display: block;">FATAL</span>
                                        <span style="color: #fd397a;font-size: 13px;display: block;">Parameter
                                            Count</span>
                                    </td>
                                    <td style="padding: 13px;font-size: 20px;color: #fd397a ;font-weight: bold;width: 40%;text-align: end;">
                                        {{ $data['fatal_first_row_block']['total_fatal_count_sub_parameter'] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0 13px;" colspan="2">
                                        <div style="height: 7px;background-color: #ebedf2;border-radius: 100px;">
                                            <div style="height: 100%; background-color: #fd397a;border-radius: 100px; width: {{ $data['fatal_first_row_block']['total_fatal_audit_per'] }} %"
                                                aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 13px;font-size: 13px;width: 60%;">Fatal Call</td>
                                    <td style="padding: 13px;font-size: 13px;width: 40%;text-align: end;">
                                        {{ $data['fatal_first_row_block']['total_fatal_audits'] }} ({{ $data['fatal_first_row_block']['total_fatal_audit_per'] }}%)
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- <tr>
                        <td style="width: 50%;vertical-align: top;padding-right: 10px;">
                            <table style="width: 100%;" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="padding: 13px;width: 60%;">
                                        <span
                                            style="color: #6c7293;font-weight: bold;font-size: 16px;display: block;">FATAL</span>
                                        <span style="color: #fd397a;font-size: 13px;display: block;">Parameter
                                            Count</span>
                                    </td>
                                    <td style="padding: 13px;font-size: 20px;color: #fd397a ;font-weight: bold;width: 40%;text-align: end;">
                                        {{ $data['fatal_first_row_block']['total_fatal_count_sub_parameter'] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0 13px;" colspan="2">
                                        <div style="height: 7px;background-color: #ebedf2;border-radius: 100px;">
                                            <div style="height: 100%; background-color: #fd397a;border-radius: 100px; width: {{ $data['fatal_first_row_block']['total_fatal_audit_per'] }} %"
                                                aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 13px;font-size: 13px;width: 60%;">Fatal Call</td>
                                    <td style="padding: 13px;font-size: 13px;width: 40%;text-align: end;">
                                        {{ $data['fatal_first_row_block']['total_fatal_audits'] }} ({{ $data['fatal_first_row_block']['total_fatal_audit_per'] }}%)
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="vertical-align: top;padding-left: 10px;">
                        </td>
                    </tr> -->

                </table>
            </td>
        </tr>
        <tr>
            <td style="padding:0px 15px 15px;">
                <table cellspacing="0" cellpadding="0" style="width: 100%;background: #fff;">
                    <tr>
                        <td style="border-bottom: 1px solid #ebedf2;font-size: 16px;padding: 13px;">
                            Process Statistics
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                <tr>
                                    <td style="padding: 15px;text-align: center;width: 25%;">
                                        <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                            <tr>
                                                <td style="text-align:center;font-size: 16px;color: #5d78ff;font-weight: bold;padding-bottom: 7px;"> DPU </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:center;font-size: 12px;color: #a5abc3;padding-bottom: 25px;">
                                                    Defect Per Unit
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:center;border-bottom: 1px dashed #ebedf2;"></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:center;padding-top: 25px;color: #646c9a;">
                                                    {{ $data['process_stats']['dpu'] }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="padding: 15px;text-align: center;width: 25%;">
                                        <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                            <tr>
                                                <td style="text-align:center;font-size: 16px;color: #5d78ff;font-weight: bold;padding-bottom: 7px;">DPO</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:center;font-size: 12px;color: #a5abc3;padding-bottom: 25px;">Defect
                                                    Per Opportunity
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:center;border-bottom: 1px dashed #ebedf2;"></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:center;padding-top: 25px;color: #646c9a;">
                                                    {{ $data['process_stats']['dpo'] }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="padding: 15px;text-align: center;width: 25%;">
                                        <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                            <tr>
                                                <td
                                                    style="text-align:center;font-size: 16px;color: #5d78ff;font-weight: bold;padding-bottom: 7px;">
                                                    DPMO</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:center;font-size: 12px;color: #a5abc3;padding-bottom: 25px;">Defect
                                                    Per Million Opportunities
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:center;border-bottom: 1px dashed #ebedf2;"></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:center;padding-top: 25px;color: #646c9a;">
                                                    {{ $data['process_stats']['dpmo'] }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="padding: 15px;text-align: center;width: 25%;">
                                        <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                            <tr>
                                                <td
                                                    style="text-align:center;font-size: 16px;color: #5d78ff;font-weight: bold;padding-bottom: 7px;">
                                                    PPM</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:center;font-size: 12px;color: #a5abc3;padding-bottom: 25px;">Parts
                                                    Per Million
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:center;border-bottom: 1px dashed #ebedf2;"></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:center;padding-top: 25px;color: #646c9a;">
                                                    {{ $data['process_stats']['ppm'] }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 0 15px 15px;">
                <table cellspacing="0" cellpadding="0" style="width: 100%;background: #fff;">
                    <tr>
                        <td style="border-bottom: 1px solid #ebedf2;font-size: 16px;padding: 13px;">Process Score</td>
                    </tr>
                    <tr>
                        <td>
                            <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                <tr>
                                    <td style="width: 50%;padding: 15px;vertical-align: top;">
                                        <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                            <tr>
                                                <td style="width: 50%;text-align: center;">
                                                    <span style="display: block;font-size: 16px;color: #4169E1;padding-bottom: 15px;">
                                                        With Fatal
                                                    </span>
                                                    <div id="with_fatel">
                                                        <img src=<?= $withFatalChart ?>>
                                                    </div>
                                                </td>
                                                <td style="width: 50%;text-align: center;"><span
                                                        style="display: block;font-size: 16px;color: #646c9a;padding-bottom: 15px;">Without
                                                        Fatal
                                                    </span>
                                                    <div id="without_fatel">
                                                        <img src=<?= $withOutFatalChart ?>>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 0 15px 15px;">
                <table cellspacing="0" cellpadding="0" style="width: 100%;background: #fff;">
                    <tr>
                        <td style="border-bottom: 1px solid #ebedf2;font-size: 16px;padding: 13px;">Process Score</td>
                    </tr>
                    <tr>
                        <td>
                            <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                <tr>
                                    <td style="width: 50%;padding: 15px;vertical-align: top;">
                                        <span style="display: block;font-size: 16px;color: #646c9a;padding-bottom: 15px;">Parameter Wise Defect</span>
                                        <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                            <tr>
                                                <th
                                                    style="text-align:center;border: 1px solid #ebedf2;font-size:13px;font-weight:normal;padding: 10px 13px;">
                                                    #</th>
                                                <th
                                                    style="text-align:left;border: 1px solid #ebedf2;font-size:13px;font-weight:normal;padding: 10px 13px;">
                                                    Parameter</th>
                                                <th
                                                    style="text-align:center;border: 1px solid #ebedf2;font-size:13px;font-weight:normal;padding: 10px 13px;">
                                                    Fatal Count </th>
                                                <th
                                                    style="text-align:center;border: 1px solid #ebedf2;font-size:13px;font-weight:normal;padding: 10px 13px;">
                                                    Fail Count </th>
                                                <th
                                                    style="text-align:center;border: 1px solid #ebedf2;font-size:13px;font-weight:normal;padding: 10px 13px;">
                                                    Non - Compliance</th>
                                            </tr>
                                            @foreach($data['pwfcs'] as $item)
                                            <tr>
                                                <td style="text-align:center;border: 1px solid #ebedf2;font-size:12px;font-weight:normal;padding: 10px 13px;">
                                                    {{$item['counter']}}
                                                </td>
                                                <td style="border: 1px solid #ebedf2;font-size:12px;font-weight:normal;padding: 10px 13px;">
                                                    {{$item['parameter']}}
                                                </td>
                                                <td style="text-align:center;border: 1px solid #ebedf2;font-size:12px;font-weight:normal;padding: 10px 13px;">
                                                    {{$item['fatal_count']}}
                                                </td>
                                                <td style="text-align:center;border: 1px solid #ebedf2;font-size:12px;font-weight:normal;padding: 10px 13px;">
                                                    {{$item['fail_count']}}
                                                </td>
                                                <?php $ncom=(100- $item['fatal_score']); ?>
                                                <td style="text-align:center;border: 1px solid #ebedf2;font-size:12px;font-weight:normal;padding: 10px 13px;">
                                                    4 %
                                                </td>
                                                
                                                <td align="center"  style="color: <?= ($ncom > 50) ? 'red' : (($ncom > 30) ? 'orange' : 'black') ?>">{{$ncom}} % </td>
 
                                                
                                                
                                                <!-- <?php if($ncom > 50) { ?> style="color:red;" <?php } ?> <?php if($ncom > 30 && $ncom <= 50) { ?> style="color:orange;" <?php } ?> <?php if($ncom <= 30) { ?> style="color:black;" <?php } ?> >{{$ncom}} %</td> -->
                                            </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 0 15px 15px;">
                <table cellspacing="0" cellpadding="0" style="width: 100%;background: #fff;">
                    <tr>
                        <td style="border-bottom: 1px solid #ebedf2;font-size: 16px;padding: 13px;">Parameter Wise Compliance</td>
                    </tr>
                    <tr>
                        <td style="padding: 13px;">
                            <div id="paramerer_wise_compilance">
                                <img src=<?= $pwcChart ?>>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 0 15px 15px;">
                <table cellspacing="0" cellpadding="0" style="width: 100%;background: #fff;">
                    <tr>
                        <td style="border-bottom: 1px solid #ebedf2;font-size: 16px;padding: 13px;">Sub Parameter Wise
                            Compliance
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 13px 20px;">
                            <span
                                style="display: inline-block;vertical-align: middle; height: 15px;width: 15px;background-color: rgb(65, 105, 225);"></span>
                            <span
                                style="display: inline-block;vertical-align: middle;padding-left: 5px;font-size: 14px;color: #646c9a;padding-right: 20px;">

                                Fatal Parameter
                            </span>

                            <span
                                style="display: inline-block;vertical-align: middle;height: 15px;width: 15px;background-color: grey;"></span>
                            <span
                                style="display: inline-block;vertical-align: middle;padding-left: 5px;font-size: 14px;color: #646c9a;">

                                Non Fatal Parameter
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 13px;">
                            <div id="container_0" style="width: 100%;">
                                <img src=<?= $cseChart ?>>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 13px;">
                            <div id="container_1" style="width: 100%;">
                                <img src = <?= $inquisitiveChart?>>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 13px;">
                            <div id="container_2" style="width: 100%;">
                                <img src = {{ $chsChart }}>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 13px;">
                            <div id="container_3" style="width: 100%;">
                                <img src = {{ $ftrChart }}>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 13px;">
                            <div id="container_4" style="width: 100%;">
                                <img src = {{ $systemDocChart }}>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 13px;">
                            <div id="nonScoring" style="width: 100%;">
                                <img src= {{ $agentChart }}>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 0 15px 15px;">
                <table cellspacing="0" cellpadding="0" style="width: 100%;">
                    <tr>
                        <td style="width: 100%;padding-right: 7px;">
                            <table cellspacing="0" cellpadding="0" style="width: 100%;background: #fff;">
                                <tr>
                                    <td style="border-bottom: 1px solid #ebedf2;font-size: 16px;padding: 13px;">Agent
                                        Performance Quartile
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 30px 15px;">
                                        <div id="agent_performance" style="width: 100%;">
                                            <img src=<?= $agentPerformanceChart ?>>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <!-- <td style="width: 50%;padding-left: 7px;">
                            <table cellspacing="0" cellpadding="0" style="width: 100%;background: #fff;">
                                <tr>
                                    <td style="border-bottom: 1px solid #ebedf2;font-size: 16px;padding: 13px;">Call
                                        Type</td>
                                </tr>
                                <tr>
                                    <td style="padding: 30px 15px;">
                                        <div id="call_type_container" style="width: 100%;"></div>
                                    </td>
                                </tr>
                            </table>
                        </td> -->
                    </tr>
                </table>
            </td>
        </tr>
        <!-- <tr>
            <td style="padding: 0 15px 15px;">
                <table cellspacing="0" cellpadding="0" style="width: 100%;background: #fff;">
                    <tr>
                        <td style="border-bottom: 1px solid #ebedf2;font-size: 16px;padding: 13px;">Agent
                            Disposition Wise Compliance
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px 15px;">
                            <div id="disposition_wise_compliance" style="width: 100%;">
                            
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr> -->
    </table>


    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script> -->
    <!-- <script>
        // Highcharts.chart('with_fatel', {
        //     chart: {
        //         type: 'solidgauge',

        //         height: 280,
        //     },

        //     title: null,
        //     exporting: {
        //         enabled: false
        //     },
        //     pane: {
        //         center: ['50%', '70%'],
        //         size: '100%',
        //         startAngle: -90,
        //         endAngle: 90,
        //         background: {
        //             innerRadius: '60%',
        //             outerRadius: '100%',
        //             shape: 'arc'
        //         }
        //     },

        //     tooltip: {
        //         enabled: false
        //     },

        //     // the value axis


        //     plotOptions: {
        //         solidgauge: {
        //             dataLabels: {
        //                 y: 6,
        //                 borderWidth: 0,
        //                 useHTML: true
        //             }
        //         }
        //     },
        //     yAxis: {
        //         min: 0,
        //         max: 100,
        //         title: {
        //             text: ''
        //         },
        //         stops: [
        //             [0.1, '#4169E1'], // green
        //             [0.5, '#4169E1'], // yellow
        //             [0.9, '#4169E1'] // red
        //         ]

        //     },

        //     credits: {
        //         enabled: false
        //     },

        //     series: [{
        //         name: 'Speed',
        //         data: [96],
        //         dataLabels: {
        //             format:
        //                 '<div style="text-align:center">' +
        //                 '<span style="font-size:25px;color: #a5abc3;">{y}%</span><br/>' +
        //                 '<span style="font-size:12px;color: #a5abc3;opacity:0.4">Score</span>' +
        //                 '</div>'
        //         },
        //         tooltip: {
        //             valueSuffix: ' Count'
        //         }
        //     }]
        // });
        // Highcharts.chart('without_fatel', {
        //     chart: {
        //         type: 'solidgauge',
        //         height: 280,
        //     },

        //     title: null,

        //     pane: {
        //         center: ['50%', '70%'],
        //         size: '100%',
        //         startAngle: -90,
        //         endAngle: 90,
        //         background: {
        //             innerRadius: '60%',
        //             outerRadius: '100%',
        //             shape: 'arc'
        //         }
        //     },

        //     tooltip: {
        //         enabled: false
        //     },

        //     // the value axis


        //     plotOptions: {
        //         solidgauge: {
        //             dataLabels: {
        //                 y: 6,
        //                 borderWidth: 0,
        //                 useHTML: true
        //             }
        //         }
        //     },
        //     yAxis: {
        //         min: 0,
        //         max: 100,
        //         title: {
        //             text: ''
        //         },
        //         stops: [
        //             [0.1, '#AFEEEE'], // green
        //             [0.5, '#AFEEEE'], // yellow
        //             [0.9, '#AFEEEE'] // red
        //         ]
        //     },

        //     credits: {
        //         enabled: false
        //     },

        //     series: [{
        //         name: 'Speed',
        //         data: [96],
        //         dataLabels: {
        //             format:
        //                 '<div style="text-align:center">' +
        //                 '<span style="font-size:25px;color: #a5abc3;">{y}%</span><br/>' +
        //                 '<span style="font-size:12px;color: #a5abc3;opacity:0.4">Score</span>' +
        //                 '</div>'
        //         },
        //         tooltip: {
        //             valueSuffix: ' Score'
        //         }
        //     }]

        // });
        // Highcharts.chart('paramerer_wise_compilance', {

        //     chart: {
        //         type: 'spline'
        //     },

        //     title: {
        //         text: ''
        //     },
        //     subtitle: {
        //         text: ''
        //     },
        //     xAxis: {
        //         categories: ["CUSTOMER SERVICE ESSENTIALS", "INQUISITIVE", "CALL HANDLING SKILLS", "FTR ACCURACY", "SYSTEM DOCUMENTATION"]
        //     },
        //     yAxis: {
        //         min: 0,
        //         max: 100,
        //         title: {
        //             text: '%'
        //         },
        //         labels: {
        //             format: '{value} %',
        //             style: {

        //             }
        //         }
        //     },
        //     plotOptions: {
        //         line: {
        //             dataLabels: {
        //                 enabled: true,
        //                 format: '{point.y:.0f}%'
        //             },
        //             enableMouseTracking: false
        //         }
        //     },
        //     series: [{
        //         name: 'Compliance %',
        //         data: [96, 100, 94, 98, 94]
        //     }]

        // });
        // Highcharts.chart('container_0', {

        //     chart: {
        //         type: 'bar',
        //         height: 250
        //     },
        //     credits: {
        //         enabled: false
        //     },

        //     title: {
        //         text: "CUSTOMER SERVICE ESSENTIALS"
        //     },

        //     tooltip: {
        //         valueSuffix: ' %',
        //         outside: true,
        //         useHTML: true,
        //         backgroundColor: "rgba(246, 246, 246, 1)",
        //         borderColor: "#bbbbbb",
        //         borderWidth: 1.5,
        //         style: {
        //             opacity: 10,
        //             background: "rgba(246, 246, 246, 1)"
        //         }
        //     },
        //     categories: {
        //         enabled: 'true'
        //     },
        //     legend: {
        //         enabled: false,
        //         layout: 'vertical',
        //         align: 'right',
        //         verticalAlign: 'top',
        //         x: -40,
        //         y: 80,
        //         floating: true,
        //         borderWidth: 1,

        //         shadow: false,
        //         itemHoverStyle: {
        //             color: 'grey'
        //         }
        //     },

        //     xAxis: {
        //         categories: ["Initial warm and farewell"],
        //         myString: ["1. Agent should open the call along with the brand name and introduce him or her self with customers & partners\r\n2.  Agent should start the call with a smile which will inculcate positive energy on call and confidence at the beginning of the call, Closing the call on a WOW note\r\n3. Agent name should be clear in call opening\r\n4. Agent should maintain the pitch and pause on call\r\n5. Context setting should be done before initiating the information ( Ticket raised issues, app-related issues)"],
        //         myColor: ["grey"],
        //         labels: {
        //             useHTML: true,
        //             formatter: function () {
        //                 //  return '<div style="color:'+this.axis.options.myColor+'" title="'+this.axis.options.myString+'" class="label">'+this.value+'</div>';
        //                 return `<div style="color: ${[this.value]}" title="${this.axis.options.myString}" class="label">${this.value}</div>`
        //             }
        //         },
        //     },

        //     yAxis: {
        //         min: 0,
        //         max: 100,
        //         title: {
        //             text: 'Score',
        //             align: 'high'
        //         },
        //         labels: {
        //             overflow: 'justify'
        //         }
        //     },

        //     plotOptions: {
        //         bar: {
        //             dataLabels: {
        //                 enabled: true,
        //                 format: '{point.y:.0f}%'
        //             },

        //         },

        //         series: {
        //             colorByPoint: true
        //         }

        //     },
        //     colors: ['grey',
        //     ],
        //     series: [{
        //         name: 'Score',
        //         data: [96]
        //     }]

        // });

        Highcharts.chart('container_1', {

            chart: {
                type: 'bar',
                height: 250
            },
            credits: {
                enabled: false
            },

            title: {
                text: "INQUISITIVE"
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
                enabled: false,
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
                categories: ["\"effective probing & system navigation\""],
                myString: ["1. Ask pertinent questions to accurately diagnose the problem\r\n2. Asking right questions to better understand the customer ask\/requirement\r\n3. Gain background of the case\/concern\/query\r\n4. Checking relevant tool\/system screen to understand the system activity\r\n5. Avoid irrelevant\/unnecessary questions such as information available in the system but still asked"],
                myColor: ["grey"],
                labels: {
                    useHTML: true,
                    formatter: function () {

                        //  return '<div style="color:'+this.axis.options.myColor+'" title="'+this.axis.options.myString+'" class="label">'+this.value+'</div>';
                        return `<div style="color: ${[this.value]}" title="${this.axis.options.myString}" class="label">${this.value}</div>`
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
            colors: ['grey',
            ],
            series: [{
                name: 'Score',
                data: [100]
            }]

        });
        Highcharts.chart('container_2', {

            chart: {
                type: 'bar',
                height: 250
            },
            credits: {
                enabled: false
            },

            title: {
                text: "CALL HANDLING SKILLS"
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
                enabled: false,
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
                categories: ["Hold procedures & conference protocol", "\"professionalism & listening skills & empathy \/ ownership (in case of a concern \/ issue)\""],
                myString: ["1. Ask permission & state the reason for hold\r\n2. Thank the customer when reverting back from hold\r\n3. Avoid placing the customer on hold for basic queries\r\n4. Apologize if kept the caller on hold for a long time\r\n5. Hold should not exceed more than 60 sec, in case more time is required (agent asked to customer as will take a couple of minutes more to resolve customer queries)\r\n6. Mute not \u003C 30 Sec to avoid dead air (non reasonable silence)", "1. Not being rude, no force disconnection by agent\r\n2. Impolite to the degree of rude \/ Abusive \/ Unprofessional\r\n3. Be polite & friendly - Use courteous words and phrases\r\n4. The agent should apologize to the customer\/partner for any inconvenience caused"],
                myColor: ["grey", "rgb(65, 105, 225)"],
                labels: {
                    useHTML: true,
                    formatter: function () {

                        //  return '<div style="color:'+this.axis.options.myColor+'" title="'+this.axis.options.myString+'" class="label">'+this.value+'</div>';
                        return `<div style="color: ${[this.value]}" title="${this.axis.options.myString}" class="label">${this.value}</div>`
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
            colors: ['grey',
                'rgb(65, 105, 225)',
            ],
            series: [{
                name: 'Score',
                data: [100, 89]
            }]

        });
        Highcharts.chart('container_3', {

            chart: {
                type: 'bar',
                height: 250
            },
            credits: {
                enabled: false
            },

            title: {
                text: "FTR ACCURACY"
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
                enabled: false,
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
                categories: ["Complete & accurate resolution &ivr transfer accuracy"],
                myString: ["1. Provide 100% accurate, correct & Relevant Information as per the customers\/partners requirements\r\n2.Call should be transfer to IVR to collect the feedback ratings from customers"],
                myColor: ["rgb(65, 105, 225)"],
                labels: {
                    useHTML: true,
                    formatter: function () {

                        //  return '<div style="color:'+this.axis.options.myColor+'" title="'+this.axis.options.myString+'" class="label">'+this.value+'</div>';
                        return `<div style="color: ${[this.value]}" title="${this.axis.options.myString}" class="label">${this.value}</div>`
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
            colors: ['rgb(65, 105, 225)',
            ],
            series: [{
                name: 'Score',
                data: [98]
            }]

        });
        Highcharts.chart('container_4', {

            chart: {
                type: 'bar',
                height: 250
            },
            credits: {
                enabled: false
            },

            title: {
                text: "SYSTEM DOCUMENTATION"
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
                enabled: false,
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
                categories: ["Tagging accuracy"],
                myString: ["1. Agent to ensure that the interaction is captured correctly & completely on CRM\r\nand correct remarks in case of closing the ticket \r\n2. Agent should close the ticket after the troubleshooting with the customers\/partners"],
                myColor: ["rgb(65, 105, 225)"],
                labels: {
                    useHTML: true,
                    formatter: function () {

                        //  return '<div style="color:'+this.axis.options.myColor+'" title="'+this.axis.options.myString+'" class="label">'+this.value+'</div>';
                        return `<div style="color: ${[this.value]}" title="${this.axis.options.myString}" class="label">${this.value}</div>`
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
            colors: ['rgb(65, 105, 225)',
            ],
            series: [{
                name: 'Score',
                data: [94]
            }]

        });
        Highcharts.chart('nonScoring', {

            chart: {
                type: 'bar',
                height: 250
            },
            credits: {
                enabled: false
            },

            title: {
                text: "Agent Confidence"
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
                enabled: false,
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
                categories: ["Agent was sounding confident while sharing the information"]
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
                data: [100]
            }]

        });

        // Highcharts.chart('agent_performance', {

        //     chart: {
        //         plotBackgroundColor: null,
        //         plotBorderWidth: null,
        //         plotShadow: false,
        //         type: 'pie'
        //     },
        //     title: {
        //         text: ''
        //     },
        //     tooltip: {
        //         pointFormat: '{series.name}: <b>{point.percentage:.0f} %</b> <br>Call Count:<b>{point.au_count}</b><br> Contribution:<b>{point.au_contri} %</b>'
        //     },
        //     plotOptions: {
        //         pie: {
        //             allowPointSelect: true,
        //             cursor: 'pointer',
        //             dataLabels: {
        //                 enabled: true,
        //                 format: '<b>{point.name}</b>: {point.percentage:.0f} % <br>Call Count:{point.au_count} <br> Contribution:{point.au_contri} %'
        //             }
        //         }
        //     },
        //     series: [{
        //         name: 'Agent %',
        //         colorByPoint: true,
        //         data: [{
        //             name: '0 - 40 %',
        //             y: 0,
        //             au_count: 0,
        //             au_contri: 0,
        //             url: 'range=1',
        //             sliced: true,
        //             selected: true,
        //             color: "#F24405"
        //         }, {
        //             name: '41 - 60 %',
        //             y: 0,
        //             au_count: 0,
        //             au_contri: 0,
        //             url: 'range=2',
        //             color: '#D7F205'
        //         }, {
        //             name: '61 - 80 %',
        //             y: 4,
        //             au_count: 4,
        //             au_contri: 13,
        //             url: 'range=3',
        //             color: '#7D07F2'
        //         }, {
        //             name: 'Greater then 80 %',
        //             y: 23,
        //             au_count: 27,
        //             au_contri: 87,
        //             url: 'range=4',
        //             color: "#20D91A"
        //         }]
        //     }]
        // });



        Highcharts.chart('call_type_container', {

            title: {
                text: 'Call Type'
            },
            xAxis: {
                categories: ['Query', 'Request', 'Complaint']
            },
            plotOptions: {
                spline: {
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
                    }
                }]
            },
            series: [{
                type: 'column',
                name: 'Audit Count',
                data: [0, 0, 0]
            }, {
                type: 'column',
                name: 'Fatal Count',
                data: [0, 0, 0]
            }, {
                type: 'spline',
                name: 'Score',
                data: [0, 0, 0],
                marker: {
                    lineWidth: 2,
                    fillColor: 'red',
                    format: '{point.y:.0f}%'
                }
            },
            ]

        });
        // Highcharts.chart('disposition_wise_compliance', {

        //     chart: {
        //         zoomType: 'xy'
        //     },
        //     title: {
        //         text: ''
        //     },
        //     subtitle: {
        //         text: ''
        //     },
        //     xAxis: [{
        //         categories: ["A-Closed-Ticket", "A_Closed_Ticket.GD", "wrap.timeout", "A_Ticket_Raised", "Connected", "followup", "Forwarded"],
        //         crosshair: true
        //     }],
        //     yAxis: [{ // Primary yAxis
        //         min: 0,
        //         max: 100,
        //         labels: {
        //             format: '{value} %',
        //             style: {

        //             }
        //         },
        //         title: {
        //             text: 'Score',
        //             style: {

        //             }
        //         }
        //     }, { // Secondary yAxis
        //         title: {
        //             text: 'Count',
        //             style: {

        //             }
        //         },
        //         labels: {
        //             format: '{value}',
        //             style: {

        //             }
        //         },
        //         opposite: true
        //     }],
        //     tooltip: {
        //         shared: true
        //     },
        //     legend: {
        //         layout: 'horizontal',
        //         align: 'center',
        //         x: 0,
        //         verticalAlign: 'top',
        //         y: 0,
        //         floating: true,

        //     },
        //     series: [{
        //         name: 'Audit Counts',
        //         type: 'column',
        //         yAxis: 1,
        //         data: [20, 26, 3, 6, 1, 1, 3],
        //         tooltip: {
        //             valueSuffix: ' Count'
        //         }

        //     }, {
        //         name: 'Score',
        //         type: 'spline',
        //         data: [100, 98, 78, 96, 100, 100, 85],
        //         tooltip: {
        //             valueSuffix: '%'
        //         }
        //     }]
        // });

    </script> -->
</body>

</html>