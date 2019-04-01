@extends('layouts.app')

@section('title')
<title>Loan Process</title>
@endsection

@section('content')

<h3 class="header mt-2">Loan Process: Transfer Money</h3>


<a class="btn btn-light border" role="button" href="/admin/requests "><i class="fas fa-arrow-left"></i> Back to list</a>

<div class="row">
    <div class="col-lg mt-3">
        {!!Form::open(['action'=> 'ProcessController@store', 'method'=>'POST']) !!}
        @csrf
            {{-- Request ID --}}
            {{ Form::text('request_id', $process->id, ['hidden']) }}
            @if($trans != NULL)
                @if($trans->transfer == 1)
                    <div class="progress progress-bar-vertical">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="height: 33%;">
                        <span class="sr-only">33% Complete</span>
                        </div>
                    </div>
                    <div class="progress-cards card my-2 note-box border-success">
                        <div class="card-body d-flex justify-content-center flex-row align-items-center text-success">
                            <i class="fas fa-check fa-lg"></i>
                            <div class="h5 mb-0 ml-3 header">Transferring Money </div>      
                        </div>
                    </div>
                    <div class="progress-cards card text-muted my-2" aria-disabled="true">
                        <div class="card-body d-flex justify-content-center">
                            <div class="h5 mb-0 text-center header">Money Transferred to Collector</div>      
                        </div>
                    </div>
                    <div class="progress-cards card text-muted my-2" aria-disabled="true">
                        <div class="card-body d-flex justify-content-center">
                            <div class="h5 mb-0 text-center header">Money Transferred to Member </div>      
                        </div>
                    </div>
                @elseif($trans->transfer >= 2)
                        @if($trans->transfer == 2 || $trans->transfer == 3)
                            <div class="progress progress-bar-vertical">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100" style="height: 66%;">
                                <span class="sr-only">66% Complete</span>
                                </div>
                            </div>
                            <div class="progress-cards card note-box border-success my-2">
                                <div class="card-body d-flex justify-content-center flex-row align-items-center text-success">
                                    <i class="fas fa-check fa-lg"></i>
                                    <div class="h5 mb-0 ml-3 header">Transferring Money </div>      
                                </div>
                            </div>
                            <div class="progress-cards card note-box border-success my-2">
                                <div class="card-body d-flex justify-content-center flex-row align-items-center text-success">
                                    <i class="fas fa-check fa-lg"></i>
                                    <div class="h5 mb-0 ml-3 header">Money Transferred to Collector</div>      
                                </div>
                            </div>
                            <div class="progress-cards card text-muted my-2" aria-disabled="true">
                                <div class="card-body d-flex justify-content-center">
                                    <div class="h5 mb-0 text-center header">Money Transferred to Member </div>      
                                </div>
                            </div>
                        @elseif($trans->transfer == 4)
                            <div class="progress progress-bar-vertical">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="height: 100%;">
                                <span class="sr-only">100% Complete</span>
                                </div>
                            </div>
                            <div class="progress-cards card note-box border-success my-2">
                                <div class="card-body d-flex justify-content-center flex-row align-items-center text-success">
                                    <i class="fas fa-check fa-lg"></i>
                                    <div class="h5 mb-0 ml-3 header">Transferring Money </div>      
                                </div>
                            </div>
                            <div class="progress-cards card note-box border-success my-2">
                                <div class="card-body d-flex justify-content-center flex-row align-items-center text-success">
                                    <i class="fas fa-check fa-lg"></i>
                                    <div class="h5 mb-0 ml-3 header">Money Transferred to Collector</div>      
                                </div>
                            </div>
                            <div class="progress-cards card note-box border-success my-2">
                                <div class="card-body d-flex justify-content-center flex-row align-items-center text-success">
                                    <i class="fas fa-check fa-lg"></i>
                                    <div class="h5 mb-0 ml-3 header">Money Transferred to Member</div>
                                </div>
                            </div>
                        @endif
                @endif 
            @endif
            @if($trans == NULL)
                <h4>Select a collector</h4>
                <div class="form-group">
                    {{ Form::label('date', 'Date', ['class' => 'h6']) }}
                    {{ Form::date('date',\Carbon\Carbon::now(), ['class' => 'form-control', 'readonly']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('colName', 'Collector Name', ['class' => 'h6']) }}
                    {{ Form::text('colName', '', ['class' => $errors->has('colName') ? 'form-control is-invalid' : 'form-control']) }}
                    <input type="hidden" id="colID" name="colID" value="{{ old('colID') }}">
                    @if ($errors->has('id'))
                        <div class="invalid-feedback">{{ $errors->first('id') }}</div>
                    @endif
                </div>
                {{ Form::submit('Transfer to Collector', ['class' => 'btn btn-primary']) }}
            @endif
        {!!Form::close()!!}
    </div>
    <div class="col-lg my-3 offset-lg-1">
        <div class="card">
            <h6 class="card-header">Loan Request Information
                @if($trans != null ? $trans->transfer == 4 : '')
                    <span class="badge badge-success"><small>Money Transferred</small></span>
                @endif
            </h6>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col col-md col-lg-4">
                            <span>Member Name</span>
                        </div>
                        <div class="col col-md col-lg">
                            <h6>{{ $user->lname }}, {{$user->fname}} {{$user->mname}} </h6>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col col-md col-lg-4">
                            <span>Amount to Loan</span>
                        </div>
                        <div class="col col-md col-lg">
                            <h6>{{ $process->loan_amount }}</h6>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col col-md col-lg-4">
                            <span>Months Payable</span>
                        </div>
                        <div class="col col-md col-lg">
                            <h6>{{ $process->days_payable }}</h6>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
    var tmp = @json($collectors);
    var collectors = [];
    for (var i = 0; i < tmp.length; i++) {
        // alert(tmp[i].id);
        item = {}
        item["value"] = tmp[i].id;
        if (tmp[i].mname == null) {
            item["label"] = tmp[i].lname+", "+tmp[i].fname;
        } else {
            item["label"] = tmp[i].lname+", "+tmp[i].fname+" "+tmp[i].mname;
        }

        collectors.push(item);
    }
    // console.log(collectors);

    /*
    var collectors = [];
    @foreach ($collectors as $collector)
        collectors.push(
            ['{!! $collector->id !!}', '{!! $collector->lname !!}', '{!! $collector->fname !!}', '{!! $collector->mname !!}']
        );
    @endforeach
    console.log(collectors);

    var collector = [];
    for (var key in collectors) {
        // skip loop if the property is from prototype
        if (!collectors.hasOwnProperty(key)) continue;
        var object = collectors[key];
        collector.push(object[0]+", "+object[1]+" "+object[2]+" "+object[3]);
        for (var property in object) {
            // skip loop if the property is from prototype
            if (!object.hasOwnProperty(property)) continue;

            console.log(property + " = " + object[property]);
        }
        console.log("value"+ collectors[key]);
    }

    Fron PHP to JSON 
    var collect = @json($collectors);
    console.log(collect);
     var collect = {!! json_encode($collectors) !!};
    var stringified = JSON.stringify(collect);
    var obj = jQuery.parseJSON(stringified);

    Looping inside an Javascript object 
    for (var key in collectors) {
        // skip loop if the property is from prototype
        if (!collectors.hasOwnProperty(key)) continue;

        var object = collectors[key];
        for (var property in object) {
            // skip loop if the property is from prototype
            if (!object.hasOwnProperty(property)) continue;

            console.log(property + " = " + object[property]);
        }
    }
    */
</script>

@push('scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ asset('js/scripts.js') }}" defer></script>
    <script src="{{ asset('js/autocomplete.js') }}"></script>
@endpush

@endsection
