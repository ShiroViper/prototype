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
                <th>Collector</th>
                <th colspan="2">Amount</th>
                {{-- <th>PDF</th> --}}
            </tr>
        </thead>
        <tbody>
        @if (count($transactions) > 0)
            @foreach ($transactions as $trans)
            <tr>
                @if( $trans->trans_type == 1 )
                    <td>Savings: Deposit</td>
                @else
                    <td>My Loan: Loan Payment</td>
                @endif
                <td>{{ date("h:i A  F d, Y", strtotime($trans->created_at)) }}</td>
                <td>{{$trans->fname}}, {{$trans->fname}} {{$trans->mname}} </td>
                <td>₱ {{ number_format($trans->amount, 2) }}</td>
                <td class="text-right">
                    {{-- <a class="btn btn-outline-primary mx-2 no-modal btn-sm" role="button" href="/member/transaction/{{Crypt::encrypt($trans->id)}}/generate" data-toggle="tooltip" data-placement="top" title="Download PDF"><i class="fas fa-download"></i></a> --}}
                    <a class="btn btn-outline-primary mx-2 no-modal btn-sm" role="button" href="/member/transaction/{{Crypt::encrypt($trans->id)}}/generate" data-toggle="tooltip" data-placement="top" title="Download PDF"><i class="fas fa-download"></i></a>
                </td>
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
