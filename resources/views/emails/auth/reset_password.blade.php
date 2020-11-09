{{ $passwordReset->email }}: <a href="{{ url('/password?token=' . $passwordReset->token) }}">{{ $passwordReset->token }}</a>
