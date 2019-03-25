@extends('layouts.app')

@section('title')
<title>Alkansya - Request Loan</title>
@endsection

@section('content')
@if(!$unpaid)
    <h3 class="header mt-2">Request Loan</h3>
    <div class="row">
        <div class="col-sm-10 col-md-7 col-lg-5 my-3">  
            {!! Form::open(['action' => 'LoanRequestsController@store', 'method' => 'POST']) !!}
            <div class="form-group">
                {{ Form::label('amount', 'Loan Amount', ['class' => 'h6']) }}
                    {{ Form::number('amount', '', ['class' => 'form-control', 'placeholder' => 'Enter amount (e.g. 1000.50)', 'step' => '0.01', 'required']) }}
            </div>
            {{ Form::label('', 'Months Payable', ['class' => 'h6']) }}
                <div class="form-group">
                {{ Form::number('months', '', ['class' => 'form-control', 'required']) }}
                </div>
            <div class="form-group">
                <div class="form-check">
                    {!! Form::checkbox('agreement', 'yes', false, ['class' => 'form-check-input', 'id' => 'agreement', 'required']) !!}
                    {{ Form::label('agreement', 'I agree with the ') }} 
                    {!! "<a href='/terms' target='_blank'>Terms and Conditions</a>" !!}
                </div>
            </div>
            {{ Form::submit('Request', ['class' => 'btn btn-primary']) }}
            {{-- <button class="btn btn-primary" role="button" data-toggle="modal" data-target="#termsModal">Continue</button> --}}
            {!! Form::close() !!}
        </div>
        <div class="col-sm-10 col-md-7 col-lg-5 my-3">  
               Note: <br>
               Php 50 below pay for 1 week <br>
               Php 200 below pay for 1 month <br>
               Php 201 above depend on the system computation <br>

               Update: <br>
               Mangutana ta ni miss pila ka months/weeks ang duration sa ila loan.
            </div>
    </div>
@else
    {{-- <h3 class="header mt-3 text-center">Request Loan Not Available</h3>
    <h3 class="header mt-3 text-center">Please Settle your loan</h3> --}}
    <div class="failed-loan d-flex justify-content-center align-items-center">
        <h6 class="display-5 header text-center">Request Loan Not Available. <br> Please settle your unfinished loan first.</h6>
        {{-- <h6 class="display-5 header text-center"></h6> --}}
        {{-- <img src="{{ asset('img/img.png') }}" alt="Failed-loan" min-width="50px" height="70px"> --}}
    </div>
@endif

{{-- <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reqModalLabel">User Terms and Condition Agreement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <h1 class="display-4">Terms and Conditions</h1>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> --}}

@endsection
