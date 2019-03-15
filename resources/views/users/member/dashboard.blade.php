@extends('layouts.app')

@section('title')
<title>Alkansya</title>
@endsection

@section('content')
    @if ($setup)
        <h3>yesss empty</h3>
    @else
        <h3 class="header mt-3">My Calendar</h3>
        <div class="row">
            <div class="col-md-8 my-3">
                <div class="card">
                    <div class="card-header">Calendar</div>
                    <div class="card-body">
                        {!! $calendar_details->calendar() !!}
                    </div>
                </div>
            </div>
            <div class="col-md my-3">
                <div class="legend">
                    <h5 class="header">Legend:</h5>
                    <div class="clearfix">
                        <div class="d-flex flex-row align-items-center p-1">
                            <div class="square current-date"></div>
                            <span class="header ml-1">Current Date</span>
                        </div>
                        <div class="d-flex flex-row align-items-center p-1">
                            <div class="square pay-dates"></div>
                            <span class="header ml-1">Pay Dates</span>
                        </div>
                        <div class="d-flex flex-row align-items-center p-1">
                            <div class="square paid-dates"></div>
                            <span class="header ml-1">Paid Dates</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js"></script>
        {!! $calendar_details->script() !!}
    @endif
@endsection
