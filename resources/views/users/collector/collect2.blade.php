@extends('layouts.app')

@section('title')
<title>Alkansya</title>
@endsection

@section('content')

@push('scripts')
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/images.js') }}"></script>
@endpush

<a class="btn btn-light border" role="button" href="/collector/transaction/create"><i class="fas fa-arrow-left mr-2"></i>Go Back</a>

<h3 class="header mt-2 mb-3">Make Transaction</h3>
<div class="row">
    <div class="col-lg order-2 order-lg-1">  
        <div class="position-sticky fixed-top">
            {!!Form::open(['action'=> 'TransactionController@store', 'method'=>'POST']) !!}
            @csrf
            {{Form::hidden('token', $token)}}
            <div class="form-group">
                {{ Form::label('date', 'Date', ['class' => 'h6 mt-3']) }}
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
                    <option selected="selected" value="" disabled hidden>-- Select Type --</option>
                    <option value="1" {{session()->get('loan_type') == 1 ? 'selected' : ''}} >Deposit</option>
                    @if($loan_request)
                        <option value="3" {{session()->get('loan_type') == 3 ? 'selected' : ''}} >Manual Loan Payment</option>
                        <option value="4" {{session()->get('loan_type') == 4 ? 'selected' : ''}} >Full Payment For This Month Loan Balance</option>
                        <option value="5" {{session()->get('loan_type') == 5 ? 'selected' : ''}} >Full Payment The Remaining Loan Balance</option>
                    @endif
                </select>
            </div>
            @if ($errors->has('type'))
                <div class="invalid-feedback">Please Select</div>
            @endif
            <div class="form-group">
                <input type="number" id="full_payment" value="{{$loan_request ? ceil($loan_request->per_month_amount) : ''}}" hidden> 
                <input type="number" id="full_remaining_balance" value="{{$loan_request ? ceil($loan_request->balance) : ''}}" hidden>
                {{ Form::label('amount', 'Amount Received', ['class' => 'h6', 'id'=>'amount_label']) }}
                {{ Form::number('amount', '', ['class'=> 'form-control',  'step' => '1', 'required']) }}
                @if ($errors->has('amount'))
                    <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
                @endif
            </div>

            <div class="form-group">
                <!-- Button trigger modal -->
                {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cameraModal">
                    Launch demo modal
                </button> --}}
                {{ Form::label('', 'Receipt', ['class' => 'h6']) }}
                <div class="img-container text-center mb-5">
                    {{-- <div class="img-placeholder border clickable p-3" data-toggle="modal" data-target="#cameraModal">
                        <span class="h5 text-muted"><i class="fas fa-camera-retro mr-1"></i> Use Camera</span>
                    </div> --}}

                    <div class="row">
                        <div class="col pr-0">
                            <div class="img-camera p-3 clickable h-100" data-toggle="modal" data-target="#cameraModal">
                                <span class="h5 text-muted"><i class="fas fa-camera-retro mr-1"></i> Use Camera</span>
                            </div>
                        </div>
                        <div class="col pl-0 overflow-hidden">
                            <div class="img-file p-3 clickable text-truncate h-100">
                                <span class="h5 text-muted mb-0" id="upload-file-info" >
                                    <i class="far fa-file mr-1"></i> Choose file
                                </span>
                                <input type="file" class="clickable" id="inputFile" onchange="uploadFile(this)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{ Form::submit('Submit', ['class' => 'btn btn-primary btn-block autocomplete-btn', 'target'=>'_blank']) }}

            {!!Form::close()!!}
        </div>
    </div>
    <div class="col-lg my-3 order-1 order-lg-2">
        <div class="card">
            <h6 class="card-header"><i class="far fa-file-alt mr-2"></i> Loan Request Information</h6>
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
                        @if(ceil($loan_request->per_month_amount) < 0)
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
                        @endif
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col col-md col-lg">
                                    Next Payment
                                </div>
                                <div class="col col-md col-lg">
                                    <h6>
                                        {{$loan_request ? (  $loan_request->per_month_to ? date('F d, Y', $loan_request->per_month_to) : '' ) : ''}}
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
                                    <h6> {{ $loan_request ? ($loan_request->per_month_amount >= 0 ? '₱ '. number_format(ceil($loan_request->per_month_amount), 2)  : '₱ 0.00') : '₱ 0.00' }}</h6>
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
        <div class="img-preview my-3 mt-4">
            <div class="row">
                <div class="col">
                    <div class="img-file-container position-relative d-flex justify-content-center">
                        <img id="file" class="img-fluid">
                        <div class="file-name text-truncate"></div>
                    </div>
                    <canvas class="img-fluid" id="canvas" width="640" height="480"></canvas>
                </div>
            </div>
            {{-- <button class="btn btn-primary my-2" onclick="download_image()" id="download">Download</button> --}}
            {{-- <i class="fas fa-compress fa-2x img-view clickable" data-toggle="tooltip" data-placement="left" title="View Image"></i> --}}
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col">
    </div>
</div>
  
<!-- Modal -->
<div class="modal fade" id="cameraModal" tabindex="-1" role="dialog" aria-labelledby="cameraModalTitle" aria-hidden="true">
    <h3 class="text-center text-white header mt-2">Press Enter / Spacebar to capture</h3>
    <div class="modal-dialog mt-2" role="document">
        <div class="modal-content">
            <div class="modal-body d-flex flex-column">
                <div>
                    <small class="text-muted">Change view</small>
                    <select id="videoSource" class="form-control">
                    </select>
                </div>
                <video class="img-fluid mt-3" id="video" muted autoplay></video>
                <div class="text-center">
                    {{-- <button class="btn btn-light m-2 header" id="snap"><i class="fas fa-camera fa-2x mr-2"></i></button> --}}
                    <span id="snap" class="fa-stack fa-2x mt-2 img-capture clickable" data-toggle="tooltip" data-placement="top" title="Take Photo">
                        <i class="fas fa-circle fa-stack-2x fa-inverse"></i>
                        <i class="fas fa-camera fa-stack-1x"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Elements for taking the snapshot
var canvas = document.getElementById('canvas');
var context = canvas.getContext('2d');
var video = document.getElementById('video');
var videoSelect = document.querySelector('select#videoSource');

// Hide canvas/view buttons first
$("#download, #canvas").hide();

// Get access to the camera!
// if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
//     // Not adding `{ audio: true }` since we only want video now
//     navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
//         //video.src = window.URL.createObjectURL(stream);
//         video.srcObject = stream;
//         video.play();
//     });
// }

// Call all functions
navigator.mediaDevices.enumerateDevices()
  .then(gotDevices).then(getStream).catch(handleError);

// Cycle camera and call getStream function
videoSelect.onchange = getStream;

// Get other camera devices through looping
function gotDevices(deviceInfos) {
    for (var i = 0; i !== deviceInfos.length; ++i) {
        var deviceInfo = deviceInfos[i];
        var option = document.createElement('option');
        option.value = deviceInfo.deviceId;

        if (deviceInfo.kind === 'videoinput') {
            option.text = deviceInfo.label || 'camera ' + (videoSelect.length + 1);
            // option.text = 'Camera ' + (videoSelect.length + 1);
            videoSelect.appendChild(option);
        } else {
            console.log('Found one other kind of source/device: ', deviceInfo);
        }
    }
}


function getStream() {
    if(window.stream) {
        window.stream.getTracks().forEach(function(track) {
            track.stop();
        });
    }

    var constraints = {
        video: {
            deviceId: {exact: videoSelect.value}
        }
    };

    navigator.mediaDevices.getUserMedia(constraints).then(gotStream).catch(handleError);
}

function gotStream(stream) {
    window.stream = stream;
    video.srcObject = stream;
}

function handleError(error) {
  console.log('Error: ', error);
}

// Trigger photo take
$("#snap").on("click", function() {
    context.drawImage(video, 0, 0, 640, 480);
    $('#cameraModal').modal('toggle');
    $('#download, #canvas, .img-view').show();
    $('.img-camera').html("<span class='h5 text-muted'><i class='far fa-image mr-1'></i> Take another picture </span>");

    $('.img-file-container').hide();
    $('#canvas').show();

    $(".img-camera, .img-file").removeClass("unselected-option selected-option");
    $(".img-camera").toggleClass("selected-option");
    $(".img-file").toggleClass("unselected-option");
});

// Filename 
var date = new Date();
var filename = date.getMonth()+1+'-'+date.getDate()+'-'+date.getFullYear()+'-'+date.getHours()+date.getMinutes()+date.getSeconds();
// console.log(date, filename);

// Download Image
function download_image(){
    var image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
    var link = document.createElement('a');
    link.download = filename;
    link.href = canvas.toDataURL("image/png;base64");
    link.click();
}

</script>
{{session()->forget('loan_type')}}
@endsection
