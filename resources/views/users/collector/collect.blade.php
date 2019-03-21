@extends('layouts.app')

@section('title')
<title>Alkansya</title>
@endsection

@section('content')

<h3 class="header mt-2">Collect Payment</h3>
<div class="row">
    <div class="col-sm-10 col-md-7 col-lg-5 my-3">  
      {!!Form::open(['action'=> 'TransactionController@store', 'method'=>'POST']) !!}
      @csrf
    
      <div class="form-group">
        {{ Form::label('date', 'Date', ['class' => 'h6']) }}
        {{ Form::date('date',\Carbon\Carbon::now(), ['class' => 'form-control', 'readonly']) }}
    </div>
    <div class="form-group">
        {{ Form::label('type', 'Type', ['class' => 'h6']) }}
        {{ Form::select('type', [1=>'Deposit', 3=>'Loan Payment'], 3, ['class' => 'form-control']) }}
    </div>
    <div class="form-group">
        {{ Form::label('amount', 'Amount Received', ['class' => 'h6']) }}
        {{ Form::number('amount', '', ['class' => $errors->has('amount') ? 'form-control is-invalid' : 'form-control', 'step' => '0.01']) }}
         @if ($errors->has('amount'))
            <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
        @endif
    </div>
    <div class="form-group">
        {{ Form::label('id', 'Member ID', ['class' => 'h6']) }}
        {{ Form::number('id', '', ['class' => $errors->has('id') ? 'form-control is-invalid' : 'form-control']) }}
         @if ($errors->has('id'))
            <div class="invalid-feedback">{{ $errors->first('id') }}</div>
        @endif
    </div>
    {{-- <div class="form-group">
        {{ Form::label('member', 'Member Name') }}
        {{ Form::text('member',, ['class' => 'form-control', 'readonly']) }}
    </div> --}}

    {{ Form::submit('Pay', ['class' => 'btn btn-primary', 'target'=>'_blank']) }}

      {!!Form::close()!!}
    </div>
</div>
@endsection
