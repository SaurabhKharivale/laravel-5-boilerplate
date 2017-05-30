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

        <form class="form" role="form" method="POST" action="{{ route('password.email') }}">
            {{ csrf_field() }}

            <div class="field">
                <p class="control has-icon">
                    <input type="email" class="input" name="email" value="{{ old('email') }}" placeholder="E-Mail Address" required>
                    <span class="icon is-small is-left">
                        <i class="fa fa-envelope"></i>
                    </span>
                    @if ($errors->has('email'))
                        <p class="help is-danger">{{ $errors->first('email') }}</p>
                    @endif
                </p>
            </div>

            <div class="field">
                <p class="control">
                    <button type="submit" class="send-reset-link button is-primary is-outlined is-fullwidth">
                        Send Password Reset Link
                    </button>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
