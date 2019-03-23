@extends('layouts.app')

@section('title')
<title>Loan Process</title>
@endsection

@section('content')

<h3>Loan Process: Transfer Money</h3>
<div class="row">
    <div class="col-sm-10 col-md-7 col-lg-5 my-3">  
      {!!Form::open(['action'=> 'ProcessController@store', 'method'=>'POST']) !!}
      @csrf
    {{-- Request ID --}}
    {{ Form::text('request_id', $process->id, ['hidden']) }}
      
    @if($trans != NULL)
        @if($trans->transfer == 1)
            <div class="form-group">
                <label>Date Sent</label>      
                <div class="col font-weight-bold">{{ $trans->updated_at }}</div>       
            </div>
            <div class="alert alert-success  text-center " role="alert">
                <strong>Transferring Money</strong>
            </div>
        @elseif($trans->transfer >= 2)
            <div class="form-group">
                <label>Date Transferred</label>      
                <div class="col font-weight-bold">{{ $trans->updated_at }}</div>       
            </div>
            <div class="alert alert-success  text-center " role="alert">
                @if($trans->transfer == 2)
                    <strong>Money Transferred to Collector </strong>
                @elseif($trans->transfer == 4)
                    <strong>Money Transferred to Member </strong>
                @endif
            </div>
        @endif 
    @endif
    @if($trans == NULL)
        <div class="form-group">
            {{ Form::label('date', 'Date') }}
            {{ Form::date('date',\Carbon\Carbon::now(), ['class' => 'form-control', 'readonly']) }}
        </div>
        <div class="form-group">
            {{ Form::label('id', 'Collector ID') }}
            {{ Form::text('id', '', ['class' => $errors->has('id') ? 'form-control is-invalid' : 'form-control']) }}
            @if ($errors->has('id'))
                <div class="invalid-feedback">{{ $errors->first('id') }}</div>
            @endif
        </div>
        {{ Form::submit('Transfer to Collector', ['class' => 'btn btn-primary']) }}
    @endif
    
    <br><hr>
    <div class="form-group">
        <label>Member Name</label>      
        <div class="col font-weight-bold">{{ $user->lname }}, {{$user->fname}} {{$user->mname}} </div>       
    </div>
    <div class="form-group">
        <label>Amount to Loan</label>      
        <div class="col font-weight-bold">{{ $process->loan_amount }}</div>       
    </div>
    <div class="form-group">
        <label>Days Payable</label>      
        <div class="col font-weight-bold">{{ $process->days_payable }}</div>       
    </div>
    {{-- <div class="form-group">
        {{ Form::label('member', 'Member Name') }}
        {{ Form::text('member',, ['class' => 'form-control', 'readonly']) }}
    </div> --}}


      {!!Form::close()!!}
    </div>
</div>
<script>
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
        // collector.push(object[0]+", "+object[1]+" "+object[2]+" "+object[3]);
        // for (var property in object) {
        //     // skip loop if the property is from prototype
        //     if (!object.hasOwnProperty(property)) continue;

        //     console.log(property + " = " + object[property]);
        // }
        console.log("value"+ collectors[key]);
    }

    // Fron PHP to JSON 
    // var collect = @json($collectors);
    // console.log(collect);

    //  var collect = {!! json_encode($collectors) !!};
    // var stringified = JSON.stringify(collect);
    // var obj = jQuery.parseJSON(stringified);

    // Looping inside an Javascript object 
    // for (var key in collectors) {
    //     // skip loop if the property is from prototype
    //     if (!collectors.hasOwnProperty(key)) continue;

    //     var object = collectors[key];
    //     for (var property in object) {
    //         // skip loop if the property is from prototype
    //         if (!object.hasOwnProperty(property)) continue;

    //         console.log(property + " = " + object[property]);
    //     }
    // }

</script>
@endsection
