@extends('layouts.app')

@section('title')
<title>Alkasnya - View user</title>
@endsection

@section('content')
<h3 class="header mt-3">View {{$user->lname}}'s Information</h3>
<div class="row">
    <div class="col-sm col-md-10 col-xl-8">
        <div class="py-3 d-flex justify-content-between align-items-center">
            <a class="btn btn-light border" role="button" href="/admin/users"><i class="fas fa-arrow-left"></i>  Back to list</a>
            <div class="float-right">
                <a href="/admin/users/{{ $user->id }}/edit" class="btn btn-success btn-block edit-button" role="button" data-toggle="tooltip" data-placement="top" title="Edit User"><i class="fas fa-user-edit fa-lg"></i></a>
            </div>
            {{-- <div class="float-right mr-2">
                <button class="btn btn-outline-secondary border" type="button" title="Archive"><i class="fas fa-archive"></i></button>
            </div> --}}
        </div>
        <div class="card">
            <h6 class="card-header">User information</h6>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col col-sm">Member ID</div>
                        <div class="col font-weight-bold">{{ $user->id }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col col-sm">Last Name</div>
                        <div class="col font-weight-bold">{{ $user->lname }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col col-sm">First Name</div>
                        <div class="col font-weight-bold">{{ $user->fname }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col col-sm">Middle Name</div>
                        <div class="col font-weight-bold">{{ $user->mname }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col col-sm">Email</div>
                        <div class="col font-weight-bold">{{ $user->email }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col col-sm">Cell Number</div>
                        <div class="col font-weight-bold">{{ $user->cell_num }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col col-sm">Role</div>
                        <div class="col font-weight-bold">
                            @if ( $user->user_type == 2 )
                                Admin
                            @elseif ( $user->user_type == 1 )
                                Collector
                            @else
                                Member
                            @endif
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col col-sm">Address</div>
                        <div class="col font-weight-bold">{{ $user->address }}</div>
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
                <button class="btn btn-outline-warning btn-block" type="button">Archive</button>
            </div>
            <div class="col">
                <a href="/admin/users/{{ $user->id }}/edit" class="btn btn-success btn-block edit-button" role="button">Edit</a>
            </div>
        </div> --}}
    </div>
</div>
@endsection