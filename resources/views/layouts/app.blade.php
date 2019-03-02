<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,500,900" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.css"/>
    {{-- <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css')}}"> --}}

    <!-- Scripts -->
    @include('inc.scripts')
    
</head>
<body>
    @include('inc.navbar')
    <main class="main-content py-3">
        @include('inc.messages')
        <div class="container">
            @yield('content')
        </div>
    </main>
    @include('inc.footer')
</body>
</html>
