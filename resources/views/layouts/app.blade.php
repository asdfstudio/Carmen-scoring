<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{csrf_token()}}" />

    <title>@section('title') Carmen Scoring System @show</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Bitter:400,700|Lato:100,300,400,700">

    <!-- Styles -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.3/css/selectize.bootstrap3.min.css">
    <link rel="stylesheet" href="/dist/css/vendor/dropzone.css">
    <link rel="stylesheet" href="/dist/css/app.css">
    @yield('style')

    <style>
        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">

    @yield('body-header')

    @yield('body-content')

    <!-- JavaScripts -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.3/js/standalone/selectize.min.js"></script>
    <script src="//unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
    <script src="/dist/js/vendor/jquery-ui/jquery-ui.min.js"></script>

    <!-- Built via webpack -->
	<script src="/dist/js/app.js"></script>

    @yield('body-footer')
    
    @stack('own-scripts')

</body>
</html>
</body>
</html>
