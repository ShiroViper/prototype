@guest
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

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

	<!-- App Scripts -->
  @include('inc.app-scripts')
</head>
    <body>
		@include('inc.navbar')
		@yield('content')
		@include('inc.footer')
	</body>
	@include('inc.scripts')
</html>	
@endguest