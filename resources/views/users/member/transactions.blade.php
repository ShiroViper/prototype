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
                <th>Date</th>
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
                @elseif( $trans->trans_type == 2 )
                    <td>Loan</td>
                @elseif ( $trans->trans_type == 3 )
                    <td>Loan Payment</td>
                @endif
                <!-- <td>{{date("h:i A", strtotime($trans->created_at))}}</td> -->
                <td>{{date("M d, Y", strtotime($trans->created_at))}}</td>
                @if($trans->trans_type == 2 || $trans->trans_type == 3 )
                    <td>My Loan</td>
                @elseif ($trans->trans_type == 1 )
                    <td>Savings</td>
                @endif
                <td>{{$trans->collector_id}} </td>
                <td>Php {{$trans->amount}}</td>
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
