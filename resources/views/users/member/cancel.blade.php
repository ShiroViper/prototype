@extends('layouts.app')

@section('title')
<title>Alkansya - Status </title>
@endsection

@section('content')
<div class="row">
    @if ($account_status)
        <div class="col-6">
            @if($account_status->confirmed == NULL)
            <h2>Requires Confirmation from the administration</h2>
            <p>If you dont request this cancellation of account click <a href="/member/cancel/destroy"> Revoke Cancellation Request </a> . </p>
            @elseif($account_status->confirmed == 1)
                <h2>Account has been canceled by the administration</h2>
            @endif
        </div>
    @else
        <div class="row">
            <div class="col-sm col-md-10 col-xl-8">
                <h2>Cancel Account</h2>
                
            {!!Form::open(['action'=> ['MemberController@update'], 'method'=>'POST']) !!}
            @csrf
            {{Form::hidden('token', $token)}}
            
            <div class="form-group">
                {{ Form::label('reason', 'Reason', ['class' => 'h6']) }}
                {{ Form::textarea('reason', '', ['class' => $errors->has('reason') ? 'form-control is-invalid' : 'form-control', 'rows' => 2, 'required']) }}
                @if ($errors->has('reason'))
                    <div class="invalid-feedback">{{ $errors->first('reason') }}</div>
                @endif
            </div>
        
            <div class="form-group">
                <label for="password" class="h6"> Password</label>
                <input id="password" type="password" name="pass" class="form-control" required>
            </div>
        
            {{ Form::submit('Deactivate', ['class' => 'btn btn-primary autocomplete-btn']) }}
        
            {!!Form::close()!!}
            </div>
        </div>
    @endif
    <div class="col-6">
        -Note for cancellation of account: <br>
        -Once your account is being cancel or revoke your membership to the cooperative, you are no longer be a member again. <br>
        -Patronage refund can be claim if the savings is beyond 1825 pesos. <br>
        -Savings can be claim without deduction. <br>
        -Cancellation of account is available when their are no current loan. <br>
        -This condition are subject to change during cancellation of account (if mo cancel sila ang member og admin mag talk if hatagan ba niyag right na i cancel iya account sa member or dili). <br>
    </div>
</div>
@endsection