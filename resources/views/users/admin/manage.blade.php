@extends('layouts.app')

@section('title')
<title>Alkansya - Manage Users</title>
@endsection

@section('content')
<h3 class="mt-2">Manage Accounts</h3>
<div class="table-responsive">
    <table class="table table-hover mt-3">
        <thead>
            <tr>
                <th>Member ID</th>
                <th>Complete Name</th>
                <th>Email</th>
                <th>Role</th>
                {{-- <th>Action</th> --}}
            </tr>
        </thead>
        <tbody>
            @if (count($users) > 0)
                @foreach ($users as $user)
                        @if ( $user->user_type == 1 )
                            {{-- Highlight if they are collectors --}}
                            <tr class="manage-accounts clickable-row table-warning" data-href="/admin/users/{{ $user->id }}">
                        @else
                            <tr class="manage-accounts clickable-row" data-href="/admin/users/{{ $user->id }}">
                        @endif
                                <td>{{ $user->id }}</td>
                                <td>
                                    <?php
                                    echo $user->lname.", ".$user->fname." ".$user->mname;
                                    ?>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if ( $user->user_type == 1 )
                                        Collector
                                    @else
                                        Member
                                    @endif
                                </td>
                                {{-- <td>
                                    {!! Form::open(['action' => ['UsersController@destroy', $user->id], 'method' => 'POST']) !!}
                                        {{ Form::hidden('_method', 'DELETE') }}
                                        {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                                    {!! Form::close() !!}
                                </td> --}}
                            </tr>

                @endforeach
            @else
            <tr>
                <td colspan="100%" class="text-center"><h4 class="text-muted">No Entries Found</h4></td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-center mt-3">
    {{ $users->links() }}
</div>
@endsection