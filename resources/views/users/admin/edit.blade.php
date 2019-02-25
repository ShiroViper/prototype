@extends('layouts.app')

@section('title')
<title>Alkansya</title>
@endsection

@section('content')
<h3>View User Information > Edit User</h3>
<div class="row">
    <div class="col-sm col-md-10 col-xl-8">
        {!! Form::open(['action' => ['UsersController@update', $user->id], 'method' => 'POST']) !!}
        <div class="py-3">
            {{-- <a class="btn btn-light" role="button" href="/admin">&lsaquo; Go back to Manage Accounts Table</a> --}}
            <a class="btn btn-danger" role="button" href="/admin/users/{{ $user->id }}">Cancel</a>
            <div class="float-right">
                {{ Form::hidden('_method', 'PUT') }}
                {{ Form::submit('Save Changes', ['class' => 'btn btn-success edit-button']) }}
            </div>
        </div>
        <div class="card">
            <h6 class="card-header">User information</h6>
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
                            {{ Form::text('lname', $user->lname, ['class' => 'form-control']) }}
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">First Name</div>
                        <div class="col font-weight-bold">
                            {{ Form::text('fname', $user->fname, ['class' => 'form-control']) }}
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">Middle Name</div>
                        <div class="col font-weight-bold">
                            {{ Form::text('mname', $user->mname, ['class' => 'form-control']) }}
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">Email</div>
                        <div class="col font-weight-bold">
                            {{ Form::email('email', $user->email, ['class' => 'form-control']) }}
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">Role</div>
                        <div class="col">
                            {{ Form::select('user_type', [0 => 'Member', 1 => 'Collector'], $user->user_type, ['class' => 'form-control']) }}
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">Address</div>
                    </div>
                    <div class="row">
                        <div class="col font-weight-bold">
                            {{ Form::textarea('address', $user->address, ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Street number, Barangay, City/Town, Province, Philippines, Zip Code']) }}
                        </div>
                    </div>
                </li>
            </ul>
            <div class="card-footer text-muted">
                <small>Date Created: {{ $user->created_at }}</small>
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