@extends('layouts.app')

@section('title')
<title>Alkansya - Requests</title>
@endsection

@section('content')
<div class="row">
    <div class="col">
            <h3 class="header float-left">Requests</h3>
            <div class="badge badge-pill badge-primary ml-3 px-2">Compound Interest: 6%</div>
            {{-- <p class="float-right">* Compound Interest: 6%</p> --}}
        @if(!$unpaid)
            <div class="float-right">
                <a class="badge badge-pill badge-success shadow border py-2" role="button" data-toggle="tooltip" data-placement="top" title="Add Loan Request" href="/member/requests/create"><span class="h5"><i class="fas fa-plus fa-lg"></i></a>
            </div>
        @endif
    </div>
</div>
{{-- {{dd($distribution)}} --}}
{{-- Show list of pending distribution confirmation --}}
@if ($distribution)
    <div class="row pt-3 mb-5">
        <div class="col">
            <div class="card">
                <h6 class="card-header">Pending Confirmation</h6>
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-hover" >
                            <thead>
                                <tr>
                                    <th>Date Distribute</th>
                                    <th>Savings</th>
                                    <th>Distribution Amount</th>
                                    <th>Patronage Refund</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{date('h:i A F d, Y', strtotime($distribution->created_at))}} </td>
                                    <td>₱ {{number_format($distribution->savings,2)}} </td>
                                    <td>₱ {{number_format($distribution->patronage_refund,2)}} </td>
                                    <td>₱ {{number_format($distribution->amount, 2)}} </td>
                                    <td>₱ {{number_format($distribution->savings + $distribution->patronage_refund + $distribution->amount, 2)}} </td>
                                    <td class="d-flex flex-row">
                                        <a class="btn btn-outline-primary mx-2 no-modal" role="button" href="/member/distribution/{{ $distribution->id }}/accept">Confirm</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $pending_mem_con->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Show list of data that waiting to confirm --}}
@if (count($pending_mem_con))
    <div class="row pt-3 mb-5">
        <div class="col">
            <div class="card">
                <h6 class="card-header">Pending Confirmation</h6>
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-hover" >
                            <thead>
                                <tr>
                                    <th>Date Transacted</th>
                                    <th>Name</th>
                                    <th>Transaction Type</th>
                                    <th>Amount Sent</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pending_mem_con as $item)
                                    <tr> 
                                        <td>{{date('h:i A F d, Y', strtotime($item->updated_at))}} </td>
                                        <td>{{$item->lname. ', '.$item->fname.' '.$item->mname}}</td>
                                        @if($item->trans_type == 1)
                                            <td>Deposit</td>
                                        @else
                                            <td>Loan Payment</td>
                                        @endif
                                        <td>₱ {{number_format($item->amount, 2)}} </td>
                                        {{-- <td>{{$item->trans_type}} </td> --}}
                                        <td class="d-flex flex-row">
                                            @if($item->trans_type == 1 )
                                                <a class="btn btn-outline-primary mx-2 no-modal" role="button" href="/member/sent/{{ $item->id }}/d_accept">Confirm</a>
                                            @else
                                                <a class="btn btn-outline-primary mx-2 no-modal" role="button" href="/member/sent/{{ $item->id }}/{{$token}}/accept">Confirm</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $pending_mem_con->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (count($pending_mem_receive) > 0)
    <div class="row pt-3">
        <div class="col">
            <div class="card">
                <h6 class="card-header">Pending Money</h6>
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-hover" >
                            <thead>
                                <tr>
                                    <th>Date Sent</th>
                                    <th>Name</th>
                                    <th>Money Received</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($pending_mem_receive) > 0)
                                    @foreach ($pending_mem_receive as $item)
                                    <tr>
                                        <td>{{date('h:i A F d, Y', strtotime($item->updated_at))}} </td>
                                        <td>{{$item->lname}}, {{$item->fname}} {{$item->mname}} </td>
                                        <td>₱ {{number_format($item->loan_amount, 2) }} </td>
                                        <td class="d-flex flex-row">
                                            <a class="btn btn-outline-primary mx-2 no-modal" role="button" href="/member/receive/{{ $item->request_id }}/{{$token}}/accept">Accept</a>
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
                        {{ $pending_mem_receive->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="row mt-3">
    <div class="col">
        <div class="card">
            <h6 class="card-header">Pending Requests</h6>
            <div class="container">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date Requested</th>
                                <th>Loan Requested</th>
                                <th>Payables</th>
                                <th>Due Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($pending) > 0)
                                @foreach ($pending as $item)
                                    {{-- <tr data-toggle="modal" data-target="#reqModal" data-id="{{ $item->id }}" data-ca="{{ $item->created_at }}" data-la="{{ $item->loan_amount }}" data-dp="{{ $item->days_payable }}" data-desc="{{ $item->description }}"> --}}
                                    <tr>
                                        <td>{{ date("h:i A  F d, Y", strtotime($item->created_at)) }}</td>
                                        <td>₱ {{ number_format($item->loan_amount, 2) }}</td>
                                        <td>{{ $item->days_payable }} Months</td>
                                        <td>{{date("F d, Y", strtotime($item->created_at.'+'.$item->days_payable.' months'))}} </td>
                                        <td>
                                            {!! Form::open(['action' => ['LoanRequestsController@destroy', $item->id], 'method' => 'POST']) !!}
                                                {{ Form::hidden('_method', 'DELETE') }}
                                                {{ Form::submit('Cancel Request', ['class' => 'btn btn-outline-secondary no-modal']) }}
                                            {!! Form::close() !!}
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

<div class="row mt-5">
        <div class="col">
            <div class="card">
                <h6 class="card-header">Requests History</h6>
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date Approved</th>
                                    <th>Loan Amount</th>
                                    <th>Payables</th>
                                    <th>Status</th>
                                    <th>Money Received</th>
                                    <th>Paid</th>
                                    <th>Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($requests) > 0)
                                    @foreach ($requests as $request)
                                        @if ($request->paid)
                                            <tr class="clickable" data-toggle="modal" data-target="#histReqModal" data-id="{{ $request->id }}" data-cdate="{{ date('F d, Y H:i:s A', strtotime($request->created_at)) }}" data-udate="{{ date('F d, Y H:i:s A', strtotime($request->updated_at)) }}" data-amount="{{ $request->loan_amount }}" data-dp="{{ $request->days_payable }}" data-conf="{{ $request->confirmed == 1 ? 'Approved' : 'Declined' }}" data-desc="{{ $request->description }}" data-paid="{{ $request->paid ? ($request->confirmed ? 'Yes' : '') : 'Ongoing' }}">
                                        @else
                                            <tr class="table-secondary font-weight-bold clickable" data-toggle="modal" data-target="#histReqModal"  data-id="{{ $request->id }}" data-cdate="{{ date('F d, Y H:i:s A', strtotime($request->created_at)) }}" data-udate="{{ date('F d, Y H:i:s A', strtotime($request->updated_at)) }}" data-amount="{{ $request->loan_amount }}" data-dp="{{ $request->days_payable }}" data-conf="{{ $request->confirmed == 1 ? 'Approved' : 'Declined' }}" data-desc="{{ $request->description }}" data-paid="{{ $request->paid ? ($request->confirmed ? 'Yes' : '') : 'Ongoing' }}">
                                        @endif
                                            <td>{{ date("h:i A  F d, Y", strtotime($request->updated_at)) }}</td>
                                            <td>₱ {{ number_format($request->loan_amount, 2) }}</td>
                                            <td>{{ $request->days_payable }} Months</td>
                                            <td>{{ $request->confirmed ? 'Approved' : 'Declined' }}</td>
                                            <td>{{ $request->received ? 'Yes' : 'No'}}</td>
                                            <td>{{ $request->paid ? ($request->confirmed ? 'Yes' : '') : 'Ongoing' }}</td>
                                            <td>{{ date("F d, Y", strtotime($request->updated_at.'+'.$request->days_payable.' months')) }}</td>
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

<div class="modal fade" id="histReqModal" tabindex="-1" role="dialog" aria-labelledby="histReqModalLabel" aria-hidden="true">
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
                        <span class="">Loan Amount: </span>
                    </div>
                    <div class="col">
                        <span class="font-weight-bold loan-amount"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 text-right">
                        <span class="">Months Payable: </span>
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
                </div>
                <div class="row mt-3">
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
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/scripts.js') }}"></script>
@endpush

@endsection
