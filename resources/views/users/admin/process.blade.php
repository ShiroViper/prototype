@extends('layouts.app')

@section('title')
<title>Loan Process</title>
@endsection

@section('content')

<h3>Loan Process: Transfer Money</h3>
<div class="row">
    <div class="col-sm-10 col-md-7 col-lg-5 my-3">  
      {!!Form::open(['action'=> 'LoanProcessController@store', 'method'=>'POST']) !!}
      @csrf
    {{-- Request ID --}}
    {{ Form::text('id', $process->id, ['hidden']) }}
      
    @if($trans != NULL)
        @if($trans->transfer == 1)
            <div class="form-group">
                <label>Date Sent</label>      
                <div class="col font-weight-bold">{{ $trans->updated_at }}</div>       
            </div>
            <div class="alert alert-success  text-center " role="alert">
                <strong>Transferring Money</strong>
            </div>
        @elseif($trans->transfer >= 2)
            <div class="form-group">
                <label>Date Transferred</label>      
                <div class="col font-weight-bold">{{ $trans->updated_at }}</div>       
            </div>
            <div class="alert alert-success  text-center " role="alert">
                @if($trans->transfer == 2)
                    <strong>Money Transferred to Collector </strong>
                @elseif($trans->transfer == 4)
                    <strong>Money Transferred to Member </strong>
                @endif
            </div>
        @endif 
    @endif
    @if($trans == NULL)
        <div class="form-group">
            {{ Form::label('date', 'Date') }}
            {{ Form::date('date',\Carbon\Carbon::now(), ['class' => 'form-control', 'readonly']) }}
        </div>
        <div class="form-group">
            {{ Form::label('name', 'Collector Name') }}
            {{ Form::text('name', '', ['class' => $errors->has('name') ? 'form-control is-invalid' : 'form-control']) }}
            @if ($errors->has('name'))
                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
            @endif
        </div>
        {{ Form::submit('Transfer to Collector', ['class' => 'btn btn-primary']) }}
    @endif
    
    <br><hr>
    <div class="form-group">
        <label>Member Name</label>      
        <div class="col font-weight-bold">{{ $user->lname }}, {{$user->fname}} {{$user->mname}} </div>       
    </div>
    <div class="form-group">
        <label>Amount to Loan</label>      
        <div class="col font-weight-bold">{{ $process->loan_amount }}</div>       
    </div>
    <div class="form-group">
        <label>Days Payable</label>      
        <div class="col font-weight-bold">{{ $process->days_payable }}</div>       
    </div>
    {{-- <div class="form-group">
        {{ Form::label('member', 'Member Name') }}
        {{ Form::text('member',, ['class' => 'form-control', 'readonly']) }}
    </div> --}}


      {!!Form::close()!!}
    </div>
</div>
@endsection
