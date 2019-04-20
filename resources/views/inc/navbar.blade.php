@guest
    <div class="navbar navbar-expand-md navbar-light bg-light  welcome-nav border-bottom">
        <div class="container">
            <div class="navbar-brand" style="width: 40%; max-width: 200px;">
            <a href="/">
                <img src="{{ asset('img/logo.png') }}" alt="" class="img-fluid">
            </a>
            </div>
            <button class="navbar-toggler" type="button"  data-toggle="collapse" data-target="#indexNav" aria-controls="indexNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="indexNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item{{ isset($active) && $active == 'welcome' ? ' active' : '' }}">
                        <a href="/" class="nav-link h6 mb-0">Home</a>
                    </li>
                    <li class="nav-item{{ isset($active) ? '' : ' active' }}">
                        <a href="/login" class="nav-link h6 mb-0">Sign In</a>
                    </li>
                    <li class="nav-item{{ isset($active) && $active == 'about' ? ' active' : '' }}">
                        <a href="/about" class="nav-link h6 mb-0">Who we are</a>
                    </li>
                    {{-- <li class="nav-item{{ isset($active) && $active == 'terms' ? ' active' : '' }}">
                        <a href="/terms" class="nav-link h6 mb-0">Terms and Conditions</a>
                    </li> --}}
                </ul>
            </div>
        </div>
    </div>
@else
<nav class="navbar navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#" style="width: 40%; max-width: 200px;">
        <img src="{{ asset('img/logo.png') }}" class="img-fluid" alt="logo">
        </a>
        <div class="current-user-container ml-auto">
            <div class="dropdown">
                <a class="h6 text-decoration-none text-capitalize dropdown-toggle" href="#" role="button" id="currentUser" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   {{ Auth::user()->lname }}, {{ Auth::user()->fname }} {{Auth::user()->mname}} 
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="currentUser">
                    {{-- <a href="/admin/terms" class="dropdown-item">Terms and Conditions</a> --}}
                    {{-- <a href="" class="dropdown-item">Settings</a> --}}
                    {{-- <div class="dropdown-divider"></div> --}}
                    @if(Auth::user()->user_type == 2)
                        <a class="dropdown-item" href="/admin/profile">Profile</a>
                    @elseif(Auth::user()->user_type == 1)
                        <a class="dropdown-item" href="/collector/profile">Profile</a>
                    @else
                        <a class="dropdown-item" href="/member/profile">Profile</a>
                    @endif
                    <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

@if(Auth::user()->inactive != 1)
    <nav class="navbar navbar-dark bg-dark border-bottom navbar-expand-xl">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menuNav" aria-controls="menuNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menuNav">
                <ul class="navbar-nav">
                {{-- Checks the type of User if .. --}}
                    {{-- ADMINISTRATOR --}}
                    @if (Auth::user()->user_type == 2)
                        <li class="nav-item px-3 h6">
                            {{-- Formerly Transactions --}}
                            <a class="nav-link{{ $active == 'dashboard' ? ' active callout' : '' }}" href="/admin/dashboard">Dashboard</a>
                        </li>
                        <li class="nav-item px-3 h6">
                            <a class="nav-link{{ $active == 'requests' ? ' active callout' : '' }}" href="/admin/requests">Requests 
                                @if (isset($counter) && $counter > 0)
                                    <span class="badge badge-pill badge-danger">
                                        {{ $counter }}
                                    </span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item px-3 h6">
                            <a class="nav-link{{ $active == 'create' ? ' active callout' : '' }}" href="/admin/users/create">
                                <!-- <span class="sr-only">(current)</span> -->
                                Add Member
                            </a>
                        </li>
                        <li class="nav-item px-3 h6">
                            <a class="nav-link{{ $active == 'manage' ? ' active callout' : '' }}" href="/admin/users">Manage</a>
                        </li>
                        <li class="nav-item px-3 h6">
                            <a class="nav-link{{ $active == 'sched' ? ' active callout' : '' }}" href="/admin/calendar">Calendar</a>
                        </li>
                        <li class="nav-item px-3 h6">
                            <a class="nav-link {{$active == 'deliquent' ? 'active callout' : ''}} " href="/admin/deliquent">Deliquent</a>
                        </li>
                        {{-- <li class="nav-item px-3 h6">
                            <a class="nav-link{{ $active == 'status' ? ' active callout' : '' }}" href="/admin/status">Status</a>
                        </li> --}}
                        
                    {{-- COLLECTOR --}}
                    @elseif (Auth::user()->user_type == 1)
                        <li class="nav-item px-3 h6">
                            <a class="nav-link{{ $active == 'dashboard' ? ' active callout' : '' }}" href="/collector/transaction">Transactions</a>
                        </li>
                        <li class="nav-item px-3 h6">
                            <a class="nav-link{{ $active == 'collect' ? ' active callout' : '' }}" href="/collector/transaction/create">Collection</a>
                        </li>
                        <li class="nav-item px-3 h6">
                            <a class="nav-link{{ $active == 'deliquent' ? ' active callout' : '' }}" href="/collector/deliquent">Deliquent</a>
                        </li>
                        <li class="nav-item px-3 h6">
                            <a class="nav-link{{ $active == 'request' ? ' active callout' : '' }}" href="/collector/process">Requests</a>
                        </li>
                        <li class="nav-item px-3 h6">
                            <a class="nav-link{{ $active == 'status' ? ' active callout' : '' }}" href="/collector/status">Status</a>
                        </li>
                    {{-- MEMBER --}}
                    @else
                        <li class="nav-item px-3 h6">
                            <a class="nav-link{{ $active == 'dashboard' ? ' active callout' : '' }}" href="/member/dashboard">Dashboard</a>
                        </li>
                        @if(Auth::user()->setup != NULL)
                            <li class="nav-item px-3 h6">
                                <a class="nav-link{{ $active == 'transactions' ? ' active callout' : '' }}" href="/member/transactions">Transactions</a>
                            </li>
                            <li class="nav-item px-3 h6">
                                <a class="nav-link{{ $active == 'loan' ? ' active callout' : '' }}" href="/member/requests/create">Loan</a>
                            </li>
                            <li class="nav-item px-3 h6">
                                <a class="nav-link{{ $active == 'requests' ? ' active callout' : '' }}" href="/member/requests">Requests</a>
                            </li>
                        @endif
                    @endif
                {{-- END IF STATEMENT --}}
                </ul>
            </div>
        </div>
    </nav>
@endif
@endguest