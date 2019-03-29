@extends('layouts.app')

@section('title')
<title>Alkasnya</title>
@endsection

@section('content')
@push('scripts')
    <script src="{{ asset('js/scripts.js') }}"></script>
@endpush
<div class="row full-view">
    <div class="col-sm col-md-6 col-xl-4">
        <h3 class="header pb-3">Change Password</h3>
        <form method="POST" action="{{ action('MemberController@change') }}">
        @csrf
        <div class="form-group">
            <label for="password">Old Password</label>
            <input name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" value="{{ isset($message) ? '' : old('password') }}" autofocus required>
            @if ( $errors->has('password') )
                <div class="invalid-feedback">{{ $errors->first('password') }}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="password_confirmation">New Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>
            <button type="submit" class="btn btn-primary px-4">Change Password</button>
        </form>
    </div>
</div>
@endsection