{{-- @extends('layouts.app')

@section('content') --}}
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="id" class="col-md-4 col-form-label text-md-right">{{ __('Member ID') }}</label>

                            <div class="col-md-6">
                                <input id="id" type="text" class="form-control{{ $errors->has('id') ? ' is-invalid' : '' }}" name="id" value="{{ old('id') }}" required autofocus>

                                @if ($errors->has('id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}

{{-- <div class="main-login">
    <div class="main-login-container mx-auto">
        <div class="container">
            <div class="d-flex flex-column align-items-center">
                <div class="login-logo m-3">
                    <a href="/">
                        <img src="{{asset('img/logo.png')}}" alt="" class="img-fluid">
                    </a>
                </div>
                <h5 class="login-title">Sign in</h5>

                @if ($message = Session::get('error'))
                    <div class="text-center pt-2">
                        <span class="text-danger text-center"><strong>{{ $message }}</strong></span><br>
                        <small class="text-danger">Incorrect Member ID or Password</small>
                    </div>
                @endif

                <div class="login-form-container w-100 clearfix">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <small>Member ID</small>
                        <input name="id" type="text" class="form-control{{ $errors->has('id') ? ' is-invalid' : '' }}" name="id" value="{{ old('id') }}" required autofocus>

                        @if ( $errors->has('id') )
                            <div class="invalid-feedback">{{ $errors->first('id') }}</div>
                        @endif

                        </div>
                        <div class="form-group">
                            <small>Password</small>
                            <input name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" value="{{ isset($message) ? '' : old('password') }}" autofocus required>
                            @if ( $errors->has('password') )
                                <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="pt-3 cleafix">
                            <a href="#">Having problems logging in?</a>
                            <button type="submit" class="btn btn-primary float-right px-4">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
{{-- @endsection --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Alkansya Login</title>

    <!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- Icon -->
	<link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon" />
	
	<!-- Page Title -->
	@yield('title')

	<!-- Fonts -->
	<link rel="dns-prefetch" href="//fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400|Open+Sans:300,400" rel="stylesheet">

	<!-- Styles -->
	<link rel="stylesheet" href="{{ asset('css/app.css')}}">
</head>
<body>
    <div class="main-login">
        <div class="main-login-container mx-auto">
            <div class="container">
                <div class="d-flex flex-column align-items-center">
                    <div class="login-logo m-3">
                        <a href="/">
                            <img src="{{asset('img/logo.png')}}" alt="" class="img-fluid">
                        </a>
                    </div>
                    <h5 class="login-title">Sign in</h5>

                    {{-- If the login is invalid --}}
                    @if ($message = Session::get('error'))
                        <div class="text-center pt-2">
                            <span class="text-danger text-center"><strong>{{ $message }}</strong></span><br>
                            <small class="text-danger">Incorrect Email or Password</small>
                        </div>
                    @endif

                    <div class="login-form-container w-100 clearfix">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <small>Email</small>
                            <input name="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                            @if ( $errors->has('email') )
                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @endif

                            </div>
                            <div class="form-group">
                                <small>Password</small>
                                <input name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" value="{{ isset($message) ? '' : old('password') }}" autofocus required>
                                @if ( $errors->has('password') )
                                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                @endif
                            </div>
                            {{-- <div class="row">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="pt-3 cleafix">
                                {{-- <small><a href="#">Having problems logging in?</a></small> --}}
                                <button type="submit" class="btn btn-primary btn-block float-right px-4">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>