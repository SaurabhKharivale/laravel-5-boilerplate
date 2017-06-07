@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="column is-offset-4 is-4">
            <div class="box">
                <h1>Profile</h1>
                <p>Name: {{ $user->first_name . ' '. $user->last_name }}</p>
                <p>Email: {{ $user->email }}</p>
            </div>

            @include('profile.change-password')

        </div>
    </div>
@endsection
