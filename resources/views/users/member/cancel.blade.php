@extends('layouts.app')

@section('title')
<title>Alkansya - Status </title>
@endsection

@section('content')

@push('scripts')
    <script src="{{ asset('js/scripts.js') }}"></script>
@endpush

@if ($account_status)
    <div class="col-6">
        @if($account_status->confirmed == NULL)
        <h2 class="display-5 header">Requires Confirmation from the administration</h2>
        <p>If you dont request this cancellation of account click <a href="/member/cancel/destroy"> Revoke Cancellation Request </a> . </p>
        <div class="row">
            <div class="col">
                <div class="py-3">
                    <a class="btn btn-light border" role="button" href="/member/profile"><i class="fas fa-arrow-left"></i>  Back </a>
                </div>
            </div>
        </div>
        @elseif($account_status->confirmed == 1)
            <h2>Account has been canceled by the administration</h2>
            <div class="row">
            </div>
        @endif
    </div>
@else
<div class="jumbotron jumbotron-fluid border rounded">
    <div class="container">
            <h2 class="display-4 header">Deactivate Account</h2>
            {{-- <h5 class="font-weight-bold header h5 pt-2">NOTE:</h5>  --}}
            <div class="lead">
                <p>Once your account is approved for cancellation, you can become a member once again after the current year ends.</p>
                <p><b class="h5">Patronage refund</b> can be claimed if your <b class="h5">savings</b> has reached the minimun ₱1825.</p>
                <p><b class="h5">Savings</b> can be claim without deduction.</p>
                <p><b class="h5">Owner's Disribution</b> cannot be claim.</p>
                <p>Cancellation of account is available when their are no current loan.</p>
                <p>This request is subject to change</p>
            </div>           
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="py-3">
            <a class="btn btn-light border" role="button" href="/member/profile"><i class="fas fa-arrow-left"></i>  Back </a>
        </div>
    </div>
</div>
@if(!$paid_null)
    <div class="row">
        <div class="col-sm col-md-10 col-xl-8">
            <h5 class="h5 pb-3">Request for Deactivation of Account</h5>
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
                @if ($errors->has('pass'))
                    <div class="invalid-feedback">{{ $errors->first('pass') }}</div>
                @endif
            </div>
        
            {{ Form::submit('Submit Request', ['class' => 'btn btn-primary autocomplete-btn']) }}
        
            {!!Form::close()!!}
        </div>
    </div>
@else    
    <h1> Please Settle Your Unfinished Loan First </h1>
@endif
@endif
{{-- <div class="col-6">
    -Note for cancellation of account: <br>
    -Once your account is being cancel or revoke your membership to the cooperative, you are no longer be a member again. <br>
    -Patronage refund can be claim if the savings is beyond 1825 pesos. <br>
    -Savings can be claim without deduction. <br>
    -Cancellation of account is available when their are no current loan. <br>
    -This condition are subject to change during cancellation of account (if mo cancel sila ang member og admin mag talk if hatagan ba niyag right na i cancel iya account sa member or dili). <br>
</div> --}}

@endsection