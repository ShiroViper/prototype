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
            {{ Form::label('', 'Days Payable', ['class' => 'h6']) }}
                <div class="form-group">
                {{ Form::number('days', '', ['class' => 'form-control', 'required']) }}
                </div>
            <div class="form-group">
                <div class="form-check">
                    {!! Form::checkbox('agreement', 'yes', false, ['class' => 'form-check-input', 'id' => 'agreement', 'required']) !!}
                    {{ Form::label('agreement', 'I agree with the ') }} 
                    {!! "<a href='/terms' target='_blank'>Terms and Conditions</a>" !!}
                </div>
            </div>
            {{ Form::submit('Request', ['class' => 'btn btn-primary']) }}
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
    <h3 class="header mt-3 text-center">Request Loan Not Available</h3>
    <h3 class="header mt-3 text-center">Please Settle your loan</h3>
@endif
@endsection
