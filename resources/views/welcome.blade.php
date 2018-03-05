<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>KYF App</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ asset('./css/app.css')}}">
        <link rel="stylesheet" media="screen" href="{{ asset('./css/particle.css') }}">
        <link href="{{ asset('./plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ asset('./css/materialize.css') }}">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <style media="screen">
        .jumbotron {
               background: rgb(200, 54, 54); /* This is for ie8 and below */
               background: rgba(200, 54, 54, .0);
               color: #fff;
            }
        i {
            display: block;
            position: absolute;
            width: 30px;
            height: 30px;
            top: 30px;
            left: 30px;
            background: url(http://i.imgur.com/lOBxb.png);
            -webkit-animation: barrelRoll 2s infinite linear;
            -webkit-animation-play-state: paused;
        }

        i:last-of-type {
            top: 22px;
            left: 56px;
            -webkit-animation-name: invertBarrelRoll;
        }

        div:hover > i {
            -webkit-animation-play-state: running;
        }

        @-webkit-keyframes barrelRoll {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @-webkit-keyframes invertBarrelRoll {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(-360deg); }
        }
        </style>
    </head>
    <body>
      <!-- particles.js container -->
      <div id="particles-js">
        <nav class="navbar navbar-expand-md navbar navbar-dark bg-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <b>Arvind App</b>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @if (Route::has('login'))
                                @auth
                                    <a class="nav-link" href="{{ url('/home') }}">Home</a>
                                @else
                                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                                @endauth
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
      </div><!-- Particle js DIV -->
      <script src="{{ asset('./js/jquery-3.2.1.slim.min.js')}}" charset="utf-8"></script>
      <script src="{{ asset('./js/popper.min.js')}}" charset="utf-8"></script>
      <script src="{{ asset('./js/bootstrap.min.js') }}" charset="utf-8"></script>
      <script src="{{ asset('./js/particles.js')}}"></script>
      <script src="{{ asset('./js/particle.js')}}"></script>
    </body>
</html>
