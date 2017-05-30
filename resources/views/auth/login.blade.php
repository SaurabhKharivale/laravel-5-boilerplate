@extends('layouts.master')

@section('content')
<div class="container">
    <div class="form-container centered" id="login-form">
        <h1 class="form-title">Login</h1>
        <form class="form" role="form" method="POST" action="{{ route('login') }}" @keydown="form.errors.clear($event.target.name)">
            {{ csrf_field() }}

            @component('components.input', [
                'name' => 'email',
                'icon' => 'envelope',
                'autofocus' => true,
                'clearAllErrorsOnInput' => true,
            ])
            @endcomponent

            @component('components.input', [
                'name' => 'password',
                'type' => 'password',
                'icon' => 'key',
                'clearAllErrorsOnInput' => true,
            ])
            @endcomponent

            <div class="field">
                <p class="control">
                    <label class="checkbox">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                    </label>
                </p>
            </div>

            <div class="field">
                <p class="control">
                    <button class="login button is-primary is-fullwidth" :disabled="form.errors.any()">Login</button>
                </p>
            </div>

            <div class="field">
                <a href="{{ route('password.request') }}">
                    Forgot Your Password?
                </a>
            </div>

            <hr>

            <div class="field">
                <p class="control">
                    Don't have an account?
                    <a href="{{ route('register') }}">
                        Create now.
                    </a>
                </p>
            </div>
        </form>

        @include('layouts.partials.social-login')
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript">
        window.form_errors = {!! json_encode($errors->toArray()) !!};
        window.form_old_inputs = {!! json_encode(old()) !!};
    </script>
@endsection
