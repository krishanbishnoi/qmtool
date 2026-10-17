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
 
    if(count($test['data'])){
        $date  = \Carbon\Carbon::now()->subDays(2)->format('Y-m-d');
        $client = $auditCount = $qaNumber = $fatalCount = $avgYesAudCount = $avgScore = $audCountVari = $audCountMtd = $fatalCountMtd = $avgScoreMtd = $rebuttalCountMtd = [];
    // foreach ($test['data'] as $key => $value) {
    //     $client[] = $value->client;
    //     $auditCount[] = $value->yesterday_audit_count ?? 0;
    //     $qaNumber[] = $value->number_of_qa ?? 0;
    //     $fatalCount[] = $value->fatal_count_yesterday ?? 0;
    //     $yes_audit_count=  $value->yesterday_audit_count; 
    //     $qa = $value->number_of_qa;
    //     $yesterday= round(($yes_audit_count/$qa),2);
    //     $avgYesAudCount[] = $yesterday ?? 0;
    //     $avgScore[] = $value->avg_yes_score ?? 0;
    //     $variance = $value->yesterday_audit_count - $value->audit_count_variance;
    //     $audCountVari[] = $variance ?? 0;
    //     $audCountMtd[] = $value->audit_count_mont ?? 0;
    //     $fatalCountMtd[] = $value->fatal_count_mtd ?? 0;
    //     $rebuttalCountMtd[]  = $value->rebuttal_count_mtd ?? 0;
    //     $avgScoreMtd[]  = $value->avg_month_score ?? 0;
    // }
   


    foreach ($test['data'] as $value) {
        $client[] = str_replace("'", "", $value->client);
        $auditCount[] = $value->yesterday_audit_count ?? 0;
        $qaNumber[] = $value->number_of_qa ?? 0;
        $fatalCount[] = $value->fatal_count_yesterday ?? 0;
        $avgYesAudCount[] = round(($value->yesterday_audit_count ?? 0) / ($value->number_of_qa ?? 1), 2);
        $avgScore[] = $value->avg_yes_score ?? 0;
        $audCountVari[] = ($value->yesterday_audit_count ?? 0) - ($value->audit_count_variance ?? 0);
        $audCountMtd[] = $value->audit_count_month ?? 0;
        $fatalCountMtd[] = $value->fatal_count_mtd ?? 0;
        $rebuttalCountMtd[] = $value->rebuttal_count_mtd ?? 0;
        $avgScoreMtd[] = $value->avg_month_score ?? 0;
    }
    $client1 = "'" . implode ( "', '", $client ) . "'";
    $auditCount1 = implode(",", $auditCount);
    $qaNumber1 = implode(",", $qaNumber);
    $fatalCount1 = implode(",", $fatalCount);
    $avgYesAudCount1 = implode(",", $avgYesAudCount);
    $avgScore1 = implode(",", $avgScore);
    $audCountVari1 = implode(",", $audCountVari);
    $audCountMtd1 = implode(",", $audCountMtd);
    $fatalCountMtd1 = implode(",", $fatalCountMtd);
    $rebuttalCountMtd1 = implode(",", $rebuttalCountMtd);
    $avgScoreMtd1 = implode(",", $avgScoreMtd);
    
   $auditCount = "{type: 'bar', data: {labels: [".$client1."],datasets: [{ label: 'Audit Count(".$date.")', backgroundColor: 'rgba(173,216,230, 0.5)', borderColor: 'rgb(173,216,230)', borderWidth: 1, data: [".$auditCount1."], }, { label: '', backgroundColor: 'rgba(255, 255, 255)', borderColor: 'rgb(255, 255, 255)', borderWidth: 1, data: [], }, ], }, options: { title: { display: true, text: 'Audit Count(".$date.")', }, plugins: { datalabels: { anchor: 'center', align: 'center', color: '#666', font: { weight: 'normal', }, }, }, }, }";

   $qaNumber = "{type: 'bar', data: {labels: [".$client1."],datasets: [{ label: 'No of QA', backgroundColor: 'rgba(173,216,230, 0.5)', borderColor: 'rgb(173,216,230)', borderWidth: 1, data: [".$qaNumber1."], }, { label: '', backgroundColor: 'rgba(255, 255, 255)', borderColor: 'rgb(255, 255, 255)', borderWidth: 1, data: [], }, ], }, options: { title: { display: true, text: 'No of QA', }, plugins: { datalabels: { anchor: 'center', align: 'center', color: '#666', font: { weight: 'normal', }, }, }, }, }";

   $fatalCount = "{type: 'bar', data: {labels: [".$client1."],datasets: [{ label: 'Fatal Count(".$date.")', backgroundColor: 'rgba(173,216,230, 0.5)', borderColor: 'rgb(173,216,230)', borderWidth: 1, data: [".$fatalCount1."], }, { label: '', backgroundColor: 'rgba(255, 255, 255)', borderColor: 'rgb(255, 255, 255)', borderWidth: 1, data: [], }, ], }, options: { title: { display: true, text: 'Fatal Count(".$date.")', }, plugins: { datalabels: { anchor: 'center', align: 'center', color: '#666', font: { weight: 'normal', }, }, }, }, }";

   $avgAuditCount = "{type: 'bar', data: {labels: [".$client1."],datasets: [{ label: 'Average Yesterday Audit Count', backgroundColor: 'rgba(173,216,230, 0.5)', borderColor: 'rgb(173,216,230)', borderWidth: 1, data: [".$avgYesAudCount1."], }, { label: '', backgroundColor: 'rgba(255, 255, 255)', borderColor: 'rgb(255, 255, 255)', borderWidth: 1, data: [], }, ], }, options: { title: { display: true, text: 'Average Yesterday Audit Count', }, plugins: { datalabels: { anchor: 'center', align: 'center', color: '#666', font: { weight: 'normal', }, }, }, }, }";

   $avgScoreCount = "{type: 'bar', data: {labels: [".$client1."],datasets: [{ label: 'Average Score(".$date.")', backgroundColor: 'rgba(173,216,230, 0.5)', borderColor: 'rgb(173,216,230)', borderWidth: 1, data: [".$avgScore1."], }, { label: '', backgroundColor: 'rgba(255, 255, 255)', borderColor: 'rgb(255, 255, 255)', borderWidth: 1, data: [], }, ], }, options: { title: { display: true, text: 'Average Score(".$date.")', }, plugins: { datalabels: { anchor: 'center', align: 'center', color: '#666', font: { weight: 'normal', }, }, }, }, }";

   $varianceCount = "{type: 'bar', data: {labels: [".$client1."],datasets: [{ label: 'Audit Count Variance', backgroundColor: 'rgba(173,216,230, 0.5)', borderColor: 'rgb(173,216,230)', borderWidth: 1, data: [".$audCountVari1."], }, { label: '', backgroundColor: 'rgba(255, 255, 255)', borderColor: 'rgb(255, 255, 255)', borderWidth: 1, data: [], }, ], }, options: { title: { display: true, text: 'Audit Count Variance', }, plugins: { datalabels: { anchor: 'center', align: 'center', color: '#666', font: { weight: 'normal', }, }, }, }, }";

   $avgCountMtd = "{type: 'bar', data: {labels: [".$client1."],datasets: [{ label: 'Audit Count(MTD)', backgroundColor: 'rgba(173,216,230, 0.5)', borderColor: 'rgb(173,216,230)', borderWidth: 1, data: [".$audCountMtd1."], }, { label: '', backgroundColor: 'rgba(255, 255, 255)', borderColor: 'rgb(255, 255, 255)', borderWidth: 1, data: [], }, ], }, options: { title: { display: true, text: 'Audit Count(MTD)', }, plugins: { datalabels: { anchor: 'center', align: 'center', color: '#666', font: { weight: 'normal', }, }, }, }, }";

   $fCountMtd = "{type: 'bar', data: {labels: [".$client1."],datasets: [{ label: 'Fatal Count(MTD)', backgroundColor: 'rgba(173,216,230, 0.5)', borderColor: 'rgb(173,216,230)', borderWidth: 1, data: [".$fatalCountMtd1."], }, { label: '', backgroundColor: 'rgba(255, 255, 255)', borderColor: 'rgb(255, 255, 255)', borderWidth: 1, data: [], }, ], }, options: { title: { display: true, text: 'Fatal Count(MTD)', }, plugins: { datalabels: { anchor: 'center', align: 'center', color: '#666', font: { weight: 'normal', }, }, }, }, }";

   $rebuttalCountMtd = "{type: 'bar', data: {labels: [".$client1."],datasets: [{ label: 'Rebuttal Count(MTD)', backgroundColor: 'rgba(173,216,230, 0.5)', borderColor: 'rgb(173,216,230)', borderWidth: 1, data: [".$rebuttalCountMtd1."], }, { label: '', backgroundColor: 'rgba(255, 255, 255)', borderColor: 'rgb(255, 255, 255)', borderWidth: 1, data: [], }, ], }, options: { title: { display: true, text: 'Rebuttal Count(MTD)', }, plugins: { datalabels: { anchor: 'center', align: 'center', color: '#666', font: { weight: 'normal', }, }, }, }, }";

   $avgScrMtd = "{type: 'bar', data: {labels: [".$client1."],datasets: [{ label: 'Average Score(MTD)', backgroundColor: 'rgba(173,216,230, 0.5)', borderColor: 'rgb(173,216,230)', borderWidth: 1, data: [".$avgScoreMtd1."], }, { label: '', backgroundColor: 'rgba(255, 255, 255)', borderColor: 'rgb(255, 255, 255)', borderWidth: 1, data: [], }, ], }, options: { title: { display: true, text: 'Average Score(MTD)', }, plugins: { datalabels: { anchor: 'center', align: 'center', color: '#666', font: { weight: 'normal', }, }, }, }, }";

    $auditCountChart  = 'https://quickchart.io/chart?&c='.urlencode($auditCount);
    $qaNumberChart  = 'https://quickchart.io/chart?&c='.urlencode($qaNumber);
    $fatalCountChart  = 'https://quickchart.io/chart?&c='.urlencode($fatalCount);
    $avgAuditCountChart  = 'https://quickchart.io/chart?&c='.urlencode($avgAuditCount);
    $avgScoreCountChart  = 'https://quickchart.io/chart?&c='.urlencode($avgScoreCount);
    $varianceCountChart  = 'https://quickchart.io/chart?&c='.urlencode($varianceCount);
    $avgCountMtdChart  = 'https://quickchart.io/chart?&c='.urlencode($avgCountMtd);
    $fCountMtdChart  = 'https://quickchart.io/chart?&c='.urlencode($fCountMtd);
    $rebuttalCountMtdChart  = 'https://quickchart.io/chart?&c='.urlencode($rebuttalCountMtd);
    $avgScrMtdChart  = 'https://quickchart.io/chart?&c='.urlencode($avgScrMtd);

    } 
    

?>

    <body style="width:100%;font-family:arial, 'helvetica neue', helvetica, sans-serif;padding:0;margin:0;background: #f2f3f8;">
        <table cellspacing="0" cellpadding="0" style="width: 100%;"> 
        @if(count($test['data']))
            <tr>
                <td style="padding: 0 15px 15px;">
                    <table cellspacing="0" cellpadding="0" style="width: 100%;background: #fff;">
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                    <tr>
                                        <td style="width: 100%;padding: 15px;vertical-align: top;">
                                            <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                                <tr>
                                                    <td style="width: 100%;text-align: center;">
                                                        <div>
                                                            <img src=<?= $auditCountChart ?> style="width: 100%;">
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
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                    <tr>
                                        <td style="width: 100%;padding: 15px;vertical-align: top;">
                                            <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                                <tr>
                                                    <td style="width: 100%;text-align: center;">
                                                        <div>
                                                            <img src=<?= $qaNumberChart ?> style="width: 100%;">
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
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                    <tr>
                                        <td style="width: 100%;padding: 15px;vertical-align: top;">
                                            <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                                <tr>
                                                    <td style="width: 100%;text-align: center;">
                                                        <div>
                                                            <img src=<?= $fatalCountChart ?> style="width: 100%;">
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
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                    <tr>
                                        <td style="width: 100%;padding: 15px;vertical-align: top;">
                                            <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                                <tr>
                                                    <td style="width: 100%;text-align: center;">
                                                        <div>
                                                            <img src=<?= $avgAuditCountChart ?> style="width: 100%;">
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
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                    <tr>
                                        <td style="width: 100%;padding: 15px;vertical-align: top;">
                                            <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                                <tr>
                                                    <td style="width: 100%;text-align: center;">
                                                        <div>
                                                            <img src=<?= $avgScoreCountChart ?> style="width: 100%;">
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
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                    <tr>
                                        <td style="width: 100%;padding: 15px;vertical-align: top;">
                                            <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                                <tr>
                                                    <td style="width: 100%;text-align: center;">
                                                        <div>
                                                            <img src=<?= $varianceCountChart ?> style="width: 100%;">
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
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                    <tr>
                                        <td style="width: 100%;padding: 15px;vertical-align: top;">
                                            <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                                <tr>
                                                    <td style="width: 100%;text-align: center;">
                                                        <div>
                                                            <img src=<?= $avgCountMtdChart ?> style="width: 100%;">
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
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                    <tr>
                                        <td style="width: 100%;padding: 15px;vertical-align: top;">
                                            <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                                <tr>
                                                    <td style="width: 100%;text-align: center;">
                                                        <div>
                                                            <img src=<?= $fCountMtdChart ?> style="width: 100%;">
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
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                    <tr>
                                        <td style="width: 100%;padding: 15px;vertical-align: top;">
                                            <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                                <tr>
                                                    <td style="width: 100%;text-align: center;">
                                                        <div>
                                                            <img src=<?= $rebuttalCountMtdChart ?> style="width: 100%;">
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
                            <td>
                                <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                    <tr>
                                        <td style="width: 100%;padding: 15px;vertical-align: top;">
                                            <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                                <tr>
                                                    <td style="width: 100%;text-align: center;">
                                                        <div>
                                                            <img src=<?= $avgScrMtdChart ?> style="width: 100%;">
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



          
            @endif
            <tr>
                <td style="padding: 0 15px 15px;">
                    <table cellspacing="0" cellpadding="0" style="width: 100%;background: #fff;">
                        <tr> <td> Also please check the attached file. </td> </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>

</html>