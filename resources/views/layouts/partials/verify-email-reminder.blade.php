@if(! request()->is('admin/*'))
    @if(auth()->check() && ! auth()->user()->verified)
        <div class="message is-warning">
            <div class="container">
                <p>
                    Account not verified. Please check your email for activation email.
                    <a href="{{ url('/resend-activation-link') }}" class="resend-activation-link">Resend activation email.</a>
                </p>
            </div>
        </div>
    @endif
@endif
