@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                @include('partials._page-header', ['pageTitle' => 'Users', 'pageSubTitle' => null])

                <users-list></users-list>

            </div>
        </div>
    </div>
@endsection
