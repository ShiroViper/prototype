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
    
      
    @if($process != NULL)
        @if($process->transfer == 3)
            <div class="form-group">
                {{ Form::label('date', 'Date Sent') }}
                {{ Form::date('date',$process->created_at, ['class' => 'form-control', 'readonly']) }}
            </div>
            <div class="alert alert-success  text-center " role="alert">
                <strong>Transferring Money</strong>
            </div>
        @elseif($process->transfer >= 4 )
            <div class="form-group">
                {{ Form::label('date', 'Date Received') }}
                {{ Form::date('date',$process->created_at, ['class' => 'form-control', 'readonly']) }}
            </div>
            <div class="alert alert-success  text-center " role="alert">
                <strong>Money Transferred to Member </strong>
            </div>
        @endif 
    @endif
    @if($process->transfer == 2)
        <div class="form-group">
            {{ Form::label('date', 'Date') }}
            {{ Form::date('date',\Carbon\Carbon::now(), ['class' => 'form-control', 'readonly']) }}
        </div>
        {{-- <div class="form-group">
            {{ Form::label('type', 'Type') }}
            {{ Form::select('type', [0=>'Widthdraw', 1=>'Loan Transfer'], 1, ['class' => 'form-control']) }}
        </div> --}}
        {{ Form::number('transfer', $process->transfer, ['hidden']) }}
        {{ Form::number('type', 1, ['hidden']) }}

        {{ Form::submit('Transfer', ['class' => 'btn btn-primary']) }}
    @endif
    
    <br><hr>
    <div class="form-group">
        {{ Form::label('id', 'Request ID') }}
        {{ Form::number('id', $request->id, ['class' => $errors->has('amount') ? 'form-control is-invalid' : 'form-control', 'readonly']) }}
      
    </div>
    <div class="form-group">
        {{ Form::label('amount', 'Amount Loaned') }}
        {{ Form::number('amount', $request->loan_amount, ['class' => $errors->has('amount') ? 'form-control is-invalid' : 'form-control', 'readonly']) }}
      
    </div>
    <div class="form-group">
        {{ Form::label('days', 'Days Payable') }}
        {{ Form::number('days', $request->days_payable, ['class' => $errors->has('days') ? 'form-control is-invalid' : 'form-control', 'readonly']) }}
        
    </div>
    <div class="form-group">
        {{ Form::label('m_id', 'Member ID') }}
        {{ Form::number('m_id', $request->user_id, ['class' => $errors->has('m_id') ? 'form-control is-invalid' : 'form-control', 'readonly']) }}
       
    </div>
    {{-- <div class="form-group">
        {{ Form::label('member', 'Member Name') }}
        {{ Form::text('member',, ['class' => 'form-control', 'readonly']) }}
    </div> --}}


      {!!Form::close()!!}
    </div>
</div>
@endsection
