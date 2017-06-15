@extends('layouts.master')

@section('content')
<section class="section">
    <div class="container">
        <div class="page-404">
            <h1 class="title is-1 is-spaced">OOPS!</h1>
            <h2 class="subtitle">The page you are looking for does not exist.</h2>
            <p class="error-code">Error code: 404</p>
            <br>
            <p>
                <a href="{{ url('/') }}" class="back-to-home">
                    <span class="icon is-small">
                        <i class="fa fa-arrow-left"></i>
                    </span>
                    <span>Back to home</span>
                </a>
            </p>
        </div>
    </div>
</section>
@endsection
