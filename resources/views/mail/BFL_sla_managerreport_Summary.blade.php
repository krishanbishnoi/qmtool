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
    </style>
</head>


<body>

    @php
        $locations = array_keys($locationData[0]);
        $auditCounts = array_column($locationData[0], 'auditCounts');
        $qualityScores = array_column($locationData[0], 'LOCQualityScore');
        $lobs = array_column($locationData[0], 'lob');
    @endphp


        <p1>Hi </p1>
        <br>
        <p>Please find the Attached LOB summary fortnight report pdf</p>

        <h4><b><u>Summary:</u> </b></h4>

        @php
            $locations_count = count($locationData[0]);
            $overall_qualityScore = array_sum($qualityScores) / $locations_count;
        @endphp
        <p><b> Overall Quality score is {{ $overall_qualityScore }}% in Last week </b></p>

        @foreach ($locations as $key => $location)
        <p  style="padding-left: 14px "> <b>{{ $lobs[0] }} {{ $location }} Quality Score is at {{ $qualityScores[$key] }}% .  </b></p>
        @endforeach

        {{-- <p>QDegrees auditor's daily audit output stands at 45.</p> --}}

        {{-- <p>All feedback is routed through the QM Tool.  </p>
            <p style="padding-left: 14px ">{{ $lobs[0] }} - Feedback closed with 100%</p> --}}

        <h4><b><u>TOP 3 Parameter Errors:</u> </b></h4>
        @foreach ($cumulativePercentages as $key => $value)
        @if ($loop->index < 3)
            @php
                $subparameter = App\QmSheetSubParameter::select('sub_parameter')->where('id', $key)->first();
                if ($subparameter) {
                    $subparameterName =  $subparameter->sub_parameter;
                    $errorCount = $value['auditcounts'];
                    $cumulativePercentage = $value['cumulative'];
                }
            @endphp

            <p>
                • {{ $subparameterName }} with error count of {{ $errorCount }} equating at {{ $cumulativePercentage }}%
            </p>
        @endif
     @endforeach





</body>

