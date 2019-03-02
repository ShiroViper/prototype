@extends('layouts.app')

@section('title')
<title>Alkansya</title>
@endsection

@section('content')
<h3 class="header mt-3">My Calendar</h3>
<div class="row">
    <div class="col-sm my-3">
        <div id="calendar"></div>
    </div>
    <div class="col-sm">
        <div class="events mb-3">
            <h6>Upcoming Events</h6>
            <div class="justify-content-center">
                <div class="card">
                    <div class="card-header">Dashboard</div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <h3>Member</h3>
                        You are logged in!
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm">
        <div class="legend">
            <h5>Legend:</h5>
            <div class="clearfix">
                <div class="d-flex flex-row align-items-end p-1">
                    <div class="square current-date"></div>
                    <h6>Current Date</h6>
                </div>
                <div class="d-flex flex-row align-items-end p-1">
                    <div class="square pay-dates"></div>
                    <h6>Pay Dates</h6>
                </div>
                <div class="d-flex flex-row align-items-end p-1">
                    <div class="square paid-dates"></div>
                    <h6>Paid Dates</h6>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h3>Member</h3>
                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
