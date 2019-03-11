@extends('layouts.app')

@section('title')
<title>Alkansya - Requests</title>
@endsection

@section('content')
<div class="row">
    <div class="col mt-3">
        <div class="float-left">
            <h3 class="header">Requests</h3>
        </div>
        @if(!$unpaid)
            <div class="float-right">
                <a class="badge badge-pill badge-success shadow border py-2" role="button" data-toggle="tooltip" data-placement="top" title="Add Loan Request" href="/member/requests/create"><span class="h5"><i class="fas fa-plus fa-lg"></i></a>
            </div>
        @endif
    </div>
</div>
<div class="row pt-3">
    <div class="col">
        <div class="card">
            <h6 class="card-header">Pending Requests</h6>
            <div class="container">
                <div class="table-responsive">
                    <table class="table table-hover" style="text-align: center">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Loan Amount</th>
                                <th>Days Payable</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($pending) > 0)
                                @foreach ($pending as $item)
                                    <tr>
                                        <td>{{ date('F d, Y', strtotime($item->created_at)) }}</td>
                                        <td>{{ $item->loan_amount }} Php</td>
                                        <td>{{ $item->days_payable }} Days</td>
                                        <td>
                                            {!! Form::open(['action' => ['LoanRequestsController@destroy', $item->id], 'method' => 'POST']) !!}
                                                {{ Form::hidden('_method', 'DELETE') }}
                                                {{ Form::submit('Cancel Request', ['class' => 'btn btn-outline-secondary']) }}
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
<div class="row pt-5">
    <div class="col">
        <div class="card">
            <h6 class="card-header">Requests History</h6>
            <div class="container">
                <div class="table-responsive">
                    <table class="table" style="text-align: center">
                        <thead>
                            <tr>
                                <th>Date</th>
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
                                        <td>{{ date('F d, Y', strtotime($request->updated_at)) }}</td>
                                        <td>{{ $request->loan_amount }} Php</td>
                                        <td>{{ $request->days_payable }} days</td>
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
