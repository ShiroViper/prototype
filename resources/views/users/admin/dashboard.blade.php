@extends('layouts.app')

@section('title')
    <title>Transactions</title>
@endsection

@section('content')
<h3 class="header mt-2">Member Request</h3>
<div class="table-responsive">
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Contact Details</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if (count($memReq) > 0)
                @foreach ($memReq as $req)
                <tr>
                    <td>{{ $req->lname}}, {{ $req->fname }} {{ $req->mname }} </td>
                    <td>{{ $req->email }}</td>
                    <td>{{ $req->contact }}</td>
                    <td>
                        <a href="{{ action('MemberRequestController@accept', $req->id) }}" class="btn btn-primary m-1" role="button">Accept</a>
                        <a href="{{ action('MemberRequestController@decline', $req->id) }}" class="btn btn-outline-secondary m-1" role="button">Decline</button>
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

<h3 class="header mt-3">Transactions</h3>
<div class="table-responsive">
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>Account</th>
                <th>Date & Time</th>
                <th>Member</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @if (count($transactions) > 0)
                @foreach ($transactions as $trans)
                <tr>
                    @if($trans->trans_type == 1 )
                        <td>Savings: Deposit</td>
                    @else
                        <td>My Loan: Loan Payment</td>
                    @endif
                    <td>{{date("h:i A M d, Y", strtotime($trans->created_at))}}</td>
                    <td>{{$trans->lname}}, {{$trans->fname}} {{$trans->mname}} </td>
                    <td>₱ {{number_format($trans->amount, 2)}}</td>
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
