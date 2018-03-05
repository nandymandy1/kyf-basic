<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Arvind App</title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{{ asset('./plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet">

    <!--Materialize-->
    <link rel="stylesheet" href="{{ asset('./css/materialize.css')}}">

    <!-- Waves Effect Css -->
    <link href="{{ asset('./plugins/node-waves/waves.css')}}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{{ asset('./plugins/animate-css/animate.css')}}" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="{{ asset('./css/style.css')}}" rel="stylesheet">
    <link href="{{ asset('./plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
</head>
    @yield('content')

    <!-- Scripts -->
    <!-- Jquery Core Js -->
    <script src="{{ asset('./plugins/jquery/jquery.min.js')}}"></script>

    <!-- Bootstrap Core Js -->
    <script src="{{ asset('./plugins/bootstrap/js/bootstrap.js')}}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{ asset('./plugins/node-waves/waves.js')}}"></script>

    <!-- Validation Plugin Js -->
    <script src="{{ asset('./plugins/jquery-validation/jquery.validate.js')}}"></script>

    <!-- Custom Js -->
    <script src="{{ asset('./js/admin.js')}}"></script>
    <script src="{{ asset('./js/particles.js')}}"></script>
    <script src="{{ asset('./js/particle.js')}}"></script>
    @yield('scripts')

</body>
</html>
