@component('mail::panel')
# Confirmation

Suite à votre demande pour la réinstalisation du mot de passe, <br>
Veuillez cliquer sur ce bouton
@component('mail::button', ['url' => 'http://localhost:8100/reset/password?email='.$email.'&token='.$token])
confirm
@endcomponent

Thanks,<br>
MsFood
@endcomponent
