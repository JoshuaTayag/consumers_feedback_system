<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LEYTE V ELECTRIC COOPERATIVE, INC.') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="{{asset('fontawesome-free-6.4.0-web/css/all.min.css')}}" rel="stylesheet">
    
    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    @yield('style')
    <style>
      .dropdown-menu li {
      position: relative;
      }
      .dropdown-menu .dropdown-submenu {
      display: none;
      position: absolute;
      left: 100%;
      top: -7px;
      }
      .dropdown-menu .dropdown-submenu-left {
      right: 100%;
      left: auto;
      }
      .dropdown-menu > li:hover > .dropdown-submenu {
      display: block;
      }
      thead tr th {
        background-color: #006DCC !important;
        color: rgb(0, 0, 0) !important;
      }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                {{-- <a class="navbar-brand" href="{{ url('home') }}">
                    LEYTE V ELECTRIC COOPERATIVE, INC.
                </a> --}}
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    {{-- <ul class="navbar-nav me-auto">
                        <li>
                        <a class="dropdown-item" href="#">Action</a>
                        </li>
                    </ul> --}}
                    <ul class="navbar-nav">
                        <!-- Dropdown -->
                        <li class="nav-item dropdown">
                          <a id="navbarPowerServe" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            POWERSERVE
                          </a>
                          <ul class="dropdown-menu" aria-labelledby="navbarPowerServe">
                            <li>
                              <a class="dropdown-item" href="#">Action</a>
                            </li>
                            <li>
                              <a class="dropdown-item" href="#">Another action</a>
                            </li>
                            <li>
                              <a class="dropdown-item" href="#">
                                HOUSE WIRING &raquo;
                              </a>
                              <ul class="dropdown-menu dropdown-submenu">
                                <li>
                                  <a class="dropdown-item" href="{{ route('pre_membership_index') }}">PRE-MEMBERSHIP</a>
                                </li>
                                <li>
                                  <a class="dropdown-item" href="{{ route('membership.index') }}">MEMBERSHIP</a>
                                </li>
                                <li>
                                  <a class="dropdown-item" href="#">APPLICATION</a>
                                </li>
                                <li>
                                  <a class="dropdown-item" href="#">Submenu item 3 &raquo; </a>
                                  <ul class="dropdown-menu dropdown-submenu">
                                    <li>
                                      <a class="dropdown-item" href="#">Multi level 1</a>
                                    </li>
                                    <li>
                                      <a class="dropdown-item" href="#">Multi level 2</a>
                                    </li>
                                  </ul>
                                </li>
                                
                                <li>
                                  <a class="dropdown-item" href="#">Submenu item 5</a>
                                </li>
                              </ul>
                            </li>
                          </ul>
                        </li>

                        <li class="nav-item dropdown">
                          <a id="navbarPowerPay" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            POWERPAY
                          </a>
                          <ul class="dropdown-menu" aria-labelledby="navbarPowerPay">
                            <li>
                              <a class="dropdown-item" href="#">Action</a>
                            </li>
                            <li>
                              <a class="dropdown-item" href="#">Another action</a>
                            </li>
                            <li>
                              <a class="dropdown-item" href="#">
                                HOUSE WIRING &raquo;
                              </a>
                              <ul class="dropdown-menu dropdown-submenu">
                                <li>
                                  <a class="dropdown-item" href="{{ route('pre_membership_index') }}">PRE-MEMBERSHIP</a>
                                </li>
                                <li>
                                  <a class="dropdown-item" href="{{ route('membership.index') }}">MEMBERSHIP</a>
                                </li>
                                <li>
                                  <a class="dropdown-item" href="#">APPLICATION</a>
                                </li>
                                <li>
                                  <a class="dropdown-item" href="#">Submenu item 3 &raquo; </a>
                                  <ul class="dropdown-menu dropdown-submenu">
                                    <li>
                                      <a class="dropdown-item" href="#">Multi level 1</a>
                                    </li>
                                    <li>
                                      <a class="dropdown-item" href="#">Multi level 2</a>
                                    </li>
                                  </ul>
                                </li>
                                
                                <li>
                                  <a class="dropdown-item" href="#">Submenu item 5</a>
                                </li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                      </ul>
                    

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            @yield('content')
        </main>
    </div>
    @include('sweetalert::alert')
</body>
@yield('script')
</html>
