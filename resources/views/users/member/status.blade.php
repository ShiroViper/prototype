@extends('layouts.app')

@section('title')
<title>Alkansya - Status </title>
@endsection

@section('content')
<div class="row">
    <div class="col-6">
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <p>Patronage Refund</p>
                    @if($patronage->patronage_refund)
                        {{$patronage->patronage_refund}}
                    @else
                        No patronage refund
                    @endif
                </div>
                <div class="col-3">
                    <p>My Loan(balance)</p>
                    @if($loan)
                        {{$loan->balance}}
                    @else
                        No current Loan
                    @endif
                </div>
                <div class="col-3">
                    <p>Savings</p>
                    @if($savings->savings)
                        {{$savings->savings}}
                    @else
                        No current savings
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        Note: patronage refund can be claim in the end of december or the distribution of savings if your savings is beyond 1825 pesos. <br>
        patronage refund available if their is atleast one loan. <br>
    </div>
</div>
@endsection