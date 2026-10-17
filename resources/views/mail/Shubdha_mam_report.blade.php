<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productivity Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Productivity Report</h1>
    
    <table>
        <thead>
            <tr>
                <th>Agent Name</th>
                <th>Email</th>
                <th>Process Names</th>
                <th>Audit Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $agentName => $audits)
                <tr>
                    <td rowspan="{{ count($audits) }}">{{ $agentName }}</td>
                    <td>{{ $audits[0]['email'] }}</td>
                    <td>
                        @foreach($audits[0]['processNames'] as $processName)
                            {{ $processName }}<br>
                        @endforeach
                    </td>
                    <td>{{ $audits[0]['auditCount'] }}</td>
                </tr>
                @for ($i = 1; $i < count($audits); $i++)
                    <tr>
                        <td>{{ $audits[$i]['email'] }}</td>
                        <td>
                            @foreach($audits[$i]['processNames'] as $processName)
                                {{ $processName }}<br>
                            @endforeach
                        </td>
                        <td>{{ $audits[$i]['auditCount'] }}</td>
                    </tr>
                @endfor
            @endforeach
        </tbody>
    </table>
</body>
</html>