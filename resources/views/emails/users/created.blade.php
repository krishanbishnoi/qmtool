@component('mail::message')
# Hello {{$data['name']}}

You are registered as {{$data['roles']}} by {{$data['company_name']}} at QM Tool Platform. To create your password for login, click on set password button.<br/>
User Name:- {{$data['email']}} <br/>
<!--Password:- {{$data['password']}}  --><br/>

@component('mail::button', ['url' => $data['url']])
Set Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
