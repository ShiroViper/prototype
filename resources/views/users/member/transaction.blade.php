@extends('layouts.app')

@section('title')
    <title>Transactions</title>
@endsection

@section('content')
<div class="row pt-5">
        <div class="col">
            <div class="card">
                <h6 class="card-header">Transactions</h6>
                <div class="container">
                    <div class="table-responsive">
                        <table class="table" style="text-align:center">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Collector Name</th>
                                    <th>Amount</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($transactions) > 0)
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            @if($transaction->trans_type == 0 || $transaction->trans_type == 1)
                                                <td>My Loan</td>
                                            @else
                                                <td>Savings</td>
                                            @endif
                                            <td>{{date("M d, Y", strtotime($transaction->created_at))}}</td>
                                            @if($transaction->trans_type == 0)
                                                <td>Loan Widthdraw</td>
                                            @elseif($transaction->trans_type == 1)
                                                <td>Loan Payment</td>
                                            @elseif($transaction->trans_type == 2)
                                                <td>Deposit</td>
                                            @endif
                                            <td>{{$transaction->lname}}, {{$transaction->fname}}</td>
                                            <td>Php {{$transaction->amount}} </td>
                                            <td>Php {{$transaction->balance}} </td>
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
        </div>
    </div>

@endsection