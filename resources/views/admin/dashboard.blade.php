@extends('layouts.master')

@section('content')
<section class="section">
    <div id="admin" class="container">
        <div class="columns">
            <div class="column is-8">
                <h1 class="title is-4">Admin dashboard</h1>
                <admins-list></admins-list>
                <create-admin></create-admin>
            </div>
        </div>
    </div>
</section>
@endsection
