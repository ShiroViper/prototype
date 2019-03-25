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
            @if ($user->inactive == null || $user->inactive == false )
            <div class="float-right d-flex flex-row">
                <a href="/admin/users/{{ $user->id }}/inactive" class="btn btn-outline-secondary border mr-3" role="button" data-toggle="tooltip" data-placement="top" title="Archive" onclick="return confirm('Are you sure to archive this user?');"><i class="fas fa-archive"></i></button>
                <a href="/admin/users/{{ $user->id }}/edit" class="btn btn-success btn-block edit-button" role="button" data-toggle="tooltip" data-placement="top" title="Edit User"><i class="fas fa-user-edit fa-lg"></i></a>
            </div>
            @else
            <div class="alert alert-warning ml-2">This user is inactive</div>   
                <div class="float-right d-flex flex-row">
                    <a href="/admin/users/{{ $user->id }}/active" class="btn btn-primary border mr-3" role="button" data-toggle="tooltip" data-placement="top" title="Set user as Active" onclick="return confirm('Set this user to active?');"><i class="fas fa-share-square"></i></button>
                    <a href="/admin/users/{{ $user->id }}/edit" class="btn btn-success btn-block edit-button" role="button" data-toggle="tooltip" data-placement="top" title="Edit User"><i class="fas fa-user-edit fa-lg"></i></a>
                </div>
            @endif
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
@push('scripts')
    <script src="{{ asset('js/scripts.js') }}" defer></script>
@endpush
@endsection