<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    @include('layouts.partials.verify-email-reminder')

    @include('layouts.partials.nav')

    @yield('content')

    <div id="flash-notification">
        <notifier
            message="{{ session('notification.message') }}"
            persist="{{ session('notification.persist') }}"
            type="{{ session('notification.type') }}">
        </notifier>
    </div>

    <!-- Scripts -->
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            var navBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

            if (navBurgers.length > 0) {
                navBurgers.forEach(function (burger) {
                    burger.addEventListener('click', () => {
                        var target = document.getElementById(burger.dataset.target);

                        burger.classList.toggle('is-active');
                        target.classList.toggle('is-active');
                    });
                });
            }
        });
    </script>
    @yield('scripts')
    <script src="{{ mix('/js/manifest.js') }}"></script>
    <script src="{{ mix('/js/vendor.js') }}"></script>
    <script src="{{ mix('/js/core.js') }}"></script>
    <script src="{{ mix('/js/app.js') }}"></script>
    @if(auth()->guard('admin')->check())
        <script src="{{ mix('/js/admin.js') }}"></script>
    @endif
</body>
</html>
