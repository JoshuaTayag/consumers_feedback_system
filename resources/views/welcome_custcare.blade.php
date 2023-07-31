<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&display=swap" rel="stylesheet">

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
        <link href="{{asset('build/assets/app-4cfe6a2c.css')}}" rel="stylesheet" />
        <script src="{{asset('build/assets/app-66e7f68a.js')}}"></script>

        <!-- Styles -->
        <style>
                .card {
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
            font-family: 'Libre Baskerville', serif;
        }
        </style>

        @googlefonts('code')

        
    </head>
    <body class="antialiased" class="bg-image" style="background-image: url('{{asset('images/background1.jpg')}}'); background-size: cover; overflow-x: hidden;">
        <!-- @if(session('success_message'))
            <div class="alert alert-success">
                {{session('success_message')}}
            </div>
        @endif -->
        <div class="row">
            <div class="col-lg-10 text-center mx-auto mt-5">
                <div class="card my-3 text-white" id="mask">
                    <div class="p-4 ">
                        <strong class="fs-1" style="font-size: 40px !important;">LEYECO V CONSUMER FEEDBACK SURVEY (CUSTCARE & CASHIER)</strong>
                    </div>
                </div>
                <div class="card my-3 text-white">
                    <div class="">
                        <div class="row">
                            <div class="col-lg-12 p-5">
                                <p class="text-start fs-1 mx-5">Are you satisfied with our services?</p>
                            </div>
                        </div>
                        <div class="row mb-5">
                            
                            <div class="col-lg-4">
                                <form method="post" action="{{url('store-survey-custcare')}}">
                                    @csrf
                                    <p class="fs-4">Satisfied</p>
                                    <input type="hidden" name="vote" value="2">
                                    <button type="submit" class="btn btn-default">
                                        <img src="{{asset('images/in-love.png')}}" class="img-fluid mx-auto" style="max-width:30%;" alt="...">
                                    </button>
                                </form>
                            </div>

                            <div class="col-lg-4">
                                <form method="post" action="{{url('store-survey-custcare')}}">
                                    @csrf
                                    <p class="fs-4">Fine</p>
                                    <input type="hidden" name="vote" value="1">
                                    <button type="submit" class="btn btn-default">
                                        <img src="{{asset('images/smiley.png')}}" class="img-fluid mx-auto" style="max-width:30%;" alt="...">
                                    </button>
                                </form>
                            </div>
                        
                            <div class="col-lg-4">
                                <form method="post" action="{{url('store-survey')}}">
                                    @csrf
                                    <p class="fs-4">Unsatisfied</p>
                                    <input type="hidden" name="vote" value="0">
                                    <button type="submit" class="btn btn-default">
                                        <img src="{{asset('images/sad.png')}}" class="img-fluid mx-auto" style="max-width:30%;" alt="...">
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('sweetalert::alert')
    </body>
</html>
