@extends('layouts.app')

@section('title')
<title>Alkansya</title>
@endsection

@section('content')

<h3 class="header mt-2">Collect Payment</h3>
<div class="row">
    <div class="col-sm-10 col-md-7 col-lg-5 my-3">  
    {!!Form::open(['action'=> 'TransactionController@store', 'method'=>'POST']) !!}
        @csrf
        {{Form::hidden('token', $token)}}
        <div class="form-group">
            {{ Form::label('date', 'Date', ['class' => 'h6']) }}
            {{ Form::date('date',\Carbon\Carbon::now(), ['class' => 'form-control', 'readonly']) }}
        </div>
        <div class="form-group">
            {{ Form::label('memName', 'Member Name', ['class' => 'h6']) }}
            {{ Form::text('memName', old('memName'), ['class' => $errors->has('memName') ? 'form-control is-invalid' : 'form-control', 'required']) }}

            {{-- Never EVER reload! The ID's value will refresh --}}
            <input type="hidden" id="memID" name="memID" value="{{ old('memID') }}">
            {{-- {{ Form::hidden('memID', '', array('id' => 'memID') )}} --}}
            
            @if ($errors->has('memName'))
                <div class="invalid-feedback">{{ $errors->first('memName') }}</div>
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('type', 'Type', ['class' => 'h6']) }}
            {{-- {{ Form::select('type', [1 => 'Deposit', 3 => 'Loan Payment'], NULL, ['class' => 'form-control', 'required']) }} --}}
            <select name="type" id="type" class="form-control" required>
                <option selected="selected" hidden>-- Select Type --</option>
                <option value="1">Deposit</option>
                <option value="3">Loan Payment</option>
            </select>
        </div>
        <div class="form-group">
            {{ Form::label('amount', 'Amount Received', ['class' => 'h6']) }}
            {{ Form::number('amount', old('amount'), ['class' => $errors->has('amount') ? 'form-control is-invalid' : 'form-control', 'step' => '0.01', 'min' => 5, 'required']) }}
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
</div>
<script>
var tmp = @json($members);
var members = [];
for (var i = 0; i < tmp.length; i++) {
    // alert(tmp[i].id);
    item = {}
    item["value"] = tmp[i].id;
    if (tmp[i].mname == null) {
        item["label"] = tmp[i].lname+", "+tmp[i].fname;
    } else {
        item["label"] = tmp[i].lname+", "+tmp[i].fname+" "+tmp[i].mname;
    }

    members.push(item);
}
// console.log(members);

// var members = [];
// item = {}
// item["id"] = 123;
// item["name"] = "member";
// members.push(item);
// console.log(members);
</script>
@push('scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/autocomplete.js') }}"></script>
@endpush
@endsection
