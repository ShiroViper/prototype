@extends('layouts.app')

@section('title')
<title>Alkansya</title>
@endsection

@section('content')

<h3>Collect Payment</h3>
<div class="row">
    <div class="col-sm-10 col-md-7 col-lg-5 my-3">  
      {!!Form::open(['action'=> 'TransactionController@store', 'method'=>'POST']) !!}
      @csrf
    
      <div class="form-group">
        {{ Form::label('date', 'Date') }}
        {{ Form::date('date',\Carbon\Carbon::now(), ['class' => 'form-control', 'readonly']) }}
    </div>
    <div class="form-group">
        {{ Form::label('type', 'Type') }}
        {{ Form::select('type', [0=>'Loan Widthdraw', 1=>'Loan Payment', 2=>'Deposit'], 1, ['class' => 'form-control']) }}
    </div>
    <div class="form-group">
        {{ Form::label('amount', 'Amount Received') }}
        {{ Form::number('amount', '', ['class' => $errors->has('amount') ? 'form-control is-invalid' : 'form-control']) }}
         @if ($errors->has('amount'))
            <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
        @endif
    </div>
    <div class="form-group">
        {{ Form::label('id', 'Member ID') }}
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
