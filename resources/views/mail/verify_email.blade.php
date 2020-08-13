<p>{{__('general.mail.hello', ['name' => $userEmail->unverified_user->username])}}</p>
<br/>
<p>{{__('settings.verify_mail.intro')}} {{__('settings.verify_mail.confirmation')}}</p>
<br/>
<a href="{{route('user.verify', ['user_id' => $userEmail->unverified_user->id, 'verification_key' => $userEmail->verification_key])}}">
    {{route('user.verify', ['user_id' => $userEmail->unverified_user->id, 'verification_key' => $userEmail->verification_key])}}
</a>

<p>{{__('settings.verify_mail.disclaimer')}}</p>
<br/>
<p>{{__('general.mail.goodbye')}}</p>