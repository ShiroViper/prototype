@extends('layouts.app')

@section('title')
<title>Alkansya - Requests</title>
@endsection

@section('content')

<h3 class="header mt-2">Requests</h3>
<input type="hidden" value="{{$token}} ">

@if(count($pending_to_mem) > 0)
    <div class="row pt-3">
        <div class="col">
            <div class="card">
                <h6 class="card-header">Waiting confirmation From the Member</h6>
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-hover mt-3">
                            <thead>
                                <tr>
                                    <th>Sending Date&Time</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pending_to_mem as $item)
                                        <tr>
                                        <td>{{date('h:i A F, d Y', strtotime($item->updated_at)) }} </td>
                                        <td>{{ $item->lname.', '. $item->fname. ' '.$item->mname }}</td>
                                        <td>₱ {{ number_format($item->loan_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $pending_to_mem->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if(count($pending_col) > 0)
    <div class="row pt-3">
        <div class="col">
            <div class="card">
                <h6 class="card-header">Pending Money From Admin</h6>
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-hover mt-3">
                            <thead>
                                <tr>
                                    <th>Money Received</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($pending_col) > 0)
                                    @foreach ($pending_col as $item)
                                        @if($item->transfer == 1)
                                            {{-- <tr data-toggle="modal" data-target="#LoanModal"> --}}
                                            <tr >
                                                <td>₱ {{ number_format($item->loan_amount, 2) }}</td>
                                                <td class="d-flex flex-row">
                                                    <a class="btn btn-outline-primary mx-2 no-modal" role="button" href="/collector/receive/{{ $item->request_id }}/{{$token}}/accept">Accept</a>
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
                                    <th>Type</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($confirmed) > 0)
                                    @foreach ($confirmed as $item)
                                        <tr>
                                            <td>{{ $item->lname.', '. $item->fname. ' '. $item->mname }}</td>
                                            <td>{{$item->trans_type == 1 ? 'Deposit' : 'Loan Payment'}} </td>
                                            <td>₱ {{ number_format($item->amount, 2) }}</td>
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
            <h6 class="card-header">Transfer Loan Money to Member</h6>
            <div class="container">
                <div class="table-responsive">
                    <table class="table table-hover mt-3">
                        <thead>
                            <tr>
                                <th>Received Date&Time</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($received_col) > 0)
                                @foreach ($received_col as $item)
                                     <tr>
                                        <td>{{date('h:i A F d, Y', strtotime($item->updated_at)) }} </td>
                                        <td>{{ $item->lname.', '. $item->fname. ' '.$item->mname }}</td>
                                        <td>₱ {{ number_format($item->loan_amount, 2) }}</td>
                                        <td><a class="btn btn-outline-primary mx-2 no-modal" role="button" href="/collector/receive/{{ $item->request_id }}/{{$token}}/accept">Transfer</a></td>
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

@push('scripts')
    <script src="{{ asset('js/scripts.js') }}"></script>
@endpush
@endsection
