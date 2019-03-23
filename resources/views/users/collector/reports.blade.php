@extends('layouts.app')

@section('title')
<title>Alkansya - Requests</title>
@endsection

@section('content')

<h3 class="header mt-2">Requests</h3>

@if(count($pending_col) > 0)
    <div class="row pt-3">
        <div class="col">
            <div class="card">
                <h6 class="card-header">Pending Money</h6>
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-hover mt-3">
                            <thead>
                                <tr>
                                    <th>Money to Receive</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($pending_col) > 0)
                                    @foreach ($pending_col as $item)
                                        @if($item->transfer == 1)
                                            {{-- <tr data-toggle="modal" data-target="#LoanModal"> --}}
                                            <tr >
                                                <td>₱ {{ $item->loan_amount }}</td>
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
                        {{ $pending_col->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Show pending confirmation during loan payment --}}
{{-- @if ($confirmed != null) --}}
    <div class="row pt-3">
        <div class="col">
            <div class="card">
                <h6 class="card-header">Pending Confirmation</h6>
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-hover mt-3">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($confirmed) > 0)
                                    @foreach ($confirmed as $item)
                                        <tr>
                                            <td>{{ $item->lname.', '. $item->fname. ' '. $item->mname }}</td>
                                            <td>₱ {{ $item->amount }}</td>
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
                        {{ $confirmed->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- @endif --}}


<div class="row pt-3">
    <div class="col">
        <div class="card">
            <h6 class="card-header">Money Received</h6>
            <div class="container">
                <div class="table-responsive">
                    <table class="table table-hover mt-3">
                        <thead>
                            <tr>
                                <th>Received Date</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($received_col) > 0)
                                @foreach ($received_col as $item)
                                     <tr>
                                        <td>{{$item->updated_at}} </td>
                                        <td>{{ $item->lname.', '. $item->fname }}</td>
                                        <td>₱ {{ $item->loan_amount }}</td>
                                        <td><a class="btn btn-outline-primary mx-2 no-modal" role="button" href="/collector/receive/{{ $item->request_id }}/accept">Transfer</a></td>
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
                    {{ $received_col->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
