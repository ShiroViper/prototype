@extends('layouts.dashboard')

@section('title')
<title>Collector's Status</title>
@endsection

@section('content')

<div class="container">
    {{-- <h3 class="header mt-2">Status as of {{date('F d, Y', strtotime(NOW()))}}</h3> --}}
    <div class="card-deck pb-4 mt-3">
        <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-center align-items-center flex-column">
                <div class="border rounded-circle p-2 bg-light"><i class="text-indigo fas fa-money-bill-alt fa-lg"></i></div>
                <h5 class="pt-2 header display-5 font-weight-bold text-center">
                    {{ '₱'.number_format($deposit, 2) }}
                </h5>
                <small>Member Deposits</small>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-center align-items-center flex-column">
                <div class="border rounded-circle p-2 bg-light"><i class="text-orange fas fa-hand-holding-usd fa-lg"></i></div>
                <h5 class="pt-2 header display-5 font-weight-bold text-center">
                    {{ '₱'.number_format($loan_payment, 2) }}
                </h5>
                <small>Member Loan Payments</small>
            </div>
        </div>
    </div>

    <div class="row pb-5">
        <div class="col-sm d-flex justify-content-center">
            @if($turn_over)
                @if ($turn_over->confirmed == 2 )
                    <span class="display-4">Money Transferred</span>
                @else
                    <button class="btn btn-primary btn-block w-50 p-3" type="button" disabled>
                        <span class="h4">Pending confirmation from Admin</span>
                    </button>
                @endif
            @elseif(count($trans) > 0)
                @if($trans[$count]->turn_over == null)
                    <a class="btn btn-primary btn-block w-50 p-3" href="/collector/{{$token}}/transfer/money" role="button" data-toggle="tooltip" data-placement="top" title="Transfer all money gathered from collector to admin"><span class="h4">Transfer Money</span></a>
                @endif
            @endif
        </div>
    </div>
</div>

    {{-- <h3 class="header mt-2">Status as of {{date('F d, Y', strtotime(NOW()))}}</h3>
    <div class="row">
        <div class="col-4">  
            Member Deposits <br>
            ₱ {{ !$deposit ? number_format($deposit, 2) : '0'}}
        </div>
        <div class="col-4">  
            Member Loan Payments <br>
            ₱ {{number_format($loan_payment, 2)}}
        </div>
        @if($turn_over)
            @if ($turn_over->confirmed == 2 )
                Money Transferred    
            @else
                Pending Confirmation from the admin
            @endif
        @elseif(count($trans) > 0)
            @if($trans[$count]->turn_over == null)
                <a href="/collector/{{$token}}/transfer/money"><button class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Transfer all money gathered from collector to admin">Transfer </button></a>
            @endif
        @endif
    </div> --}}

<div class="dashboard-notif-area py-3">
    <div class="container">
        <div class="row pt-3">
            <div class="col">
                <div class="h5 header text-primary">
                    Ready to transfer from member
                    <i class="fa fa-question-circle fa-sm ml-3 text-primary" data-toggle="tooltip" data-placement="top" title="Shows a list of member ready to transfer the loan money"></i>
                </div>
                <div class="card shadow-sm">
                    {{-- shows a list of money ready for transfer in loan request --}}
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
                                    @if (count($process) > 0)
                                        @foreach ($process as $item)
                                                <tr>
                                                <td>{{ $item->lname.', '. $item->fname. ' '.$item->mname }}</td>
                                                <td>₱ {{ number_format($item->loan_amount, 2) }}</td>
                                                {{-- <td><a class="btn btn-outline-primary mx-2 no-modal" role="button" href="/collector/receive/{{ $item->request_id }}/{{$token}}/accept">Transfer</a></td> --}}
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
                            {{ $process->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script src="{{asset ('js/scripts.js')}} "></script>
@endpush
@endsection
