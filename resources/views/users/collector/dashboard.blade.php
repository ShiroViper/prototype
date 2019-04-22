@extends('layouts.app')

@section('title')
    <title>Transactions</title>
@endsection

@section('content')
@push('scripts')
    <script src="{{ asset('js/scripts.js') }}"></script>
@endpush
<div class="row">
    <div class="col">
        <h3 class="header mt-2">Transactions</h3>
        <div class="table-responsive">
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Date & Time</th>
                        <th>Member </th>
                        <th colspan="2">Amount</th>
                        {{-- <th>PDF</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @if (count($transactions) > 0)
                        @foreach ($transactions as $trans)
                        <tr>
                            @if($trans->trans_type == 1)
                                <td>Deposit</td>
                            @else
                                <td>Loan Payment</td>
                            @endif
                            <td>{{date("h:i A M d, Y", strtotime($trans->created_at))}}</td>
                            <td>{{$trans->lname}}, {{$trans->fname}} {{$trans->mname}} </td>
                            <td>₱{{ $trans->amount }}</td>
                            <td class="text-right border-left-0">
                                <a class="btn btn-outline-secondary btn-sm" role="button" href="/collector/transaction/{{ Crypt::encrypt($trans->id) }}/generate" data-toggle="tooltip" data-placement="top" title="Download PDF">
                                    <i class="fas fa-download"></i>
                                </a>
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
            {{ $transactions->links() }}
        </div>
    </div>
</div>

@endsection