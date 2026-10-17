@component('mail::message')
# Hello

You are requested for Calibration Process by {{$data['company_name']}} as {{$data['as']}}, details are as follows:-
<br/>Client:- {{$data['client_name']}}
<br/>Process:- {{$data['process_name']}}
<br/>Submission Date:- {{$data['due_date']}}

<br/>Request you to submit your calibration before submission due date.

@component('mail::button', ['url' => $data['url']])
Start Now
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
