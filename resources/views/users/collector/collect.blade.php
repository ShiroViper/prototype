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
        {{ Form::label('type', 'Type', ['class' => 'h6']) }}
        {{ Form::select('type', [1=>'Deposit', 3=>'Loan Payment'],NULL, ['class' => 'form-control', 'placeholder'=>'Select Type', 'required ']) }}
    </div>
    <div class="form-group">
        {{ Form::label('amount', 'Amount Received', ['class' => 'h6']) }}
        {{ Form::number('amount', '', ['class' => $errors->has('amount') ? 'form-control is-invalid' : 'form-control', 'step' => '0.01', 'min' => 5, 'required']) }}
         @if ($errors->has('amount'))
            <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
        @endif
    </div>
    <div class="form-group">
        {{ Form::label('id', 'Member ID', ['class' => 'h6']) }}
        {{ Form::text('id', null, ['class' => $errors->has('id') ? 'form-control is-invalid' : 'form-control', 'required']) }}
         @if ($errors->has('id'))
            <div class="invalid-feedback">{{ $errors->first('id') }}</div>
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
    item["label"] = tmp[i].lname+", "+tmp[i].fname+" "+tmp[i].mname;
    members.push(item);
}
console.log(members);

// var members = [];
// item = {}
// item["id"] = 123;
// item["name"] = "member";
// members.push(item);
// console.log(members);
</script>
@push('scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ asset('js/scripts.js') }}" defer></script>
    <script src="{{ asset('js/autocomplete.js') }}"></script>
@endpush
@endsection
