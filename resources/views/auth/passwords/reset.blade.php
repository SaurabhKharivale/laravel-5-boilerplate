@extends('layouts.master')

@section('content')
<div class="container">
    <div class="form-container centered">
        <h3 class="form-title">Reset Password</h3>

        @if (session('status'))
            <div class="notification is-success">
                <span class="icon">
                    <i class="fa fa-check-circle"></i>
                </span>
                {{ session('status') }}
            </div>
        @endif

        <form class="form" role="form" method="POST" action="{{ route('password.request') }}">
            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="field">
                <p class="control has-icon">
                    <input type="email" class="input" name="email" value="{{ $email or old('email') }}" placeholder="E-Mail Address" required autofocus>
                    <span class="icon is-small is-left">
                        <i class="fa fa-envelope"></i>
                    </span>
                    @if ($errors->has('email'))
                        <p class="help is-danger">{{ $errors->first('email') }}</p>
                    @endif
                </p>
            </div>

            <div class="field">
                <p class="control has-icon">
                    <input type="password" class="input" name="password" placeholder="Password" required>
                    <span class="icon is-small is-left">
                        <i class="fa fa-key"></i>
                    </span>
                    @if ($errors->has('password'))
                        <p class="help is-danger">{{ $errors->first('password') }}</p>
                    @endif
                </p>
            </div>

            <div class="field">
                <p class="control has-icon">
                    <input type="password" class="input" name="password_confirmation" placeholder="Confirm Password" required>
                    <span class="icon is-small is-left">
                        <i class="fa fa-key"></i>
                    </span>
                    @if ($errors->has('password_confirmation'))
                        <p class="help is-danger">{{ $errors->first('password_confirmation') }}</p>
                    @endif
                </p>
            </div>

            <div class="field">
                <p class="control">
                    <button type="submit" class="button is-primary is-outlined is-fullwidth">
                        Reset Password
                    </button>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection