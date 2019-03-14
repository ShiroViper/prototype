@extends('layouts.app')

@section('title')
<title>Loan Process</title>
@endsection

@section('content')

<h3>Loan Process</h3>
<div class="row">
    <div class="col-sm-10 col-md-7 col-lg-5 my-3">  
      {!!Form::open(['action'=> 'LoanProcessController@store', 'method'=>'POST']) !!}
      @csrf
    
      <div class="form-group">
        {{ Form::label('date', 'Date') }}
        {{ Form::date('date',\Carbon\Carbon::now(), ['class' => 'form-control', 'readonly']) }}
    </div>
    <div class="form-group">
        {{ Form::label('type', 'Type') }}
        {{ Form::select('type', [0=>'Widthdraw', 1=>'Loan Transfer'], 1, ['class' => 'form-control']) }}
    </div>
    <div class="form-group">
        {{ Form::label('amount', 'Amount Loaned') }}
        {{ Form::number('amount', '', ['class' => $errors->has('amount') ? 'form-control is-invalid' : 'form-control']) }}
         @if ($errors->has('amount'))
            <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
        @endif
    </div>
    <div class="form-group">
        {{ Form::label('m_id', 'Member ID') }}
        {{ Form::number('m_id', '', ['class' => $errors->has('m_id') ? 'form-control is-invalid' : 'form-control']) }}
         @if ($errors->has('m_id'))
            <div class="invalid-feedback">{{ $errors->first('m_id') }}</div>
        @endif
    </div>
    <div class="form-group">
        {{ Form::label('c_id', 'Collector ID') }}
        {{ Form::number('c_id', '', ['class' => $errors->has('c_id') ? 'form-control is-invalid' : 'form-control']) }}
         @if ($errors->has('c_id'))
            <div class="invalid-feedback">{{ $errors->first('c_id') }}</div>
        @endif
    </div>
    {{-- <div class="form-group">
        {{ Form::label('member', 'Member Name') }}
        {{ Form::text('member',, ['class' => 'form-control', 'readonly']) }}
    </div> --}}

    {{ Form::submit('Pay', ['class' => 'btn btn-primary']) }}

      {!!Form::close()!!}
    </div>
</div>
@endsection
