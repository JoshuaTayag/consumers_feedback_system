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

    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
    <link href="{{asset('build/assets/app-4cfe6a2c.css')}}" rel="stylesheet" />
    <script src="{{asset('build/assets/app-66e7f68a.js')}}"></script>

    <style>
        .card-transparent {
            margin-right: auto;
            margin-left: auto;
            /* width: 250px; */
            /* box-shadow: 0 15px 25px rgba(129, 124, 124, 0.2); */
            /* height: 300px; */
            border-radius: 5px;
            border-color: #e5e5e54a;
            backdrop-filter: blur(10px);
            background-color: rgba(38, 38, 38, 0.3);
            padding: 10px;
            text-align: center;
        }
        body{
            font-family: 'Belanosima', sans-serif;
            background-image: url('{{asset('images/background2.jpg')}}'); 
            background-size: cover; 
            overflow-x: hidden;
        }
    </style>
</head>
<body>
    <div id="app">
        <main class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 fw-bold pt-5" style="font-family: 'Bacasime Antique', serif;">
                        <div class="row">
                            <div class="col-md-10 mx-auto">
                            <img src="{{asset('images/logo.png')}}" class="img-fluid mx-auto" style="max-width:25%;" alt="..."><span class="" style="font-size: 50px;"> LEYECO V </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10 mx-auto fs-3">
                            <ul class="">
                                <li>Consumers Feedback Survey</li>
                                <li>Membership / Pre-membership</li>
                                <li>Lifeline</li>
                                <li>MER</li>
                                <li>Barangay Electrician</li>
                            </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card py-4">
                            <!-- <div class="card-header">{{ __('Login') }}</div> -->

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-10 mx-auto">
                                        <h1><strong>Welcome Back</strong></h1>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-10 mx-auto">
                                        <span class="text-muted">Login to continue | <a href="{{ route('register') }}">I don't have an account</a></span>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="row mb-2">
                                        <div class="col-md-10 mx-auto">
                                            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Address" autofocus>

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-10 mx-auto">
                                            <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-10 mx-auto">
                                            <button type="submit" class="btn btn-primary col-md-12">
                                                {{ __('Login') }}
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-10 mx-auto">
                                            @if (Route::has('password.request'))
                                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                                    {{ __('Forgot Your Password?') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row my-2">
                                        <div class="col-md-10 mx-auto text-center">
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-10 mx-auto text-center">
                                            <div class="row">
                                                <div class="col-md-6 pr-4">
                                                    <a type="submit" class="btn btn-secondary col-md-12">
                                                    <img src="{{asset('images/icons/google.png')}}" class="img-fluid mx-auto" style="max-width:15%;" alt="...">
                                                        {{ __('Google') }}
                                                    </a>
                                                </div>
                                                <div class="col-md-6 pl-1">
                                                    <a type="submit" class="btn btn-primary col-md-12">
                                                    <img src="{{asset('images/icons/facebook.png')}}" class="img-fluid mx-auto" style="max-width:15%;" alt="...">
                                                        {{ __('Facebook') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    
                                </form>
                                    <!-- <div class="row mb-3">
                                        <div class="col-md-6 offset-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                <label class="form-check-label" for="remember">
                                                    {{ __('Remember Me') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>