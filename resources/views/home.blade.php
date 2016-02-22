@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="jumbotron">
                    <h1>hey!</h1>
                    <p>...</p>
                    <p>
                        @if ($loggedInUserIsAdmin)
                            <a class="btn btn-primary btn-lg"
                               href="{{ url('/users') }}"
                               role="button"
                            >
                                Manage Users
                            </a>
                        @endif
                        <a class="btn btn-success btn-lg"
                           href="{{ url('/users/' . $loggedInUserId . '/timesheets') }}"
                           role="button"
                        >
                            My Timesheets
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
