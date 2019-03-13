@extends('layouts.app')

@section('title')
    <title>Transactions</title>
@endsection

@section('content')
<div class="row pt-5">
        <div class="col">
            <div class="card">
                <h6 class="card-header">Transactions History</h6>
                <div class="container">
                    <div class="table-responsive">
                        <table class="table" style="text-align:center">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Date</th>
                                    <th>Member ID</th>
                                    <th>Name</th>
                                    <th>Account</th>
                                    <th>Type</th>
                                    <th>Pay</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($transactions) > 0)
                                    @foreach ($transactions as $trans)
                                    <tr>
                                        <td>{{date("h:i A", strtotime($trans->created_at))}}</td>
                                        <td>{{date("M d, Y", strtotime($trans->created_at))}}</td>

                                        <td>{{$trans->id}} </td>
                                        <td>{{$trans->lname}} {{$trans->fname}}</td>
                                    
                                        @if($trans->trans_type == 0 || $trans->trans_type == 1 )
                                            <td>My Loan</td>
                                        @elseif ($trans->trans_type==2)
                                            <td>Savings</td>
                                        @endif
                
                                        @if($trans->trans_type == 0 )
                                            <td>Loan</td>
                                        @elseif( $trans->trans_type == 1)
                                            <td>Loan Payment</td>
                                        @elseif ($trans->trans_type==2)
                                            <td>Deposit</td>
                                        @endif
                
                                        <td>Php {{$trans->amount}}</td>
                                        <td>Php {{$trans->balance}} </td>
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