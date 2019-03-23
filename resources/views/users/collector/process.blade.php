@extends('layouts.app')

@section('title')
<title>Loan Process</title>
@endsection

@section('content')

<h3>Loan Process: Transfer Money</h3>
<div class="row">
    <div class="col-sm-10 col-md-7 col-lg-5 my-3">  
      {!!Form::open(['action'=> 'ProcessController@store', 'method'=>'POST']) !!}
      @csrf

    @if($process->transfer == 3)
        <div class="form-group">
            <div class="form-group">
                <label>Date Sent</label>      
                <div class="col font-weight-bold">{{ $process->updated_at }}</div>       
            </div>
            <div class="alert alert-success  text-center " role="alert">
                <strong>Transferring Money</strong>
            </div>
    @elseif($process->transfer >= 4 )
        <div class="form-group">
            <label>Date Transferred</label>      
            <div class="col font-weight-bold">{{ $process->updated_at }}</div>       
        </div>
        <div class="alert alert-success  text-center " role="alert">
            <strong>Money Transferred to Member </strong>
        </div>
    @elseif($process->transfer == 2)
        <div class="form-group">
            {{ Form::label('date', 'Date') }}
            {{ Form::date('date',\Carbon\Carbon::now(), ['class' => 'form-control', 'readonly']) }}
        </div>
        {{ Form::number('transfer', $process->transfer, ['hidden']) }}
        {{ Form::number('id', $process->request_id, ['hidden']) }}
        {{ Form::submit('Transfer to '.$user->lname. ', '.$user->fname, ['class' => 'btn btn-primary']) }}
    @endif
    
    <br><hr>
    <div class="form-group">
        <label>Member Name</label>      
        <div class="col font-weight-bold">{{ $user->lname }}, {{$user->fname}} {{$user->mname}} </div>       
    </div>
    <div class="form-group">
        <label>Loan To Amount</label>      
        <div class="col font-weight-bold">{{ $request->loan_amount }}</div>       
    </div>
    <div class="form-group">
        <label>Days Payable</label>      
        <div class="col font-weight-bold">{{ $request->days_payable }}</div>       
    </div>
      {!!Form::close()!!}
    </div>
</div>
@endsection
