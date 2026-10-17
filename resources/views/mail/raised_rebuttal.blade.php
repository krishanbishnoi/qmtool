@component('mail::message')
# Hello,

{{ $data['msg'] }}

<br/>
<div style="overflow-x:auto;">
<table >
 
    <tr>
        <th style="border: 1px solid black;
  border-collapse: collapse;"><b>Audit Date</b></th>
        <th style="border: 1px solid black;
  border-collapse: collapse;"><b>Partner</b></th>
        <th style="border: 1px solid black;
  border-collapse: collapse;"><b>Location</b></th>
        <th style="border: 1px solid black;
  border-collapse: collapse;"><b>Call Id</b></th>
        <th style="border: 1px solid black;
  border-collapse: collapse;"><b>Agent Name</b></th>    
        <th style="border: 1px solid black;
  border-collapse: collapse;"><b>Emp. Id</b></th>
        <th style="border: 1px solid black;
  border-collapse: collapse;"><b>LOB</b></th>
        <th style="border: 1px solid black;
  border-collapse: collapse;"><b>Case Id</b></th>
        <th style="border: 1px solid black;
  border-collapse: collapse;"><b>Call Time</b></th>
        <th style="border: 1px solid black;
  border-collapse: collapse;"><b>Customer Phone</b></th>
        <th style="border: 1px solid black;
  border-collapse: collapse;"><b>Parameter</b></th>
        <th style="border: 1px solid black;
  border-collapse: collapse;"><b>Sub Parameter</b></th>
        <th style="border: 1px solid black;
  border-collapse: collapse;"><b>Status</b></th>
        <th style="border: 1px solid black;
  border-collapse: collapse;"><b>Auditor Name</b></th>
  </tr>
  <tr>
    <td style="border: 1px solid black;
  border-collapse: collapse;">{{ $data['audit_date'] }}</td>
    <td style="border: 1px solid black;
  border-collapse: collapse;">{{ $data['partner_name'] }}</td>
    <td style="border: 1px solid black;
  border-collapse: collapse;">{{ $data['location'] }}</td>
    <td style="border: 1px solid black;
  border-collapse: collapse;">{{ $data['call_id'] }}</td>
    <td style="border: 1px solid black;
  border-collapse: collapse;">{{ $data['agent_name'] }}</td>
    <td style="border: 1px solid black;
  border-collapse: collapse;">{{ $data['emp_id'] }}</td>
    <td style="border: 1px solid black;
  border-collapse: collapse;">{{ $data['lob'] }}</td>
    <td style="border: 1px solid black;
  border-collapse: collapse;">{{ $data['case_id'] }}</td>
    <td style="border: 1px solid black;
  border-collapse: collapse;">{{ $data['call_time'] }}</td>
    <td style="border: 1px solid black;
  border-collapse: collapse;">{{ $data['phone_number'] }}</td>
    <td style="border: 1px solid black;
  border-collapse: collapse;">{{ $data['parameter'] }}</td>
    <td style="border: 1px solid black;
  border-collapse: collapse;">{{ $data['sub_parameter'] }}</td>
    <td style="border: 1px solid black;
  border-collapse: collapse;">{{ $data['status'] }}</td>
    <td style="border: 1px solid black;
  border-collapse: collapse;">{{ $data['auditor_name'] }}</td>

  </tr>
</table>
</div>
<br/>
{{ $data['msg_1'] }}
 

<!-- @component('mail::button', ['url' => $data['url']])
Login Now
@endcomponent -->

Thanks,<br>
{{ config('app.name') }}
<img alt="Logo" src="https://simpliq.qdegrees.com/qdegrees_logo.png">
@endcomponent
