<nav class="navbar">
    <div class="navbar-brand">
        <a class="navbar-item" href="{{ url('/') }}">
            <strong>L5 Boilerplate</strong>
        </a>
        <div class="navbar-burger" data-target="navMenu">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="navbar-menu" id="navMenu">
        <div class="navbar-start">
            <a class="navbar-item">Home</a>
            <a class="navbar-item">Features</a>
            <a class="navbar-item">About</a>
        </div>
        <div class="navbar-end">
            @if(Auth::check())
                <a href="{{ route('profile') }}" class="navbar-item">
                    {{ Auth::user()->first_name }} <span class="caret"></span>
                </a>

                <a class="navbar-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @elseif(Auth::guard('admin')->check())
                <a href="#" class="navbar-item">
                    {{ Auth::guard('admin')->user()->first_name }} <span class="caret"></span>
                </a>

                <a class="navbar-item" href="{{ route('admin.logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    Logout
                </a>

                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @else
                <a class="navbar-item" href="{{ route('login') }}">Login</a>
                <a class="navbar-item" href="{{ route('register') }}">Register</a>
            @endif
        </div>
    </div>
</nav>
