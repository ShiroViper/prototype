@extends('layouts.app')

@section('title')
<title>Alkansya</title>
@endsection

@section('content')

<h3 class="header mt-2">Collect Payment</h3>
<div class="row">
    <div class="col-6">  
        {!!Form::open(['action'=> 'TransactionController@partial_store', 'method'=>'GET']) !!}
            {{-- @csrf --}}
            {{Form::hidden('token', $token)}}
            <div class="form-group">
                {{ Form::label('date', 'Date', ['class' => 'h6']) }}
                {{ Form::date('',\Carbon\Carbon::now(), ['class' => 'form-control', 'readonly']) }}
            </div>
            <div class="form-group">
                {{ Form::label('memName', 'Member Name', ['class' => 'h6']) }}
                {{ Form::text('memName', '', ['class' => $errors->has('memID') ? 'form-control is-invalid' : 'form-control', 'required' ]) }}
                @if ($errors->has('memID'))
                    <div class="invalid-feedback">Member Not Found</div>
                @endif
                {{-- Never EVER reload! The ID's value will refresh --}}
                <input type="hidden" id="memID" name="memID">
                {{-- {{ Form::hidden('memID', '', array('id' => 'memID') )}} --}}            
                @if ($errors->has('memName'))
                    <div class="invalid-feedback">{{ $errors->first('memName') }}</div>
                @endif
            </div>

            {{ Form::submit('Search Member', ['class' => 'btn btn-primary autocomplete-btn', 'target'=>'_blank']) }}

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
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/autocomplete.js') }}"></script>
@endpush
@endsection
