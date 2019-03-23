@extends('layouts.app')

@section('title')
<title>Alkansya</title>
@endsection

@section('content')
    @push('scripts')
        <script src="{{ asset('js/scripts.js') }}"></script>
    @endpush
    @if (is_null($user))
        <div class="row mt-2">
            <div class="col-md-8 my-3">
                <div class="card shadow">
                    <!-- <div class="card-header">Calendar</div> -->
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
        @prepend('scripts')
            <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js"></script>
        @endprepend 
        {!! $calendar_details->script() !!}
    @else
        <h3 class="header mt-3 display-5">Please setup your account first</h3>
        {!! Form::open(['action' => 'UsersController@setup', 'method' => 'POST']) !!}
            @csrf
            <h5 class="pt-3">Set your new password</h5>
            <div class="row">
                <div class="col-sm col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="password" class="h6">Password</label>
                        <input type="password" name="password" id="password" class="form-control" onChange="passwordChecker();" required>
                    </div>
                </div>
            </div>
            <div class="row pb-2">
                <div class="col-sm col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="cpassword" class="h6">Confirm Password</label>
                        <input type="password" name="cpassword" id="cpassword" class="form-control" onChange="passwordChecker();" required>
                    </div>
                    <div id="password-checker"></div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg col-xl-10 my-3">
                    {{-- <form action="{{ route('member-setup') }}" method="POST"> --}}
                    <div class="row">
                        <div class="col-lg">
                            <div class="form-group">
                                {{ Form::label('lname', 'Last Name', ['class' => 'h6']) }}
                                {{ Form::text('lname', $user->lname, ['class' => $errors->has('lname') ? 'form-control is-invalid' : 'form-control']) }}
                                @if ($errors->has('lname'))
                                    <div class="invalid-feedback">{{ $errors->first('lname') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg">
                            <div class="form-group">
                                {{ Form::label('fname', 'First Name', ['class' => 'h6']) }}
                                {{ Form::text('fname', $user->fname, ['class' => $errors->has('fname') ? 'form-control is-invalid' : 'form-control']) }}
                                @if ($errors->has('fname'))
                                    <div class="invalid-feedback">{{ $errors->first('fname') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg">
                            <div class="form-group">
                                {{ Form::label('mname', 'Middle Name', ['class' => 'h6']) }}
                                {{ Form::text('mname', $user->mname, ['class' => $errors->has('mname') ? 'form-control is-invalid' : 'form-control']) }}
                                @if ($errors->has('mname'))
                                    <div class="invalid-feedback">{{ $errors->first('mname') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                {{ Form::label('email', 'Email', ['class' => 'h6']) }}
                                {{ Form::email('email', $user->email, ['class' => $errors->has('email') ? 'form-control is-invalid' : 'form-control']) }}
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                {{ Form::label('cell_num', 'Cellphone Number', ['class' => 'h6']) }}
                                {{ Form::text('cell_num', $user->cell_num, ['class' => $errors->has('cell_num') ? 'form-control is-invalid' : 'form-control']) }}
                                @if ($errors->has('cell_num'))
                                    <div class="invalid-feedback">{{ $errors->first('cell_num') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                {{ Form::label('address', 'Complete Address', ['class' => 'h6 mb-0']) }}
                                <div class="mb-2"><small class="text-muted">Street number, Barangay, City/Town, Province, Philippines, Zip Code</small></div>
                                    {{ Form::textarea('address', $user->address, ['class' => $errors->has('address') ? 'form-control is-invalid' : 'form-control', 'rows' => 2]) }}
                                    @if ($errors->has('address'))
                                        <div class="invalid-feedback">{{ $errors->first('address') }}</div>
                                    @endif
                                </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="terms" required>
                            <label class="custom-control-label" for="terms">I Agree to the terms and conditions</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-lg-center">
                        {{ Form::submit("Looks Good!", ['class' => 'btn btn-primary align-content-center px-3 member-setup-btn', 'disabled']) }}
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
        {{-- <hr>
        <div class="row">
            <div class="col">
                asdasd
            </div>
        </div> --}}
    @endif
@endsection
