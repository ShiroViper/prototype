@extends('layouts.app')

@section('title')
<title>Alkansya</title>
@endsection

@section('content')
<h3 class="header">Transactions</h3>
<div class="table-responsive pt-3">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Account</th>
                <th>Date & Time</th>
                <th>Type</th>
                <th>Collector</th>
                <th>Amount</th>
                <!-- <th>Balance</th> -->
            </tr>
        </thead>
        <tbody>
        @if (count($transactions) > 0)
            @foreach ($transactions as $trans)
            <tr>
                @if( $trans->trans_type == 1 )
                    <td>Deposit</td>
                @else
                    <td>Loan Payment</td>
                @endif
                <td>{{ date("h:i A  F d, Y", strtotime($trans->created_at)) }}</td>
                @if( $trans->trans_type == 1 )
                    <td>Savings</td>
                @else
                    <td>My Loan</td>
                @endif
                <td>{{$trans->fname}}, {{$trans->fname}} {{$trans->mname}} </td>
                <td>₱ {{ number_format($trans->amount, 2) }}</td>
            </tr>
            @endforeach
        @else
        <tr>
            <td colspan="100%" class="text-center"><h4 class="text-muted">No Entries Found</h4></td>
        </tr>
        @endif
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-center mt-3">
    {{ $transactions->links() }}
</div>
@endsection
