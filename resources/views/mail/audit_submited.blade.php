@component('mail::message')
# Hello,
You have submited an audit 
Call id:    {{ $data['call_id'] }}



Thanks,<br>
{{ config('app.name') }}
<img alt="Logo" src="https://simpliq.qdegrees.com/qdegrees_logo.png">
@endcomponent
