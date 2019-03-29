@extends('layouts.app')

@section('title')

<title>Alkansya - {{$user->fname}}'s Profile </title>
@endsection

@section('content')
<div class="row">
    <div class="col-sm col-md-10 col-xl-8">
        <h3 class="header">Update {{$user->fname}}'s Profile</h3>
        {!! Form::open(['action' => ['ProfilesController@update', $user->id], 'method' => 'POST']) !!}
        @csrf
        {{-- <div class="py-3">
            {{Form::hidden('_method', 'PUT')}}
            {{ Form::submit('Save Changes', ['class' => 'btn btn-success edit-button']) }}
            @csrf
            @if(Auth::user()->user_type == 2)
                <a class="btn btn-outline-danger ml-3" role="button" data-toggle="tooltip" data-placement="top" title="Discard Changes" href="/admin/profile"><i class="fas fa-times fa-lg"></i></a>
            @elseif(Auth::user()->user_type == 1)
                <a class="btn btn-outline-danger ml-3" role="button" data-toggle="tooltip" data-placement="top" title="Discard Changes" href="/collector/profile"><i class="fas fa-times fa-lg"></i></a>
            @else
                <a class="btn btn-outline-danger ml-3" role="button" data-toggle="tooltip" data-placement="top" title="Discard Changes" href="/member/profile"><i class="fas fa-times fa-lg"></i></a>
            @endif
        </div> --}}
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="header">User Information</h5>
                    <div class="px-2">
                        @if(Auth::user()->user_type == 2)
                            <a class="btn btn-outline-danger mr-3" role="button" data-toggle="tooltip" data-placement="top" title="Discard Changes" href="/admin/profile"><i class="fas fa-times fa-lg"></i></a>
                        @elseif(Auth::user()->user_type == 1)
                            <a class="btn btn-outline-danger mr-3" role="button" data-toggle="tooltip" data-placement="top" title="Discard Changes" href="/collector/profile"><i class="fas fa-times fa-lg"></i></a>
                        @else
                            <a class="btn btn-outline-danger mr-3" role="button" data-toggle="tooltip" data-placement="top" title="Discard Changes" href="/member/profile"><i class="fas fa-times fa-lg"></i></a>
                        @endif
                        {{Form::hidden('_method', 'PUT')}}
                        {{ Form::submit('Save Changes', ['class' => 'btn btn-success edit-button']) }}
                    </div>
                </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">Member ID</div>
                        <div class="col font-weight-bold">{{ $user->id }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">Last Name</div>
                        <div class="col font-weight-bold">
                            {{ Form::text('lname', $user->lname, ['class' =>  $errors->has('lname') ? 'form-control is-invalid' : 'form-control']) }}
                            @if ($errors->has('lname'))
                                <div class="invalid-feedback">{{ $errors->first('lname') }}</div>
                            @endif{{ $errors->first('email') }}
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">First Name</div>
                        <div class="col font-weight-bold">
                            {{ Form::text('fname', $user->fname, ['class' =>  $errors->has('fname') ? 'form-control is-invalid' : 'form-control']) }}
                            @if ($errors->has('fname'))
                                <div class="invalid-feedback">{{ $errors->first('fname') }}</div>
                            @endif
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">Middle Name</div>
                        <div class="col font-weight-bold">
                            <small class="danger">*Optional</small>
                            {{ Form::text('mname', $user->mname, ['class' =>  $errors->has('mname') ? 'form-control is-invalid' : 'form-control']) }}
                            @if ($errors->has('mname'))
                                <div class="invalid-feedback">{{ $errors->first('mname') }}</div>
                            @endif
                            {{ $errors->first('mname') }}
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">Email</div>
                        <div class="col font-weight-bold">{{ $user->email }}</div>
                        {{ Form::email('email', $user->email, ['class' =>  $errors->has('email') ? 'form-control is-invalid' : 'form-control', 'hidden']) }}
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">Contact Number</div>
                        <div class="col font-weight-bold">
                            <small>*11 Digits</small>
                            {{ Form::number('cell_num', $user->cell_num, ['class' =>  $errors->has('cell_num') ? 'form-control is-invalid' : 'form-control']) }}
                            @if ($errors->has('cell_num'))
                                <div class="invalid-feedback">{{ $errors->first('cell_num') }}</div>
                            @endif
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">Address</div>
                    </div>
                    <div class="row">
                        <div class="col font-weight-bold">
                            {{ Form::textarea('address', $user->address, ['class' =>  $errors->has('address') ? 'form-control is-invalid' : 'form-control', 'rows' => 3, 'placeholder' => 'Street number, Barangay, City/Town, Province, Philippines, Zip Code']) }}
                            @if ($errors->has('address'))
                                <div class="invalid-feedback">{{ $errors->first('address') }}</div>
                            @endif
                        </div>
                    </div>
                </li>
            </ul>
            <div class="card-footer text-muted">
                <small>Account Created: {{date("F d, Y", strtotime($user->created_at))}}  Time: {{date(" h:i:s A", strtotime($user->created_at))}}</small><br>
                <small>Account Updated: {{date("F d, Y", strtotime($user->updated_at))}}  Time: {{date(" h:i:s A", strtotime($user->updated_at))}}</small>
            </div>
        </div>
        {{-- <div class="row mt-3">
            <div class="col">
                <a class="btn btn-danger btn-block" role="button" href="/admin/users/{{ $user->id }}">Cancel</a>
            </div>
            <div class="col">
                {{ Form::hidden('_method', 'PUT') }}
                {{ Form::submit('Update', ['class' => 'btn btn-primary btn-block edit-button']) }}
            </div>
        </div> --}}
        {!! Form::close() !!}
    </div>
</div>
@endsection