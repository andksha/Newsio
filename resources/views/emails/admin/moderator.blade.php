Follow this link to confirm your email and change default password:
<a href="{{ url('moderator/confirmation?token=' . $moderatorConfirmation->token) }}">{{ $moderatorConfirmation->token }}</a>