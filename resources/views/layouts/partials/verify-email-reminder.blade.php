@if(! request()->is('admin/*'))
    @if(auth()->check() && ! auth()->user()->verified)
        <div class="activation-reminder message is-warning">
            <p align="center">
                Account not verified. Please check your email for activation email.
                <a href="{{ url('/resend-activation-link') }}" class="resend-activation-link">
                    Resend activation email.
                </a>
            </p>
        </div>
    @endif
@endif
