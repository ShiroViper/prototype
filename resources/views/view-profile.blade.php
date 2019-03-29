@extends('layouts.app')

@section('title')
<title>Alkasnya - View {{$user->fname}}'s Info </title>
@endsection

@section('content')
@push('scripts')
    <script src="{{ asset('js/scripts.js') }}"></script>
@endpush
<div class="row">
    <div class="col-sm col-md-10 col-xl-8">
        <div class="d-flex flex-row">
            <h3 class="header">{{$user->fname}}'s Profile</h3>
            @if (Auth::user()->user_type == 1)
                <div class="dropdown">
                    <button class="btn dropdown-toggle ml-3 btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-cog"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="change_pass">Change Password</a>
                        <a class="dropdown-item" href="cancel">Deactivate Account</a>
                    </div>
                </div>
            @elseif (Auth::user()->user_type == 0)
                <div class="dropdown">
                    <button class="btn dropdown-toggle ml-3 btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-cog"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="change_pass">Change Password</a>
                        <a class="dropdown-item" href="cancel">Deactivate Account</a>
                    </div>
                </div>
            @else
                <div class="dropdown">
                    <button class="btn dropdown-toggle ml-3 btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-cog"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="change_pass">Change Password</a>
                    </div>
                </div>
            @endif
        </div>
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="header">User information</h5>
                <div class="px-2">
                    @if(Auth::user()->user_type == 2)
                        <a href="/admin/profile/{{$user->id}}/edit" class="btn btn-success edit-button" role="button" data-toggle="tooltip" data-placement="top" title="Edit User"><i class="fas fa-user-edit fa-lg"></i></a><br>
                    @elseif(Auth::user()->user_type == 1)
                        <a href="/collector/profile/{{$user->id}}/edit" class="btn btn-success edit-button" role="button" data-toggle="tooltip" data-placement="top" title="Edit User"><i class="fas fa-user-edit fa-lg"></i></a><br>
                    @else
                        <a href="/member/profile/{{$user->id}}/edit" class="btn btn-success edit-button" role="button" data-toggle="tooltip" data-placement="top" title="Edit User"><i class="fas fa-user-edit fa-lg"></i></a><br>
                    @endif
                </div>
            </div>
            {{-- <h6 class="card-header">User information</h6> --}}
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
                @if($user->mname != NULL)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col">Middle Name</div>
                            <div class="col font-weight-bold">{{ $user->mname }}</div>
                        </div>
                    </li>
                @endif
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">Email</div>
                        <div class="col font-weight-bold">{{ $user->email }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">Contact Number</div>
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