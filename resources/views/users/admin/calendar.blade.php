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
        <div class="row">
            <div class="col-12 pb-3 admin-calendar-options">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="header">Options:</h5>
                        <div class="row">
                            <div class="col"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 pb-3 admin-calendar-assign">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="header">Assigned to</h5>
                        <div class="row">
                            <div class="col"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 admin-calendar-info">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="header">Show the info</h5>
                        <div class="row">
                            <div class="col"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@prepend('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js"></script>
@endprepend 
@push('scripts')
    <script src="{{ asset('js/scripts.js') }}" defer></script>
@endpush
    {!! $calendar_details->script() !!}
@endsection
