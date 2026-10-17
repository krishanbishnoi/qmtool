@component('mail::message')
# Hello {{$data['name']}}

This mail is regarding update to New Password Policy. As per policy password creation & Update will be at user side.<br/>

Password Creation on first time use, Forgot password for existing user and Update password features are included in the existing tool.<br/>
You are receiving this mail because you are already part of the existing system. To ensure the policy please update your existing password.<br/>

Use “Update Password” button to reset your password.<br/>

User Email:- {{$data['email']}} 

@component('mail::button', ['url' => $data['url']])
Update Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
