@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                @include('partials._page-header', ['pageTitle' => 'Time Sheets', 'pageSubTitle' => $dateRange])

                @foreach ($timeSheets->groupBy('date') as $date => $timeSheetsPerDate)
                    <?php
                    $totalHours = $timeSheetsPerDate->sum('hours');
                    ?>
                    <ul class="list-group">
                        <li class="list-group-item active">
                            {{ $date }}
                            <span class="pull-right">{{ $totalHours }} {{ str_plural('hour', $totalHours) }}</span>
                        </li>
                        <li class="list-group-item">
                            @foreach ($timeSheetsPerDate as $timeSheet)
                                <div class="well well-sm">{{ $timeSheet->description }}</div>
                            @endforeach
                        </li>
                    </ul>
                @endforeach
            </div>
        </div>
    </div>
@endsection