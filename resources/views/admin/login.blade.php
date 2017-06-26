@extends('layouts.master')

@section('content')
<div class="container">
    <div class="app-content is-padded is-centered">
        <div class="form-container is-fixed-width" id="login-form">
            <h1 class="form-title has-text-centered">Welcome back!</h1>
            <div class="form-content">
                <form class="form" role="form" method="POST" action="{{ route('admin.login') }}" @keydown="form.errors.clear($event.target.name)">
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
                            <button class="login button is-primary is-outlined is-fullwidth" :disabled="form.errors.any()">Login</button>
                        </p>
                    </div>

                </form>
            </div>
        </div>
        <div class="field">
            <a href="{{ route('admin.password.request') }}">Forgot password?</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript">
        window.form_errors = {!! json_encode($errors->toArray()) !!};
        window.form_old_inputs = {!! json_encode(old()) !!};
    </script>
@endsection
