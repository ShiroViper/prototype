@extends('layouts.app')

@section('title')
<title>Alkansya</title>
@endsection

@section('content')
<h3 class="header mt-3">Requests</h3>
<div class="row pt-3">
    <div class="col">
        <div class="card">
            <h6 class="card-header">Pending Requests</h6>
            <div class="container">
                <div class="table-responsive">
                    <table class="table table-hover mt-3">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Loan Amount</th>
                                <th>Days Payable</th>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($pending) > 0)
                                @foreach ($pending as $item)
                                    {{-- <tr data-toggle="modal" data-target="#LoanModal"> --}}
                                    <tr>
                                        <td>{{date("F d, Y", strtotime($item->created_at))}}</td>
                                        <td>{{ $item->loan_amount }}</td>
                                        <td>{{ $item->days_payable }}</td>
                                        <td>{{ $item->user_id }}</td>
                                        <td>{{ $item->lname.', '. $item->fname.' '. $item->mname }}</td>
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
                                <th>Date Checked</th>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Loan Amount</th>
                                <th>Days Payable</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($requests) > 0)
                                @foreach ($requests as $request)
                                    @if ($request->confirmed)
                                    <tr class="text-success" data-toggle="modal" data-target="#histReqModal" data-id="{{ $request->id }}" data-ca="{{ $request->created_at }}" data-cf="{{ $request->updated_at }}" data-la="{{ $request->loan_amount }}" data-dp="{{ $request->days_payable }}" data-ap="{{ $request->confirmed == 1 ? 'Approved' : 'Declined' }}" data-desc="{{ $request->description }}">
                                    @else
                                    <tr class="text-danger" data-toggle="modal" data-target="#histReqModal" data-id="{{ $request->id }}" data-ca="{{ $request->created_at }}" data-cf="{{ $request->updated_at }}" data-la="{{ $request->loan_amount }}" data-dp="{{ $request->days_payable }}" data-ap="{{ $request->confirmed == 1 ? 'Approved' : 'Declined' }}" data-desc="{{ $request->description }}">
                                    @endif
                                        <td>{{ $request->updated_at }}</td>
                                        <td>{{ $request->user_id }}</td>
                                        <td>{{ $request->lname.', '. $request->fname.' '. $request->mname }}</td>
                                        <td>{{ $request->loan_amount }}</td>
                                        <td>{{ $request->days_payable }}</td>
                                        <td>{{ $request->confirmed ? 'Approved' : 'Declined' }}</td>
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

<div class="modal fade" id="reqModal" tabindex="-1" role="dialog" aria-labelledby="reqModalLabel" aria-hidden="true">
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
                <div class="row">
                    <div class="col-4">
                        <span class="display-5">Status: </span>
                    </div>
                    <div class="col">
                        <span class="loan-ap"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <span class="display-5">Confirmed on: </span>
                    </div>
                    <div class="col">
                        <span class="loan-cf"></span>
                    </div>
                </div>
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
            </div>
        </div>
    </div>
</div>
@endsection
