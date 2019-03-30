@extends('layouts.app')

@section('title')
<title>Alkansya</title>
@endsection

@section('content')
{{-- <h3 class="header mt-3">Schedules</h3> --}}
<div class="row mt-2">
    <div class="col-lg-7 mt-3">
        <div class="card shadow position-sticky fixed-top">
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
    <div class="col-lg my-3">
        <div class="row">
            <div class="col-12 col-lg pb-3">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="legend">
                            <h5 class="header">Legend:</h5>
                            <div class="row">
                                <div class="col">
                                    <div class="d-flex flex-row">
                                        <div class="square admin-calendar-current-date"></div>
                                        <span class="header ml-1">Current Date</span>
                                    </div>
                                    <div class="d-flex flex-row">
                                        <div class="square admin-calendar-paid-dates"></div>
                                        <span class="header ml-1">Paid Dates</span>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="d-flex flex-row">
                                        <div class="square admin-calendar-loan-dates"></div>
                                        <span class="header ml-1">Loan Dates</span>
                                    </div>
                                    <div class="d-flex flex-row">
                                        <div class="square admin-calendar-highlighted"></div>
                                        <span class="header ml-1">Dates highlighted</span>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="clearfix">
                                <div class="d-flex flex-row align-items-center p-1">
                                    <div class="square admin-calendar-current-date"></div>
                                    <span class="header ml-1">Current Date</span>
                                </div>
                                <div class="d-flex flex-row align-items-center p-1">
                                    <div class="square admin-calendar-paid-dates"></div>
                                    <span class="header ml-1">Paid Dates</span>
                                </div>
                                <div class="d-flex flex-row align-items-center p-1">
                                    <div class="square admin-calendar-loan-dates"></div>
                                    <span class="header ml-1">Loan Dates</span>
                                </div>
                                <div class="d-flex flex-row align-items-center p-1">
                                    <div class="square admin-calendar-highlighted"></div>
                                    <span class="header ml-1">Dates highlighted</span>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 pb-3">
                <div class="card shadow info-card">
                    <div class="card-header info-title">Event Details</div>
                    <div class="card-body">
                        <small class="info-sub-title">Click click on an event to view more details</small>
                        <div class="row">
                            <div class="col admin-calendar-info">
                                <div class="row">
                                    <div class="col text-center">
                                        <div class="info-paid"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col col-sm col-lg">
                                        <div class="info-name"></div>
                                    </div>
                                    <div class="col col-sm col-lg">
                                        <div class="info-email"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col col-sm col-lg">
                                        <div class="info-address"></div>
                                    </div>
                                    <div class="col col-sm col-lg">
                                        <div class="info-cell"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col col-sm col-lg">
                                        <div class="info-dp"></div>
                                    </div>
                                    <div class="col col-sm col-lg">
                                        <div class="info-amt"></div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col col-sm col-lg">
                                        <div class="info-status"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-12">
                <div class="card shadow duty-card">
                    <div class="card-header duty-title">Available collectors</div>
                    <div class="card-body">
                        <small class="duty-sub-title">Click and drag dates to view available collectors</small>
                        <form action="{{ action('DaysOffController@available') }}" class="" method="POST">
                            @csrf
                            <div class="row admin-calendar-duty form-row mt-2">
                                <div class="col col-sm col-lg form-group">
                                    <label for="av_start">Start Date</label>
                                    <input type="date" name="av_start" id="" class="form-control" required>
                                </div>
                                <div class="col col-sm col-lg form-group">
                                    <label for="av_end">End Date</label>
                                    <input type="date" name="av_end" id="" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block mb-2">View Available Collectors</button>
                        </form>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>

<script>
    var tmp = @json($collectors);
    var collectors = [];
    for (var i = 0; i < tmp.length; i++) {
        item = {}
        item["value"] = tmp[i].id;
        item["label"] = tmp[i].lname+", "+tmp[i].fname+" "+tmp[i].mname;
        collectors.push(item);
    }
    console.log(collectors);
</script>

@prepend('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js"></script>
    
@endprepend 

{!! $calendar_details->script() !!}

@push('scripts')
    <script src="{{ asset('js/scripts.js') }}"></script>
@endpush

@endsection
