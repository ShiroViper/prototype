@extends('layouts.app')

@section('title')
<title>Alkasnya</title>
@endsection

@section('content')
@push('scripts')
    <script src="{{ asset('js/scripts.js') }}"></script>
@endpush
@prepend('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js"></script>
@endprepend 
<div class="row">
    <div class="col">
        <div class="py-3">
            <a class="btn btn-light border" role="button" href="/member/profile"><i class="fas fa-arrow-left"></i>  Back </a>
        </div>
    </div>
</div>
<div class="row full-view">
    <div class="col-sm col-md-6 col-xl-4">
        <h3 class="header pb-3">Change Password</h3>
        <form method="POST" action="{{ action('MemberController@change') }}">
        @csrf
        <div class="form-group">
            <label for="password">Old Password</label>
            <input name="old_password" type="password" class="form-control" autofocus required>
        </div>
        <div class="form-group">
            <label for="password">New Password</label>
            <input name="password" type="password" id="password" onChange="passwordChecker();" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" value="{{ isset($message) ? '' : old('password') }}" autofocus required>
            @if ( $errors->has('password') )
                <div class="invalid-feedback">{{ $errors->first('password') }}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="cpassword" onChange="passwordChecker();" name="password_confirmation" id="password_confirmation" class="form-control" required>
            <div id="password-checker"></div>
        </div>
            <button type="submit" class="btn btn-primary px-4 member-setup-btn" disabled>Change Password</button>
        </form>
    </div>
</div>
@endsection