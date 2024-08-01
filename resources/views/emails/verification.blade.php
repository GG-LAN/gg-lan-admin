@component('mail::message')
# Salut !

Merci de cliquer sur le bouton ci-dessous pour vérifier votre adresse mail.

@component('mail::button', ['url' => $url])
Vérifier mon adresse mail
@endcomponent


GLHF,<br>
{{ config('app.name') }}

@component('mail::subcopy')
Si vous n'arrivez pas à cliquer sur le bouton "Vérifier mon adresse mail",cliquez ou copier/coller cette url: <a href="{{ $url }}">{{ $url }}</a>
@endcomponent

@component('mail::footer')
Ceci est un mail automatique, merci de ne pas y répondre.
@endcomponent

@endcomponent
