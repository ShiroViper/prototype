@extends('layouts.app')

@section('title')
<title>Alkansya - Requests</title>
@endsection

@section('content')

<h3 class="header mt-3">Requests</h3>
<div class="row pt-3">
    <div class="col">
        <div class="card">
            <h6 class="card-header">Pending Money</h6>
            <div class="container">
                <div class="table-responsive">
                    <table class="table table-hover mt-3">
                        <thead>
                            <tr>
                                <th>Admin ID</th>
                                <th>Name</th>
                                <th>Money to Receive</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($pending) > 0)
                                @foreach ($pending as $item)
                                    @if($item->transfer == 1)
                                        {{-- <tr data-toggle="modal" data-target="#LoanModal"> --}}
                                        <tr >
                                            <td>{{ $item->admin_id }}</td>
                                            <td>{{ $item->lname.', '. $item->fname }}</td>
                                            <td>₱{{ $item->loan_amount }}</td>
                                            <td>Transferring</td>
                                            <td class="d-flex flex-row">
                                                <a class="btn btn-outline-primary mx-2 no-modal" role="button" href="/collector/receive/{{ $item->request_id }}/accept">Accept</a>
                                            </td>
                                        </tr>
                                    @endif
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

<div class="row pt-3">
    <div class="col">
        <div class="card">
            <h6 class="card-header">Money Transferred To Member</h6>
            <div class="container">
                <div class="table-responsive">
                    <table class="table table-hover mt-3">
                        <thead>
                            <tr>
                                <th>Received Date</th>
                                <th>Request ID</th>
                                <th>Member ID</th>
                                <th>Name</th>
                                <th>Money Transferred</th>

                            </tr>
                        </thead>
                        <tbody>
                            @if (count($transferred) > 0)
                                @foreach ($transferred as $item)
                                     <tr class="manage-accounts clickable-row" data-href="/collector/process/{{ $item->id }}/edit">
                                        <td>{{$item->updated_at}} </td>
                                        <td>{{$item->request_id}} </td>
                                        <td>{{ $item->user_id }}</td>
                                        <td>{{ $item->lname.', '. $item->fname }}</td>
                                        <td>₱{{ $item->loan_amount }}</td>
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
                    {{ $transferred->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row pt-3">
    <div class="col">
        <div class="card">
            <h6 class="card-header">Money Received[Click Here]</h6>
            <div class="container">
                <div class="table-responsive">
                    <table class="table table-hover mt-3">
                        <thead>
                            <tr>
                                <th>Received Date</th>
                                <th>Request ID</th>
                                <th>Admin ID</th>
                                <th>Name</th>
                                <th>Money</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($received) > 0)
                                @foreach ($received as $item)
                                     <tr class="manage-accounts clickable-row" data-href="/collector/process/{{ $item->id }}/edit">
                                        <td>{{$item->updated_at}} </td>
                                        <td>{{$item->request_id}} </td>
                                        <td>{{ $item->admin_id }}</td>
                                        <td>{{ $item->lname.', '. $item->fname }}</td>
                                        <td>₱{{ $item->loan_amount }}</td>
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
                    {{ $received->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection