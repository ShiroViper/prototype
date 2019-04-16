@extends('layouts.app')

@section('title')
<title>Alkansya</title>
@endsection

@section('content')
    @push('scripts')
        <script src="{{ asset('js/scripts.js') }}"></script>
    @endpush
    @if (is_null($user))
        <p class="header display-5 mt-3">My Account</p>
        {{-- <div class="row">
            <div class="col-sm p-2">
                <div class="card shadow-sm" style="max-width: 18rem">
                    <div class="card-body d-flex justify-content-center align-items-center flex-column">
                        <h1 class="display-4">5</h1>
                        <h5 class="card-title">Savings</h5>
                    </div>
                </div>
            </div>
            <div class="col-sm p-2">
                <div class="card shadow-sm" style="max-width: 18rem">
                    <div class="card-body d-flex justify-content-center align-items-center flex-column">
                        <h1 class="display-4">5</h1>
                        <h5 class="card-title">Patronage Refund</h5>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="card-deck pb-5">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-center align-items-center flex-column">
                    <div class="border rounded-circle p-2 bg-light"><i class="text-primary fas fa-piggy-bank fa-lg"></i></div>
                    <h5 class="pt-2 header display-5 font-weight-bold text-center">
                        {{ $savings && $savings->savings != null ? '₱ '.$savings->savings : '₱ 0.00' }}
                    </h5>
                    <small>Savings</small>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-center align-items-center flex-column">
                    <div class="border rounded-circle p-2 bg-light"><i class="text-success fas fa-hand-holding-usd fa-lg"></i></div>
                    <h5 class="pt-2 header display-5 font-weight-bold text-center">
                        {{ $patronage && $patronage->patronage_refund != null ? '₱ '.round($patronage->patronage_refund).'.00' : '₱ 0.00' }}
                    </h5>
                    <small>Patronage Refund</small>
                </div>
            </div>
            @if($loan)
                <div class="card shadow-sm" data-toggle="modal" data-target="#statModal" >
            @else
                <div class="card shadow-sm">
            @endif
                <div class="card-body d-flex justify-content-center align-items-center flex-column">
                    <div class="border rounded-circle p-2 bg-light"><i class="text-warning fas fa-coins fa-lg"></i></div>
                    <h5 class="pt-2 header display-5 font-weight-bold text-center">
                        {{ $loan ? ($loan->per_month_amount <= 0 ? '₱'.abs($loan->per_month_amount) : '₱'.$loan->per_month_amount ) : '₱ 0.00' }}
                    </h5>
                    {{-- <small >Current Loan Balance For This Month</small> --}}
                    <small> {{$loan ? ($loan->per_month_amount <=0 ? 'Over Paid' : 'Current Loan Balance ' ) : 'Current Loan Balance'}} </small>
                    <a class="text-primary clickable"><small>View Details</small></a>
                    {{-- <small> From {{$loan ? (  $loan->per_month_from ? date('F d, Y', $loan->per_month_from) : '' ) : ''}} <br> To {{$loan ? (  $loan->per_month_to ? date('F d, Y', $loan->per_month_to) : '' ) : ''}}  --}}
                    </small>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md col-lg-7 my-3">
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
            <div class="col-lg my-3">
                <div class="row">
                    <div class="col-12 col-lg">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="legend">
                                    <h5 class="header">Legend:</h5>
                                    <div class="clearfix">
                                        <div class="d-flex flex-row align-items-center p-1">
                                            <div class="square calendar-current-date"></div>
                                            <span class="header ml-1">Current Date</span>
                                        </div>
                                        <div class="d-flex flex-row align-items-center p-1">
                                            <div class="square calendar-paid-dates"></div>
                                            <span class="header ml-1">Paid Dates</span>
                                        </div>
                                        {{-- <div class="d-flex flex-row align-items-center p-1">
                                            <div class="square pay-dates"></div>
                                            <span class="header ml-1">Pay Dates</span>
                                        </div> --}}
                                        <div class="d-flex flex-row align-items-center p-1">
                                            <div class="square calendar-loan-dates"></div>
                                            <span class="header ml-1">Loan Dates</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card shadow info-card mt-3">
                            <div class="card-header info-title">Event Details</div>
                            <div class="card-body">
                                <small class="info-sub-title">Click click on an event to view more details</small>
                                <div class="row">
                                    <div class="col member-calendar-info">
                                        <div class="row">
                                            <div class="col text-center">
                                                <div class="info-details"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
<!-- Modal -->
<div class="modal fade" id="statModal" tabindex="-1" role="dialog" aria-labelledby="statModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statModalLabel">Loan Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col col-md col-lg-4">
                                    <span>Months Payable</span>
                                </div>
                                <div class="col col-md col-lg">
                                    <h6>{{$loan ? ($loan->days_payable ? $loan->days_payable. ' Months' : 'No Current Loan') : 'No Current Loan' }} </h6>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col col-md col-lg-4">
                                    <span>Amount Loaned</span>
                                </div>
                                <div class="col col-md col-lg">
                                    <h6>{{$loan ? ($loan->loan_amount ? '₱ '.number_format($loan->loan_amount, 2) : 'No Current Loan') : 'No Current Loan' }} </h6>
                                </div>
                            </div>
                        </li>  
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col col-md col-lg-4">
                                    <span>Monthly Payment</span>
                                </div>
                                <div class="col col-md col-lg">
                                    {{-- This trick the member's loan balance stop from paying --}}
                                    {{-- <h6>{{$loan ? ($loan->loan_amount ? ($loan->loan_amount * 0.06 * $loan->days_payable + $loan->loan_amount) / $loan->payable ) : '' }} </h6> --}}
                                    @php
                                        if($loan){
                                            if($loan->loan_amount){
                                                $per_month_amount =  ($loan->loan_amount * 0.06 * $loan->days_payable + $loan->loan_amount) / $loan->days_payable ;
                                                echo '<h6>₱ '.number_format($per_month_amount, 2). '</h6>';
                                            }
                                        }
                                    @endphp
                                </div>
                            </div>
                        </li>  
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col col-md col-lg-4">
                                    <span>Total Loan Balance</span>
                                </div>
                                <div class="col col-md col-lg">
                                    {{-- This trick the member's loan balance stop from paying --}}
                                    <h6>{{$loan ? ($loan->loan_amount >= 0 ? '₱ '.($loan->balance) : '₱ '.($loan->balance - $loan->per_month_amount).'.00' ) : 'No Current Loan' }} </h6>
                                </div>
                            </div>
                        </li>                      
                    </ul>
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
                            <label class="custom-control-label" for="terms">I Agree to the <a href='/terms' target='_blank'>Terms and Conditions</a></label>
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
