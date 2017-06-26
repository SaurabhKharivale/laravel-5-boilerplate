@extends('layouts.master')

@section('content')
<div class="container">
    <div class="app-content is-padded is-centered">
        <div class="form-container centered">
            <h3 class="form-title has-text-centered">Reset Password</h3>
            <div class="form-content">
                @if (session('status'))
                    <div class="notification is-success">
                        <span class="icon">
                            <i class="fa fa-check-circle"></i>
                        </span>
                        {{ session('status') }}
                    </div>
                @else
                    <div class="message">
                        <p class="message-body">
                            <span class="icon">
                                <i class="fa fa-info-circle"></i>
                            </span>
                            Password reset link will be sent to your email address.
                        </p>
                    </div>
                @endif
                <form class="form" role="form" method="POST" action="{{ route('admin.password.email') }}">
                {{ csrf_field() }}

                <div class="field">
                    <p class="control has-icon">
                        <input type="email" class="input" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required>
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
                            Send Reset Link
                        </button>
                    </p>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection
