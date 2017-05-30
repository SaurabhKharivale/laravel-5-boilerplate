@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="form-container centered" id="register-form">
            <h1 class="form-title">Create Your Account</h1>
            <form class="form" role="form" action="/register" method="post"  @keydown="form.errors.clear($event.target.name)" @submit.prevent="onSubmit">
                {{ csrf_field() }}

                @component('components.input', [
                    'icon' => 'user',
                    'name' => 'first_name',
                    'placeholder' => 'First name',
                ])
                @endcomponent

                @component('components.input', [
                    'icon' => 'user',
                    'name' => 'last_name',
                    'placeholder' => 'Last name',
                ])
                @endcomponent

                @component('components.input', [
                    'icon' => 'envelope',
                    'type' => 'email',
                    'name' => 'email',
                ])
                @endcomponent

                @component('components.input', [
                    'icon' => 'key',
                    'type' => 'password',
                    'name' => 'password',
                ])
                @endcomponent

                @component('components.input', [
                    'icon' => 'key',
                    'type' => 'password',
                    'name' => 'password_confirmation',
                    'placeholder' => 'Confirm password',
                ])
                @endcomponent

                <div class="field">
                    <p class="control">
                        <button class="register button is-primary is-fullwidth" :disabled="form.errors.any()">Register</button>
                    </p>
                </div>

                <div class="field">
                    <p class="control">
                        Already have an account?
                        <a href="{{ route('login') }}">
                            Login now.
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        window.form_errors = {!! json_encode($errors->toArray()) !!};
        window.form_old_inputs = {!! json_encode(old()) !!};
    </script>
@endsection
