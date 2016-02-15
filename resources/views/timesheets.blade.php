@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                @include('partials._page-header', ['pageTitle' => 'Time Sheets', 'pageSubTitle' => 'for ' . $userName])
                <timesheets-list user-id="{{ $userId }}"></timesheets-list>
            </div>
        </div>
    </div>
@endsection
