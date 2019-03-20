@extends('layouts.app')

@section('title')
<title>Alkasnya - View user</title>
@endsection

@section('content')
    @if($active == 'patronage')
    <p>pat</p>
    @elseif($active == 'loan')
        
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
                                        <th>Request Date</th>
                                        <th>User ID</th>
                                        <th>Name</th>
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
                                                <td>{{ $item->user_id }}</td>
                                                <td>{{ $item->user->lname.', '. $item->user->fname.' '. $item->user->mname }}</td>
                                                <td>₱{{ $item->loan_amount }}</td>
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
    @else
    <p>saving</p>
    @endif
@endsection