@component('mail::message')
# Welcome

Thanks for signing up for {{ config('app.name') }}!

Please click the button below to confirm your email address.
@component('mail::button', ['url' => route('activation', [$token])])
Confirm now!
@endcomponent

If above button doesn't work then please click the link below to confirm your email address.
{{ route('activation', [$token]) }}

Thanks,<br>
Team {{ config('app.name') }}
@endcomponent
