@extends('layouts.app')

@section('title')
    <title>Transactions</title>
@endsection

@section('content')
<div class="row">
    <div class="col">
        <h3 class="header mt-2">Transactions</h3>
        <div class="table-responsive">
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>Account</th>
                        <th>Date&Time</th>
                        <th>Member </th>
                        <th>Amount</th>
                        <th>PDF</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($transactions) > 0)
                        @foreach ($transactions as $trans)
                        <tr>
                            @if($trans->trans_type == 1)
                                <td>Savings: Deposit</td>
                            @else
                                <td>My Loan: Loan Payment</td>
                            @endif
                            <td>{{date("h:i A M d, Y", strtotime($trans->created_at))}}</td>
                            <td>{{$trans->lname}}, {{$trans->fname}} {{$trans->mname}} </td>
                            <td>₱ {{$trans->amount}}</td>
                            <td><a class="btn btn-outline-primary mx-2 no-modal" role="button" href="/collector/transaction/{{ Crypt::encrypt($trans->id) }}/generate">Generate</a></td>
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
    </div>
</div>

@endsection