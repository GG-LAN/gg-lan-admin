@component('mail::message')
# Salut !

Merci de cliquer sur le bouton ci-dessous pour réinitialiser votre mot de passe.

@component('mail::button', ['url' => $url])
Réinitialiser mon mot de passe
@endcomponent


GLHF,<br>
{{ config('app.name') }}

@component('mail::subcopy')
Si vous n'arrivez pas à cliquer sur le bouton "Réinitialiser mon mot de passe",cliquez ou copier/coller cette url: <a href="{{ $url }}">{{ $url }}</a>
@endcomponent

@component('mail::footer')
Ceci est un mail automatique, merci de ne pas y répondre.
@endcomponent

@endcomponent
