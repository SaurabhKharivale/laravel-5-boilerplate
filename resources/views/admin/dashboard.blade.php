@extends('layouts.master')

@section('content')
<section class="section">
    <div id="admin" class="container">
        <h1 class="title is-4">Admin dashboard</h1>
        @can('create', App\Admin::class)
        <div class="columns">
            <div class="column is-8">
                <h3 class="heading">Manage admins</h3>
                <admins-list></admins-list>
                <create-admin></create-admin>
            </div>
        </div>
        @endcan
    </div>
</section>
@endsection
