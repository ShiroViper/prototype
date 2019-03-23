@extends('layouts.app')

@section('title')
<title>Alkansya - Requests</title>
@endsection

@section('content')
<h3 class="header mt-2">Requests</h3>
<div class="row pt-3">
    <div class="col">
        <div class="card">
            <h6 class="card-header">Pending Requests</h6>
            <div class="container">
                <div class="table-responsive">
                    <table class="table table-hover mt-3">
                        <thead>
                            <tr>
                                <th>Date Requested</th>
                                <th>Name</th>
                                <th>Requested Money</th>
                                <th>Loan Amount</th>
                                <th>Payables</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($pending) > 0)
                                @foreach ($pending as $item)
                                    {{-- <tr data-toggle="modal" data-target="#LoanModal"> --}}
                                    <tr >
                                        <td>{{ date("F d, Y", strtotime($item->created_at)) }}</td>
                                        <td>{{ $item->user->lname.', '. $item->user->fname.' '. $item->user->mname }}</td>
                                        <td>₱{{ number_format($item->loan_amount/0.94, 2) }}</td>
                                        <td>₱{{ number_format($item->loan_amount, 2) }}</td>
                                        @if($item->method == 2)
                                            <td>{{ $item->days_payable / 30 }} Months</td>
                                        @elseif($item->method == 1)
                                            <td>{{ $item->days_payable /7 }} Weeks</td>
                                        @else
                                            <td>{{ $item->days_payable }} Days</td>
                                        @endif
                                        <td class="d-flex flex-row">
                                            <a class="btn btn-outline-primary mx-2 no-modal" role="button" href="/admin/requests/{{ $item->id }}/accept">Accept</a>
                                            <a class="btn btn-outline-secondary mx-2 no-modal" role="button" href="/admin/requests/{{ $item->id }}/reject">Decline</a>
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
                    {{ $pending->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row pt-5">
        <div class="col">
            <div class="card">
                <h6 class="card-header">Requests History</h6>
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date Approved</th>
                                    <th>Name</th>
                                    <th>Loan Amount</th>
                                    <th>Payables</th>
                                    <th>Status</th>
                                    <th>Money Received</th>
                                    <th>Paid</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($requests) > 0)
                                    @foreach ($requests as $request)
                                        {{-- @if ($request->paid)
                                        <tr class="clickable" data-toggle="modal" data-target="#histReqModal" data-id="{{ $request->id }}" data-cdate="{{ date('F d, Y H:i:s A', strtotime($request->created_at)) }}" data-mem="{{ $request->user->lname.', '.$request->user->fname.' '.$request->user->mname }}" data-memid="{{ $request->user->id }}" data-udate="{{ date('F d, Y H:i:s A', strtotime($request->updated_at)) }}" data-amount="{{ $request->loan_amount }}" data-dp="{{ $request->days_payable }}" data-conf="{{ $request->confirmed == 1 ? 'Approved' : 'Declined' }}" data-desc="{{ $request->description }}" data-paid="{{ $request->paid ? ($request->confirmed ? 'Yes' : '') : 'Ongoing' }}">
                                        @else
                                        <tr class="table-secondary font-weight-bold clickable" data-toggle="modal" data-target="#histReqModal" data-id="{{ $request->id }}" data-cdate="{{ date('F d, Y H:i:s A', strtotime($request->created_at)) }}" data-mem="{{ $request->user->lname.', '.$request->user->fname.' '.$request->user->mname }}" data-memid="{{ $request->user->id }}" data-udate="{{ date('F d, Y H:i:s A', strtotime($request->updated_at)) }}" data-amount="{{ $request->loan_amount }}" data-dp="{{ $request->days_payable }}" data-conf="{{ $request->confirmed == 1 ? 'Approved' : 'Declined' }}" data-desc="{{ $request->description }}" data-paid="{{ $request->paid ? ($request->confirmed ? 'Yes' : '') : 'Ongoing' }}">
                                        @endif --}}
                                        <tr >
                                            <td>{{ $request->updated_at}}</td>
                                            {{-- <td>{{ $request->user->lname.', '. $request->user->fname.' '. $request->user->mname }}</td> --}}
                                            <td>{{$request->lname}}, {{$request->fname}} {{$request->mname}} </td>
                                            <td>₱ {{ $request->loan_amount }}</td>
                                            <td>{{ $request->days_payable }} Days</td>
                                            <td>{{ $request->confirmed ? 'Approved' : 'Declined' }}</td>
                                            <td>{{ $request->received ? 'Yes' : ($request->confirmed ? 'No': 'N/A')}} </td>  
                                            <td>{{ $request->confirmed ? ($request->paid ? 'Yes' : 'Ongoing') : 'N/A' }}</td>
                                            {{-- if status is declined skip this function otherwise execur --}}
                                            @if($request->received != 1 && $request->confirmed != 0)
                                                <td><a class="btn btn-outline-primary mx-2 no-modal" role="button" href="/admin/process/{{ $request->id }}/edit">Transfer</a></td>
                                            @else
                                                <td>N/A</td>
                                            @endif --}}
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
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
{{-- <div class="modal fade" id="reqModal" tabindex="-1" role="dialog" aria-labelledby="reqModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reqModalLabel">View Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4">
                        <span class="display-5">Loan Amount: </span>
                    </div>
                    <div class="col">
                        <span class="loan-la"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <span class="display-5">Days Payable: </span>
                    </div>
                    <div class="col">
                        <span class="loan-dp"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <span class="display-5">Description: </span>
                    </div>
                    <div class="col">
                        <span class="loan-desc"></span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-4">
                        <span class="display-5">Date Created: </span>
                    </div>
                    <div class="col">
                        <span class="loan-ca"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> --}}

{{-- <div class="modal fade" id="histReqModal" tabindex="-1" role="dialog" aria-labelledby="histReqModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="histReqModalLabel">View Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning loan-unpaid text-center">  
                </div>
                <div class="row">
                    <div class="col-4 text-right">
                        <span class="">Status: </span>
                    </div>
                    <div class="col">
                        <span class="font-weight-bold loan-conf"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4 text-right">
                        <span class="">Confirmed on: </span>
                    </div>
                    <div class="col">
                        <span class="font-weight-bold loan-udate"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 text-right">
                        <span class="">Loaned By: </span>
                    </div>
                    <div class="col">
                        <span class="font-weight-bold loan-mem"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4 text-right">
                        <span class="">Member's ID: </span>
                    </div>
                    <div class="col">
                        <span class="font-weight-bold loan-memid"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 text-right">
                        <span class="">Loan Amount: </span>
                    </div>
                    <div class="col">
                        <span class="font-weight-bold loan-amount"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 text-right">
                        <span class="">Days Payable: </span>
                    </div>
                    <div class="col">
                        <span class="font-weight-bold loan-dp"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 text-right">
                        <span class="">Paid: </span>
                    </div>
                    <div class="col">
                        <span class="font-weight-bold loan-paid"></span>
                    </div>
                </div> --}}
                {{-- <div class="row">
                    <div class="col-4 text-right">
                        <span class="">Description: </span>
                    </div>
                    <div class="col">
                        <span class="font-weight-bold loan-desc"></span>
                    </div>
                </div> --}}
                {{-- <div class="row mt-3">
                    <div class="col-4 text-right">
                        <span class="">Date Created: </span>
                    </div>
                    <div class="col">
                        <span class="font-weight-bold loan-cdate"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div> --}}
</div>
@endsection
