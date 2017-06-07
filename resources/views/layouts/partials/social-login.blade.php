<div class="social-login">
    <p>
        @if(request()->is('register'))
            <a href="{{ route('login') }}">Login</a>
        @endif

        @if(request()->is('login'))
            <a href="{{ route('register') }}">Register</a>
        @endif
    </p>
    <p>
        <a href="{{ url('/auth/google') }}">Sign in with Google</a>
    </p>
    <p>
        <a href="{{ url('/auth/twitter') }}">Sign in with Twitter</a>
    </p>
</div>
