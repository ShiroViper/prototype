@extends('layouts.app')

@section('title')
<title>Alkansya</title>
@endsection

@section('content')
    @if (is_null($setup))
        <h3 class="header mt-3">My Calendar</h3>
        <div class="row">
            <div class="col-md-8 my-3">
                <div class="card">
                    <div class="card-header">Calendar</div>
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
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js"></script>
        {!! $calendar_details->script() !!}
    @else
        <h3 class="header mt-3">Please setup your Account first</h3>
        <div class="row">
            <div class="col-sm-10 col-md-7 col-lg-5 my-3">
                <form action="{{ route('member-setup') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="payment_method">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="custom-select" required>
                            <option value="" selected hidden>Choose payment method</option>
                            <option value="1">Daily</option>
                            <option value="2">Weekly</option>
                            <option value="3">Monthly</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" class="form-control" min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="terms" required>
                            <label class="custom-control-label" for="terms">I Agree to the terms and conditions</label>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
            </div>
        </div>
    @endif
@endsection
