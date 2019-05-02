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
                <div class="border rounded-circle p-2 bg-light"><i class="text-indigo fas fa-wallet fa-lg"></i></div>
                <h5 class="pt-2 header display-5 font-weight-bold text-center">
                    {{ '₱'.number_format(round($status->savings), 2) }}
                </h5>
                <small>Member's Savings</small>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-center align-items-center flex-column">
                <div class="border rounded-circle p-2 bg-light"><i class="text-success fas fa-hand-holding-heart fa-lg"></i></div>
                <h5 class="pt-2 header display-5 font-weight-bold text-center">
                    {{ '₱'.number_format(round($status->patronage_refund), 2) }}
                </h5>
                <small>Patronage Refund</small>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-center align-items-center flex-column">
                <div class="border rounded-circle p-2 bg-light"><i class="text-orange fas fa-money-bill-alt fa-lg"></i></div>
                <h5 class="pt-2 header display-5 font-weight-bold text-center">
                    {{ '₱'.number_format(round($status->distribution), 2) }}
                </h5>
                <small>Distribution</small>
                <div>
                    @php
                        // $title = date('n', strtotime(NOW())) > 11 ? 'Distribute Money' : 'This Function can be use during December'; 
                        $path = date('n', strtotime(NOW())) > 11 ? '/admin/distribute' : '#'; 
                        $dis = $distribution || $status->distribution == 0 ? 'hidden' : '';
                    @endphp
                    <a href="{{$path}}">
                        <button class="btn btn-success mt-3" data-toggle="tooltip" data-placement="top" {{ date('n', strtotime(NOW())) > 11 ?  : 'hidden' }} {{$dis = $distribution || $status->distribution == 0 ? 'hidden' : ''}}>Distribute Money</button>
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
                <div class="card {{ count($pending) > 0 ? 'border border-primary shadow' : '' }}">
                    <div class="card-body">
                        {!! count($pending) > 0 ? '<span class="fa-stack fa-sm notif-marker tada">
                                <i class="fas fa-circle fa-stack-2x text-primary"></i>
                                <i class="fas fa-exclamation fa-stack-1x fa-inverse rubber"></i>
                            </span>' : '' !!}
                        <div class="row">
                            <div class="col">
                                <h6 class="header mb-3">Loan Requests</h6>
                            </div>
                            <div class="col text-right">
                                <a href="/admin/requests"><small>Requests History</small></a>
                            </div>
                        </div>
                         @if (count($pending) > 0)
                            <div class="accordion" id="dashboard-admin-lr-accordion">
                                <div class="list-group">
                                    @foreach ($pending as $key => $item)
                                        <div class="list-body">
                                            <div class="list-group-item list-group-item-action list-header" data-toggle="collapse" href="#collapse{{ $key }}" role="button" aria-expanded="false" aria-controls="collapse{{ $key }}">
                                                <div class="row d-flex justify-content-center align-items-center">
                                                    <div class="col text-truncate">
                                                        Loan Request from {{ $item->lname.', '. $item->fname.' '. $item->mname }}
                                                    </div>
                                                    <div class="col-2 text-right">
                                                        <a class="card-link text-secondary" data-toggle="collapse" href="#collapse{{ $key }}" role="button" aria-expanded="false" aria-controls="collapse{{ $key }}">
                                                            <i class="fas fa-chevron-down list-chevron"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="collapse{{ $key }}" class="collapse" aria-labelledby="heading{{ $key }}" data-parent="#dashboard-admin-lr-accordion">
                                                <div class="list-content border">   
                                                    <div class="row">
                                                        <div class="col-sm mb-3">
                                                            <div class="h5 mb-0">{{ $item->lname.', '. $item->fname.' '. $item->mname }}</div>
                                                            <small class="text-muted">Member Name</small>
                                                        </div>
                                                        <div class="col-sm mb-3">
                                                            <div class="h5 mb-0">₱ {{ number_format($item->loan_amount, 2) }}</div>
                                                            <small class="text-muted">Loan Amount</small>
                                                        </div>
                                                        <div class="col-sm mb-3">
                                                            <div class="h5 mb-0">{{ $item->days_payable }} Month/s</div>
                                                            <small class="text-muted">Payable</small>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                        <div class="text-justify">{{ $item->comments }}</div>
                                                            {{-- <small class="text-muted">Comment</small> --}}
                                                        </div>
                                                    </div>
                                                    <div class="row pt-3">
                                                        <div class="col">
                                                            <a class="btn btn-outline-primary btn-sm btn-block" role="button" href="/admin/requests/{{ $item->request_id }}/accept">Accept</a>
                                                        </div>
                                                        <div class="col">
                                                            <a class="btn btn-outline-secondary btn-sm btn-block" role="button" href="/admin/requests/{{ $item->request_id }}/reject">Decline</a>
                                                        </div>
                                                    </div>
                                                    {{-- <p>{{ $item->lname.', '. $item->fname.' '. $item->mname }}</p>
                                                    <p>₱ {{ number_format($item->loan_amount, 2) }}</p>
                                                    <p>{{ $item->days_payable }} Month/s</p> --}}
                                                    {{-- <div>
                                                        <a class="btn btn-outline-primary mx-2 no-modal" role="button" href="/admin/requests/{{ $item->id }}/accept">Accept</a>
                                                        <a class="btn btn-outline-secondary mx-2 no-modal" role="button" href="/admin/requests/{{ $item->id }}/reject">Decline</a>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                                    {{-- @foreach ($turn_over as $key => $item)
                                        <div class="card">
                                            <div class="card-header" id="head{{ $key }}">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{ $key }}" aria-expanded="true" aria-controls="collapse{{ $key }}">
                                                Collapsible Group Item #1
                                                </button>
                                            </h2>
                                            </div>
    
                                            <div id="collapse{{ $key }}" class="collapse show" aria-labelledby="head{{ $key }}" data-parent="#dashboard-admin-lr-accordion">
                                            <div class="card-body">
                                                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                            </div>
                                            </div>
                                        </div>
                                    @endforeach --}}
                        @else
                            <div class="p-3 header text-center bg-light text-muted"><small>No Entries Found</small></div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg my-3">
                <div class="card {{ count($turn_over) > 0 ? 'border border-primary shadow' : '' }}">
                    <div class="card-body">
                        {!! count($turn_over) > 0 ? '<span class="fa-stack fa-sm notif-marker tada">
                                <i class="fas fa-circle fa-stack-2x text-primary"></i>
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
                                            <th colspan="2">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($turn_over as $item)
                                                <tr>
                                                <td>{{ $item->lname.', '. $item->fname. ' '.$item->mname }}</td>
                                                <td>₱ {{ number_format($item->amount, 2) }}</td>
                                                <td><a class="btn btn-primary btn-sm no-modal" role="button" href="/admin/receive/{{ $item->id }}/accept">Confirm</a></td>
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
                <div class="card {{ count($memReq) > 0 ? 'border border-primary shadow' : '' }}">
                    <div class="card-body">
                        {!! count($memReq) > 0 ? '<span class="fa-stack fa-sm notif-marker tada">
                                <i class="fas fa-circle fa-stack-2x text-primary"></i>
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

        <h5 class="header text-primary my-3 pt-2"><i class="fas fa-list mr-3"></i>Transactions</h5>
        <div class="row">
            <div class="col-sm my-3">
                <div class="card">
                    <div class="card-body">
                        @if (count($transactions) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Date & Time</th>
                                            <th>Member</th>
                                            <th colspan="2">Amount</th>
                                            <th>Photo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $trans)
                                        <tr>
                                            @if($trans->trans_type == 1 )
                                                <td>Deposit</td>
                                            @else
                                                <td>Loan Payment</td>
                                            @endif
                                             {{-- <td><img class="img-square" style="width:60px;height:60px" src="/storage/cover_images/{{$m->id_photo}}"></td> --}}
                                            <td>{{date("h:i A M d, Y", strtotime($trans->created_at))}}</td>
                                            <td>{{$trans->lname}}, {{$trans->fname}} {{$trans->mname}} </td>
                                            <td class="border-right-0">₱ {{number_format($trans->amount, 2)}}</td>
                                            <td class="text-right border-left-0">
                                                <a class="btn btn-outline-primary mx-2 no-modal btn-sm" role="button" href="/admin/transaction/{{Crypt::encrypt($trans->id)}}/generate" data-toggle="tooltip" data-placement="top" title="Download PDF"><i class="fas fa-download"></i></a>
                                            </td>
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
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/scripts.js') }} "></script>
@endpush
@endsection
