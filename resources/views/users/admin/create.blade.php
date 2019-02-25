@extends('layouts.app')

@section('title')
<title>Alkansya - Add Member</title>
@endsection

@section('content')
{{-- Gets the last member ID and increments it
or creates a new one depending on the year --}}
@if ( count($users) > 0 )
    @foreach ($users as $user)
        @php
            // Get the last ID number
            $last_id = $user->id;

            $get_year = now()->year - 2000;
            $result = floor($last_id/10000);
            // result in 2 digit number

            // echo $result."<br>"; 
            
            if ( $result == $get_year ) {
                // echo "true";
                $ctr = $result * 10000;
                $result = ($get_year*10000)+(($last_id - $ctr)+1);

            } else {
                // echo "false";
                $result = $get_year*10000;
                $result++;
            }
        @endphp
    @endforeach
@endif
<h3 class="header mt-3">Add Member</h3>
<div class="row">
    <div class="col-sm-10 col-md-7 col-lg-5 my-3">  
        {!! Form::open(['action' => 'UsersController@store', 'method' => 'POST']) !!}
            <div class="form-group">
                {{ Form::label('id', 'Member ID') }}
                {{ Form::text('id', $result, ['class' => 'form-control', 'readonly']) }}
            </div>
            <div class="form-group">
                {{ Form::label('type', 'Account Type') }}
                {{ Form::select('type', [0 => 'Member', 1 => 'Collector'], 0, ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('lname', 'Last Name') }}
                {{ Form::text('lname', '', ['class' => $errors->has('lname') ? 'form-control is-invalid' : 'form-control']) }}
                @if ($errors->has('lname'))
                    <div class="invalid-feedback">{{ $errors->first('lname') }}</div>
                @endif
            </div>
            <div class="form-group">
                {{ Form::label('fname', 'First Name') }}
                {{ Form::text('fname', '', ['class' => $errors->has('fname') ? 'form-control is-invalid' : 'form-control']) }}
                @if ($errors->has('fname'))
                    <div class="invalid-feedback">{{ $errors->first('fname') }}</div>
                @endif
            </div>
            <div class="form-group">
                {{ Form::label('mname', 'Middle Name') }}
                {{ Form::text('mname', '', ['class' => $errors->has('mname') ? 'form-control is-invalid' : 'form-control']) }}
                @if ($errors->has('mname'))
                    <div class="invalid-feedback">{{ $errors->first('mname') }}</div>
                @endif
            </div>
            <div class="form-group">
                {{ Form::label('email', 'Email') }}
                {{ Form::email('email', '', ['class' => $errors->has('email') ? 'form-control is-invalid' : 'form-control']) }}
                @if ($errors->has('email'))
                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                @endif
            </div>
            <div class="form-group">
                {{ Form::label('address', 'Complete Address') }}
                {{ Form::textarea('address', '', ['class' => $errors->has('address') ? 'form-control is-invalid' : 'form-control', 'rows' => 2, 'placeholder' => 'Street number, Barangay, City/Town, Province, Philippines, Zip Code']) }}
                @if ($errors->has('address'))
                    <div class="invalid-feedback">{{ $errors->first('address') }}</div>
                @endif
            </div>
            {{ Form::submit('Create Member', ['class' => 'btn btn-primary']) }}
        {!! Form::close() !!}
    </div>
</div>
@endsection