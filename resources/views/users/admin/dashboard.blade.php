@extends('layouts.dashboard')

@section('title')
    <title>Transactions</title>
@endsection

@section('content')

<div class="container">
    {{-- <div class="mt-2 header"><i class="fas fa-chart-bar"></i><span class="display-5">Current Status</span></div> --}}
    {{-- Status as of {{date('F d, Y', strtotime(NOW()))}} --}}
    {{-- ang pag distribute sa kwarta ig end year
    <div class="row">
        <div class="col">
            <div class="py-3">
            </div>
        </div>
    </div> --}}
    <div class="card-deck pb-5 mt-3">
        <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-center align-items-center flex-column">
                <div class="border rounded-circle p-2 bg-light"><i class="text-indigo fas fa-money-bill-alt fa-lg"></i></div>
                <h5 class="pt-2 header display-5 font-weight-bold text-center">
                    {{ '₱'.number_format(round($status->savings), 2) }}
                </h5>
                <small>Member's Savings</small>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-center align-items-center flex-column">
                <div class="border rounded-circle p-2 bg-light"><i class="text-success fas fa-chart-line fa-lg"></i></div>
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
                <div>
                    @php
                        // $title = date('n', strtotime(NOW())) > 11 ? 'Distribute Money' : 'This Function can be use during December'; 
                        $path = date('n', strtotime(NOW())) > 3 ? '/admin/distribute' : '#'; 
                    @endphp
                    <a href="{{$path}}">
                        <button class="btn btn-success mt-3" data-toggle="tooltip" data-placement="top" {{ date('n', strtotime(NOW())) > 11 ?  : 'hidden' }}>Distribute Money</button>
                    </a>
                </div>
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
</div>

<div class="dashboard-notif-area py-3">
    <div class="container">
        <h5 class="header text-primary my-3"><i class="far fa-bell mr-3"></i>Notifications</h5>
        <div class="row">
            <div class="col-lg my-3">
                <div class="card {{ count($pending) > 0 ? 'border border-warning shadow' : '' }}">
                    <div class="card-body">
                        {!! count($pending) > 0 ? '<span class="fa-stack fa-sm notif-marker tada">
                                <i class="fas fa-circle fa-stack-2x text-warning"></i>
                                <i class="fas fa-exclamation fa-stack-1x fa-inverse rubber"></i>
                            </span>' : '' !!}
                        <h6 class="header mb-3">Loan Requests</h6>
                         @if (count($pending) > 0)
                            
                        @else
                            <div class="p-3 header text-center bg-light text-muted"><small>No Entries Found</small></div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg my-3">
                <div class="card {{ count($turn_over) > 0 ? 'border border-warning shadow' : '' }}">
                    <div class="card-body">
                        {!! count($turn_over) > 0 ? '<span class="fa-stack fa-sm notif-marker tada">
                                <i class="fas fa-circle fa-stack-2x text-warning"></i>
                                <i class="fas fa-exclamation fa-stack-1x fa-inverse rubber"></i>
                            </span>' : '' !!}
                        <h6 class="header mb-3">Money turn-over</h6>
                            {{-- <i class="fas fa-circle fa-lg text-danger notif-marker rounded-circle border shadow"></i> --}}
                        @if (count($turn_over) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($turn_over as $item)
                                                <tr>
                                                <td>{{ $item->lname.', '. $item->fname. ' '.$item->mname }}</td>
                                                <td>₱ {{ number_format($item->amount, 2) }}</td>
                                                <td><a class="btn btn-outline-primary mx-2 no-modal" role="button" href="/admin/receive/{{ $item->id }}/accept">Confirm</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-2">
                                    {{ $turn_over->links() }}
                                </div>
                            </div>
                        @else
                            <div class="p-3 header text-center bg-light text-muted"><small>No Entries Found</small></div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-12 my-3">
                <div class="card {{ count($memReq) > 0 ? 'border border-warning shadow' : '' }}">
                    <div class="card-body">
                        {!! count($memReq) > 0 ? '<span class="fa-stack fa-sm notif-marker tada">
                                <i class="fas fa-circle fa-stack-2x text-warning"></i>
                                <i class="fas fa-exclamation fa-stack-1x fa-inverse rubber"></i>
                            </span>' : '' !!}
                        <h6 class="header mb-3">Member Requests</h6>
                         @if (count($memReq) > 0)
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Contact</th>
                                            <th>Address</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($memReq as $req)
                                            <tr class="">
                                                <td>{{ $req->lname}}, {{ $req->fname }} {{ $req->mname }} </td>
                                                <td>{{ $req->email }}</td>
                                                <td>{{ $req->contact }}</td>
                                                <td>{{ $req->address }}</td>
                                                <td>
                                                    <a href="{{ action('MemberRequestController@accept', $req->id) }}" class="btn btn-success btn-sm m-1" role="button">Accept</a>
                                                    <a href="{{ action('MemberRequestController@decline', $req->id) }}" class="btn btn-outline-secondary btn-sm m-1" role="button">Decline</button>
                                                </td>
                                            </tr>  
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-2">
                                    {{ $turn_over->links() }}
                                </div>
                            </div>
                        @else
                            <div class="p-3 header text-center bg-light text-muted"><small>No Entries Found</small></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="container">
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
        </div> --}}

        {{-- <h3 class="header mt-3">Member Request</h3>
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
        </div> --}}

        <h5 class="header text-primary my-3"><i class="fas fa-list mr-3"></i>Transactions</h5>
        <div class="card">
            <div class="card-body">
                @if (count($transactions) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="border-0">Account</th>
                                    <th class="border-0">Date & Time</th>
                                    <th class="border-0">Member</th>
                                    <th class="border-0">Amount</th>
                                    <th class="border-0">PDF</th>
                                </tr>
                            </thead>
                            <tbody>
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
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $transactions->links() }}
                        </div>
                    </div>
                @else
                    <div class="p-3 header text-center bg-light text-muted"><small>No Entries Found</small></div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{asset ('js/scripts.js')}} "></script>
@endpush
@endsection
