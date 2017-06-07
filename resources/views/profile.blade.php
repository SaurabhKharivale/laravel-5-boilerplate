@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="column is-offset-4 is-4">
            <div class="box">
                <h1>Profile</h1>
                <p>Name: {{ $user->first_name . ' '. $user->last_name }}</p>
                <p>Email: {{ $user->email }}</p>
            </div>
            <div class="form-container">
                <h1 class="form-title">Change password</h1>
                <div class="form-content">
                    <form class="form" action="{{ route('password.change') }}" method="post">
                        {{ csrf_field() }}

                        @component('components.input', [
                            'name' => 'current_password',
                            'type' => 'password',
                            'placeholder' => 'Current password',
                        ])
                        @endcomponent

                        @component('components.input', [
                            'name' => 'new_password',
                            'type' => 'password',
                            'placeholder' => 'New password',
                        ])
                        @endcomponent

                        @component('components.input', [
                            'name' => 'new_password_confirmation',
                            'type' => 'password',
                            'placeholder' => 'Confirm password',
                        ])
                        @endcomponent

                        <div class="field">
                            <p class="control">
                                <button class="register button is-primary is-outlined is-fullwidth" :disabled="form.errors.any()">Change password</button>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
