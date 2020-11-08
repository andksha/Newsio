{{ $emailConfirmation->email }}: <a href="{{ url('/confirmation?token=' . $emailConfirmation->token) }}">{{ $emailConfirmation->token }}</a>
