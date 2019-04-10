@extends('layouts.app')

@section('title')
    <title>Transactions</title>
@endsection

@section('content')


<h3 class="header mt-2">Collected money as of {{date('F d, Y', strtotime(NOW()))}}</h3>
<div class="row">
    <div class="col-4">  
        deposit <br>
        {{$deposit.'.00'}}
        {{-- {{dd($loan_from_member)}} --}}
    </div>
    <div class="col-4">  
        loan payment from the member <br>
        {{$loan_payment.'.00'}}
    </div>
    <div class="col-4">  
        distrubution <br>
        {{round($distribution->distribution).'.00'}}
    </div>
    {{-- @if($check->admin_confirmed == null) 
        <a href="/collector/transfer/money"><button class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Transfer all money gathered to admin">Transfer to Admin   </button></a>
    @elseif ($check->admin_confirmed == 1)
        Pending Confirmation from the admin
    @elseif ($check->admin_confirmed ==2 )
        Money Transferred    
    @endif --}}

</div>    
{{-- <small class="badge badge-pill badge-info shadow border py-2 float-right" data-toggle="tooltip" data-placement="top" title="Shows a list of member ready to transfer of the loan money from the request"><span class="h6"><i class="fa fa-question-circle fa-lg" aria-hidden="true"></i></small> --}}
    <div class="row pt-3">
        <div class="col">
            <div class="card">
                <h6 class="card-header float-left">Collector requested confirmation for money turn-over</h6>
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-hover mt-3">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($turn_over) > 0)
                                    @foreach ($turn_over as $item)
                                         <tr>
                                            <td>{{ $item->lname.', '. $item->fname. ' '.$item->mname }}</td>
                                            <td>₱ {{ number_format($item->amount, 2) }}</td>
                                            <td><a class="btn btn-outline-primary mx-2 no-modal" role="button" href="/admin/receive/{{ $item->id }}/accept">Confirm</a></td>
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
                        {{ $turn_over->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                <th>PDF</th>
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
                    <td><a class="btn btn-outline-primary mx-2 no-modal" role="button" href="/admin/transaction/{{Crypt::encrypt($trans->id)}}/generate"> Generate</a> </td>
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
