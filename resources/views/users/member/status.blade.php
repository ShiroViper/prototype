@extends('layouts.app')

@section('title')
<title>Alkansya - Status </title>
@endsection

@section('content')
<div class="row">
    <div class="col-sm col-md-10 col-xl-8">
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <p>Patronage Refund</p>
                    @if($patronage)
                        {{$patronage->patronage_refund}}
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
                    @if($savings)
                        {{$savings->savings}}
                    @endif
                </div>
                <div class="col-3">
                    <p>Shares</p>
                    @if($savings)
                        {{$savings->savings / 5}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection