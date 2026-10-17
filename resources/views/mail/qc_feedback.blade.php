@component('mail::message')
# Hello,



<br/>
<div style="overflow-x:auto;">
    QC provide a feedback please check
</div>
<br/>
<a href="{{ $data['link'] }}"><button class="btn btn-primary">View Audit</button></a>
 



Thanks,<br>
{{ config('app.name') }}
<img alt="Logo" src="https://simpliq.qdegrees.com/qdegrees_logo.png">
@endcomponent
 