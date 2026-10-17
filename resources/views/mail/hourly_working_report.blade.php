@component('mail::message')
Hello,
<br>
<br>
Please find below the audit data for Hourly productivity and movement ({{ date("d-m-Y H:i:s") }}).
<style>
    table {
        font-family: Arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    /* td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    } */

    /* tr:nth-child(even) {
        background-color: #f9f9f9;
    } */

    .highlight-red {
        background-color: #f8d7da;
        color: #721c24;
    }
    .highlight-rred {
        background-color: #f8d7da;
        color: #e68892;
    }
</style>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Auditor Name</th>
            <th>QDId</th>
            <th>Target</th>
            <th>Process</th>
            <th>Achieve</th>
            <th>Achieve %</th>
            <th>Present Based on Target</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; @endphp
        @foreach ($data as $value)
            @php
                $newPer = $value['auditCount'] ? round(($value['auditCount'] / $value['qaTargets']) * 100) : 0;
                $total += $value['auditCount'];

                // Determine present status and row highlight
                $present_status = "";
                $rowClass = "";

                if ( $value['auditCount'] < $value['qaTargets']/2 ) {
                    $present_status = "Absent";
                    $rowClass = "highlight-red";
                } elseif ($value['auditCount'] >= $value['qaTargets']/2 && $value['auditCount'] < $value['qaTargets'] ) {
                    $present_status = "Not Completed target";
                    $rowClass = "highlight-rred";
                } else {
                    $present_status = "Present";
                }

                // Get the first process name if available
                $firstProcessId = $value['process'][0] ?? null;
                $firstProcessName = 'NA';

                if ($firstProcessId) {
                    $process = \App\Process::find($firstProcessId);
                    $firstProcessName = $process->name ?? 'NA';
                }
            @endphp

            <tr class="{{ $rowClass }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $value['agentName'] }}</td>
                <td>{{ $value['qdId'] ?? 'NA' }}</td>
                <td>{{ $value['qaTargets'] ?? '40' }}</td>
                <td>{{ $firstProcessName }}</td>
                <td>{{ $value['auditCount'] }}</td>
                <td>{{ $newPer }}%</td>
                <td>{{ $present_status }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4" style="text-align: center;">Total Audits</td>
            <td colspan="3" style="text-align: center;">{{ $total }}</td>
        </tr>
    </tbody>
</table>

@endcomponent
