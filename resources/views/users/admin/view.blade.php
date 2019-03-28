@extends('layouts.app')

@section('title')
<title>Alkasnya - View user</title>
@endsection

@section('content')
<h3 class="header mt-2">View {{$user->lname}}'s Information</h3>
<div class="row">
    <div class="col">
        <div class="py-3">
            <a class="btn btn-light border" role="button" href="/admin/users"><i class="fas fa-arrow-left"></i>  Back to list</a>
            {{-- @if ($user->inactive == null || $user->inactive == false )
            <div class="float-right d-flex flex-row">
                <a href="/admin/users/{{ $user->id }}/inactive" class="btn btn-outline-secondary border mr-3" role="button" data-toggle="tooltip" data-placement="top" title="Archive" onclick="return confirm('Are you sure to archive this user?');"><i class="fas fa-archive"></i></button>
            </div>
            @else
            <div class="alert alert-warning ml-2">This user is inactive</div>   
                <div class="float-right d-flex flex-row">
                    <a href="/admin/users/{{ $user->id }}/active" class="btn btn-primary border mr-3" role="button" data-toggle="tooltip" data-placement="top" title="Set user as Active" onclick="return confirm('Set this user to active?');"><i class="fas fa-share-square"></i></button>
                    <a href="/admin/users/{{ $user->id }}/edit" class="btn btn-success btn-block edit-button" role="button" data-toggle="tooltip" data-placement="top" title="Edit User"><i class="fas fa-user-edit fa-lg"></i></a>
                </div>
            @endif --}}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>User information</h4>
                <div class="px-2">
                    <a href="/admin/users/{{ $user->id }}/edit" class="btn btn-success edit-button" role="button" data-toggle="tooltip" data-placement="top" title="Edit User"><i class="fas fa-user-edit fa-lg"></i></a>
                </div>
            </div>
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
                        <div class="col col-sm">Contact Number</div>
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
    </div>
</div>
@push('scripts')
    <script src="{{ asset('js/scripts.js') }}" defer></script>
@endpush
@endsection
        {{-- <div class="row mt-3">
            <div class="col">
                <button class="btn btn-outline-warning btn-block" type="button">Archive</button>
            </div>
            <div class="col">
                <a href="/admin/users/{{ $user->id }}/edit" class="btn btn-success btn-block edit-button" role="button">Edit</a>
            </div>
        </div> --}}