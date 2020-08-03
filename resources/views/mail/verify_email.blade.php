<p>Hallo {{$userEmail->unverified_user->username}}!</p>
<br/>
<p>jemand hat soeben deine E-Mail Adresse bei KStats deinem Account zugewiesen. Bitte bestätige, dass diese Adresse zu
    dir gehört, indem du auf folgenden Link klickst:</p>
<br/>
<a href="{{route('user.verify', ['user_id' => $userEmail->unverified_user->id, 'verification_key' => $userEmail->verification_key])}}">
    {{route('user.verify', ['user_id' => $userEmail->unverified_user->id, 'verification_key' => $userEmail->verification_key])}}
</a>

<p>Solltest du das nicht gewesen sein, klicke bitte nicht auf den Link und wende dich an den KStats Support.</p>
<br/>
<p>Viele Grüße!</p>