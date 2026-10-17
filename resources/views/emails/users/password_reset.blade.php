@component('mail::message')
# Hello {{$data['name']}}

We have received a reset password request from this email account.To reset your password for login, click on set password button.<br/>
<!--User Name:- {{$data['email']}} <br/>
Password:- {{$data['password']}}  <br/> -->

@component('mail::button', ['url' => $data['url']])
Set Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
