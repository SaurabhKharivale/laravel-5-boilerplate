<nav class="nav has-shadow">
    <div class="container">
        <div class="nav-left">
            <a class="nav-item">
                <img src="http://bulma.io/images/bulma-logo.png" alt="Bulma logo">
            </a>
            <a class="nav-item is-tab is-hidden-mobile is-active">Home</a>
            <a class="nav-item is-tab is-hidden-mobile">Features</a>
            <a class="nav-item is-tab is-hidden-mobile">About</a>
        </div>
        <span class="nav-toggle">
            <span></span>
            <span></span>
            <span></span>
        </span>
        <div class="nav-right nav-menu">
            <a class="nav-item is-tab is-hidden-tablet is-active">Home</a>
            <a class="nav-item is-tab is-hidden-tablet">Features</a>
            <a class="nav-item is-tab is-hidden-tablet">About</a>
            @if (Auth::guest())
                <a class="nav-item is-tab" href="{{ route('login') }}">Login</a>
                <a class="nav-item is-tab" href="{{ route('register') }}">Register</a>
            @else
                <a href="{{ route('profile') }}" class="nav-item is-tab">
                    {{ Auth::user()->first_name }} <span class="caret"></span>
                </a>


                <a class="nav-item is-tab" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @endif
        </div>
    </div>
</nav>
