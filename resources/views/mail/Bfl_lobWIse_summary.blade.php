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




    <body
        style="width:100%;font-family:arial, 'helvetica neue', helvetica, sans-serif;padding:0;margin:0;background: #f2f3f8;">

        @php
            $totalAuditcount = 0;
            $qualityScore = 0;
            foreach ($weekData as $data) {
                $totalAuditcount += $data['auditCountsWeekwise'];
                $qualityScore += $data['OveallqualityScore'];
            }
        @endphp


        @php
            $lobs = array_column($locationData[0], 'lob');
        @endphp

        <h3 style="text-align: center; align-items : center ; padding: 20px; color:#0e70cd;">
            <b>{{ $lobs[0] }}</b>
            overall LOB Wise Audit count ({{ $totalAuditcount }}) and Quality score ({{ round($qualityScore / 5) }}%)
            for
            last 5 weeks.
        </h3>

        {{--
        <p style="text-align: center; align-items : center ; padding: 20px;  color:#510ecd; "><b> Overall Audit Counts is in Last 5 week </b></p>
        <p style="text-align: center; align-items : center ; padding: 20px; color:#510ecd; "><b> Overall Quality score is  in Last 5 week </b></p> --}}




        <table style="width:100%">
            <tr>
                <td style="width:50%">
                    <div style="padding: 10px;">
                        <div style="border: 1px solid #0e70cd;padding: 10px;">
                            <h5 style="text-align: center">{{ $lobs[0] }} Overall Week wise Scores Trend</h5>
                            <img style="width: 100%;text-align: center;padding: 15px;"
                                src="data:image/png;base64,{{ base64_encode($overallTrendImage) }}" alt="SLA Chart">
                        </div>
                    </div>
                </td>
                <td>
                    <div style="padding:10px">
                        <div style="border: 1px solid #0e70cd;padding: 10px;">
                            <h5 style="text-align: center">{{ $lobs[0] }} Overall Sale/Non-Sale Scores</h5>
                            <img style=" width: 100%;text-align: center;padding: 15px;"
                                src="data:image/png;base64,{{ base64_encode($saleNonSaleImage) }}" alt="SLA Chart">
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <div style="padding:10px">
            <div style="border: 1px solid #0e70cd;padding: 10px;">
                <img style=" width: 90%;text-align: center;padding: 15px;"
                    src="data:image/png;base64,{{ base64_encode($subParametricChartImage) }}" alt="SLA Chart">
            </div>
        </div>


        <div style="page-break-after: always;"></div>

        <h5 style="text-align: center; align-items : center; padding: 20px; color:#0e70cd;">
            <b>{{ $lobs[0] }}</b>
            Location Wise performance
        </h5>


        <div style="padding: 10px;">
            <div style="border: 1px solid #0e70cd;padding: 10px;">
                <h3 style="text-align: center">{{ $lobs[0] }} Location Wise Performance</h3>
                <img style="width: 100%;text-align: center;padding: 15px;"
                    src="data:image/png;base64,{{ base64_encode($auditCountChart) }}" alt="SLA Chart">
            </div>
        </div>

        <p style="text-align: center; align-items : center; padding: 20px; color:#0e70cd;">
            <b>{{ $lobs[0] }}</b>
            Location Wise Trends
        </p>


        <div>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    @php $count = 0; @endphp
                    @foreach ($OverallLocationsCharts as $locationKey => $locationstrends)
                        <td style="vertical-align: top; padding: 10px;text-align: center;">
                            <div style="padding: 10px;border: 1px solid #0e70cd;">
                                <h5 style="text-align: center">{{ $lobs[0] }} -{{ $locationKey }} Location Wise trends
                                </h5>
                                <img style="width: 100%; text-align: center; padding: 15px;"
                                    src="data:image/png;base64,{{ base64_encode($locationstrends) }}" alt="TL Chart">
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
        </div>

        <p style="text-align: center; align-items : center; padding: 20px; color:#0e70cd;">
            <b>{{ $lobs[0] }}</b>
            Location Wise Sale And None sale scores
        </p>


        <div>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    @php $count = 0; @endphp
                    @foreach ($locCharts as $locationKey => $saleNonesale)
                        <td style="vertical-align: top; padding: 10px; text-align: center;">
                            <div style="padding: 10px;border: 1px solid #0e70cd;">
                                <h5 style="text-align: center">{{ $lobs[0] }} -{{ $locationKey }} Sale/Non Sale
                                </h5>
                                <img style="width: 100%; text-align: center; padding: 15px;"
                                    src="data:image/png;base64,{{ base64_encode($saleNonesale) }}" alt="TL Chart">
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
        </div>

        <p style="text-align: center; align-items : center; padding: 20px; color:#0e70cd;">
            <b>{{ $lobs[0] }}</b>
            Location Wise TL Performance
        </p>

        <div>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    @php $count = 0; @endphp
                    @foreach ($tlCharts as $locationKey => $tlChart)
                        <td style="vertical-align: top; padding: 10px;text-align: center;">
                            <div style="padding: 10px;border: 1px solid #0e70cd;">
                                <h5 style="text-align: center">{{ $lobs[0] }} TL Performance -
                                    {{ $locationKey }}
                                </h5>
                                <img style="width: 100%; text-align: center; padding: 15px;"
                                    src="data:image/png;base64,{{ base64_encode($tlChart) }}" alt="TL Chart">
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
        </div>


        <p style="text-align: center; align-items : center; padding: 20px; color:#0e70cd;">
            <b>{{ $lobs[0] }}</b>
            Location Wise SLA Manager Performance
        </p>


        <div>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    @php $count = 0; @endphp
                    @foreach ($SLACharts as $locationKey => $slaChart)
                        <td style="vertical-align: top; padding: 10px;text-align: center;">
                            <div style="padding: 10px;border: 1px solid #0e70cd;">
                                <h5 style="text-align: center">{{ $lobs[0] }} SLA Performance -
                                    {{ $locationKey }}
                                </h5>
                                <img style="width: 100%; text-align: center; padding: 15px;"
                                    src="data:image/png;base64,{{ base64_encode($slaChart) }}" alt="SLA Chart">
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
        </div>

        <p style="text-align: center; align-items : center; padding: 20px; color:#0e70cd;">
            <b>{{ $lobs[0] }}</b>
            Location Wise Subparametric failure charts
        </p>

        <div>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    @php $count = 0; @endphp
                    @foreach ($LocSubCharts as $locationKey => $failureCharts)
                        <td style="vertical-align: top; padding: 10px;text-align: center;">
                            <div style="padding: 10px;border: 1px solid #0e70cd;">
                                <h5 style="text-align: center">{{ $lobs[0] }} - {{ $locationKey }} SubParaMetric
                                    failure Chart
                                </h5>
                                <img style="width: 100%; text-align: center; padding: 15px;"
                                    src="data:image/png;base64,{{ base64_encode($failureCharts) }}" alt="SLA Chart">
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
        </div>




    </body>

</html>
