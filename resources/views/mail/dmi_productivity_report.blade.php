<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Productivity Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .total-row {
            font-weight: bold;
            background-color: #e2e3e5;
        }
    </style>
</head>

<body>
    <h2>Hourly Productivity Report - {{ now()->format('d M Y') }}</h2>

    <table>
        <thead>
            <tr>
                <th>QA Name</th>
                <th>Client Name</th>
                <th>Target</th>
                @foreach (array_keys($data[0]['hours']) as $slot)
                    <th>{{ $slot }}</th>
                @endforeach
                <th>Total Audits</th>
                <th>Achived percentage%</th>
                <th>Fatal Score (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr class="{{ $row['qa_name'] == 'Total' ? 'total-row' : '' }}">
                    <td>{{ $row['qa_name'] }}</td>
                    <td>{{ $row['client_name'] }}</td>
                    <td>{{ $row['target'] }}</td>

                    {{-- Hourly counts --}}
                    @foreach ($row['hours'] as $count)
                        <td>{{ $count }}</td>
                    @endforeach

                    <td>{{ $row['total'] }}</td>
                    <td>
                        {{ $row['target'] > 0 ? round(($row['total'] / $row['target']) * 100, 2) : 0 }} %
                    </td>

                    <td>
                        @if ($row['fatal_score_overall'] !== '')
                            {{ number_format($row['fatal_score_overall'], 2) }}%
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
