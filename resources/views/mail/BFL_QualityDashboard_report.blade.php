<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
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
        </style>
        <script src="https://code.highcharts.com/highcharts.js"></script>
    </head>

    <body>


        <div style="padding: 20px;">
            <div style="border: 1px solid #0e70cd;padding: 10px;text-align: center;">
                <table style="width: 100%;">

                    <tr>
                        <td style="width:10%; padding:  5px;"> <img style="width:120px;"
                                src="{{ public_path('BajajFinserve.png') }}" alt=""></td>
                        <td
                            style="width:80%;padding: 5px; font-size:26px;color:#0e70cd; font-weight:bold; text-align:center;">
                            Quality dasboard</td>
                        <td style="width: 10%;padding: 5px;"> <img
                                style="width:120px;"src="{{ public_path('qdegrees_logo.png') }}" alt=""></td>
                    </tr>
                </table>
            </div>
            @php
                $overall = round(($creditQs[4] + $landingQs[4]) / 2);
            @endphp
            <div style="padding: 20px;border: 1px solid #0e70cd;">
                <p>1. The overall quality score stands at {{ $overall }} in  <?php echo date('F Y'); ?> </p>
                <p>2. The quality scores for lending and credit cards are trending at {{ $landingQs[4] }}% and
                    {{ $creditQs[4] }}% respectively</p>
            </div>
        </div>


        <table style="width:100%">
            <tr>
                <td style="width:50%">
                    <div style="padding: 10px;">
                        <div style="border: 1px solid #0e70cd;padding: 10px;">
                            <h5 style="text-align: center">Month Wise Quality Score Overall</h5>
                            <img style="width: 100%;text-align: center;padding: 15px;"
                                src="data:image/png;base64,{{ base64_encode($MonthwiseQcScoresConfig) }}"
                                alt="SLA Chart">
                        </div>
                    </div>
                </td>
                <td style="width:50%">
                    <div style="padding: 10px;">
                        <div style="border: 1px solid #0e70cd;padding: 10px;">
                            <h5 style="text-align: center">(Last Two Days)Quality Score Overall</h5>
                            <img style="width: 100%;text-align: center;padding: 15px;"
                                src="data:image/png;base64,{{ base64_encode($DayWiseScoresConfig) }}" alt="SLA Chart">
                        </div>
                    </div>
                </td>
            </tr>

        </table>

        <table>
            <tr>
                <td style="width:90%">
                    <div style="padding: 10px;">
                        <div style="border: 1px solid #0e70cd;padding: 10px;">
                            <h5 style="text-align: center">Week wise Quality Score - Overall </h5>
                            <img style="width: 88%; height:auto;text-align: center;padding: 15px;"
                                src="data:image/png;base64,{{ base64_encode($WeekwiseQcScoresConfig) }}"
                                alt="SLA Chart">
                        </div>
                    </div>
                </td>

            </tr>
        </table>
        <div style="page-break-after: always;"></div>

        <div style="padding: 30px;"></div>

        <h5 style="text-align: center; align-items : center; padding: 20px; color:#0e70cd;">
           Months Wise Score%  Lending & CC
        </h5>

        <table>
            <tr>
                <td style="width:50%">
                    <div style="padding: 10px;">
                        <div style="border: 1px solid #0e70cd;padding: 10px;">
                            <h5 style="text-align: center">Month Wise Quality Score Lending</h5>
                            <img style="width: 100%;text-align: center;padding: 15px;"
                                src="data:image/png;base64,{{ base64_encode($MonthwiseQcScoresConfigLL) }}"
                                alt="SLA Chart">
                        </div>
                    </div>
                </td>
                <td style="width:50%">
                    <div style="padding: 10px;">
                        <div style="border: 1px solid #0e70cd;padding: 10px;">
                            <h5 style="text-align: center">Month Wise Quality Score CC</h5>
                            <img style="width: 100%;text-align:center;padding: 15px;"
                                src="data:image/png;base64,{{ base64_encode($MonthwiseQcScoresConfigCC) }}"
                                alt="SLA Chart">
                        </div>
                    </div>
                </td>
            </tr>

        </table>

        <h5 style="text-align: center; align-items : center; padding: 20px; color:#0e70cd;">
            Week Wise Score%  Lending & CC
         </h5>

        <table>
            <tr>
                <td style="width:100%;padding: 10px;">
                    <div style="padding: 10px;">
                        <div style="border: 1px solid #0e70cd;padding: 10px;">
                            <h5 style="text-align: center">Week wise Quality Score - Lending </h5>
                            <img style="width: 100%;text-align: center;padding: 15px;"
                                src="data:image/png;base64,{{ base64_encode($WeekwiseQcScoresLLConfig) }}"
                                alt="SLA Chart">
                        </div>
                    </div>
                </td>
                <td style="width:100%;padding: 10px;">
                    <div style="padding: 10px;">
                        <div style="border: 1px solid #0e70cd;padding: 10px;">
                            <h5 style="text-align: center">Week wise Quality Score - Credit Card </h5>
                            <img style="width: 100%;text-align: center;padding: 15px;"
                                src="data:image/png;base64,{{ base64_encode($WeekwiseQcScoresCCConfig) }}"
                                alt="SLA Chart">
                        </div>
                    </div>
                </td>
            </tr>

        </table>

        <div style="page-break-after: always;"></div>

        <h5 style="text-align: center; align-items : center; padding: 20px; color:#0e70cd;">
            Defects Pareto %
        </h5>


        <table>
            <tr>
                <td style="width:100%">
                    <div style="padding: 10px;">
                        <div style="border: 1px solid #0e70cd;padding: 10px;">
                            <h5 style="text-align: center">Parameter wise Pareto Lending</h5>
                            <img style="width: 90%; height:auto; text-align: center;padding: 15px;"
                                src="data:image/png;base64,{{ base64_encode($LLsubParametricConfig) }}"
                                alt="SLA Chart">
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width:100%">
                    <div style="padding: 10px;">
                        <div style="border: 1px solid #0e70cd;padding: 10px;">
                            <h5 style="text-align: center">Parameter wise Pareto CC</h5>
                            <img style="width: 90%;height:auto;text-align: center;padding: 15px;"
                                src="data:image/png;base64,{{ base64_encode($CCsubParametricConfig) }}"
                                alt="SLA Chart">
                        </div>
                    </div>
                </td>
            </tr>

        </table>

        <h5 style="text-align: center; align-items : center; padding: 20px; color:#0e70cd;">
            LOB wise Score% (Lending & CC)
        </h5>
        <table>
            <tr>
                <td style="width:100%">
                    <div style="padding: 10px;">
                        <div style="border: 1px solid #0e70cd;padding: 10px;">
                            <h5 style="text-align: center">Lob wise Score% - Overall</h5>
                            <img style="width: 100%;text-align: center;padding: 15px;"
                                src="data:image/png;base64,{{ base64_encode($LobWiseScoresConfig) }}" alt="SLA Chart">
                        </div>
                    </div>
                </td>
            </tr>

        </table>

        <h5 style="text-align: center; align-items : center; padding: 20px; color:#0e70cd;">
            Location Wise LOB's Score% Of LENDING
        </h5>

        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                @php $count = 0; @endphp
                @foreach ($LocImages as $locationKey => $Chart)
                    <td style="vertical-align: top; padding: 10px;text-align: center;">
                        <div style="padding: 10px;border: 1px solid #0e70cd;">
                            <h5 style="text-align: center">Lob wise Score% - Lending {{ $locationKey }} </h5>
                            <img style="width: 100%; text-align: center; padding: 15px;"
                                src="data:image/png;base64,{{ base64_encode($Chart) }}" alt="TL Chart">
                        </div>
                    </td>
                    @php $count++; @endphp
                    @if ($count % 2 == 0 || $loop->last)
            </tr>
            @if (!$loop->last)
                <tr>
            @endif
            @endif
            @endforeach
        </table>

        <h5 style="text-align: center; align-items : center; padding: 20px; color:#0e70cd;">
            Location Wise LOB's Score% Of CC
        </h5>

        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                @php $count = 0; @endphp
                @foreach ($LocImagesCC as $locationKeyCC => $ChartCC)
                    <td style="vertical-align: top; padding: 10px;text-align: center;">
                        <div style="padding: 10px;border: 1px solid #0e70cd;">
                            <h5 style="text-align: center">Lob wise Score% - CC {{ $locationKeyCC }} </h5>
                            <img style="width: 100%; text-align: center; padding: 15px;"
                                src="data:image/png;base64,{{ base64_encode($ChartCC) }}" alt="TL Chart">
                        </div>
                    </td>
                    @php $count++; @endphp
                    @if ($count % 2 == 0 || $loop->last)
            </tr>
            @if (!$loop->last)
                <tr>
            @endif
            @endif
            @endforeach
        </table>

    </body>

</html>
