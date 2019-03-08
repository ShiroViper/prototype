@extends('layouts.app')

@section('title')
<title>Alkasnya - View {{$user->fname}}'s Info </title>
@endsection

@section('content')
<div class="row">
    <div class="col-sm col-md-10 col-xl-8">
        <h3 style="text-align:center;">View {{$user->fname}}'s Information</h3>
        <div class="py-3">
            @if(Auth::user()->user_type == 2)
                <a href="/admin/profile/{{$user->id}}/edit" class="btn btn-success float-right edit-button" role="button">Edit</a>
            @elseif(Auth::user()->user_type == 1)
                <a href="/collector/profile/{{$user->id}}/edit" class="btn btn-success float-right edit-button" role="button">Edit</a>
            @else
                <a href="/member/profile/{{$user->id}}/edit" class="btn btn-success float-right edit-button" role="button">Edit</a>
            @endif
            <br>
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
                        <div class="col font-weight-bold">{{ $user->lname }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">First Name</div>
                        <div class="col font-weight-bold">{{ $user->fname }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">Middle Name</div>
                        <div class="col font-weight-bold">{{ $user->mname }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">Email</div>
                        <div class="col font-weight-bold">{{ $user->email }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">Cell Number</div>
                        <div class="col font-weight-bold">{{ $user->cell_num }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">Role</div>
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
                        <div class="col">Address</div>
                        <div class="col font-weight-bold">{{ $user->address }}</div>
                    </div>
                    {{-- <div class="row">
                    </div> --}}
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