@extends('layouts.app')

@section('title')
<title>Alkansya</title>
@endsection

@section('content')

@push('scripts')
    <script src="{{ asset('js/scripts.js') }}"></script>
@endpush

<a class="btn btn-light border" role="button" href="/collector/transaction/create"><i class="fas fa-arrow-left"></i>  Back to list</a>
<h3 class="header mt-2">Collect Payment</h3>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary mb-5" data-toggle="modal" data-target="#cameraModal">
    Launch demo modal
</button>

<div class="row">
    <div class="col-md-6">  
        {!!Form::open(['action'=> 'TransactionController@store', 'method'=>'POST']) !!}
            @csrf
            {{Form::hidden('token', $token)}}
            <div class="form-group">
                {{ Form::label('date', 'Date', ['class' => 'h6']) }}
                {{ Form::date('date',\Carbon\Carbon::now(), ['class' => 'form-control', 'readonly']) }}
            </div>
            <div class="form-group">
                {{-- {{ Form::label('memName', 'Member Name', ['class' => 'h6']) }}
                {{ Form::text('memName', $member->lname.', '. $member->fname.' '. $member->mname , ['class' => $errors->has('memID') ? 'form-control is-invalid' : 'form-control', 'required', 'readonly' ]) }} --}}
                @if ($errors->has('memID'))
                    <div class="invalid-feedback">Member Not Found</div>
                @endif
                {{-- Never EVER reload! The ID's value will refresh --}}
                {{-- <input type="hidden" id="memID" name="memID"> --}}
                {{Form::hidden('memID', $member->id)}}
                {{-- {{ Form::hidden('memID', '', array('id' => 'memID') )}} --}}            
                @if ($errors->has('memName'))
                    <div class="invalid-feedback">{{ $errors->first('memName') }}</div>
                @endif
            </div>

            <div class="form-group">
                {{ Form::label('type', 'Type', ['class' => 'h6']) }}
                {{-- {{ Form::select('type', [1 => 'Deposit', 3 => 'Loan Payment'], NULL, ['class' => 'form-control', 'required']) }} --}}
                <select name="type" id="type" class="{{$errors->has('type') ? 'form-control is-invalid' : 'form-control'}} " required>
                    <option selected="selected" value="null" hidden>-- Select Type --</option>
                    <option value="1">Deposit</option>
                    <option value="3">Loan Payment</option>
                </select>
            </div>
            @if ($errors->has('type'))
                <div class="invalid-feedback">Please Select</div>
            @endif

            <div class="form-group">
                {{ Form::label('amount', 'Amount Received', ['class' => 'h6']) }}
                {{ Form::number('amount', old('amount'), ['class'=> 'form-control', 'step' => '0.01', 'required']) }}
                @if ($errors->has('amount'))
                    <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
                @endif
            </div>

        {{-- <div class="form-group">
            {{ Form::label('member', 'Member Name') }}
            {{ Form::text('member', null, ['class' => 'form-control', 'readonly']) }}
        </div> --}}

            {{ Form::submit('Submit Payment', ['class' => 'btn btn-primary autocomplete-btn', 'target'=>'_blank']) }}

        {!!Form::close()!!}
    </div>
    <div class="col-lg my-3 offset-lg-1">
        <div class="card">
            <h6 class="card-header">Loan Request Information</h6>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col col-md col-lg">
                            <span>Member Name</span>
                        </div>
                        <div class="col col-md col-lg">
                            <h6>{{ $member->lname }}, {{$member->fname}} {{$member->mname}} </h6>
                        </div>
                    </div>
                </li>
                {{-- {{dd($loan_request ? $loan_request->paid_using_savings != null : '', $loan)}} --}}
                @if($loan_request ? !$loan_request->paid_using_savings != null : '')
                {{-- {{dd(date('F d, Y', strtotime($loan_request->per_month_from)))}} --}}
                    @if($loan_request->per_month_amount <= 0)
                    {{-- {{dd($loan_request)}} --}}
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col col-md col-lg">
                                    Remaining Loan Balance
                                </div>
                                <div class="col col-md col-lg">
                                    <h6>₱ {{ number_format(ceil($loan_request->balance), 2) }}</h6>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            {{-- <div class="row">
                                <div class="col col-md col-lg">
                                    Loan payment this month
                                </div>
                                <div class="col col-md col-lg">
                                    <h6>₱ {{ round(($loan_request->loan_amount * 0.06  * $loan_request->days_payable + $loan_request->loan_amount) / $loan_request->days_payable, 2) - abs($loan_request->per_month_amount) }} </h6>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col col-md col-lg">
                                    <small class="text-muted">Over Payment</small>
                                </div>
                                <div class="col col-md col-lg">
                                    <small class="text-muted"><h6>₱ {{ number_format(round(abs($loan_request->per_month_amount)), 2) }} </h6></small>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col col-md col-lg">
                                    Duration
                                </div>
                                <div class="col col-md col-lg">
                                    <h6>
                                        {{ $loan_request ? (  $loan_request->per_month_amount ? date('F d, Y', $loan_request->per_month_from) : '' ) : ''}}  to {{$loan_request ? (  $loan_request->per_month_to ? date('F d, Y', $loan_request->per_month_to) : '' ) : ''}}
                                    </h6>
                                </div>
                            </div>
                        </li>
                    @else
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col col-md col-lg">
                                    Loan payment this month
                                </div>
                                <div class="col col-md col-lg">
                                    <h6> {{ $loan_request ? ($loan_request->per_month_amount >= 0 ? '₱ '. $loan_request->per_month_amount  : '₱ 0.00') : '₱ 0.00' }}</h6>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col col-md col-lg">
                                    Duration
                                </div>
                                <div class="col col-md col-lg">
                                    <h6>
                                        {{ $loan_request ? (  $loan_request->per_month_amount ? date('F d, Y', $loan_request->per_month_from) : '' ) : ''}}  to {{$loan_request ? (  $loan_request->per_month_to ? date('F d, Y', $loan_request->per_month_to) : '' ) : ''}}
                                    </h6>
                                </div>
                            </div>
                        </li>
                    @endif
                @else
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col col-md col-lg">
                                Loan
                            </div>
                            <div class="col col-md col-lg">
                               <h6> No Current Loan</h6>
                            </div>
                        </div>
                    </li>
                @endif
                <li class="list-group-item">
                    <div class="row">
                        <div class="col col-md col-lg">
                            <span>Pending Confirmation</span>
                        </div>
                        <div class="col col-md col-lg">
                            @if(count($check_for_pending) > 0)
                                @foreach($check_for_pending as $pending)
                                <h6> {{$pending ? ($pending->trans_type == 1 ? ('Deposit :  ₱ '. $pending->amount) : '') : ''}} </h6>
                                <h6> {{$pending ? ($pending->trans_type == 3 ? ('Loan Payment : ₱ '. $pending->amount) : '') : ''}} </h6>
                                @endforeach
                            @else
                                <h6> No Current Pending Transaction</h6>
                            @endif
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col">
        <canvas id="canvas" width="640" height="480"></canvas>
        <button onclick="download_image()" id="download">Download</a>
    </div>
</div>
  
<!-- Modal -->
<div class="modal fade" id="cameraModal" tabindex="-1" role="dialog" aria-labelledby="cameraModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="cameraModalTitle">Take a Picture</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col text-center">
                    <video id="video" width="640" height="480" autoplay></video>
                </div>
                <div class="col-12 text-center">
                    <button id="snap">Snap Photo</button>
                </div>
            </div>
                
        </div>
        <div class="modal-footer">
            {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button> --}}
        </div>
        </div>
    </div>
</div>

<script>
// Elements for taking the snapshot
var canvas = document.getElementById('canvas');
var context = canvas.getContext('2d');
var video = document.getElementById('video');
// Hide download button first
$("#download").hide();

// Get access to the camera!
if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    // Not adding `{ audio: true }` since we only want video now
    navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
        //video.src = window.URL.createObjectURL(stream);
        video.srcObject = stream;
        video.play();
    });
}

// Filename 
var date = new Date();
var output = date.getMonth()+1+'-'+date.getDate()+'-'+date.getFullYear()+'-'+date.getHours()+date.getMinutes()+date.getSeconds();
// console.log(date, output);

// Download Image
function download_image(){
    var image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
    var link = document.createElement('a');
    link.download = output;
    link.href = canvas.toDataURL("image/png;base64");
    link.click();
}

// Trigger photo take
$("#snap").on("click", function() {
    context.drawImage(video, 0, 0, 640, 480);
    $('#cameraModal').modal('toggle');
    $('#download').show();
});

</script>

@endsection
