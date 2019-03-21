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
<h3 class="header mt-2">Add Member</h3>
    <div class="row">
        <div class="col-lg col-xl-10 m-3">  
            {!! Form::open(['action' => 'UsersController@store', 'method' => 'POST']) !!}
            @csrf
            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        {{ Form::label('id', 'Member ID', ['class' => 'h6']) }}
                        {{ Form::text('id', $result, ['class' => 'form-control', 'readonly']) }}
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        {{ Form::label('type', 'Account Type', ['class' => 'h6']) }}
                        {{ Form::select('type', [0 => 'Member', 1 => 'Collector'], 0, ['class' => 'form-control']) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg">
                    <div class="form-group">
                        {{ Form::label('lname', 'Last Name', ['class' => 'h6']) }}
                        {{ Form::text('lname', '', ['class' => $errors->has('lname') ? 'form-control is-invalid' : 'form-control']) }}
                        @if ($errors->has('lname'))
                            <div class="invalid-feedback">{{ $errors->first('lname') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-lg">
                    <div class="form-group">
                        {{ Form::label('fname', 'First Name', ['class' => 'h6']) }}
                        {{ Form::text('fname', '', ['class' => $errors->has('fname') ? 'form-control is-invalid' : 'form-control']) }}
                        @if ($errors->has('fname'))
                            <div class="invalid-feedback">{{ $errors->first('fname') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-lg">
                    <div class="form-group">
                        {{ Form::label('mname', 'Middle Name', ['class' => 'h6']) }}
                        {{ Form::text('mname', '', ['class' => $errors->has('mname') ? 'form-control is-invalid' : 'form-control']) }}
                        @if ($errors->has('mname'))
                            <div class="invalid-feedback">{{ $errors->first('mname') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        {{ Form::label('email', 'Email', ['class' => 'h6']) }}
                        {{ Form::email('email', '', ['class' => $errors->has('email') ? 'form-control is-invalid' : 'form-control']) }}
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        {{ Form::label('cell_num', 'Cellphone Number', ['class' => 'h6']) }}
                        {{ Form::text('cell_num', '', ['class' => $errors->has('cell_num') ? 'form-control is-invalid' : 'form-control']) }}
                        @if ($errors->has('cell_num'))
                            <div class="invalid-feedback">{{ $errors->first('cell_num') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        {{ Form::label('address', 'Complete Address', ['class' => 'h6 mb-0']) }}
                        <div class="mb-2"><small class="text-muted">Street number, Barangay, City/Town, Province, Philippines, Zip Code</small></div>
                        {{ Form::textarea('address', '', ['class' => $errors->has('address') ? 'form-control is-invalid' : 'form-control', 'rows' => 2]) }}
                        @if ($errors->has('address'))
                            <div class="invalid-feedback">{{ $errors->first('address') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-lg-center">
                {{ Form::submit('Create Member', ['class' => 'btn btn-primary align-content-center px-3']) }}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
        