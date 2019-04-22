@extends('layouts.app')

@section('title')
<title>Collector's Status</title>
@endsection

@section('content')

<h3 class="header mt-2">Status as of {{date('F d, Y', strtotime(NOW()))}}</h3>
<div class="row">
    <div class="col-4">  
        Member Deposits <br>
        ₱ {{number_format($deposit, 2)}}
        {{-- {{dd($loan_from_member)}} --}}
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

</div>    
<small class="badge badge-pill badge-info shadow border py-2 float-right" data-toggle="tooltip" data-placement="top" title="Shows a list of member ready to transfer of the loan money from the request"><span class="h6"><i class="fa fa-question-circle fa-lg" aria-hidden="true"></i></small>
    <div class="row pt-3">
        <div class="col">
            <div class="card">
                {{-- shows a list of money ready for transfer in loan request --}}
                <h6 class="card-header float-left">Ready to transfer from member</h6>
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

@push('scripts')
    <script src="{{asset ('js/scripts.js')}} "></script>
@endpush
@endsection
