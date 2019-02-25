@extends('layouts.app')

@section('title')
<title>Alkansya</title>
@endsection

@section('content')
<h3 class="mt-3">Requests</h3>
<div class="row pt-3">
    <div class="col col-xl-10">
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
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->loan_amount }}</td>
                                        <td>{{ $item->days_payable }}</td>
                                        <td>{{ $item->user_id }}</td>
                                        <td>{{ $item->lname.', '. $item->fname.' '. $item->mname }}</td>
                                        <td class="d-flex flex-row">
                                            <a class="btn btn-outline-primary mx-2" role="button" href="/admin/requests/{{ $item->id }}/accept">Accept</a>
                                            <a class="btn btn-outline-secondary mx-2" role="button" href="/admin/requests/{{ $item->id }}/reject">Decline</a>
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
    <div class="col col-xl-10">
        <div class="card">
            <h6 class="card-header">Requests History</h6>
            <div class="container">
                <div class="table-responsive">
                    <table class="table">
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
                                    <tr class="text-success">
                                    @else
                                    <tr class="text-danger">
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
@endsection
