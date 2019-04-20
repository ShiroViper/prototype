@extends('layouts.app')

@section('title')
    <title>Transactions</title>
@endsection

@section('content')


<h3 class="header mt-2">Status as of {{date('F d, Y', strtotime(NOW()))}}</h3>
ang pag distribute sa kwarta ig end year
<div class="row">
    <div class="col">
        <div class="py-3">
            @php
                $title = date('n', strtotime(NOW())) > 11 ? 'Distribute Money' : 'This Function can be use during December'; 
                $path = date('n', strtotime(NOW())) > 3 ? '/admin/distribute' : '#'; 
            @endphp
            <a href="{{$path}} "><button class="btn btn-light border" data-toggle="tooltip" data-placement="top" title = "{{$title}} " >Distribute</button></a>
        </div>
    </div>
</div>
<div class="card-deck pb-5 mt-3">
    <div class="card shadow-sm">
        <div class="card-body d-flex justify-content-center align-items-center flex-column">
            <div class="border rounded-circle p-2 bg-light"><i class="text-success fas fa-money-bill-alt fa-lg"></i></div>
            <h5 class="pt-2 header display-5 font-weight-bold text-center">
                {{ '₱'.number_format(round($status->savings), 2) }}
            </h5>
            <small>Member's Savings</small>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body d-flex justify-content-center align-items-center flex-column">
            <div class="border rounded-circle p-2 bg-light"><i class="text-primary fas fa-chart-line fa-lg"></i></div>
            <h5 class="pt-2 header display-5 font-weight-bold text-center">
                {{ '₱'.number_format(round($status->patronage_refund), 2) }}
            </h5>
            <small>Patronage Refund</small>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body d-flex justify-content-center align-items-center flex-column">
            <div class="border rounded-circle p-2 bg-light"><i class="text-orange fas fa-wallet fa-lg"></i></div>
            <h5 class="pt-2 header display-5 font-weight-bold text-center">
                {{ '₱'.number_format(round($status->distribution), 2) }}
            </h5>
            <small>Distribution</small>
        </div>
    </div>
</div>

{{-- <div class="row">
    <div class="col-4">  
        deposit <br>
        {{$deposit.'.00'}}
    </div>
    <div class="col-3">  
        loan payment from the member <br>
        {{$loan_payment.'.00'}}
    </div>
    <div class="col-3">  
        distrubution <br>
        {{round($distribution->distribution).'.00'}}
    </div>
</div>     --}}
{{-- <small class="badge badge-pill badge-info shadow border py-2 float-right" data-toggle="tooltip" data-placement="top" title="Shows a list of member ready to transfer of the loan money from the request"><span class="h6"><i class="fa fa-question-circle fa-lg" aria-hidden="true"></i></small> --}}

<h3 class="header mt-3">Money turn-over</h3>
<div class="container">
    <div class="table-responsive">
        <table class="table table-striped mt-3">
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

<h3 class="header mt-3">Member Request</h3>
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
@push('scripts')
    <script src="{{asset ('js/scripts.js')}} "></script>
@endpush
@endsection
