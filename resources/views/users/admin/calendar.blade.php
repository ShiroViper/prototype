@extends('layouts.app')

@section('title')
<title>Alkansya</title>
@endsection

@section('content')
{{-- <h3 class="header mt-3">Schedules</h3> --}}
<div class="row mt-2">
    <div class="col-md-8 my-3">
        <div class="card shadow">
            {{-- <div class="card-header">Calendar</div> --}}
            <div class="card-body">
            <div class="d-flex justify-content-center">
                <div class="spinner-border text-primary p-5 m-5" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
                {!! $calendar_details->calendar() !!}
            </div>
        </div>
    </div>
    <div class="col-md my-3">
        <div class="card shadow">
        <div class="card-body">
            <div class="legend">
                <h5 class="header">Legend:</h5>
                <div class="clearfix">
                    <div class="d-flex flex-row align-items-center p-1">
                        <div class="square current-date"></div>
                        <span class="header ml-1">Current Date</span>
                    </div>
                    <div class="d-flex flex-row align-items-center p-1">
                        <div class="square paid-dates"></div>
                        <span class="header ml-1">Paid Dates</span>
                    </div>
                    <div class="d-flex flex-row align-items-center p-1">
                        <div class="square pay-dates"></div>
                        <span class="header ml-1">Pay Dates</span>
                    </div>
                    <div class="d-flex flex-row align-items-center p-1">
                        <div class="square loan-dates"></div>
                        <span class="header ml-1">Loan Dates</span>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js"></script>
    {!! $calendar_details->script() !!}
@endsection
